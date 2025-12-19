<?php

namespace App\Http\Controllers;

use App\Models\PatentExternalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PatentExternalDocumentController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = PatentExternalDocument::orderBy('name')
            ->paginate(7);

        return Inertia::render('funcionario/rentas/external-documents/Index', [
            'documents' => $documents,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('funcionario/rentas/external-documents/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'where_to_obtain' => 'required|string|max:1000',
            'obtain_address' => 'nullable|string|max:500',
            'info_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = Auth::id();
        
        PatentExternalDocument::create($validated);

        return redirect()
            ->route('funcionario.rentas.external-documents.index')
            ->with('success', 'Documento externo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PatentExternalDocument $externalDocument)
    {
        // Verificar que el usuario sea el creador
        if ($externalDocument->created_by !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('funcionario/rentas/external-documents/Edit', [
            'document' => $externalDocument,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatentExternalDocument $externalDocument)
    {
        // Verificar que el usuario sea el creador
        if ($externalDocument->created_by !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'where_to_obtain' => 'required|string|max:1000',
            'obtain_address' => 'nullable|string|max:500',
            'info_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
        ]);

        $externalDocument->update($validated);

        return redirect()
            ->route('funcionario.rentas.external-documents.index')
            ->with('success', 'Documento externo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatentExternalDocument $externalDocument)
    {
        // Verificar que el usuario sea el creador
        if ($externalDocument->created_by !== Auth::id()) {
            abort(403);
        }

        $externalDocument->delete();

        return redirect()
            ->route('funcionario.rentas.external-documents.index')
            ->with('success', 'Documento externo eliminado correctamente.');
    }
}
