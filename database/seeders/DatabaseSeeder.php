<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'rol' => 'Administrador',
            ]
        );

        // Usuario Docente
        User::updateOrCreate(
            ['email' => 'docente@test.com'],
            [
                'name' => 'Docente',
                'password' => Hash::make('12345678'),
                'rol' => 'Docente',
            ]
        );
    }
}
