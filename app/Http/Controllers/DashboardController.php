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
    // public function index()
    // {

    //     $usuarios = User::all()->count();

    //     // gráfico 1 - usuários
    //     $usersData = User::select([
    //         DB::raw('YEAR(created_at) as ano'),
    //         DB::raw('COUNT(*) as total'),
    //     ])
    //         ->groupBy('ano')
    //         ->orderBy('ano', 'asc')
    //         ->get();

    //     // preparar arrays
    //     foreach ($usersData as $user) {
    //         $ano[] = $user->ano;
    //         $total[] = $user->total;
    //     }

    //     // formatar para chartjs
    //     $userLabel = "'Comparativo de cadastro de usúario'";
    //     $userAno = implode(',', $ano);
    //     $userTotal = implode(',', $total);

    //     return view('adm.vendas', compact('usuarios', 'userLabel', 'userAno', 'userTotal'));
    // }

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
