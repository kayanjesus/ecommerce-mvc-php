<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
class NotificacaoController extends Controller
{
    public function index()
    {
        // Alteração aqui: Apenas pedidos com status 'pago' ou 'processando', 'enviado', 'entregue'
        // ou se você quiser ver 'pendente' apenas em outra seção ou com filtro.
        // Pela sua regra: "O pedido só deve aparecer na tela de pedidos do admin após o status estar como pago."
        $pedidos = Pedido::with(['usuario', 'itens.produto', 'pagamento'])
            ->whereIn('status', ['pago', 'processando', 'enviado', 'entregue']) // Apenas pedidos pagos ou em andamento
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pedidos', compact('pedidos'));
    }

    public function alterarStatus(Request $request, Pedido $pedido) // Injetar o Pedido diretamente
    {
        $request->validate([
            'status' => 'required|in:processando,enviado,entregue,cancelado' // Admin altera status de envio, não de pagamento
        ]);

        // Regra: "O administrador poderá confirmar o envio do pedido depois que o status estiver como pago."
        if ($pedido->status !== 'pago') {
            return back()->with('erro', 'Só é possível alterar o status de envio de pedidos com pagamento confirmado.');
        }

        // Antes de alterar o status do pedido, registre o status anterior
        $oldStatus = $pedido->status;

        $pedido->status = $request->status;
        $pedido->save();

        // Opcional: registrar histórico de status no pedido_status
        $pedido->historicoStatus()->create([ // Assumindo método historicoStatus no Pedido model
            'status' => $request->status,
            'observacao' => 'Status alterado pelo administrador.'
        ]);

        // Notificar o admin (ou o usuário, se for o caso) sobre a mudança de status
        $adminUser = User::where('is_admin', true)->first();
        if ($adminUser) {
            // Você pode criar uma notificação específica para "Status do Pedido Alterado"
            // Por simplicidade, vou usar a mesma para o exemplo.
            Notification::send($adminUser, new NovoPedidoNotification($pedido));
        }

        return back()->with('sucesso', 'Status do pedido atualizado com sucesso para ' . ucfirst($request->status) . '!');
    }

    // Método para exibir pedidos pendentes (se quiser uma tela separada para eles)
    public function showPendingOrders()
    {
        $pedidosPendentes = Pedido::with(['usuario', 'itens.produto', 'pagamento'])
            ->where('status', 'pendente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pedidos-pendentes', compact('pedidosPendentes'));
    }


    public function marcarComoLida($notificacao)
    {
        $notificacao = auth()->user()->notifications()->findOrFail($notificacao);
        $notificacao->markAsRead();

        return response()->json(['success' => true]);
    }
}
