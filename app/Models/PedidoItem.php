<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    protected $table = 'pedido_itens';
    protected $primaryKey = 'id_item';

    protected $fillable = [
        'id_pedido',
        'id_produto',  // Este deve ser apenas o ID numérico do produto
        'quantidade',
        'preco_unitario',
        'cor',       // Campo para armazenar a cor separadamente
        'tamanho'    // Campo para armazenar o tamanho separadamente
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}