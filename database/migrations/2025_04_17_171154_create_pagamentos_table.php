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
        // Tabela de Pagamentos
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id('id_pagamento');
            $table->unsignedBigInteger('id_pedido');
            $table->enum('metodo_pagamento', ['cartao', 'boleto', 'pix']);
            $table->decimal('valor_pago', 10, 2);
            $table->timestamp('data_pagamento');
            $table->timestamps();

            $table->foreign('id_pedido')->references('id_pedido')->on('pedidos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
