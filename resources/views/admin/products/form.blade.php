@extends('layouts.admin')

@section('title', $product->exists ? 'Edit Product' : 'New Product')

@section('content')

    <h1 class="font-display text-2xl font-semibold mb-6">{{ $product->exists ? 'Edit product' : 'New product' }}</h1>

    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-5">
        @csrf
        @if($product->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded">
                @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
            </div>
        @endif

        <div>
            <label class="text-xs font-mono uppercase text-ink/50">Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                   class="mt-1 w-full border-hairline focus:border-accent focus:ring-accent">
        </div>

        <div>
            <label class="text-xs font-mono uppercase text-ink/50">Category</label>
            <select name="category_id" required class="mt-1 w-full border-hairline focus:border-accent focus:ring-accent">
                <option value="">Select a category</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-xs font-mono uppercase text-ink/50">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full border-hairline focus:border-accent focus:ring-accent">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="text-xs font-mono uppercase text-ink/50">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                       class="mt-1 w-full border-hairline focus:border-accent focus:ring-accent">
            </div>
            <div>
                <label class="text-xs font-mono uppercase text-ink/50">Compare-at price</label>
                <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}"
                       class="mt-1 w-full border-hairline focus:border-accent focus:ring-accent">
            </div>
            <div>
                <label class="text-xs font-mono uppercase text-ink/50">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                       class="mt-1 w-full border-hairline focus:border-accent focus:ring-accent">
            </div>
        </div>

        <div>
            <label class="text-xs font-mono uppercase text-ink/50">SKU</label>
            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                   class="mt-1 w-full border-hairline font-mono focus:border-accent focus:ring-accent">
        </div>

        <div>
            <label class="text-xs font-mono uppercase text-ink/50">Images</label>
            <input type="file" name="images[]" multiple accept="image/*" class="mt-1 w-full text-sm">
            @if($product->images)
                <div class="flex gap-2 mt-2">
                    @foreach ($product->images as $img)
                        <img src="{{ Storage::url($img) }}" class="w-14 h-14 object-cover border border-hairline">
                    @endforeach
                </div>
                <p class="text-xs text-ink/50 mt-1">Uploading new images will replace these.</p>
            @endif
        </div>

        <div class="flex gap-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                       class="text-accent focus:ring-accent rounded">
                Active
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                       class="text-accent focus:ring-accent rounded">
                Featured
            </label>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-ink text-paper px-6 py-2.5 text-sm hover:bg-accent transition">
                {{ $product->exists ? 'Save changes' : 'Create product' }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 text-sm border border-hairline hover:border-accent">
                Cancel
            </a>
        </div>
    </form>

@endsection