<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RequirementController
{
    public function index()
    {

        // Solo mostrar requisitos activos con paginación
        $requirements = Requirement::withCount('procedures')
            ->orderBy('name')
            ->paginate(7);

        return Inertia::render('admin/requirements/Index', [
            'response' => $requirements,
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/requirements/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'how_to_obtain' => 'nullable|string',
        ]);

        // Siempre crear como activo
        $validated['is_active'] = true;

        Requirement::create($validated);

        return redirect()->route('admin.requirements.index')
            ->with('success', 'Requisito creado correctamente.');
    }

    public function edit(Requirement $requirement)
    {        
        return Inertia::render('admin/requirements/Edit', [
            'requirement' => $requirement,
        ]);
    }

    public function update(Request $request, Requirement $requirement)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'how_to_obtain' => 'nullable|string',
        ]);

        // No permitir cambiar is_active manualmente (solo se cambia en destroy)
        $requirement->update($validated);

        return redirect()->route('admin.requirements.index')
            ->with('success', 'Requisito actualizado correctamente.');
    }

    public function destroy(Requirement $requirement)
    {
        // Contar solo trámites activos (no soft-deleted)
        $tramitesActivos = $requirement->procedures()->count();

        // Si tiene trámites asociados ACTIVOS, NO se puede eliminar
        // Seguridad para evitar eliminar requisitos con trámites activos
        if ($tramitesActivos > 0) {
            return redirect()->route('admin.requirements.index')
                ->with('error', 'No se puede eliminar el requisito "' . $requirement->name .
                    '" porque está siendo usado por ' . $tramitesActivos . ' trámite(s) activo(s).');
        }

        // Si NO tiene trámites activos, marcar como inactivo
        $requirement->delete();

        // El flash se va controlar desde el componente, si es exitoso se lanza el toast desde el componente
        return redirect()->route('admin.requirements.index');
    }
}
