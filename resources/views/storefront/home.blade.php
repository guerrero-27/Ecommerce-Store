@extends('layouts.storefront')

@section('title', 'Home')

@section('content')

    {{-- Hero --}}
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-24 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <span class="font-mono text-xs uppercase tracking-widest text-accent">New arrivals, weekly</span>
            <h1 class="font-display text-5xl md:text-6xl font-semibold leading-[1.05] mt-4">
                Goods worth<br>keeping around.
            </h1>
            <p class="mt-6 text-ink/70 max-w-md">
                A small, considered catalog — restocked often, curated carefully.
            </p>
            <a href="{{ route('products.index') }}"
               class="inline-block mt-8 bg-ink text-paper px-7 py-3 font-body text-sm hover:bg-accent transition">
                Browse the catalog →
            </a>
        </div>
        <div class="aspect-[4/5] bg-ink/5 border border-hairline flex items-center justify-center">
            <span class="font-mono text-xs text-ink/40">Hero image</span>
        </div>
    </section>

    {{-- Categories strip --}}
    <section class="max-w-7xl mx-auto px-6 pb-20">
        <h2 class="font-display text-xl font-semibold mb-6">Shop by category</h2>
        <div class="flex gap-4 overflow-x-auto pb-2">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}"
                   class="shrink-0 border border-hairline px-5 py-3 text-sm hover:border-accent hover:text-accent transition">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured products --}}
    <section class="max-w-7xl mx-auto px-6 pb-24">
        <h2 class="font-display text-xl font-semibold mb-6">Featured</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($featured as $product)
                @include('storefront.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

@endsection