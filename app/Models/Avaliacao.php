<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avaliacao extends Model
{
    protected $table = 'avaliacoes'; // Certifique-se de que o nome da tabela está correto
    protected $primaryKey = 'id_avaliacao'; // Ou o nome da sua PK
    protected $fillable = [
        'id_pedido_item',
        'id_usuario',
        'id_produto', // Pode ser útil para acesso direto ao produto
        'nota',
        'comentario',
        // Outros campos relevantes para avaliação, como data da avaliação
    ];

    /**
     * Relacionamento com o item do pedido avaliado.
     */
    public function pedidoItem()
    {
        return $this->belongsTo(PedidoItem::class, 'id_pedido_item', 'id_item');
    }

    /**
     * Relacionamento com o usuário que fez a avaliação.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Relacionamento com o produto avaliado.
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto', 'id_produto'); // Ajuste 'id_produto' se for diferente no modelo Produto
    }
}