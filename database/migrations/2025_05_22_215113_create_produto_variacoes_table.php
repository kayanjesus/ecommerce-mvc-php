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

        Schema::create('produto_variacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('cor_id');
            $table->unsignedBigInteger('tamanho_id');
            $table->integer('estoque')->default(0);
            $table->decimal('preco', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('produto_id')->references('id_produto')->on('produtos')->onDelete('cascade');
            $table->foreign('cor_id')->references('id_cor')->on('cores')->onDelete('cascade');
            $table->foreign('tamanho_id')->references('id_tamanho')->on('tamanhos')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_variacoes');
    }
};