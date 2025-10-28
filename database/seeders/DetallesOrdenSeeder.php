<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetalleOrden;
use App\Models\Menu;

class DetallesOrdenSeeder extends Seeder
{
    public function run()
    {
        $detalles = [
            // Orden 1
            ['id_orden' => 1, 'id_menu' => 1, 'cantidad' => 1],
            ['id_orden' => 1, 'id_menu' => 3, 'cantidad' => 1],
            ['id_orden' => 1, 'id_menu' => 5, 'cantidad' => 2],

            // Orden 2
            ['id_orden' => 2, 'id_menu' => 4, 'cantidad' => 1],
            ['id_orden' => 2, 'id_menu' => 7, 'cantidad' => 1],
            ['id_orden' => 2, 'id_menu' => 8, 'cantidad' => 1],

            // Orden 3
            ['id_orden' => 3, 'id_menu' => 2, 'cantidad' => 1],
            ['id_orden' => 3, 'id_menu' => 5, 'cantidad' => 2],
            ['id_orden' => 3, 'id_menu' => 6, 'cantidad' => 1],
        ];

        foreach ($detalles as $detalle) {
            $menu = Menu::find($detalle['id_menu']);
            if (!$menu) {
                continue; // saltar si el menú no existe
            }

            $precio_unitario = $menu->precio;
            $subtotal = $detalle['cantidad'] * $precio_unitario;

            DetalleOrden::updateOrCreate(
                [
                    'id_orden' => $detalle['id_orden'],
                    'id_menu' => $detalle['id_menu'],
                ],
                [
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $precio_unitario,
                    'subtotal' => $subtotal,
                    'nombre_menu' => $menu->nombre,
                    'precio_menu' => $menu->precio,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
