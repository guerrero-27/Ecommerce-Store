@extends('layouts.storefront')

@section('title', 'Checkout')

@section('content')

    <div class="max-w-5xl mx-auto px-6 py-12 grid md:grid-cols-[1fr_360px] gap-12">

        <div>
            <h1 class="font-display text-3xl font-semibold mb-8">Checkout</h1>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded mb-6">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST" class="space-y-5">
                @csrf

                @guest
                    <div>
                        <label class="font-mono text-xs uppercase tracking-wide text-ink/50">Full name</label>
                        <input type="text" name="guest_name" value="{{ old('guest_name') }}" required
                               class="mt-1 w-full border-hairline focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <label class="font-mono text-xs uppercase tracking-wide text-ink/50">Email</label>
                        <input type="email" name="guest_email" value="{{ old('guest_email') }}" required
                               class="mt-1 w-full border-hairline focus:border-accent focus:ring-accent">
                    </div>
                @endguest

                <div>
                    <label class="font-mono text-xs uppercase tracking-wide text-ink/50">Shipping address</label>
                    <textarea name="shipping_address" rows="3" required
                              class="mt-1 w-full border-hairline focus:border-accent focus:ring-accent">{{ old('shipping_address') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-ink text-paper py-3.5 text-sm hover:bg-accent transition">
                    Continue to payment →
                </button>
            </form>
        </div>

        {{-- Order summary --}}
        <aside class="border border-hairline p-6 h-fit">
            <h2 class="font-display text-lg font-semibold mb-4">Order summary</h2>

            <div class="space-y-3 text-sm">
                @foreach ($cart->items as $item)
                    <div class="flex justify-between">
                        <span class="text-ink/70">{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span class="font-mono">₱{{ number_format($item->subtotal(), 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-hairline mt-4 pt-4 space-y-2">
                {{-- Coupon form --}}
                @if($coupon)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-accent font-mono">{{ $coupon->code }} applied</span>
                        <form action="{{ route('cart.coupon.remove') }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-ink/40 hover:text-red-600 text-xs">Remove</button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="code" placeholder="Coupon code"
                               class="flex-1 border-hairline text-sm focus:border-accent focus:ring-accent">
                        <button class="border border-hairline px-3 text-sm hover:border-accent hover:text-accent">Apply</button>
                    </form>
                @endif

                <div class="flex justify-between text-sm pt-2">
                    <span>Subtotal</span>
                    <span class="font-mono">₱{{ number_format($cart->total(), 2) }}</span>
                </div>
                @if($discount > 0)
                    <div class="flex justify-between text-sm text-accent">
                        <span>Discount</span>
                        <span class="font-mono">-₱{{ number_format($discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-display text-lg pt-2 border-t border-hairline">
                    <span>Total</span>
                    <span>₱{{ number_format($total, 2) }}</span>
                </div>
            </div>
        </aside>

    </div>

@endsection