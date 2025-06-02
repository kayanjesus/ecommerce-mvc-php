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
        // Tabela de Cupons de Desconto
        Schema::create('cupons', function (Blueprint $table) {
            $table->id('id_cupom');
            $table->string('codigo')->unique();
            $table->decimal('valor', 10, 2);
            $table->enum('tipo', ['percentual', 'fixo']);
            $table->timestamp('validade');
            $table->integer('usos_maximos')->nullable(); // Número máximo de usos (null para ilimitado)
            $table->integer('usos_atual')->default(0); // Contador de usos
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};
