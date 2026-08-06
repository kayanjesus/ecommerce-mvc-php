<?php

namespace Database\Seeders;

use App\Models\Tamanho;
use Illuminate\Database\Seeder;

class TamanhosSeeder extends Seeder
{
    public function run(): void
    {
        $tamanhos = [
            // Bebê
            ['nome' => 'RN', 'tipo' => 'bebe'],
            ['nome' => 'P', 'tipo' => 'bebe'],
            ['nome' => 'M', 'tipo' => 'bebe'],
            ['nome' => 'G', 'tipo' => 'bebe'],
            ['nome' => '1m', 'tipo' => 'bebe'],
            ['nome' => '3m', 'tipo' => 'bebe'],
            ['nome' => '6m', 'tipo' => 'bebe'],
            ['nome' => '9m', 'tipo' => 'bebe'],
            ['nome' => '12m', 'tipo' => 'bebe'],
            ['nome' => '18m', 'tipo' => 'bebe'],
            ['nome' => '24m', 'tipo' => 'bebe'],

            // Infantil
            ['nome' => 'P', 'tipo' => 'infantil'],
            ['nome' => 'M', 'tipo' => 'infantil'],
            ['nome' => 'G', 'tipo' => 'infantil'],
            ['nome' => '1', 'tipo' => 'infantil'],
            ['nome' => '2', 'tipo' => 'infantil'],
            ['nome' => '3', 'tipo' => 'infantil'],
            ['nome' => '4', 'tipo' => 'infantil'],
            ['nome' => '6', 'tipo' => 'infantil'],
            ['nome' => '8', 'tipo' => 'infantil'],
            ['nome' => '10', 'tipo' => 'infantil'],
            ['nome' => '12', 'tipo' => 'infantil'],
            ['nome' => '14', 'tipo' => 'infantil'],
            ['nome' => '16', 'tipo' => 'infantil'],
        ];

        foreach ($tamanhos as $tamanho) {
            // combinação nome+tipo evita duplicar (ex: '2' infantil != '2' outro tipo)
            Tamanho::firstOrCreate(
                ['nome' => $tamanho['nome'], 'tipo' => $tamanho['tipo']]
            );
        }
    }
}
