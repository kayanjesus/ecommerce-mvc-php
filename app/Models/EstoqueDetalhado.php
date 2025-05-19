<?php

// EstoqueDetalhado.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueDetalhado extends Model
{
    protected $table = 'estoque_detalhado';
    protected $primaryKey = 'id_estoque';
    protected $fillable = ['id_produto', 'tamanho', 'cor', 'quantidade'];
    public $timestamps = true;

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}

