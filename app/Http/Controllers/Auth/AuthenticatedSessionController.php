<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    protected function authenticated(Request $request, $user)
    {
        // Carrega o carrinho salvo no banco para a sessão atual
        $carrinho = Carrinho::where('id_usuario', $user->id)->first();

        if ($carrinho && $carrinho->conteudo) {
            $itens = json_decode($carrinho->conteudo, true);

            // Limpa o carrinho atual da sessão
            \Cart::clear();

            // Adiciona todos os itens salvos
            foreach ($itens as $item) {
                \Cart::add($item);
            }

            \Log::debug('Carrinho recuperado do banco para o usuário: ' . $user->id);
        }

        return redirect()->intended($this->redirectPath());
    }
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->access_level === 'admin') {
            return redirect()->route('adm.dashboard');
        }

        return redirect()->route('home.index');
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
