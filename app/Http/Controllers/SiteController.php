<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Produto;
use App\Models\Categoria;


class SiteController extends Controller
{

    public function index()
    {
        $search = request('search');

        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $categoriasMenu = Categoria::whereIn('nome_categoria', ['Conjunto', 'Camisetas', 'Calças', 'Vestidos'])->get();
        $novidades = Produto::orderBy('created_at', 'desc')->take(4)->get();

        if ($search) {
            $produtos = Produto::where('nome_produto', 'like', '%' . $search . '%')->get();

            return view('home.categoria', [
                'produtos' => $produtos,
                'search' => $search,
                'categoriasTopo' => $categoriasTopo,
                'categoriasMenu' => $categoriasMenu,
                'novidades' => $novidades,
            ]);
        }

        // Sem busca, exibe a home padrão
        $produtos = Produto::paginate(4);

        return view('home.index', [
            'categoriasTopo' => $categoriasTopo,
            'categoriasMenu' => $categoriasMenu,
            'produtos' => $produtos,
            'novidades' => $novidades,
        ]);
    }


    public function temporada($temporada)
    {
        $produtos = Produto::where('estacao', ucfirst($temporada))
            ->when(request('modelo'), function ($query, $modelo) {
                return $query->where('modelo', $modelo);
            })
            ->get();

        return view('home.categoria', compact('produtos'));
    }


    public function details($slug)
    {
        $produto = Produto::with(['imagens', 'variacoes.cor', 'variacoes.tamanho'])->where('slug', $slug)->firstOrFail();
        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $produtos = Produto::where('id_produto', '!=', $produto->id_produto)->take(3)->get();

        return view('home.details', compact('produto', 'categoriasTopo', 'produtos'));
    }



    public function categoria($id_categoria, Request $request)
    {
        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $categoriasMenu = Categoria::whereIn('nome_categoria', ['Conjunto', 'Camisetas', 'Calças', 'Vestidos'])->get();

        // Recupera a categoria principal que foi clicada (ex: Categoria 'Conjunto' com id_categoria = 4)
        $categoriaSelecionada = Categoria::findOrFail($id_categoria);

        // Inicializa a query para os produtos da categoria selecionada
        $produtosQuery = $categoriaSelecionada->produtos();

        // Verifica se há um filtro de "gênero" (Menino/Menina) na URL
        // No seu caso, 'genero' virá como 'Masculino' ou 'Feminino' via URL.
        // Precisamos mapear isso para os IDs das categorias.
        $generoParam = $request->query('genero'); // Use query() para parâmetros de URL

        if ($generoParam) {
            $categoriaGeneroId = null;
            if ($generoParam === 'Masculino') {
                // ID da categoria 'Menino'
                $categoriaGeneroId = Categoria::where('nome_categoria', 'Menino')->value('id_categoria');
            } elseif ($generoParam === 'Feminino') {
                // ID da categoria 'Menina'
                $categoriaGeneroId = Categoria::where('nome_categoria', 'Menina')->value('id_categoria');
            }

            // Se um ID de categoria de gênero foi encontrado, adicione o filtro
            if ($categoriaGeneroId) {
                $produtosQuery->whereHas('categorias', function ($q) use ($categoriaGeneroId) {
                    $q->where('categorias.id_categoria', $categoriaGeneroId);
                });
            }
        }

        // Aplica o filtro de 'modelo' se presente
        $produtosQuery->when(request('modelo'), function ($query, $modelo) {
            return $query->where('modelo', $modelo);
        });

        // Obtém os produtos finalizando a query
        $produtos = $produtosQuery->get();

        // A variável $search não está sendo definida aqui, você pode removê-la do compact
        // ou adicionar uma lógica para ela se for usada em outro lugar para a busca geral.
        // Por enquanto, vou considerar que $search é para a busca geral e não para categorias específicas.
        $search = null; // Defina como null ou remova se não for usado

        return view('home.categoria', compact('categoriaSelecionada', 'produtos', 'categoriasTopo', 'categoriasMenu', 'search'));
    }


    public function novidades()
    {
        // Buscar os últimos 4 produtos inseridos no banco
        $novidades = Produto::latest()->take(4)->get();

        // Passar os produtos para a view
        return view('sua-view', compact('novidades'));
    }


}
