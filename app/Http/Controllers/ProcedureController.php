<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Procedure;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProcedureController
{
    public function index()
    {
        $procedures = Procedure::with('category.department')
            ->withCount('requirements')
            ->paginate(7);

        return Inertia::render('admin/procedures/Index', [
            'response' => $procedures,
        ]);
    }

    public function create()
    {
        $categories = Category::with('department')
            ->active()
            ->orderBy('name')
            ->get();

        $requirements = Requirement::orderBy('name')->get();

        return Inertia::render('admin/procedures/Create', [
            'categories' => $categories,
            'requirements' => $requirements,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('procedures')->withoutTrashed()],
            'description' => 'nullable|string',
            'modality' => 'required|in:online,presencial,mixto',
            'cost' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'user_requirements' => 'required|array|min:1',
            'user_requirements.*' => 'filled|string|max:500',
            'instructions' => 'required|array|min:1',
            'instructions.*' => 'filled|string|max:500',
            'legal_framework' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'requirements' => 'required|array|min:1',
            'requirements.*.id' => 'exists:requirements,id',
            'requirements.*.is_mandatory' => 'boolean',
        ], [
            'requirements.required' => 'Debes seleccionar al menos un requisito.',
            'requirements.min' => 'Debes seleccionar al menos un requisito.',
            'user_requirements.required' => 'Debes agregar al menos una condición del usuario.',
            'user_requirements.min' => 'Debes agregar al menos una condición del usuario.',
            'instructions.required' => 'Debes agregar al menos una instrucción.',
            'instructions.min' => 'Debes agregar al menos una instrucción.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $procedure = Procedure::create($validated);

        // Sincronizar requisitos
        if (!empty($validated['requirements'])) {
            $syncData = [];
            foreach ($validated['requirements'] as $req) {
                $syncData[$req['id']] = [
                    'is_mandatory' => (bool) ($req['is_mandatory'] ?? true),
                ];
            }
            $procedure->requirements()->sync($syncData);
        }

        return redirect()->route('admin.procedures.index')
            ->with('success', 'Trámite creado correctamente.');
    }

    public function edit(Procedure $procedure)
    {
        $procedure->load('requirements');
        $requirements = Requirement::orderBy('name')->get();

        $categories = Category::with('department')
            ->active()
            ->orderBy('name')
            ->get()
            ->groupBy('department.name') // Agrupar por nombre del departamento
            ->map(fn($cats) => $cats->values()); // Reindexar las colecciones internas


        return Inertia::render('admin/procedures/Edit', [
            'procedure' => $procedure,
            'categories' => $categories,
            'requirements' => $requirements,
        ]);
    }

    public function update(Request $request, Procedure $procedure)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('procedures')->withoutTrashed()->ignore($procedure->id)],
            'description' => 'nullable|string',
            'modality' => 'required|in:online,presencial,mixto',
            'cost' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'user_requirements' => 'nullable|array',
            'user_requirements.*' => 'filled|string|max:500',
            'instructions' => 'nullable|array',
            'instructions.*' => 'filled|string|max:500',
            'legal_framework' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'requirements' => 'required|array|min:1',
            'requirements.*.id' => 'exists:requirements,id',
            'requirements.*.is_mandatory' => 'boolean',
        ], [
            'requirements.required' => 'Debes seleccionar al menos un requisito.',
            'requirements.min' => 'Debes seleccionar al menos un requisito.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Extraer requisitos antes de update (no es campo de la tabla)
        $requirementsData = $validated['requirements'];
        unset($validated['requirements']);

        $procedure->update($validated);

        // Sincronizar requisitos
        $syncData = [];
        foreach ($requirementsData as $req) {
            $syncData[$req['id']] = [
                'is_mandatory' => (bool) ($req['is_mandatory'] ?? true),
            ];
        }
        $procedure->requirements()->sync($syncData);

        return redirect()->route('admin.procedures.index')
            ->with('success', 'Trámite actualizado correctamente.');
    }

    public function destroy(Procedure $procedure)
    {
        $procedure->delete();

        return redirect()->route('admin.procedures.index');
    }
}
