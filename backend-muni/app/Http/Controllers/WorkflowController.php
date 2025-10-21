<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use App\Repositories\WorkflowRepository;
use App\Actions\CreateWorkflowAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WorkflowController extends Controller
{
    protected WorkflowRepository $workflowRepo;
    protected CreateWorkflowAction $createAction;

    public function __construct(WorkflowRepository $workflowRepo, CreateWorkflowAction $createAction)
    {
        $this->workflowRepo = $workflowRepo;
        $this->createAction = $createAction;
        $this->authorizeResource(Workflow::class, 'workflow');
    }

    // Hybrid methods (support both JSON and web responses)
    public function index(Request $request)
    {
        $filters = $request->only(['tipo', 'gerencia_id', 'activo', 'search', 'gerencia', 'status']);
        $perPage = $request->wantsJson() ? (int)$request->get('per_page', 15) : 10;
        $workflows = $this->workflowRepo->paginate($filters, $perPage);
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $workflows]);
        }
        
        // Estadísticas para la vista
        $stats = [
            'total' => Workflow::count(),
            'activos' => Workflow::where('activo', true)->count(),
            'inactivos' => Workflow::where('activo', false)->count(),
            'en_progreso' => \DB::table('expediente_workflow_progress')
                ->whereIn('estado', ['pendiente', 'en_proceso'])
                ->whereNull('fecha_completado')
                ->distinct('expediente_id')
                ->count('expediente_id'),
        ];
        
        $options = $this->workflowRepo->getOptions();
        $gerencias = $options['gerencias'] ?? [];
        
        return view('workflows.index', compact('workflows', 'options', 'stats', 'gerencias'));
    }

    public function store(Request $request)
    {
        // Si viene tipo_tramite_id, es el flujo de creación desde la vista
        if ($request->has('tipo_tramite_id')) {
            try {
                $tipoTramite = \App\Models\TipoTramite::findOrFail($request->tipo_tramite_id);
                
                // Validar datos mínimos
                $request->validate([
                    'tipo_tramite_id' => 'required|exists:tipo_tramites,id',
                    'steps' => 'required|array|min:1',
                    'steps.*.nombre' => 'required|string',
                    'steps.*.gerencia_id' => 'required|exists:gerencias,id',
                    'steps.*.orden' => 'required|integer|min:1',
                ]);
                
                // Preparar datos para crear el workflow
                $workflowData = [
                    'nombre' => 'Flujo para ' . $tipoTramite->nombre,
                    'codigo' => 'WF-' . strtoupper(substr(uniqid(), -8)),
                    'tipo' => 'tramite',
                    'gerencia_id' => $tipoTramite->gerencia_id,
                    'descripcion' => 'Workflow generado automáticamente para el trámite: ' . $tipoTramite->nombre,
                    'configuracion' => [
                        'tipo_tramite_id' => $tipoTramite->id,
                        'tipo_tramite_nombre' => $tipoTramite->nombre,
                    ],
                    'activo' => true,
                    'created_by' => auth()->id(),
                ];
                
                DB::beginTransaction();
                
                // Crear el workflow
                $workflow = Workflow::create($workflowData);
                
                // Crear los pasos
                foreach ($request->steps as $stepData) {
                    $step = $workflow->steps()->create([
                        'nombre' => $stepData['nombre'],
                        'codigo' => 'STEP-' . strtoupper(substr(uniqid(), -6)),
                        'orden' => $stepData['orden'],
                        'descripcion' => $stepData['descripcion'] ?? 'Paso ' . $stepData['orden'],
                        'tiempo_estimado' => ($stepData['tiempo_limite_dias'] ?? 3) * 1440, // convertir días a minutos
                        'tipo' => 'normal',
                        'responsable_tipo' => 'gerencia', // Asignar por gerencia
                        'responsable_id' => $stepData['gerencia_id'], // ID de la gerencia responsable
                        'activo' => true,
                    ]);
                    
                    // Guardar los documentos asociados al paso
                    if (isset($stepData['documentos']) && is_array($stepData['documentos'])) {
                        foreach ($stepData['documentos'] as $documentoId) {
                            \App\Models\WorkflowStepDocument::create([
                                'workflow_step_id' => $step->id,
                                'tipo_documento_id' => $documentoId,
                                'es_obligatorio' => isset($stepData['documentos_obligatorios'][$documentoId]) ? true : false,
                                'orden' => 0,
                            ]);
                        }
                    }
                }
                
                DB::commit();
                
                return redirect()->route('workflows.show', $workflow)
                    ->with('success', 'Workflow creado exitosamente con ' . count($request->steps) . ' pasos');
                    
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->back()
                    ->withErrors($e->validator)
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error al crear workflow: ' . $e->getMessage(), [
                    'exception' => $e,
                    'request' => $request->all()
                ]);
                return redirect()->back()
                    ->withErrors(['error' => 'Error al crear el workflow: ' . $e->getMessage()])
                    ->withInput();
            }
        }
        
        // Flujo normal con validación estricta (para API o formulario avanzado)
        $validator = $this->validateWorkflow($request);
        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $workflow = $this->createAction->execute($request->all());
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Workflow creado exitosamente', 'data' => $workflow], 201);
        }
        
        return redirect()->route('workflows.index')->with('success', 'Workflow creado exitosamente');
    }

    public function show(Workflow $workflow)
    {
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'data' => $workflow]);
        }
        
        // Cargar pasos con sus documentos
        $workflow->load('steps.documentos.tipoDocumento');
        
        // Calcular estadísticas para el workflow específico
        $stats = [
            'total_pasos' => $workflow->steps()->count(),
            'expedientes_procesados' => \DB::table('expediente_workflow_progress')
                ->join('workflow_steps', 'expediente_workflow_progress.workflow_step_id', '=', 'workflow_steps.id')
                ->where('workflow_steps.workflow_id', $workflow->id)
                ->distinct('expediente_workflow_progress.expediente_id')
                ->count('expediente_workflow_progress.expediente_id'),
            'expedientes_activos' => \DB::table('expediente_workflow_progress')
                ->join('workflow_steps', 'expediente_workflow_progress.workflow_step_id', '=', 'workflow_steps.id')
                ->where('workflow_steps.workflow_id', $workflow->id)
                ->whereIn('expediente_workflow_progress.estado', ['pendiente', 'en_proceso'])
                ->whereNull('expediente_workflow_progress.fecha_completado')
                ->distinct('expediente_workflow_progress.expediente_id')
                ->count('expediente_workflow_progress.expediente_id'),
            'tiempo_promedio' => round(\DB::table('expediente_workflow_progress')
                ->join('workflow_steps', 'expediente_workflow_progress.workflow_step_id', '=', 'workflow_steps.id')
                ->where('workflow_steps.workflow_id', $workflow->id)
                ->whereNotNull('expediente_workflow_progress.fecha_completado')
                ->whereNotNull('expediente_workflow_progress.fecha_inicio')
                ->selectRaw('AVG(DATEDIFF(fecha_completado, fecha_inicio)) as promedio')
                ->value('promedio') ?? 0, 1),
        ];
        
        return view('workflows.show', compact('workflow', 'stats'));
    }

    public function update(Request $request, Workflow $workflow)
    {
        $validator = $this->validateWorkflow($request, $workflow->id);
        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $workflow = $this->workflowRepo->update($workflow->id, $request->all());
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Workflow actualizado exitosamente', 'data' => $workflow]);
        }
        
        return redirect()->route('workflows.index')->with('success', 'Workflow actualizado exitosamente');
    }

    public function destroy(Workflow $workflow)
    {
        $this->workflowRepo->delete($workflow->id);
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Workflow eliminado exitosamente']);
        }
        
        return redirect()->route('workflows.index')->with('success', 'Workflow eliminado exitosamente');
    }

    public function duplicate($id): JsonResponse
    {
        $newWorkflow = $this->workflowRepo->duplicate($id);
        return response()->json(['success' => true, 'message' => 'Workflow duplicado exitosamente', 'data' => $newWorkflow]);
    }

    public function getOptions(): JsonResponse
    {
        $options = $this->workflowRepo->getOptions();
        return response()->json(['success' => true, 'data' => $options]);
    }

    public function create()
    {
        $options = $this->workflowRepo->getOptions();
        
        // Datos adicionales para la vista de crear workflow
        $tiposTramite = \App\Models\TipoTramite::with(['gerencia', 'tiposDocumentos'])->get();
        
        // Cargar gerencias con responsables y usuarios asignados
        $gerenciasPrincipales = \App\Models\Gerencia::whereNull('gerencia_padre_id')
            ->with([
                'subgerencias.responsable', 
                'subgerencias.usuarios', // Usuarios asignados a cada subgerencia
                'responsable',
                'usuarios' // Usuarios asignados a la gerencia principal
            ])
            ->get();
            
        $todasGerencias = \App\Models\Gerencia::with(['responsable', 'usuarios'])->get();
        
        // Cargar todos los usuarios con su gerencia asignada y sus roles
        $usuarios = \App\Models\User::with('roles:id,name')
            ->select('id', 'name', 'email', 'gerencia_id')
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'gerencia_id' => $user->gerencia_id,
                    'rol' => $user->roles->first()?->name ?? 'sin_rol'
                ];
            });
        
        // NO cargar todos los documentos, se cargarán dinámicamente según el tipo de trámite seleccionado
        
        return view('workflows.create', compact('options', 'tiposTramite', 'gerenciasPrincipales', 'todasGerencias', 'usuarios'));
    }

    public function edit(Workflow $workflow)
    {
        $options = $this->workflowRepo->getOptions();
        
        // Datos adicionales para la vista de editar
        $gerencias = \App\Models\Gerencia::all();
        $usuarios = \App\Models\User::select('id', 'name', 'email')->get();
        
        // Cargar el tipo de trámite con sus documentos si existe
        $tipoTramite = null;
        $tiposDocumento = collect();
        
        if ($workflow->configuracion && isset($workflow->configuracion['tipo_tramite_id'])) {
            $tipoTramite = \App\Models\TipoTramite::with('tiposDocumentos')
                                                    ->find($workflow->configuracion['tipo_tramite_id']);
            if ($tipoTramite) {
                $tiposDocumento = $tipoTramite->tiposDocumentos;
            }
        }
        
        // Si no hay tipo de trámite, cargar todos los documentos (workflows antiguos)
        if ($tiposDocumento->isEmpty()) {
            $tiposDocumento = \App\Models\TipoDocumento::orderBy('nombre')->get();
        }
        
        $isCustom = $workflow->tipo === 'personalizado';
        
        // Cargar pasos con sus documentos
        $workflow->load('steps.documentos.tipoDocumento');
        
        return view('workflows.edit', compact('workflow', 'options', 'gerencias', 'usuarios', 'tiposDocumento', 'tipoTramite', 'isCustom'));
    }

    private function validateWorkflow(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => $id ? ['required', 'string', 'max:100', Rule::unique('workflows')->ignore($id)] 
                            : 'required|string|max:100|unique:workflows,codigo',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string|in:expediente,tramite,proceso',
            'configuracion' => 'nullable|array',
            'gerencia_id' => 'nullable|exists:gerencias,id',
            'activo' => 'boolean'
        ]);
    }
}