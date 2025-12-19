<?php

namespace App\Http\Controllers;

use App\Models\PatentForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PatentFormController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = PatentForm::orderBy('name')
            ->paginate(7);

        return Inertia::render('funcionario/rentas/forms/Index', [
            'forms' => $forms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('funcionario/rentas/forms/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'template_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            $originalName = $file->getClientOriginalName();
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            
            // Reemplazar espacios con guiones bajos y limpiar caracteres especiales
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nameWithoutExt);
            $cleanName = preg_replace('/_+/', '_', $cleanName); // Reemplazar múltiples _ con uno solo
            $cleanName = trim($cleanName, '_'); // Eliminar _ al inicio o final
            
            // Generar nombre único: nombre_limpio_timestamp.extension
            $uniqueName = $cleanName . '_' . time() . '.' . $extension;
            
            $path = $file->storeAs('patent-forms', $uniqueName, 'public');
            $validated['template_file'] = $path;
        }

        $validated['created_by'] = Auth::id();
        
        PatentForm::create($validated);

        return redirect()
            ->route('funcionario.rentas.forms.index')
            ->with('success', 'Formulario creado correctamente.');
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
    public function edit(PatentForm $form)
    {
        // Verificar que el usuario sea el creador
        if ($form->created_by !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('funcionario/rentas/forms/Edit', [
            'form' => $form,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatentForm $form)
    {
 
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'template_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('template_file')) {
            // Eliminar archivo anterior
            if ($form->template_file) {
                Storage::disk('public')->delete($form->template_file);
            }
            
            $file = $request->file('template_file');
            $originalName = $file->getClientOriginalName();
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            
            // Reemplazar espacios con guiones bajos y limpiar caracteres especiales
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nameWithoutExt);
            $cleanName = preg_replace('/_+/', '_', $cleanName); // Reemplazar múltiples _ con uno solo
            $cleanName = trim($cleanName, '_'); // Eliminar _ al inicio o final
            
            // Generar nombre único: nombre_limpio_timestamp.extension
            $uniqueName = $cleanName . '_' . time() . '.' . $extension;
            
            $path = $file->storeAs('patent-forms', $uniqueName, 'public');
            $validated['template_file'] = $path;
        } else {
            // Mantener el archivo existente si no se sube uno nuevo
            unset($validated['template_file']);
        }

        $form->update($validated);

        return redirect()
            ->route('funcionario.rentas.forms.index')
            ->with('success', 'Formulario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatentForm $form)
    {
        // Verificar que el usuario sea el creador
        if ($form->created_by !== Auth::id()) {
            abort(403);
        }

        // Eliminar archivo
        if ($form->template_file) {
            Storage::disk('public')->delete($form->template_file);
        }

        $form->delete();

        return redirect()
            ->route('funcionario.rentas.forms.index')
            ->with('success', 'Formulario eliminado correctamente.');
    }
}
