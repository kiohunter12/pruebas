<?php

namespace App\Http\Controllers;

use App\Models\WorkflowStep;
use App\Repositories\WorkflowStepRepository;
use App\Actions\CreateStepAction;
use App\Actions\UpdateStepAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class WorkflowStepController extends Controller
{
    protected WorkflowStepRepository $stepRepo;
    protected CreateStepAction $createAction;
    protected UpdateStepAction $updateAction;

    public function __construct(
        WorkflowStepRepository $stepRepo,
        CreateStepAction $createAction,
        UpdateStepAction $updateAction
    ) {
        $this->stepRepo = $stepRepo;
        $this->createAction = $createAction;
        $this->updateAction = $updateAction;
        $this->authorizeResource(WorkflowStep::class, 'step');
    }

    public function index(Request $request, $workflowId = null)
    {
        if ($workflowId) {
            $workflow = \App\Models\Workflow::findOrFail($workflowId);
            $steps = $this->stepRepo->getByWorkflow($workflowId);
            
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'data' => $steps]);
            }
            
            return view('workflow-steps.index', compact('workflow', 'steps'));
        }
        
        $filters = $request->only(['workflow_id', 'tipo', 'activo']);
        $steps = $this->stepRepo->paginate($filters, (int)$request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $steps]);
    }

    public function create($workflowId)
    {
        $workflow = \App\Models\Workflow::findOrFail($workflowId);
        $gerencias = \App\Models\Gerencia::all();
        $usuarios = \App\Models\User::all();
        $nextOrder = $workflow->steps()->max('orden') + 1;
        
        return view('workflow-steps.create', compact('workflow', 'gerencias', 'usuarios', 'nextOrder'));
    }

    public function store(Request $request, $workflowId = null)
    {
        // Si viene workflowId de la URL, lo agregamos al request
        if ($workflowId) {
            $request->merge(['workflow_id' => $workflowId]);
        }
        
        $validator = $this->validateStep($request);
        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $step = $this->createAction->execute($request->all());
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Paso creado exitosamente', 'data' => $step], 201);
        }
        
        return redirect()->route('workflow-steps.index', $workflowId ?? $step->workflow_id)
            ->with('success', 'Paso creado exitosamente');
    }

    public function show($workflowId, $id = null)
    {
        // Si solo hay un parámetro, es el ID del step
        if ($id === null) {
            $id = $workflowId;
            $workflowId = null;
        }
        
        $step = $this->stepRepo->find($id);
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'data' => $step]);
        }
        
        $workflow = $step->workflow;
        return view('workflow-steps.show', compact('workflow', 'step'));
    }

    public function edit($workflowId, $id = null)
    {
        // Si solo hay un parámetro, es el ID del step
        if ($id === null) {
            $id = $workflowId;
            $workflowId = null;
        }
        
        $step = $this->stepRepo->find($id);
        $workflow = $step->workflow;
        $gerencias = \App\Models\Gerencia::all();
        $usuarios = \App\Models\User::all();
        
        return view('workflow-steps.edit', compact('workflow', 'step', 'gerencias', 'usuarios'));
    }

    public function update(Request $request, $workflowId, $id = null)
    {
        // Si solo hay dos parámetros, el segundo es el ID del step
        if ($id === null) {
            $id = $workflowId;
            $workflowId = null;
        }
        
        $validator = $this->validateStep($request, $id);
        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $step = $this->updateAction->execute($id, $request->all());
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Paso actualizado exitosamente', 'data' => $step]);
        }
        
        return redirect()->route('workflow-steps.index', $workflowId ?? $step->workflow_id)
            ->with('success', 'Paso actualizado exitosamente');
    }

    public function destroy($workflowId, $id = null)
    {
        // Si solo hay un parámetro, es el ID del step
        if ($id === null) {
            $id = $workflowId;
            $workflowId = null;
        }
        
        $step = $this->stepRepo->find($id);
        $workflow_id = $step->workflow_id;
        $this->stepRepo->delete($id);
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Paso eliminado exitosamente']);
        }
        
        return redirect()->route('workflow-steps.index', $workflowId ?? $workflow_id)
            ->with('success', 'Paso eliminado exitosamente');
    }

    public function getByWorkflow(Request $request, $workflowId): JsonResponse
    {
        $steps = $this->stepRepo->getByWorkflow($workflowId);
        return response()->json(['success' => true, 'data' => $steps]);
    }

    public function reorder(Request $request, $workflowId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'steps' => 'required|array',
            'steps.*.id' => 'required|exists:workflow_steps,id',
            'steps.*.orden' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $this->stepRepo->reorderSteps($workflowId, $request->steps);
        return response()->json(['success' => true, 'message' => 'Pasos reordenados exitosamente']);
    }

    private function validateStep(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'workflow_id' => 'required|exists:workflows,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'tipo' => 'nullable|in:inicio,normal,decision,paralelo,fin',
            'orden' => 'required|integer|min:1',
            'configuracion' => 'nullable|array',
            'condiciones' => 'nullable|array',
            'acciones' => 'nullable|array',
            'requiere_aprobacion' => 'nullable|boolean',
            'es_final' => 'nullable|boolean',
            'tiempo_limite_dias' => 'nullable|integer|min:1',
            'tiempo_estimado' => 'nullable|integer|min:1',
            'responsable_tipo' => 'nullable|in:usuario,rol,gerencia',
            'responsable_id' => 'nullable|integer',
            'gerencia_id' => 'nullable|exists:gerencias,id',
            'usuario_responsable_id' => 'nullable|exists:users,id',
            'activo' => 'nullable|boolean'
        ]);
    }
}