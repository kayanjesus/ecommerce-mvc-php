<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// app/Models/Categoria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Produto extends Model
{
    // Definir a tabela e chave primária
    protected $table = 'produtos';
    protected $primaryKey = 'id_produto';

    // Indicar que a chave primária não é auto-incrementada, se necessário
    // public $incrementing = false;

    // Definir os campos que podem ser preenchidos
    protected $fillable = ['nome', 'descricao', 'preco', 'imagem'];

    // Relacionamento: muitos para muitos com Categoria
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_produto', 'id_produto', 'id_categoria');
    }

    // Caso queira que o modelo use as colunas created_at e updated_at
    public $timestamps = true;

    // Se precisar de alguma customização para os campos de data:
    // protected $dates = ['created_at', 'updated_at'];
}



    // public function categorias()
    // {
    //     return $this->belongsToMany(Categoria::class, 'categoria_produto', 'id_produto', 'id_categoria');
    // }

