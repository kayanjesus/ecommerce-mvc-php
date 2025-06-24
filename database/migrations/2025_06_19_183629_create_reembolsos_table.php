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
        Schema::create('reembolsos', function (Blueprint $table) {
            $table->id('id_reembolso');
            $table->foreignId('id_pedido')->constrained('pedidos', 'id_pedido')->onDelete('cascade');
            $table->decimal('valor_reembolso', 10, 2);
            $table->string('motivo')->nullable();
            $table->string('status')->default('solicitado')->comment('solicitado, aprovado, negado, processando, concluido');
            $table->timestamp('data_solicitacao')->useCurrent();
            $table->timestamp('data_processamento')->nullable();
            $table->timestamp('data_conclusao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reembolsos');
    }
};
