<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pedido;
use App\Models\Reembolso;

// Pega o primeiro pedido com código de transação
$pedido = Pedido::whereHas('pagamentoCheckout', function($q) {
    $q->whereNotNull('codigo_transacao');
})->first();

if (!$pedido) {
    echo "Nenhum pedido com código de transação encontrado.\n";
    exit;
}

echo "Pedido encontrado: #" . $pedido->id_pedido . "\n";
echo "Código transação: " . $pedido->pagamentoCheckout->codigo_transacao . "\n";
echo "Valor: R$ " . $pedido->total . "\n";

// Cria reembolso
$reembolso = Reembolso::create([
    'id_pedido' => $pedido->id_pedido,
    'valor_reembolso' => $pedido->total,
    'motivo' => 'Teste manual',
    'status' => 'solicitado',
    'data_solicitacao' => now(),
]);

// Testa o método
$controller = new App\Http\Controllers\ClientePedidoController();
$result = $controller->processarReembolsoPagSeguro($pedido, $reembolso);

echo "\nResultado: " . ($result ? "✅ Sucesso" : "❌ Falha") . "\n";