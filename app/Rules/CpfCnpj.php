<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = preg_replace('/[^0-9]/', '', $value); // Limpa o valor (já feito no prepareForValidation, mas é bom ter aqui tb)

        if (strlen($value) === 11) {
            // Validar CPF
            if (!$this->validateCpf($value)) {
                $fail('O CPF informado é inválido.');
            }
        } elseif (strlen($value) === 14) {
            // Validar CNPJ
            if (!$this->validateCnpj($value)) {
                $fail('O CNPJ informado é inválido.');
            }
        } else {
            $fail('O CPF/CNPJ deve ter 11 ou 14 dígitos.');
        }
    }

    /**
     * Validate CPF.
     *
     * @param string $cpf
     * @return bool
     */
    protected function validateCpf(string $cpf): bool
    {
        // Elimina CPFs invalidos conhecidos
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para verificar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate CNPJ.
     *
     * @param string $cnpj
     * @return bool
     */
    protected function validateCnpj(string $cnpj): bool
    {
        // Elimina CNPJs invalidos conhecidos
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Valida tamanho
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Valida primeiro digito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        // Valida segundo digito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}