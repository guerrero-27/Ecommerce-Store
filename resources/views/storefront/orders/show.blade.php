@extends('layouts.storefront')

@section('title', $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">

    <a href="{{ route('orders.index') }}" class="text-sm text-ink/50 hover:text-accent">&larr; My orders</a>

    <div class="flex items-start justify-between mt-6 mb-8">
        <div>
            <h1 class="font-display text-2xl font-semibold">{{ $order->order_number }}</h1>
            <p class="text-sm text-ink/50 mt-1">Placed on {{ $order->created_at->format('d M Y, g:i A') }}</p>
        </div>
        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium capitalize {{ $order->statusClasses() }}">
            {{ $order->status }}
        </span>
    </div>

    {{-- Items --}}
    <div class="border border-hairline mb-6">
        <div class="px-5 py-4 border-b border-hairline">
            <h2 class="font-display text-lg font-semibold">Items</h2>
        </div>
        <div class="divide-y divide-hairline">
            @foreach ($order->items as $item)
            <div class="flex justify-between px-5 py-4 text-sm">
                <div>
                    <p class="font-medium">{{ $item->product_name }}</p>
                    <p class="text-ink/50 text-xs mt-0.5">₱{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                </div>
                <span class="font-mono">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach
        </div>
        <div class="px-5 py-4 border-t border-hairline space-y-2 text-sm">
            <div class="flex justify-between text-ink/60">
                <span>Subtotal</span>
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
    </div>

    {{-- Shipping --}}
    <div class="border border-hairline px-5 py-4 text-sm">
        <h2 class="font-display text-lg font-semibold mb-2">Shipping address</h2>
        <p class="text-ink/70 whitespace-pre-line">{{ $order->shipping_address }}</p>
    </div>

</div>
@endsection
