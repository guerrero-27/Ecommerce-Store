@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <h1 class="font-display text-2xl font-semibold mb-8">Dashboard</h1>

    {{-- Stat cards --}}
    <div class="grid grid-cols-4 gap-5 mb-10">
        <div class="border border-hairline p-5">
            <p class="font-mono text-xs uppercase text-ink/50">Total revenue</p>
            <p class="font-display text-2xl mt-2">₱{{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
        <div class="border border-hairline p-5">
            <p class="font-mono text-xs uppercase text-ink/50">Orders</p>
            <p class="font-display text-2xl mt-2">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="border border-hairline p-5">
            <p class="font-mono text-xs uppercase text-ink/50">Customers</p>
            <p class="font-display text-2xl mt-2">{{ $stats['total_customers'] }}</p>
        </div>
        <div class="border border-hairline p-5 {{ $stats['low_stock_count'] > 0 ? 'border-red-300' : '' }}">
            <p class="font-mono text-xs uppercase text-ink/50">Low stock items</p>
            <p class="font-display text-2xl mt-2 {{ $stats['low_stock_count'] > 0 ? 'text-red-600' : '' }}">
                {{ $stats['low_stock_count'] }}
            </p>
        </div>
    </div>

    <div class="grid md:grid-cols-[1fr_320px] gap-8 mb-10">

        {{-- Revenue chart --}}
        <div class="border border-hairline p-6">
            <h2 class="font-mono text-xs uppercase text-ink/50 mb-4">Revenue — last 30 days</h2>
            <canvas id="revenueChart" height="90"></canvas>
        </div>

        {{-- Order status breakdown --}}
        <div class="border border-hairline p-6">
            <h2 class="font-mono text-xs uppercase text-ink/50 mb-4">Order status</h2>
            <canvas id="statusChart"></canvas>
        </div>

    </div>

    {{-- Top products table --}}
    <div class="border border-hairline">
        <h2 class="font-mono text-xs uppercase text-ink/50 px-4 pt-4">Top products</h2>
        <table class="w-full text-sm mt-3">
            <thead class="bg-ink/5 text-left font-mono text-xs uppercase text-ink/50">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Units sold</th>
                    <th class="px-4 py-3">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-hairline">
                @forelse ($topProducts as $product)
                    <tr>
                        <td class="px-4 py-3">{{ $product->product_name }}</td>
                        <td class="px-4 py-3">{{ $product->total_sold }}</td>
                        <td class="px-4 py-3 font-mono">₱{{ number_format($product->total_revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-6 text-center text-ink/50">No sales yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Revenue line chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($revenueChart->pluck('date')),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueChart->pluck('total')),
                borderColor: '#2F6F5E',
                backgroundColor: 'rgba(47, 111, 94, 0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 0,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: (v) => '₱' + v.toLocaleString() } }
            }
        }
    });

    // Order status doughnut chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($statusBreakdown->pluck('status')->map(fn ($s) => ucfirst($s))),
            datasets: [{
                data: @json($statusBreakdown->pluck('count')),
                backgroundColor: ['#D4A02A', '#2F6F5E', '#6366F1', '#8B5CF6', '#22C55E', '#EF4444'],
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
        }
    });
});
</script>
@endpush