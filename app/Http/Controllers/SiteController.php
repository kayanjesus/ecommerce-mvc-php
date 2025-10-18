<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Cor;
use App\Models\Tamanho;
use App\Models\Avaliacao;
use App\Models\User;
use Carbon\Carbon;

class SiteController extends Controller
{
    public function index()
    {
        $search = request('search');

        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $categoriasMenu = Categoria::whereIn('nome_categoria', ['Conjunto', 'Camisetas', 'Calças', 'Vestidos'])->get();
        $novidades = Produto::orderBy('created_at', 'desc')->take(4)->get();

        $avaliacoes = Avaliacao::with('usuario')
            ->whereNotNull('comentario')
            ->where('nota', '>=', 4)
            ->latest()
            ->take(6)
            ->get();

        if ($search) {
            return redirect()->route('home.categoria', ['id_categoria' => 0, 'search' => $search]);
        }

        $produtos = Produto::paginate(4);

        return view('home.index', [
            'categoriasTopo' => $categoriasTopo,
            'categoriasMenu' => $categoriasMenu,
            'produtos' => $produtos,
            'novidades' => $novidades,
            'avaliacoes' => $avaliacoes,
        ]);
    }

    public function temporada($temporada)
    {
        $produtosQuery = Produto::where('estacao', ucfirst($temporada));

        if (request('modelo')) {
            $produtosQuery->where('modelo', request('modelo'));
        }

        $produtos = $produtosQuery->with(['imagens', 'variacoes.cor', 'variacoes.tamanho'])->get();

        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $categoriasMenu = Categoria::all();

        $todasCategorias = Categoria::all();
        $cores = Cor::orderBy('nome')->get();
        $marcas = Produto::distinct()->pluck('marca')->filter()->values()->all();
        $tamanhos = Tamanho::orderBy('nome')->get();
        $generos = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->pluck('nome_categoria')->toArray();

        $categoriaSelecionada = (object) ['id_categoria' => 0, 'nome_categoria' => 'Coleção ' . ucfirst($temporada)];

        return view('home.categoria', compact('produtos', 'categoriasTopo', 'categoriasMenu', 'todasCategorias', 'cores', 'marcas', 'tamanhos', 'generos', 'categoriaSelecionada'));
    }


    public function show($slug)
    {
        $produto = Produto::where('slug', $slug)
            ->with(['imagens', 'variacoes.tamanho', 'variacoes.cor'])
            // Adicione avaliacoes e o usuário que fez a avaliação (se necessário)
            ->with([
                'avaliacoes' => function ($query) {
                    $query->with('usuario')->latest(); // Ordena pelas mais recentes
                }
            ])
            ->firstOrFail();

        return view('home.details', compact('produto'));
    }
    public function produtoDetalhes(Produto $produto)
    {
        // Otimização: Carregue as avaliações e o usuário que as fez junto com o produto
        // Isso é mais eficiente do que carregar na view.
        $produto->load('avaliacoes.usuario');

        // NENHUMA BUSCA GLOBAL DE AVALIAÇÕES É NECESSÁRIA AQUI.
        // $avaliacoes = Avaliacao::all(); // ISSO SERIA ERRADO

        return view('details.blade.php', compact('produto'));
        // Agora o $produto já tem os dados de avaliações para uso na view.
    }
    public function details($slug)
    {
        $produto = Produto::where('slug', $slug)
            ->with([
                'variacoes.cor',
                'variacoes.tamanho',
                'imagens',
                // 1. Carrega todas as avaliações e o usuário que as fez
                'avaliacoes.usuario',
            ])
            // 2. Calcula a média das notas e o total de avaliações.
            // As propriedades 'avaliacoes_avg_nota' e 'avaliacoes_count' serão adicionadas ao objeto $produto
            ->withAvg('avaliacoes', 'nota') // Adiciona $produto->avaliacoes_avg_nota
            ->withCount('avaliacoes')     // Adiciona $produto->avaliacoes_count
            ->firstOrFail();

        // Lógica para produtos relacionados
        $produtos = Produto::where('id_produto', '!=', $produto->id_produto)
            ->where('estacao', $produto->estacao)
            ->limit(4)
            ->get();

        $categoriasMenu = Categoria::all();

        // 3. Passe tudo para a view
        return view('home.details', compact('produto', 'produtos', 'categoriasMenu'));
    }

    public function categoria($id_categoria, Request $request)
    {
        $produtosQuery = Produto::query();
        $categoriaSelecionada = null; // Inicializa para ser definida dinamicamente

        // Coleta todos os IDs de categorias e gêneros selecionados nos filtros laterais
        $categoriasFiltradasIds = $request->query('categorias', []);
        $generosParam = $request->query('generos', []);

        $idsGeneros = [];
        if (!empty($generosParam)) {
            $idsGeneros = Categoria::whereIn('nome_categoria', $generosParam)->pluck('id_categoria')->toArray();
        }

        // Combina os IDs de categorias e gêneros para uma única lista de filtros de categoria
        $allCategoryFilterIds = array_merge($categoriasFiltradasIds, $idsGeneros);
        // Garante que não haja IDs duplicados
        $allCategoryFilterIds = array_unique($allCategoryFilterIds);


        // Lógica principal para determinar a categoria base da query
        if (!empty($allCategoryFilterIds)) {
            // Se algum filtro de categoria/gênero foi selecionado na sidebar,
            // ele "substitui" ou se torna a base da busca de categoria.
            // Usamos whereIn para buscar produtos que pertençam a QUALQUER UMA das categorias/gêneros selecionados.
            $produtosQuery->whereHas('categorias', function ($q) use ($allCategoryFilterIds) {
                $q->whereIn('categorias.id_categoria', $allCategoryFilterIds);
            });

            // Define a categoria selecionada para exibição, pode ser genérica ou a primeira da lista
            $selectedCategoryNames = Categoria::whereIn('id_categoria', $allCategoryFilterIds)->pluck('nome_categoria')->implode(', ');
            $categoriaSelecionada = (object) ['id_categoria' => 0, 'nome_categoria' => 'Filtrado: ' . $selectedCategoryNames];

        } elseif ($id_categoria == 0) {
            // Se nenhum filtro de categoria/gênero foi selecionado E id_categoria é 0,
            // mostra "Todos os Produtos".
            $categoriaSelecionada = (object) ['id_categoria' => 0, 'nome_categoria' => 'Todos os Produtos'];
            // Não adiciona nenhuma condição de categoria, pois é "todos os produtos"
        } else {
            // Se id_categoria é específico E nenhum filtro de categoria/gênero lateral foi selecionado,
            // filtra apenas pela categoria da URL.
            $categoriaSelecionada = Categoria::findOrFail($id_categoria);
            $produtosQuery->whereHas('categorias', function ($q) use ($id_categoria) {
                $q->where('categorias.id_categoria', $id_categoria);
            });
        }


        $produtosQuery->with(['imagens', 'variacoes.cor', 'variacoes.tamanho']);

        // Aplicação do Filtro de Busca (barra superior ou campo oculto)
        $search = $request->query('search');
        if ($search) {
            $produtosQuery->where('nome_produto', 'like', '%' . $search . '%');
        }

        // Aplicação do Filtro de Cores
        $coresFiltradasIds = $request->query('cores');
        if (!empty($coresFiltradasIds)) {
            $produtosQuery->whereHas('variacoes', function ($q) use ($coresFiltradasIds) {
                $q->whereIn('cor_id', $coresFiltradasIds);
            });
        }

        // Aplicação do Filtro de Marcas
        $marcasFiltradas = $request->query('marcas');
        if (!empty($marcasFiltradas)) {
            $produtosQuery->whereIn('marca', $marcasFiltradas);
        }

        // Aplicação do Filtro de Tamanhos
        $tamanhosFiltradosIds = $request->query('tamanhos');
        if (!empty($tamanhosFiltradosIds)) {
            $produtosQuery->whereHas('variacoes', function ($q) use ($tamanhosFiltradosIds) {
                $q->whereIn('tamanho_id', $tamanhosFiltradosIds);
            });
        }

        // O ponto de depuração pode ser útil aqui se ainda houver problemas
        // dd($produtosQuery->toSql(), $produtosQuery->getBindings());

        $produtos = $produtosQuery->paginate(12);

        // Carregar todos os dados para os filtros na sidebar
        $todasCategorias = Categoria::all();
        $cores = Cor::orderBy('nome')->get();
        $marcas = Produto::distinct()->pluck('marca')->filter()->values()->all();
        $tamanhos = Tamanho::orderBy('nome')->get();
        $generos = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->pluck('nome_categoria')->toArray();

        // Dados para o layout (topo e menu)
        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $categoriasMenu = Categoria::all();

        return view('home.categoria', compact(
            'categoriaSelecionada',
            'produtos',
            'search',
            'todasCategorias',
            'cores',
            'marcas',
            'tamanhos',
            'generos',
            'categoriasTopo',
            'categoriasMenu'
        ));
    }

    public function novidades()
    {
        $novidades = Produto::latest()->take(4)->get();
        return view('sua-view', compact('novidades'));
    }
}