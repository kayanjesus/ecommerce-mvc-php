<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Pedido;

class NovoPedidoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function via($notifiable)
    {
        return ['database']; // Apenas database, sem broadcast
    }

    public function toArray($notifiable)
    {
        // Usando pagamentoCheckout, que é o relacionamento correto no seu Model Pedido
        $metodoPagamento = $this->pedido->pagamentoCheckout->metodo_pagamento ?? 'desconhecido';
        $statusPagamento = $this->pedido->pagamentoCheckout->status ?? 'pendente';

        return [
            'type' => 'novo_pedido',
            'message' => 'Novo pedido #' . $this->pedido->id_pedido . ' recebido - R$ ' . number_format($this->pedido->total, 2, ',', '.') . " (Status Pagamento: " . ucfirst($statusPagamento) . ")",
            'link' => route('adm.pedidos.detalhes', $this->pedido->id_pedido), // Verifique se essa rota 'adm.pedidos.detalhes' existe e funciona
            'pedido_id' => $this->pedido->id_pedido,
            'metodo_pagamento' => $metodoPagamento,
            'status_pagamento' => $statusPagamento
        ];
    }
}
