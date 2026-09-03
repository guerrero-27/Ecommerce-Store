@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="px-8 py-7">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-7">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Good Morning, Admin! 👋</h1>
            <p class="text-sm text-gray-500 mt-0.5">Here's what's happening with your store today</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm text-gray-600 shadow-sm">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ now()->format('d M Y') }}
            </div>
            <div class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center text-white text-sm font-semibold">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
        @php
            $cards = [
                ['label' => 'Total Products',  'value' => $stats['total_products'],  'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'color' => 'bg-blue-50 text-blue-600'],
                ['label' => 'Total Orders',    'value' => $stats['total_orders'],    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'bg-green-50 text-green-600'],
                ['label' => 'Total Customers', 'value' => $stats['total_customers'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'bg-purple-50 text-purple-600'],
                ['label' => 'Low Stock Items', 'value' => $stats['low_stock_count'], 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => $stats['low_stock_count'] > 0 ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-500'],
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                <div class="w-9 h-9 rounded-xl {{ $card['color'] }} flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Revenue + Top Products --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">

        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <div>
                    <h2 class="font-semibold text-gray-900">Your sales report</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Last 30 days</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($stats['total_revenue'], 2) }}</p>
                    <p class="text-xs text-green-500 mt-0.5">Total Revenue</p>
                </div>
            </div>
            <div class="mt-4">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>

        {{-- Order Status --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h2 class="font-semibold text-gray-900 mb-1">Order Status</h2>
            <p class="text-xs text-gray-400 mb-4">Breakdown by status</p>
            <canvas id="statusChart"></canvas>
            <div class="mt-4 space-y-2">
                @foreach ($statusBreakdown as $s)
                <div class="flex items-center justify-between text-sm">
                    <span class="capitalize text-gray-600">{{ $s->status }}</span>
                    <span class="font-semibold text-gray-900">{{ $s->count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Orders + Top Products --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Recent Orders Table --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-400 hover:text-gray-700">View all →</a>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                        <th class="px-6 py-3 text-left font-medium">Order ID</th>
                        <th class="px-6 py-3 text-left font-medium">Customer</th>
                        <th class="px-6 py-3 text-left font-medium">Date</th>
                        <th class="px-6 py-3 text-left font-medium">Total</th>
                        <th class="px-6 py-3 text-left font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $order->order_number }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $order->user->name ?? 'Guest' }}</td>
                        <td class="px-6 py-3 text-gray-400 text-xs">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 font-semibold text-gray-900">₱{{ number_format($order->total, 2) }}</td>
                        <td class="px-6 py-3">
                            @php
                                $colors = [
                                    'pending'    => 'bg-yellow-100 text-yellow-700',
                                    'paid'       => 'bg-blue-100 text-blue-700',
                                    'processing' => 'bg-purple-100 text-purple-700',
                                    'shipped'    => 'bg-indigo-100 text-indigo-700',
                                    'delivered'  => 'bg-green-100 text-green-700',
                                    'cancelled'  => 'bg-red-100 text-red-700',
                                ];
                                $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }} capitalize">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Top Products --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Top Products 🏆</h2>
                <p class="text-xs text-gray-400 mt-0.5">Best selling items</p>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($topProducts as $i => $product)
                <div class="flex items-center gap-4 px-6 py-3.5">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $product->product_name }}</p>
                        <p class="text-xs text-gray-400">{{ $product->total_sold }} sold</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 shrink-0">₱{{ number_format($product->total_revenue, 0) }}</p>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">No sales data yet.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($revenueChart->pluck('date')),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueChart->pluck('total')),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.08)',
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                borderWidth: 2,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 6, font: { size: 11 }, color: '#9ca3af' } },
                y: { grid: { color: '#f3f4f6' }, beginAtZero: true, ticks: { callback: v => '₱' + v.toLocaleString(), font: { size: 11 }, color: '#9ca3af' } }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($statusBreakdown->pluck('status')->map(fn ($s) => ucfirst($s))),
            datasets: [{
                data: @json($statusBreakdown->pluck('count')),
                backgroundColor: ['#fbbf24','#6366f1','#8b5cf6','#22c55e','#3b82f6','#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush
