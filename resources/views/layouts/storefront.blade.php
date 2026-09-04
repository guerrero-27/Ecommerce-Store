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

    <footer class="border-t border-hairline mt-24">
        <div class="max-w-7xl mx-auto px-6 py-10 text-sm text-ink/60 flex justify-between">
            <span>&copy; {{ date('Y') }} Store. All rights reserved.</span>
            <span class="font-mono">Handled with care.</span>
        </div>
    </footer>

</body>
</html>