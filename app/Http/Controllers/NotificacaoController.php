<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Entrega; // Para manipular a tabela de entregas
use Illuminate\Notifications\Notification;
use App\Notifications\NovoPedidoNotification;
use Illuminate\Support\Facades\Log; // Para logs

class NotificacaoController extends Controller
{
    /**
     * Exibe a lista de pedidos para o administrador.
     * Regra: Pedido só aparece após o status estar como 'pago'.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // CORRIGIDO: Usando 'pagamentoCheckout' ao invés de 'pagamento'
        $pedidos = Pedido::with(['usuario', 'itens.produto', 'pagamentoCheckout', 'entrega'])
            ->whereIn('status', ['pago', 'processando', 'enviado', 'entregue', 'reembolso_solicitado']) // Status visíveis para o admin
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('adm.pedidos', compact('pedidos'));
    }

    /**
     * Exibe os detalhes de um pedido específico para o administrador.
     *
     * @param int $id_pedido
     * @return \Illuminate\View\View
     */
    public function detalhePedido(int $id_pedido)
    {
        $pedido = Pedido::where('id_pedido', $id_pedido)
            ->with(['usuario', 'itens.produto.imagens', 'itens.cor', 'itens.tamanho', 'pagamentoCheckout', 'entrega.rastreio'])
            ->firstOrFail();

        return view('admin.detalhes_pedido', compact('pedido')); // Crie esta view se não existir
    }


    /**
     * Altera o status de um pedido pelo administrador.
     *
     * @param Request $request
     * @param Pedido $pedido
     * @return \Illuminate\Http\RedirectResponse
     */
    public function alterarStatus(Request $request, Pedido $pedido)
    {
        $request->validate([
            'status' => 'required|in:pendente,pago,processando,enviado,em_transito,saiu_para_entrega,entregue,cancelado,reembolso_solicitado,reembolsado'
        ]);

        // Regra: Não pode alterar status se o pedido já está em um estado final e proibido para admin
        if (!$pedido->podeSerAlteradoPeloAdministrador() && in_array($request->status, ['entregue', 'cancelado', 'reembolso_solicitado', 'reembolsado'])) {
            return back()->with('erro', 'Este pedido já está em um status final e não pode ser alterado diretamente.');
        }

        // Lógicas de transição de status
        if ($request->status === 'enviado' && $pedido->status !== 'pago') {
            return back()->with('erro', 'Para enviar, o pedido deve ter o pagamento confirmado (status "pago").');
        }

        if (in_array($request->status, ['entregue', 'reembolsado', 'cancelado']) && $pedido->status === $request->status) {
            return back()->with('info', "O pedido #{$pedido->id_pedido} já está com o status '{$request->status}'.");
        }

        $oldStatus = $pedido->status;
        $pedido->status = $request->status;
        $pedido->save();

        $pedido->historicoStatus()->create([
            'status' => $request->status,
            'observacao' => 'Status alterado pelo administrador.'
        ]);

        return back()->with('sucesso', 'Status do pedido atualizado com sucesso para ' . ucfirst($request->status) . '!');
    }

    /**
     * Atualiza o status de entrega do pedido (usado para data de envio ou entrega).
     *
     * @param Request $request
     * @param Pedido $pedido
     * @return \Illuminate\Http\RedirectResponse
     */
    public function atualizarStatusEntrega(Request $request, Pedido $pedido)
    {
        $request->validate([
            'data_envio' => 'nullable|date',
            'data_entrega' => 'nullable|date',
        ]);

        try {
            if (!$pedido->entrega) {
                // Se não houver registro de entrega, cria um novo.
                $entrega = new Entrega();
                $entrega->id_pedido = $pedido->id_pedido;
                // Defina valores padrão ou pegue de algum lugar
                $entrega->metodo_entrega = 'admin_manual';
                $entrega->valor_entrega = 0.00;
            } else {
                $entrega = $pedido->entrega;
            }

            if ($request->filled('data_envio')) {
                $entrega->data_envio = $request->data_envio;
            }
            if ($request->filled('data_entrega')) {
                $entrega->data_entrega = $request->data_entrega;
                // Se a data de entrega é definida, atualiza o status do pedido para 'entregue'
                if ($pedido->status !== 'entregue') {
                    $pedido->status = 'entregue';
                    $pedido->save();
                    $pedido->historicoStatus()->create([
                        'status' => 'entregue',
                        'observacao' => 'Confirmado como entregue pelo administrador.'
                    ]);
                }
            }
            $entrega->save();

            return back()->with('sucesso', 'Informações de entrega atualizadas com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar status de entrega do pedido #{$pedido->id_pedido} pelo admin: " . $e->getMessage());
            return back()->with('erro', 'Ocorreu um erro ao atualizar as informações de entrega.');
        }
    }

    /**
     * Adiciona ou atualiza o código de rastreio de um pedido.
     *
     * @param Request $request
     * @param Pedido $pedido
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adicionarRastreio(Request $request, Pedido $pedido)
    {
        $request->validate([
            'codigo_rastreio' => 'nullable|string|max:255',
            'url_rastreio' => 'nullable|url|max:255',
        ]);

        try {
            if (!$pedido->entrega) {
                // Se não houver registro de entrega, cria um novo.
                $entrega = new Entrega();
                $entrega->id_pedido = $pedido->id_pedido;
                $entrega->metodo_entrega = 'admin_manual';
                $entrega->valor_entrega = 0.00;
            } else {
                $entrega = $pedido->entrega;
            }

            if (!$entrega->rastreio) {
                $entrega->rastreio()->create([
                    'codigo_rastreio' => $request->codigo_rastreio,
                    'url_rastreio' => $request->url_rastreio,
                ]);
            } else {
                $entrega->rastreio->codigo_rastreio = $request->codigo_rastreio;
                $entrega->rastreio->url_rastreio = $request->url_rastreio;
                $entrega->rastreio->save();
            }

            return back()->with('sucesso', 'Código de rastreio atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao adicionar rastreio ao pedido #{$pedido->id_pedido} pelo admin: " . $e->getMessage());
            return back()->with('erro', 'Ocorreu um erro ao adicionar o código de rastreio.');
        }
    }

    /**
     * Método para exibir pedidos pendentes.
     *
     * @return \Illuminate\View\View
     */
    public function showPendingOrders()
    {
        // CORRIGIDO: Usando 'pagamentoCheckout' ao invés de 'pagamento'
        $pedidosPendentes = Pedido::with(['usuario', 'itens.produto', 'pagamentoCheckout'])
            ->where('status', 'pendente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pedidos-pendentes', compact('pedidosPendentes'));
    }

    /**
     * Marca uma notificação como lida.
     *
     * @param string $notificacaoId
     * @return \Illuminate\Http\JsonResponse
     */
    public function marcarComoLida(string $notificacaoId)
    {
        $notificacao = auth()->user()->notifications()->findOrFail($notificacaoId);
        $notificacao->markAsRead();

        return response()->json(['success' => true]);
    }


    // Se houver um método 'marcarTodasComoLidas' em suas rotas e você quer que ele seja aqui:
    // public function marcarTodasComoLidas()
    // {
    //     auth()->user()->unreadNotifications->markAsRead();
    //     return response()->json(['success' => true]);
    // }


public function metricas()
{
    // 💡 IMPORTANTE: Substitua os valores abaixo pela sua lógica correta de banco de dados
    
    // Se o seu DashboardController já tem esses valores, você pode usá-los aqui.
    // Exemplo: $vendasHoje = Pedido::whereDate('created_at', today())->where('status', 'pago')->count();

    $data = [
        'vendasHoje' => 0, // Inicie com zero ou a contagem real
        'valorRecebido' => 0.00, // Inicie com zero ou o valor real
        'avaliacoes' => 0, // Inicie com zero ou a contagem real
    ];

    return response()->json($data);
}
}
