<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagamentoCheckout extends Model
{
    protected $table = 'pagamentos'; // Mantemos a tabela original
    protected $primaryKey = 'id_pagamento';
    

    protected $fillable = [
        'id_pedido',          // Confirmar que está incluído
        'metodo_pagamento',
        'valor_pago',
        'valor_original',
        'desconto',
        'valor_frete',
        'parcelas',
        'codigo_transacao',   // Adicione se existir na tabela
        'status',
        'data_pagamento',
        'detalhes',            // Adicione se for um campo JSON
        'estoque_atualizado_em',
        'estoque_processado'
    ];

    protected $casts = [
        'data_pagamento' => 'datetime',
        'valor_pago' => 'decimal:2',
        'valor_original' => 'decimal:2',
        'desconto' => 'decimal:2',
        'valor_frete' => 'decimal:2',
        'detalhes' => 'array' // Para armazenar JSON
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
