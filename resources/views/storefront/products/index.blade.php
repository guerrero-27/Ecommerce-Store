@extends('layouts.storefront')

@section('title', 'Shop')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-[240px_1fr] gap-10">

        {{-- Filters sidebar --}}
        <aside>
            <form method="GET" action="{{ route('products.index') }}" class="space-y-8">

                {{-- Search --}}
                <div>
                    <label class="font-mono text-xs uppercase tracking-wide text-ink/50">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Find a product..."
                           class="mt-2 w-full border-hairline text-sm focus:border-accent focus:ring-accent">
                </div>

                {{-- Category --}}
                <div>
                    <label class="font-mono text-xs uppercase tracking-wide text-ink/50">Category</label>
                    <div class="mt-2 space-y-2">
                        @foreach ($categories as $cat)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="category" value="{{ $cat->slug }}"
                                       {{ request('category') === $cat->slug ? 'checked' : '' }}
                                       class="text-accent focus:ring-accent">
                                {{ $cat->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Price range --}}
                <div>
                    <label class="font-mono text-xs uppercase tracking-wide text-ink/50">Price range</label>
                    <div class="mt-2 flex gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}"
                               placeholder="Min" class="w-full border-hairline text-sm">
                        <input type="number" name="max_price" value="{{ request('max_price') }}"
                               placeholder="Max" class="w-full border-hairline text-sm">
                    </div>
                </div>

                <button type="submit" class="w-full bg-ink text-paper py-2.5 text-sm hover:bg-accent transition">
                    Apply filters
                </button>

                @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price']))
                    <a href="{{ route('products.index') }}" class="block text-center text-sm text-ink/50 hover:text-accent">
                        Clear all
                    </a>
                @endif
            </form>
        </aside>

        {{-- Results --}}
        <div>
            <div class="flex items-center justify-between mb-8">
                <p class="text-sm text-ink/60">{{ $products->total() }} products</p>

                <form method="GET" class="flex items-center gap-2">
                    {{-- Preserve existing filters when sorting --}}
                    @foreach (request()->except('sort') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label class="font-mono text-xs uppercase text-ink/50">Sort</label>
                    <select name="sort" onchange="this.form.submit()" class="text-sm border-hairline focus:border-accent focus:ring-accent">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to high</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to low</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                    </select>
                </form>
            </div>

            @if($products->isEmpty())
                <div class="border border-hairline py-20 text-center">
                    <p class="font-display text-xl">No products found</p>
                    <p class="text-sm text-ink/60 mt-2">Try adjusting your filters.</p>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($products as $product)
                        @include('storefront.partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>

@endsection