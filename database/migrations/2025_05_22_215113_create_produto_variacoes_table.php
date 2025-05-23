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
            $table->foreignId('cor_id')->constrained('cores')->onDelete('cascade');
            $table->foreignId('tamanho_id')->constrained('tamanhos')->onDelete('cascade');
            $table->integer('estoque')->default(0);
            $table->decimal('preco', 10, 2)->nullable();
            $table->timestamps();
            $table->foreign('produto_id')->references('id_produto')->on('produtos')->onDelete('cascade');
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
