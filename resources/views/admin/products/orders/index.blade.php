@extends('layouts.admin')

@section('title', 'Orders')

@section('content')

    <h1 class="font-display text-2xl font-semibold mb-6">Orders</h1>

    <div class="flex gap-3 mb-6">
        <form method="GET" class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order # or email..."
                   class="w-full max-w-sm border-hairline text-sm focus:border-accent focus:ring-accent">
        </form>

        <form method="GET" class="flex gap-2">
            @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
            <select name="status" onchange="this.form.submit()" class="border-hairline text-sm focus:border-accent focus:ring-accent">
                <option value="">All statuses</option>
                @foreach(['pending','paid','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="border border-hairline">
        <table class="w-full text-sm">
            <thead class="bg-ink/5 text-left font-mono text-xs uppercase text-ink/50">
                <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-hairline">
                @foreach ($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">{{ $order->customerName() }}</td>
                        <td class="px-4 py-3 font-mono">₱{{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="text-{{ $order->statusColor() }}-700 bg-{{ $order->statusColor() }}-50 text-xs font-mono uppercase px-2 py-1 rounded">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-accent hover:underline text-xs">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>

@endsection