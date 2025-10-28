<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Factura;

class FacturasSeeder extends Seeder
{
    public function run()
    {
        $facturas = [
            [
                'id_orden' => 2,
                'id_users' => 4, // Edenilson Flores (cajero)
                'tipo_factura' => 'Consumidor Final',
                'fecha_emision' => now()->subDay(),
                'nombre_cliente' => 'Carlos López',
                'telefono_cliente' => '78541236',
                'nit_cliente' => 'CF',
                'direccion_cliente' => 'Zona 1, Ciudad',
                'subtotal' => 26.15,
                'impuestos' => 3.92,
                'totaliva' => 30.07,
                'total' => 30.07,
            ],
            [
                'id_orden' => 3,
                'id_users' => 4,
                'tipo_factura' => 'Consumidor Final',
                'fecha_emision' => now()->subHours(5),
                'nombre_cliente' => 'María Pérez',
                'telefono_cliente' => '45698712',
                'nit_cliente' => '2345234-5',
                'direccion_cliente' => 'Colonia Centro',
                'subtotal' => 15.75,
                'impuestos' => 2.36,
                'totaliva' => 18.11,
                'total' => 18.11,
            ],
        ];

        foreach ($facturas as $factura) {
            Factura::updateOrCreate(
                [
                    'id_orden' => $factura['id_orden']
                ],
                array_merge($factura, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
