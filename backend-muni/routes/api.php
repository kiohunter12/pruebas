<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExpedientController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\AdminGerenciaController;
use App\Http\Controllers\AuthController;
// MesaPartesController ELIMINADO - Sistema usa Workflows
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\WorkflowStepController;
use App\Http\Controllers\WorkflowTransitionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Convenciones:
| - Rutas públicas: prefijo /public o recursos abiertos de solo lectura
| - Rutas de formularios/catálogos: /form-data/* (solo desarrollo)
| - Rutas autenticadas: protegidas con auth:sanctum
| - Rutas administrativas: permisos o roles específicos
| - Evitar duplicidad en idiomas (/expedients vs /expedientes)
| - DEBUG ONLY: no usar en producción
*/

// -------------------------------------------------------------------------
// Health & Meta
// -------------------------------------------------------------------------

Route::get('/version', fn() => response()->json([
    'version' => app()->version(),
    'php'     => PHP_VERSION
]));



// -------------------------------------------------------------------------
// Rutas públicas (/public)
// -------------------------------------------------------------------------
Route::prefix('public')->group(function () {
    Route::get('/expedients/tracking/{trackingNumber}', [ExpedientController::class, 'getByTrackingNumber']);
    // Mesa de Partes ELIMINADO - Sistema usa Workflows automáticos
    // Route::get('/mesa-partes/seguimiento/{codigoSeguimiento}', [MesaPartesController::class, 'consultarPorCodigo']);
    // Route::get('/tipos-documento', [MesaPartesController::class, 'getTiposDocumento']);
    // Route::get('/tipos-tramite', [MesaPartesController::class, 'getTiposTramite']);
});

// -------------------------------------------------------------------------
// Rutas para trámites (accesibles con autenticación)
// -------------------------------------------------------------------------
Route::middleware(['auth:sanctum,web'])->group(function () {
    Route::get('/tramites/{id}/documentos', function($id) {
        $tipoTramite = \App\Models\TipoTramite::with('documentos')->find($id);
        
        if (!$tipoTramite) {
            return response()->json(['error' => 'Trámite no encontrado'], 404);
        }
        
        return response()->json([
            'success' => true,
            'documentos' => $tipoTramite->documentos->map(function($doc) {
                return [
                    'id' => $doc->id,
                    'nombre' => $doc->nombre,
                    'descripcion' => $doc->descripcion,
                    'codigo' => $doc->codigo,
                    'requiere_firma' => $doc->requiere_firma,
                    'requerido' => $doc->pivot->requerido ?? false,
                    'orden' => $doc->pivot->orden ?? 0
                ];
            })
        ]);
    });
});

// -------------------------------------------------------------------------
// Form-data (solo desarrollo)
// -------------------------------------------------------------------------
Route::prefix('form-data')->group(function () {
    Route::get('/gerencias', fn() => response()->json(['data' => \App\Models\Gerencia::select('id','nombre','codigo')->where('activo', true)->get()]));
    Route::get('/usuarios', fn() => response()->json(['data' => \App\Models\User::select('id','name','email','gerencia_id')->get()]));
    Route::get('/tipos-tramite', fn() => response()->json(['data' => \App\Models\TipoTramite::select('id','nombre','codigo','gerencia_id','costo')->where('activo', true)->get()]));
    Route::get('/tipos-documento', fn() => response()->json(['data' => \App\Models\TipoDocumento::select('id','nombre','codigo','requiere_firma')->where('activo', true)->get()]));
    Route::get('/roles', fn() => response()->json(['data' => \Spatie\Permission\Models\Role::select('id','name')->get()]));
    Route::get('/permisos', fn() => response()->json(['data' => \Spatie\Permission\Models\Permission::select('id','name')->get()]));
});


// -------------------------------------------------------------------------
// Autenticación (/auth)
// -------------------------------------------------------------------------
Route::prefix('auth')->group(function () {
    Route::post('/login',    [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user',    [AuthController::class, 'user']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/refresh',         [AuthController::class, 'refresh']);
    });
});

// -------------------------------------------------------------------------
// Protegidas con autenticación
// -------------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Usuario autenticado
    Route::get('/user', fn(Request $request) => $request->user()->load(['roles','permissions']));

    // -------------------- Expedientes (ES) --------------------
    Route::prefix('expedientes')->group(function () {
        Route::get('/', [ExpedienteController::class, 'index']);
        Route::post('/', [ExpedienteController::class, 'store'])->middleware('permission:registrar_expediente');
        Route::get('/{expediente}', [ExpedienteController::class, 'show']);
        Route::put('/{expediente}', [ExpedienteController::class, 'update'])->middleware('permission:editar_expediente');
        Route::delete('/{expediente}', [ExpedienteController::class, 'destroy'])->middleware('permission:eliminar_expediente');

        Route::post('/{expediente}/derivar',   [ExpedienteController::class, 'derivar'])->middleware('permission:derivar_expediente');
        Route::post('/{expediente}/aprobar',   [ExpedienteController::class, 'aprobar'])->middleware('permission:emitir_resolucion');
        Route::post('/{expediente}/rechazar',  [ExpedienteController::class, 'rechazar'])->middleware('permission:rechazar_expediente');
        Route::post('/{expediente}/documentos',[ExpedienteController::class, 'subirDocumento'])->middleware('permission:subir_documento');

        Route::get('/tipos-tramite', [ExpedienteController::class, 'getTiposTramite']);
        Route::get('/estados',       [ExpedienteController::class, 'getEstados']);
        Route::get('/estadisticas',  [ExpedienteController::class, 'getEstadisticas']);
        Route::get('/exportar',      [ExpedienteController::class, 'exportar']);
    });

    // -------------------- Expedients (EN) --------------------
    Route::prefix('expedients')->group(function () {
        Route::get('/', [ExpedientController::class, 'index']);
        Route::post('/', [ExpedientController::class, 'store']);
        Route::get('/{id}', [ExpedientController::class, 'show']);
        Route::put('/{id}', [ExpedientController::class, 'update']);
        Route::delete('/{id}', [ExpedientController::class, 'destroy']);

        Route::get('/{id}/files',      [ExpedientController::class, 'getFiles']);
        Route::post('/{id}/files',     [ExpedientController::class, 'uploadFile']);
        Route::get('/{id}/history',    [ExpedientController::class, 'getHistory']);
        Route::post('/{id}/assign',    [ExpedientController::class, 'assignToUser']);
        Route::post('/{id}/change-status', [ExpedientController::class, 'changeStatus']);
    });

    // -------------------- Workflows --------------------
    Route::prefix('workflows')->group(function () {
        Route::get('/', [WorkflowController::class, 'index'])->middleware('permission:gestionar_workflows');
        Route::post('/', [WorkflowController::class, 'store'])->middleware('permission:gestionar_workflows');
        Route::get('/{id}', [WorkflowController::class, 'show'])->middleware('permission:gestionar_workflows');
        Route::put('/{id}', [WorkflowController::class, 'update'])->middleware('permission:gestionar_workflows');
        Route::delete('/{id}', [WorkflowController::class, 'destroy'])->middleware('permission:gestionar_workflows');

        Route::post('/{id}/toggle', [WorkflowController::class, 'toggleActive'])->middleware('permission:gestionar_workflows');
        Route::post('/{id}/clone',  [WorkflowController::class, 'clone'])->middleware('permission:gestionar_workflows');
        Route::get('/tipo/{tipo}',  [WorkflowController::class, 'getByType'])->middleware('permission:gestionar_workflows');
    });

    Route::prefix('workflow-steps')->group(function () {
        Route::get('/', [WorkflowStepController::class, 'index'])->middleware('permission:gestionar_workflows');
        Route::post('/', [WorkflowStepController::class, 'store'])->middleware('permission:gestionar_workflows');
        Route::get('/{id}', [WorkflowStepController::class, 'show'])->middleware('permission:gestionar_workflows');
        Route::put('/{id}', [WorkflowStepController::class, 'update'])->middleware('permission:gestionar_workflows');
        Route::delete('/{id}', [WorkflowStepController::class, 'destroy'])->middleware('permission:gestionar_workflows');

        Route::post('/reorder', [WorkflowStepController::class, 'reorder'])->middleware('permission:gestionar_workflows');
    });

    Route::prefix('workflow-transitions')->group(function () {
        Route::get('/', [WorkflowTransitionController::class, 'index'])->middleware('permission:gestionar_workflows');
        Route::post('/', [WorkflowTransitionController::class, 'store'])->middleware('permission:gestionar_workflows');
        Route::get('/{id}', [WorkflowTransitionController::class, 'show'])->middleware('permission:gestionar_workflows');
        Route::put('/{id}', [WorkflowTransitionController::class, 'update'])->middleware('permission:gestionar_workflows');
        Route::delete('/{id}', [WorkflowTransitionController::class, 'destroy'])->middleware('permission:gestionar_workflows');
    });

    // -------------------- Gerencias --------------------
    Route::get('/gerencias', fn() => response()->json(['success' => true, 'data' => \App\Models\Gerencia::all()]));
    Route::prefix('gerencias/admin')->middleware('permission:gestionar_gerencias')->group(function () {
        Route::get('/', [AdminGerenciaController::class, 'index']);
        Route::get('/all', [AdminGerenciaController::class, 'getAll']);
        Route::post('/', [AdminGerenciaController::class, 'store']);
        Route::get('/{gerencia}', [AdminGerenciaController::class, 'show']);
        Route::put('/{gerencia}', [AdminGerenciaController::class, 'update']);
        Route::delete('/{gerencia}', [AdminGerenciaController::class, 'destroy']);

        Route::post('/{gerencia}/estado', [AdminGerenciaController::class, 'cambiarEstado']);
        Route::get('/{gerencia}/subgerencias', [AdminGerenciaController::class, 'getSubgerencias']);
        Route::get('/{gerencia}/usuarios',     [AdminGerenciaController::class, 'getUsuarios']);
        Route::post('/{gerencia}/usuarios',    [AdminGerenciaController::class, 'asignarUsuario']);
        Route::delete('/{gerencia}/usuarios/{user}', [AdminGerenciaController::class, 'removerUsuario']);
        Route::get('/{gerencia}/estadisticas', [AdminGerenciaController::class, 'getEstadisticas']);

        Route::get('/tipo/{tipo}',                [AdminGerenciaController::class, 'getPorTipo']);
        Route::get('/flujos-disponibles',         [AdminGerenciaController::class, 'getFlujosDisponibles']);
        Route::get('/jerarquia',                  [AdminGerenciaController::class, 'getJerarquia']);
        Route::get('/{gerencia}/puede-manejar/{tipoTramite}', [AdminGerenciaController::class, 'puedeManejarTramite']);
    });

    // -------------------- Usuarios --------------------
    Route::prefix('usuarios')->middleware('permission:gestionar_usuarios')->group(function () {
        Route::get('/', [AdminGerenciaController::class, 'getUsuarios']);
        Route::post('/', [AdminGerenciaController::class, 'createUsuario']);
        Route::get('/{user}', [AdminGerenciaController::class, 'getUsuario']);
        Route::put('/{user}', [AdminGerenciaController::class, 'updateUsuario']);
        Route::delete('/{user}', [AdminGerenciaController::class, 'deleteUsuario']);
    });
});
