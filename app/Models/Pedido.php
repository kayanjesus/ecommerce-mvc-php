<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon; // Importe Carbon para manipulação de datas

// Model Pedido
class Pedido extends Model
{
    protected $primaryKey = 'id_pedido';
    protected $fillable = [
        'id_usuario',
        'total',
        'status',
        'endereco_entrega',
        'observacoes',
        'data_pedido',
        'status_reembolso', // Adicione esta coluna ao fillable
        'confirmado_pelo_cliente'
    ];

    protected $casts = [
        'endereco_entrega' => 'array',
        'data_pedido' => 'datetime',
        'confirmado_pelo_cliente' => 'boolean'
    ];

    protected $dates = [
        'data_pedido',
        'created_at',
        'updated_at'
    ];

    /**
     * Relacionamento com o usuário que fez o pedido.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Relacionamento com os itens do pedido.
     */
    public function itens(): HasMany
    {
        return $this->hasMany(PedidoItem::class, 'id_pedido');
    }

    /**
     * Relacionamento com o histórico de status do pedido.
     */
    public function historicoStatus(): HasMany
    {
        return $this->hasMany(PedidoStatus::class, 'id_pedido', 'id_pedido');
    }

    /**
     * Relacionamento com as informações de pagamento do checkout.
     */
    public function pagamentoCheckout(): HasOne
    {
        return $this->hasOne(PagamentoCheckout::class, 'id_pedido');
    }

    /**
     * Relacionamento com o cupom aplicado (se houver).
     */
    public function cupom(): BelongsTo
    {
        return $this->belongsTo(Cupom::class, 'id_cupom');
    }

    /**
     * Relacionamento com as informações de entrega.
     */
    public function entrega(): HasOne
    {
        // 'id_pedido' é a FK na tabela 'entregas' que referencia a PK deste modelo
        return $this->hasOne(Entrega::class, 'id_pedido');
    }

    /**
     * Relacionamento com as solicitações de reembolso.
     */


    /**
     * Calcula o valor do frete com base no CEP e no total do pedido.
     *
     * @param string $cep
     * @return float
     */
    public function calcularFrete(string $cep): float
    {
        $primeiroDigito = substr(preg_replace('/[^0-9]/', '', $cep), 0, 1);
        $estado = $this->endereco_entrega['estado'] ?? null;

        if ($estado === 'SP' && $this->total >= 250) {
            return 0.00;
        } elseif ($this->total >= 399) {
            return 0.00;
        }

        return match ($primeiroDigito) {
            '0', '1', '2', '3' => 25.00,
            default => 35.00
        };
    }

    /**
     * Retorna a qualificação para frete grátis, se aplicável.
     *
     * @return string|null
     */
    public function qualificarFreteGratis(): ?string
    {
        $estado = $this->endereco_entrega['estado'] ?? null;

        if ($estado === 'SP' && $this->total >= 250) {
            return 'Frete grátis para São Paulo (acima de R$ 250)';
        } elseif ($this->total >= 399) {
            return 'Frete grátis nacional (acima de R$ 399)';
        }

        return null;
    }

    /**
     * Aplica um cupom de desconto ao pedido.
     *
     * @param string $codigoCupom
     * @return bool
     */
    public function aplicarCupom(string $codigoCupom): bool
    {
        $cupom = Cupom::where('codigo', $codigoCupom)->first();

        if ($cupom && $cupom->estaValido()) {
            $this->id_cupom = $cupom->id_cupom;
            $this->total = $cupom->aplicarDesconto($this->total);
            $cupom->registrarUso();
            return true;
        }

        return false;
    }

    /**
     * Verifica se o pedido pode ser cancelado pelo cliente.
     * Regra: Só pode cancelar se o status for 'pendente' ou 'pago'.
     *
     * @return bool
     */
    public function podeSerCanceladoPeloCliente(): bool
    {
        return in_array($this->status, ['pendente', 'pago']);
    }

    /**
     * Verifica se o cliente pode solicitar um reembolso para este pedido.
     * Regra: Pedido deve estar 'entregue' e dentro do prazo de 7 dias após a entrega.
     * E não pode já ter um reembolso solicitado ou aprovado.
     *
     * @return bool
     */
    public function podeSolicitarReembolso(): bool
    {
        // O pedido precisa estar no status 'entregue' e confirmado pelo cliente
        if ($this->status !== 'entregue' || !$this->confirmado_pelo_cliente) {
            return false;
        }

        // O pedido precisa ter um registro de entrega com data
        if (!$this->entrega || !$this->entrega->data_entrega) {
            return false;
        }

        // Carrega o relacionamento de reembolso se não carregou
        $this->loadMissing('reembolso');

        // Verifica se já existe uma solicitação de reembolso que está pendente ou já foi processada/concluída
        if ($this->reembolso && in_array($this->reembolso->status, ['solicitado', 'aprovado', 'processando', 'concluido'])) {
            return false;
        }

        // Calcula os dias passados desde a entrega
        $dataEntrega = Carbon::parse($this->entrega->data_entrega);
        $diasPassados = $dataEntrega->diffInDays(Carbon::now());

        // O prazo é de 7 dias (incluindo o dia da entrega)
        return $diasPassados >= 0 && $diasPassados < 7; // Menor que 7, pois 7 dias significaria que o 7º dia expirou. Ajuste conforme sua política.
    }

    /**
     * Retorna o número de dias restantes para solicitar reembolso.
     * Retorna 0 se o prazo expirou ou null se não for aplicável.
     *
     * @return int|null
     */
    public function getPrazoReembolsoRestanteAttribute(): ?int
    {
        // Verifica se o status permite e se há data de entrega válida
        if ($this->status === 'entregue' && $this->confirmado_pelo_cliente && $this->entrega && $this->entrega->data_entrega) {
            $dataEntrega = Carbon::parse($this->entrega->data_entrega);
            $diasPassados = $dataEntrega->diffInDays(Carbon::now());
            $diasRestantes = 7 - $diasPassados;
            return $diasRestantes > 0 ? $diasRestantes : 0;
        }
        return null;
    }


    // NOVO MÉTODO: Verificar se o cliente pode confirmar a entrega
    public function podeConfirmarEntrega(): bool
    {
        // Se o pedido já foi confirmado pelo cliente, ele não pode confirmar de novo.
        if ($this->confirmado_pelo_cliente) {
            return false;
        }

        // O cliente pode confirmar a entrega se o status estiver em uma dessas fases,
        // indicando que a entrega está em andamento ou já ocorreu fisicamente.
        return in_array($this->status, ['enviado', 'em_transito', 'saiu_para_entrega', 'entregue']);
    }

    /**
     * Verifica se o pedido pode ser avaliado pelo cliente.
     * Regra: O status do pedido deve ser 'entregue' e o cliente ainda não avaliou todos os itens.
     * Presume que a avaliação é feita por item do pedido.
     * Para simplificar, vamos verificar se *algum* item do pedido não foi avaliado.
     * Você pode precisar ajustar isso dependendo da sua estrutura de avaliação (por pedido ou por item).
     *
     * @return bool
     */



    public function todosItensAvaliados(): bool
    {
        // Se o pedido não tem itens, ou não está no status 'entregue' e confirmado,
        // não há como avaliar, então retorna falso.
        if ($this->itens->isEmpty() || $this->status !== 'entregue' || !$this->confirmado_pelo_cliente) {
            return false;
        }

        // Carrega as avaliações dos itens se ainda não carregou
        $this->loadMissing('itens.avaliacao');

        // Verifica se CADA item do pedido TEM uma avaliação associada.
        return $this->itens->every(fn($item) => $item->avaliacao !== null);
    }

    // MÉTODO ATUALIZADO: Verificar se o pedido pode ser avaliado
    public function podeAvaliar(): bool
    {
        // O pedido deve estar entregue e confirmado pelo cliente
        if ($this->status !== 'entregue' || !$this->confirmado_pelo_cliente) {
            return false;
        }

        // Carrega os itens e suas avaliações
        $this->loadMissing('itens.avaliacao');

        // Verifica se existe pelo menos um item que ainda não foi avaliado
        return $this->itens->some(fn($item) => $item->avaliacao === null);
    }


    public function reembolso(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Reembolso::class, 'id_pedido', 'id_pedido');
    }

    // NOVO MÉTODO: Verifica se o pedido já foi avaliado
    public function jaFoiAvaliado(): bool
    {
        // Isso assume que você tem um relacionamento 'avaliacoes' no modelo Pedido,
        // ou você pode verificar se todos os itens do pedido foram avaliados.
        // Se a avaliação for por pedido_item, você precisaria de uma lógica mais complexa
        // para verificar se TODOS os itens do pedido foram avaliados.
        // Para simplificar, vamos assumir que 'avaliacoes' aqui se refere a uma avaliação geral do pedido.
        // OU, se você avalia por item do pedido, teria que iterar os itens:
        // return $this->itens->every(fn($item) => $item->avaliacoes->isNotEmpty());
        return $this->avaliacoes->isNotEmpty(); // Se o relacionamento for Pedido hasOne Avaliacao ou hasMany
    }

    // Adicione o relacionamento se você for avaliar o pedido como um todo
    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'id_pedido_item', 'id_pedido'); // Isso pode precisar de ajuste dependendo de como avaliacoes está ligada a Pedido
        // Se Avaliacao está ligada a PedidoItem, essa relação aqui pode não fazer sentido
        // Se Avaliacao é um relacionamento 1:1 com PedidoItem, você vai precisar verificar nos itens do pedido.
    }

    /**
     * Verifica se o status do pedido pode ser alterado pelo administrador.
     * Regra: O administrador NÃO pode mudar o status se o pedido estiver 'entregue',
     * 'cancelado' ou 'reembolso_solicitado' (considerados estados finais para alterações diretas).
     *
     * @return bool
     */
    public function podeSerAlteradoPeloAdministrador(): bool
    {
        return !in_array($this->status, ['entregue', 'cancelado', 'reembolso_solicitado', 'reembolsado']);
    }
}