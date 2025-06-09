<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Pedido;
use App\Models\PagamentoCheckout;
use App\Models\User;
use App\Notifications\NovoPedidoNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Webhook PagSeguro recebido (payload original):', $payload);

        // --- Lógica para lidar com notificações baseadas em 'notificationCode' ---
        if (isset($payload['notificationCode']) && isset($payload['notificationType']) && $payload['notificationType'] === 'transaction') {
            $notificationCode = $payload['notificationCode'];
            Log::info("Webhook PagSeguro: Recebido notificationCode: {$notificationCode}. Buscando detalhes completos da transação.");

            try {
                Log::debug('Configuração PagSeguro:', [
                    'token' => config('pagseguro.token'),
                    'environment' => config('pagseguro.environment')
                ]);


                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('pagseguro.token'),
                    'Content-Type' => 'application/json',
                    'x-api-version' => '4',
                ])->get("https://" . (config('pagseguro.environment') === 'sandbox' ? 'sandbox.' : '') . "api.pagseguro.com/notifications/{$notificationCode}");

                if ($response->successful()) {
                    $notificationData = $response->json();
                    Log::info('Detalhes da notificação PagSeguro obtidos via API:', $notificationData);

                    // Reatribui $payload para a lógica de processamento genérica
                    $payload = $notificationData;

                } else {
                    Log::error('Erro ao buscar detalhes da notificação do PagSeguro (STATUS NON-SUCCESSFUL):', [
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                    // **MUDANÇA AQUI:** Retorna um status de erro para o PagSeguro reenviar
                    return response()->json(['message' => 'Falha ao buscar detalhes da notificação.'], 500);
                }
            } catch (\Exception $e) {
                Log::error('Exceção ao se comunicar com a API do PagSeguro para buscar notificação:', [
                    'notificationCode' => $notificationCode,
                    'exception' => $e->getMessage()
                ]);
                // **MUDANÇA AQUI:** Retorna um status de erro para o PagSeguro reenviar
                return response()->json(['message' => 'Erro interno na comunicação com PagSeguro.'], 500);
            }
        }
        // --- Fim da lógica para 'notificationCode' ---

        // A partir daqui, $payload DEVE conter o formato completo da ordem
        $pagSeguroOrderId = $payload['id'] ?? null;
        $referenceId = $payload['reference_id'] ?? null;
        $charges = $payload['charges'] ?? [];

        if (!$pagSeguroOrderId || !$referenceId || empty($charges)) {
            Log::warning('Webhook PagSeguro: Payload da ordem incompleto ou inesperado (após tentativa de buscar por código).', $payload);
            return response()->json(['message' => 'Dados de ordem incompletos ou inesperados'], 400);
        }

        $parts = explode('-', $referenceId);
        $localPedidoId = end($parts);

        $pedido = Pedido::find($localPedidoId);

        if (!$pedido) {
            Log::warning("Webhook PagSeguro: Pedido com reference_id '{$referenceId}' (ID local: {$localPedidoId}) não encontrado em seu sistema.");
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        foreach ($charges as $charge) {
            if (isset($charge['payment_method']['type']) && $charge['payment_method']['type'] === 'PIX') {
                $pagseguroStatus = $charge['status'];
                $chargeId = $charge['id'] ?? null;

                $newPedidoStatus = $this->mapPagSeguroPedidoStatus($pagseguroStatus);
                $newPagamentoStatus = $this->mapPagSeguroPagamentoStatus($pagseguroStatus);

                $pagamento = PagamentoCheckout::where('id_pedido', $pedido->id_pedido)
                    ->where('codigo_transacao', $chargeId)
                    ->first();

                if (!$pagamento) {
                    $pagamento = PagamentoCheckout::where('id_pedido', $pedido->id_pedido)
                        ->where('metodo_pagamento', 'pix')
                        ->first();
                }

                if ($pagamento) {
                    if ($newPagamentoStatus === 'pago' && $pagamento->status !== 'pago') {
                        $pagamento->status = $newPagamentoStatus;
                        $pagamento->data_pagamento = now();
                        $pagamento->save();

                        $pedido->status = $newPedidoStatus;
                        $pedido->save();

                        $adminUser = User::where('access_level', 'admin')->first();
                        if ($adminUser) {
                            Notification::send($adminUser, new NovoPedidoNotification($pedido));
                            Log::info("Notificação de pagamento {$pedido->id_pedido} enviada ao admin.");
                        }

                        Log::info("Pedido {$pedido->id_pedido} e Pagamento atualizados para status: {$newPagamentoStatus} (via webhook PagSeguro).");
                    } elseif ($newPagamentoStatus !== $pagamento->status) {
                        $pagamento->status = $newPagamentoStatus;
                        if ($newPagamentoStatus === 'pago') {
                            $pagamento->data_pagamento = now();
                        } else {
                            $pagamento->data_pagamento = null;
                        }
                        $pagamento->save();

                        if ($newPedidoStatus !== $pedido->status) {
                            $pedido->status = $newPedidoStatus;
                            $pedido->save();
                            Log::info("Pedido {$pedido->id_pedido} atualizado para status: {$newPedidoStatus} (via webhook PagSeguro - não pago).");
                        }
                    } else {
                        Log::info("Webhook PagSeguro: Status para pedido {$pedido->id_pedido} e pagamento já está {$pagamento->status}. Nenhuma alteração necessária.");
                    }
                } else {
                    Log::warning("Webhook PagSeguro: Registro de pagamento Pix para pedido {$pedido->id_pedido} não encontrado ou charge_id não corresponde. (Charge ID: {$chargeId})");
                }
            }
        }

        // Resposta de sucesso para o PagSeguro
        return response()->json(['message' => 'Notificação processada com sucesso'], 200);
    }

    protected function mapPagSeguroPedidoStatus($pagseguroStatus)
    {
        switch ($pagseguroStatus) {
            case 'PAID':
                return 'pago';
            case 'DECLINED':
            case 'CANCELED':
            case 'EXPIRED':
                return 'cancelado';
            case 'PENDING':
            case 'APPROVED':
                return 'pendente';
            default:
                return 'pendente';
        }
    }

    protected function mapPagSeguroPagamentoStatus($pagseguroStatus)
    {
        switch ($pagseguroStatus) {
            case 'PAID':
                return 'pago';
            case 'DECLINED':
                return 'recusado';
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