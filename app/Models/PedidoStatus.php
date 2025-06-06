<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoStatus extends Model
{
    use HasFactory;

    protected $table = 'pedido_status'; // Nome da tabela
    protected $primaryKey = 'id_status'; // Chave primária

    protected $fillable = [
        'id_pedido',
        'status',
        'observacao',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }
}