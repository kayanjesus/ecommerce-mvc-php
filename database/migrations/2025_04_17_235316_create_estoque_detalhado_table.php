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
        Schema::create('estoque_detalhado', function (Blueprint $table) {
            $table->id('id_estoque'); // Chave primária auto-incrementável
            $table->unsignedBigInteger('id_produto'); // Chave estrangeira para produto
            $table->enum('tamanho', ['P', 'M', 'G', 'GG']); // Tamanho do produto
            $table->string('cor', 50); // Cor do produto
            $table->integer('quantidade'); // Quantidade disponível
            $table->timestamps();
        
            $table->foreign('id_produto')->references('id_produto')->on('produtos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque_detalhado');
    }
};
