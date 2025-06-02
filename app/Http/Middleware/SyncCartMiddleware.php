<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SyncCartMiddleware
{
    public function handle($request, Closure $next)
    {
        // Verificação corrigida da sessão
        if ($request->hasSession() && !$request->session()->isStarted()) {
            $request->session()->regenerate();
        }

        // Sincroniza o carrinho se o usuário estiver autenticado
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
        // Implemente a lógica para carregar o carrinho do banco para a sessão
        $content = json_decode($cartContent, true);
        foreach ($content as $item) {
            \Cart::add([
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'attributes' => $item['attributes'] ?? []
            ]);
        }
    }

    protected function saveCartToDatabase($userId)
    {
        // Implemente a lógica para salvar o carrinho da sessão no banco
        $content = json_encode(\Cart::getContent()->toArray());

        \App\Models\Carrinho::updateOrCreate(
            ['id_usuario' => $userId],
            ['conteudo' => $content]
        );
    }
}