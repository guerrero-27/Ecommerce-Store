@extends('layouts.storefront')

@section('title', 'Home — ' . config('app.name'))

@section('content')

{{-- HERO BANNER --}}
<section class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
                <span class="inline-block bg-yellow-400 text-gray-900 text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-wide">New Arrivals</span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-4">
                    Shop the Best<br>
                    <span class="text-yellow-400">Products</span> for You
                </h1>
                <p class="text-gray-300 text-base sm:text-lg mb-8 max-w-md">
                    Discover our curated collection of quality products — from electronics to home essentials, all in one place.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}"
                       class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold px-6 py-3 rounded-lg transition text-sm sm:text-base">
                        Shop Now →
                    </a>
                    <a href="{{ route('categories.show', $categories->first()->slug ?? '#') }}"
                       class="border border-white/30 hover:border-white text-white px-6 py-3 rounded-lg transition text-sm sm:text-base">
                        Browse Categories
                    </a>
                </div>
                <div class="flex gap-6 mt-10 text-sm text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Top Rated Products
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Free Delivery ₱999+
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Easy Returns
                    </div>
                </div>
            </div>
            <div class="hidden md:flex justify-center">
                <div class="relative">
                    <div class="w-72 h-72 lg:w-96 lg:h-96 bg-white/10 rounded-full flex items-center justify-center">
                        <div class="w-56 h-56 lg:w-72 lg:h-72 bg-white/10 rounded-full flex items-center justify-center">
                            <svg class="w-32 h-32 lg:w-40 lg:h-40 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                    </div>
                    <div class="absolute -top-4 -right-4 bg-yellow-400 text-gray-900 rounded-2xl px-4 py-2 text-sm font-bold shadow-lg">
                        Up to 50% OFF
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-white text-gray-900 rounded-2xl px-4 py-2 text-sm font-semibold shadow-lg">
                        🚚 Free Shipping
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- PROMO STRIP --}}
<section class="bg-yellow-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex flex-wrap justify-center gap-6 text-gray-900 text-sm font-medium">
            <span class="flex items-center gap-2">🚚 Free delivery on orders ₱999+</span>
            <span class="hidden sm:flex items-center gap-2">✓ 100% Authentic Products</span>
            <span class="hidden sm:flex items-center gap-2">🔄 Easy 30-day Returns</span>
            <span class="flex items-center gap-2">💳 Secure Checkout</span>
        </div>
    </div>
</section>

{{-- SHOP BY CATEGORY --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Shop by Category</h2>
        <a href="{{ route('products.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">All Departments →</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        @foreach ($categories as $category)
        <a href="{{ route('categories.show', $category->slug) }}"
           class="group bg-white border border-gray-200 rounded-xl p-4 text-center hover:border-yellow-400 hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-yellow-100 transition">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-700 group-hover:text-yellow-700 leading-tight">{{ $category->name }}</p>
        </a>
        @endforeach
    </div>
</section>

{{-- FEATURED PRODUCTS --}}
<section class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Featured Products</h2>
                <p class="text-sm text-gray-500 mt-1">Hand-picked just for you</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">View all →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
            @forelse ($featured as $product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-yellow-300 transition-all">
                <div class="relative aspect-square bg-gray-50 overflow-hidden">
                    @if($product->images && count($product->images))
                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    @if($product->onSale())
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">
                            -{{ $product->discountPercentage() }}%
                        </span>
                    @endif
                    @unless($product->inStock())
                        <div class="absolute inset-0 bg-white/80 flex items-center justify-center">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sold Out</span>
                        </div>
                    @endunless
                </div>
                <div class="p-3 sm:p-4">
                    <p class="text-xs text-gray-400 mb-1">{{ $product->category->name }}</p>
                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 leading-snug mb-2">{{ $product->name }}</h3>
                    <div class="flex items-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3 h-3 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-base font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                        @if($product->onSale())
                            <span class="text-xs text-gray-400 line-through">₱{{ number_format($product->compare_price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-4 text-center py-12 text-gray-400">No featured products yet.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- PROMO BANNERS --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
        <div class="relative bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-6 sm:p-8 text-white overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full"></div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <p class="text-blue-200 text-sm font-medium mb-2">Limited Time</p>
            <h3 class="text-2xl font-bold mb-2">Electronics Sale</h3>
            <p class="text-blue-100 text-sm mb-4">Up to 30% off on all electronics</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-white text-blue-700 font-semibold text-sm px-5 py-2 rounded-lg hover:bg-blue-50 transition">
                Shop Now
            </a>
        </div>
        <div class="relative bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl p-6 sm:p-8 text-white overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full"></div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <p class="text-emerald-200 text-sm font-medium mb-2">New Collection</p>
            <h3 class="text-2xl font-bold mb-2">Home & Living</h3>
            <p class="text-emerald-100 text-sm mb-4">Fresh picks for your home</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-white text-emerald-700 font-semibold text-sm px-5 py-2 rounded-lg hover:bg-emerald-50 transition">
                Explore
            </a>
        </div>
    </div>
</section>

{{-- LATEST / TOP SELLERS --}}
<section class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Latest Products</h2>
                <p class="text-sm text-gray-500 mt-1">Just arrived in our store</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">View more →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
            @forelse ($latest as $product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-yellow-300 transition-all">
                <div class="relative aspect-square bg-gray-50 overflow-hidden">
                    @if($product->images && count($product->images))
                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    @if($product->onSale())
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">
                            -{{ $product->discountPercentage() }}%
                        </span>
                    @endif
                    <span class="absolute top-2 right-2 bg-yellow-400 text-gray-900 text-xs font-bold px-2 py-0.5 rounded">NEW</span>
                </div>
                <div class="p-3 sm:p-4">
                    <p class="text-xs text-gray-400 mb-1">{{ $product->category->name }}</p>
                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 leading-snug mb-2">{{ $product->name }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-base font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                        @if($product->onSale())
                            <span class="text-xs text-gray-400 line-through">₱{{ number_format($product->compare_price, 2) }}</span>
                        @endif
                    </div>
                    @if($product->inStock())
                        <p class="text-xs text-green-600 mt-1 font-medium">✓ In Stock</p>
                    @else
                        <p class="text-xs text-red-500 mt-1 font-medium">Out of Stock</p>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-4 text-center py-12 text-gray-400">No products yet.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- WHY CHOOSE US --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 text-center mb-8">Why Shop With Us</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach([
            ['icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'title' => 'Free Shipping', 'desc' => 'On orders over ₱999'],
            ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Secure Payment', 'desc' => '100% secure transactions'],
            ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => 'Easy Returns', 'desc' => '30-day return policy'],
            ['icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'title' => '24/7 Support', 'desc' => 'Always here to help'],
        ] as $feature)
        <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition">
            <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $feature['title'] }}</h3>
            <p class="text-xs text-gray-500">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- NEWSLETTER --}}
<section class="bg-gradient-to-br from-gray-900 to-gray-800 text-white py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-3">Subscribe to the News</h2>
        <p class="text-gray-400 mb-8 text-sm sm:text-base">Be aware of all discounts and bargains! Don't miss your benefit 🎉</p>
        <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <input type="email" placeholder="Enter your email address"
                   class="flex-1 px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:border-yellow-400 text-sm">
            <button type="submit"
                    class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold px-6 py-3 rounded-lg transition text-sm whitespace-nowrap">
                Subscribe
            </button>
        </form>
    </div>
</section>

@endsection
