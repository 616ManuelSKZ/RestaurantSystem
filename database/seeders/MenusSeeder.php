<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenusSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            // Entradas (id_categoria = 1)
            [
                'id_categoria' => 1,
                'nombre' => 'Nachos con Queso',
                'descripcion' => 'Totopos de maíz con queso fundido y jalapeños.',
                'precio' => 4.50,
                'disponible' => true,
                'imagen' => 'menus/nachos.jpg',
            ],
            [
                'id_categoria' => 1,
                'nombre' => 'Ensalada César',
                'descripcion' => 'Lechuga romana con aderezo césar y crutones.',
                'precio' => 5.25,
                'disponible' => true,
                'imagen' => 'menus/ensalada_cesar.jpg',
            ],

            // Platos Fuertes (id_categoria = 2)
            [
                'id_categoria' => 2,
                'nombre' => 'Pollo a la Plancha',
                'descripcion' => 'Pechuga de pollo asada con vegetales al vapor.',
                'precio' => 8.90,
                'disponible' => true,
                'imagen' => 'menus/pollo_plancha.jpg',
            ],
            [
                'id_categoria' => 2,
                'nombre' => 'Carne Asada',
                'descripcion' => 'Carne de res asada con papas fritas y ensalada.',
                'precio' => 10.50,
                'disponible' => true,
                'imagen' => 'menus/carne_asada.jpg',
            ],

            // Bebidas (id_categoria = 3)
            [
                'id_categoria' => 3,
                'nombre' => 'Limonada Natural',
                'descripcion' => 'Bebida refrescante de limón natural.',
                'precio' => 2.00,
                'disponible' => true,
                'imagen' => 'menus/limonada.jpg',
            ],
            [
                'id_categoria' => 3,
                'nombre' => 'Coca-Cola',
                'descripcion' => 'Bebida gaseosa clásica.',
                'precio' => 1.75,
                'disponible' => true,
                'imagen' => 'menus/cocacola.jpg',
            ],

            // Postres (id_categoria = 4)
            [
                'id_categoria' => 4,
                'nombre' => 'Pastel de Chocolate',
                'descripcion' => 'Rebanada de pastel húmedo de chocolate con cobertura.',
                'precio' => 3.80,
                'disponible' => true,
                'imagen' => 'menus/pastel_chocolate.jpg',
            ],
            [
                'id_categoria' => 4,
                'nombre' => 'Helado de Vainilla',
                'descripcion' => 'Dos bolas de helado de vainilla.',
                'precio' => 2.90,
                'disponible' => true,
                'imagen' => 'menus/helado_vainilla.jpg',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['nombre' => $menu['nombre']],
                [
                    'id_categoria' => $menu['id_categoria'],
                    'descripcion' => $menu['descripcion'],
                    'precio' => $menu['precio'],
                    'disponible' => $menu['disponible'],
                    'imagen' => $menu['imagen'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
