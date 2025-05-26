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
        // Tabela de Produtos
        Schema::create('produtos', function (Blueprint $table) {
            $table->id('id_produto');
            $table->string('nome_produto');
            $table->string('tipo'); // Agora será usado para categorias
            $table->string('slug')->unique();
            $table->text('variacao')->nullable(); // Antiga 'descricao'
            $table->string('marca');
            $table->decimal('preco', 10, 2);
            $table->string('tecido');
            $table->enum('estacao', ['Verão', 'Inverno']); // Novo campo
            $table->string('modelo'); // Antigo 'genero'
            $table->timestamps();
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
