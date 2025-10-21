<?php

namespace App\Repositories;

use App\Models\WorkflowStep;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WorkflowStepRepository
{
    protected WorkflowStep $model;

    public function __construct(WorkflowStep $model)
    {
        $this->model = $model;
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['workflow', 'responsable']);

        if (!empty($filters['workflow_id'])) {
            $query->where('workflow_id', $filters['workflow_id']);
        }

        if (!empty($filters['tipo'])) {
            $query->where('tipo', $filters['tipo']);
        }

        if (isset($filters['activo'])) {
            $query->where('activo', $filters['activo']);
        }

        return $query->orderBy('orden')->paginate($perPage);
    }

    public function create(array $data): WorkflowStep
    {
        return $this->model->create($data);
    }

    public function find(int $id): WorkflowStep
    {
        return $this->model->with(['workflow', 'responsable', 'transitionsFrom.toStep', 'transitionsTo.fromStep'])
                          ->findOrFail($id);
    }

    public function update(int $id, array $data): WorkflowStep
    {
        $step = $this->model->findOrFail($id);
        $step->update($data);
        return $step->fresh(['workflow', 'responsable']);
    }

    public function delete(int $id): void
    {
        $step = $this->model->findOrFail($id);
        
        // Business rule: cannot delete if has active expedientes
        if ($step->expedientes()->count() > 0) {
            throw new \Exception('No se puede eliminar el paso porque tiene expedientes activos');
        }
        
        $step->delete();
    }

    public function getByWorkflow(int $workflowId): Collection
    {
        return $this->model->where('workflow_id', $workflowId)
                          ->with(['responsable'])
                          ->activos()
                          ->ordenadas()
                          ->get();
    }

    public function getInitialSteps(int $workflowId): Collection
    {
        return $this->model->where('workflow_id', $workflowId)
                          ->where('tipo', 'inicio')
                          ->activos()
                          ->get();
    }

    public function getFinalSteps(int $workflowId): Collection
    {
        return $this->model->where('workflow_id', $workflowId)
                          ->where('tipo', 'fin')
                          ->activos()
                          ->get();
    }

    public function getNextStep(int $currentStepId): ?WorkflowStep
    {
        $currentStep = $this->model->findOrFail($currentStepId);
        return $this->model->where('workflow_id', $currentStep->workflow_id)
                          ->where('orden', '>', $currentStep->orden)
                          ->activos()
                          ->ordenadas()
                          ->first();
    }

    public function reorderSteps(int $workflowId, array $steps): void
    {
        DB::transaction(function () use ($workflowId, $steps) {
            foreach ($steps as $stepData) {
                $this->model->where('id', $stepData['id'])
                          ->where('workflow_id', $workflowId)
                          ->update(['orden' => $stepData['orden']]);
            }
        });
    }

    public function getStepsByResponsible(int $responsableId, string $responsableTipo = 'usuario'): Collection
    {
        return $this->model->where('responsable_id', $responsableId)
                          ->where('responsable_tipo', $responsableTipo)
                          ->with(['workflow'])
                          ->activos()
                          ->get();
    }
}