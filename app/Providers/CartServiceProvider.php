<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Carrinho;
use Illuminate\Support\Facades\Auth;

class CartServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('cart', function ($app) {
            $storage = Auth::check()
                ? $this->getUserCartStorage(Auth::id())
                : new \Darryldecode\Cart\CartCollection([]);

            $cart = new \Darryldecode\Cart\Cart(
                $storage,
                $app['events'],
                'cart',
                Auth::id() ?: session()->getId(),
                $this->getCartConfig()
            );

            return $cart;
        });
    }

    protected function getUserCartStorage($userId)
    {
        $cart = Carrinho::firstOrCreate(['id_usuario' => $userId]);
        return $cart->conteudo
            ? new \Darryldecode\Cart\CartCollection(json_decode($cart->conteudo, true))
            : new \Darryldecode\Cart\CartCollection([]);
    }

    protected function getCartConfig()
    {
        return [
            'format_numbers' => false,
            'decimals' => 2,
            'decimal_point' => '.',
            'thousand_separator' => ','
        ];
    }
}