<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white">
        <div class="min-h-screen flex">
            {{-- Left: Form --}}
            <div class="flex flex-col justify-center w-full md:w-1/2 px-8 sm:px-16 lg:px-24 py-12">
                <div class="mb-8">
                    <a href="/" class="text-sm font-semibold tracking-widest uppercase text-gray-800">
                        ✦ {{ config('app.name', 'Store') }}
                    </a>
                </div>
                {{ $slot }}
            </div>

            {{-- Right: Image Panel --}}
            <div class="hidden md:flex md:w-1/2 relative overflow-hidden rounded-l-[2.5rem] m-4">
                <img
                    src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=900&auto=format&fit=crop"
                    alt="Store"
                    class="absolute inset-0 w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                <div class="absolute bottom-10 left-8 right-8 text-white">
                    <h2 class="text-3xl font-semibold leading-snug mb-2">Discovering the Best<br>Products for Your Home</h2>
                    <p class="text-sm text-white/75 mb-5">Quality products curated for everyday living.</p>
                    <div class="flex gap-3 flex-wrap">
                        <span class="flex items-center gap-2 bg-white/20 backdrop-blur-sm text-white text-xs px-4 py-2 rounded-full border border-white/30">
                            ✓ 100% Guarantee
                        </span>
                        <span class="flex items-center gap-2 bg-white/20 backdrop-blur-sm text-white text-xs px-4 py-2 rounded-full border border-white/30">
                            🚚 Free delivery on orders ₱999+
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
