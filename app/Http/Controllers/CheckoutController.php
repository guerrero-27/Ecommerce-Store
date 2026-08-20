<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    private function getCurrentCart(): ?Cart
    {
        if (auth()->check()) {
            return Cart::with('items.product')->firstWhere('user_id', auth()->id());
        }

        if (session()->has('cart_session_id')) {
            return Cart::with('items.product')->firstWhere('session_id', session('cart_session_id'));
        }

        return null;
    }

    public function index()
    {
        $cart = $this->getCurrentCart();

        abort_if(!$cart || $cart->items->isEmpty(), 404, 'Your cart is empty.');

        $coupon = session()->has('coupon_id') ? Coupon::find(session('coupon_id')) : null;
        $discount = $coupon ? $coupon->calculateDiscount($cart->total()) : 0;
        $total = $cart->total() - $discount;

        return view('storefront.checkout', compact('cart', 'coupon', 'discount', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name' => 'required_without:auth|string|max:255',
            'guest_email' => 'required_without:auth|email|max:255',
            'shipping_address' => 'required|string|max:500',
        ]);

        $cart = $this->getCurrentCart();
        abort_if(!$cart || $cart->items->isEmpty(), 404, 'Your cart is empty.');

        // Re-validate stock before creating the order — stock may have changed since browsing
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->withErrors([
                    'stock' => "Only {$item->product->stock} of {$item->product->name} left in stock.",
                ]);
            }
        }

        $coupon = session()->has('coupon_id') ? Coupon::find(session('coupon_id')) : null;
        $subtotal = $cart->total();

        // Re-validate coupon at the moment of purchase — never trust earlier session state alone
        if ($coupon && !$coupon->isValid($subtotal)) {
            session()->forget('coupon_id');
            $coupon = null;
        }

        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $total = $subtotal - $discount;

        // Everything below must succeed together, or nothing should be saved
        $order = DB::transaction(function () use ($request, $cart, $coupon, $subtotal, $discount, $total) {

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'guest_name' => auth()->check() ? null : $request->guest_name,
                'guest_email' => auth()->check() ? null : $request->guest_email,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'coupon_id' => $coupon?->id,
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name, // snapshot
                    'price' => $item->product->price,        // snapshot
                    'quantity' => $item->quantity,
                ]);

                // Deduct stock immediately (reserve it) — freed back if payment fails/expires
                $item->product->decrement('stock', $item->quantity);

                InventoryLog::create([
                    'product_id' => $item->product_id,
                    'change' => -$item->quantity,
                    'reason' => 'sale',
                    'stock_after' => $item->product->fresh()->stock,
                ]);
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            return $order;
        });

        // Create the Stripe Checkout Session
        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = $order->items->map(fn ($item) => [
            'price_data' => [
                'currency' => 'php',
                'product_data' => ['name' => $item->product_name],
                'unit_amount' => (int) round($item->price * 100), // Stripe uses smallest currency unit (centavos)
            ],
            'quantity' => $item->quantity,
        ])->toArray();

        // Represent the coupon discount as a negative line item, since Stripe Checkout
        // line items can't go negative individually — we handle this via a coupon on the session instead
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'customer_email' => auth()->user()->email ?? $request->guest_email,
            'success_url' => route('checkout.success', ['order' => $order->order_number]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.index'),
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
        ]);

        // If there's a discount, apply it as a Stripe coupon on the session total instead of per-line-item
        if ($discount > 0) {
            $stripeCoupon = \Stripe\Coupon::create([
                'amount_off' => (int) round($discount * 100),
                'currency' => 'php',
                'duration' => 'once',
            ]);

            $session = StripeSession::create(array_merge(
                ['discounts' => [['coupon' => $stripeCoupon->id]]],
                $session->toArray()
            ));
        }

        $order->update(['stripe_session_id' => $session->id]);

        session()->forget(['coupon_id', 'cart_session_id']);
        $cart->items()->delete(); // clear the cart now that the order has been created

        return redirect($session->url);
    }

    public function success(Request $request, Order $order)
    {
        // Verify payment actually completed before showing success — never trust the redirect alone
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = StripeSession::retrieve($request->session_id);

        if ($session->payment_status === 'paid' && $order->status === 'pending') {
            $order->update(['status' => 'paid']);

            Payment::create([
                'order_id' => $order->id,
                'method' => 'stripe',
                'status' => 'completed',
                'transaction_id' => $session->payment_intent,
                'amount' => $order->total,
            ]);
        }

        return view('storefront.checkout-success', compact('order'));
    }
}