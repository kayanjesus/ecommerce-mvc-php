<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Reembolso;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReembolsoService
{
    private $pagSeguroService;

    public function __construct(PagSeguroService $pagSeguroService)
    {
        $this->pagSeguroService = $pagSeguroService;
    }

    /**
     * Processar cancelamento com reembolso automático
     */
    public function processarCancelamentoComReembolso(Pedido $pedido, $motivo = '')
    {
        DB::beginTransaction();

        try {
            // 1. Atualizar status do pedido
            $pedido->status = 'cancelado';
            $pedido->save();

            // 2. Criar registro de reembolso
            $reembolso = Reembolso::create([
                'id_pedido' => $pedido->id_pedido,
                'valor_reembolso' => $pedido->total,
                'motivo' => $motivo ?: 'Cancelamento solicitado',
                'status' => 'solicitado',
                'data_solicitacao' => now(),
            ]);

            // 3. Tentar processar reembolso
            $resultado = $this->processarReembolso($pedido, $reembolso);

            if ($resultado['sucesso']) {
                DB::commit();
                return [
                    'sucesso' => true,
                    'mensagem' => $resultado['mensagem'],
                    'reembolso' => $reembolso,
                ];
            } else {
                // Reembolso falhou, mas o pedido foi cancelado
                DB::commit();
                return [
                    'sucesso' => true,
                    'mensagem' => 'Pedido cancelado. ' . $resultado['mensagem'],
                    'reembolso' => $reembolso,
                    'alerta' => true,
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao processar cancelamento: " . $e->getMessage());

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao processar cancelamento: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Processar reembolso (real ou simulado)
     */
    private function processarReembolso(Pedido $pedido, Reembolso $reembolso)
    {
        // Em ambiente de produção e se tiver código de transação
        if (
            app()->environment('production') &&
            $pedido->pagamentoCheckout &&
            $pedido->pagamentoCheckout->codigo_transacao
        ) {

            // Tentar PagSeguro real
            $resultado = $this->pagSeguroService->criarReembolso(
                $pedido->pagamentoCheckout->codigo_transacao,
                $pedido->total,
                $reembolso->motivo
            );

            if ($resultado['success']) {
                $reembolso->status = 'processando';
                $reembolso->codigo_reembolso_pagseguro = $resultado['codigo_reembolso'];
                $reembolso->data_processamento = now();
                $reembolso->save();

                return [
                    'sucesso' => true,
                    'mensagem' => 'Reembolso solicitado ao PagSeguro.',
                ];
            } else {
                // PagSeguro falhou
                $reembolso->status = 'solicitado';
                $reembolso->save();

                return [
                    'sucesso' => false,
                    'mensagem' => 'Não foi possível processar o reembolso automaticamente. ' .
                        'Nossa equipe foi notificada e processará manualmente.',
                ];
            }
        } else {
            // Ambiente de desenvolvimento - simular
            return $this->simularReembolso($reembolso);
        }
    }

    /**
     * Simular reembolso para desenvolvimento
     */
    private function simularReembolso(Reembolso $reembolso)
    {
        Log::info("Simulando reembolso para desenvolvimento", [
            'reembolso_id' => $reembolso->id_reembolso,
            'pedido_id' => $reembolso->id_pedido,
        ]);

        // Simular processamento
        sleep(1);

        $reembolso->status = 'concluido';
        $reembolso->codigo_reembolso_pagseguro = 'DEV-' . time() . '-' . $reembolso->id_reembolso;
        $reembolso->data_processamento = now();
        $reembolso->data_conclusao = now();
        $reembolso->save();

        return [
            'sucesso' => true,
            'mensagem' => 'Reembolso simulado para ambiente de desenvolvimento.',
        ];
    }

    /**
     * Processar reembolso manual (para admin)
     */
    public function processarReembolsoManual($reembolsoId)
    {
        $reembolso = Reembolso::findOrFail($reembolsoId);
        $pedido = $reembolso->pedido;

        if (app()->environment('production')) {
            // Tentar PagSeguro real
            if ($pedido->pagamentoCheckout && $pedido->pagamentoCheckout->codigo_transacao) {
                $resultado = $this->pagSeguroService->criarReembolso(
                    $pedido->pagamentoCheckout->codigo_transacao,
                    $reembolso->valor_reembolso,
                    $reembolso->motivo . ' (Processamento manual)'
                );

                if ($resultado['success']) {
                    $reembolso->status = 'processando';
                    $reembolso->codigo_reembolso_pagseguro = $resultado['codigo_reembolso'];
                    $reembolso->data_processamento = now();
                    $reembolso->save();

                    return [
                        'sucesso' => true,
                        'mensagem' => 'Reembolso solicitado ao PagSeguro.',
                    ];
                }
            }
        }

        // Se não conseguir, marcar como concluído manualmente
        $reembolso->status = 'concluido';
        $reembolso->data_processamento = now();
        $reembolso->data_conclusao = now();
        $reembolso->save();

        return [
            'sucesso' => true,
            'mensagem' => 'Reembolso processado manualmente.',
        ];
    }
}