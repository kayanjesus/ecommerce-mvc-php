<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Bebê',
            'Bebê Menina',
            'Bebê Menino',
            'Menina',
            'Menino',
            'Infantil',

            'Body',
            'Vestidos',
            'Camisetas',
            'Blusas',
            'Calças',
            'Leggings',
            'Shorts e Bermudas',
            'Conjuntos',
            'Casacos e Jaquetas',
            'Moletom',
            'Pijamas',
            'Macacões e Jardineiras',
            'Saias',
            'Moda Praia',

            'Calçados',
            'Meias',
            'Bonés e Chapéus',
            'Laços e Faixas',
            'Acessórios',
        ];

        foreach ($categorias as $nome) {
            // firstOrCreate evita duplicar se o seeder rodar mais de uma vez
            Categoria::firstOrCreate(['nome_categoria' => $nome]);
        }
    }
}
