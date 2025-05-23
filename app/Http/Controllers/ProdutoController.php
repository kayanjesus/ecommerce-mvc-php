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
        // Debug inicial
        // dd($request->all());

        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'tipo' => 'required|string|max:255',
                'descricao' => 'required|string|max:255',
                'cores' => 'required|array',
                'cores.*' => 'string|max:255',
                'tamanhos' => 'required|array',
                'tamanhos.*' => 'string|max:50',
                'categorias' => 'required|array',
                'categorias.*' => 'exists:categorias,id_categoria',
                // 'estacao' => 'required|string|max:255',
                'marca' => 'required|string|max:255',
                'valor' => 'required|numeric',
                'estoque' => 'required|integer|min:0',
                'tecido' => 'required|string|max:255',
                'genero' => 'required|string|max:50',
                'imagens' => 'required|array',
                'imagens.*' => 'image|max:2048',
            ]);


            // Upload da imagem
            // if ($request->hasFile('imagem')) {
            //     $path = $request->file('imagem')->store('produtos', 'public');
            // } else {
            //     throw new \Exception('Nenhuma imagem foi enviada');
            // }
            // Criação do produto
            $produto = Produto::create([
                'nome_produto' => $request->nome,
                'tipo' => $request->tipo,
                'slug' => Str::slug($request->nome),
                'descricao' => $request->descricao,
                'marca' => $request->marca,
                'genero' => $request->genero,
                'preco' => $request->valor,
                'tecido' => $request->tecido,
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

            foreach ($cores as $cor) {
                foreach ($tamanhos as $tamanho) {
                    ProdutoVariacoes::create([
                        'produto_id' => $produto->id_produto,
                        'cor_id' => $cor->id,
                        'tamanho_id' => $tamanho->id,
                        'estoque' => $request->estoque,
                        'preco' => $request->valor ?? null,
                    ]);
                }
            }


            return redirect()->route('adm.cdtproduto')->with('success', 'Produto cadastrado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao cadastrar produto: ' . $e->getMessage()]);
        }
    }

    // Métodos placeholders caso precise no futuro
    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
    }
    public function update(Request $request, string $id)
    {
    }
    public function destroy(string $id)
    {
    }
}
