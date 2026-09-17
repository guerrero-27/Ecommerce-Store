@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="px-8 py-7">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $customers->total() }} registered customers</p>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
        <form method="GET" class="flex gap-3 items-center">
            <div class="flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name or email..."
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
            </div>
            <button type="submit" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-400 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                    <th class="px-6 py-3 text-left font-medium">Customer</th>
                    <th class="px-6 py-3 text-left font-medium">Joined</th>
                    <th class="px-6 py-3 text-left font-medium">Orders</th>
                    <th class="px-6 py-3 text-left font-medium">Total Spent</th>
                    <th class="px-6 py-3 text-left font-medium">Avg. Order</th>
                    <th class="px-6 py-3 text-left font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($customers as $customer)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600 shrink-0">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $customer->created_at->format('d M Y') }}
                        <p class="text-gray-300 mt-0.5">{{ $customer->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($customer->orders_count > 0)
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-600">
                                {{ $customer->orders_count }} order{{ $customer->orders_count > 1 ? 's' : '' }}
                            </span>
                        @else
                            <span class="text-gray-300 text-xs">No orders</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-mono font-semibold text-gray-900">
                        ₱{{ number_format($customer->orders_sum_total ?? 0, 2) }}
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-500 text-xs">
                        @if($customer->orders_count > 0)
                            ₱{{ number_format(($customer->orders_sum_total ?? 0) / $customer->orders_count, 2) }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.customers.show', $customer) }}"
                           class="text-xs font-medium text-gray-500 hover:text-gray-900 transition">
                            View →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        No customers found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $customers->links() }}</div>

</div>
@endsection
