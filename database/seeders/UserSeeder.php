<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Crear usuario contribuyente
        User::create([
            'name' => 'Eduardo',
            'email' => 'eduardotito.jose01@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'contribuyente',
            'is_active' => true,
        ]);
    }
}
