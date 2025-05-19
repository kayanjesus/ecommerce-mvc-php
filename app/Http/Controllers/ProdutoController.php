<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        //
    }

    /**
     * Armazena um novo produto no banco de dados.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
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
