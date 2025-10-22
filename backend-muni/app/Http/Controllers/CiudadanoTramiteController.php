<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expediente;
use App\Models\TipoTramite;
use App\Models\Gerencia;
use App\Models\DocumentoExpediente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CiudadanoTramiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ciudadano')->only(['create', 'store']);
    }

    /**
     * Mostrar lista de trámites disponibles para el ciudadano
     */
    public function index()
    {
        $tiposTramite = TipoTramite::with(['gerencia', 'documentos'])
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('ciudadano.tramites.index', compact('tiposTramite'));
    }

    /**
     * Mostrar formulario para solicitar un trámite
     */
    public function create(Request $request)
    {
        $tipoTramiteId = $request->query('tipo_tramite_id');
        
        if (!$tipoTramiteId) {
            return redirect()->route('ciudadano.tramites.index')
                ->with('error', 'Debe seleccionar un tipo de trámite.');
        }

        $tipoTramite = TipoTramite::with(['gerencia', 'documentos'])->findOrFail($tipoTramiteId);

        return view('ciudadano.tramites.create', compact('tipoTramite'));
    }

    /**
     * Almacenar una nueva solicitud de trámite
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_tramite_id' => 'required|exists:tipo_tramites,id',
            'asunto' => 'required|string|max:500',
            'descripcion' => 'required|string',
            'documentos' => 'nullable|array',
            'documentos.*.tipo_documento_id' => 'nullable|exists:tipo_documentos,id',
            'documentos.*.nombre' => 'required_with:documentos.*.archivo|string',
            'documentos.*.archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
            'documentos.*.requerido' => 'nullable|boolean',
            'documentos_opcionales' => 'nullable|array',
            'documentos_opcionales.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ], [
            'tipo_tramite_id.required' => 'Debe seleccionar un tipo de trámite.',
            'asunto.required' => 'El asunto es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'documentos.*.archivo.mimes' => 'Los documentos deben ser PDF, imágenes o documentos Word.',
            'documentos.*.archivo.max' => 'Cada documento no debe superar los 10MB.',
        ]);

        DB::beginTransaction();
        
        try {
            $tipoTramite = TipoTramite::with(['gerencia', 'documentos', 'workflow.steps'])->findOrFail($validated['tipo_tramite_id']);
            
            // Obtener workflow del tipo de trámite
            $workflow = $tipoTramite->workflow;
            
            // Si no hay workflow asignado al tipo de trámite, buscar por gerencia
            if (!$workflow) {
                $workflow = \App\Models\Workflow::where('activo', true)
                    ->where('gerencia_id', $tipoTramite->gerencia_id)
                    ->with('steps')
                    ->first();
            }
            
            // Generar número de expediente
            $numeroExpediente = $this->generarNumeroExpediente();
            
            // Obtener el primer paso del workflow si existe
            $primerPaso = null;
            if ($workflow) {
                // Buscar paso inicial (tipo = 'inicio') o el primer paso por orden
                $primerPaso = $workflow->steps()
                    ->where('tipo', 'inicio')
                    ->orderBy('orden')
                    ->first();
                
                // Si no hay paso tipo 'inicio', tomar el primer paso por orden
                if (!$primerPaso) {
                    $primerPaso = $workflow->steps()->orderBy('orden')->first();
                }
            }
            
            // Crear el expediente
            $expediente = Expediente::create([
                'numero' => $numeroExpediente,
                'tipo_tramite_id' => $tipoTramite->id,
                'workflow_id' => $workflow?->id,
                'current_step_id' => $primerPaso?->id,
                'solicitante_nombre' => auth()->user()->name,
                'solicitante_dni' => auth()->user()->dni ?? 'N/A',
                'solicitante_email' => auth()->user()->email,
                'solicitante_telefono' => auth()->user()->telefono ?? 'N/A',
                'tipo_tramite' => strtolower(str_replace(' ', '_', $tipoTramite->nombre)),
                'asunto' => $validated['asunto'],
                'descripcion' => $validated['descripcion'],
                'estado' => Expediente::ESTADO_PENDIENTE,
                'gerencia_id' => $tipoTramite->gerencia_id,
                'fecha_registro' => now(),
                'usuario_registro_id' => auth()->id(),
            ]);

            $documentosSubidos = 0;

            // Guardar documentos requeridos/específicos
            if (isset($validated['documentos']) && is_array($validated['documentos'])) {
                foreach ($validated['documentos'] as $docData) {
                    // Solo procesar si hay archivo
                    if (isset($docData['archivo']) && $docData['archivo'] instanceof \Illuminate\Http\UploadedFile) {
                        $file = $docData['archivo'];
                        
                        // Generar nombre único para el archivo
                        $nombreArchivo = \Str::random(40) . '.' . $file->getClientOriginalExtension();
                        
                        // Guardar archivo en storage privado
                        $path = $file->storeAs(
                            'documentos/expedientes/' . $expediente->id, 
                            $nombreArchivo, 
                            'private'
                        );
                        
                        // Crear registro de documento
                        DocumentoExpediente::create([
                            'expediente_id' => $expediente->id,
                            'nombre' => $docData['nombre'] ?? $file->getClientOriginalName(),
                            'tipo_documento' => $docData['tipo_documento_id'] ?? null,
                            'archivo' => $path,
                            'extension' => $file->getClientOriginalExtension(),
                            'tamaño' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'usuario_subio_id' => auth()->id(),
                            'requerido' => isset($docData['requerido']) && $docData['requerido'] == '1',
                        ]);
                        
                        $documentosSubidos++;
                    }
                }
            }

            // Guardar documentos opcionales
            if (isset($validated['documentos_opcionales']) && is_array($validated['documentos_opcionales'])) {
                foreach ($validated['documentos_opcionales'] as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        // Generar nombre único para el archivo
                        $nombreArchivo = \Str::random(40) . '.' . $file->getClientOriginalExtension();
                        
                        // Guardar archivo en storage privado
                        $path = $file->storeAs(
                            'documentos/expedientes/' . $expediente->id, 
                            $nombreArchivo, 
                            'private'
                        );
                        
                        // Crear registro de documento
                        DocumentoExpediente::create([
                            'expediente_id' => $expediente->id,
                            'nombre' => 'Documento opcional - ' . $file->getClientOriginalName(),
                            'tipo_documento' => null,
                            'archivo' => $path,
                            'extension' => $file->getClientOriginalExtension(),
                            'tamaño' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'usuario_subio_id' => auth()->id(),
                            'requerido' => false,
                        ]);
                        
                        $documentosSubidos++;
                    }
                }
            }

            // Registrar en historial
            \App\Models\HistorialExpediente::create([
                'expediente_id' => $expediente->id,
                'usuario_id' => auth()->id(),
                'accion' => 'crear',
                'estado_anterior' => null,
                'estado_nuevo' => Expediente::ESTADO_PENDIENTE,
                'descripcion' => "Expediente creado por ciudadano a través del portal web. Documentos adjuntos: {$documentosSubidos}",
            ]);

            // Si hay workflow, crear el progreso en el primer paso
            if ($workflow && $primerPaso) {
                // Determinar a quién asignar (responsable de la gerencia o usuario específico del paso)
                $asignadoA = null;
                
                // Si el paso tiene un rol específico, buscar usuario con ese rol en la gerencia
                if ($primerPaso->rol_requerido) {
                    $asignadoA = \App\Models\User::role($primerPaso->rol_requerido)
                        ->where('gerencia_id', $tipoTramite->gerencia_id)
                        ->first()?->id;
                }
                
                // Si no se encontró, buscar al jefe/responsable de la gerencia
                if (!$asignadoA && $tipoTramite->gerencia) {
                    $asignadoA = $tipoTramite->gerencia->responsable_id;
                }
                
                // Crear el progreso del workflow
                \App\Models\ExpedienteWorkflowProgress::create([
                    'expediente_id' => $expediente->id,
                    'workflow_step_id' => $primerPaso->id,
                    'estado' => \App\Models\ExpedienteWorkflowProgress::ESTADO_PENDIENTE,
                    'asignado_a' => $asignadoA,
                    'fecha_inicio' => now(),
                    'fecha_limite' => $primerPaso->tiempo_estimado 
                        ? now()->addDays($primerPaso->tiempo_estimado) 
                        : now()->addDays(30),
                    'comentarios' => 'Paso inicial del workflow - Expediente creado por ciudadano',
                ]);
                
                // Actualizar el estado del expediente si el workflow lo define
                if ($primerPaso->estado_expediente) {
                    $expediente->update(['estado' => $primerPaso->estado_expediente]);
                }
                
                // Registrar inicio del workflow en historial
                \App\Models\HistorialExpediente::create([
                    'expediente_id' => $expediente->id,
                    'usuario_id' => auth()->id(),
                    'accion' => 'iniciar_workflow',
                    'estado_anterior' => Expediente::ESTADO_PENDIENTE,
                    'estado_nuevo' => $primerPaso->estado_expediente ?? Expediente::ESTADO_PENDIENTE,
                    'descripcion' => "Workflow '{$workflow->nombre}' iniciado automáticamente. Paso actual: {$primerPaso->nombre}" . 
                                   ($asignadoA ? " - Asignado a usuario ID: {$asignadoA}" : ''),
                ]);
            }

            DB::commit();

            return redirect()->route('ciudadano.tramites.index')
                ->with('success', 'Su solicitud ha sido enviada exitosamente.')
                ->with('expediente_numero', $numeroExpediente);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error al crear trámite ciudadano: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request' => $request->except(['documentos', 'documentos_opcionales']),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el trámite: ' . $e->getMessage() . '. Por favor, intente nuevamente.');
        }
    }

    /**
     * Mostrar los trámites del ciudadano actual
     */
    public function misTramites()
    {
        // Eager-load workflow steps to render the flujo de esferas in the list
        $expedientes = Expediente::with(['tipoTramite.workflow.steps', 'gerencia', 'documentos', 'workflow', 'workflowProgress.asignado'])
            ->where('usuario_registro_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('ciudadano.tramites.mis-tramites', compact('expedientes'));
    }

    /**
     * Mostrar detalle de un trámite del ciudadano
     */
    public function show($id)
    {
        $expediente = Expediente::with([
            'workflow.steps',
            'tipoTramite.workflow.steps', 
            'gerencia', 
            'documentos', 
            'historial.usuario',
            'workflowProgress.asignado.gerencia',
            'currentStep'
        ])
            ->where('usuario_registro_id', auth()->id())
            ->findOrFail($id);

        return view('ciudadano.tramites.show', compact('expediente'));
    }

    /**
     * Descargar documento del trámite
     */
    public function descargarDocumento($documentoId)
    {
        $documento = DocumentoExpediente::findOrFail($documentoId);
        
        // Verificar que el documento pertenece al usuario autenticado
        $expediente = $documento->expediente;
        if ($expediente->usuario_registro_id !== auth()->id()) {
            abort(403, 'No tiene permiso para descargar este documento');
        }

        $filePath = storage_path('app/private/' . $documento->archivo);
        
        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($filePath, $documento->nombre);
    }

    /**
     * Generar número de expediente único
     */
    private function generarNumeroExpediente()
    {
        $year = date('Y');
        
        // Buscar el último expediente del año actual por el campo 'numero'
        $lastExpediente = Expediente::where('numero', 'like', "EXP-{$year}-%")
            ->orderByRaw('CAST(SUBSTRING(numero, -6) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = $lastExpediente 
            ? (int)substr($lastExpediente->numero, -6) + 1 
            : 1;

        return 'EXP-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
