<?php

// Categoria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    protected $fillable = ['nome_categoria'];
    public $timestamps = true;

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'categoria_produto', 'id_categoria', 'id_produto');
    }
}

