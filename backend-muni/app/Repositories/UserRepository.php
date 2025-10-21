<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Gerencia;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getByRole(string $role): Collection
    {
        return $this->model->role($role)->get();
    }

    public function getByGerencia(int $gerenciaId): Collection
    {
        return $this->model->where('gerencia_id', $gerenciaId)
                          ->with('roles')
                          ->get();
    }

    public function getApproversForStep(int $stepId): Collection
    {
        // Logic to get users who can approve a specific workflow step
        return $this->model->whereHas('roles', function ($q) {
            $q->whereIn('name', ['supervisor', 'gerente', 'jefe']);
        })->get();
    }

    public function getResponsiblesForWorkflow(int $workflowId): Collection
    {
        return $this->model->whereHas('gerencia.workflows', function ($q) use ($workflowId) {
            $q->where('id', $workflowId);
        })->with('roles')->get();
    }

    public function getActiveUsers(): Collection
    {
        return $this->model->where('active', true)
                          ->with(['roles', 'gerencia'])
                          ->orderBy('name')
                          ->get();
    }
}