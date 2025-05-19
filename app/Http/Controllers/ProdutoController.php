<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Cor;
use App\Models\Tamanho;
use App\Models\EstoqueDetalhado;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::paginate(4);
        return view('home.index', compact('produtos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $cores = Cor::all();
        $tamanhos = Tamanho::all();

        return view('produtos.create', compact('categorias', 'cores', 'tamanhos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome_produto' => 'required',
            'slug' => 'required|unique:produtos,slug',
            'descricao' => 'nullable',
            'genero' => 'required',
            'preco' => 'required|numeric',
            'img' => 'required|image',
            'categorias' => 'array',
            'cores' => 'array',
            'tamanhos' => 'array',
        ]);

        $imagem = $request->file('img')->store('produtos', 'public');

        $produto = Produto::create([
            'nome_produto' => $request->nome_produto,
            'slug' => $request->slug,
            'descricao' => $request->descricao,
            'genero' => $request->genero,
            'preco' => $request->preco,
            'img' => $imagem,
        ]);

        $produto->categorias()->sync($request->categorias);
        $produto->cores()->sync($request->cores);
        $produto->tamanhos()->sync($request->tamanhos);

        return redirect()->route('produtos.create')->with('success', 'Produto cadastrado com sucesso!');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
