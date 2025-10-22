<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Models\Gerencia;
use App\Models\Subgerencia;
use App\Models\DocumentoExpediente;
use App\Models\HistorialExpediente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ExpedienteController extends Controller
{
    /**
     * Display a listing of expedientes.
     */
    public function index(Request $request)
    {
        // Si es request AJAX o API, devolver JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->indexApi($request);
        }

        // Para vistas web, devolver vista
        $user = auth()->user();
        
        $query = Expediente::with([
            'gerencia', 
            'gerencia.gerenciaPadre', 
            'usuarioRegistro',
            'files'
        ]);

        // Filtros por permisos y gerencia del usuario
        // NOTA: mesa_partes eliminado - sistema usa workflows
        if ($user->hasRole('gerente_urbano') || $user->hasRole('inspector')) {
            if ($user->gerencia_id) {
                $query->where(function($q) use ($user) {
                    $q->where('gerencia_id', $user->gerencia_id)
                      ->orWhere('gerencia_padre_id', $user->gerencia_id);
                });
            }
        } elseif ($user->hasRole('secretaria_general')) {
            $query->where('requiere_informe_legal', true);
        } elseif ($user->hasRole('alcalde')) {
            $query->where('es_acto_administrativo_mayor', true);
        }

        // Aplicar filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_expediente', 'like', "%{$search}%")
                  ->orWhere('asunto', 'like', "%{$search}%")
                  ->orWhere('ciudadano_nombre', 'like', "%{$search}%")
                  ->orWhere('ciudadano_dni', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('gerencia_id')) {
            $query->where('gerencia_id', $request->gerencia_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $expedientes = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Estadísticas
        $stats = [
            'pendientes' => Expediente::where('estado', 'pendiente')->count(),
            'en_proceso' => Expediente::where('estado', 'en_proceso')->count(),
            'resueltos' => Expediente::whereIn('estado', ['aprobado', 'rechazado'])->count(),
        ];

        $gerencias = Gerencia::where('activo', true)->get();
        $tipos_tramite = \App\Models\TipoTramite::where('activo', true)->get();
    // Usar el scope definido en el modelo para filtrar reglas activas
    $workflows = \App\Models\WorkflowRule::activas()->get();

        return view('expedientes.index', compact(
            'expedientes', 
            'gerencias', 
            'tipos_tramite', 
            'workflows',
            'stats'
        ));
    }

    /**
     * API version of index method
     */
    private function indexApi(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Expediente::with([
            'gerencia', 
            'gerencia.gerenciaPadre', 
            'usuarioRegistro',
            'files'
        ]);

        // Filtros por permisos y gerencia del usuario
        // NOTA: mesa_partes eliminado - sistema usa workflows
        if ($user->hasRole('gerente_urbano') || $user->hasRole('inspector')) {
            // Usuarios de gerencia solo ven expedientes de su gerencia
            if ($user->gerencia_id) {
                $query->where(function($q) use ($user) {
                    $q->where('gerencia_id', $user->gerencia_id)
                      ->orWhere('gerencia_padre_id', $user->gerencia_id);
                });
            }
        } elseif ($user->hasRole('secretaria_general')) {
            $query->where('requiere_informe_legal', true);
        } elseif ($user->hasRole('alcalde')) {
            $query->where('es_acto_administrativo_mayor', true);
        }

        // Filtros adicionales
        if ($request->filled('estado')) {
            $query->porEstado($request->estado);
        }

        if ($request->filled('gerencia_id')) {
            $query->porGerencia($request->gerencia_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('solicitante_nombre', 'like', "%{$search}%")
                  ->orWhere('asunto', 'like', "%{$search}%");
            });
        }

        $expedientes = $query->orderBy('created_at', 'desc')
                            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $expedientes,
            'estados' => Expediente::getEstados(),
            'tipos_tramite' => Expediente::getTiposTramite(),
        ]);
    }

    /**
     * Store a newly created expediente (Ciudadano registra solicitud).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'solicitante_nombre' => 'required|string|max:255',
            'solicitante_dni' => 'required|string|max:20',
            'solicitante_email' => 'required|email|max:255',
            'solicitante_telefono' => 'required|string|max:20',
            'tipo_tramite' => 'required|string|in:' . implode(',', array_keys(Expediente::getTiposTramite())),
            'asunto' => 'required|string|max:500',
            'descripcion' => 'required|string',
            'gerencia_id' => 'required|exists:gerencias,id',
            'subgerencia_id' => 'nullable|exists:subgerencias,id',
            'documentos' => 'required|array|min:1',
            'documentos.*.nombre' => 'required|string|max:255',
            'documentos.*.tipo_documento' => 'required|string|in:' . implode(',', array_keys(DocumentoExpediente::getTiposDocumento())),
            'documentos.*.archivo' => 'required|file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            // Si es una solicitud web, redirigir con errores
            if (!request()->wantsJson() && !request()->is('api/*')) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Generar número de expediente
            $numero = $this->generarNumeroExpediente();

            // Crear expediente y asignar/iniciar workflow automáticamente
            $expediente = Expediente::crearConAsignacionAutomatica([
                'numero' => $numero,
                'solicitante_nombre' => $request->solicitante_nombre,
                'solicitante_dni' => $request->solicitante_dni,
                'solicitante_email' => $request->solicitante_email,
                'solicitante_telefono' => $request->solicitante_telefono,
                'tipo_tramite' => $request->tipo_tramite,
                'asunto' => $request->asunto,
                'descripcion' => $request->descripcion,
                'gerencia_id' => $request->gerencia_id,
                'subgerencia_id' => $request->subgerencia_id,
                'estado' => Expediente::ESTADO_PENDIENTE,
                'fecha_registro' => now(),
                'usuario_registro_id' => auth()->id(),
            ]);

            // Guardar documentos
            foreach ($request->file('documentos') as $documentoData) {
                $archivo = $documentoData['archivo'];
                $nombreArchivo = Str::random(40) . '.' . $archivo->getClientOriginalExtension();
                
                // Guardar archivo
                $path = $archivo->storeAs('documentos/expedientes/' . $expediente->id, $nombreArchivo, 'private');
                
                // Crear registro de documento
                DocumentoExpediente::create([
                    'expediente_id' => $expediente->id,
                    'nombre' => $documentoData['nombre'],
                    'tipo_documento' => $documentoData['tipo_documento'],
                    'archivo' => $path,
                    'extension' => $archivo->getClientOriginalExtension(),
                    'tamaño' => $archivo->getSize(),
                    'mime_type' => $archivo->getMimeType(),
                    'usuario_subio_id' => auth()->id(),
                    'requerido' => true,
                ]);
            }

            // Registrar en historial
            $this->registrarHistorial($expediente, 'crear', null, Expediente::ESTADO_PENDIENTE, 'Expediente creado por ciudadano');

            DB::commit();

            // Si es una solicitud web, redirigir con éxito
            if (!request()->wantsJson() && !request()->is('api/*')) {
                return redirect()->route('expedientes.show', $expediente->id)
                    ->with('success', 'Expediente registrado exitosamente. Número: ' . $numero);
            }

            return response()->json([
                'success' => true,
                'message' => 'Expediente registrado exitosamente',
                'data' => [
                    'numero' => $numero,
                    'id' => $expediente->id,
                    'estado' => Expediente::ESTADO_PENDIENTE,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Si es una solicitud web, redirigir con error
            if (!request()->wantsJson() && !request()->is('api/*')) {
                return redirect()->back()
                    ->with('error', 'Error al registrar expediente: ' . $e->getMessage())
                    ->withInput();
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar expediente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified expediente.
     */
    public function show(Expediente $expediente)
    {
        $expediente->load([
            'gerencia', 
            'subgerencia', 
            'usuarioRegistro',
            'usuarioRevisionTecnica',
            'usuarioRevisionLegal',
            'usuarioResolucion',
            'usuarioFirma',
            'documentos',
            'historial.usuario',
            'currentStep',
            'workflow.steps'
        ]);

        // Si es una solicitud de API, devolver JSON
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $expediente
            ]);
        }

        // Si es una solicitud web, devolver vista
        // Obtener progreso actual (si existe) con usuario asignado
        $currentProgress = null;
        if ($expediente->currentStep) {
            $currentProgress = $expediente->workflowProgress()
                ->where('workflow_step_id', $expediente->currentStep->id)
                ->whereIn('estado', ['pendiente', 'en_proceso'])
                ->with('asignado')
                ->first();
        }

        return view('expedientes.show', compact('expediente', 'currentProgress'));
    }

    /**
     * Update the specified expediente.
     */
    public function update(Request $request, Expediente $expediente)
    {
        $validator = Validator::make($request->all(), [
            'asunto' => 'sometimes|string|max:500',
            'descripcion' => 'sometimes|string',
            'observaciones' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            // Si es una solicitud web, redirigir con errores
            if (!request()->wantsJson() && !request()->is('api/*')) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $estadoAnterior = $expediente->estado;
        
        $expediente->update($request->only(['asunto', 'descripcion', 'observaciones']));

        $this->registrarHistorial($expediente, 'actualizar', $estadoAnterior, $expediente->estado, 'Expediente actualizado');

        // Si es una solicitud web, redirigir con éxito
        if (!request()->wantsJson() && !request()->is('api/*')) {
            return redirect()->route('expedientes.show', $expediente->id)
                ->with('success', 'Expediente actualizado exitosamente');
        }

        return response()->json([
            'success' => true,
            'message' => 'Expediente actualizado exitosamente',
            'data' => $expediente
        ]);
    }

    /**
     * Remove the specified expediente.
     */
    public function destroy(Expediente $expediente): JsonResponse
    {
        if (!$expediente->puedeSerEliminado()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar este expediente en su estado actual'
            ], 400);
        }

        $expediente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expediente eliminado exitosamente'
        ]);
    }

    /**
     * Derivar expediente a gerencia.
     * NOTA: Con workflows esto debería ser automático, pero se mantiene para casos especiales
     */
    public function derivar(Request $request, Expediente $expediente): JsonResponse
    {
        if (!$expediente->puedeSerDerivado()) {
            return response()->json([
                'success' => false,
                'message' => 'El expediente no puede ser derivado en su estado actual'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'gerencia_id' => 'required|exists:gerencias,id',
            'gerencia_padre_id' => 'nullable|exists:gerencias,id',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener la gerencia destino
        $gerenciaDestino = Gerencia::find($request->gerencia_id);
        
        // Verificar que la gerencia puede procesar este tipo de trámite
        if (!$expediente->puedeSerDerivadoAGerencia($gerenciaDestino)) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede derivar este expediente a la gerencia seleccionada. Tipo de trámite no permitido.'
            ], 400);
        }

        // Si se especifica gerencia_padre_id, verificar que sea válida
        if ($request->gerencia_padre_id) {
            $subgerencia = Gerencia::find($request->gerencia_padre_id);
            if (!$subgerencia || $subgerencia->tipo !== Gerencia::TIPO_SUBGERENCIA) {
                return response()->json([
                    'success' => false,
                    'message' => 'La gerencia padre especificada no es válida'
                ], 400);
            }
            
            // Verificar que la subgerencia pertenezca a la gerencia principal
            if ($subgerencia->gerencia_padre_id !== $request->gerencia_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La subgerencia no pertenece a la gerencia especificada'
                ], 400);
            }
        }

        $estadoAnterior = $expediente->estado;
        
        $expediente->update([
            'gerencia_id' => $request->gerencia_id,
            'gerencia_padre_id' => $request->gerencia_padre_id,
            'estado' => Expediente::ESTADO_EN_REVISION,
            'fecha_derivacion' => now(),
            'observaciones' => $request->observaciones,
        ]);

        $this->registrarHistorial($expediente, 'derivar', $estadoAnterior, Expediente::ESTADO_EN_REVISION, 'Expediente derivado a gerencia');

        return response()->json([
            'success' => true,
            'message' => 'Expediente derivado exitosamente',
            'data' => $expediente
        ]);
    }

    /**
     * Revisión técnica (Gerencia/Subgerencia).
     */
    public function revisionTecnica(Request $request, Expediente $expediente): JsonResponse
    {
        if (!$expediente->puedeSerRevisadoTecnicamente()) {
            return response()->json([
                'success' => false,
                'message' => 'El expediente no puede ser revisado técnicamente en su estado actual'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'requiere_informe_legal' => 'required|boolean',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $estadoAnterior = $expediente->estado;
        $nuevoEstado = $request->requiere_informe_legal ? 
            Expediente::ESTADO_REVISION_LEGAL : 
            Expediente::ESTADO_RESOLUCION_EMITIDA;
        
        $expediente->update([
            'estado' => $nuevoEstado,
            'fecha_revision_tecnica' => now(),
            'usuario_revision_tecnica_id' => auth()->id(),
            'requiere_informe_legal' => $request->requiere_informe_legal,
            'observaciones' => $request->observaciones,
        ]);

        $this->registrarHistorial($expediente, 'revision_tecnica', $estadoAnterior, $nuevoEstado, 'Revisión técnica completada');

        return response()->json([
            'success' => true,
            'message' => 'Revisión técnica completada exitosamente',
            'data' => $expediente
        ]);
    }

    /**
     * Revisión legal (Secretaría General).
     */
    public function revisionLegal(Request $request, Expediente $expediente): JsonResponse
    {
        if (!$expediente->puedeSerRevisadoLegalmente()) {
            return response()->json([
                'success' => false,
                'message' => 'El expediente no puede ser revisado legalmente en su estado actual'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'es_acto_administrativo_mayor' => 'required|boolean',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $estadoAnterior = $expediente->estado;
        $nuevoEstado = Expediente::ESTADO_RESOLUCION_EMITIDA;
        
        $expediente->update([
            'estado' => $nuevoEstado,
            'fecha_revision_legal' => now(),
            'usuario_revision_legal_id' => auth()->id(),
            'es_acto_administrativo_mayor' => $request->es_acto_administrativo_mayor,
            'observaciones' => $request->observaciones,
        ]);

        $this->registrarHistorial($expediente, 'revision_legal', $estadoAnterior, $nuevoEstado, 'Revisión legal completada');

        return response()->json([
            'success' => true,
            'message' => 'Revisión legal completada exitosamente',
            'data' => $expediente
        ]);
    }

    /**
     * Emitir resolución (Gerencia/Subgerencia o Secretaría General).
     */
    public function emitirResolucion(Request $request, Expediente $expediente): JsonResponse
    {
        if (!$expediente->puedeEmitirResolucion()) {
            return response()->json([
                'success' => false,
                'message' => 'El expediente no puede emitir resolución en su estado actual'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'numero_resolucion' => 'required|string|max:100',
            'archivo_resolucion' => 'required|file|mimes:pdf|max:10240',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $estadoAnterior = $expediente->estado;
        $nuevoEstado = $expediente->es_acto_administrativo_mayor ? 
            Expediente::ESTADO_PENDIENTE_FIRMA : 
            Expediente::ESTADO_RESOLUCION_EMITIDA;

        // Guardar archivo de resolución
        $archivo = $request->file('archivo_resolucion');
        $nombreArchivo = 'resolucion_' . $expediente->numero . '.' . $archivo->getClientOriginalExtension();
        $path = $archivo->storeAs('resoluciones', $nombreArchivo, 'private');
        
        $expediente->update([
            'estado' => $nuevoEstado,
            'fecha_resolucion' => now(),
            'usuario_resolucion_id' => auth()->id(),
            'numero_resolucion' => $request->numero_resolucion,
            'archivo_resolucion' => $path,
            'observaciones' => $request->observaciones,
        ]);

        $this->registrarHistorial($expediente, 'emitir_resolucion', $estadoAnterior, $nuevoEstado, 'Resolución emitida');

        return response()->json([
            'success' => true,
            'message' => 'Resolución emitida exitosamente',
            'data' => $expediente
        ]);
    }

    /**
     * Firmar resolución (Alcalde).
     */
    public function firmaResolucion(Request $request, Expediente $expediente): JsonResponse
    {
        if (!$expediente->puedeSerFirmado()) {
            return response()->json([
                'success' => false,
                'message' => 'El expediente no puede ser firmado en su estado actual'
            ], 400);
        }

        $estadoAnterior = $expediente->estado;
        $nuevoEstado = Expediente::ESTADO_FIRMADO;
        
        $expediente->update([
            'estado' => $nuevoEstado,
            'fecha_firma' => now(),
            'usuario_firma_id' => auth()->id(),
        ]);

        $this->registrarHistorial($expediente, 'firmar', $estadoAnterior, $nuevoEstado, 'Resolución firmada por alcalde');

        return response()->json([
            'success' => true,
            'message' => 'Resolución firmada exitosamente',
            'data' => $expediente
        ]);
    }

    /**
     * Notificar al ciudadano.
     */
    public function notificar(Request $request, Expediente $expediente): JsonResponse
    {
        if (!$expediente->puedeSerNotificado()) {
            return response()->json([
                'success' => false,
                'message' => 'El expediente no puede ser notificado en su estado actual'
            ], 400);
        }

        $estadoAnterior = $expediente->estado;
        $nuevoEstado = Expediente::ESTADO_NOTIFICADO;
        
        $expediente->update([
            'estado' => $nuevoEstado,
            'notificado_ciudadano' => true,
            'fecha_notificacion' => now(),
        ]);

        // Aquí se enviaría la notificación por email/SMS
        // TODO: Implementar notificación real
        
        $this->registrarHistorial($expediente, 'notificar', $estadoAnterior, $nuevoEstado, 'Ciudadano notificado');

        return response()->json([
            'success' => true,
            'message' => 'Ciudadano notificado exitosamente',
            'data' => $expediente
        ]);
    }

    /**
     * Rechazar expediente.
     */
    public function rechazar(Request $request, Expediente $expediente): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'motivo_rechazo' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $estadoAnterior = $expediente->estado;
        $nuevoEstado = Expediente::ESTADO_RECHAZADO;
        
        $expediente->update([
            'estado' => $nuevoEstado,
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        $this->registrarHistorial($expediente, 'rechazar', $estadoAnterior, $nuevoEstado, 'Expediente rechazado: ' . $request->motivo_rechazo);

        return response()->json([
            'success' => true,
            'message' => 'Expediente rechazado exitosamente',
            'data' => $expediente
        ]);
    }

    /**
     * Generar número único de expediente.
     */
    private function generarNumeroExpediente(): string
    {
        $anio = date('Y');
        $ultimoExpediente = Expediente::whereYear('created_at', $anio)
                                    ->orderBy('id', 'desc')
                                    ->first();
        
        if ($ultimoExpediente) {
            $ultimoNumero = (int) substr($ultimoExpediente->numero, -6);
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }
        
        return sprintf('EXP-%s-%06d', $anio, $nuevoNumero);
    }

    /**
     * Registrar acción en historial.
     */
    private function registrarHistorial(Expediente $expediente, string $accion, ?string $estadoAnterior, string $estadoNuevo, string $descripcion): void
    {
        HistorialExpediente::create([
            'expediente_id' => $expediente->id,
            'usuario_id' => auth()->id(),
            'accion' => $accion,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'descripcion' => $descripcion,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Obtener estadísticas de expedientes.
     */
    public function estadisticas(): JsonResponse
    {
        $user = auth()->user();
        
        $query = Expediente::query();
        
        // Filtrar por permisos del usuario
        // NOTA: mesa_partes eliminado - sistema usa workflows
        if ($user->hasRole('gerente_urbano')) {
            $query->where('gerencia_id', $user->gerencia_id ?? 0);
        }
        
        $estadisticas = [
            'total' => $query->count(),
            'pendientes' => $query->clone()->pendientes()->count(),
            'en_revision' => $query->clone()->porEstado(Expediente::ESTADO_EN_REVISION)->count(),
            'revision_tecnica' => $query->clone()->porEstado(Expediente::ESTADO_REVISION_TECNICA)->count(),
            'revision_legal' => $query->clone()->porEstado(Expediente::ESTADO_REVISION_LEGAL)->count(),
            'resolucion_emitida' => $query->clone()->porEstado(Expediente::ESTADO_RESOLUCION_EMITIDA)->count(),
            'firmados' => $query->clone()->porEstado(Expediente::ESTADO_FIRMADO)->count(),
            'notificados' => $query->clone()->porEstado(Expediente::ESTADO_NOTIFICADO)->count(),
            'rechazados' => $query->clone()->porEstado(Expediente::ESTADO_RECHAZADO)->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $estadisticas
        ]);
    }
    
    /**
     * Show the form for creating a new expediente (Web view)
     */
    public function create()
    {
        $gerencias = Gerencia::where('estado', 'activo')->get();
        $tiposTramite = TipoTramite::where('estado', 'activo')->get();
        $tiposDocumento = TipoDocumento::where('estado', 'activo')->get();
        
        return view('expedientes.create', compact('gerencias', 'tiposTramite', 'tiposDocumento'));
    }
    
    /**
     * Show the form for editing the specified expediente (Web view)
     */
    public function edit($id)
    {
        $expediente = Expediente::with([
            'gerencia',
            'tipoTramite',
            'documentos.tipoDocumento'
        ])->findOrFail($id);
        
        $gerencias = Gerencia::where('estado', 'activo')->get();
        $tiposTramite = TipoTramite::where('estado', 'activo')->get();
        $tiposDocumento = TipoDocumento::where('estado', 'activo')->get();
        
        return view('expedientes.edit', compact('expediente', 'gerencias', 'tiposTramite', 'tiposDocumento'));
    }
}
