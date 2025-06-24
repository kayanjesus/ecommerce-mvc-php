<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamanho;
use App\Models\Cor;
use App\Models\Produto;
use App\Models\Carrinho;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Adicione este use

class CarrinhoController extends Controller
{
    // Remova o método carrinhoLista ou renomeie-o se ele não for mais usado
    // public function carrinhoLista() { ... }

    public function adicionaCarrinho(Request $request)
    {
        Log::debug('Dados recebidos no adicionaCarrinho:', $request->all());

        try {
            $validated = $request->validate([
                'id' => 'required|exists:produtos,id_produto',
                'name' => 'required',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:1',
                'cor_id' => 'sometimes|exists:cores,id_cor',
                'tamanho_id' => 'sometimes|exists:tamanhos,id_tamanho',
                'img' => 'required'
            ]);

            Log::debug('Dados validados:', $validated);

            $produto = Produto::with('imagens')->findOrFail($validated['id']);
            $mainImage = $produto->imagens->where('principal', true)->first() ?? $produto->imagens->first();

            $userId = Auth::id();

            \Cart::session($userId)->add([
                'id' => $validated['id'] . (isset($validated['cor_id']) ? '-' . $validated['cor_id'] : '') . (isset($validated['tamanho_id']) ? '-' . $validated['tamanho_id'] : ''),
                'name' => $validated['name'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'attributes' => [
                    'image' => $mainImage ? $mainImage->caminho : $validated['img'],
                    'cor_id' => $validated['cor_id'] ?? null,
                    'tamanho_id' => $validated['tamanho_id'] ?? null,
                    'product_id' => $validated['id']
                ]
            ]);

            Log::debug('Conteúdo atual do carrinho (após adição):', \Cart::session($userId)->getContent()->toArray());

            if (Auth::check()) {
                $this->salvarCarrinhoNoBanco($userId);
            }

            // Redireciona sempre para a rota que exibe a página de CEP/carrinho (agora no PagamentoController)
            return redirect()->route('pagamento.cep')->with('sucesso', 'Produto adicionado ao carrinho!');

        } catch (\Exception $e) {
            Log::error('Erro ao adicionar ao carrinho:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('erro', 'Erro ao adicionar produto ao carrinho: ' . $e->getMessage());
        }
    }

    protected function salvarCarrinhoNoBanco($userId)
    {
        try {
            $cartContent = \Cart::session($userId)->getContent()->toArray();

            if (empty($cartContent)) {
                Carrinho::where('id_usuario', $userId)->delete();
            } else {
                Carrinho::updateOrCreate(
                    ['id_usuario' => $userId],
                    ['conteudo' => json_encode($cartContent)]
                );
            }

            Log::debug('Carrinho salvo no banco para o usuário: ' . $userId);

        } catch (\Exception $e) {
            Log::error('Erro ao salvar carrinho no banco:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    public function removeCarrinho(Request $request)
    {
        try {
            $userId = Auth::id();
            \Cart::session($userId)->remove($request->id);

            if (Auth::check()) {
                $this->salvarCarrinhoNoBanco($userId);
            }

            // Se o carrinho ficar vazio APÓS a remoção, redirecione para a Home.
            // Caso contrário, redirecione de volta para a página de CEP/carrinho (no PagamentoController).
            if (\Cart::session($userId)->isEmpty()) {
                return redirect()->route('home.index')->with('aviso', 'Seu carrinho está vazio!');
            }

            return redirect()->route('pagamento.cep')->with('sucesso', 'Produto removido do carrinho com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao remover do carrinho:', ['error' => $e->getMessage()]);
            return back()->with('erro', 'Erro ao remover produto: ' . $e->getMessage());
        }
    }

    public function atualizaCarrinho(Request $request)
    {
        try {
            $userId = Auth::id();
            \Cart::session($userId)->update($request->id, [
                'quantity' => [
                    'relative' => false,
                    'value' => abs($request->quantity),
                ],
            ]);

            if (Auth::check()) {
                $this->salvarCarrinhoNoBanco($userId);
            }

            return redirect()->route('pagamento.cep')->with('sucesso', 'Quantidade atualizada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar carrinho:', ['error' => $e->getMessage()]);
            return back()->with('erro', 'Erro ao atualizar quantidade: ' . $e->getMessage());
        }
    }

    public function limparCarrinho(Request $request)
    {
        try {
            $userId = Auth::id();
            \Cart::session($userId)->clear();

            if (Auth::check()) {
                Carrinho::where('id_usuario', $userId)->delete();
            }

            return redirect()->route('home.index')->with('aviso', 'Seu carrinho está vazio!');

        } catch (\Exception $e) {
            Log::error('Erro ao limpar carrinho:', ['error' => $e->getMessage()]);
            return back()->with('erro', 'Erro ao limpar carrinho: ' . $e->getMessage());
        }
    }
}