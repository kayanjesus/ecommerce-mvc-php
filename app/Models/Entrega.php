<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Entrega extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_entrega';

    protected $fillable = [
        'id_pedido',
        'metodo_entrega',
        'valor_entrega',
        'data_envio',
        'data_entrega',
    ];

    protected $casts = [
        'data_envio' => 'datetime',
        'data_entrega' => 'datetime',
    ];

    /**
     * Uma entrega pertence a um pedido.
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    /**
     * Uma entrega pode ter um rastreio.
     */
    public function rastreio(): HasOne // <-- NOVO RELACIONAMENTO
    {
        return $this->hasOne(Rastreio::class, 'id_entrega', 'id_entrega');
    }
}
