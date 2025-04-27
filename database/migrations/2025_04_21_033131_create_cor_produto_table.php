<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabela de CorProduto
        Schema::create('cor_produto', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_produto'); // Coluna que vai referenciar a tabela produtos
            $table->unsignedBigInteger('id_cor'); // Exemplo de outra coluna de chave estrangeira
            $table->timestamps();
    
            // Definindo a chave estrangeira para a tabela produtos
            $table->foreign('id_produto')->references('id_produto')->on('produtos')->onDelete('cascade');
        });
    }
    
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cor_produto');
    }
};
