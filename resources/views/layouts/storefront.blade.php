<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-body antialiased">

    <header class="border-b border-hairline">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-display text-2xl font-semibold tracking-tight">
                Store<span class="text-accent">.</span>
            </a>

            <nav class="hidden md:flex items-center gap-8 font-body text-sm">
                <a href="{{ route('products.index') }}" class="hover:text-accent transition">Shop</a>
                @foreach (\App\Models\Category::where('is_active', true)->take(4)->get() as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="hover:text-accent transition">{{ $cat->name }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-5">
                
                <a href="{{ route('cart.index') }}" class="relative font-mono text-sm">
                    Cart
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-3 bg-accent text-paper text-xs w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-sm hover:text-accent">Dashboard</a>
                    @else
                        <a href="{{ route('home') }}" class="text-sm hover:text-accent">Account</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-ink/60 hover:text-accent transition">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm hover:text-accent">Log in</a>
                @endauth
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-6 pt-4">
            <div class="bg-accent/10 border border-accent text-accent text-sm px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">
                <div class="col-span-2 md:col-span-1">
                    <a href="{{ route('home') }}" class="text-white font-bold text-xl">{{ config('app.name') }}<span class="text-yellow-400">.</span></a>
                    <p class="mt-3 text-sm leading-relaxed">Your one-stop shop for quality products at great prices.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Shop</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index') }}" class="hover:text-yellow-400 transition">All Products</a></li>
                        @foreach($categories->take(3) as $cat)
                        <li><a href="{{ route('categories.show', $cat->slug) }}" class="hover:text-yellow-400 transition">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Account</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-yellow-400 transition">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-yellow-400 transition">Register</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-yellow-400 transition">My Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Help</h4>
                    <ul class="space-y-2 text-sm">
                        <li><span class="hover:text-yellow-400 transition cursor-pointer">FAQ</span></li>
                        <li><span class="hover:text-yellow-400 transition cursor-pointer">Shipping Policy</span></li>
                        <li><span class="hover:text-yellow-400 transition cursor-pointer">Returns</span></li>
                        <li><span class="hover:text-yellow-400 transition cursor-pointer">Contact Us</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs">
                <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
                <span>Made with ❤️ in the Philippines</span>
            </div>
        </div>
    </footer>

</body>
</html>