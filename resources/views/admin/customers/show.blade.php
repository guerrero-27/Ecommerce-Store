@extends('layouts.admin')

@section('title', $user->name)

@section('content')
<div class="px-8 py-7">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.customers.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← Customers</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-medium text-gray-700">{{ $user->name }}</span>
    </div>

    <div class="grid lg:grid-cols-[1fr_300px] gap-6">

        {{-- LEFT --}}
        <div class="space-y-6">

            {{-- Orders --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Order History</h2>
                    <span class="text-xs text-gray-400">{{ $orders->count() }} order(s)</span>
                </div>

                @if($orders->isEmpty())
                    <div class="px-6 py-12 text-center text-gray-400">
                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm">No orders yet.</p>
                    </div>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                                <th class="px-6 py-3 text-left font-medium">Order</th>
                                <th class="px-6 py-3 text-left font-medium">Date</th>
                                <th class="px-6 py-3 text-left font-medium">Items</th>
                                <th class="px-6 py-3 text-left font-medium">Total</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                <th class="px-6 py-3 text-left font-medium"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($orders as $order)
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
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3 font-mono text-xs font-semibold text-gray-700">{{ $order->order_number }}</td>
                                <td class="px-6 py-3 text-gray-400 text-xs">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-3 text-gray-500 text-xs">{{ $order->items->count() }}</td>
                                <td class="px-6 py-3 font-mono font-semibold text-gray-900">₱{{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                                 {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="text-xs font-medium text-gray-400 hover:text-gray-900 transition">View →</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="space-y-5">

            {{-- Profile --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex flex-col items-center text-center mb-5">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-600 mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h2 class="font-bold text-gray-900 text-lg">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-400 mt-0.5">{{ $user->email }}</p>
                </div>
                <div class="space-y-2 text-sm border-t border-gray-100 pt-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Joined</span>
                        <span class="text-gray-700">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Member for</span>
                        <span class="text-gray-700">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h2 class="font-semibold text-gray-900">Stats</h2>
                @php
                    $totalSpent = $orders->whereNotIn('status', ['cancelled'])->sum('total');
                    $totalOrders = $orders->count();
                    $completedOrders = $orders->where('status', 'delivered')->count();
                    $avgOrder = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;
                @endphp
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $totalOrders }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Total Orders</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $completedOrders }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Delivered</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center col-span-2">
                        <p class="text-xl font-bold text-gray-900 font-mono">₱{{ number_format($totalSpent, 2) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Total Spent</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center col-span-2">
                        <p class="text-lg font-bold text-gray-900 font-mono">₱{{ number_format($avgOrder, 2) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Avg. Order Value</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
