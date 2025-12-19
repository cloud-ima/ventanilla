<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDepartment
{
    public function handle(Request $request, Closure $next, string $departmentSlug): Response
    {
        $user = $request->user();
        
        if (!$user || $user->role !== 'funcionario') {
            return abort(403, 'Acceso denegado: No eres funcionario');
        }

        // Compara el slug del usuario con el que viene en la ruta
        if (!$user->department || $user->department->slug !== $departmentSlug) {
            return abort(403, "Acceso denegado: No perteneces al departamento de " . ucfirst($departmentSlug));
        }

        return $next($request);
    }
}
