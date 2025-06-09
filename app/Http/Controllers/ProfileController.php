<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Pedido;
use Darryldecode\Cart\Facades\CartFacade as Cart; // Adicione esta linha
use App\Http\Controllers\FavoritosController; // Adicione esta linha

class ProfileController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function userDashboard(Request $request): View
    {
        $user = Auth::user();

        // Determina qual conteúdo mostrar: 'pedidos' (padrão) ou 'favoritos'
        $show = $request->query('show', 'pedidos'); // Padrão é 'pedidos'

        $data = [
            'user' => $user,
        ];

        if ($show === 'favoritos') {
            // Instancia o FavoritosController para usar o método sincronizarFavoritos
            $favoritosController = new FavoritosController();
            $favoritosController->sincronizarFavoritos(); // Garante que o carrinho de favoritos está atualizado

            $itensFavoritos = Cart::session('favoritos_' . Auth::id())->getContent();
            $totalFavoritos = Cart::session('favoritos_' . Auth::id())->getTotal();

            $data['favoritos'] = $itensFavoritos;
            $data['totalFavoritos'] = $totalFavoritos;
            $data['currentView'] = 'favoritos'; // Para controlar na view qual seção está ativa
        } else {
            // Busca os pedidos do usuário logado, ordenados do mais novo para o mais antigo
            $pedidos = Pedido::where('id_usuario', $user->id)
                ->with('itens.produto.imagens') // Carrega os itens e as imagens dos produtos
                ->orderBy('created_at', 'desc')
                ->get();

            $data['pedidos'] = $pedidos;
            $data['currentView'] = 'pedidos'; // Para controlar na view qual seção está ativa
        }

        return view('home.dashboard', $data); // Renomeei para dashboard se você quiser o mesmo arquivo
    }


    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}