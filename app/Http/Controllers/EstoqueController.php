<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\ProdutoVariacoes;
use App\Models\PagamentoCheckout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstoqueController extends Controller
{
    /**
     * Atualiza o estoque quando um pedido é confirmado/pago
     */
    public static function atualizarEstoquePedido($pedidoId)
    {
        try {
            $pedido = Pedido::with('itens')->find($pedidoId);

            if (!$pedido) {
                Log::error("Pedido #{$pedidoId} não encontrado para atualização de estoque.");
                return false;
            }

            DB::beginTransaction();

            foreach ($pedido->itens as $item) {
                // Busca a variação específica do produto (cor + tamanho)
                $variacao = ProdutoVariacoes::where('produto_id', $item->id_produto)
                    ->where('cor_id', $item->id_cor)
                    ->where('tamanho_id', $item->id_tamanho)
                    ->first();

                if ($variacao) {
                    if ($variacao->estoque >= $item->quantidade) {
                        $variacao->estoque -= $item->quantidade;
                        $variacao->save();

                        Log::info("Estoque atualizado: Produto #{$item->id_produto}, Cor #{$item->id_cor}, Tamanho #{$item->id_tamanho}, Quantidade: -{$item->quantidade}, Estoque restante: {$variacao->estoque}");
                    } else {
                        DB::rollBack();
                        Log::error("Estoque insuficiente para o produto #{$item->id_produto}. Disponível: {$variacao->estoque}, Solicitado: {$item->quantidade}");
                        return false;
                    }
                } else {
                    DB::rollBack();
                    Log::error("Variação não encontrada para o produto #{$item->id_produto}, Cor #{$item->id_cor}, Tamanho #{$item->id_tamanho}");
                    return false;
                }
            }

            // Marca o pagamento como processado
            $pagamento = PagamentoCheckout::where('id_pedido', $pedidoId)->first();
            if ($pagamento) {
                $pagamento->estoque_processado = true;
                $pagamento->estoque_atualizado_em = now();
                $pagamento->save();
            }

            DB::commit();
            Log::info("Estoque atualizado com sucesso para o pedido #{$pedidoId}");
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao atualizar estoque do pedido #{$pedidoId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Restaura o estoque quando um pedido é cancelado/reembolsado
     */
    public static function restaurarEstoquePedido($pedidoId)
    {
        try {
            $pedido = Pedido::with('itens')->find($pedidoId);

            if (!$pedido) {
                Log::error("Pedido #{$pedidoId} não encontrado para restauração de estoque.");
                return false;
            }

            DB::beginTransaction();

            foreach ($pedido->itens as $item) {
                $variacao = ProdutoVariacoes::where('produto_id', $item->id_produto)
                    ->where('cor_id', $item->id_cor)
                    ->where('tamanho_id', $item->id_tamanho)
                    ->first();

                if ($variacao) {
                    $variacao->estoque += $item->quantidade;
                    $variacao->save();
                    Log::info("Estoque restaurado: Produto #{$item->id_produto}, Cor #{$item->id_cor}, Tamanho #{$item->id_tamanho}, Quantidade: +{$item->quantidade}, Estoque atual: {$variacao->estoque}");
                } else {
                    Log::warning("Variação não encontrada para restaurar estoque do produto #{$item->id_produto}");
                }
            }

            DB::commit();
            Log::info("Estoque restaurado com sucesso para o pedido #{$pedidoId}");
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao restaurar estoque do pedido #{$pedidoId}: " . $e->getMessage());
            return false;
        }
    }
}