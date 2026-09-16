@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="px-8 py-7">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Products</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $products->total() }} total products</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="bg-gray-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            + New Product
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <div class="flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name or SKU..."
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
            </div>
            <select name="category" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
                <option value="">All categories</option>
                @foreach(\App\Models\Category::orderBy('name')->get() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
                <option value="">All status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
            <button type="submit" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'category', 'status']))
                <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-400 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                    <th class="px-6 py-3 text-left font-medium">Product</th>
                    <th class="px-6 py-3 text-left font-medium">Category</th>
                    <th class="px-6 py-3 text-left font-medium">Price</th>
                    <th class="px-6 py-3 text-left font-medium">Stock</th>
                    <th class="px-6 py-3 text-left font-medium">Visibility</th>
                    <th class="px-6 py-3 text-left font-medium">Featured</th>
                    <th class="px-6 py-3 text-left font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($products as $product)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                                @if($product->images && count($product->images))
                                    <img src="{{ Storage::url($product->images[0]) }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $product->sku }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            {{ $product->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-mono font-semibold text-gray-900">₱{{ number_format($product->price, 2) }}</p>
                        @if($product->onSale())
                            <p class="text-xs text-gray-400 font-mono line-through mt-0.5">₱{{ number_format($product->compare_price, 2) }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->stock === 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Out of stock
                            </span>
                        @elseif($product->stock <= 5)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                Low · {{ $product->stock }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                {{ $product->stock }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->is_active)
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Hidden</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->is_featured)
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">⭐ Featured</span>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('products.show', $product->slug) }}" target="_blank"
                               class="text-xs text-gray-400 hover:text-gray-700" title="View on store">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="text-xs font-medium text-gray-500 hover:text-gray-900 transition">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-medium text-red-400 hover:text-red-600 transition">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        No products found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $products->links() }}</div>

</div>
@endsection
