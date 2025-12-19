<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Procedure;
use Inertia\Inertia;

class PortalController
{
    public function index()
    {
        $departments = Department::withCount('procedures')->get();

        return Inertia::render('portal/Index', [
            'departments' => $departments,
        ]);
    }

    public function search()
    {
        $query = request('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $procedures = Procedure::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['category.department'])
            ->limit(10)
            ->get()
            ->map(function ($procedure) {
                return [
                    'id' => $procedure->id,
                    'name' => $procedure->name,
                    'description' => $procedure->description,
                    'category' => $procedure->category->name,
                    'department' => $procedure->category->department->name,
                    'url' => route('portal.procedure', [
                        'department' => $procedure->category->department->slug,
                        'category' => $procedure->category->slug,
                        'procedure' => $procedure->slug,
                    ]),
                ];
            });

        return response()->json($procedures);
    }

    public function showDepartment(string $departmentSlug)
    {
        $department = Department::where('slug', $departmentSlug)->firstOrFail();

        if (!$department) {
            return redirect()->route('home');
        }

        $categories = Category::where('department_id', $department->id)
            ->where('is_active', true)
            ->withCount(['procedures' => function ($procedureQuery) {
                $procedureQuery->where('is_active', true);
            }])
            ->orderBy('order')
            ->get();

        if (!$categories) {
            return redirect()->route('home');
        }

        return Inertia::render('portal/departments/Show', [
            'department' => $department,
            'categories' => $categories
        ]);
    }

    public function showCategory(string $departmentSlug, string $categorySlug)
    {
        $department = Department::where('slug', $departmentSlug)->firstOrFail();

        if (!$department) {
            return redirect()->route('home');
        }

        $category = Category::where('department_id', $department->id)
            ->where('slug', $categorySlug)
            ->where('is_active', true)
            ->with(['procedures' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->firstOrFail();

        if (!$category) {
            return redirect()->route('home');
        }

        return Inertia::render('portal/categories/Show', [
            'department' => $department,
            'category' => $category
        ]);
    }

    public function showProcedure(string $departmentSlug, string $categorySlug, string $procedureSlug)
    {
        $department = Department::where('slug', $departmentSlug)->first();
        if (!$department) {
            return redirect()->route('home');
        }

        $category = Category::where('department_id', $department->id)
            ->where('slug', $categorySlug)
            ->where('is_active', true)
            ->first();
        if (!$category) {
            return redirect()->route('home');
        }

        $procedure = Procedure::where('category_id', $category->id)
            ->where('slug', $procedureSlug)
            ->where('is_active', true)
            ->with('requirements')
            ->first();
        if (!$procedure) {
            return redirect()->route('home');
        }

        return Inertia::render('portal/procedure/Show', [
            'department' => $department,
            'category' => $category,
            'procedure' => $procedure
        ]);
    }
}
