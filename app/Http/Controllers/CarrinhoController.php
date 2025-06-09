<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamanho;
use App\Models\Cor;
use App\Models\Produto; // Adicione esta linha
use App\Models\Carrinho; // Se você ainda não tiver
use Illuminate\Support\Facades\Auth; // Para usar o Auth
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Storage;

class CarrinhoController extends Controller
{
    public function carrinhoLista()
    {
        // Certifique-se de que estamos usando a sessão do usuário logado consistentemente
        $itensCarrinho = \Cart::session(Auth::id())->getContent();
        $totalCarrinho = \Cart::session(Auth::id())->getTotal();

        // Use $itensCarrinho que já pegou da sessão correta
        $itens = $itensCarrinho; // <<< CORREÇÃO AQUI!

        foreach ($itens as $item) {
            if (isset($item->attributes['cor_id'])) {
                $item->cor = Cor::find($item->attributes['cor_id']);
            }
            if (isset($item->attributes['tamanho_id'])) {
                $item->tamanho = Tamanho::find($item->attributes['tamanho_id']);
            }
            // Não se esqueça de adicionar a lógica para a imagem aqui também,
            // caso ela não esteja sendo resolvida corretamente na view, embora
            // o log já mostre que ela está no atributo.
            // Ex: $item->image = $item->attributes->image;
        }

        // Passamos 'itens' para a view, que agora contém os itens da sessão correta
        return view('home.carrinho', compact('itens', 'totalCarrinho')); // Opcional: passe totalCarrinho também
    }

    // ... (restante do seu controlador sem alterações nesta parte)

    public function adicionaCarrinho(Request $request)
    {
        \Log::debug('Dados recebidos no adicionaCarrinho:', $request->all());

        try {
            $validated = $request->validate([
                'id' => 'required|exists:produtos,id_produto',
                'name' => 'required',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:1',
                // 'cor_id' e 'tamanho_id' podem ser opcionais se nem todo produto tiver
                'cor_id' => 'sometimes|exists:cores,id', // Use 'sometimes'
                'tamanho_id' => 'sometimes|exists:tamanhos,id', // Use 'sometimes'
                'img' => 'required'
            ]);

            \Log::debug('Dados validados:', $validated);

            $produto = Produto::with('imagens')->findOrFail($validated['id']);
            $mainImage = $produto->imagens->where('principal', true)->first() ?? $produto->imagens->first();

            // Adiciona o item ao carrinho na sessão do usuário logado
            \Cart::session(Auth::id())->add([ // <<< Garanta que esta linha está assim
                // O ID do item no carrinho deve ser único para a combinação produto+cor+tamanho
                // Isso evita que, ao adicionar o mesmo produto com cor/tamanho diferente, ele apenas atualize a quantidade
                'id' => $validated['id'] . (isset($validated['cor_id']) ? '-' . $validated['cor_id'] : '') . (isset($validated['tamanho_id']) ? '-' . $validated['tamanho_id'] : ''),
                'name' => $validated['name'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'attributes' => [
                    'image' => $mainImage ? $mainImage->caminho : $validated['img'],
                    'cor_id' => $validated['cor_id'] ?? null, // Use null se não existir
                    'tamanho_id' => $validated['tamanho_id'] ?? null, // Use null se não existir
                    'product_id' => $validated['id']
                ]
            ]);

            \Log::debug('Conteúdo atual do carrinho (após adição):', \Cart::session(Auth::id())->getContent()->toArray()); // <<< Verifique a sessão aqui também

            if (Auth::check()) {
                $this->salvarCarrinhoNoBanco(Auth::id());
            }

            return redirect()->route('home.carrinho')->with('sucesso', 'Produto adicionado ao carrinho!');

        } catch (\Exception $e) {
            \Log::error('Erro ao adicionar ao carrinho:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('erro', 'Erro ao adicionar produto ao carrinho: ' . $e->getMessage());
        }
    }


    // ... (removeCarrinho, atualizaCarrinho, limparCarrinho, salvarCarrinhoNoBanco - verificar essas funções também)

    // Revise também seu método salvarCarrinhoNoBanco para garantir que ele salve o conteúdo da sessão Auth::id()
    protected function salvarCarrinhoNoBanco($userId)
    {
        try {
            $cartContent = \Cart::session($userId)->getContent()->toArray(); // <<< Correção: use a sessão do usuário

            if (empty($cartContent)) {
                Carrinho::where('id_usuario', $userId)->delete();
            } else {
                Carrinho::updateOrCreate(
                    ['id_usuario' => $userId],
                    ['conteudo' => json_encode($cartContent)]
                );
            }

            \Log::debug('Carrinho salvo no banco para o usuário: ' . $userId);

        } catch (\Exception $e) {
            \Log::error('Erro ao salvar carrinho no banco:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
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


}
