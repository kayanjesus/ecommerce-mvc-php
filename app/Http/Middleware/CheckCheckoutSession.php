<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckCheckoutSession
{
    // Rotas que exigem verificação
    protected $checkoutRoutes = [
        'pagamento.revisao',
        'pagamento.forma-pagamento',
        'pagamento.finalizar',
        'pagamento.sucesso'
    ];

    public function handle($request, Closure $next)
    {
        try {
            // Ignorar verificação para rotas de sucesso/erro e para a própria rota de CEP
            // Se a rota atual é 'pagamento.cep', o middleware não deve redirecionar para ela novamente.
            if (
                $request->route()->named('pagamento.sucesso') ||
                $request->route()->named('pagamento.erro') ||
                $request->route()->named('pagamento.cep') // <<< ADICIONADO AQUI!
            ) {
                return $next($request);
            }

            $cart = \Cart::session(Auth::id());
            $cartItems = $cart->getContent();
            $sessionItems = session('itens_checkout', []);

            // Verificar itens do carrinho. Se estiver vazio e não for a página de CEP, redirecione para a página de CEP.
            if ($cartItems->isEmpty() && empty($sessionItems)) {
                return redirect()
                    ->route('pagamento.cep')
                    ->with('erro', 'Seu carrinho está vazio para prosseguir com o checkout.');
            }

            // Verificar endereço apenas para rotas após a etapa de CEP
            $postCepRoutes = ['pagamento.forma-pagamento', 'pagamento.revisao', 'pagamento.finalizar'];
            if (in_array($request->route()->getName(), $postCepRoutes)) {
                if (!session()->has('endereco_entrega')) {
                    return redirect()
                        ->route('pagamento.cep')
                        ->with('erro', 'Por favor, informe o endereço de entrega para prosseguir.');
                }
            }

            return $next($request);

        } catch (\Exception $e) {
            Log::error('Middleware Checkout Error', [
                'error' => $e->getMessage(),
                'user' => Auth::id()
            ]);

            return redirect()
                ->route('pagamento.erro')
                ->with('erro', 'Erro ao verificar seu pedido.');
        }
    }
}