<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class PublicOfficialController
{
    use PasswordValidationRules;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = User::with('department')->where('role', 'funcionario')->paginate(7);

        return Inertia::render('admin/public-officials/Index', [
            'response' => $response,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();

        return Inertia::render('admin/public-officials/Create', [
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => $this->passwordRules(),
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'boolean',
        ], $this->passwordMessages());

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'department_id' => $request->department_id,
            'role' => 'funcionario',
        ]);

        return redirect()->route('admin.public-officials.index')
            ->with('success', 'Funcionario creado correctamente.');
    }

    public function edit(User $publicOfficial)
    {
        $publicOfficial->load('department');
        $departments = Department::all();

        return Inertia::render('admin/public-officials/Edit', [
            'publicOfficial' => $publicOfficial,
            'departments' => $departments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $publicOfficial = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $publicOfficial->id,
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $publicOfficial->update($validated);

        return redirect()->route('admin.public-officials.index')
            ->with('success', 'Funcionario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $publicOfficial = User::findOrFail($id);

        $publicOfficial->delete();

        return redirect()->route('admin.public-officials.index');
    }
}
