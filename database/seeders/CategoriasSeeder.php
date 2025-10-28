<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            ['nombre' => 'Entradas'],
            ['nombre' => 'Platos Fuertes'],
            ['nombre' => 'Bebidas'],
            ['nombre' => 'Postres'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::updateOrCreate(
                ['nombre' => $categoria['nombre']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
