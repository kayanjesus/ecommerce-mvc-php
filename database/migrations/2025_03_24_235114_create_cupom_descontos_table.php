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
        Schema::create('cupon_descontos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('localizador');
            $table->decimal('desconto', 6, 2);
            $table->enum('modo_desconto', ['porcentagem', 'fixo']);
            $table->decimal('limite', 6, 2)->nullable();
            $table->enum('modo_limite', ['valor', 'qtd'])->nullable();
            $table->dateTime('dthr_validade');
            $table->enum('ativo', ['S', 'N'])->default('S');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupom_descontos');
    }
};
