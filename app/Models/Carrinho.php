<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    protected $table = 'carrinhos';
    protected $primaryKey = 'id_carrinho';
    
    protected $fillable = [
        'id_usuario',
        'conteudo'
    ];
    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}