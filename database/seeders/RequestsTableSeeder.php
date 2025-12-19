<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PatentRequest;

class RequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generar solicitudes para el usuario específico eduardotito.jose01@gmail.com
        $this->generateSampleRequestsForEduardo();
        
        // Generar usuarios contribuyentes
        $users = User::factory(5)->create(['role' => 'contribuyente']); // Reducido de 20 a 5
        $reviewers = User::where('role', 'funcionario')->get();

        // Asegurar que hay funcionarios disponibles
        if ($reviewers->isEmpty()) {
            // Obtener el departamento de Rentas (slug 'rentas')
            $department = \App\Models\Department::where('slug', 'rentas')->first();
            
            if (!$department) {
                // Si no existe, crearlo
                $department = \App\Models\Department::create([
                    'name' => 'Oficina de Rentas Municipales',
                    'slug' => 'rentas',
                    'description' => 'Gestión de patentes comerciales, permisos y cobros municipales.',
                    'icon' => 'Coins',
                    'color' => '#06048c',
                ]);
            }
            
            $reviewers = User::factory(2)->create([ // Reducido de 5 a 2
                'role' => 'funcionario',
                'department_id' => $department->id
            ]);
        }

        // Generar datos históricos para los últimos 3 meses (reducido de 6 a 3)
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now();
        
        $requestId = 1;

        // Generar solicitudes distribuidas en 3 meses
        for ($month = 0; $month < 3; $month++) {
            $currentMonth = $startDate->copy()->addMonths($month);
            $daysInMonth = $currentMonth->daysInMonth;
            
            // Generar entre 5-10 solicitudes por mes (reducido de 15-25)
            $requestsPerMonth = rand(5, 10);
            
            for ($i = 0; $i < $requestsPerMonth; $i++) {
                $day = rand(1, $daysInMonth);
                $createdAt = $currentMonth->copy()->day($day)
                    ->hour(rand(8, 18))
                    ->minute(rand(0, 59))
                    ->second(rand(0, 59));

                // Determinar el estado basado en la antigüedad
                $daysSinceCreation = $createdAt->diffInDays($endDate);
                $state = $this->determineState($daysSinceCreation, $createdAt);
                
                // Crear solicitud usando Eloquent para que se genere el código automáticamente
                $request = PatentRequest::create([
                    'user_id' => $users->random()->id,
                    'rut' => $this->generateRut(),
                    'business_address' => $this->generateAddress(),
                    'business_activity' => $this->generateBusinessActivity(),
                    'state' => $state,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'observations' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $state !== 'pending' ? $createdAt->copy()->addDays(rand(1, 30)) : $createdAt,
                ]);

                // Si no está pendiente, asignar revisor y fecha de revisión
                if ($state !== 'pending') {
                    $request->update([
                        'reviewed_by' => $reviewers->random()->id,
                        'reviewed_at' => $request->updated_at,
                    ]);
                    
                    if ($state === 'rejected_with_observations') {
                        $request->update([
                            'observations' => $this->generateObservations(),
                        ]);
                    }
                }

                $requestId++;
            }
        }

        // Generar relaciones con formularios y requisitos
        $this->generateRequestForms($requestId - 1);
        $this->generateRequestRequirements($requestId - 1);
    }

    /**
     * Determinar el estado basado en la antigüedad de la solicitud
     */
    private function determineState($daysSinceCreation, $createdAt): string
    {
        // Solicitudes recientes (últimos 15 días) tienden a estar pendientes
        if ($daysSinceCreation < 15) {
            return rand(0, 100) < 70 ? 'pending' : (rand(0, 1) ? 'approved' : 'rejected');
        }
        
        // Distribución general: 60% aprobadas, 30% pendientes, 10% rechazadas
        $rand = rand(0, 100);
        if ($rand < 60) return 'approved';
        if ($rand < 90) return 'pending';
        if ($rand < 95) return 'rejected';
        return 'rejected_with_observations';
    }

    /**
     * Generar un RUT chileno válido
     */
    private function generateRut(): string
    {
        $number = rand(1000000, 25000000);
        $dv = $this->calculateDv($number);
        return $number . '-' . $dv;
    }

    /**
     * Calcular dígito verificador del RUT
     */
    private function calculateDv($number): string|int
    {
        $sum = 0;
        $multiplier = 2;
        $temp = $number;
        
        while ($temp > 0) {
            $sum += ($temp % 10) * $multiplier;
            $temp = floor($temp / 10);
            $multiplier = $multiplier == 7 ? 2 : $multiplier + 1;
        }
        
        $dv = 11 - ($sum % 11);
        return $dv == 11 ? 0 : ($dv == 10 ? 'K' : $dv);
    }

    /**
     * Generar dirección comercial
     */
    private function generateAddress(): string
    {
        $streets = ['Av. Principal', 'Calle Comercio', 'Pasaje Central', 'Calle Nueva', 'Av. Estación', 'Calle del Sol', 'Pasaje Las Flores'];
        $numbers = rand(100, 9999);
        $additional = rand(0, 1) ? 'Oficina ' . rand(101, 505) : 'Local ' . rand(1, 99);
        
        return $streets[array_rand($streets)] . ' #' . $numbers . ', ' . $additional;
    }

    /**
     * Generar actividad comercial
     */
    private function generateBusinessActivity(): string
    {
        $activities = [
            'Venta de alimentos y bebidas',
            'Servicios de belleza y peluquería',
            'Comercio de vestuario y calzado',
            'Servicios de reparación técnica',
            'Venta de productos de limpieza',
            'Servicios de consultoría',
            'Comercio de artículos escolares',
            'Servicios de gastronomía',
            'Venta de productos electrónicos',
            'Servicios de salud y bienestar',
        ];
        
        return $activities[array_rand($activities)];
    }

    /**
     * Generar observaciones para rechazos
     */
    private function generateObservations(): ?string
    {
        $observations = [
            'La documentación presentada está incompleta. Favor completar los requisitos faltantes.',
            'El domicilio comercial no cumple con los requisitos municipales.',
            'Se requiere permiso sanitario adicional para esta actividad.',
            'La razón social no coincide con los documentos presentados.',
            'Firma del representante legal no está debidamente autorizada.',
        ];
        
        return $observations[array_rand($observations)];
    }

    /**
     * Generar relaciones entre solicitudes y formularios
     */
    private function generateRequestForms($totalRequests): void
    {
        $requestForms = [];
        
        // Obtener usuarios funcionarios para attached_by
        $users = User::where('role', 'funcionario')->get();
        
        for ($requestId = 1; $requestId <= $totalRequests; $requestId++) {
            // Cada solicitud tiene 1-3 formularios asociados
            $formCount = rand(1, 3);
            
            // Obtener IDs de formularios disponibles
            $availableFormIds = [1, 2, 3]; // IDs de formularios existentes
            $selectedFormIds = array_rand($availableFormIds, min($formCount, count($availableFormIds)));
            
            if (!is_array($selectedFormIds)) {
                $selectedFormIds = [$selectedFormIds];
            }
            
            foreach ($selectedFormIds as $formIndex) {
                $requestForms[] = [
                    'patent_request_id' => $requestId,
                    'patent_form_id' => $availableFormIds[$formIndex],
                    'attached_by' => $users->random()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        // Usar insertOrIgnore para evitar duplicados en tablas con PK compuesta
        DB::table('patent_request_forms')->insertOrIgnore($requestForms);
    }

    /**
     * Generar relaciones entre solicitudes y requisitos
     */
    private function generateRequestRequirements($totalRequests): void
    {
        $requestRequirements = [];
        
        // Obtener usuarios funcionarios para attached_by
        $users = User::where('role', 'funcionario')->get();
        
        // Obtener IDs reales de requisitos existentes
        $requirementIds = \App\Models\PatentRequirement::pluck('id')->toArray();
        
        if (empty($requirementIds)) {
            // Si no hay requisitos, no generar relaciones
            return;
        }
        
        for ($requestId = 1; $requestId <= $totalRequests; $requestId++) {
            // Cada solicitud requiere entre 5-12 requisitos
            $requirementCount = rand(5, min(12, count($requirementIds)));
            $selectedRequirements = array_rand($requirementIds, $requirementCount);
            
            if (!is_array($selectedRequirements)) {
                $selectedRequirements = [$selectedRequirements];
            }
            
            foreach ($selectedRequirements as $reqIndex) {
                $requestRequirements[] = [
                    'patent_request_id' => $requestId,
                    'patent_requirement_id' => $requirementIds[$reqIndex],
                    'observations' => rand(0, 1) ? 'Documento presentado para revisión' : null,
                    'attached_by' => $users->random()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        // Usar insertOrIgnore para evitar duplicados en tablas con PK compuesta
        DB::table('patent_request_requirements')->insertOrIgnore($requestRequirements);
    }

    /**
     * Generar solicitudes de ejemplo para el usuario eduardotito.jose01@gmail.com
     */
    private function generateSampleRequestsForEduardo(): void
    {
        // Obtener el usuario eduardotito.jose01@gmail.com
        $user = User::where('email', 'eduardotito.jose01@gmail.com')->first();
        
        if (!$user) {
            return;
        }

        // Obtener un funcionario para revisar
        $funcionario = User::where('role', 'funcionario')->first();

        if (!$funcionario) {
            // Si no hay funcionario, crear uno
            $department = \App\Models\Department::where('slug', 'rentas')->first();
            if (!$department) {
                $department = \App\Models\Department::create([
                    'name' => 'Oficina de Rentas Municipales',
                    'slug' => 'rentas',
                    'description' => 'Gestión de patentes comerciales, permisos y cobros municipales.',
                    'icon' => 'Coins',
                    'color' => '#06048c',
                ]);
            }
            
            $funcionario = User::factory()->create([
                'role' => 'funcionario',
                'department_id' => $department->id,
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@rentas.cl',
            ]);
        }

        // Obtener IDs de formularios y requisitos
        $formIds = DB::table('patent_forms')->pluck('id')->toArray();
        $requirementIds = DB::table('patent_requirements')->pluck('id')->toArray();

        if (empty($formIds) || empty($requirementIds)) {
            return;
        }

        // Crear 3 solicitudes de ejemplo
        $requests = [
            [
                'user_id' => $user->id,
                'rut' => '12345678-9',
                'business_address' => 'Av. Principal #123, Santiago',
                'business_activity' => 'Venta de productos tecnológicos',
                'state' => 'approved',
                'reviewed_by' => $funcionario->id,
                'reviewed_at' => Carbon::now()->subDays(10),
                'observations' => 'Solicitud aprobada con documentación completa',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'user_id' => $user->id,
                'rut' => '12345678-9',
                'business_address' => 'Calle Secundaria #456, Santiago',
                'business_activity' => 'Servicios de consultoría',
                'state' => 'pending',
                'reviewed_by' => null,
                'reviewed_at' => null,
                'observations' => null,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id' => $user->id,
                'rut' => '12345678-9',
                'business_address' => 'Plaza Central #789, Santiago',
                'business_activity' => 'Restaurante y comida rápida',
                'state' => 'rejected_with_observations',
                'reviewed_by' => $funcionario->id,
                'reviewed_at' => Carbon::now()->subDays(2),
                'observations' => 'Se requiere actualizar el comprobante de domicilio y presentar el permiso municipal',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ];

        // Insertar las solicitudes
        foreach ($requests as $requestData) {
            // Crear solicitud usando Eloquent para generar el código automáticamente
            $request = PatentRequest::create($requestData);
            $requestId = $request->id;

            // Agregar formularios a cada solicitud
            foreach ($formIds as $formId) {
                DB::table('patent_request_forms')->insert([
                    'patent_request_id' => $requestId,
                    'patent_form_id' => $formId,
                    'attached_by' => $funcionario->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Agregar requisitos a cada solicitud
            $selectedRequirements = array_rand($requirementIds, rand(5, 8));
            if (!is_array($selectedRequirements)) {
                $selectedRequirements = [$selectedRequirements];
            }

            foreach ($selectedRequirements as $reqIndex) {
                DB::table('patent_request_requirements')->insert([
                    'patent_request_id' => $requestId,
                    'patent_requirement_id' => $requirementIds[$reqIndex],
                    'observations' => rand(0, 1) ? 'Documento presentado para revisión' : null,
                    'attached_by' => $funcionario->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
