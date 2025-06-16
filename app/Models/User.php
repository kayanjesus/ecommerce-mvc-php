<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute; // Certifique-se que esta linha está aqui
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
        'cpf_hash', // Este campo será um hash não reversível
        'data_nasc',
        'telefone',
        'password',
        'access_level',
        'telefone', // Certifique-se de que 'telefone' está aqui se você o usa
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'cpf', // Você pode deixar 'cpf' hidden se não quiser que ele apareça em arrays/json por padrão
        'cpf_hash', // Pode deixar 'cpf_hash' hidden também
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
        'data_nasc' => 'date', // 'date' para que o Laravel trate como objeto Carbon
    ];

    /**
     * Get the user's CPF (decrypted).
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            // O set automaticamente criptografa e limpa o CPF antes de salvar
            set: fn($value) => $value ? Crypt::encryptString(preg_replace('/[^0-9]/', '', $value)) : null,
        );
    }

    protected function cpfHash(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? hash('sha256', preg_replace('/[^0-9]/', '', $value)) : null,
        );
    }

    /**
     * Get the user's phone number (cleaned).
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    // public function setTelefoneAttribute($value)
    // {
    //     $this->attributes['telefone'] = preg_replace('/[^0-9]/', '', $value);
    // }

    // public function getTelefoneAttribute($value)
    // {
    //     $cleaned = preg_replace('/[^0-9]/', '', $value);
    //     if (strlen($cleaned) === 11) { // Ex: 11999998888 -> (11) 99999-8888
    //         return preg_replace('/^(\d{2})(\d{5})(\d{4})$/', '($1) $2-$3', $cleaned);
    //     } elseif (strlen($cleaned) === 10) { // Ex: 1199998888 -> (11) 9999-8888
    //         return preg_replace('/^(\d{2})(\d{4})(\d{4})$/', '($1) $2-$3', $cleaned);
    //     }
    //     return $value; // Retorna o valor original se não for 10 ou 11 dígitos.
    // }

    /**
     * Check if the user has admin access level.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->access_level === 'admin';
    }
}