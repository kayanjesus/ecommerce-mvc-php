<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritosController extends Controller
{
    public function favoritosLista()
    {
        $itens = \Cart::session('favoritos')->getContent();
        return view('home.favoritos', compact('itens'));
    }
    

    public function adicionaFavoritos(Request $request)
    {
        \Cart::session('favoritos')->add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => abs($request->qnt ?? 1),
            'attributes' => array(
                'image' => $request->img
            )
        ]);
        return redirect()->route('home.favoritos')->with('sucesso', 'Produto adiconado no favoritos com sucesso!');

    }

    public function removeFavoritos(Request $request)
    {
        \Cart::session('favoritos')->remove($request->id);
        return redirect()->route('home.favoritos')->with('sucesso', 'Produto removido do favoritos com sucesso!');

    }


    public function atualizaFavoritos(Request $request)
    {
        \Cart::session('favoritos')->update($request->id, [
            'quantity' => [
                'relative' => false,
                'value' => abs($request->quantity),
            ],
        ]);
        return redirect()->route('home.favoritos')->with('sucesso', 'Produto atualizado do favoritos com sucesso!');

    }

    public function limparFavoritos(Request $request)
    {
        \Cart::session('favoritos')->clear();
        return redirect()->route('home.favoritos')->with('aviso', 'Seu favoritos esta vazio!');

    }
}