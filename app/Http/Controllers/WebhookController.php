<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Pedido;
use App\Models\Reembolso;
use App\Services\PagSeguroService;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook PagSeguro recebido', $request->all());

        // Verificar autenticação (se configurado)
        $token = $request->header('X-PagSeguro-Token');
        if (!$this->validarToken($token)) {
            Log::warning('Token de webhook inválido');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $evento = $request->input('event');
        $resource = $request->input('resource');

        Log::info("Evento PagSeguro: {$evento}");

        switch ($evento) {
            case 'PAYMENT.CANCELLED':
                $this->processarPagamentoCancelado($resource);
                break;

            case 'PAYMENT.REFUNDED':
                $this->processarReembolsoConcluido($resource);
                break;

            case 'PAYMENT.CREATED':
            case 'PAYMENT.PAID':
                $this->processarPagamentoAprovado($resource);
                break;
        }

        return response()->json(['status' => 'received']);
    }

    private function processarPagamentoCancelado($resource)
    {
        try {
            $codigoTransacao = $resource['id'] ?? null;

            if (!$codigoTransacao) {
                Log::warning('Código de transação não encontrado no webhook');
                return;
            }

            // Buscar pedido pelo código da transação
            $pedido = Pedido::whereHas('pagamentoCheckout', function ($q) use ($codigoTransacao) {
                $q->where('codigo_transacao', $codigoTransacao)
                    ->orWhere('codigo_transacao', 'ORDE_' . $codigoTransacao);
            })->first();

            if ($pedido) {
                // Atualizar status do pedido
                $pedido->status = 'cancelado';
                $pedido->save();

                // Criar ou atualizar reembolso
                $reembolso = Reembolso::firstOrCreate(
                    ['id_pedido' => $pedido->id_pedido],
                    [
                        'valor_reembolso' => $pedido->total,
                        'motivo' => 'Cancelamento via PagSeguro',
                        'status' => 'processando',
                        'data_solicitacao' => now(),
                        'data_processamento' => now(),
                    ]
                );

                Log::info("Pedido #{$pedido->id_pedido} cancelado via webhook PagSeguro");
            }

        } catch (\Exception $e) {
            Log::error("Erro ao processar pagamento cancelado: " . $e->getMessage());
        }
    }

    private function processarReembolsoConcluido($resource)
    {
        try {
            $codigoReembolso = $resource['id'] ?? null;
            $codigoTransacao = $resource['charge_id'] ?? null;

            if ($codigoTransacao) {
                // Buscar pedido
                $pedido = Pedido::whereHas('pagamentoCheckout', function ($q) use ($codigoTransacao) {
                    $q->where('codigo_transacao', $codigoTransacao)
                        ->orWhere('codigo_transacao', 'ORDE_' . $codigoTransacao);
                })->first();

                if ($pedido && $pedido->reembolso) {
                    $pedido->reembolso->status = 'concluido';
                    $pedido->reembolso->codigo_reembolso_pagseguro = $codigoReembolso;
                    $pedido->reembolso->data_conclusao = now();
                    $pedido->reembolso->save();

                    Log::info("Reembolso concluído para pedido #{$pedido->id_pedido}");
                }
            }

        } catch (\Exception $e) {
            Log::error("Erro ao processar reembolso concluído: " . $e->getMessage());
        }
    }

    private function validarToken($token)
    {
        // Implementar validação se necessário
        // O PagSeguro pode enviar um token específico
        return true; // Temporariamente verdadeiro para desenvolvimento
    }
}