<?php

// Cor.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cor extends Model
{
    protected $table = 'cores';
    protected $fillable = ['nome'];
    public $timestamps = true;

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'cor_produto', 'id_cor', 'id_produto');
    }
}

