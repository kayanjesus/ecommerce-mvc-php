<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Entrega;
use App\Models\Reembolso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    /**
     * Listar pedidos para administrador
     */
    public function index()
    {
        $pedidos = Pedido::with(['usuario', 'itens.produto', 'pagamentoCheckout', 'reembolso'])
            ->latest()
            ->paginate(10);

        return view('adm.pedidos', compact('pedidos'));
    }

    /**
     * Este método é para alterar o status de um pedido pelo ADMIN
     */
    public function alterarStatus(Request $request, Pedido $pedido)
    {
        // NOTA: 'entregue' foi REMOVIDO daqui. O admin não pode mais marcar como 'entregue'.
        // O status 'entregue' será definido EXCLUSIVAMENTE pelo cliente ao confirmar o recebimento.
        $request->validate([
            'status' => 'required|in:pendente,pago,processando,enviado,em_transito,saiu_para_entrega,cancelado,reembolso_solicitado,reembolsado,reembolso_negado',
        ]);

        // Verificar se o pedido está cancelado
        if ($pedido->status == 'cancelado') {
            return back()->with('error', 'Não é possível alterar o status de um pedido cancelado.');
        }

        DB::beginTransaction();
        try {
            // Se o status que o admin está definindo é "cancelado", criar reembolso automaticamente
            if ($request->input('status') === 'cancelado') {
                // Verificar se já existe um reembolso
                if (!$pedido->reembolso) {
                    // Criar registro de reembolso
                    Reembolso::create([
                        'id_pedido' => $pedido->id_pedido,
                        'valor_reembolso' => $pedido->total,
                        'motivo' => 'Cancelamento do pedido pelo administrador',
                        'status' => 'aprovado',
                        'data_processamento' => now(),
                    ]);
                }
            }

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

    /**
     * Mostrar detalhes do pedido
     */
    public function show(Pedido $pedido)
    {
        $pedido->load(['usuario', 'itens.produto', 'pagamentoCheckout', 'entrega.rastreio', 'reembolso']);

        return view('adm.detalhe_pedido', compact('pedido'));
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

    public function detalhePedido($id_pedido)
    {
        $pedido = Pedido::with(['usuario', 'itens.tamanho', 'pagamentoCheckout', 'entrega.rastreio', 'reembolso'])
            ->find($id_pedido);

        if (!$pedido) {
            return redirect()->route('adm.pedidos')->with('erro', 'Pedido não encontrado.');
        }

        // Se o pedido não tem uma entrega associada, crie uma com status inicial
        if (!$pedido->entrega) {
            try {
                $entrega = Entrega::create([
                    'id_pedido' => $pedido->id_pedido,
                    'metodo_entrega' => 'padrao',
                    'valor_entrega' => 0,
                    'status' => 'pendente',
                    'data_envio' => null,
                    'data_entrega' => null,
                ]);
                $pedido->load('entrega');
            } catch (\Exception $e) {
                Log::error("Erro ao criar entrega para pedido #{$pedido->id_pedido}: " . $e->getMessage());
            }
        }

        return view('adm.detalhe_pedido', compact('pedido'));
    }
}