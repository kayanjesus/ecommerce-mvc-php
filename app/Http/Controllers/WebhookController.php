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

        // **ALTERAÇÃO IMPORTANTE 1: Validação de Segurança do Webhook**
        // É CRÍTICO validar a assinatura para garantir que a requisição é do PagSeguro.
        // Você precisará configurar uma SECRET KEY no painel do PagSeguro para o seu webhook.
        // Adicione PAGSEGURO_WEBHOOK_SECRET="sua_chave_secreta_aqui" ao seu arquivo .env
        $webhookSecret = env('PAGSEGURO_WEBHOOK_SECRET');

        if ($webhookSecret) {
            $signature = $request->header('x-ps-signature') ?? $request->header('x-pagseguro-signature');

            if (!$signature) {
                Log::warning('Webhook PagSeguro: Assinatura faltando no cabeçalho. Requisição potencialmente inválida.');
                return response()->json(['message' => 'Unauthorized: Signature missing'], 401);
            }

            // O conteúdo do corpo da requisição é necessário para validar a assinatura
            $requestBody = $request->getContent();
            $expectedSignature = hash_hmac('sha256', $requestBody, $webhookSecret);

            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('Webhook PagSeguro: Assinatura inválida. Requisição potencialmente forjada.', [
                    'received_signature' => $signature,
                    'expected_signature_calculated' => $expectedSignature,
                    'payload_sent' => $payload // Loga o payload para depuração
                ]);
                return response()->json(['message' => 'Unauthorized: Invalid signature'], 401);
            }
        } else {
            Log::warning('PAGSEGURO_WEBHOOK_SECRET não configurado. Validação de assinatura de webhook desabilitada (APENAS PARA DESENVOLVIMENTO!).');
            // Em produção, isso deve ser um ERRO CRÍTICO que impede o processamento sem validação.
            // Para testar em desenvolvimento, você pode deixar assim por um tempo, mas corrija para produção.
        }

        // --- Lógica para lidar com notificações baseadas em 'notificationCode' ---
        if (isset($payload['notificationCode']) && isset($payload['notificationType']) && $payload['notificationType'] === 'transaction') {
            $notificationCode = $payload['notificationCode'];
            Log::info("Webhook PagSeguro: Recebido notificationCode: {$notificationCode}. Buscando detalhes completos da transação.");

            try {
                // Certifique-se que config('pagseguro.token') está com o token correto (sandbox ou produção)
                $pagseguroToken = config('pagseguro.token');
                $pagseguroEnvironment = config('pagseguro.environment') === 'sandbox' ? 'sandbox.' : '';
                $pagseguroApiUrl = "https://{$pagseguroEnvironment}api.pagseguro.com/notifications/{$notificationCode}";

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $pagseguroToken,
                    'Content-Type' => 'application/json',
                    'x-api-version' => '4', // Confirme se esta API Version é a correta para seu token e endpoint
                ])->get($pagseguroApiUrl);

                if ($response->successful()) {
                    $notificationData = $response->json();
                    Log::info('Detalhes da notificação PagSeguro obtidos via API:', $notificationData);

                    // Reatribui $payload para a lógica de processamento genérica com os dados completos
                    $payload = $notificationData;

                } else {
                    Log::error('Erro ao buscar detalhes da notificação do PagSeguro (STATUS NON-SUCCESSFUL):', [
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                    // Retorna um status de erro para o PagSeguro reenviar a notificação
                    return response()->json(['message' => 'Falha ao buscar detalhes da notificação.'], 500);
                }
            } catch (\Exception $e) {
                Log::error('Exceção ao se comunicar com a API do PagSeguro para buscar notificação:', [
                    'notificationCode' => $notificationCode,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString() // Adiciona o trace para melhor depuração
                ]);
                // Retorna um status de erro para o PagSeguro reenviar
                return response()->json(['message' => 'Erro interno na comunicação com PagSeguro.'], 500);
            }
        }
        // --- Fim da lógica para 'notificationCode' ---

        // A partir daqui, $payload DEVE conter o formato completo da ordem, seja vindo direto ou após a consulta por notificationCode
        $pagSeguroOrderId = $payload['id'] ?? null; // ID da Ordem no PagSeguro (ex: ORDE_...)
        $referenceId = $payload['reference_id'] ?? null; // Sua referência interna (ex: pedido-5)
        $charges = $payload['charges'] ?? []; // Lista de cobranças dentro da ordem

        if (!$pagSeguroOrderId || !$referenceId || empty($charges)) {
            Log::warning('Webhook PagSeguro: Payload da ordem incompleto ou inesperado (após tentativa de buscar por código).', $payload);
            return response()->json(['message' => 'Dados de ordem incompletos ou inesperados'], 400);
        }

        // Extrai o ID do pedido local (ex: de 'pedido-5' para '5')
        $parts = explode('-', $referenceId);
        $localPedidoId = end($parts);

        $pedido = Pedido::find($localPedidoId);

        if (!$pedido) {
            Log::warning("Webhook PagSeguro: Pedido com reference_id '{$referenceId}' (ID local: {$localPedidoId}) não encontrado em seu sistema.");
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        // Percorre todas as cobranças dentro desta ordem
        foreach ($charges as $charge) {
            // **ALTERAÇÃO IMPORTANTE 2: Focando na cobrança PIX (ou outro tipo) e seu status**
            // Note que o 'status' que vem na cobrança é o que nos interessa para o Pix/Cartão.
            // O 'id' da cobrança ('chargeId') é diferente do 'id' da ordem ('pagSeguroOrderId').
            // Vamos usar o 'pagSeguroOrderId' para buscar o pagamento em 'codigo_transacao'
            // porque no seu `processarPagamento` você salvou o `pagSeguroOrderId` (ORDE_...) lá.

            $pagseguroStatus = $charge['status'] ?? 'UNKNOWN'; // Status da cobrança específica (ex: PAID, PENDING)
            $chargeMethodType = $charge['payment_method']['type'] ?? null; // Ex: PIX, CREDIT_CARD
            $chargeId = $charge['id'] ?? null; // ID da cobrança específica (ex: CHAR_...)

            Log::info("Processando charge para pedido {$pedido->id_pedido}:", [
                'charge_id' => $chargeId,
                'method_type' => $chargeMethodType,
                'pagseguro_status' => $pagseguroStatus
            ]);


            // Acha o registro de PagamentoCheckout correspondente no seu banco de dados
            // Usamos o pagSeguroOrderId (ID da Ordem) porque é o que você salva em 'codigo_transacao'
            // quando a ordem é criada no PagSeguro (no seu método `processarPagamento`).
            $pagamento = PagamentoCheckout::where('id_pedido', $pedido->id_pedido)
                ->where('codigo_transacao', $pagSeguroOrderId) // Busca pelo ID da Ordem (ORDE_...)
                ->first();

            // Se por algum motivo não encontrar pelo pagSeguroOrderId, tente pelo método Pix.
            // Isso pode ser útil se o 'codigo_transacao' não foi preenchido corretamente na criação,
            // mas é menos ideal do que ter um ID único para cada pagamento.
            if (!$pagamento && $chargeMethodType === 'PIX') {
                $pagamento = PagamentoCheckout::where('id_pedido', $pedido->id_pedido)
                    ->where('metodo_pagamento', 'pix')
                    ->first();
                Log::warning("Webhook PagSeguro: PagamentoCheckout não encontrado pelo 'codigo_transacao' ({$pagSeguroOrderId}) para pedido {$pedido->id_pedido}. Tentando por 'metodo_pagamento'.");
            }


            if ($pagamento) {
                // Mapeia os status do PagSeguro para os seus status internos
                $newPagamentoStatus = $this->mapPagSeguroPagamentoStatus($pagseguroStatus);
                $newPedidoStatus = $this->mapPagSeguroPedidoStatus($pagseguroStatus);

                // **ALTERAÇÃO IMPORTANTE 3: Salva o ID da Cobrança nos detalhes, se necessário**
                // Isso pode ser útil para depuração e para ter o CHAR_ID registrado.
                $pagamento->detalhes = array_merge($pagamento->detalhes ?? [], ['pagseguro_charge_id' => $chargeId]);

                // Verifica se o status mudou para evitar gravações desnecessárias
                if ($newPagamentoStatus !== $pagamento->status) {
                    $pagamento->status = $newPagamentoStatus;

                    // Define ou limpa a data de pagamento com base no novo status
                    if ($newPagamentoStatus === 'pago') {
                        $pagamento->data_pagamento = now();
                    } else {
                        // Se não estiver pago, pode limpar a data de pagamento (se aplicável ao seu negócio)
                        // ou manter a data original se for um status temporário.
                        // Para PIX, uma vez pago, a data fica; se for recusado/expirado, não há data.
                        $pagamento->data_pagamento = null;
                    }
                    $pagamento->save();
                    Log::info("Pagamento {$pagamento->id_pagamento} do pedido {$pedido->id_pedido} atualizado para status: {$newPagamentoStatus}.");

                    // Atualiza o status do pedido se for diferente
                    if ($newPedidoStatus !== $pedido->status) {
                        $pedido->status = $newPedidoStatus;
                        $pedido->save();
                        Log::info("Pedido {$pedido->id_pedido} atualizado para status: {$newPedidoStatus}.");
                    }

                    // Envia notificação ao admin apenas quando o pedido é realmente pago
                    if ($newPagamentoStatus === 'pago') {
                        $adminUser = User::where('access_level', 'admin')->first();
                        if ($adminUser) {
                            Notification::send($adminUser, new NovoPedidoNotification($pedido));
                            Log::info("Notificação de pagamento {$pedido->id_pedido} enviada ao admin.");
                        }
                    }

                } else {
                    Log::info("Webhook PagSeguro: Status para pedido {$pedido->id_pedido} e pagamento já está {$pagamento->status}. Nenhuma alteração necessária.");
                }
            } else {
                Log::warning("Webhook PagSeguro: Registro de PagamentoCheckout para pedido {$pedido->id_pedido} (Ordem PagSeguro ID: {$pagSeguroOrderId}) não encontrado, ou o CHAR_ID não foi a chave principal. (Charge ID: {$chargeId})");
            }
        }

        // Resposta de sucesso para o PagSeguro
        return response()->json(['message' => 'Notificação processada com sucesso'], 200);
    }

    // --- Mapeamento de status: Verifique se esses status existem nos seus ENUMs do banco de dados! ---
    protected function mapPagSeguroPedidoStatus($pagseguroStatus)
    {
        switch ($pagseguroStatus) {
            case 'PAID':
                return 'pago'; // Confirme que 'pago' é um status válido em Pedido
            case 'DECLINED':
            case 'CANCELED':
            case 'EXPIRED':
            case 'REFUNDED': // Adicionado, caso o PagSeguro envie este status
                return 'cancelado'; // Confirme que 'cancelado' é um status válido em Pedido
            case 'PENDING':
            case 'APPROVED': // 'APPROVED' pode ser tratado como 'pendente' ou 'processando'
                return 'pendente'; // Confirme que 'pendente' é um status válido em Pedido
            default:
                return 'pendente'; // Default para status desconhecidos
        }
    }

    // **ALTERAÇÃO IMPORTANTE 4: Mapeamento de status para PagamentoCheckout**
    // Garanta que todos esses status ('pago', 'recusado', 'cancelado', 'expirado', 'pendente')
    // existam na sua coluna 'status' da tabela `pagamentos` (PagamentoCheckout)!
    // Se eles não existirem no ENUM, você terá um erro de "Data truncated for column 'status'".
    // Considere mudar o tipo da coluna 'status' para VARCHAR(50) ou adicionar os valores ao ENUM.
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
            case 'REFUNDED': // Adicionado, caso o PagSeguro envie este status
                return 'reembolsado'; // Crie esse status no seu ENUM se precisar
            case 'PENDING':
            case 'APPROVED':
                return 'pendente';
            default:
                return 'pendente';
        }
    }


        private function notificarAdministradores(Pedido $pedido)
    {
        try {
            $administradores = User::where('access_level', 'admin')->get();

            foreach ($administradores as $admin) {
                try {
                    $admin->notify(new NovoPedidoNotification($pedido));
                    Log::info("Notificação de pagamento {$pedido->id_pedido} enviada ao admin {$admin->id}.");
                } catch (\Exception $e) {
                    Log::error("Falha ao notificar admin {$admin->id}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error("Erro no processo de notificação: " . $e->getMessage());
        }
    }
}