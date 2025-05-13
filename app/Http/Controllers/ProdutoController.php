<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    /**
     * Exibe os produtos, com ou sem filtro por estação.
     */
    public function index(Request $request)
    {
        $query = Produto::query();

        if ($request->has('estacao') && $request->estacao != '') {
            $query->where('estacao', $request->estacao);
        }

        $produtos = $query->paginate(4); // ou ->get() se não quiser paginação
        return view('home.index', compact('produtos'));
    }

    /**
     * Mostra o formulário de cadastro (se for usar uma rota tipo GET /produtos/create).
     */
    public function create()
    {
        return view('produtos.create'); // caso tenha uma view separada
    }

    /**
     * Armazena um novo produto no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nome_produto' => 'required|string',
            'tipo' => 'required|string',
            'variacao' => 'nullable|string',
            'cor' => 'nullable|string',
            'estacao' => 'required|string|in:primavera,verao,outono,inverno',
            'marca' => 'required|string',
            'preco' => 'required|numeric',
            'tamanho' => 'nullable|string',
            'estoque' => 'required|integer',
            'tecido' => 'nullable|string',
            'modelo' => 'nullable|string',
        ]);

        // Salvar a imagem no storage
        $path = $request->file('foto')->store('produtos', 'public');

        // Criar o produto
        Produto::create([
            'foto' => $path,
            'nome_produto' => $request->nome_produto,
            'tipo' => $request->tipo,
            'variacao' => $request->variacao,
            'cor' => $request->cor,
            'estacao' => $request->estacao,
            'marca' => $request->marca,
            'preco' => $request->preco,
            'tamanho' => $request->tamanho,
            'estoque' => $request->estoque,
            'tecido' => $request->tecido,
            'modelo' => $request->modelo,
        ]);

        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
