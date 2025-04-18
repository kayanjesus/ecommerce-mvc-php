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
        // Tabela de Atendimentos
        Schema::create('atendimentos', function (Blueprint $table) {
            $table->id('id_atendimento');
            $table->unsignedBigInteger('id_usuario');
            $table->text('mensagem');
            $table->timestamp('data_envio');
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atendimentos');
    }
};
