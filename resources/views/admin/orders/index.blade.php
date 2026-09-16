@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="px-8 py-7">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $orders->total() }} total orders</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <div class="flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search order # or email..."
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
            </div>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
                <option value="">All statuses</option>
                @foreach(['pending','paid','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search','status']))
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-400 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>

    {{-- Status summary pills --}}
    @php
        $statusCounts = \App\Models\Order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');
        $statusColors = [
            'pending'    => 'bg-yellow-100 text-yellow-700',
            'paid'       => 'bg-blue-100 text-blue-700',
            'processing' => 'bg-indigo-100 text-indigo-700',
            'shipped'    => 'bg-purple-100 text-purple-700',
            'delivered'  => 'bg-green-100 text-green-700',
            'cancelled'  => 'bg-red-100 text-red-700',
        ];
    @endphp
    <div class="flex flex-wrap gap-2 mb-5">
        @foreach(['pending','paid','processing','shipped','delivered','cancelled'] as $s)
            @if(($statusCounts[$s] ?? 0) > 0)
            <a href="{{ route('admin.orders.index', ['status' => $s]) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium transition
                      {{ request('status') === $s ? $statusColors[$s] . ' ring-2 ring-offset-1 ring-current' : $statusColors[$s] }}">
                {{ ucfirst($s) }}
                <span class="font-bold">{{ $statusCounts[$s] }}</span>
            </a>
            @endif
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                    <th class="px-6 py-3 text-left font-medium">Order</th>
                    <th class="px-6 py-3 text-left font-medium">Customer</th>
                    <th class="px-6 py-3 text-left font-medium">Items</th>
                    <th class="px-6 py-3 text-left font-medium">Subtotal</th>
                    <th class="px-6 py-3 text-left font-medium">Discount</th>
                    <th class="px-6 py-3 text-left font-medium">Total</th>
                    <th class="px-6 py-3 text-left font-medium">Status</th>
                    <th class="px-6 py-3 text-left font-medium">Date</th>
                    <th class="px-6 py-3 text-left font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-mono text-xs font-semibold text-gray-900">{{ $order->order_number }}</p>
                        @if($order->stripe_session_id)
                            <p class="font-mono text-xs text-gray-300 mt-0.5 truncate max-w-[120px]" title="{{ $order->stripe_session_id }}">
                                {{ Str::limit($order->stripe_session_id, 16) }}
                            </p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $order->customerName() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->user->email ?? $order->guest_email ?? '—' }}</p>
                        @if(!$order->user_id)
                            <span class="inline-block mt-1 text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Guest</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $order->items_count ?? $order->items->count() }} item(s)
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-700">₱{{ number_format($order->subtotal, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($order->discount > 0)
                            <span class="font-mono text-green-600">-₱{{ number_format($order->discount, 2) }}</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-mono font-semibold text-gray-900">₱{{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = [
                                'pending'    => 'bg-yellow-100 text-yellow-700',
                                'paid'       => 'bg-blue-100 text-blue-700',
                                'processing' => 'bg-indigo-100 text-indigo-700',
                                'shipped'    => 'bg-purple-100 text-purple-700',
                                'delivered'  => 'bg-green-100 text-green-700',
                                'cancelled'  => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                     {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs whitespace-nowrap">
                        {{ $order->created_at->format('d M Y') }}<br>
                        <span class="text-gray-300">{{ $order->created_at->format('g:i A') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-xs font-medium text-gray-500 hover:text-gray-900 transition">
                            View →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $orders->links() }}</div>

</div>
@endsection
