<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Administrador
        User::updateOrCreate(
            ['email' => 'admin@culturapp.com'],
            [
                'name' => 'Administrador CulturApp',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'bio' => 'Administrador principal de CulturApp',
                'activo' => true,
            ]
        );

        // Colaborador Cultural
        User::updateOrCreate(
            ['email' => 'roberto@culturapp.com'],
            [
                'name' => 'Don Roberto Hernández',
                'password' => Hash::make('roberto123'),
                'role' => 'colaborador',
                'bio' => 'Cronista de la ciudad y conocedor de las tradiciones locales',
                'telefono' => '771-123-4567',
                'activo' => true,
            ]
        );

        // Usuario Normal
        User::updateOrCreate(
            ['email' => 'maria@culturapp.com'],
            [
                'name' => 'María García',
                'password' => Hash::make('maria123'),
                'role' => 'usuario',
                'bio' => 'Estudiante interesada en las tradiciones de su región',
                'activo' => true,
            ]
        );

        // Otro Usuario Normal
        User::updateOrCreate(
            ['email' => 'juan@culturapp.com'],
            [
                'name' => 'Juan López',
                'password' => Hash::make('juan123'),
                'role' => 'usuario',
                'bio' => 'Joven emprendedor cultural',
                'activo' => true,
            ]
        );
    }
}