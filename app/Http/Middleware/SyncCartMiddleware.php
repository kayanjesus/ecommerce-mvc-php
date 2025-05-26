<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SyncCartMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $this->syncCartWithDatabase(Auth::id());
        }
        
        return $next($request);
    }

    protected function syncCartWithDatabase($userId)
    {
        $cart = \App\Models\Carrinho::where('id_usuario', $userId)->first();
        
        // Se tem carrinho no banco e a sessão está vazia
        if ($cart && $cart->conteudo && \Cart::isEmpty()) {
            $this->loadCartFromDatabase($cart->conteudo);
        }
        // Se tem itens na sessão e não no banco, ou são diferentes
        elseif (!\Cart::isEmpty()) {
            $this->saveCartToDatabase($userId);
        }
    }

    protected function loadCartFromDatabase($cartContent)
    {
        $items = json_decode($cartContent, true);
        foreach ($items as $item) {
            \Cart::add($item);
        }
    }

    protected function saveCartToDatabase($userId)
    {
        \App\Models\Carrinho::updateOrCreate(
            ['id_usuario' => $userId],
            ['conteudo' => json_encode(\Cart::getContent()->toArray())]
        );
    }
}