<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\EstoqueDetalhado;
use Illuminate\Support\Str;
use App\Models\Cor;
use App\Models\Tamanho;
use App\Models\Categoria;
use App\Models\ProdutoVariacoes;
use App\Models\ProdutoImagem;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    /**
     * Exibe os produtos, com ou sem filtro por estação.
     */
    public function index(Request $request)
    {
        $query = Produto::query();

        if ($request->filled('estacao')) {
            $query->where('estacao', $request->estacao);
        }

        $produtos = $query->paginate(4);

        return view('home.index', compact('produtos'));
    }

    /**
     * Mostra o formulário de cadastro.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $cores = Cor::all();
        $tamanhos = Tamanho::all();

        return view('produtos.create', compact('categorias', 'cores', 'tamanhos'));
    }

    /**
     * Armazena um novo produto no banco de dados.
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'categorias' => 'required|array', // Alterado de 'tipo' para 'categorias'
                'categorias.*' => 'exists:categorias,id_categoria',
                'descricao' => 'required|string|max:255', // Alterado de 'variacao' para 'descricao'
                'cores' => 'required|array',
                'cores.*' => 'exists:cores,id_cor', // Mantido pois agora enviamos IDs
                'tamanhos' => 'required|array',
                'tamanhos.*' => 'exists:tamanhos,id_tamanho', // Alterado para validar IDs
                'estacao' => 'required|in:Verão,Inverno',
                'marca' => 'required|string|max:255',
                'valor' => 'required|numeric',
                'estoque' => 'required|integer|min:0',
                'tecido' => 'required|string|max:255',
                'modelo' => 'required|string|max:50',
                'imagens' => 'required|array',
                'imagens.*' => 'image|max:2048',
            ]);

            $produto = Produto::create([
                'nome_produto' => $request->nome,
                'tipo' => implode(',', $request->categorias), // Converte array para string
                'variacao' => $request->descricao, // Usa o campo descricao do form
                'marca' => $request->marca,
                'preco' => $request->valor,
                'tecido' => $request->tecido,
                'estacao' => $request->estacao,
                'modelo' => $request->modelo,
                'slug' => Str::slug($request->nome)
            ]);


            // Upload e armazenamento das imagens
            if ($request->hasFile('imagens')) {
                foreach ($request->file('imagens') as $key => $imagem) {
                    $path = $imagem->store('produtos', 'public');
                    $isPrincipal = ($key === 0); // Define a primeira imagem como principal

                    ProdutoImagem::create([
                        'produto_id' => $produto->id_produto,
                        'caminho' => 'storage/' . $path,
                        'principal' => $isPrincipal,
                    ]);
                }
            }

            // Associa as categorias
            if ($request->has('categorias')) {
                $produto->categorias()->attach($request->categorias);
            }


            // Relacionamento com cor e tamanho
            $cores = Cor::whereIn('nome', $request->cores)->get();
            $tamanhos = Tamanho::whereIn('nome', $request->tamanhos)->get();

            // Relacionamento com cor e tamanho (usando IDs)
            foreach ($request->cores as $corId) {
                foreach ($request->tamanhos as $tamanhoId) {
                    ProdutoVariacoes::create([
                        'produto_id' => $produto->id_produto,
                        'cor_id' => $corId,
                        'tamanho_id' => $tamanhoId,
                        'estoque' => $request->estoque,
                        'preco' => $request->valor,
                    ]);
                }
            }


            return redirect()->route('adm.cdtproduto')->with('success', 'Produto cadastrado!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao cadastrar produto: ' . $e->getMessage()]);
        }
    }

    // Métodos placeholders caso precise no futuro
    public function show($id_produto)
    {
        $produto = Produto::with(['avaliacoes.usuario'])->findOrFail($id_produto);

        // PASSO DE DEBUG 1: CONFIRMAR O ID DO PRODUTO ATUAL
        \Log::info("ID do Produto Visualizado: " . $produto->id_produto);

        // PASSO DE DEBUG 2: VERIFICAR AS AVALIAÇÕES CARREGADAS (SE HOUVER)
        if ($produto->avaliacoes->isEmpty()) {
            \Log::warning("Nenhuma avaliação encontrada para o produto ID: " . $produto->id_produto);
        } else {
            \Log::info("Avaliações encontradas: " . $produto->avaliacoes->count());
            // Se este log retornar um número > 0, o problema está na sua view details.blade.php.
        }

        // DESCOMENTE ESTA LINHA E RECARREGUE A PÁGINA:
        // dd($produto->avaliacoes->toArray()); 

        return view('home.details', [
            'produto' => $produto,
        ]);
    }
    public function edit($id)
    {
        $produto = Produto::with(['imagens', 'variacoes', 'categorias'])->findOrFail($id);
        $categorias = Categoria::all();
        $cores = Cor::all();
        $tamanhos = Tamanho::all();

        $produto->load(['imagens', 'variacoes.cor', 'variacoes.tamanho', 'categorias', 'avaliacao.usuario']);


        return view('adm.edit', compact('produto', 'categorias', 'cores', 'tamanhos'));
    }

    public function update(Request $request, $id)
    {
        try {
            $produto = Produto::findOrFail($id);

            $request->validate([
                'nome' => 'required|string|max:255',
                'categorias' => 'required|array',
                'categorias.*' => 'exists:categorias,id_categoria',
                'descricao' => 'required|string|max:255',
                'cores' => 'required|array',
                'cores.*' => 'exists:cores,id_cor',
                'tamanhos' => 'required|array',
                'tamanhos.*' => 'exists:tamanhos,id_tamanho',
                'estacao' => 'required|in:Verão,Inverno',
                'marca' => 'required|string|max:255',
                'valor' => 'required|numeric',
                'estoque' => 'required|integer|min:0',
                'tecido' => 'required|string|max:255',
                'modelo' => 'required|string|max:50',
                'imagens' => 'sometimes|array',
                'imagens.*' => 'image|max:2048',
                'removed_images' => 'nullable|string', // Alterado para nullable
                'main_image_id' => 'nullable|string'   // Alterado para nullable
            ]);

            // Atualiza os dados básicos
            $produto->update([
                'nome_produto' => $request->nome,
                'tipo' => implode(',', $request->categorias),
                'variacao' => $request->descricao,
                'marca' => $request->marca,
                'preco' => $request->valor,
                'tecido' => $request->tecido,
                'estacao' => $request->estacao,
                'modelo' => $request->modelo,
            ]);

            // Atualiza categorias
            $produto->categorias()->sync($request->categorias);

            // Atualiza variações (cores e tamanhos)
            $produto->variacoes()->delete();
            foreach ($request->cores as $corId) {
                foreach ($request->tamanhos as $tamanhoId) {
                    ProdutoVariacoes::create([
                        'produto_id' => $produto->id_produto,
                        'cor_id' => $corId,
                        'tamanho_id' => $tamanhoId,
                        'estoque' => $request->estoque,
                        'preco' => $request->valor,
                    ]);
                }
            }

            // Remove imagens marcadas para exclusão
            if ($request->filled('removed_images')) {
                $removedIds = explode(',', $request->removed_images);
                foreach ($removedIds as $id) {
                    if (is_numeric($id)) { // Verifica se é um ID válido
                        $imagem = ProdutoImagem::find($id);
                        if ($imagem) {
                            // Remove o arquivo físico
                            Storage::disk('public')->delete(str_replace('storage/', '', $imagem->caminho));
                            $imagem->delete();
                        }
                    }
                }
            }

            // Atualiza imagem principal
            if ($request->filled('main_image_id')) {
                // Remove a principal atual
                ProdutoImagem::where('produto_id', $produto->id_produto)
                    ->update(['principal' => false]);

                // Define a nova principal
                $mainImageId = $request->main_image_id;
                if (is_numeric($mainImageId)) {
                    // É uma imagem existente
                    ProdutoImagem::where('id', $mainImageId)
                        ->update(['principal' => true]);
                }
                // Para novas imagens, a principal será definida abaixo
            }

            // Adiciona novas imagens (se enviadas)
            if ($request->hasFile('imagens')) {
                foreach ($request->file('imagens') as $key => $imagem) {
                    $path = $imagem->store('produtos', 'public');
                    $isPrincipal = ($request->main_image_id === 'new-' . $key);

                    ProdutoImagem::create([
                        'produto_id' => $produto->id_produto,
                        'caminho' => 'storage/' . $path,
                        'principal' => $isPrincipal,
                    ]);
                }
            }

            return redirect()->route('adm.pdtestoque')
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();

        return redirect()->route('adm.pdtestoque')
            ->with('success', 'Produto excluído com sucesso');
    }

}
