<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cupom';

    protected $fillable = [
        'codigo',
        'valor',
        'tipo',
        'validade',
        'usos_maximos',
        'usos_atual',
        'ativo'
    ];

    protected $casts = [
        'validade' => 'datetime',
        'ativo' => 'boolean'
    ];

    // Relacionamento com pedidos (um cupom pode estar em muitos pedidos)
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_cupom');
    }

    // Método para verificar se o cupom é válido
    public function estaValido()
    {
        return $this->ativo &&
            now()->lt($this->validade) &&
            ($this->usos_maximos === null || $this->usos_atual < $this->usos_maximos);
    }

    // Método para aplicar o desconto
    public function aplicarDesconto($valorTotal)
    {
        if (!$this->estaValido()) {
            return $valorTotal;
        }

        return $this->tipo === 'percentual'
            ? $valorTotal * (1 - ($this->valor / 100))
            : max(0, $valorTotal - $this->valor);
    }

    // Método para registrar uso do cupom
    public function registrarUso()
    {
        if ($this->usos_maximos !== null) {
            $this->increment('usos_atual');

            if ($this->usos_atual >= $this->usos_maximos) {
                $this->update(['ativo' => false]);
            }
        }
    }
}