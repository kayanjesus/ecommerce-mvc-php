<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use App\Rules\CpfCnpj; // Importe a regra CpfCnpj

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'data_nasc' => ['required', 'date', 'before:today', 'date_format:Y-m-d'],
            'cpf' => [
                'required',
                'string',
                'max:14',
                new CpfCnpj,
                'unique:users,cpf_hash',
            ],
            // 'telefone' => ['required', 'string', 'min:10', 'max:11'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $this->input('cpf')),
            // 'telefone' => preg_replace('/[^0-9]/', '', $this->telefone), // Limpa o telefone
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'cpf.unique' => 'Este CPF/CNPJ já está cadastrado em outra conta.',
            'data_nasc.before' => 'A data de nascimento deve ser uma data anterior à de hoje.',
            'data_nasc.date_format' => 'O formato da data de nascimento é inválido. Use AAAA-MM-DD.',
            'telefone.required' => 'O campo telefone é obrigatório.',
            // 'telefone.string' => 'O telefone deve ser uma string.',
            // 'telefone.min' => 'O telefone deve ter no mínimo :min dígitos.',
            // 'telefone.max' => 'O telefone deve ter no máximo :max dígitos.',
            // Adicione outras mensagens personalizadas conforme necessário
        ];
    }
}