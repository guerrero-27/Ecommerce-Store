<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e){
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed'){
            $session = $event->data->object;
            $order = Order::find($session->metadata->order_id);

            if ($order && $order->status === 'pending'){
                $order->update(['status' => 'paid']);

                Payment::firstOrCreate(
                    ['order_id' => $order->id],
                    [
                        'method' => 'stripe',
                        'status' => 'completed',
                        'transaction_id' => $session->payment_intent,
                        'amount' => $order->total,
                    ]
                );
            }
        }

        return response('Webhook Handled', 200);
    }
}
