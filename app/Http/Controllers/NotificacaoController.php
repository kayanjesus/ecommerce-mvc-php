<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\NovoPedidoNotification;

class NotificacaoController extends Controller
{
    public function index()
    {
        $notificacoes = Auth::user()->notifications()->latest()->take(20)->get();
        return view('admin.partials.notificacoes', compact('notificacoes'))->render();
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
        // Exemplo - ajuste conforme sua lógica de negócios
        $vendasHoje = Pedido::whereDate('created_at', today())->count();
        $valorRecebido = Pedido::whereDate('created_at', today())->sum('total');
        $avaliacoes = Avaliacao::whereDate('created_at', today())->count();

        return response()->json([
            'vendasHoje' => $vendasHoje,
            'valorRecebido' => $valorRecebido,
            'avaliacoes' => $avaliacoes
        ]);
    }
}