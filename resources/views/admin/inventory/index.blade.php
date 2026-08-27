@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="font-display text-2xl font-semibold">Inventory</h1>
        <a href="{{ route('admin.inventory.index', ['low_stock' => 1]) }}"
            class="text-sm {{ request('low_stock') ? 'text-accent' : 'text-ink/50 hover:text-accent' }}">
            Show low stock only
        </a>
    </div>

    <div class="border border-hairline">
        <table class="w-full text-sm">
            <thead class="bg-ink/5 text-left font-mono text-xs uppercase text-ink/50">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Current stock</th>
                    <th class="px-4 py-3">Adjust</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-hairline">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <div>{{ $product->name }}</div>
                            <div class="font-mono text-xs text-ink/40">{{ $product->sku }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-mono {{ $product->stock <= 5 ? 'text-red-600 font-medium' : '' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.inventory.adjust', $product) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="change" placeholder="±qty" required
                                    class="w-24 border-hairline text-sm focus:border-accent focus:ring-accent">
                                <input type="text" name="reason" placeholder="Reason (e.g. restock)" required
                                    class="w-48 border-hairline text-sm focus:border-accent focus:ring-accent">
                                <button class="border border-hairline px-3 py-1.5 text-xs hover:border-accent hover:text-accent">
                                    Apply
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $products->links() }}</div>

@endsection