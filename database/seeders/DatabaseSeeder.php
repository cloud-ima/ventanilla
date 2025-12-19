<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios base
        $this->call(UserSeeder::class);

        // Crear formularios base (preserva los 3 existentes)
        $this->call(FormsTableSeeder::class);

        // Crear requisitos de patentes (antes que las solicitudes)
        $this->call(PatentRequirementSeeder::class);

        // Generar solicitudes históricas (6 meses de datos)
        $this->call(RequestsTableSeeder::class);

        // Seeders adicionales existentes
        $this->call([
            PortalSeeder::class,
        ]);
    }
}