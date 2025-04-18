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
        // Tabela de Rastreio de Entregas
        Schema::create('rastreios', function (Blueprint $table) {
            $table->id('id_rastreio');
            $table->unsignedBigInteger('id_entrega');
            $table->string('codigo_rastreio');
            $table->timestamps();

            $table->foreign('id_entrega')->references('id_entrega')->on('entregas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rastreios');
    }
};
