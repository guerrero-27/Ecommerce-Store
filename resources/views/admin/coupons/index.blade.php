@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')
<div class="px-8 py-7">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Coupons</h1>
        <a href="{{ route('admin.coupons.create') }}"
           class="bg-gray-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            + New Coupon
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                    <th class="px-6 py-3 text-left font-medium">Code</th>
                    <th class="px-6 py-3 text-left font-medium">Type</th>
                    <th class="px-6 py-3 text-left font-medium">Value</th>
                    <th class="px-6 py-3 text-left font-medium">Min Order</th>
                    <th class="px-6 py-3 text-left font-medium">Usage</th>
                    <th class="px-6 py-3 text-left font-medium">Expires</th>
                    <th class="px-6 py-3 text-left font-medium">Status</th>
                    <th class="px-6 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($coupons as $coupon)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3 font-mono font-semibold text-gray-900">{{ $coupon->code }}</td>
                    <td class="px-6 py-3 capitalize text-gray-600">{{ $coupon->type }}</td>
                    <td class="px-6 py-3 font-mono text-gray-900">
                        {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '₱' . number_format($coupon->value, 2) }}
                    </td>
                    <td class="px-6 py-3 text-gray-500">
                        {{ $coupon->min_order_amount ? '₱' . number_format($coupon->min_order_amount, 2) : '—' }}
                    </td>
                    <td class="px-6 py-3 text-gray-500">
                        {{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}
                    </td>
                    <td class="px-6 py-3 text-gray-500 text-xs">
                        {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : '—' }}
                    </td>
                    <td class="px-6 py-3">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $coupon->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 flex items-center gap-3">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-gray-400 hover:text-gray-900 text-xs">Edit</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                              onsubmit="return confirm('Delete this coupon?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-10 text-center text-gray-400">No coupons yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
