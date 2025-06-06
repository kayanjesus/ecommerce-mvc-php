<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['usuario', 'itens.produto', 'pagamentoCheckout'])
            ->latest()
            ->paginate(10);

        return view('adm.pedidos', compact('pedidos'));
    }

    public function marcarComoLida(Request $request)
    {
        $notificacao = Auth::user()->notifications()->findOrFail($request->id);
        $notificacao->markAsRead();
        return response()->json(['success' => true]);
    }

    public function marcarTodasComoLidas()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function metricas()
    {
        $vendasHoje = Pedido::whereDate('created_at', today())->count();
        $valorRecebido = Pedido::whereDate('created_at', today())->sum('total');
        $avaliacoes = 0; // Substitua por sua lógica de avaliações se necessário

        return response()->json([
            'vendasHoje' => $vendasHoje,
            'valorRecebido' => $valorRecebido,
            'avaliacoes' => $avaliacoes
        ]);
    }
}
