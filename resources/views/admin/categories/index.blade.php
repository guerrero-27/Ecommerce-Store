@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="px-8 py-7">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $categories->count() }} total categories</p>
        </div>
        <button onclick="document.getElementById('create-modal').classList.remove('hidden')"
                class="bg-gray-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            + New Category
        </button>
    </div>

    @if($errors->has('category'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5">
            {{ $errors->first('category') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                    <th class="px-6 py-3 text-left font-medium">Category</th>
                    <th class="px-6 py-3 text-left font-medium">Slug</th>
                    <th class="px-6 py-3 text-left font-medium">Description</th>
                    <th class="px-6 py-3 text-left font-medium">Products</th>
                    <th class="px-6 py-3 text-left font-medium">Status</th>
                    <th class="px-6 py-3 text-left font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($categories as $category)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $category->name }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs text-gray-400">{{ $category->slug }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate">
                        {{ $category->description ?: '—' }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.products.index', ['category' => $category->id]) }}"
                           class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            {{ $category->products_count }} products
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        @if($category->is_active)
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Hidden</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <button onclick="openEdit({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}', {{ $category->is_active ? 'true' : 'false' }})"
                                    class="text-xs font-medium text-gray-500 hover:text-gray-900 transition">Edit</button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                onsubmit="return confirm('Delete \'{{ addslashes($category->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-medium text-red-400 hover:text-red-600 transition">Delete</button>
                            </form>
                            <a href="{{ route('categories.show', $category->slug) }}" target="_blank"
                            class="text-gray-300 hover:text-gray-600 transition" title="View on store">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        No categories yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Create Modal --}}
<div id="create-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">New Category</h2>
            <button onclick="document.getElementById('create-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0"
                       placeholder="e.g. Electronics">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Description <span class="text-gray-300 normal-case font-normal">optional</span></label>
                <textarea name="description" rows="2"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0 resize-none"
                          placeholder="Short description...">{{ old('description') }}</textarea>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" checked
                       class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                <span class="text-sm text-gray-700">Active</span>
            </label>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 bg-gray-900 text-white text-sm py-2.5 rounded-lg hover:bg-gray-700 transition">
                    Create Category
                </button>
                <button type="button" onclick="document.getElementById('create-modal').classList.add('hidden')"
                        class="flex-1 border border-gray-200 text-gray-500 text-sm py-2.5 rounded-lg hover:border-gray-400 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Edit Category</h2>
            <button onclick="document.getElementById('edit-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="edit-form" action="" method="POST" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Name</label>
                <input type="text" id="edit-name" name="name" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Description <span class="text-gray-300 normal-case font-normal">optional</span></label>
                <textarea id="edit-description" name="description" rows="2"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-gray-400 focus:ring-0 resize-none"></textarea>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="edit-is-active" name="is_active" value="1"
                       class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                <span class="text-sm text-gray-700">Active</span>
            </label>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 bg-gray-900 text-white text-sm py-2.5 rounded-lg hover:bg-gray-700 transition">
                    Save Changes
                </button>
                <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')"
                        class="flex-1 border border-gray-200 text-gray-500 text-sm py-2.5 rounded-lg hover:border-gray-400 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEdit(id, name, description, isActive) {
    document.getElementById('edit-form').action = `/admin/categories/${id}`;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-is-active').checked = isActive;
    document.getElementById('edit-modal').classList.remove('hidden');
}
</script>
@endpush
@endsection
