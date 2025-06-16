<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Certifique-se que BelongsTo está importado

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
        // 'cor_id' é a chave estrangeira em produto_variacoes
        // 'id_cor' é a chave primária na tabela cores
        return $this->belongsTo(Cor::class, 'cor_id', 'id_cor');
    }

    public function tamanho(): BelongsTo
    {
        // 'tamanho_id' é a chave estrangeira em produto_variacoes
        // 'id_tamanho' é a chave primária na tabela tamanhos
        return $this->belongsTo(Tamanho::class, 'tamanho_id', 'id_tamanho');
    }
}
