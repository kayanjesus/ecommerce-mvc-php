<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt; // Manter este import, ele será usado pelo Attribute Cast
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'cpf_hash',
        'data_nasc',
        'password',
        'access_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // 'cpf', // Se você usar o Attribute Casting para 'get', você pode remover 'cpf' de 'hidden'
        // se quiser que ele apareça em toArray() ou toJson() descriptografado.
        // Para a API do PagSeguro, não faz diferença se está hidden ou não,
        // contanto que você o acesse como $usuario->cpf.
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'data_nasc' => 'date',
    ];


    // MANTENHA SOMENTE ESTE mutator de CPF
    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Crypt::decryptString($value) : null,
            set: fn($value) => $value ? \Crypt::encryptString(preg_replace('/[^0-9]/', '', $value)) : null,
        );
    }

    // Se você usa o campo 'telefone' também, adicione um mutator similar
    protected function telefone(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value, // Se não criptografado, apenas retorne
            set: fn($value) => preg_replace('/[^0-9]/', '', $value), // Limpa o telefone antes de salvar
        );
    }
}