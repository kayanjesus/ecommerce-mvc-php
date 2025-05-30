<?php

namespace App\Http\Controllers;
use App\Models\Tamanho;
use App\Models\Categoria;
use App\Models\Cor;
use App\Models\Produto;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
class DashboardController extends Controller
{
    public function index()
    {
        $vendasHoje = Pedido::whereDate('created_at', today())->count();
        $valorRecebido = Pedido::whereDate('created_at', today())->sum('total');
        $avaliacoes = Avaliacao::whereDate('created_at', today())->count();
        $notificacoes = auth()->user()->unreadNotifications()->latest()->take(10)->get();

        return view('admin.sistema', compact('vendasHoje', 'valorRecebido', 'avaliacoes', 'notificacoes'));
    }

    public function dashboard()
    {
        return view('adm.dashboard');
    }

    public function pedidos()
    {
        return view('adm.pedidos');
    }

    public function pdtestoque()
    {
        // Listagem de produtos com variações, cor e tamanho
        $produtos = Produto::with(['variacoes.cor', 'variacoes.tamanho', 'categorias'])->get();
        return view('adm.pdtestoque', compact('produtos'));
    }

    public function cdtproduto()
    {
        // Buscar tamanhos ordenados por nome
        $tamanhos = Tamanho::orderBy('nome')->get();

        // Pega todas categorias do banco (ou só as que quer)
        $categorias = Categoria::all();

        // Buscar cores ordenadas por nome
        $cores = Cor::orderBy('nome')->get();

        // Buscar todas as categorias para o menu
        $categoriasMenu = Categoria::all();

        // Retornar a view com os dados
        return view('adm.cdtproduto', compact('tamanhos', 'cores', 'categoriasMenu', 'categorias'));
    }


    public function usercadastrado()
    {
        return view('adm.usercadastrado');
    }

    public function vendas()
    {
        return view('adm.vendas');
    }

}
