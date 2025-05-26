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
        // Tabela de Endereços
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id('id_endereco');
            $table->string('cep');
            $table->string('rua');
            $table->string('bairro');
            $table->string('numero');   
            $table->string('cidade');
            $table->string('estado');
            $table->string('complemento')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
