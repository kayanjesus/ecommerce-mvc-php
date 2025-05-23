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
        Cor::create(['nome' => 'Cinza Claro', 'codigo_hex' => '#D3D3D3']);
        Cor::create(['nome' => 'Cinza Escuro', 'codigo_hex' => '#A9A9A9']);

        Cor::create(['nome' => 'Vermelho', 'codigo_hex' => '#FF0000']);
        Cor::create(['nome' => 'Vinho', 'codigo_hex' => '#800000']);
        Cor::create(['nome' => 'Bordô', 'codigo_hex' => '#4B0101']);

        Cor::create(['nome' => 'Rosa', 'codigo_hex' => '#FFC0CB']);
        Cor::create(['nome' => 'Rosa Claro', 'codigo_hex' => '#FFDDEE']);
        Cor::create(['nome' => 'Rosa Bebê', 'codigo_hex' => '#F8BBD0']);
        Cor::create(['nome' => 'Rosa Choque', 'codigo_hex' => '#FF69B4']);

        Cor::create(['nome' => 'Azul', 'codigo_hex' => '#0000FF']);
        Cor::create(['nome' => 'Azul Claro', 'codigo_hex' => '#ADD8E6']);
        Cor::create(['nome' => 'Azul Marinho', 'codigo_hex' => '#000080']);
        Cor::create(['nome' => 'Azul Royal', 'codigo_hex' => '#4169E1']);
        Cor::create(['nome' => 'Azul Turquesa', 'codigo_hex' => '#40E0D0']);

        Cor::create(['nome' => 'Verde', 'codigo_hex' => '#00FF00']);
        Cor::create(['nome' => 'Verde Claro', 'codigo_hex' => '#90EE90']);
        Cor::create(['nome' => 'Verde Militar', 'codigo_hex' => '#4B5320']);
        Cor::create(['nome' => 'Verde Oliva', 'codigo_hex' => '#808000']);
        Cor::create(['nome' => 'Verde Água', 'codigo_hex' => '#00CED1']);

        Cor::create(['nome' => 'Amarelo', 'codigo_hex' => '#FFFF00']);
        Cor::create(['nome' => 'Mostarda', 'codigo_hex' => '#FFDB58']);
        Cor::create(['nome' => 'Bege', 'codigo_hex' => '#F5F5DC']);
        Cor::create(['nome' => 'Nude', 'codigo_hex' => '#E3BC9A']);

        Cor::create(['nome' => 'Laranja', 'codigo_hex' => '#FFA500']);
        Cor::create(['nome' => 'Salmão', 'codigo_hex' => '#FA8072']);
        Cor::create(['nome' => 'Coral', 'codigo_hex' => '#FF7F50']);

        Cor::create(['nome' => 'Marrom', 'codigo_hex' => '#8B4513']);
        Cor::create(['nome' => 'Chocolate', 'codigo_hex' => '#7B3F00']);
        Cor::create(['nome' => 'Caramelo', 'codigo_hex' => '#A97142']);

        Cor::create(['nome' => 'Roxo', 'codigo_hex' => '#800080']);
        Cor::create(['nome' => 'Lavanda', 'codigo_hex' => '#E6E6FA']);
        Cor::create(['nome' => 'Lilás', 'codigo_hex' => '#C8A2C8']);

        Cor::create(['nome' => 'Dourado', 'codigo_hex' => '#FFD700']);
        Cor::create(['nome' => 'Prata', 'codigo_hex' => '#C0C0C0']);
        Cor::create(['nome' => 'Cobre', 'codigo_hex' => '#B87333']);
    }

}
