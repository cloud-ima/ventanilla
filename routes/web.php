<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PatentRequirementController;
use App\Http\Controllers\PatentFormController;
use App\Http\Controllers\PublicOfficialController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\Funcionario\Rentas\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PatentRequestController;

// Rutas Públicas del Portal 

Route::get('/', [PortalController::class, 'index'])->name('home');
Route::get('/search', [PortalController::class, 'search'])->name('search');
Route::get('/portal/{department}', [PortalController::class, 'showDepartment'])->name('portal.department');
Route::get('/portal/{department}/{category}', [PortalController::class, 'showCategory'])->name('portal.category');
Route::get('/portal/{department}/{category}/{procedure}', [PortalController::class, 'showProcedure'])->name('portal.procedure');

// Ruta para redirigir al dashboard según rol

Route::get('dashboard', function () {
    $user = auth()->user();

    $route = match ($user->role) {
        'admin' => 'admin.public-officials.index',
        'funcionario' => 'funcionario.rentas.patentes.index',
        default => 'contribuyente.patentes.index',
    };
    return redirect()->route($route);
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas Admin 

Route::middleware(['auth', 'verified', 'auth.active', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', fn() => redirect()->route('admin.public-officials.index'))->name('dashboard');
       // Route::get('/dashboard', fn() => Inertia::render('admin/Dashboard'))->name('dashboard');
        Route::resource('public-officials', PublicOfficialController::class)->except(['show']);
        Route::resource('departments', DepartmentController::class);

        // Gestión Portal
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('procedures', ProcedureController::class)->except(['show']);
        Route::resource('requirements', RequirementController::class)->except(['show']);
        
        // Catch-all: redirige CUALQUIER otra ruta a public-officials.index
        Route::any('{any}', fn() => redirect()->route('admin.public-officials.index'))->where('any', '.*');
    });

// Rutas Funcionario 

// Rentas
Route::middleware(['auth', 'verified', 'auth.active', 'department:rentas'])
    ->prefix('funcionario/rentas')
    ->name('funcionario.rentas.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Patentes Gestión Patentes
        Route::get('/patentes', [PatentRequestController::class, 'indexRentas'])->name('patentes.index');
        Route::get('patentes/{code}', [PatentRequestController::class, 'showRentas'])->name('patentes.show');
        Route::get('patentes/{code}/approve', [PatentRequestController::class, 'approveForm'])->name('patentes.approve.form');
        Route::post('patentes/{code}/approve', [PatentRequestController::class, 'approve'])->name('patentes.approve');
        Route::post('patentes/{code}/approve-with-documents', [PatentRequestController::class, 'approveWithDocuments'])->name('patentes.approve-with-documents');
        Route::post('patentes/{code}/reject', [PatentRequestController::class, 'reject'])->name('patentes.reject');
        Route::post('patentes/{code}/reject-with-observations', [PatentRequestController::class, 'rejectWithObservations'])->name('patentes.reject-observations');
        Route::get('/api/dashboard-stats', [PatentRequestController::class, 'getDashboardStats'])->name('patentes.dashboard-stats');

        // Gestión de Requerimientos
        Route::resource('requirements', PatentRequirementController::class);
        Route::resource('forms', PatentFormController::class);
    });


// Rutas Contribuyente
Route::middleware(['auth', 'verified', 'auth.active', 'role:contribuyente'])
    ->prefix('contribuyente')
    ->name('contribuyente.')
    ->group(function () {
        //Route::get('/dashboard', fn() => Inertia::render('contribuyente/Dashboard'))->name('dashboard');

        // Ver mis Patentes
        Route::get('patentes', [PatentRequestController::class, 'indexContribuyente'])->name('patentes.index');
        Route::get('patentes/create', [PatentRequestController::class, 'create'])->name('patentes.create');
        Route::post('patentes', [PatentRequestController::class, 'store'])->name('patentes.store');
        Route::get('patentes/{code}', [PatentRequestController::class, 'showContribuyente'])->name('patentes.show');
    });

require __DIR__ . '/settings.php';

// Catch-all global: Si ninguna ruta coincide, redirige al home
Route::any('{any}', fn() => redirect()->route('home'))->where('any', '.*');
