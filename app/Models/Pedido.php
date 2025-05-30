<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Pedido
class Pedido extends Model
{
    protected $primaryKey = 'id_pedido';

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class, 'id_pedido');
    }

    public function pagamento()
    {
        return $this->hasOne(Pagamento::class, 'id_pedido');
    }
}

// Model PedidoItem
class PedidoItem extends Model
{
    protected $primaryKey = 'id_item';

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}

// Model Pagamento
class Pagamento extends Model
{
    protected $primaryKey = 'id_pagamento';

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}