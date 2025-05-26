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

        $categoria = Categoria::with('produtos')->findOrFail($id_categoria);
        $produtos = $categoria->produtos;

        if ($request->has('genero')) {
            $produtos = $produtos->where('genero', $request->genero);
        }

        $produtos = Categoria::find($id_categoria)->produtos()
            ->when(request('modelo'), function ($query, $modelo) {
                return $query->where('modelo', $modelo);
            })
            ->get();

        return view('home.categoria', compact('categoria', 'produtos', 'categoriasTopo', 'categoriasMenu'));
    }


    public function novidades()
    {
        // Buscar os últimos 4 produtos inseridos no banco
        $novidades = Produto::latest()->take(4)->get();

        // Passar os produtos para a view
        return view('sua-view', compact('novidades'));
    }


}
