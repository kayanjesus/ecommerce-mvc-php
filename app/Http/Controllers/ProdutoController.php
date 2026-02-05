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
use Illuminate\Database\QueryException;

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
                'categorias' => 'required|array',
                'categorias.*' => 'exists:categorias,id_categoria',
                'descricao' => 'required|string|max:255',
                'cores' => 'required|array',
                'cores.*' => 'exists:cores,id_cor',
                'tamanhos' => 'required|array',
                'tamanhos.*' => 'exists:tamanhos,id_tamanho',
                'estacao' => 'required|in:Verão,Inverno',
                'marca' => 'required|string|max:255',
                'valor' => 'required|numeric|min:0',
                'estoque' => 'required|integer|min:0',
                'tecido' => 'required|string|max:255',
                'modelo' => 'required|string|max:50',
                'imagens' => 'required|array',
                'imagens.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'main_image_id' => 'nullable|string'
            ]);

            // Gera o slug baseado no nome do produto
            $slugBase = Str::slug($request->nome);
            $slug = $slugBase;
            $counter = 1;

            // Verifica se o slug já existe
            while (Produto::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter;
                $counter++;
            }

            // Cria o produto
            $produto = Produto::create([
                'nome_produto' => $request->nome,
                'tipo' => implode(',', $request->categorias),
                'variacao' => $request->descricao,
                'marca' => $request->marca,
                'preco' => $request->valor,
                'tecido' => $request->tecido,
                'estacao' => $request->estacao,
                'modelo' => $request->modelo,
                'slug' => $slug // Usa o slug verificado/único
            ]);

            // Associa categorias
            $produto->categorias()->attach($request->categorias);

            // Cria variações (cores e tamanhos)
            foreach ($request->cores as $corId) {
                foreach ($request->tamanhos as $tamanhoId) {
                    ProdutoVariacoes::create([
                        'produto_id' => $produto->id_produto,
                        'cor_id' => $corId,
                        'tamanho_id' => $tamanhoId,
                        'estoque' => $request->estoque / (count($request->cores) * count($request->tamanhos)),
                        'preco' => $request->valor,
                    ]);
                }
            }

            // Upload e armazenamento das imagens
            if ($request->hasFile('imagens')) {
                $mainImageId = $request->main_image_id;

                foreach ($request->file('imagens') as $key => $imagem) {
                    $path = $imagem->store('produtos', 'public');

                    // Verifica se esta é a imagem principal
                    $isPrincipal = false;
                    if ($mainImageId === 'new-' . $key) {
                        $isPrincipal = true;
                    }
                    // Se não foi especificada uma imagem principal, a primeira será principal
                    elseif ($key === 0 && empty($mainImageId)) {
                        $isPrincipal = true;
                    }

                    ProdutoImagem::create([
                        'produto_id' => $produto->id_produto,
                        'caminho' => 'storage/' . $path,
                        'principal' => $isPrincipal,
                    ]);
                }
            }

            return redirect()->route('adm.cdtproduto')
                ->with('success', 'Produto cadastrado com sucesso!');

        } catch (QueryException $e) {
            // Captura erros de duplicação (código 1062)
            if ($e->errorInfo[1] == 1062) {
                \Log::warning('Tentativa de cadastrar produto com nome duplicado: ' . $request->nome);

                return back()
                    ->withInput()
                    ->withErrors([
                        'nome' => 'Já existe um produto com este nome. Por favor, escolha um nome diferente ou adicione uma descrição mais específica.',
                        'duplicate_error' => 'nome' // Flag adicional para JavaScript
                    ]);
            }

            // Outros erros do banco
            \Log::error('Erro de banco de dados ao cadastrar produto: ' . $e->getMessage());
            \Log::error('SQL: ' . $e->getSql());
            \Log::error('Bindings: ' . json_encode($e->getBindings()));

            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao conectar com o banco de dados. Por favor, tente novamente.']);

        } catch (\Exception $e) {
            \Log::error('Erro geral ao cadastrar produto: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Ocorreu um erro inesperado. Por favor, tente novamente.']); // CORRIGIDO AQUI
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
                'variacao' => 'required|string|max:255', // Alterado de 'descricao' para 'variacao'
                'cores' => 'required|array',
                'cores.*' => 'exists:cores,id_cor',
                'tamanhos' => 'required|array',
                'tamanhos.*' => 'exists:tamanhos,id_tamanho',
                'estacao' => 'required|in:Verão,Inverno',
                'marca' => 'required|string|max:255',
                'valor' => 'required|numeric|min:0',
                'estoque' => 'required|integer|min:0',
                'tecido' => 'required|string|max:255',
                'modelo' => 'required|string|max:50',
                'descricao' => 'nullable|string',
                'imagens' => 'nullable|array',
                'imagens.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'removed_images' => 'nullable|string',
                'main_image_id' => 'nullable|string'
            ]);

            // Atualiza os dados básicos
            $produto->update([
                'nome_produto' => $request->nome,
                'tipo' => implode(',', $request->categorias),
                'variacao' => $request->variacao,
                'marca' => $request->marca,
                'preco' => $request->valor,
                'tecido' => $request->tecido,
                'estacao' => $request->estacao,
                'modelo' => $request->modelo,
                'descricao' => $request->descricao,
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
                        'estoque' => $request->estoque / (count($request->cores) * count($request->tamanhos)), // Distribui o estoque
                        'preco' => $request->valor,
                    ]);
                }
            }

            // Remove imagens marcadas para exclusão
            if ($request->filled('removed_images')) {
                $removedIds = explode(',', $request->removed_images);
                foreach ($removedIds as $id) {
                    if (is_numeric($id)) {
                        $imagem = ProdutoImagem::find($id);
                        if ($imagem && $imagem->produto_id == $produto->id_produto) {
                            // Remove o arquivo físico
                            $filePath = str_replace('storage/', '', $imagem->caminho);
                            if (Storage::disk('public')->exists($filePath)) {
                                Storage::disk('public')->delete($filePath);
                            }
                            $imagem->delete();
                        }
                    }
                }
            }

            // Atualiza imagem principal
            if ($request->filled('main_image_id')) {
                // Primeiro, remove todas as flags de principal
                ProdutoImagem::where('produto_id', $produto->id_produto)
                    ->update(['principal' => false]);

                $mainImageId = $request->main_image_id;

                if (is_numeric($mainImageId)) {
                    // É uma imagem existente
                    ProdutoImagem::where('id', $mainImageId)
                        ->where('produto_id', $produto->id_produto)
                        ->update(['principal' => true]);
                }
            }

            // Adiciona novas imagens (se enviadas)
            if ($request->hasFile('imagens')) {
                foreach ($request->file('imagens') as $key => $imagem) {
                    $path = $imagem->store('produtos', 'public');

                    // Verifica se esta é a nova imagem principal
                    $isPrincipal = false;
                    if ($request->filled('main_image_id') && $request->main_image_id === 'new-' . $key) {
                        $isPrincipal = true;
                    }

                    // Se não houver imagem principal ainda, a primeira nova imagem será principal
                    if ($key === 0 && !ProdutoImagem::where('produto_id', $produto->id_produto)->where('principal', true)->exists()) {
                        $isPrincipal = true;
                    }

                    ProdutoImagem::create([
                        'produto_id' => $produto->id_produto,
                        'caminho' => 'storage/' . $path,
                        'principal' => $isPrincipal,
                    ]);
                }
            }

            // Se ainda não houver imagem principal após todas as operações, define a primeira como principal
            if (!ProdutoImagem::where('produto_id', $produto->id_produto)->where('principal', true)->exists()) {
                $firstImage = ProdutoImagem::where('produto_id', $produto->id_produto)->first();
                if ($firstImage) {
                    $firstImage->update(['principal' => true]);
                }
            }

            return redirect()->route('adm.pdtestoque')
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar produto: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
        }
    }

    // public function destroy($id)
    // {
    //     $produto = Produto::findOrFail($id);

    //     Remove imagens associadas
    //     foreach ($produto->imagens as $imagem) {
    //         $filePath = str_replace('storage/', '', $imagem->caminho);
    //         if (Storage::disk('public')->exists($filePath)) {
    //             Storage::disk('public')->delete($filePath);
    //         }
    //         $imagem->delete();
    //     }

    //     Remove variações associadas
    //     $produto->variacoes()->delete();

    //     Finalmente, remove o produto
    //     $produto->delete();

    //     return redirect()->route('adm.pdtestoque')->with('success', 'Produto removido com sucesso!');
    // }
}