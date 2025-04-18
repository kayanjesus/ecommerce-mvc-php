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
        // Tabela de Recibos
        Schema::create('recibos', function (Blueprint $table) {
            $table->id('id_recibo');
            $table->unsignedBigInteger('id_pagamento');
            $table->string('comprovante_url');
            $table->timestamps();

            $table->foreign('id_pagamento')->references('id_pagamento')->on('pagamentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibos');
    }
};
