<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-body antialiased">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-60 border-r border-hairline shrink-0">
            <div class="px-6 py-5 border-b border-hairline">
                <span class="font-display text-xl font-semibold">Admin<span class="text-accent">.</span></span>
            </div>

            <nav class="px-3 py-4 space-y-1 text-sm">
                @php
                    $links = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard'],
                        ['route' => 'admin.products.index', 'label' => 'Products'],
                        ['route' => 'admin.categories.index', 'label' => 'Categories'],
                        ['route' => 'admin.orders.index', 'label' => 'Orders'],
                        ['route' => 'admin.customers.index', 'label' => 'Customers'],
                        ['route' => 'admin.coupons.index', 'label' => 'Coupons'],
                        ['route' => 'admin.inventory.index', 'label' => 'Inventory'],
                    ];
                @endphp
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="block px-3 py-2 rounded {{ request()->routeIs($link['route'].'*') ? 'bg-ink text-paper' : 'hover:bg-ink/5' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 mt-auto border-t border-hairline pt-4 absolute bottom-4 w-60">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-sm text-ink/50 hover:text-accent">← View store</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full text-left px-3 py-2 text-sm text-ink/50 hover:text-accent">Log out</button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <main class="flex-1">
            <div class="px-10 py-8">
                @if(session('success'))
                    <div class="bg-accent/10 border border-accent text-accent text-sm px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

    </div>

</body>
</html>