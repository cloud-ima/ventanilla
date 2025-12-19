<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryController
{
    public function index()
    {
        $response = Category::with('department')->withCount('procedures')->orderBy('order')->paginate(7);

        return Inertia::render('admin/categories/Index', [
            'response' => $response,
        ]);
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return Inertia::render('admin/categories/Create', [
            'departments' => $departments,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->withoutTrashed(),
            ],
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Auto-asignar orden si no se especifica
        if (!isset($validated['order']) || $validated['order'] === 0) {
            $validated['order'] = Category::where('department_id', $validated['department_id'])->max('order') + 1;
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category)
    {
        $departments = Department::orderBy('name')->get();

        return Inertia::render('admin/categories/Edit', [
            'category' => $category,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->withoutTrashed()->ignore($category->id),
            ],
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index');
    }
}
