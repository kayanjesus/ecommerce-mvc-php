<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Favorito;
use Darryldecode\Cart\Facades\CartFacade as Cart; // Verifique se esta linha está correta
use Illuminate\Support\Facades\{Log};
use Illuminate\Support\Facades\Auth;

class FavoritosController extends Controller
{
    // Este método agora pode ser opcional se o dashboard exibir os favoritos.
    // Se você quiser uma página de favoritos dedicada, mantenha-o.

    public function adicionaFavoritos(Request $request)
    {
        try {
            $produto = Produto::findOrFail($request->id);

            Favorito::firstOrCreate([
                'user_id' => Auth::id(),
                'produto_id' => $produto->id_produto
            ]);

            \Cart::session('favoritos_' . Auth::id())->add([
                'id' => $produto->id_produto,
                'name' => $produto->nome_produto,
                'price' => $produto->preco,
                'quantity' => 1,
                'attributes' => [
                    'image' => $produto->imagens->first()->caminho ?? 'caminho/para/imagem_default.jpg', // Adicione um fallback para a imagem
                    'product_id' => $produto->id_produto
                ]
            ]);

            // Redireciona para o dashboard mostrando favoritos
            return redirect()->route('home.dashboard', ['show' => 'favoritos'])->with('sucesso', 'Produto adicionado aos favoritos!');

        } catch (\Exception $e) {
            return back()->with('erro', 'Erro ao adicionar aos favoritos: ' . $e->getMessage());
        }
    }

    public function removeFavoritos(Request $request)
    {
        Favorito::where('user_id', Auth::id())
            ->where('produto_id', $request->id)
            ->delete();

        Cart::session('favoritos_' . Auth::id())->remove($request->id);

        // Redireciona para o dashboard mostrando favoritos
        return redirect()->route('home.dashboard', ['show' => 'favoritos'])->with('sucesso', 'Produto removido dos favoritos com sucesso!');
    }

    public function limparFavoritos()
    {
        Favorito::where('user_id', Auth::id())->delete();

        Cart::session('favoritos_' . Auth::id())->clear();

        // Redireciona para o dashboard mostrando favoritos
        return redirect()->route('home.dashboard', ['show' => 'favoritos'])->with('aviso', 'Seus favoritos estão vazios!');
    }

    /**
     * Sincroniza os favoritos do banco de dados com o carrinho de sessão.
     * Deve ser acessível por outros controladores.
     */


    public function sincronizarFavoritos()
    {
        if (Auth::check()) {
            $favoritosDb = Favorito::with('produto.imagens')
                ->where('user_id', Auth::id())
                ->get();

            $cart = Cart::session('favoritos_' . Auth::id());
            $cart->clear();

            foreach ($favoritosDb as $favorito) {
                $produto = $favorito->produto;
                if ($produto) { // Garante que o produto existe
                    $cart->add([
                        'id' => $produto->id_produto,
                        'name' => $produto->nome_produto,
                        'price' => $produto->preco,
                        'quantity' => 1,
                        'attributes' => [
                            'image' => $produto->imagens->first()->caminho ?? 'caminho/para/imagem_default.jpg', // Fallback
                            'product_id' => $produto->id_produto
                        ]
                    ]);
                }
            }
        }
    }
}