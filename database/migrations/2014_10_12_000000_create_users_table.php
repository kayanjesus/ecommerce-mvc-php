<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->text('cpf')->nullable(); // TEXT para criptografia
            $table->string('cpf_hash', 64)->nullable();
            $table->date('data_nasc')->nullable(); // DATE para data de nascimento
            $table->string('telefone')->nullable(); // DESCOMENTE ESTA LINHA
            $table->string('password');
            $table->string('access_level')->default('user');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('telefone_verified_at')->nullable(); // DESCOMENTE ESTA LINHA
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};