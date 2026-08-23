@extends('layouts.admin')

@section('title', 'Products')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="font-display text-2xl font-semibold">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-ink text-paper px-5 py-2.5 text-sm hover:bg-accent transition">
            + New product
        </a>
    </div>

    <form method="GET" class="mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
               class="w-full max-w-sm border-hairline text-sm focus:border-accent focus:ring-accent">
    </form>

    <div class="border border-hairline">
        <table class="w-full text-sm">
            <thead class="bg-ink/5 text-left font-mono text-xs uppercase text-ink/50">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-hairline">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $product->name }}</div>
                            <div class="font-mono text-xs text-ink/40">{{ $product->sku }}</div>
                        </td>
                        <td class="px-4 py-3 text-ink/70">{{ $product->category->name }}</td>
                        <td class="px-4 py-3 font-mono">₱{{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $product->stock <= 5 ? 'text-red-600' : '' }}">{{ $product->stock }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($product->is_active)
                                <span class="text-accent text-xs font-mono uppercase">Active</span>
                            @else
                                <span class="text-ink/40 text-xs font-mono uppercase">Hidden</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-accent hover:underline text-xs">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $products->links() }}</div>

@endsection