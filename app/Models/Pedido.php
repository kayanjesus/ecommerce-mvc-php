<?php

namespace App\Models;
use App\Models\PagamentoCheckout;

use Illuminate\Database\Eloquent\Model;

// Model Pedido
// Model Pedido.php
class Pedido extends Model
{
    protected $primaryKey = 'id_pedido';
    protected $fillable = [
        'id_usuario',
        'total',
        'status',
        'endereco_entrega',
        'observacoes',
        'data_pedido'
    ];

    protected $casts = [
        'endereco_entrega' => 'array',
        'data_pedido' => 'datetime'
    ];

    public function calcularFrete($cep)
    {
        $primeiroDigito = substr(preg_replace('/[^0-9]/', '', $cep), 0, 1);
        $estado = $this->endereco_entrega['estado'] ?? null;

        if ($estado === 'SP' && $this->total >= 250) {
            return 0;
        } elseif ($this->total >= 399) {
            return 0;
        }

        return match ($primeiroDigito) {
            '0', '1', '2', '3' => 25.00,
            default => 35.00
        };
    }

    protected $dates = [
        'data_pedido',
        'created_at',
        'updated_at'
    ];


    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class, 'id_pedido');
    }

    public function historicoStatus()
    {
        return $this->hasMany(PedidoStatus::class, 'id_pedido', 'id_pedido');
    }

    public function pagamentoCheckout()
    {
        return $this->hasOne(PagamentoCheckout::class, 'id_pedido');
    }
    public function cupom()
    {
        return $this->belongsTo(Cupom::class, 'id_cupom');
    }

    public function aplicarCupom($codigoCupom)
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



    // app/Models/Pedido.php
    public function qualificarFreteGratis()
    {
        $estado = $this->endereco_entrega['estado'] ?? null;

        if ($estado === 'SP' && $this->total >= 250) {
            return 'Frete grátis para São Paulo (acima de R$ 250)';
        } elseif ($this->total >= 399) {
            return 'Frete grátis nacional (acima de R$ 399)';
        }

        return null;
    }
}