<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reembolso extends Model
{
    protected $table = 'reembolsos'; // Certifique-se de que o nome da tabela está correto
    protected $primaryKey = 'id_reembolso';
    protected $fillable = [
        'id_pedido',
        'valor_reembolso',
        'motivo',
        'status', // Ex: 'solicitado', 'aprovado', 'negado', 'processando', 'concluido'
        'data_solicitacao',
        'data_processamento',
        'data_conclusao',
    ];

    protected $casts = [
        'data_solicitacao' => 'datetime',
        'data_processamento' => 'datetime',
        'data_conclusao' => 'datetime',
    ];

    /**
     * Relacionamento com o pedido associado.
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}