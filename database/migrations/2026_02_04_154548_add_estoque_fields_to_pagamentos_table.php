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
        Schema::table('pagamentos', function (Blueprint $table) {
            // Adiciona campos de controle de estoque
            $table->timestamp('estoque_atualizado_em')->nullable()->after('detalhes');
            $table->boolean('estoque_processado')->default(false)->after('estoque_atualizado_em');

            // Verifica se existe a coluna 'parcelas', se não, adiciona
            if (!Schema::hasColumn('pagamentos', 'parcelas')) {
                $table->integer('parcelas')->nullable()->after('desconto');
            }

            // Atualiza o ENUM do status se necessário
            // $table->enum('status', ['pendente', 'pago', 'recusado', 'cancelado', 'expirado', 'reembolsado', 'aguardando_captura_cartao', 'boleto_gerado'])->default('pendente')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn(['estoque_atualizado_em', 'estoque_processado']);
            // Se adicionou parcelas, remova também
            if (Schema::hasColumn('pagamentos', 'parcelas')) {
                $table->dropColumn('parcelas');
            }
        });
    }
};