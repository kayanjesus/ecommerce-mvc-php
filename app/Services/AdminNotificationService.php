<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\User;
use App\Notifications\NovoPedidoNotification;
use Illuminate\Support\Facades\Log;

/**
 * Antes esse método existia duplicado (com pequenas diferenças) em
 * PagamentoController e WebhookController. Centralizado aqui para
 * manter uma única fonte de verdade.
 */
class AdminNotificationService
{
    public function notificarNovoPedido(Pedido $pedido): void
    {
        try {
            $administradores = User::where('access_level', 'admin')->get();

            foreach ($administradores as $admin) {
                try {
                    $admin->notify(new NovoPedidoNotification($pedido));
                    Log::info("Notificação de pedido {$pedido->id_pedido} enviada ao admin {$admin->id}.");
                } catch (\Exception $e) {
                    Log::error("Falha ao notificar admin {$admin->id}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error("Erro no processo de notificação de admins: " . $e->getMessage());
        }
    }
}
