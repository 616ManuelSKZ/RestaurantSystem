<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetalleFactura;
use App\Models\Menu;

class DetallesFacturaSeeder extends Seeder
{
    public function run()
    {
        $detalles = [
            // Factura 1 (Orden 2)
            ['id_factura' => 1, 'id_menu' => 4, 'cantidad' => 1],
            ['id_factura' => 1, 'id_menu' => 7, 'cantidad' => 1],
            ['id_factura' => 1, 'id_menu' => 8, 'cantidad' => 1],

            // Factura 2 (Orden 3)
            ['id_factura' => 2, 'id_menu' => 2, 'cantidad' => 1],
            ['id_factura' => 2, 'id_menu' => 5, 'cantidad' => 2],
            ['id_factura' => 2, 'id_menu' => 6, 'cantidad' => 1],
        ];

        foreach ($detalles as $detalle) {
            $menu = Menu::find($detalle['id_menu']);
            if (!$menu) {
                continue; // evitar error si el menú no existe
            }

            $precio_unitario = $menu->precio;
            $subtotal = $detalle['cantidad'] * $precio_unitario;

            DetalleFactura::updateOrCreate(
                [
                    'id_factura' => $detalle['id_factura'],
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
