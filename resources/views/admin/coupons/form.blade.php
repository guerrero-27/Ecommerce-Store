@extends('layouts.admin')

@section('title', $coupon->exists ? 'Edit Coupon' : 'New Coupon')

@section('content')
<div class="px-8 py-7 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.coupons.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← Coupons</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-bold text-gray-900">{{ $coupon->exists ? 'Edit Coupon' : 'New Coupon' }}</h1>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
          method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
        @csrf
        @if($coupon->exists) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Code</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:border-gray-400 focus:ring-0 uppercase"
                       placeholder="SAVE20">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Type</label>
                <select name="type" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed (₱)</option>
                    <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Value</label>
                <input type="number" name="value" value="{{ old('value', $coupon->value) }}" required min="0" step="0.01"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Min Order Amount</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" min="0" step="0.01"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0" placeholder="Optional">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Usage Limit</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0" placeholder="Unlimited">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Expires At</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
            <label for="is_active" class="text-sm text-gray-700">Active</label>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-gray-900 text-white text-sm px-5 py-2.5 rounded-lg hover:bg-gray-700 transition">
                {{ $coupon->exists ? 'Update Coupon' : 'Create Coupon' }}
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-5 py-2.5">Cancel</a>
        </div>
    </form>
</div>
@endsection
