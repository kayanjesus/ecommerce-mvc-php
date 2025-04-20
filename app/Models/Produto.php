<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// app/Models/Categoria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';
    protected $primaryKey = 'id_produto';

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_produto', 'id_produto', 'id_categoria');
    }
}


    // public function categorias()
    // {
    //     return $this->belongsToMany(Categoria::class, 'categoria_produto', 'id_produto', 'id_categoria');
    // }

