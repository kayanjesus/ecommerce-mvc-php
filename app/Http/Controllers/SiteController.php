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

        // Buscando os produtos
        $produtos = Produto::paginate(4); // ou alguma lógica mais específica

        return view('home.index', compact('categoriasTopo', 'categoriasMenu', 'produtos'));
    }


    // public function index()
    // {
    //     // return "index";
    //     $produtos = Produto::paginate(4);
    //     return view('home.index', compact('produtos'));
    // }

    public function details($slug)
    {
        $produto = Produto::where('slug', $slug)->first(); // adiciona o firstOrFail
        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        return view('home.details', compact('produto', 'categoriasTopo'));
    }

    public function categoria($id_categoria)
    {
        $categoriasTopo = Categoria::whereIn('nome_categoria', ['Bebê', 'Menina', 'Menino'])->get();
        $categoriasMenu = Categoria::whereIn('nome_categoria', ['Conjunto', 'Camisetas', 'Calças', 'Vestidos'])->get();
    
        $categoria = Categoria::with('produtos')->findOrFail($id_categoria);
        $produtos = $categoria->produtos;
    
        return view('home.categoria', compact('categoria', 'produtos', 'categoriasTopo', 'categoriasMenu'));
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
