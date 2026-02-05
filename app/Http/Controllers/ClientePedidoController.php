<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Http;
use App\Models\Pedido;
use App\Models\Entrega;
use App\Models\Reembolso; // Importar o modelo Reembolso
use App\Models\Avaliacao; // Importar o modelo Avaliacao
use App\Models\PedidoItem; // Importar o modelo PedidoItem
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException; // Para lançar exceções de validação
use App\Services\PagSeguroService;
use App\Services\ReembolsoService;

class ClientePedidoController extends Controller
{

    protected $pagSeguroService;

    public function __construct()
    {
        $this->pagSeguroService = new PagSeguroService();
    }
    public function meusPedidos()
    {
        $pedidos = Pedido::where('id_usuario', Auth::id())
            ->with(['itens.produto.imagens', 'itens.cor', 'itens.tamanho', 'entrega', 'reembolso', 'itens.avaliacao']) // Carregar avaliações dos itens
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('home.meus_pedidos', compact('pedidos')); // Sua view de lista pode ser 'home.meus_pedidos'
    }

    /**
     * Detalhes de um pedido específico para o cliente.
     */
    public function verDetalhesPedido(Pedido $pedido) // Usando Route Model Binding
    {
        if ($pedido->id_usuario !== Auth::id()) {
            abort(403, 'Acesso não autorizado a este pedido.');
        }

        $pedido->load(['itens.produto.imagens', 'itens.cor', 'itens.tamanho', 'pagamentoCheckout', 'entrega.rastreio', 'reembolso', 'itens.avaliacao']);

        return view('home.detalhes_meu_pedido', compact('pedido')); // Ou a view que exibe os detalhes do pedido
    }

    /**
     * Ação do cliente para cancelar um pedido.
     */
    public function cancelarPedido(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        if (!$pedido->podeSerCanceladoPeloCliente()) {
            return back()->with('error', 'Este pedido não pode ser cancelado.');
        }

        if ($pedido->id_usuario != Auth::id()) {
            return back()->with('error', 'Você não tem permissão para cancelar este pedido.');
        }

        $reembolsoService = new ReembolsoService(new PagSeguroService());
        $resultado = $reembolsoService->processarCancelamentoComReembolso(
            $pedido,
            $request->input('motivo', 'Cancelamento solicitado pelo cliente')
        );

        if ($resultado['sucesso']) {
            $tipo = isset($resultado['alerta']) ? 'warning' : 'success';
            return back()->with($tipo, $resultado['mensagem']);
        } else {
            return back()->with('error', $resultado['mensagem']);
        }
    }


    /**
     * Processar reembolso no PagSeguro
     */
    private function processarReembolsoPagSeguro(Pedido $pedido, Reembolso $reembolso)
    {
        try {
            // Obter credenciais do PagSeguro
            $credentials = config('pagseguro.getCredentials')();

            $email = $credentials['email'];
            $token = $credentials['token'];
            $baseUrl = $credentials['url'];

            // Verificar código de transação
            if (!$pedido->pagamentoCheckout || !$pedido->pagamentoCheckout->codigo_transacao) {
                Log::warning("Pedido sem código de transação para reembolso");
                return false;
            }

            $codigoTransacao = $pedido->pagamentoCheckout->codigo_transacao;
            $url = "{$baseUrl}/v2/transactions/refunds";

            // Preparar dados
            $data = [
                'email' => $email,
                'token' => $token,
                'transactionCode' => $codigoTransacao,
                'refundValue' => number_format($reembolso->valor_reembolso, 2, '.', ''),
            ];

            Log::info("Solicitando reembolso PagSeguro", [
                'url' => $url,
                'transactionCode' => $codigoTransacao,
                'value' => $reembolso->valor_reembolso,
            ]);

            // Fazer requisição
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            Log::info("Resposta PagSeguro", [
                'http_code' => $httpCode,
                'response' => $response,
            ]);

            if ($httpCode === 200) {
                $xml = simplexml_load_string($response);
                if ($xml && isset($xml->result)) {
                    $codigoReembolso = (string) $xml->result;
                    $reembolso->codigo_reembolso_pagseguro = $codigoReembolso;
                    $reembolso->save();

                    Log::info("Reembolso criado com sucesso", [
                        'codigo_reembolso' => $codigoReembolso,
                    ]);

                    return true;
                }
            } else {
                Log::error("Erro no reembolso PagSeguro", [
                    'http_code' => $httpCode,
                    'response' => $response,
                ]);
            }

            return false;

        } catch (\Exception $e) {
            Log::error("Exception no reembolso PagSeguro", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Ação do cliente para confirmar a entrega de um pedido.
     * Altera o status do pedido para 'entregue' e marca como confirmado pelo cliente.
     */
    public function confirmarEntrega(Pedido $pedido)
    {
        if ($pedido->id_usuario !== Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para confirmar este pedido.');
        }

        // Verifica se o pedido pode ser confirmado
        // (ex: status é 'enviado', 'em_transito', 'saiu_para_entrega' ou 'entregue' pelo admin e ainda não confirmado pelo cliente)
        if (!$pedido->podeConfirmarEntrega()) {
            return redirect()->back()->with('error', 'Este pedido não pode ser confirmado neste momento. Verifique o status.');
        }

        DB::beginTransaction();
        try {
            // O status do pedido se torna 'entregue' AQUI, pela ação do cliente.
            $pedido->status = 'entregue';
            $pedido->confirmado_pelo_cliente = true; // Marca como confirmado pelo cliente
            $pedido->save();

            // Define a data de entrega na tabela 'entregas' se ainda não estiver definida.
            // Isso é importante para o cálculo do prazo de reembolso.
            if ($pedido->entrega && !$pedido->entrega->data_entrega) {
                $pedido->entrega->data_entrega = Carbon::now();
                $pedido->entrega->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Recebimento do pedido #' . $pedido->id_pedido . ' confirmado com sucesso! Agora você pode avaliá-lo e solicitar reembolso.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao confirmar recebimento do pedido #{$pedido->id_pedido}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao confirmar o recebimento. Por favor, tente novamente.');
        }
    }

    /**
     * Exibe o formulário para o cliente avaliar os itens de um pedido.
     */
    public function avaliarView(Pedido $pedido)
    {
        if ($pedido->id_usuario !== Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para acessar este formulário de avaliação.');
        }

        if (!$pedido->podeAvaliar()) {
            return redirect()->back()->with('error', 'Este pedido ainda não pode ser avaliado ou já foi totalmente avaliado.');
        }

        $pedido->load(['itens.produto', 'itens.avaliacao']);

        $itensParaAvaliar = $pedido->itens->filter(fn($item) => $item->avaliacao === null);

        if ($itensParaAvaliar->isEmpty()) {
            return redirect()->route('cliente.pedidos.verDetalhesPedido', $pedido->id_pedido)->with('info', 'Todos os itens deste pedido já foram avaliados.');
        }

        return view('home.avaliar_pedido', compact('pedido', 'itensParaAvaliar'));
    }

    /**
     * Processa a submissão das avaliações dos itens de um pedido pelo cliente.
     */
    public function salvarAvaliacoes(Request $request, Pedido $pedido)
    {
        if ($pedido->id_usuario !== Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para realizar esta ação.');
        }

        if (!$pedido->podeAvaliar()) {
            return redirect()->back()->with('error', 'Este pedido não pode ser avaliado no momento ou já foi totalmente avaliado.');
        }

        $request->validate([
            'avaliacoes' => 'required|array',
            'avaliacoes.*.id_item' => 'required|integer|exists:pedido_itens,id_item',
            'avaliacoes.*.nota' => 'required|integer|min:1|max:5',
            'avaliacoes.*.comentario' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->input('avaliacoes') as $avaliacaoData) {
                $pedidoItem = PedidoItem::where('id_item', $avaliacaoData['id_item'])
                    ->where('id_pedido', $pedido->id_pedido)
                    ->first();

                if (!$pedidoItem) {
                    throw ValidationException::withMessages(['avaliacoes' => 'Um item de avaliação inválido foi fornecido.']);
                }

                $existingAvaliacao = Avaliacao::where('id_pedido_item', $pedidoItem->id_item)
                    ->where('id_usuario', Auth::id())
                    ->first();

                if ($existingAvaliacao) {
                    $existingAvaliacao->update([
                        'nota' => $avaliacaoData['nota'],
                        'comentario' => $avaliacaoData['comentario'],
                    ]);
                } else {
                    Avaliacao::create([
                        'id_pedido_item' => $pedidoItem->id_item,
                        'id_usuario' => Auth::id(),
                        'id_produto' => $pedidoItem->id_produto,
                        'nota' => $avaliacaoData['nota'],
                        'comentario' => $avaliacaoData['comentario'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('cliente.pedidos.verDetalhesPedido', $pedido->id_pedido)->with('success', 'Suas avaliações foram enviadas com sucesso!');

        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao salvar avaliações do pedido #{$pedido->id_pedido}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao enviar suas avaliações. Por favor, tente novamente.');
        }
    }

    /**
     * Ação do cliente para solicitar reembolso para um pedido.
     * Cria um registro de reembolso e atualiza o status do pedido.
     */
    public function solicitarReembolso(Request $request, Pedido $pedido)
    {
        if ($pedido->id_usuario !== Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para solicitar reembolso para este pedido.');
        }

        if (!$pedido->podeSolicitarReembolso()) {
            $errorMessage = 'Não é possível solicitar reembolso para este pedido no momento.';
            if ($pedido->status !== 'entregue' || !$pedido->confirmado_pelo_cliente) {
                $errorMessage .= ' O pedido não foi entregue ou confirmado pelo cliente.';
            } elseif ($pedido->entrega && $pedido->entrega->data_entrega && $pedido->prazoReembolsoRestante !== null && $pedido->prazoReembolsoRestante <= 0) {
                $errorMessage .= ' O prazo para solicitação de reembolso expirou.';
            } elseif ($pedido->reembolso && in_array($pedido->reembolso->status, ['solicitado', 'aprovado', 'processando', 'concluido'])) {
                $errorMessage .= ' Já existe uma solicitação de reembolso pendente ou concluída para este pedido.';
            }
            return redirect()->back()->with('error', $errorMessage);
        }

        $request->validate([
            'motivo_reembolso' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            Reembolso::create([
                'id_pedido' => $pedido->id_pedido,
                'valor_reembolso' => $pedido->total,
                'motivo' => $request->input('motivo_reembolso'),
                'status' => 'solicitado',
                'data_solicitacao' => Carbon::now(),
            ]);

            $pedido->status = 'reembolso_solicitado';
            $pedido->status_reembolso = 'solicitado';
            $pedido->save();

            DB::commit();
            return redirect()->back()->with('success', 'Solicitação de reembolso para o pedido #' . $pedido->id_pedido . ' enviada com sucesso! Acompanhe o status na seção de detalhes do pedido.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao solicitar reembolso para o pedido #{$pedido->id_pedido} pelo cliente: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao solicitar o reembolso. Por favor, tente novamente.');
        }
    }
}