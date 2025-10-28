<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AreaMesa;

class AreaMesasSeeder extends Seeder
{
    public function run()
    {
        $areas = [
            ['nombre' => 'Comedor Principal'],
            ['nombre' => 'Terraza'],
            ['nombre' => 'Barra'],
        ];

        foreach ($areas as $area) {
            AreaMesa::updateOrCreate(
                ['nombre' => $area['nombre']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
