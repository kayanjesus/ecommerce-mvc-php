<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar BelongsTo
use Illuminate\Database\Eloquent\Relations\HasOne;

class PedidoItem extends Model
{
    protected $table = 'pedido_itens';
    protected $primaryKey = 'id_item';

    protected $fillable = [
        'id_pedido',
        'id_produto',
        'quantidade',
        'preco_unitario',
        'id_cor', // Agora seria id_cor
        'id_tamanho' // Agora seria id_tamanho
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }

    // NOVO: Adicione o relacionamento para Cor
    public function cor(): BelongsTo
    {
        return $this->belongsTo(Cor::class, 'id_cor', 'id_cor'); // Assumindo que o PK de Cor é 'id_cor'
    }

    // NOVO: Adicione o relacionamento para Tamanho
    public function tamanho(): BelongsTo
    {
        return $this->belongsTo(Tamanho::class, 'id_tamanho', 'id_tamanho'); // Assumindo que o PK de Tamanho é 'id_tamanho'
    }

    public function avaliacao(): HasOne
    {
        return $this->hasOne(Avaliacao::class, 'id_pedido_item', 'id_item');
    }
}