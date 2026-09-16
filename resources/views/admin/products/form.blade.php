@extends('layouts.admin')

@section('title', $product->exists ? 'Edit Product' : 'New Product')

@section('content')
<div class="px-8 py-7">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← Products</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-bold text-gray-900">{{ $product->exists ? 'Edit Product' : 'New Product' }}</h1>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-6">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if($product->exists) @method('PUT') @endif

        <div class="grid lg:grid-cols-[1fr_300px] gap-6">

            {{-- LEFT --}}
            <div class="space-y-5">

                {{-- Basic Info --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                    <h2 class="font-semibold text-gray-900">Basic Information</h2>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Product Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0"
                               placeholder="e.g. Wireless Headphones">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Description</label>
                        <textarea name="description" rows="5"
                                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0 resize-none"
                                  placeholder="Describe the product...">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Category</label>
                        <select name="category_id" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
                            <option value="">Select a category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                    <h2 class="font-semibold text-gray-900">Pricing</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Price (₱)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required min="0"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0"
                                   placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                Compare-at Price (₱)
                                <span class="text-gray-300 normal-case font-normal ml-1">optional</span>
                            </label>
                            <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" min="0"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0"
                                   placeholder="0.00">
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">Set a compare-at price higher than the selling price to show a sale badge.</p>
                </div>

                {{-- Inventory --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                    <h2 class="font-semibold text-gray-900">Inventory</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:border-gray-400 focus:ring-0"
                                   placeholder="e.g. WH-1000XM5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Stock Quantity</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0"
                                   placeholder="0">
                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h2 class="font-semibold text-gray-900">Images</h2>

                    @if($product->images && count($product->images))
                        <div>
                            <p class="text-xs text-gray-400 mb-2">Current images</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($product->images as $img)
                                    <div class="relative w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                        <img src="{{ Storage::url($img) }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Uploading new images will replace these.</p>
                        </div>
                    @endif

                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-gray-400 hover:bg-gray-50 transition">
                        <svg class="w-7 h-7 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-gray-400">Click to upload images</span>
                        <span class="text-xs text-gray-300 mt-1">PNG, JPG up to 2MB each</span>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden"
                               onchange="previewImages(this)">
                    </label>

                    <div id="image-preview" class="flex flex-wrap gap-2 hidden"></div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-5">

                {{-- Publish --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h2 class="font-semibold text-gray-900">Publish</h2>

                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Active</p>
                            <p class="text-xs text-gray-400">Visible to customers</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-10 h-6 bg-gray-200 rounded-full peer peer-checked:bg-gray-900 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Featured</p>
                            <p class="text-xs text-gray-400">Show on homepage</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-10 h-6 bg-gray-200 rounded-full peer peer-checked:bg-gray-900 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                        </div>
                    </label>
                </div>

                {{-- Summary (edit only) --}}
                @if($product->exists)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-3">
                    <h2 class="font-semibold text-gray-900">Summary</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Created</span>
                            <span class="text-gray-700">{{ $product->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Updated</span>
                            <span class="text-gray-700">{{ $product->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Slug</span>
                            <span class="font-mono text-xs text-gray-500">{{ $product->slug }}</span>
                        </div>
                    </div>
                    <a href="{{ route('products.show', $product->slug) }}" target="_blank"
                       class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-700 mt-2 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        View on store
                    </a>
                </div>
                @endif

                {{-- Actions --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-3">
                    <button type="submit"
                            class="w-full bg-gray-900 text-white text-sm py-2.5 rounded-lg hover:bg-gray-700 transition font-medium">
                        {{ $product->exists ? 'Save Changes' : 'Create Product' }}
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                       class="block w-full text-center text-sm py-2.5 rounded-lg border border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-700 transition">
                        Cancel
                    </a>
                    @if($product->exists)
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full text-sm py-2.5 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 hover:border-red-300 transition">
                            Delete Product
                        </button>
                    </form>
                    @endif
                </div>

            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImages(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    if (input.files.length === 0) { preview.classList.add('hidden'); return; }
    preview.classList.remove('hidden');
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'w-20 h-20 rounded-lg overflow-hidden border border-gray-200';
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
@endsection
