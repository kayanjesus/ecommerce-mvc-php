<?php

// Tamanho.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamanho extends Model
{
    protected $table = 'tamanhos';
    protected $primaryKey = 'id'; // Isso já é o padrão, mas pode ser explícito
    protected $fillable = ['nome'];
    public $timestamps = true;

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'produto_tamanho', 'id_tamanho', 'id_produto');
    }
}

