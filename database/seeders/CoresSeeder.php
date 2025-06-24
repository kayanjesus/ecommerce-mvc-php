<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use App\Models\Cor;
class CoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Cor::create(['nome' => 'Preto', 'codigo_hex' => '#000000']);
        Cor::create(['nome' => 'Branco', 'codigo_hex' => '#FFFFFF']);
        Cor::create(['nome' => 'Cinza', 'codigo_hex' => '#808080']);

        Cor::create(['nome' => 'Vermelho', 'codigo_hex' => '#FF0000']);
        Cor::create(['nome' => 'Vinho', 'codigo_hex' => '#800000']);

        Cor::create(['nome' => 'Rosa', 'codigo_hex' => '#FFC0CB']);

        Cor::create(['nome' => 'Azul', 'codigo_hex' => '#0000FF']);
        Cor::create(['nome' => 'Azul Claro', 'codigo_hex' => '#ADD8E6']);

        Cor::create(['nome' => 'Verde', 'codigo_hex' => '#00FF00']);
        Cor::create(['nome' => 'Verde Claro', 'codigo_hex' => '#90EE90']);

        Cor::create(['nome' => 'Amarelo', 'codigo_hex' => '#FFFF00']);
        Cor::create(['nome' => 'Bege', 'codigo_hex' => '#F5F5DC']);

        Cor::create(['nome' => 'Laranja', 'codigo_hex' => '#FFA500']);

        Cor::create(['nome' => 'Marrom', 'codigo_hex' => '#8B4513']);

        Cor::create(['nome' => 'Roxo', 'codigo_hex' => '#800080']);
        Cor::create(['nome' => 'Lilás', 'codigo_hex' => '#C8A2C8']);
    }

}
