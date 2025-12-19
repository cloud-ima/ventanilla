<?php

namespace App\Http\Controllers;

use App\Models\PatentRequirement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatentRequirementController
{
    public function index(Request $request)
    {
        $query = PatentRequirement::with('creator');

        // Filtrar por nombre si se proporciona
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filtrar por categoría si se proporciona
        if ($request->has('category') && $request->get('category')) {
            $category = $request->get('category');
            $query->where('category', $category);
        }

        $requirements = $query
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(10);

        return Inertia::render('funcionario/rentas/requirements/Index', [
            'requirements' => $requirements,
        ]);
    }

    public function create()
    {
        return Inertia::render('funcionario/rentas/requirements/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:patent_requirements',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:municipal,sanitario,legal,profesional,financiero,transporte,educacion,seguridad,otros',
            'where_to_obtain' => 'required|string|max:255',
            'obtain_address' => 'nullable|string|max:255',
            'obtain_phone' => 'nullable|string|max:50',
            'info_url' => 'nullable|url|max:500',
            'validity_days' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        
        PatentRequirement::create($validated);

        return redirect()
            ->route('funcionario.rentas.requirements.index')
            ->with('success', 'Requerimiento creado correctamente.');
    }


    public function edit(PatentRequirement $requirement)
    {
        return Inertia::render('funcionario/rentas/requirements/Edit', [
            'requirement' => $requirement,
        ]);
    }

    public function update(Request $request, PatentRequirement $requirement)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:patent_requirements,code,' . $requirement->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:municipal,sanitario,legal,profesional,financiero,transporte,educacion,seguridad,otros',
            'where_to_obtain' => 'required|string|max:255',
            'obtain_address' => 'nullable|string|max:255',
            'obtain_phone' => 'nullable|string|max:50',
            'info_url' => 'nullable|url|max:500',
            'validity_days' => 'nullable|integer|min:1',
            'is_active' => 'boolean|required',
        ]);

        $requirement->update($validated);

        return redirect()
            ->route('funcionario.rentas.requirements.index')
            ->with('success', 'Requerimiento actualizado correctamente.');
    }

    public function destroy(PatentRequirement $patentRequirement)
    {
        $patentRequirement->delete();

        return redirect()
            ->route('funcionario.rentas.requirements.index')
            ->with('success', 'Requerimiento eliminado correctamente.');
    }
}
