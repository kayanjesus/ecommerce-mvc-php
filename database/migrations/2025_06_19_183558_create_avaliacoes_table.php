<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id('id_avaliacao');
            $table->foreignId('id_pedido_item')->constrained('pedido_itens', 'id_item')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            // A correção está aqui: agora especificamos 'id_produto' como a PK na tabela 'produtos'
            $table->foreignId('id_produto')->constrained('produtos', 'id_produto')->onDelete('cascade');
            $table->tinyInteger('nota')->comment('Nota de 1 a 5 estrelas');
            $table->text('comentario')->nullable();
            $table->timestamps();

            $table->unique(['id_pedido_item', 'id_usuario'], 'unique_avaliacao_item_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
