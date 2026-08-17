<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use illuminate\Support\Str;

class CartController extends Controller
{
    private function getCurrentCart(): Cart
    {
        if (auth()->check()){
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }

        $sessionId = session()->get('cart_session_id');

        if (!$sessionId){
            $sessionId = Str::uuid();
            session()->put('cart_session_id', $sessionId);
        }

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function index()
    {
        $cart = $this->getCurrentCart();
        $cart->load('items.product');

        return view('storefront.cart', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        abort_unless($product->inStock(), 422, 'Product is out of stock.');

        $cart = $this->getCurrentCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item){
            $item->increment('quantity', $request->quantity);
        }else{
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Added to cart');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Item removed.');
    }
}
