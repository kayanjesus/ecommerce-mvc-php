<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;

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
        'cpf_hash', // Hash não reversível, usado para checar unicidade do CPF
        'data_nasc',
        'telefone',
        'password',
        'access_level',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'cpf',
        'cpf_hash',
        'google_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'telefone_verified_at' => 'datetime',
        'password' => 'hashed',
        'data_nasc' => 'date',
    ];

    /**
     * CPF é armazenado criptografado (Crypt::encryptString), que NÃO é
     * determinístico — o mesmo CPF gera um valor cifrado diferente a cada
     * vez. Por isso, validação de "CPF único" nunca deve comparar contra
     * esta coluna. Use sempre `cpf_hash` (SHA-256, determinístico) para
     * checar duplicidade — ver regra de validação em RegisteredUserController.
     */
    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString(preg_replace('/[^0-9]/', '', $value)) : null,
        );
    }

    protected function cpfHash(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? hash('sha256', preg_replace('/[^0-9]/', '', $value)) : null,
        );
    }

    public function isAdmin(): bool
    {
        return $this->access_level === 'admin';
    }
}
