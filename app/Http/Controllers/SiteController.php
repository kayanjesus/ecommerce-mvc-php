<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Produto;
use App\Models\Categoria;

class SiteController extends Controller
{

    public function index()
    {
        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $categoriasMenu = Categoria::whereIn('nome_categoria', ['Conjunto', 'Camisetas', 'Calças', 'Vestidos'])->get();

        // Buscando os produtos (paginados)
        $produtos = Produto::paginate(4);

        // Buscando os últimos 4 produtos inseridos (novidades)
        $novidades = Produto::orderBy('created_at', 'desc')->take(4)->get();

        return view('home.index', compact('categoriasTopo', 'categoriasMenu', 'produtos', 'novidades'));
    }



    // public function index()
    // {
    //     // return "index";
    //     $produtos = Produto::paginate(4);
    //     return view('home.index', compact('produtos'));
    // }

    public function temporada($temporada, Request $request)
    {
        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $categoriasMenu = Categoria::whereIn('nome_categoria', ['Conjunto', 'Camisetas', 'Calças', 'Vestidos'])->get();

        // Definindo o id da categoria conforme a temporada
        $id_categoria = null;
        if ($temporada == 'inverno') {
            $id_categoria = 8; // ID da categoria Inverno
        } elseif ($temporada == 'verao') {
            $id_categoria = 9; // ID da categoria Verão
        }

        // Buscando a categoria pelo ID
        $categoria = Categoria::findOrFail($id_categoria);
        $produtos = $categoria->produtos;

        // Filtrando por gênero, caso seja passado no request
        if ($request->has('genero')) {
            $produtos = $produtos->where('genero', $request->genero);
        }

        // Passando as variáveis para a view home.categoria
        return view('home.categoria', compact('categoria', 'produtos', 'categoriasTopo', 'categoriasMenu'));
    }


    public function details($slug)
    {
        $produto = Produto::where('slug', $slug)->firstOrFail();
        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $produtos = Produto::where('id_produto', '!=', $produto->id_produto)->take(3)->get(); // busca outros produtos relacionados

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

        return view('home.categoria', compact('categoria', 'produtos', 'categoriasTopo', 'categoriasMenu'));
    }


    public function novidades()
    {
        // Buscar os últimos 4 produtos inseridos no banco
        $novidades = Produto::latest()->take(4)->get();

        // Passar os produtos para a view
        return view('sua-view', compact('novidades'));
    }

    // public function categoria($id_categoria)
    // {
    //     $categoria = Categoria::with('produtos')->findOrFail($id_categoria);
    //     $produtos = $categoria->produtos;
    //     return view('home.categoria', compact('categoria', 'produtos'));
    // }

    // public function categoria($id_categoria)
    // {
    //     $categoria = Categoria::with('produtos')->findOrFail($id_categoria); // Carregar produtos junto com a categoria
    //     // dd($categoria); // Verifique se os produtos estão carregados
    //     $produtos = $categoria->produtos; // Relacionamento muitos-para-muitos
    //     return view('home.categoria', compact('categoria', 'produtos'));
    // }


    // public function categoria($id_categoria)
    // {
    //     $categoria = Categoria::findOrFail($id_categoria); // Encontrar a categoria
    //     dd($categoria); // Verifique se a categoria foi carregada corretamente

    //     $produtos = $categoria->produtos; // Relacionamento muitos-para-muitos
    //     return view('home.categoria', compact('categoria', 'produtos'));
    // }


    // public function categoria($id_categoria)
    // {
    //     $categoria = Categoria::findOrFail($id_categoria); // Encontrar a categoria
    //     $produtos = $categoria->produtos; // Relacionamento muitos-para-muitos

    //     return view('home.categoria', compact('categoria', 'produtos'));
    // }



    // public function categoria($id_categoria)
    // {
    //     $categoria = Categoria::findOrFail($id_categoria); // Verifique se a categoria existe
    //     $produtos = Produto::where('categoria_id', $id_categoria)->get(); // Corrija para o nome da chave estrangeira

    //     return view('home.categoria', compact('categoria', 'produtos'));
    // }


    // public function categorias($id_categoria)
    // {
    //     $produtos = Produto::where('id_categoria', $id_categoria)->get(); // adiciona o firstOrFail
    //     return view('home.categoria', compact('produtos'));
    // }

}
