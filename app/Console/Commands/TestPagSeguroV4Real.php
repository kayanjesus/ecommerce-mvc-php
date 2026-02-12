<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PagSeguroService;

class TestPagSeguroV4Real extends Command
{
    protected $signature = 'pagseguro:test-v4';
    protected $description = 'Testar API V4 real do PagSeguro';

    public function handle()
    {
        $this->info("=== TESTE API V4 PAGSEGURO ===");

        $service = new PagSeguroService();

        $this->info("Bearer Token (início): " . substr(config('pagseguro.bearer_token'), 0, 20) . "...");
        $this->info("Sandbox: " . (config('pagseguro.sandbox') ? 'Sim' : 'Não'));

        // Testar conexão
        $this->info("\n=== TESTANDO CONEXÃO ===");
        $conexao = $service->testarConexao();

        $this->info("Status HTTP: " . ($conexao['status'] ?? 'N/A'));

        if ($conexao['success']) {
            $this->info("Conexão bem-sucedida!");
        } else {
            $this->error("Falha: " . ($conexao['error'] ?? 'Desconhecido'));
        }

        // Verificar uma transação real
        $this->info("\n=== VERIFICANDO TRANSAÇÃO ===");
        $pedido = \App\Models\Pedido::whereHas('pagamentoCheckout', function ($q) {
            $q->whereNotNull('codigo_transacao');
        })->first();

        if ($pedido && $pedido->pagamentoCheckout->codigo_transacao) {
            $this->info("Pedido: #{$pedido->id_pedido}");
            $this->info("Código transação: {$pedido->pagamentoCheckout->codigo_transacao}");

            // Verificar transação
            $transacao = $service->verificarTransacao($pedido->pagamentoCheckout->codigo_transacao);

            if ($transacao) {
                $this->info("Transação encontrada na API V4!");
                $this->info("Status: " . ($transacao['status'] ?? 'N/A'));
                $this->info("Valor: R$ " . (($transacao['amount']['value'] ?? 0) / 100));

                if ($this->confirm('Deseja testar um reembolso de R$ 1,00?')) {
                    $this->info("\n=== TESTE DE REEMBOLSO (R$ 1,00) ===");

                    $resultado = $service->criarReembolso(
                        $pedido->pagamentoCheckout->codigo_transacao,
                        1.00,
                        'Teste de reembolso via comando'
                    );

                    $this->info("API: " . ($resultado['api'] ?? 'N/A'));

                    if ($resultado['success']) {
                        $this->info("Reembolso criado com sucesso!");
                        $this->info("Código: " . ($resultado['codigo_reembolso'] ?? 'N/A'));
                        $this->info("Status: " . ($resultado['status'] ?? 'N/A'));
                    } else {
                        $this->error("Falha: " . ($resultado['erro'] ?? 'Desconhecido'));

                        // Mostrar detalhes se houver
                        if (isset($resultado['detalhes'])) {
                            $this->error("Detalhes: " . json_encode($resultado['detalhes']));
                        }
                    }
                }
            } else {
                $this->warn("Transação não encontrada ou erro na API");
            }
        } else {
            $this->warn("Nenhum pedido com código de transação encontrado.");
        }

        return 0;
    }
}