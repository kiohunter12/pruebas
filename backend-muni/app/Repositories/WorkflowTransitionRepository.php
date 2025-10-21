<?php

namespace App\Repositories;

use App\Models\WorkflowTransition;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class WorkflowTransitionRepository
{
    protected WorkflowTransition $model;

    public function __construct(WorkflowTransition $model)
    {
        $this->model = $model;
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['workflow', 'fromStep', 'toStep']);

        if (!empty($filters['workflow_id'])) {
            $query->where('workflow_id', $filters['workflow_id']);
        }

        if (!empty($filters['from_step_id'])) {
            $query->where('from_step_id', $filters['from_step_id']);
        }

        if (!empty($filters['to_step_id'])) {
            $query->where('to_step_id', $filters['to_step_id']);
        }

        if (isset($filters['activo'])) {
            $query->where('activo', $filters['activo']);
        }

        return $query->orderBy('orden')->paginate($perPage);
    }

    public function create(array $data): WorkflowTransition
    {
        return $this->model->create($data);
    }

    public function find(int $id): WorkflowTransition
    {
        return $this->model->with(['workflow', 'fromStep', 'toStep'])->findOrFail($id);
    }

    public function update(int $id, array $data): WorkflowTransition
    {
        $transition = $this->model->findOrFail($id);
        $transition->update($data);
        return $transition->fresh(['workflow', 'fromStep', 'toStep']);
    }

    public function delete(int $id): void
    {
        $transition = $this->model->findOrFail($id);
        $transition->delete();
    }

    public function getByWorkflow(int $workflowId): Collection
    {
        return $this->model->where('workflow_id', $workflowId)
                          ->with(['fromStep', 'toStep'])
                          ->activos()
                          ->ordenadas()
                          ->get();
    }

    public function getAvailableFromStep(int $stepId): Collection
    {
        return $this->model->where('from_step_id', $stepId)
                          ->with(['toStep'])
                          ->activos()
                          ->ordenadas()
                          ->get();
    }

    public function getAutomaticTransitions(int $workflowId): Collection
    {
        return $this->model->where('workflow_id', $workflowId)
                          ->where('automatica', true)
                          ->with(['fromStep', 'toStep'])
                          ->activos()
                          ->get();
    }

    public function findBySteps(int $fromStepId, int $toStepId): ?WorkflowTransition
    {
        return $this->model->where('from_step_id', $fromStepId)
                          ->where('to_step_id', $toStepId)
                          ->activos()
                          ->first();
    }
}