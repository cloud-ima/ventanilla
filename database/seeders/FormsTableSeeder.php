<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\PatentForm;

class FormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el usuario admin para created_by
        $adminUser = User::where('email', 'admin@example.com')->first();
        
        if (!$adminUser) {
            $adminUser = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Formularios existentes que deben preservarse
        $forms = [
            [
                'id' => 1,
                'name' => 'Microempresa Familiar',
                'description' => 'Formulario para registro de microempresa familiar',
                'template_file' => 'patent-forms/Microempresa_Familiar_1766047063.pdf',
                'created_by' => $adminUser->id,
                'is_active' => true,
                'created_at' => '2025-12-18 08:37:43',
                'updated_at' => '2025-12-18 08:37:43',
            ],
            [
                'id' => 2,
                'name' => 'Solicitud de Cambio',
                'description' => 'Formulario para solicitar cambios en patentes existentes',
                'template_file' => 'patent-forms/Solicitud_de_Cambio_1766047074.pdf',
                'created_by' => $adminUser->id,
                'is_active' => true,
                'created_at' => '2025-12-18 08:37:54',
                'updated_at' => '2025-12-18 08:37:54',
            ],
            [
                'id' => 3,
                'name' => 'Solicitud de Patente',
                'description' => 'Formulario principal para solicitud de patente comercial',
                'template_file' => 'patent-forms/Solicitud_de_Patente_1766047086.pdf',
                'created_by' => $adminUser->id,
                'is_active' => true,
                'created_at' => '2025-12-18 08:38:06',
                'updated_at' => '2025-12-18 08:38:06',
            ],
        ];

        // Insertar los formularios con IDs específicos
        foreach ($forms as $form) {
            DB::table('patent_forms')->insert($form);
        }

        // Copiar archivos PDF si existen en el directorio de seeders
        $this->copyPdfFiles();
    }

    /**
     * Copiar archivos PDF desde el directorio de seeders a storage
     */
    private function copyPdfFiles(): void
    {
        $sourceDir = database_path('seeders/data/patent-forms');
        $targetDir = 'patent-forms';

        if (is_dir($sourceDir)) {
            $files = scandir($sourceDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                    $sourcePath = $sourceDir . '/' . $file;
                    $targetPath = $targetDir . '/' . $file;
                    
                    if (!Storage::disk('public')->exists($targetPath)) {
                        Storage::disk('public')->put($targetPath, file_get_contents($sourcePath));
                    }
                }
            }
        }
    }
}
