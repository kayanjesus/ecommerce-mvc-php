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
        // Tabela de Produtos
        Schema::create('produtos', function (Blueprint $table) {
            $table->id('id_produto');
            $table->string('nome_produto');
            $table->string('tipo');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->string('marca');
            $table->decimal('preco', 10, 2);
            $table->string('tecido');
            $table->string('genero');

            $table->timestamps();

            // $table->foreign('id_categoria')->references('id_categoria')->on('categorias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
