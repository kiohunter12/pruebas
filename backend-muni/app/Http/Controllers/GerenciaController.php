<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GerenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        // Permisos específicos para cada acción
        $this->middleware(['permission:gestionar_gerencias'])->only(['create', 'store']);
        $this->middleware(['permission:editar_gerencias'])->only(['edit', 'update', 'toggleStatus']);
        $this->middleware(['permission:eliminar_gerencias'])->only(['destroy']);
        // index y show son accesibles para usuarios autenticados con cualquier permiso relacionado a gerencias
    }

    /**
     * Display a listing of gerencias.
     */
    public function index(Request $request)
    {
        $query = Gerencia::with(['subgerencias', 'users', 'gerenciaPadre'])
                        ->withCount('users');

        // Filtro de búsqueda por nombre o código
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('activo', $request->estado == '1');
        }

        // Filtro por gerencia padre (para subgerencias)
        if ($request->filled('gerencia_padre')) {
            $query->where('gerencia_padre_id', $request->gerencia_padre);
        }

        $gerencias = $query->orderBy('orden')
                          ->orderBy('nombre')
                          ->paginate(15)
                          ->appends($request->except('page'));

        $stats = [
            'total_gerencias' => Gerencia::count(),
            'gerencias_activas' => Gerencia::where('activo', true)->count(),
            'subgerencias_total' => Gerencia::where('tipo', 'subgerencia')->count(),
            'total_usuarios_asignados' => User::whereNotNull('gerencia_id')->count()
        ];

        // Si es una solicitud de API, devolver JSON
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $gerencias,
                'stats' => $stats
            ]);
        }

        // Si es una solicitud web, devolver vista
        return view('gerencias.index', compact('gerencias', 'stats'));
    }

    /**
     * Show the form for creating a new gerencia.
     */
    public function create()
    {
        $gerenciasPadre = Gerencia::where('tipo', 'gerencia')
                                 ->where('activo', true)
                                 ->orderBy('nombre')
                                 ->get();

        return view('gerencias.create', compact('gerenciasPadre'));
    }

    /**
     * Store a newly created gerencia.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:gerencias,nombre',
            'codigo' => 'required|string|max:10|unique:gerencias,codigo',
            'descripcion' => 'nullable|string|max:500',
            'tipo' => ['required', Rule::in(['gerencia', 'subgerencia'])],
            'gerencia_padre_id' => 'nullable|exists:gerencias,id',
            'activo' => 'boolean',
            'orden' => 'nullable|integer|min:1',
            'flujos_permitidos' => 'nullable|array',
            'flujos_permitidos.*' => 'string'
        ]);

        // Si es subgerencia, verificar que tenga gerencia padre
        if ($validated['tipo'] === 'subgerencia' && empty($validated['gerencia_padre_id'])) {
            return back()->withErrors(['gerencia_padre_id' => 'Las subgerencias deben tener una gerencia padre.']);
        }

        // Si es gerencia principal, no puede tener padre
        if ($validated['tipo'] === 'gerencia') {
            $validated['gerencia_padre_id'] = null;
        }

        // Auto-generar orden si no se especifica
        if (empty($validated['orden'])) {
            $validated['orden'] = Gerencia::max('orden') + 1;
        }

        DB::beginTransaction();
        try {
            $gerencia = Gerencia::create($validated);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Gerencia creada exitosamente',
                    'data' => $gerencia->load('gerenciaPadre')
                ], 201);
            }

            return redirect()->route('gerencias.index')
                           ->with('success', 'Gerencia creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la gerencia: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Error al crear la gerencia.']);
        }
    }

    /**
     * Display the specified gerencia.
     */
    public function show(Gerencia $gerencia)
    {
        $gerencia->load(['subgerencias', 'users.roles', 'gerenciaPadre']);
        
        // Agregar contadores para las vistas
        $gerencia->loadCount(['users', 'subgerencias']);
        
        $stats = [
            'total_usuarios' => $gerencia->users()->count(),
            'usuarios_activos' => $gerencia->users()->count(), // Todos los usuarios por ahora
            'expedientes_procesados' => 0, // Por ahora 0, se implementará cuando tengamos expedientes
        ];

        // Si es una solicitud de API, devolver JSON
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $gerencia,
                'stats' => $stats
            ]);
        }

        // Si es una solicitud web, devolver vista
        return view('gerencias.show', compact('gerencia', 'stats'));
    }

    /**
     * Show the form for editing the specified gerencia.
     */
    public function edit(Gerencia $gerencia)
    {
        $gerenciasPadre = Gerencia::where('tipo', 'gerencia')
                                 ->where('activo', true)
                                 ->where('id', '!=', $gerencia->id)
                                 ->orderBy('nombre')
                                 ->get();

        return view('gerencias.edit', compact('gerencia', 'gerenciasPadre'));
    }

    /**
     * Update the specified gerencia.
     */
    public function update(Request $request, Gerencia $gerencia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:gerencias,nombre,' . $gerencia->id,
            'codigo' => 'required|string|max:10|unique:gerencias,codigo,' . $gerencia->id,
            'descripcion' => 'nullable|string|max:500',
            'tipo' => ['required', Rule::in(['gerencia', 'subgerencia'])],
            'gerencia_padre_id' => 'nullable|exists:gerencias,id',
            'activo' => 'boolean',
            'orden' => 'nullable|integer|min:1',
            'flujos_permitidos' => 'nullable|array',
            'flujos_permitidos.*' => 'string'
        ]);

        // Si es subgerencia, verificar que tenga gerencia padre
        if ($validated['tipo'] === 'subgerencia' && empty($validated['gerencia_padre_id'])) {
            return back()->withErrors(['gerencia_padre_id' => 'Las subgerencias deben tener una gerencia padre.']);
        }

        // Si es gerencia principal, no puede tener padre
        if ($validated['tipo'] === 'gerencia') {
            $validated['gerencia_padre_id'] = null;
        }

        // No puede ser padre de sí misma
        if ($validated['gerencia_padre_id'] == $gerencia->id) {
            return back()->withErrors(['gerencia_padre_id' => 'Una gerencia no puede ser padre de sí misma.']);
        }

        DB::beginTransaction();
        try {
            $gerencia->update($validated);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Gerencia actualizada exitosamente',
                    'data' => $gerencia->load('gerenciaPadre')
                ]);
            }

            return redirect()->route('gerencias.index')
                           ->with('success', 'Gerencia actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la gerencia: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Error al actualizar la gerencia.']);
        }
    }

    /**
     * Remove the specified gerencia from storage.
     */
    public function destroy(Gerencia $gerencia)
    {
        // Verificar que no tenga usuarios asignados
        if ($gerencia->users()->count() > 0) {
            $message = 'No se puede eliminar la gerencia porque tiene usuarios asignados.';
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }

            return back()->withErrors(['error' => $message]);
        }

        // Verificar que no tenga subgerencias
        if ($gerencia->subgerencias()->count() > 0) {
            $message = 'No se puede eliminar la gerencia porque tiene subgerencias asociadas.';
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }

            return back()->withErrors(['error' => $message]);
        }

        // Verificar que no esté siendo utilizada en workflow_rules
        $workflowRulesCount = DB::table('workflow_rules')
                               ->where('gerencia_destino_id', $gerencia->id)
                               ->count();

        if ($workflowRulesCount > 0) {
            $message = 'No se puede eliminar la gerencia porque está siendo utilizada en reglas de flujo de trabajo.';
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }

            return back()->withErrors(['error' => $message]);
        }

        // Verificar referencias en otras tablas críticas
        $referencias = [];
        
        // Verificar mesa_partes
        if (DB::table('mesa_partes')->where('gerencia_destino_id', $gerencia->id)->exists()) {
            $referencias[] = 'registros de mesa de partes';
        }
        
        // Verificar tipo_tramites
        if (DB::table('tipo_tramites')->where('gerencia_id', $gerencia->id)->exists()) {
            $referencias[] = 'tipos de trámites';
        }
        
        // Verificar expedientes
        if (DB::table('expedientes')->where('gerencia_padre_id', $gerencia->id)->exists()) {
            $referencias[] = 'expedientes';
        }
        
        if (!empty($referencias)) {
            $message = 'No se puede eliminar la gerencia porque está siendo utilizada en: ' . implode(', ', $referencias) . '.';
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }

            return back()->withErrors(['error' => $message]);
        }

        DB::beginTransaction();
        try {
            $gerencia->delete();

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Gerencia eliminada exitosamente'
                ]);
            }

            return redirect()->route('gerencias.index')
                           ->with('success', 'Gerencia eliminada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log del error para debugging
            \Log::error('Error al eliminar gerencia ID: ' . $gerencia->id, [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la gerencia: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al eliminar la gerencia: ' . $e->getMessage()]);
        }
    }

    /**
     * Get subgerencias of a gerencia.
     */
    public function subgerencias(Gerencia $gerencia): JsonResponse
    {
        $subgerencias = $gerencia->subgerencias()
                                ->where('activo', true)
                                ->orderBy('orden')
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $subgerencias
        ]);
    }

    /**
     * Toggle status of gerencia
     */
    public function toggleStatus(Gerencia $gerencia)
    {
        DB::beginTransaction();
        try {
            $gerencia->update(['activo' => !$gerencia->activo]);
            
            DB::commit();

            $message = $gerencia->activo ? 'Gerencia activada exitosamente' : 'Gerencia desactivada exitosamente';

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $gerencia
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cambiar el estado: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al cambiar el estado.']);
        }
    }
}
