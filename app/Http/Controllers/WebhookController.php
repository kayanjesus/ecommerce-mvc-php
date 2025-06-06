<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Pedido;
use App\Models\Pagamento; // Seu modelo Pagamento
use App\Models\User;
use App\Notifications\NovoPedidoNotification; // Reutiliza para notificar admin
use Illuminate\Support\Facades\Notification;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook PagSeguro recebido:', $request->all());

        // O PagSeguro envia um 'id' na notificação que é o ID da transação no PagSeguro
        // e 'reference_id' que é o seu ID de referência.
        $notificationData = $request->json()->all();

        $pagSeguroOrderId = $notificationData['id'] ?? null; // ID do pedido PagSeguro
        $referenceId = $notificationData['reference_id'] ?? null; // Sua referência (ex: "pedido-123")
        $charges = $notificationData['charges'] ?? [];

        if (!$pagSeguroOrderId || !$referenceId || empty($charges)) {
            Log::warning('Webhook PagSeguro: Dados de notificação incompletos.', $notificationData);
            return response()->json(['message' => 'Dados de notificação incompletos'], 400);
        }

        // Extrair o id_pedido da sua reference_id
        $parts = explode('-', $referenceId);
        $localPedidoId = end($parts);

        // Busca o Pedido no seu banco
        $pedido = Pedido::find($localPedidoId);

        if (!$pedido) {
            Log::warning("Webhook PagSeguro: Pedido com reference_id '{$referenceId}' não encontrado em seu sistema.");
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        // Itera sobre as cobranças (charges) para encontrar o status do Pix
        foreach ($charges as $charge) {
            if ($charge['payment_method']['type'] === 'PIX') {
                $pagseguroStatus = $charge['status'];
                $chargeId = $charge['id'] ?? null; // ID da cobrança (charge_id) no PagSeguro

                // Mapeia o status do PagSeguro para o status do seu sistema
                $newPedidoStatus = $this->mapPagSeguroPedidoStatus($pagseguroStatus);
                $newPagamentoStatus = $this->mapPagSeguroPagamentoStatus($pagseguroStatus);

                // Encontra o registro de pagamento associado a este pedido e charge_id
                $pagamento = Pagamento::where('id_pedido', $pedido->id_pedido)
                    ->where('codigo_transacao', $chargeId) // Garante que é o pagamento correto
                    ->first();

                // Se o pagamento não foi encontrado pelo chargeId, tenta pelo id_pedido e metodo
                if (!$pagamento) {
                    $pagamento = Pagamento::where('id_pedido', $pedido->id_pedido)
                        ->where('metodo_pagamento', 'pix')
                        ->first();
                }

                if ($pagamento) {
                    // Se o status mudou para 'pago' e antes não era 'pago'
                    if ($newPagamentoStatus === 'pago' && $pagamento->status !== 'pago') {
                        $pagamento->status = $newPagamentoStatus;
                        $pagamento->data_pagamento = now();
                        $pagamento->save();

                        // Atualiza o status do pedido para 'pago'
                        $pedido->status = $newPedidoStatus;
                        $pedido->save();

                        // Notifica o administrador que o pagamento foi confirmado
                        $adminUser = User::where('is_admin', true)->first();
                        if ($adminUser) {
                            Notification::send($adminUser, new NovoPedidoNotification($pedido)); // Reutiliza a notificação
                            Log::info("Notificação de pagamento {$pedido->id_pedido} enviada ao admin.");
                        }

                        Log::info("Pedido {$pedido->id_pedido} e Pagamento atualizados para status: {$newPagamentoStatus} (via webhook PagSeguro).");
                        return response()->json(['message' => 'Notificação de pagamento processada com sucesso'], 200);

                    } elseif ($newPagamentoStatus !== $pagamento->status) {
                        // Atualiza outros status (cancelado, expirado, etc.)
                        $pagamento->status = $newPagamentoStatus;
                        if ($newPagamentoStatus === 'pago') { // Só define data_pagamento se for pago
                            $pagamento->data_pagamento = now();
                        } else {
                            $pagamento->data_pagamento = null;
                        }
                        $pagamento->save();

                        // Atualiza o status do pedido se for diferente
                        if ($newPedidoStatus !== $pedido->status) {
                            $pedido->status = $newPedidoStatus;
                            $pedido->save();
                            Log::info("Pedido {$pedido->id_pedido} atualizado para status: {$newPedidoStatus} (via webhook PagSeguro - não pago).");
                        }
                    } else {
                        Log::info("Webhook PagSeguro: Status para pedido {$pedido->id_pedido} já está {$pagamento->status}. Nenhuma alteração necessária.");
                    }
                } else {
                    Log::warning("Webhook PagSeguro: Registro de pagamento Pix para pedido {$pedido->id_pedido} não encontrado ou charge_id não corresponde.");
                }
            }
        }

        return response()->json(['message' => 'Notificação processada (sem alteração de status se já estiver atualizado)'], 200);
    }

    protected function mapPagSeguroPedidoStatus($pagseguroStatus)
    {
        // Mapeamento para o status da tabela 'pedidos'
        switch ($pagseguroStatus) {
            case 'PAID':
                return 'pago';
            case 'DECLINED':
            case 'CANCELED':
            case 'EXPIRED':
                return 'cancelado'; // Ou 'expirado', 'cancelado' dependendo da sua granularidade
            case 'PENDING':
            case 'APPROVED': // PIX foi gerado, aguardando pagamento
                return 'pendente';
            default:
                return 'pendente'; // Manter como pendente se status desconhecido
        }
    }

    protected function mapPagSeguroPagamentoStatus($pagseguroStatus)
    {
        // Mapeamento para o status da tabela 'pagamentos'
        switch ($pagseguroStatus) {
            case 'PAID':
                return 'pago';
            case 'DECLINED':
                return 'cancelado'; // Ou 'recusado'
            case 'CANCELED':
                return 'cancelado';
            case 'EXPIRED':
                return 'expirado';
            case 'PENDING':
            case 'APPROVED':
                return 'pendente';
            default:
                return 'pendente';
        }
    }
}