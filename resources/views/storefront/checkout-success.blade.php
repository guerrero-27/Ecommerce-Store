@extends('layouts.storefront')

@section('title', 'Order Confirmed')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-16 text-center">

    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="font-display text-3xl font-semibold mb-2">Order confirmed!</h1>
    <p class="text-ink/60 mb-1">Thank you for your purchase.</p>
    <p class="font-mono text-sm text-accent mb-8">{{ $order->order_number }}</p>

    <div class="border border-hairline text-left mb-8">
        <div class="px-6 py-4 border-b border-hairline">
            <h2 class="font-display text-lg font-semibold">Order summary</h2>
        </div>

        <div class="divide-y divide-hairline">
            @foreach ($order->items as $item)
            <div class="flex justify-between px-6 py-3 text-sm">
                <span class="text-ink/70">{{ $item->product_name }} × {{ $item->quantity }}</span>
                <span class="font-mono">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-hairline space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-ink/60">Subtotal</span>
                <span class="font-mono">₱{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount > 0)
            <div class="flex justify-between text-accent">
                <span>Discount</span>
                <span class="font-mono">-₱{{ number_format($order->discount, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between font-display text-lg pt-2 border-t border-hairline">
                <span>Total</span>
                <span>₱{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-hairline text-sm text-ink/60 space-y-1">
            <p><span class="font-medium text-ink">Shipping to:</span> {{ $order->shipping_address }}</p>
            @if($order->guest_email)
            <p><span class="font-medium text-ink">Confirmation sent to:</span> {{ $order->guest_email }}</p>
            @endif
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        @auth
        <a href="{{ route('orders.index') }}"
           class="border border-hairline px-6 py-3 text-sm hover:border-accent hover:text-accent transition">
            View my orders
        </a>
        @endauth
        <a href="{{ route('products.index') }}"
           class="bg-ink text-paper px-6 py-3 text-sm hover:bg-accent transition">
            Continue shopping
        </a>
    </div>

</div>
@endsection
