@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="px-8 py-7">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← Orders</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-mono font-semibold text-gray-700">{{ $order->order_number }}</span>
    </div>

    <div class="flex flex-wrap items-start justify-between gap-4 mb-7">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</h1>
            <p class="text-sm text-gray-400 mt-1">
                Placed {{ $order->created_at->format('d M Y, g:i A') }}
                · {{ $order->created_at->diffForHumans() }}
            </p>
        </div>

        {{-- Status updater --}}
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex items-center gap-2">
            @csrf @method('PATCH')
            <select name="status" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
                @foreach(['pending','paid','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            @php
                $statusColors = [
                    'pending'    => 'bg-yellow-100 text-yellow-700',
                    'paid'       => 'bg-blue-100 text-blue-700',
                    'processing' => 'bg-indigo-100 text-indigo-700',
                    'shipped'    => 'bg-purple-100 text-purple-700',
                    'delivered'  => 'bg-green-100 text-green-700',
                    'cancelled'  => 'bg-red-100 text-red-700',
                ];
            @endphp
            <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-semibold capitalize
                         {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                {{ $order->status }}
            </span>
        </form>
    </div>

    <div class="grid lg:grid-cols-[1fr_320px] gap-6">

        {{-- LEFT COLUMN --}}
        <div class="space-y-6">

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900">Order Items</h2>
                    <span class="text-xs text-gray-400">{{ $order->items->count() }} item(s)</span>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                            <th class="px-6 py-3 text-left font-medium">Product</th>
                            <th class="px-6 py-3 text-right font-medium">Unit Price</th>
                            <th class="px-6 py-3 text-right font-medium">Qty</th>
                            <th class="px-6 py-3 text-right font-medium">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($order->items as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                                @if($item->product)
                                    <a href="{{ route('admin.products.edit', $item->product) }}"
                                       class="text-xs text-gray-400 hover:text-gray-700 mt-0.5 inline-block">
                                        SKU: {{ $item->product->sku }}
                                    </a>
                                @else
                                    <span class="text-xs text-gray-300">Product deleted</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-gray-600">₱{{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 text-right text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-right font-mono font-semibold text-gray-900">₱{{ number_format($item->subtotal(), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Totals --}}
                <div class="px-6 py-4 border-t border-gray-100 space-y-2 bg-gray-50/50">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-mono">₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-sm text-green-600">
                        <span class="flex items-center gap-1.5">
                            Discount
                            @if($order->coupon)
                                <span class="font-mono text-xs bg-green-100 px-1.5 py-0.5 rounded">{{ $order->coupon->code }}</span>
                            @endif
                        </span>
                        <span class="font-mono">-₱{{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-200">
                        <span>Total</span>
                        <span class="font-mono">₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Payment</h2>
                </div>
                <div class="px-6 py-5">
                    @if($order->payment)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Method</p>
                            <p class="text-sm font-semibold text-gray-800 capitalize">{{ $order->payment->method }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $order->payment->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($order->payment->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Amount</p>
                            <p class="text-sm font-semibold text-gray-800 font-mono">₱{{ number_format($order->payment->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Date</p>
                            <p class="text-sm text-gray-600">{{ $order->payment->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    @if($order->payment->transaction_id)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Transaction ID</p>
                        <p class="font-mono text-xs text-gray-500 break-all">{{ $order->payment->transaction_id }}</p>
                    </div>
                    @endif
                    @if($order->stripe_session_id)
                    <div class="mt-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Stripe Session</p>
                        <p class="font-mono text-xs text-gray-400 break-all">{{ $order->stripe_session_id }}</p>
                    </div>
                    @endif
                    @else
                    <div class="flex items-center gap-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <p class="text-sm">No payment recorded yet.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="space-y-5">

            {{-- Customer --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Customer</h2>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600 shrink-0">
                            {{ strtoupper(substr($order->customerName(), 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $order->customerName() }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user->email ?? $order->guest_email ?? '—' }}</p>
                        </div>
                    </div>
                    @if($order->user_id)
                        <a href="{{ route('admin.customers.show', $order->user) }}"
                           class="block text-center text-xs border border-gray-200 rounded-lg py-2 text-gray-500 hover:border-gray-400 hover:text-gray-800 transition">
                            View customer profile →
                        </a>
                    @else
                        <div class="text-xs bg-gray-50 rounded-lg px-3 py-2 text-gray-400 text-center">Guest checkout</div>
                    @endif
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Shipping Address</h2>
                </div>
                <div class="px-5 py-4">
                    <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">{{ $order->shipping_address }}</p>
                </div>
            </div>

            {{-- Coupon --}}
            @if($order->coupon)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Coupon Applied</h2>
                </div>
                <div class="px-5 py-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Code</span>
                        <span class="font-mono font-semibold text-gray-900">{{ $order->coupon->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Type</span>
                        <span class="capitalize text-gray-700">{{ $order->coupon->type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Value</span>
                        <span class="font-mono text-gray-700">
                            {{ $order->coupon->type === 'percentage' ? $order->coupon->value . '%' : '₱' . number_format($order->coupon->value, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Saved</span>
                        <span class="font-mono font-semibold text-green-600">₱{{ number_format($order->discount, 2) }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Order Timeline --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Timeline</h2>
                </div>
                <div class="px-5 py-4 space-y-3">
                    @php
                        $timeline = [
                            ['status' => 'pending',    'label' => 'Order placed',       'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            ['status' => 'paid',       'label' => 'Payment received',   'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                            ['status' => 'processing', 'label' => 'Processing',         'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                            ['status' => 'shipped',    'label' => 'Shipped',            'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                            ['status' => 'delivered',  'label' => 'Delivered',          'icon' => 'M5 13l4 4L19 7'],
                        ];
                        $statuses = ['pending','paid','processing','shipped','delivered','cancelled'];
                        $currentIndex = array_search($order->status, $statuses);
                    @endphp
                    @if($order->status === 'cancelled')
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-red-600">Order cancelled</p>
                        </div>
                    @else
                        @foreach($timeline as $step)
                        @php
                            $stepIndex = array_search($step['status'], $statuses);
                            $done = $stepIndex <= $currentIndex;
                            $active = $step['status'] === $order->status;
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0
                                {{ $done ? 'bg-green-100' : 'bg-gray-100' }}">
                                <svg class="w-3.5 h-3.5 {{ $done ? 'text-green-600' : 'text-gray-300' }}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                                </svg>
                            </div>
                            <p class="text-sm {{ $active ? 'font-semibold text-gray-900' : ($done ? 'text-gray-600' : 'text-gray-300') }}">
                                {{ $step['label'] }}
                            </p>
                            @if($active)
                                <span class="ml-auto text-xs text-gray-400">{{ $order->updated_at->diffForHumans() }}</span>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
