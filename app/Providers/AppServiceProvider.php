<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.storefront', function ($view) {
            $cart = null;

            if (auth()->check()) {
                $cart = Cart::with('items')->firstWhere('user_id', auth()->id());
            } elseif (session()->has('cart_session_id')) {
                $cart = Cart::with('items')->firstWhere('session_id', session('cart_session_id'));
            }

            $view->with('cartCount', $cart?->itemCount() ?? 0);
        });
    }
}