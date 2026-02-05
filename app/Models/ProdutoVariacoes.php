<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdutoVariacoes extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto_id',
        'cor_id',
        'tamanho_id',
        'estoque',
        'preco',
    ];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function cor(): BelongsTo
    {
        return $this->belongsTo(Cor::class, 'cor_id', 'id_cor');
    }

    public function tamanho(): BelongsTo
    {
        return $this->belongsTo(Tamanho::class, 'tamanho_id', 'id_tamanho');
    }

    // NOVOS MÉTODOS PARA GERENCIAR ESTOQUE:
    
    /**
     * Diminui o estoque da variação
     */
    public function diminuirEstoque(int $quantidade): bool
    {
        if ($this->estoque >= $quantidade) {
            $this->estoque -= $quantidade;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Aumenta o estoque da variação
     */
    public function aumentarEstoque(int $quantidade): bool
    {
        $this->estoque += $quantidade;
        $this->save();
        return true;
    }
}