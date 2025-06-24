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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamp('data_pedido')->useCurrent();
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pendente', 'pago', 'processando', 'enviado', 'em_transito', 'saiu_para_entrega', 'entregue', 'cancelado', 'reembolso_solicitado', 'reembolsado']);
            // A ordem de declaração agora define a ordem na tabela
            $table->string('status_reembolso')->nullable()->comment('solicitado, aprovado, negado, concluido');
            $table->boolean('confirmado_pelo_cliente')->default(false);
            $table->unsignedBigInteger('id_cupom')->nullable();
            $table->json('endereco_entrega');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_cupom')->references('id_cupom')->on('cupons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};