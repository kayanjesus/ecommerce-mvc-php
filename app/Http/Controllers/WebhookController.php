<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PagamentoCheckout;
use App\Models\User;
use App\Services\AdminNotificationService;
use App\Services\PagSeguroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private PagSeguroService $pagSeguroService,
        private AdminNotificationService $adminNotificationService,
    ) {}

    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Webhook PagSeguro recebido (payload original):', $payload);

        // --- Validação de assinatura do webhook ---
        $webhookSecret = config('pagseguro.webhook_secret');

        if (!$webhookSecret) {
            // Em produção isso é um erro crítico: nunca processar sem validar
            // a origem da requisição, senão qualquer um pode forjar um "pago".
            if (app()->environment('production')) {
                Log::error('PAGSEGURO_WEBHOOK_SECRET não configurado em produção. Recusando processar webhook.');
                return response()->json(['message' => 'Webhook não configurado corretamente'], 500);
            }

            Log::warning('PAGSEGURO_WEBHOOK_SECRET não configurado. Validação de assinatura desabilitada (ambiente não-produção).');
        } else {
            $signature = $request->header('x-ps-signature') ?? $request->header('x-pagseguro-signature');

            if (!$signature) {
                Log::warning('Webhook PagSeguro: Assinatura faltando no cabeçalho.');
                return response()->json(['message' => 'Unauthorized: Signature missing'], 401);
            }

            $expectedSignature = hash_hmac('sha256', $request->getContent(), $webhookSecret);

            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('Webhook PagSeguro: Assinatura inválida. Requisição potencialmente forjada.', [
                    'received_signature' => $signature,
                ]);
                return response()->json(['message' => 'Unauthorized: Invalid signature'], 401);
            }
        }

        // --- Lida com notificações baseadas em 'notificationCode' (formato antigo) ---
        if (isset($payload['notificationCode']) && ($payload['notificationType'] ?? null) === 'transaction') {
            $notificationCode = $payload['notificationCode'];
            Log::info("Webhook PagSeguro: Recebido notificationCode: {$notificationCode}. Buscando detalhes completos.");

            try {
                $response = $this->pagSeguroService->buscarNotificacao($notificationCode);

                if ($response->successful()) {
                    $payload = $response->json();
                    Log::info('Detalhes da notificação PagSeguro obtidos via API:', $payload);
                } else {
                    Log::error('Erro ao buscar detalhes da notificação do PagSeguro:', [
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);
                    return response()->json(['message' => 'Falha ao buscar detalhes da notificação.'], 500);
                }
            } catch (\Exception $e) {
                Log::error('Exceção ao buscar notificação do PagSeguro:', [
                    'notificationCode' => $notificationCode,
                    'exception' => $e->getMessage(),
                ]);
                return response()->json(['message' => 'Erro interno na comunicação com PagSeguro.'], 500);
            }
        }

        // A partir daqui, $payload deve conter o formato completo da ordem
        $pagSeguroOrderId = $payload['id'] ?? null;
        $referenceId = $payload['reference_id'] ?? null;
        $charges = $payload['charges'] ?? [];

        if (!$pagSeguroOrderId || !$referenceId || empty($charges)) {
            Log::warning('Webhook PagSeguro: Payload da ordem incompleto ou inesperado.', $payload);
            return response()->json(['message' => 'Dados de ordem incompletos ou inesperados'], 400);
        }

        $parts = explode('-', $referenceId);
        $localPedidoId = end($parts);

        $pedido = Pedido::find($localPedidoId);

        if (!$pedido) {
            Log::warning("Webhook PagSeguro: Pedido '{$referenceId}' (ID local: {$localPedidoId}) não encontrado.");
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        foreach ($charges as $charge) {
            $pagseguroStatus = $charge['status'] ?? 'UNKNOWN';
            $chargeMethodType = $charge['payment_method']['type'] ?? null;
            $chargeId = $charge['id'] ?? null;

            Log::info("Processando charge para pedido {$pedido->id_pedido}:", [
                'charge_id' => $chargeId,
                'method_type' => $chargeMethodType,
                'pagseguro_status' => $pagseguroStatus,
            ]);

            $pagamento = PagamentoCheckout::where('id_pedido', $pedido->id_pedido)
                ->where('codigo_transacao', $pagSeguroOrderId)
                ->first();

            if (!$pagamento && $chargeMethodType === 'PIX') {
                $pagamento = PagamentoCheckout::where('id_pedido', $pedido->id_pedido)
                    ->where('metodo_pagamento', 'pix')
                    ->first();
                Log::warning("Webhook PagSeguro: pagamento não encontrado por codigo_transacao ({$pagSeguroOrderId}). Tentando por metodo_pagamento.");
            }

            if (!$pagamento) {
                Log::warning("Webhook PagSeguro: PagamentoCheckout para pedido {$pedido->id_pedido} não encontrado (Ordem: {$pagSeguroOrderId}, Charge: {$chargeId}).");
                continue;
            }

            $newPagamentoStatus = $this->mapPagSeguroPagamentoStatus($pagseguroStatus);
            $newPedidoStatus = $this->mapPagSeguroPedidoStatus($pagseguroStatus);

            $pagamento->detalhes = array_merge($pagamento->detalhes ?? [], ['pagseguro_charge_id' => $chargeId]);

            if ($newPagamentoStatus === $pagamento->status) {
                Log::info("Webhook PagSeguro: status do pedido {$pedido->id_pedido} já é {$pagamento->status}. Nada a fazer.");
                continue;
            }

            $pagamento->status = $newPagamentoStatus;
            $pagamento->data_pagamento = $newPagamentoStatus === 'pago' ? now() : null;
            $pagamento->save();

            Log::info("Pagamento {$pagamento->id_pagamento} do pedido {$pedido->id_pedido} atualizado para: {$newPagamentoStatus}.");

            if ($newPedidoStatus !== $pedido->status) {
                $pedido->status = $newPedidoStatus;
                $pedido->save();
                Log::info("Pedido {$pedido->id_pedido} atualizado para status: {$newPedidoStatus}.");
            }

            if ($newPagamentoStatus === 'pago') {
                $this->adminNotificationService->notificarNovoPedido($pedido);
            }
        }

        return response()->json(['message' => 'Notificação processada com sucesso'], 200);
    }

    protected function mapPagSeguroPedidoStatus($pagseguroStatus)
    {
        return match ($pagseguroStatus) {
            'PAID' => 'pago',
            'DECLINED', 'CANCELED', 'EXPIRED', 'REFUNDED' => 'cancelado',
            default => 'pendente',
        };
    }

    protected function mapPagSeguroPagamentoStatus($pagseguroStatus)
    {
        return match ($pagseguroStatus) {
            'PAID' => 'pago',
            'DECLINED' => 'recusado',
            'CANCELED' => 'cancelado',
            'EXPIRED' => 'expirado',
            'REFUNDED' => 'reembolsado',
            default => 'pendente',
        };
    }
}
