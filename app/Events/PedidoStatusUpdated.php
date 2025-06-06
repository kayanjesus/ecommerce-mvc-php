<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PedidoStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido->load('pagamento'); // Carrega o relacionamento pagamento
    }

    public function broadcastOn()
    {
        // Canal privado para admins, ou um canal mais genérico se muitos admins
        return new Channel('admin-pedidos'); // Ou um canal mais específico como 'admin.pedidos.{id_admin}'
    }

    public function broadcastAs()
    {
        return 'pedido.status.updated';
    }

    public function broadcastWith()
    {
        return [
            'pedido_id' => $this->pedido->id_pedido,
            'status_pedido' => $this->pedido->status,
            'status_pagamento' => $this->pedido->pagamento->status ?? 'N/A',
            'data_pedido' => $this->pedido->created_at->format('d/m/Y H:i'),
            // Inclua mais dados se precisar para atualizar a UI
        ];
    }
}