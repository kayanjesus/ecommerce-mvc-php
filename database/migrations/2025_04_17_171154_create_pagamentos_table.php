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

        // Tabela de Pagamentos
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id('id_pagamento');
            $table->unsignedBigInteger('id_pedido');
            $table->enum('metodo_pagamento', ['cartao', 'boleto', 'pix']);
            $table->decimal('valor_pago', 10, 2);
            $table->decimal('valor_original', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('valor_frete', 10, 2)->default(0);
            $table->integer('parcelas')->default(1);
            $table->string('codigo_transacao')->nullable(); // Para armazenar código de transação
            $table->enum('status', ['pendente', 'pago', 'cancelado', 'estornado', 'reembolsado'])->default('pendente');
            $table->timestamp('data_pagamento')->nullable(); // Alterado para nullable
            $table->json('detalhes')->nullable(); // Para armazenar dados adicionais
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
