@extends('layouts.storefront')

@section('title', $product->name)

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-12">

        <a href="{{ route('products.index') }}" class="text-sm text-ink/50 hover:text-accent">&larr; Back to shop</a>

        <div class="grid md:grid-cols-2 gap-14 mt-6">

            {{-- Image gallery --}}
            <div>
                <div class="aspect-square bg-ink/5 border border-hairline overflow-hidden">
                    @if($product->images && count($product->images))
                        <img id="main-image" src="{{ Storage::url($product->images[0]) }}"
                             alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center font-mono text-xs text-ink/30">No image</div>
                    @endif
                </div>

                @if($product->images && count($product->images) > 1)
                    <div class="flex gap-3 mt-4">
                        @foreach ($product->images as $img)
                            <button onclick="document.getElementById('main-image').src = '{{ Storage::url($img) }}'"
                                    class="w-16 h-16 border border-hairline overflow-hidden hover:border-accent transition">
                                <img src="{{ Storage::url($img) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div>
                <p class="font-mono text-xs uppercase tracking-wide text-accent">{{ $product->category->name }}</p>
                <h1 class="font-display text-3xl md:text-4xl font-semibold mt-2">{{ $product->name }}</h1>

                <div class="flex items-baseline gap-3 mt-4">
                    <span class="font-mono text-2xl">₱{{ number_format($product->price, 2) }}</span>
                    @if($product->onSale())
                        <span class="font-mono text-sm text-ink/40 line-through">₱{{ number_format($product->compare_price, 2) }}</span>
                        <span class="bg-mustard text-ink text-xs px-2 py-0.5 font-mono">-{{ $product->discountPercentage() }}%</span>
                    @endif
                </div>

                <p class="text-ink/70 mt-6 leading-relaxed">{{ $product->description }}</p>

                <div class="mt-4 font-mono text-xs text-ink/50">
                    SKU: {{ $product->sku }}
                    @if($product->inStock())
                        · <span class="text-accent">{{ $product->stock }} in stock</span>
                    @else
                        · <span class="text-red-600">Out of stock</span>
                    @endif
                </div>

                @if($product->inStock())
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-8 flex gap-3">
                        @csrf
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                               class="w-20 border-hairline text-sm focus:border-accent focus:ring-accent">
                        <button type="submit"
                                class="flex-1 bg-ink text-paper py-3 text-sm font-body hover:bg-accent transition">
                            Add to cart
                        </button>
                    </form>
                @else
                    <button disabled class="mt-8 w-full bg-ink/20 text-ink/40 py-3 text-sm cursor-not-allowed">
                        Out of stock
                    </button>
                @endif
            </div>
        </div>

        {{-- Related products --}}
        @if($related->isNotEmpty())
            <div class="mt-24">
                <h2 class="font-display text-xl font-semibold mb-6">You might also like</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach ($related as $item)
                        @include('storefront.partials.product-card', ['product' => $item])
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection