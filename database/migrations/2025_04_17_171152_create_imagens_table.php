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
        // Tabela de Imagens dos Produtos
        Schema::create('imagem_produto', function (Blueprint $table) {
            $table->id('id_imagem');
            $table->unsignedBigInteger('id_produto');
            $table->string('caminho_imagem');
            $table->timestamps();

            $table->foreign('id_produto')->references('id_produto')->on('produtos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagens');
    }
};
