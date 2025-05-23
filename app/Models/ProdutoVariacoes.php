<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function cor()
    {
        return $this->belongsTo(Cor::class, 'cor_id');
    }

    public function tamanho()
    {
        return $this->belongsTo(Tamanho::class, 'tamanho_id', 'id');
    }

}
