<a href="{{ route('products.show', $product->slug) }}" class="group block">
    <div class="relative aspect-[4/5] bg-ink/5 border border-hairline overflow-hidden">
        @if($product->images && count($product->images))
            <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center font-mono text-xs text-ink/30">No image</div>
        @endif

        {{-- Swing tag --}}
        <div class="absolute top-3 right-3 bg-paper border border-ink/20 px-2.5 py-1.5 rotate-3 shadow-sm font-mono text-[11px] leading-tight">
            <div class="text-ink/50">{{ $product->sku }}</div>
            <div class="font-medium">₱{{ number_format($product->price, 2) }}</div>
        </div>

        @if($product->onSale())
            <div class="absolute top-3 left-3 bg-mustard text-ink px-2 py-1 font-mono text-[10px] uppercase tracking-wide">
                -{{ $product->discountPercentage() }}%
            </div>
        @endif

        @unless($product->inStock())
            <div class="absolute inset-0 bg-paper/80 flex items-center justify-center font-mono text-xs uppercase tracking-wide">
                Sold out
            </div>
        @endunless
    </div>

    <div class="mt-3">
        <p class="font-mono text-xs text-ink/50 uppercase">{{ $product->category->name }}</p>
        <h3 class="font-display text-lg mt-0.5">{{ $product->name }}</h3>
    </div>
</a>