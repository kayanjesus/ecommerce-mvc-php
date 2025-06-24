<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Entrega;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Adicionado para Log

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['usuario', 'itens.produto', 'pagamentoCheckout', 'reembolso'])
            ->latest()
            ->paginate(10);

        return view('adm.pedidos', compact('pedidos'));
    }

    // Este método é para alterar o status de um pedido pelo ADMIN
    public function alterarStatus(Request $request, Pedido $pedido)
    {
        // NOTA: 'entregue' foi REMOVIDO daqui. O admin não pode mais marcar como 'entregue'.
        // O status 'entregue' será definido EXCLUSIVAMENTE pelo cliente ao confirmar o recebimento.
        $request->validate([
            'status' => 'required|in:pendente,pago,processando,enviado,em_transito,saiu_para_entrega,cancelado,reembolso_solicitado,reembolsado,reembolso_negado',
        ]);

        DB::beginTransaction();
        try {
            // Se o status que o admin está definindo é "reembolsado" ou "reembolso_negado",
            // tratamos o reembolso. Caso contrário, atualizamos apenas o status do pedido.
            if ($request->input('status') === 'reembolsado' || $request->input('status') === 'reembolso_negado') {
                if ($pedido->reembolso) {
                    $pedido->reembolso->status = $request->input('status') === 'reembolsado' ? 'concluido' : 'negado';
                    $pedido->reembolso->data_conclusao = \Carbon\Carbon::now();
                    $pedido->reembolso->save();
                }
                $pedido->status_reembolso = $request->input('status') === 'reembolsado' ? 'concluido' : 'negado';
            } else {
                $pedido->status = $request->input('status');
            }

            $pedido->save();
            DB::commit();

            return back()->with('success', 'Status do pedido atualizado para ' . ucfirst(str_replace('_', ' ', $request->input('status'))));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao alterar status do pedido #{$pedido->id_pedido} pelo admin: " . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar status: ' . $e->getMessage());
        }
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

    public function show(Pedido $pedido) // ou public function show($id) se não for usar Route Model Binding
    {
        // O Laravel automaticamente injeta o modelo Pedido com base no ID da rota
        // se o nome do parâmetro na rota e no método forem os mesmos (ex: {pedido} e Pedido $pedido).
        // Se o ID for '9' na URL, $pedido será a instância do Pedido com ID 9.

        // Carrega os itens do pedido, se houver um relacionamento 'itens' no modelo Pedido
        $pedido->load('itens'); // Supondo que você tem um relacionamento chamado 'itensDoPedido'

        return view('adm.detalhe_pedido', compact('pedido'));
        // OU
        // return view('pedidos.detalhes', compact('pedido')); // Nome da sua view de detalhes
    }


    public function detalhePedido($id_pedido)
    {
        // ...
        $pedido = Pedido::with(['usuario', 'itens.tamanho', 'pagamentoCheckout', 'entrega.rastreio'])
            ->find($id_pedido);

        if (!$pedido) {
            return redirect()->route('adm.pedidos')->with('erro', 'Pedido não encontrado.');
        }

        // Se o pedido não tem uma entrega associada, crie uma com status inicial
        if (!$pedido->entrega) { // <--- AQUI ESTÁ O CHECK
            // ... Lógica para criar a entrega ...
            try {
                $entrega = Entrega::create([
                    'id_pedido' => $pedido->id_pedido,
                    // ...
                ]);
                // Recarrega o relacionamento para que o objeto $pedido agora tenha $pedido->entrega
                $pedido->load('entrega'); // <--- AQUI VOCÊ RECARREGA A ENTREGA
                // ...
            } catch (\Exception $e) {
                // ...
            }
        }
        // ...
    }
}