@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')

    <a href="{{ route('admin.orders.index') }}" class="text-sm text-ink/50 hover:text-accent">&larr; Back to orders</a>

    <div class="flex items-center justify-between mt-4 mb-8">
        <h1 class="font-display text-2xl font-semibold">{{ $order->order_number }}</h1>

        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex items-center gap-2">
            @csrf @method('PATCH')
            <select name="status" onchange="this.form.submit()" class="border-hairline text-sm focus:border-accent focus:ring-accent">
                @foreach(['pending','paid','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid md:grid-cols-[1fr_320px] gap-10">

        <div>
            <h2 class="font-mono text-xs uppercase text-ink/50 mb-3">Items</h2>
            <div class="border border-hairline divide-y divide-hairline">
                @foreach ($order->items as $item)
                    <div class="flex justify-between px-4 py-3 text-sm">
                        <div>
                            <p>{{ $item->product_name }}</p>
                            <p class="text-ink/50 text-xs">Qty: {{ $item->quantity }} × ₱{{ number_format($item->price, 2) }}</p>
                        </div>
                        <p class="font-mono">₱{{ number_format($item->subtotal(), 2) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 space-y-1 text-sm text-right">
                <p>Subtotal: <span class="font-mono">₱{{ number_format($order->subtotal, 2) }}</span></p>
                @if($order->discount > 0)
                    <p class="text-accent">Discount ({{ $order->coupon?->code }}): <span class="font-mono">-₱{{ number_format($order->discount, 2) }}</span></p>
                @endif
                <p class="font-display text-lg">Total: ₱{{ number_format($order->total, 2) }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <h2 class="font-mono text-xs uppercase text-ink/50 mb-2">Customer</h2>
                <p class="text-sm">{{ $order->customerName() }}</p>
                <p class="text-sm text-ink/60">{{ $order->user->email ?? $order->guest_email }}</p>
            </div>

            <div>
                <h2 class="font-mono text-xs uppercase text-ink/50 mb-2">Shipping address</h2>
                <p class="text-sm text-ink/70">{{ $order->shipping_address }}</p>
            </div>

            @if($order->payment)
                <div>
                    <h2 class="font-mono text-xs uppercase text-ink/50 mb-2">Payment</h2>
                    <p class="text-sm">{{ ucfirst($order->payment->method) }} · {{ ucfirst($order->payment->status) }}</p>
                    <p class="text-xs font-mono text-ink/40 mt-1">{{ $order->payment->transaction_id }}</p>
                </div>
            @endif
        </div>

    </div>

@endsection