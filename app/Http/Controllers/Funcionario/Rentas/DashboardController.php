<?php

namespace App\Http\Controllers\Funcionario\Rentas;

use App\Models\PatentRequest;
use App\Models\PatentForm;
use App\Models\PatentRequirement;
use App\Models\PatentRequestForm;
use App\Models\PatentRequestRequirement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_requests' => PatentRequest::count(),
            'pending_requests' => PatentRequest::where('state', 'pending')->count(),
            'approved_requests' => PatentRequest::where('state', 'approved')->count(),
            'rejected_requests' => PatentRequest::whereIn('state', ['rejected', 'rejected_with_observations'])->count(),
            'total_forms' => PatentForm::where('is_active', true)->count(),
            'total_requirements' => PatentRequirement::where('is_active', true)->count(),
        ];

        // Solicitudes por estado
        $requestsByState = PatentRequest::selectRaw('
                state,
                COUNT(*) as count,
                ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 2) as percentage
            ')
            ->groupBy('state')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'state' => $item->state,
                    'label' => match($item->state) {
                        'pending' => 'Pendientes',
                        'approved' => 'Aprobadas',
                        'rejected' => 'Rechazadas',
                        'rejected_with_observations' => 'Rechazadas con Observaciones',
                    },
                    'count' => $item->count,
                    'percentage' => $item->percentage,
                ];
            });

        // Solicitudes por mes (últimos 6 meses)
        $requestsByMonth = PatentRequest::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                DATE_FORMAT(created_at, "%M %Y") as month_label,
                COUNT(*) as total,
                SUM(CASE WHEN state = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN state = "approved" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN state IN ("rejected", "rejected_with_observations") THEN 1 ELSE 0 END) as rejected
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month', 'month_label')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month_label,
                    'total' => (int) $item->total,
                    'pending' => (int) $item->pending,
                    'approved' => (int) $item->approved,
                    'rejected' => (int) $item->rejected,
                ];
            });

        // Requisitos más solicitados (top 5)
        $topRequirements = PatentRequestRequirement::selectRaw('
                patent_requirements.name,
                patent_requirements.category,
                COUNT(*) as count
            ')
            ->join('patent_requirements', 'patent_request_requirements.patent_requirement_id', '=', 'patent_requirements.id')
            ->where('patent_requirements.is_active', true)
            ->groupBy('patent_requirements.id', 'patent_requirements.name', 'patent_requirements.category')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'category' => $item->category,
                    'count' => $item->count,
                ];
            });

        // Formularios más usados (top 5)
        $topForms = PatentRequestForm::selectRaw('
                patent_forms.name,
                patent_forms.description,
                COUNT(*) as count
            ')
            ->join('patent_forms', 'patent_request_forms.patent_form_id', '=', 'patent_forms.id')
            ->where('patent_forms.is_active', true)
            ->groupBy('patent_forms.id', 'patent_forms.name', 'patent_forms.description')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'description' => $item->description,
                    'count' => $item->count,
                ];
            });

        // Solicitudes por categoría de requisito
        $requirementsByCategory = PatentRequestRequirement::selectRaw('
                patent_requirements.category,
                COUNT(*) as count
            ')
            ->join('patent_requirements', 'patent_request_requirements.patent_requirement_id', '=', 'patent_requirements.id')
            ->where('patent_requirements.is_active', true)
            ->groupBy('patent_requirements.category')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => match($item->category) {
                        'municipal' => 'Municipal',
                        'sanitario' => 'Sanitario',
                        'legal' => 'Legal',
                        'profesional' => 'Profesional',
                        'financiero' => 'Financiero',
                        'transporte' => 'Transporte',
                        'educacion' => 'Educación',
                        'seguridad' => 'Seguridad',
                        'otros' => 'Otros',
                    },
                    'count' => $item->count,
                ];
            });

        // Tiempo promedio de respuesta (en días)
        $avgResponseTime = PatentRequest::whereNotNull('reviewed_at')
            ->selectRaw('AVG(DATEDIFF(reviewed_at, created_at)) as avg_days')
            ->value('avg_days');

        // Solicitudes revisadas este mes
        $reviewsThisMonth = PatentRequest::whereNotNull('reviewed_at')
            ->whereMonth('reviewed_at', now()->month)
            ->whereYear('reviewed_at', now()->year)
            ->count();

        return Inertia::render('funcionario/rentas/Dashboard', [
            'stats' => $stats,
            'requestsByState' => $requestsByState,
            'requestsByMonth' => $requestsByMonth,
            'topRequirements' => $topRequirements,
            'topForms' => $topForms,
            'requirementsByCategory' => $requirementsByCategory,
            'avgResponseTime' => round($avgResponseTime ?? 0, 1),
            'reviewsThisMonth' => $reviewsThisMonth,
        ]);
    }
}
