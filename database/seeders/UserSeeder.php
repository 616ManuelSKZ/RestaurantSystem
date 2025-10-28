<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Usuario Administrador
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin1234'),
                'rol' => 'administrador',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Usuario Manuel Hernández (mesero)
        User::updateOrCreate(
            ['email' => 'manuel@gmail.com'],
            [
                'name' => 'Manuel Hernandez',
                'password' => Hash::make('Manuel123'),
                'rol' => 'mesero',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Usuario Javier Castellanos (cocinero)
        User::updateOrCreate(
            ['email' => 'javier@gmail.com'],
            [
                'name' => 'Javier Castellanos',
                'password' => Hash::make('Javier123'),
                'rol' => 'cocinero',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Usuario Edenilson Flores (cajero)
        User::updateOrCreate(
            ['email' => 'edenilson@gmail.com'],
            [
                'name' => 'Edenilson Flores',
                'password' => Hash::make('Edenilson123'),
                'rol' => 'cajero',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
