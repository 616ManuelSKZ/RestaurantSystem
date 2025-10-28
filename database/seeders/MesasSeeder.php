<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mesa;

class MesasSeeder extends Seeder
{
    public function run()
    {
        $mesas = [
            // Comedor Principal
            ['id_area_mesas' => 1, 'numero' => 1, 'capacidad' => 4, 'estado' => 'disponible'],
            ['id_area_mesas' => 1, 'numero' => 2, 'capacidad' => 4, 'estado' => 'disponible'],
            ['id_area_mesas' => 1, 'numero' => 3, 'capacidad' => 6, 'estado' => 'disponible'],

            // Terraza
            ['id_area_mesas' => 2, 'numero' => 4, 'capacidad' => 2, 'estado' => 'disponible'],
            ['id_area_mesas' => 2, 'numero' => 5, 'capacidad' => 4, 'estado' => 'disponible'],

            // Barra
            ['id_area_mesas' => 3, 'numero' => 6, 'capacidad' => 1, 'estado' => 'disponible'],
            ['id_area_mesas' => 3, 'numero' => 7, 'capacidad' => 1, 'estado' => 'disponible'],
        ];

        foreach ($mesas as $mesa) {
            Mesa::updateOrCreate(
                ['numero' => $mesa['numero']],
                [
                    'id_area_mesas' => $mesa['id_area_mesas'],
                    'capacidad' => $mesa['capacidad'],
                    'estado' => $mesa['estado'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
