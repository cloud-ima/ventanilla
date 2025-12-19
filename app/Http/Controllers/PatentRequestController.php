<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidRut;
use App\Models\PatentRequest;
use App\Models\PatentForm;
use App\Models\PatentRequirement;

class PatentRequestController
{
    /**
     *  Despliega las patentes pero solo la suyas.
     */
    public function indexContribuyente(Request $request)
    {
        $query = auth()->user()
            ->patentRequests();

        // Filtrar por código si se proporciona
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        $requests = $query->latest()->paginate(10)->withQueryString();

        // Estadísticas personales del contribuyente
        $stats = [
            'pending' => auth()->user()->patentRequests()->where('state', 'pending')->count(),
            'approved' => auth()->user()->patentRequests()->where('state', 'approved')->count(),
            'rejected' => auth()->user()->patentRequests()->where('state', 'rejected')->count(),
            'rejected_with_observations' => auth()->user()->patentRequests()->where('state', 'rejected_with_observations')->count(),
        ];

        return Inertia::render('contribuyente/patentes/Index', [
            'requests' => $requests,
            'stats' => $stats,
        ]);
    }

    /**
     * Desplige todas las patentes en general para el Funcionario de Rentas.
     */
    public function indexRentas(Request $request)
    {
        $query = PatentRequest::query()
            ->with(['user', 'reviewer', 'history']);

        // Filtrar por estado si se proporciona
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // Filtrar por RUT si se proporciona
        if ($request->filled('rut')) {
            $query->where('rut', 'like', '%' . $request->rut . '%');
        }

        // Filtrar por código si se proporciona
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        // Ordenar por más reciente primero
        $query->latest();

        // Paginar resultados
        $requests = $query->paginate(10)->withQueryString();

        // Estadísticas
        $stats = [
            'pending' => PatentRequest::where('state', 'pending')->count(),
            'approved' => PatentRequest::where('state', 'approved')->count(),
            'rejected' => PatentRequest::where('state', 'rejected')->count(),
            'rejected_with_observations' => PatentRequest::where('state', 'rejected_with_observations')->count(),
        ];

        return Inertia::render('funcionario/rentas/patentes/Index', [
            'requests' => $requests,
            'stats' => $stats,
            'filters' => $request->only(['state', 'rut']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar que no tenga más de 5 solicitudes pendientes
        $pendingCount = auth()->user()
            ->patentRequests()
            ->where('state', 'pending')
            ->count();

        if ($pendingCount >= PatentRequest::MAX_PENDING_REQUESTS) {
            return redirect()
                ->route('contribuyente.patentes.index')
                ->with('error', 'Has alcanzado el límite máximo de ' . PatentRequest::MAX_PENDING_REQUESTS . ' solicitudes pendientes. Espera a que sean procesadas para crear una nueva.');
        }

        return Inertia::render('contribuyente/patentes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar que no tenga más de las solicitudaes establecidas
        $pendingCount = auth()->user()
            ->patentRequests()
            ->where('state', 'pending')
            ->count();

        $isMax = $pendingCount >= PatentRequest::MAX_PENDING_REQUESTS;

        abort_if($isMax, 403, 'Has alcanzado el límite máximo de ' . PatentRequest::MAX_PENDING_REQUESTS . ' solicitudes pendientes');

        // Validar datos
        $validated = $request->validate([
            'rut' => ['required', 'string', new ValidRut],
            'business_address' => 'required|string|max:255',
            'business_activity' => 'required|string|max:1000',
        ]);


        DB::transaction(function () use ($validated) {
            // Crear solicitud
            $patentRequest = auth()->user()->patentRequests()->create([
                'rut' => $validated['rut'],
                'business_address' => $validated['business_address'],
                'business_activity' => $validated['business_activity'],
                'state' => 'pending',
            ]);

            // Registrar en historial
            $patentRequest->history()->create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'observations' => null,
            ]);
        });

        return redirect()->route('contribuyente.patentes.index')
            ->with('success', 'Solicitud enviada correctamente. Te notificaremos cuando sea revisada.');
    }

    /**
     * Obtener estadísticas para el dashboard de Rentas
     */
    public function getDashboardStats()
    {
        // Estadísticas generales
        $stats = [
            'pending' => PatentRequest::where('state', 'pending')->count(),
            'approved' => PatentRequest::where('state', 'approved')->count(),
            'rejected' => PatentRequest::where('state', 'rejected')->count(),
            'rejected_with_observations' => PatentRequest::where('state', 'rejected_with_observations')->count(),
        ];

        // Solicitudes por estado (para gráfico donut)
        $requestsByState = [
            ['name' => 'Pendientes', 'value' => $stats['pending'], 'color' => 'var(--chart-1)'],
            ['name' => 'Aprobadas', 'value' => $stats['approved'], 'color' => 'var(--chart-2)'],
            ['name' => 'Rechazadas', 'value' => $stats['rejected'], 'color' => 'var(--chart-3)'],
            ['name' => 'Con Observaciones', 'value' => $stats['rejected_with_observations'], 'color' => 'var(--chart-4)'],
        ];

        // Solicitudes por mes (últimos 6 meses)
        $requestsByMonth = PatentRequest::selectRaw('
                CASE MONTH(created_at)
                    WHEN 1 THEN "Enero"
                    WHEN 2 THEN "Febrero"
                    WHEN 3 THEN "Marzo"
                    WHEN 4 THEN "Abril"
                    WHEN 5 THEN "Mayo"
                    WHEN 6 THEN "Junio"
                    WHEN 7 THEN "Julio"
                    WHEN 8 THEN "Agosto"
                    WHEN 9 THEN "Septiembre"
                    WHEN 10 THEN "Octubre"
                    WHEN 11 THEN "Noviembre"
                    WHEN 12 THEN "Diciembre"
                END as month,
                COUNT(*) as count
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'count' => (int) $item->count,
                ];
            });

        // Requisitos más solicitados
        $topRequirements = PatentRequest::join('patent_request_requirements', 'patent_requests.id', '=', 'patent_request_requirements.patent_request_id')
            ->join('patent_requirements', 'patent_request_requirements.patent_requirement_id', '=', 'patent_requirements.id')
            ->selectRaw('
                patent_requirements.name,
                patent_requirements.category,
                COUNT(*) as count
            ')
            ->groupBy('patent_requirements.id', 'patent_requirements.name', 'patent_requirements.category')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Formularios más usados
        $topForms = PatentRequest::join('patent_request_forms', 'patent_requests.id', '=', 'patent_request_forms.patent_request_id')
            ->join('patent_forms', 'patent_request_forms.patent_form_id', '=', 'patent_forms.id')
            ->selectRaw('
                patent_forms.name,
                COUNT(*) as count
            ')
            ->groupBy('patent_forms.id', 'patent_forms.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Tiempo promedio de respuesta (días)
        $avgResponseTime = PatentRequest::whereNotNull('reviewed_at')
            ->selectRaw('AVG(DATEDIFF(reviewed_at, created_at)) as avg_days')
            ->value('avg_days') ?? 0;

        // Revisiones este mes
        $reviewsThisMonth = PatentRequest::whereMonth('reviewed_at', now()->month)
            ->whereYear('reviewed_at', now()->year)
            ->count();

        return response()->json([
            'stats' => $stats,
            'requestsByState' => $requestsByState,
            'requestsByMonth' => $requestsByMonth,
            'topRequirements' => $topRequirements,
            'topForms' => $topForms,
            'avgResponseTime' => round($avgResponseTime, 1),
            'reviewsThisMonth' => $reviewsThisMonth,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function showContribuyente(string $code)
    {
        $patentRequest = PatentRequest::where('code', $code)
            ->where('user_id', auth()->id())
            ->with([
                'user',
                'reviewer',
                'history.user',
                'forms.patentForm',
                'requirements.patentRequirement'
            ])
            ->firstOrFail();

        return Inertia::render('contribuyente/patentes/Show', [
            'patentRequest' => $patentRequest,
        ]);
    }

    public function showRentas(string $code)
    {
        $patentRequest = PatentRequest::where('code', $code)
            ->with([
                'user',
                'reviewer'
            ])->firstOrFail();

        return Inertia::render('funcionario/rentas/patentes/Show', [
            'patentRequest' => $patentRequest,
        ]);
    }

    public function approve(string $code)
    {
        $patentRequest = PatentRequest::where('code', $code)->firstOrFail();

        // Validate that request is still pending
        if ($patentRequest->state !== 'pending') {
            return redirect()
                ->route('funcionario.rentas.patentes.show', $patentRequest->code)
                ->with('error', 'Esta solicitud ya ha sido procesada y no puede ser aprobada nuevamente.');
        }

        DB::transaction(function () use ($patentRequest) {

            $patentRequest->update([
                'state' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            $patentRequest->history()->create([
                'patent_request_id' => $patentRequest->id,
                'action' => 'approved',
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('funcionario.rentas.patentes.index', $patentRequest->code)
            ->with('success', 'Solicitud aprobada.');
    }

    /**
     * Show approve form with document selection
     */
    public function approveForm(string $code)
    {
        // Get active forms and external documents
        $patentRequest = PatentRequest::where('code', $code)->firstOrFail();

        // Validate that request is still pending
        if ($patentRequest->state !== 'pending') {
            return redirect()
                ->route('funcionario.rentas.patentes.show', $patentRequest->code)
                ->with('error', 'Esta solicitud ya ha sido procesada y no puede ser aprobada nuevamente.');
        }

        $forms = PatentForm::where('is_active', true)->get();
        $requirements = PatentRequirement::where('is_active', true)->get();

        return Inertia::render('funcionario/rentas/patentes/Approve', [
            'patentRequest' => $patentRequest->load('user'),
            'forms' => $forms,
            'requirements' => $requirements,
        ]);
    }
    public function approveWithDocuments(Request $request, string $code)
    {
        $patentRequest = PatentRequest::where('code', $code)->firstOrFail();

        // Validate that request is still pending
        if ($patentRequest->state !== 'pending') {
            return redirect()
                ->route('funcionario.rentas.patentes.show', $patentRequest->code)
                ->with('error', 'Esta solicitud ya ha sido procesada y no puede ser aprobada nuevamente.');
        }
        
        $validated = $request->validate([
            'forms' => 'array',
            'forms.*' => 'exists:patent_forms,id',
            'requirements' => 'array',
            'requirements.*' => 'exists:patent_requirements,id',
        ]);


        DB::transaction(function () use ($patentRequest, $validated) {
            // Update request status
            $patentRequest->update([
                'state' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            // Attach selected forms
            if (!empty($validated['forms'])) {
                foreach ($validated['forms'] as $formId) {
                    $patentRequest->forms()->create([
                        'patent_form_id' => $formId,
                        'attached_by' => Auth::id(),
                    ]);
                }
            }

            // Attach selected requirements
            if (!empty($validated['requirements'])) {
                foreach ($validated['requirements'] as $reqId) {
                    $patentRequest->requirements()->create([
                        'patent_requirement_id' => $reqId,
                        'observations' => null,
                        'attached_by' => Auth::id(),
                    ]);
                }
            }

            // Create history record
            $patentRequest->history()->create([
                'patent_request_id' => $patentRequest->id,
                'action' => 'approved',
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('funcionario.rentas.patentes.show', $patentRequest->code)
            ->with('success', 'Solicitud aprobada con documentos requeridos.');
    }

    /**
     * Reject patent request without observations
     */
    public function reject(Request $request, string $code)
    {
        $patentRequest = PatentRequest::where('code', $code)->firstOrFail();

        // Validate that request is still pending
        if ($patentRequest->state !== 'pending') {
            return redirect()
                ->route('funcionario.rentas.patentes.show', $patentRequest->code)
                ->with('error', 'Esta solicitud ya ha sido procesada.');
        }

        DB::transaction(function () use ($patentRequest) {
            // Update request status
            $patentRequest->update([
                'state' => 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            // Create history record
            $patentRequest->history()->create([
                'patent_request_id' => $patentRequest->id,
                'action' => 'rejected',
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('funcionario.rentas.patentes.index', $patentRequest->code)
            ->with('success', 'Solicitud rechazada.');
    }

    /**
     * Reject patent request with observations
     */
    public function rejectWithObservations(Request $request, string $code)
    {
        $patentRequest = PatentRequest::where('code', $code)->firstOrFail();

        // Validate that request is still pending
        if ($patentRequest->state !== 'pending') {
            return redirect()
                ->route('funcionario.rentas.patentes.show', $patentRequest->code)
                ->with('error', 'Esta solicitud ya ha sido procesada.');
        }

        $validated = $request->validate([
            'observations' => 'required|string|min:10|max:1000',
        ]);

        DB::transaction(function () use ($patentRequest, $validated) {
            // Update request status
            $patentRequest->update([
                'state' => 'rejected_with_observations',
                'observations' => $validated['observations'],
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            // Create history record
            $patentRequest->history()->create([
                'patent_request_id' => $patentRequest->id,
                'action' => 'rejected_with_observations',
                'user_id' => Auth::id(),
                'observations' => $validated['observations'],
            ]);
        });

        return redirect()->route('funcionario.rentas.patentes.index', $patentRequest->code)
            ->with('success', 'Solicitud rechazada con observaciones.');
    }
}
