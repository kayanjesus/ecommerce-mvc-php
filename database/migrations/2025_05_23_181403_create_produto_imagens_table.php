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
        Schema::create('produto_imagens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_id');
            $table->string('caminho'); // Armazena o caminho da imagem (ex: 'produtos/imagem1.jpg')
            $table->boolean('principal')->default(false); // Indica se é a imagem principal
            $table->timestamps();

            $table->foreign('produto_id')
                ->references('id_produto')
                ->on('produtos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_imagens');
    }
};
