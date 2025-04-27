<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
class DashboardController extends Controller
{
    public function index() {

        $usuarios = User::all()->count();

        // gráfico 1 - usuários
        $usersData = User::select([
            DB::raw('YEAR(created_at) as ano'),
            DB::raw('COUNT(*) as total'),
        ])
        ->groupBy('ano')
        ->orderBy('ano', 'asc')
        ->get();

        // preparar arrays
        foreach($usersData as $user) {
            $ano[] = $user->ano;
            $total[] = $user->total;
        }

        // formatar para chartjs
        $userLabel = "'Comparativo de cadastro de usúario'";
        $userAno = implode(',', $ano);
        $userTotal = implode(',', $total);

        return view('adm.vendas', compact('usuarios', 'userLabel', 'userAno', 'userTotal'));
    }
}
