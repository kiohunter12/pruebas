<?php

namespace App\Http\Controllers;

use App\Models\WorkflowTransition;
use App\Repositories\WorkflowTransitionRepository;
use App\Actions\CreateTransitionAction;
use App\Actions\UpdateTransitionAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WorkflowTransitionController extends Controller
{
    protected WorkflowTransitionRepository $transitionRepo;
    protected CreateTransitionAction $createAction;
    protected UpdateTransitionAction $updateAction;

    public function __construct(
        WorkflowTransitionRepository $transitionRepo,
        CreateTransitionAction $createAction,
        UpdateTransitionAction $updateAction
    ) {
        $this->transitionRepo = $transitionRepo;
        $this->createAction = $createAction;
        $this->updateAction = $updateAction;
        $this->authorizeResource(WorkflowTransition::class, 'transition');
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['workflow_id', 'from_step_id', 'to_step_id', 'activo']);
        $transitions = $this->transitionRepo->paginate($filters, (int)$request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $transitions]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = $this->validateTransition($request);
        if ($validator->fails()) return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        
        $transition = $this->createAction->execute($request->all());
        return response()->json(['success' => true, 'message' => 'Transición creada exitosamente', 'data' => $transition], 201);
    }

    public function show($id): JsonResponse
    {
        $transition = $this->transitionRepo->find($id);
        return response()->json(['success' => true, 'data' => $transition]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validator = $this->validateTransition($request, $id);
        if ($validator->fails()) return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        
        $transition = $this->updateAction->execute($id, $request->all());
        return response()->json(['success' => true, 'message' => 'Transición actualizada exitosamente', 'data' => $transition]);
    }

    public function destroy($id): JsonResponse
    {
        $this->transitionRepo->delete($id);
        return response()->json(['success' => true, 'message' => 'Transición eliminada exitosamente']);
    }

    public function getByWorkflow(Request $request, $workflowId): JsonResponse
    {
        $transitions = $this->transitionRepo->getByWorkflow($workflowId);
        return response()->json(['success' => true, 'data' => $transitions]);
    }

    public function getAvailableTransitions(Request $request, $stepId): JsonResponse
    {
        $transitions = $this->transitionRepo->getAvailableFromStep($stepId);
        return response()->json(['success' => true, 'data' => $transitions]);
    }

    private function validateTransition(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'workflow_id' => 'required|exists:workflows,id',
            'from_step_id' => 'nullable|exists:workflow_steps,id',
            'to_step_id' => 'required|exists:workflow_steps,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'condicion' => 'nullable|string',
            'reglas' => 'nullable|array',
            'automatica' => 'boolean',
            'orden' => 'required|integer|min:1',
            'activo' => 'boolean'
        ]);
    }
}