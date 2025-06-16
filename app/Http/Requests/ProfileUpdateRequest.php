<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\CpfCnpj; // Certifique-se de criar esta regra

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // Obtém o ID do usuário autenticado para ignorá-lo na regra unique do email
        $userId = $this->user()->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($userId),
            ],
            // Validação do CPF
            'cpf' => [
                'required',
                'string',
                'max:14', // CPF (11) ou CNPJ (14)
                new CpfCnpj, // Regra para validar formato e dígitos
                // Validação de unicidade para o cpf_hash (ignorando o próprio usuário)
                Rule::unique(User::class, 'cpf_hash')->ignore($userId),
            ],
            // Validação da Data de Nascimento
            'data_nasc' => ['required', 'date', 'before:today', 'date_format:Y-m-d'],
            // Validação do Telefone
            'telefone' => ['nullable', 'string', 'max:15'], // O telefone pode ter 10-11 dígitos + máscara, então 15 é seguro para o formato limpo
        ];
    }

    /**
     * Prepare the data for validation.
     * Limpa os campos antes da validação para garantir que o formato é consistente.
     * Isso é útil para campos como CPF e telefone que podem vir com máscaras.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // Remove caracteres não numéricos do CPF antes da validação
            'cpf' => preg_replace('/[^0-9]/', '', $this->input('cpf')),
            // Remove caracteres não numéricos do telefone antes da validação
            'telefone' => preg_replace('/[^0-9]/', '', $this->input('telefone')),
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cpf.unique' => 'Este CPF/CNPJ já está cadastrado em outra conta.',
            'data_nasc.before' => 'A data de nascimento deve ser uma data anterior à de hoje.',
            'data_nasc.date_format' => 'O formato da data de nascimento é inválido. Use AAAA-MM-DD.',
            // Adicione outras mensagens personalizadas conforme necessário
        ];
    }
}