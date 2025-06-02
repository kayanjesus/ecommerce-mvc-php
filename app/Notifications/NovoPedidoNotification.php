<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Pedido;
use Illuminate\Notifications\Messages\BroadcastMessage;

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
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Novo pedido #' . $this->pedido->id_pedido . ' recebido - R$ ' . number_format($this->pedido->total, 2, ',', '.'),
            'link' => route('adm.pedidos.detalhes', $this->pedido->id_pedido),
            'pedido_id' => $this->pedido->id_pedido,
            'metodo_pagamento' => $this->pedido->pagamento->metodo_pagamento ?? 'desconhecido',
            'status_pagamento' => $this->pedido->pagamento->status ?? 'pendente'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->toArray($notifiable),
            'notifiable_id' => $notifiable->id
        ]);
    }
}