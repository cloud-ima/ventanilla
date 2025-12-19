<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DepartmentController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Department::withCount(['categories', 'users'])->paginate(7);

        return Inertia::render('admin/departments/Index', [
            'response' => $response,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/departments/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('departments')->withoutTrashed()],
            'icon' => 'required|string',
            'description' => 'required|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'color.regex' => 'El color debe ser un valor hexadecimal válido (ej: #3b82f6).',
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments.index')->with('success', 'Departamento creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);

        return Inertia::render('admin/departments/Edit', [
            'department' => $department,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'required|string',
        ], [
            'color.regex' => 'El color debe ser un valor hexadecimal válido (ej: #3b82f6).',
        ]);

        // No permitir cambiar el slug
        $department->update($validated);

        return redirect()->route('admin.departments.index')->with('success', 'Departamento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        // Verificar si tiene categorías asociadas
        $categoriesCount = $department->categories()->count();
        if ($categoriesCount > 0) {
            return redirect()->route('admin.departments.index')
                ->withErrors([
                    'error' => 'No se puede eliminar el departamento "' . $department->name .
                        '" porque tiene ' . $categoriesCount . ' categoría(s) asociada(s). ' .
                        'Elimina primero todas las categorías de este departamento.'
                ]);
        }

        // Verificar si tiene funcionarios asignados
        $usersCount = $department->users()->count();
        if ($usersCount > 0) {
            return redirect()->route('admin.departments.index')
                ->withErrors([
                    'error' => 'No se puede eliminar el departamento "' . $department->name .
                        '" porque tiene ' . $usersCount . ' funcionario(s) asignado(s). ' .
                        'Reasigna primero los funcionarios a otro departamento.'
                ]);
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departamento eliminado correctamente.');
    }
}
