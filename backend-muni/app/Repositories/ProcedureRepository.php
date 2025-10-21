<?php

namespace App\Repositories;

use App\Models\Expediente;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProcedureRepository
{
    protected Expediente $model;

    public function __construct(Expediente $model)
    {
        $this->model = $model;
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['workflow', 'gerencia', 'solicitante', 'currentStep']);

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['gerencia_id'])) {
            $query->where('gerencia_id', $filters['gerencia_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('asunto', 'like', "%{$search}%")
                  ->orWhereHas('solicitante', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['fecha_desde'])) {
            $query->whereDate('created_at', '>=', $filters['fecha_desde']);
        }

        if (!empty($filters['fecha_hasta'])) {
            $query->whereDate('created_at', '<=', $filters['fecha_hasta']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Expediente
    {
        return $this->model->create($data);
    }

    public function find(int $id): Expediente
    {
        return $this->model->with([
            'workflow.steps', 
            'gerencia', 
            'solicitante', 
            'currentStep',
            'documentos',
            'historial.usuario'
        ])->findOrFail($id);
    }

    public function update(int $id, array $data): Expediente
    {
        $expediente = $this->model->findOrFail($id);
        $expediente->update($data);
        return $expediente->fresh();
    }

    public function delete(int $id): void
    {
        $expediente = $this->model->findOrFail($id);
        $expediente->delete();
    }

    public function getByWorkflow(int $workflowId): Collection
    {
        return $this->model->where('workflow_id', $workflowId)
                          ->with(['currentStep', 'solicitante'])
                          ->get();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
                          ->with(['workflow', 'currentStep'])
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    public function getPendingByStep(int $stepId): Collection
    {
        return $this->model->where('current_step_id', $stepId)
                          ->where('estado', 'en_progreso')
                          ->with(['solicitante', 'gerencia'])
                          ->get();
    }

    public function getStatsByGerencia(int $gerenciaId): array
    {
        $total = $this->model->where('gerencia_id', $gerenciaId)->count();
        $pendientes = $this->model->where('gerencia_id', $gerenciaId)
                                  ->where('estado', 'en_progreso')
                                  ->count();
        $completados = $this->model->where('gerencia_id', $gerenciaId)
                                   ->where('estado', 'completado')
                                   ->count();
        $vencidos = $this->model->where('gerencia_id', $gerenciaId)
                                ->where('estado', 'en_progreso')
                                ->where('fecha_limite', '<', now())
                                ->count();

        return compact('total', 'pendientes', 'completados', 'vencidos');
    }
}