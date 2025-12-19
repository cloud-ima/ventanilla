<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\Requirement;
use App\Models\Procedure;
use App\Models\User;
use Illuminate\Database\Seeder;

class PortalSeeder extends Seeder
{
    public function run(): void
    {
        // Crear/Actualizar Departments con datos del portal
        $departments = [
            [
                'name' => 'Oficina de Rentas Municipales',
                'slug' => 'rentas',
                'description' => 'Gestión de patentes comerciales, permisos y cobros municipales.',
                'icon' => 'Coins',
                'color' => '#06048c',
            ],
            [
                'name' => 'Obras',
                'slug' => 'obras',
                'description' => 'Permisos de edificación, regularizaciones y recepciones de obras.',
                'icon' => 'Building2',
                'color' => '#00b9f1',
            ]
        ];

        foreach ($departments as $deptData) {
            Department::updateOrCreate(
                ['slug' => $deptData['slug']],
                $deptData
            );
        }

        // Obtener el departamento de Rentas para crear datos de ejemplo
        $rentas = Department::where('slug', 'rentas')->first();

        // Crear requisitos comunes (is_active se pone automáticamente en true)
        $requisitos = [
            ['name' => 'Cédula de identidad vigente', 'description' => 'Original y fotocopia por ambos lados', 'how_to_obtain' => 'Registro Civil'],
            ['name' => 'Iniciación de actividades en SII', 'description' => 'Documento que acredite inicio de actividades', 'how_to_obtain' => 'Servicio de Impuestos Internos (www.sii.cl)'],
            ['name' => 'Contrato de arriendo o título de dominio', 'description' => 'Del local comercial', 'how_to_obtain' => 'Notaría o Conservador de Bienes Raíces'],
            ['name' => 'Permiso de edificación o recepción final', 'description' => 'Según corresponda al tipo de local', 'how_to_obtain' => 'Dirección de Obras Municipales'],
            ['name' => 'Resolución sanitaria', 'description' => 'Para actividades que lo requieran (alimentos, etc.)', 'how_to_obtain' => 'SEREMI de Salud'],
            ['name' => 'Certificado de antecedentes', 'description' => 'Sin anotaciones', 'how_to_obtain' => 'Registro Civil'],
            ['name' => 'Título profesional', 'description' => 'Legalizado ante notario', 'how_to_obtain' => 'Institución educacional correspondiente'],
        ];

        $createdRequisitos = [];
        foreach ($requisitos as $reqData) {
            $createdRequisitos[] = Requirement::updateOrCreate(
                ['name' => $reqData['name']],
                $reqData
            );
        }

        // Crear categoría Patentes
        $patentes = Category::updateOrCreate(
            ['slug' => 'patentes', 'department_id' => $rentas->id],
            [
                'name' => 'Patentes',
                'description' => 'Trámites relacionados con patentes comerciales, profesionales y de alcoholes.',
                'order' => 1,
                'is_active' => true,
            ]
        );

        // Crear categoría Permisos
        $permisos = Category::updateOrCreate(
            ['slug' => 'permisos', 'department_id' => $rentas->id],
            [
                'name' => 'Permisos',
                'description' => 'Permisos municipales para diversas actividades comerciales.',
                'order' => 2,
                'is_active' => true,
            ]
        );

        // Crear trámites de ejemplo
        $tramitePatente = Procedure::updateOrCreate(
            ['slug' => 'patente-comercial-definitiva', 'category_id' => $patentes->id],
            [
                'name' => 'Patente Comercial Definitiva',
                'description' => 'Obtención de patente para establecimientos comerciales permanentes.',
                'modality' => 'mixto',
                'cost' => 'Variable según actividad',
                'user_requirements' => [
                    'Cédula de identidad vigente',
                    'Iniciación de actividades en SII',
                    'Contrato de arriendo o título de dominio',
                ],
                'instructions' => [
                    'Complete el formulario en línea con sus datos personales y del negocio.',
                    'Adjunte los documentos requeridos en formato PDF o imagen clara.',
                    'Realice el pago correspondiente a través de los medios habilitados.',
                    'Espere la aprobación por parte del departamento de Rentas.',
                ],
                'legal_framework' => 'Ley N° 20.169 sobre Rentas Municipales y su reglamento. Ordenanza Municipal vigente.',
                'duration' => '5 a 10 días hábiles',
                'order' => 1,
                'is_active' => true,
            ]
        );

        // Asociar requisitos al trámite
        $tramitePatente->requirements()->sync([
            $createdRequisitos[0]->id => ['is_mandatory' => true],
            $createdRequisitos[1]->id => ['is_mandatory' => true],
            $createdRequisitos[2]->id => ['is_mandatory' => true],
            $createdRequisitos[3]->id => ['is_mandatory' => true],
            $createdRequisitos[4]->id => ['is_mandatory' => false],
        ]);

        $tramiteProfesional = Procedure::updateOrCreate(
            ['slug' => 'patente-profesional', 'category_id' => $patentes->id],
            [
                'name' => 'Patente Profesional',
                'description' => 'Para ejercicio de profesiones liberales.',
                'modality' => 'online',
                'cost' => '0.5 UTM',
                'duration' => '3 a 5 días hábiles',
                'user_requirements' => [
                    'Cédula de identidad vigente',
                    'Título profesional legalizado',
                    'Certificado de antecedentes',
                ],
                'instructions' => [
                    'Llene el formulario de solicitud en línea con sus datos personales y profesionales.',
                    'Suba los documentos requeridos en los formatos aceptados.',
                    'Realice el pago de la patente a través del sistema en línea.',
                    'Espere la confirmación y emisión de la patente por parte del municipio.',
                ],
                'legal_framework' => 'Ley N° 20.169 sobre Rentas Municipales. Código Sanitario para profesiones de la salud.',
                'order' => 2,
                'is_active' => true,
            ]
        );

        $tramiteProfesional->requirements()->sync([
            $createdRequisitos[0]->id => ['is_mandatory' => true],
            $createdRequisitos[6]->id => ['is_mandatory' => true],
            $createdRequisitos[1]->id => ['is_mandatory' => true],
        ]);

        $tramitePermiso = Procedure::updateOrCreate(
            ['slug' => 'permiso-publicidad', 'category_id' => $permisos->id],
            [
                'name' => 'Permiso de Publicidad',
                'description' => 'Autorización para instalación de letreros y publicidad.',
                'modality' => 'presencial',
                'cost' => 'Variable según tamaño',
                'user_requirements' => [
                    'Cédula de identidad vigente',
                    'Permiso de edificación o recepción final',
                    'Contrato de arriendo o título de dominio',
                ],
                'instructions' => [
                    'Complete el formulario de solicitud en línea con los detalles de la publicidad.',
                    'Adjunte los documentos necesarios en los formatos requeridos.',
                    'Realice el pago correspondiente según las tarifas vigentes.',
                    'Espere la evaluación y aprobación del permiso por parte del municipio.',
                ],
                'legal_framework' => 'Ordenanza Municipal sobre Publicidad Exterior. Ley N° 19.418 sobre Libertad de Opinión e Información.',
                'duration' => '5 a 10 días hábiles',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $tramitePermiso->requirements()->sync([
            $createdRequisitos[0]->id => ['is_mandatory' => true],
        ]);


        // Crear usuario funcionario para revisión
        User::create([
            'name' => 'Funcionario Rentas',
            'email' => 'fabian@rentas.cl',
            'password' => bcrypt('password123'),
            'role' => 'funcionario',
            'department_id' => $rentas->id,
            'is_active' => true,
        ]);
    }
}
