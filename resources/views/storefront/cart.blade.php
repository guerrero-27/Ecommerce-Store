@extends('layouts.storefront')

@section('title', 'Your Cart')

@section('content')

    <div class="max-w-4xl mx-auto px-6 py-12">

        <h1 class="font-display text-3xl font-semibold mb-8">Your cart</h1>

        @if($cart->items->isEmpty())
            <div class="border border-hairline py-20 text-center">
                <p class="font-display text-xl">Your cart is empty</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-4 text-sm text-accent hover:underline">
                    Browse the catalog →
                </a>
            </div>
        @else
            <div class="divide-y divide-hairline border-t border-b border-hairline">
                @foreach ($cart->items as $item)
                    <div class="flex items-center gap-5 py-5">
                        <div class="w-20 h-20 bg-ink/5 border border-hairline shrink-0 overflow-hidden">
                            @if($item->product->images && count($item->product->images))
                                <img src="{{ Storage::url($item->product->images[0]) }}" class="w-full h-full object-cover">
                            @endif
                        </div>

                        <div class="flex-1">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="font-display text-lg hover:text-accent">
                                {{ $item->product->name }}
                            </a>
                            <p class="font-mono text-xs text-ink/50 mt-1">₱{{ number_format($item->product->price, 2) }} each</p>
                        </div>

                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                max="{{ $item->product->stock }}"
                                onchange="this.form.submit()"
                                class="w-16 border-hairline text-sm text-center focus:border-accent focus:ring-accent">
                        </form>

                        <p class="font-mono text-sm w-24 text-right">₱{{ number_format($item->subtotal(), 2) }}</p>

                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-ink/40 hover:text-red-600 text-sm">Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between items-center mt-8">
                <a href="{{ route('products.index') }}" class="text-sm text-ink/50 hover:text-accent">&larr; Continue shopping</a>

                <div class="text-right">
                    <p class="font-mono text-xs text-ink/50 uppercase">Total</p>
                    <p class="font-display text-2xl">₱{{ number_format($cart->total(), 2) }}</p>
                </div>
            </div>

            <a href="{{ route('checkout.index') }}"
               class="block text-center mt-6 bg-ink text-paper py-3.5 text-sm hover:bg-accent transition">
                Proceed to checkout
            </a>
        @endif

    </div>

@endsection