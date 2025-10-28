<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Orden;

class OrdenesSeeder extends Seeder
{
    public function run()
    {
        $ordenes = [
            [
                'id_mesas' => 1, // Mesa 1 - Comedor Principal
                'id_users' => 2, // Manuel Hernández (mesero)
                'estado' => 'Completada',
                'fecha_orden' => now()->subHours(3),
                'subtotal' => 18.40,
                'impuestos' => 2.76,
                'totaliva' => 21.16,
            ],
            [
                'id_mesas' => 3, // Mesa 3 - Comedor Principal
                'id_users' => 2,
                'estado' => 'Completada',
                'fecha_orden' => now()->subDay(),
                'subtotal' => 26.15,
                'impuestos' => 3.92,
                'totaliva' => 30.07,
            ],
            [
                'id_mesas' => 5, // Mesa 5 - Terraza
                'id_users' => 2,
                'estado' => 'Completada',
                'fecha_orden' => now()->subHours(5),
                'subtotal' => 15.75,
                'impuestos' => 2.36,
                'totaliva' => 18.11,
            ],
        ];

        foreach ($ordenes as $orden) {
            Orden::updateOrCreate(
                [
                    'id_mesas' => $orden['id_mesas'],
                    'fecha_orden' => $orden['fecha_orden']
                ],
                [
                    'id_users' => $orden['id_users'],
                    'estado' => $orden['estado'],
                    'subtotal' => $orden['subtotal'],
                    'impuestos' => $orden['impuestos'],
                    'totaliva' => $orden['totaliva'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
