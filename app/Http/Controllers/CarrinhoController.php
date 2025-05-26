<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamanho;
use App\Models\Cor;
use App\Models\Produto; // Adicione esta linha
use App\Models\Carrinho; // Se você ainda não tiver
use Illuminate\Support\Facades\Auth; // Para usar o Auth
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CarrinhoController extends Controller
{
    public function carrinhoLista()
    {
        $itens = \Cart::getContent();

        foreach ($itens as $item) {
            if (isset($item->attributes['cor_id'])) {
                $item->cor = Cor::find($item->attributes['cor_id']);
            }
            if (isset($item->attributes['tamanho_id'])) {
                $item->tamanho = Tamanho::find($item->attributes['tamanho_id']);
            }
        }

        return view('home.carrinho', compact('itens'));
    }

    public function adicionaCarrinho(Request $request)
    {
        \Log::debug('Dados recebidos no adicionaCarrinho:', $request->all());

        try {
            $validated = $request->validate([
                'id' => 'required|exists:produtos,id_produto',
                'name' => 'required',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:1',
                'cor_id' => 'required|exists:cores,id',
                'tamanho_id' => 'required|exists:tamanhos,id',
                'img' => 'required'
            ]);

            \Log::debug('Dados validados:', $validated);

            // Busca o produto com as imagens relacionadas
            $produto = Produto::with('imagens')->findOrFail($validated['id']);

            // Obtém a imagem principal ou a primeira imagem disponível
            $mainImage = $produto->imagens->where('principal', true)->first() ?? $produto->imagens->first();

            // Adiciona o item ao carrinho
            \Cart::add([
                'id' => $validated['id'] . '-' . $validated['cor_id'] . '-' . $validated['tamanho_id'],
                'name' => $validated['name'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'attributes' => [
                    'image' => $mainImage ? $mainImage->caminho : $validated['img'],
                    'cor_id' => $validated['cor_id'],
                    'tamanho_id' => $validated['tamanho_id'],
                    'product_id' => $validated['id']
                ]
            ]);

            \Log::debug('Conteúdo atual do carrinho:', \Cart::getContent()->toArray());

            // Se o usuário está logado, salva o carrinho no banco de dados
            if (Auth::check()) {
                $this->salvarCarrinhoNoBanco(Auth::id());
            }

            return redirect()->route('home.carrinho')->with('sucesso', 'Produto adicionado ao carrinho!');

        } catch (\Exception $e) {
            \Log::error('Erro ao adicionar ao carrinho:', ['error' => $e->getMessage()]);
            return back()->with('erro', 'Erro ao adicionar produto ao carrinho: ' . $e->getMessage());
        }
    }

    public function removeCarrinho(Request $request)
    {
        try {
            \Cart::remove($request->id);

            // Atualiza o carrinho no banco se o usuário estiver logado
            if (Auth::check()) {
                $this->salvarCarrinhoNoBanco(Auth::id());
            }

            return redirect()->route('home.carrinho')->with('sucesso', 'Produto removido do carrinho com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao remover do carrinho:', ['error' => $e->getMessage()]);
            return back()->with('erro', 'Erro ao remover produto: ' . $e->getMessage());
        }
    }

    public function atualizaCarrinho(Request $request)
    {
        try {
            \Cart::update($request->id, [
                'quantity' => [
                    'relative' => false,
                    'value' => abs($request->quantity),
                ],
            ]);

            // Atualiza o carrinho no banco se o usuário estiver logado
            if (Auth::check()) {
                $this->salvarCarrinhoNoBanco(Auth::id());
            }

            return redirect()->route('home.carrinho')->with('sucesso', 'Quantidade atualizada com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar carrinho:', ['error' => $e->getMessage()]);
            return back()->with('erro', 'Erro ao atualizar quantidade: ' . $e->getMessage());
        }
    }

    public function limparCarrinho(Request $request)
    {
        try {
            \Cart::clear();

            // Limpa o carrinho no banco se o usuário estiver logado
            if (Auth::check()) {
                Carrinho::where('id_usuario', Auth::id())->delete();
            }

            return redirect()->route('home.carrinho')->with('aviso', 'Seu carrinho está vazio!');

        } catch (\Exception $e) {
            \Log::error('Erro ao limpar carrinho:', ['error' => $e->getMessage()]);
            return back()->with('erro', 'Erro ao limpar carrinho: ' . $e->getMessage());
        }
    }

    protected function salvarCarrinhoNoBanco($userId)
    {
        try {
            $cartContent = \Cart::getContent()->toArray();

            Carrinho::updateOrCreate(
                ['id_usuario' => $userId],
                ['conteudo' => json_encode($cartContent)]
            );

            \Log::debug('Carrinho salvo no banco para o usuário: ' . $userId);

        } catch (\Exception $e) {
            \Log::error('Erro ao salvar carrinho no banco:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
