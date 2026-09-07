@extends('layouts.storefront')

@section('title', 'My Orders')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">

    <h1 class="font-display text-3xl font-semibold mb-8">My orders</h1>

    @if($orders->isEmpty())
        <div class="border border-hairline py-20 text-center">
            <p class="font-display text-xl">No orders yet</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 text-sm text-accent hover:underline">
                Start shopping →
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
            <a href="{{ route('orders.show', $order) }}"
               class="block border border-hairline p-5 hover:border-accent transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-mono text-sm font-semibold text-ink group-hover:text-accent transition">
                            {{ $order->order_number }}
                        </p>
                        <p class="text-xs text-ink/50 mt-0.5">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-mono text-sm font-semibold">₱{{ number_format($order->total, 2) }}</p>
                        <span class="inline-flex mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium capitalize {{ $order->statusClasses() }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
                <div class="mt-3 text-xs text-ink/50">
                    {{ $order->items->count() }} item(s) ·
                    {{ $order->items->pluck('product_name')->take(2)->implode(', ') }}{{ $order->items->count() > 2 ? '...' : '' }}
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $orders->links() }}</div>
    @endif

</div>
@endsection
