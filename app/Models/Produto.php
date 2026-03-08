<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Avaliacao;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';
    protected $primaryKey = 'id_produto';

    protected $fillable = [
        'nome_produto',
        'tipo', // Agora para categorias
        'slug',
        'variacao', // Antiga descrição
        'marca',
        'preco',
        'tecido',
        'estacao', // Novo campo
        'modelo' // Antigo genero
    ];

    public $timestamps = true;

    // Muitos para muitos: Produto-Categoria

    // App/Models/Produto.php
    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'id_produto', 'id_produto');
    }
    public function getMediaAvaliacaoAttribute(): float
    {
        // Usa o relacionamento "avaliacoes" para calcular a média APENAS para este produto.
        // Se não houver avaliações, retorna 0.
        return round($this->avaliacoes()->avg('nota') ?? 0, 1);
    }

    /**
     * Retorna o número total de avaliações do produto.
     * Accessor: $produto->total_avaliacoes
     * @return int
     */
    public function getTotalAvaliacoesAttribute(): int
    {
        // Usa o relacionamento "avaliacoes" para contar APENAS as avaliações deste produto.
        return $this->avaliacoes()->count();
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_produto', 'id_produto', 'id_categoria');
    }

    // Um para muitos: Produto tem vários itens de estoque
    public function estoque()
    {
        return $this->hasMany(EstoqueDetalhado::class, 'id_produto');
    }

    // Muitos para muitos: Produto-Cor
    public function cores()
    {
        return $this->belongsToMany(Cor::class, 'cor_produto', 'id_produto', 'id_cor');
    }

    // Muitos para muitos: Produto-Tamanho
    public function tamanhos()
    {
        return $this->belongsToMany(Tamanho::class, 'produto_tamanho', 'id_produto', 'id_tamanho');
    }

    public function variacoes()
    {
        return $this->hasMany(ProdutoVariacoes::class, 'produto_id', 'id_produto'); // tava ProdutoVariacao
    }


    public function avaliacao()
    {
        return $this->hasMany(Avaliacao::class, 'id_produto', 'id_produto');
    }

    // No model Produto.php
    public function imagens()
    {
        return $this->hasMany(ProdutoImagem::class, 'produto_id', 'id_produto');
    }


}






