<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Favorito;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;

class FavoritosController extends Controller
{
    public function favoritosLista()
    {
        // Sincroniza com o banco de dados
        $this->sincronizarFavoritos();

        $itens = \Cart::session('favoritos_' . Auth::id())->getContent();
        $total = \Cart::session('favoritos_' . Auth::id())->getTotal();

        return view('home.favoritos', compact('itens', 'total'));
    }

    public function adicionaFavoritos(Request $request)
    {
        try {
            $produto = Produto::findOrFail($request->id);

            // Adiciona ao banco de dados
            Favorito::firstOrCreate([
                'user_id' => Auth::id(),
                'produto_id' => $produto->id_produto
            ]);

            // Adiciona ao carrinho de favoritos
            \Cart::session('favoritos_' . Auth::id())->add([
                'id' => $produto->id_produto,
                'name' => $produto->nome_produto,
                'price' => $produto->preco,
                'quantity' => 1,
                'attributes' => [
                    'image' => $produto->imagens->first()->caminho,
                    'product_id' => $produto->id_produto
                ]
            ]);

            return redirect()->route('home.favoritos')->with('sucesso', 'Produto adicionado aos favoritos!');

        } catch (\Exception $e) {
            return back()->with('erro', 'Erro ao adicionar aos favoritos: ' . $e->getMessage());
        }
    }

    public function removeFavoritos(Request $request)
    {
        // Remove do banco de dados
        Favorito::where('user_id', Auth::id())
            ->where('produto_id', $request->id)
            ->delete();

        // Remove do carrinho de favoritos
        Cart::session('favoritos_' . Auth::id())->remove($request->id);

        return redirect()->route('home.favoritos')->with('sucesso', 'Produto removido dos favoritos com sucesso!');
    }

    public function limparFavoritos()
    {
        // Limpa o banco de dados
        Favorito::where('user_id', Auth::id())->delete();

        // Limpa o carrinho de favoritos
        Cart::session('favoritos_' . Auth::id())->clear();

        return redirect()->route('home.favoritos')->with('aviso', 'Seus favoritos estão vazios!');
    }

    protected function sincronizarFavoritos()
    {
        if (Auth::check()) {
            $favoritosDb = Favorito::with('produto.imagens')
                ->where('user_id', Auth::id())
                ->get();

            $cart = \Cart::session('favoritos_' . Auth::id());
            $cart->clear();

            foreach ($favoritosDb as $favorito) {
                $produto = $favorito->produto;
                $cart->add([
                    'id' => $produto->id_produto,
                    'name' => $produto->nome_produto,
                    'price' => $produto->preco,
                    'quantity' => 1,
                    'attributes' => [
                        'image' => $produto->imagens->first()->caminho,
                        'product_id' => $produto->id_produto
                    ]
                ]);
            }
        }
    }
}