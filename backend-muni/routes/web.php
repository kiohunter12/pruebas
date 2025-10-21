<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\GerenciaController;
use App\Http\Controllers\TipoTramiteController;
use App\Http\Controllers\TramiteController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WorkflowController;

/*
|--------------------------------------------------------------------------
| Web Routes - BLADE FRONTEND + ANGULAR COEXISTING
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Web)
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebController::class, 'login'])->name('login.post');
    Route::get('/register', [WebController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [WebController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');
    
    // Expedientes Additional Routes (must come before resource routes)
    Route::prefix('expedientes')->name('expedientes.')->group(function () {
        Route::get('/pendientes', [WebController::class, 'expedientesPendientes'])->name('pendientes');
        Route::get('/proceso', [WebController::class, 'expedientesProceso'])->name('proceso');
        Route::get('/finalizados', [WebController::class, 'expedientesFinalizados'])->name('finalizados');
    });
    
    // Expedientes Routes (Web Views)
    Route::resource('expedientes', ExpedienteController::class)->names([
        'index' => 'expedientes.index',
        'create' => 'expedientes.create',
        'store' => 'expedientes.store',
        'show' => 'expedientes.show',
        'edit' => 'expedientes.edit',
        'update' => 'expedientes.update',
        'destroy' => 'expedientes.destroy'
    ]);
    
    // Gerencias Routes (Web Views)
    Route::resource('gerencias', GerenciaController::class);
    Route::patch('/gerencias/{gerencia}/toggle-status', [GerenciaController::class, 'toggleStatus'])->name('gerencias.toggle-status');
    Route::get('/gerencias/{gerencia}/subgerencias', [GerenciaController::class, 'subgerencias'])->name('gerencias.subgerencias');

    // Tipos de Trámite Routes (Web Views)
    Route::resource('tipos-tramite', TipoTramiteController::class)->parameters([
        'tipos-tramite' => 'tipoTramite'
    ])->names([
        'index' => 'tipos-tramite.index',
        'create' => 'tipos-tramite.create',
        'store' => 'tipos-tramite.store',
        'show' => 'tipos-tramite.show',
        'edit' => 'tipos-tramite.edit',
        'update' => 'tipos-tramite.update',
        'destroy' => 'tipos-tramite.destroy'
    ]);
    Route::patch('/tipos-tramite/{tipoTramite}/toggle-status', [TipoTramiteController::class, 'toggleStatus'])->name('tipos-tramite.toggle-status');
    Route::get('/gerencias/{gerencia}/tipos-tramite', [TipoTramiteController::class, 'byGerencia'])->name('tipos-tramite.by-gerencia');

    // Workflows Routes (Web Views)
    Route::resource('workflows', \App\Http\Controllers\WorkflowController::class)->names([
        'index' => 'workflows.index',
        'create' => 'workflows.create',
        'store' => 'workflows.store',
        'show' => 'workflows.show',
        'edit' => 'workflows.edit',
        'update' => 'workflows.update',
        'destroy' => 'workflows.destroy'
    ]);

    // Workflow Steps Routes (nested under workflows)
    Route::prefix('workflows/{workflow}')->name('workflow-steps.')->group(function () {
        Route::get('/steps', [\App\Http\Controllers\WorkflowStepController::class, 'index'])->name('index');
        Route::get('/steps/create', [\App\Http\Controllers\WorkflowStepController::class, 'create'])->name('create');
        Route::post('/steps', [\App\Http\Controllers\WorkflowStepController::class, 'store'])->name('store');
        Route::get('/steps/{step}', [\App\Http\Controllers\WorkflowStepController::class, 'show'])->name('show');
        Route::get('/steps/{step}/edit', [\App\Http\Controllers\WorkflowStepController::class, 'edit'])->name('edit');
        Route::put('/steps/{step}', [\App\Http\Controllers\WorkflowStepController::class, 'update'])->name('update');
        Route::delete('/steps/{step}', [\App\Http\Controllers\WorkflowStepController::class, 'destroy'])->name('destroy');
    });

    // Workflow Transitions Routes (nested under workflows)
    Route::prefix('workflows/{workflow}')->name('workflow-transitions.')->group(function () {
        Route::get('/transitions', [\App\Http\Controllers\WorkflowTransitionController::class, 'index'])->name('index');
        Route::get('/transitions/create', [\App\Http\Controllers\WorkflowTransitionController::class, 'create'])->name('create');
        Route::post('/transitions', [\App\Http\Controllers\WorkflowTransitionController::class, 'store'])->name('store');
        Route::get('/transitions/{transition}', [\App\Http\Controllers\WorkflowTransitionController::class, 'show'])->name('show');
        Route::get('/transitions/{transition}/edit', [\App\Http\Controllers\WorkflowTransitionController::class, 'edit'])->name('edit');
        Route::put('/transitions/{transition}', [\App\Http\Controllers\WorkflowTransitionController::class, 'update'])->name('update');
        Route::delete('/transitions/{transition}', [\App\Http\Controllers\WorkflowTransitionController::class, 'destroy'])->name('destroy');
    });

    // Mesa de Partes ELIMINADO - Sistema usa Workflows automáticos
    // Route::prefix('mesa-partes')->name('mesa-partes.')->group(function () {
    //     Route::get('/', [WebController::class, 'mesaPartes'])->name('index');
    //     Route::get('/derivacion', [WebController::class, 'mesaPartesDerivacion'])->name('derivacion');
    //     Route::get('/registro', [WebController::class, 'mesaPartesRegistro'])->name('registro');
    // });
    
    // Trámites Routes (Admin)
    Route::resource('tramites', \App\Http\Controllers\TramiteController::class)->names([
        'index' => 'tramites.index',
        'create' => 'tramites.create',
        'store' => 'tramites.store',
        'show' => 'tramites.show',
        'edit' => 'tramites.edit',
        'update' => 'tramites.update',
        'destroy' => 'tramites.destroy'
    ]);
    
    // Rutas para Ciudadanos - Solicitar Trámites
    Route::prefix('ciudadano')->name('ciudadano.')->middleware(['role:ciudadano'])->group(function () {
        Route::get('/tramites', [\App\Http\Controllers\CiudadanoTramiteController::class, 'index'])->name('tramites.index');
        Route::get('/tramites/solicitar', [\App\Http\Controllers\CiudadanoTramiteController::class, 'create'])->name('tramites.create');
        Route::post('/tramites/solicitar', [\App\Http\Controllers\CiudadanoTramiteController::class, 'store'])->name('tramites.store');
        Route::get('/mis-tramites', [\App\Http\Controllers\CiudadanoTramiteController::class, 'misTramites'])->name('tramites.mis-tramites');
        Route::get('/mis-tramites/{id}', [\App\Http\Controllers\CiudadanoTramiteController::class, 'show'])->name('tramites.show');
        Route::get('/tramites/descargar-documento/{documentoId}', [\App\Http\Controllers\CiudadanoTramiteController::class, 'descargarDocumento'])->name('tramites.descargar-documento');
    });

    // Rutas para Gerencias - Trámites Asignados
    Route::prefix('gerencia')->name('gerencia.')->group(function () {
        Route::get('/mis-asignados', [\App\Http\Controllers\GerenciaTramiteController::class, 'misAsignados'])->name('tramites.mis-asignados');
        Route::get('/tramites/{id}', [\App\Http\Controllers\GerenciaTramiteController::class, 'show'])->name('tramites.show');
        Route::post('/tramites/{id}/avanzar', [\App\Http\Controllers\GerenciaTramiteController::class, 'avanzar'])->name('tramites.avanzar');
        Route::post('/tramites/{id}/aprobar', [\App\Http\Controllers\GerenciaTramiteController::class, 'aprobar'])->name('tramites.aprobar');
        Route::post('/tramites/{id}/rechazar', [\App\Http\Controllers\GerenciaTramiteController::class, 'rechazar'])->name('tramites.rechazar');
        Route::post('/tramites/{id}/observacion', [\App\Http\Controllers\GerenciaTramiteController::class, 'agregarObservacion'])->name('tramites.observacion');
    });
    
    // Reportes Routes
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [WebController::class, 'reportes'])->name('index');
        Route::get('/expedientes', [WebController::class, 'reportesExpedientes'])->name('expedientes');
        Route::get('/tramites', [WebController::class, 'reportesTramites'])->name('tramites');
        Route::get('/tiempos', [WebController::class, 'reportesTiempos'])->name('tiempos');
    });
    
    // Profile Routes
    Route::get('/profile', [WebController::class, 'profile'])->name('profile.show');
    Route::patch('/profile', [WebController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', [WebController::class, 'settings'])->name('settings');
    
    // Admin Routes
    Route::middleware(['role:superadministrador|administrador'])->group(function () {
        // Usuarios CRUD
        Route::resource('usuarios', UsuarioController::class);
        Route::patch('/usuarios/{usuario}/toggle-status', [UsuarioController::class, 'toggleStatus'])->name('usuarios.toggle-status');
        
        // Roles CRUD
        Route::resource('roles', RoleController::class);
        Route::post('/roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
        
        Route::get('/permisos', [WebController::class, 'permisos'])->name('permisos.index');
        Route::get('/configuracion', [WebController::class, 'configuracion'])->name('configuracion.index');
        
        // API Routes para la interfaz web (usan auth:web en lugar de auth:sanctum)
        Route::prefix('api')->group(function () {
            // Permisos
            Route::prefix('permissions')->group(function () {
                Route::get('/', [RolePermissionController::class, 'getPermissions']);
                Route::post('/', [RolePermissionController::class, 'createPermission']);
                Route::get('/{permission}', [RolePermissionController::class, 'getPermission']);
                Route::put('/{permission}', [RolePermissionController::class, 'updatePermission']);
                Route::delete('/{permission}', [RolePermissionController::class, 'deletePermission']);
            });
            
            // Roles
            Route::prefix('roles')->group(function () {
                Route::get('/', [RolePermissionController::class, 'getRoles']);
                Route::post('/', [RolePermissionController::class, 'createRole']);
                Route::get('/{role}', [RolePermissionController::class, 'getRole']);
                Route::put('/{role}', [RolePermissionController::class, 'updateRole']);
                Route::delete('/{role}', [RolePermissionController::class, 'deleteRole']);
            });
        });
    });
});

// Angular Routes (Catch-all for SPA) - Must be LAST
Route::get('/angular/{any}', function () {
    return view('welcome');
})->where('any', '.*')->name('angular');

// Fallback for other routes
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
