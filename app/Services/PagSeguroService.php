<?php

namespace App\Services;

use App\Support\PagSeguroUrl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagSeguroService
{
    private $bearerToken;
    private $sandbox;
    private $baseUrl;

    public function __construct()
    {
        // API V4 usa bearer token
        $this->bearerToken = config('pagseguro.bearer_token');
        $this->sandbox = config('pagseguro.sandbox', true);
        $this->baseUrl = PagSeguroUrl::v4();
    }

    /**
     * Criar reembolso na API V4
     */
    public function criarReembolso(string $codigoTransacao, float $valor, string $motivo = '')
    {
        try {
            Log::info("Criando reembolso na API V4", [
                'codigo_transacao' => $codigoTransacao,
                'valor' => $valor,
                'motivo' => $motivo,
            ]);

            // Remover prefixo ORDE_ se existir
            if (strpos($codigoTransacao, 'ORDE_') === 0) {
                $codigoTransacao = substr($codigoTransacao, 5); // Remove "ORDE_"
                Log::info("Código transação ajustado: {$codigoTransacao}");
            }

            $url = "{$this->baseUrl}/orders/{$codigoTransacao}/refunds";

            Log::info("URL API V4: {$url}");

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($url, [
                'amount' => [
                    'value' => (int) round($valor * 100), // Em centavos
                    'currency' => 'BRL',
                ],
                'reason' => $motivo ?: 'Cancelamento solicitado pelo cliente',
            ]);

            $statusCode = $response->status();
            $body = $response->json();

            Log::info("Resposta API V4", [
                'status' => $statusCode,
                'response' => $body,
            ]);

            if ($statusCode === 201 || $statusCode === 200) {
                return [
                    'success' => true,
                    'codigo_reembolso' => $body['id'] ?? $codigoTransacao . '-REFUND',
                    'status' => $body['status'] ?? 'CREATED',
                    'api' => 'v4',
                    'dados' => $body,
                ];
            }

            // Se for 403, pode ser falta de permissão específica
            if ($statusCode === 403) {
                return [
                    'success' => false,
                    'erro' => 'Permissão negada. Verifique se sua conta tem permissão para reembolsos.',
                    'api' => 'v4',
                    'detalhes' => $body,
                ];
            }

            return [
                'success' => false,
                'erro' => $body['error_messages'] ?? $body['message'] ?? 'Erro desconhecido na API V4',
                'api' => 'v4',
                'detalhes' => $body,
            ];
        } catch (\Exception $e) {
            Log::error("Exceção API V4: " . $e->getMessage());
            return [
                'success' => false,
                'erro' => $e->getMessage(),
                'api' => 'v4',
            ];
        }
    }

    /**
     * Verificar uma transação na API V4
     */
    public function verificarTransacao(string $codigoTransacao)
    {
        try {
            // Remover prefixo ORDE_ se existir
            if (strpos($codigoTransacao, 'ORDE_') === 0) {
                $codigoTransacao = substr($codigoTransacao, 5);
            }

            $url = "{$this->baseUrl}/orders/{$codigoTransacao}";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'Accept' => 'application/json',
            ])->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Erro ao verificar transação API V4: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar detalhes completos de uma notificação de webhook a partir do
     * notificationCode enviado pelo PagSeguro (fluxo antigo, ainda usado
     * pelo WebhookController quando o payload não vem completo).
     */
    public function buscarNotificacao(string $notificationCode)
    {
        $url = "{$this->baseUrl}/notifications/{$notificationCode}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->bearerToken,
            'Content-Type' => 'application/json',
            'x-api-version' => '4',
        ])->get($url);

        return $response;
    }

    /**
     * Testar conexão com API V4
     */
    public function testarConexao()
    {
        try {
            $url = "{$this->baseUrl}/charges";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'Accept' => 'application/json',
            ])->timeout(10)->get($url, [
                'limit' => 1,
            ]);

            $statusCode = $response->status();
            $body = $response->json();

            return [
                'status' => $statusCode,
                'success' => $response->successful(),
                'data' => $body,
                'error' => $response->failed() ? ($body['error_message'] ?? 'Erro desconhecido') : null,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 0,
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Método SIMULADO para desenvolvimento local
     */
    public function criarReembolsoSimulado(string $codigoTransacao, float $valor, string $motivo = '')
    {
        Log::info("SIMULANDO reembolso para desenvolvimento", [
            'codigo_transacao' => $codigoTransacao,
            'valor' => $valor,
            'motivo' => $motivo,
        ]);

        sleep(2);

        return [
            'success' => true,
            'codigo_reembolso' => 'SIM-' . time() . '-' . substr($codigoTransacao, -8),
            'status' => 'APPROVED',
            'api' => 'simulado',
            'mensagem' => 'Reembolso simulado para ambiente de desenvolvimento',
        ];
    }
}
