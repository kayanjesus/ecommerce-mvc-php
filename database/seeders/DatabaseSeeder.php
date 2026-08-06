<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CoresSeeder::class,
            CategoriasSeeder::class,
            TamanhosSeeder::class,
        ]);
    }
}
