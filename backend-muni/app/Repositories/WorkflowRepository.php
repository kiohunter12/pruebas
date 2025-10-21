<?php

namespace App\Repositories;

use App\Models\Workflow;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkflowRepository
{
    protected Workflow $model;

    public function __construct(Workflow $model)
    {
        $this->model = $model;
    }

    /**
     * Paginate workflows with optional filters
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['gerencia', 'creador', 'steps']);

        if (!empty($filters['tipo'])) {
            $query->porTipo($filters['tipo']);
        }

        if (!empty($filters['gerencia_id'])) {
            $query->porGerencia($filters['gerencia_id']);
        }

        if (isset($filters['activo'])) {
            $query->activos();
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')
                     ->paginate($perPage);
    }

    public function create(array $data): Workflow
    {
        return $this->model->create($data);
    }

    public function find(int $id): Workflow
    {
        return $this->model->with([
            'gerencia', 'creador',
            'steps' => function ($q) { $q->orderBy('orden'); },
            'steps.transitionsFrom' => function ($q) { $q->with('toStep'); },
            'steps.transitionsTo' => function ($q) { $q->with('fromStep'); }
        ])->findOrFail($id);
    }

    public function update(int $id, array $data): Workflow
    {
        $workflow = $this->model->findOrFail($id);
        $workflow->update($data);
        return $workflow;
    }

    public function delete(int $id): void
    {
        $workflow = $this->model->findOrFail($id);
        
        // Business rule: cannot delete if in use
        if ($workflow->expedientes()->count() > 0) {
            throw new \Exception('No se puede eliminar el workflow porque está siendo usado por expedientes');
        }
        
        $workflow->delete();
    }

    public function duplicate(int $id): Workflow
    {
        $original = $this->model->with('steps.transitions')->findOrFail($id);

        $newWorkflow = $this->model->create([
            'nombre' => $original->nombre . ' (Copia)',
            'codigo' => $original->codigo . '_copy_' . time(),
            'descripcion' => $original->descripcion,
            'tipo' => $original->tipo,
            'configuracion' => $original->configuracion,
            'activo' => false,
            'gerencia_id' => $original->gerencia_id,
            'created_by' => auth()->id()
        ]);

        $stepMapping = [];
        foreach ($original->steps as $step) {
            $newStep = $newWorkflow->steps()->create([
                'nombre' => $step->nombre,
                'codigo' => $step->codigo . '_copy',
                'descripcion' => $step->descripcion,
                'orden' => $step->orden,
                'tipo' => $step->tipo,
                'configuracion' => $step->configuracion,
                'condiciones' => $step->condiciones,
                'acciones' => $step->acciones,
                'requiere_aprobacion' => $step->requiere_aprobacion,
                'tiempo_estimado' => $step->tiempo_estimado,
                'responsable_tipo' => $step->responsable_tipo,
                'responsable_id' => $step->responsable_id,
                'activo' => $step->activo
            ]);

            $stepMapping[$step->id] = $newStep->id;
        }

        foreach ($original->steps as $step) {
            foreach ($step->transitionsFrom as $transition) {
                $newWorkflow->transitions()->create([
                    'from_step_id' => $stepMapping[$transition->from_step_id] ?? null,
                    'to_step_id' => $stepMapping[$transition->to_step_id],
                    'nombre' => $transition->nombre,
                    'descripcion' => $transition->descripcion,
                    'condicion' => $transition->condicion,
                    'reglas' => $transition->reglas,
                    'automatica' => $transition->automatica,
                    'orden' => $transition->orden,
                    'activo' => $transition->activo
                ]);
            }
        }

        return $newWorkflow;
    }

    public function getOptions(): array
    {
        $gerencias = \App\Models\Gerencia::where('activo', true)->select('id', 'nombre')->get();

        $tipos = [
            ['value' => 'expediente', 'label' => 'Expediente'],
            ['value' => 'tramite', 'label' => 'Trámite'],
            ['value' => 'proceso', 'label' => 'Proceso']
        ];

        $tiposPaso = [
            ['value' => 'normal', 'label' => 'Normal'],
            ['value' => 'inicio', 'label' => 'Inicio'],
            ['value' => 'fin', 'label' => 'Fin'],
            ['value' => 'decision', 'label' => 'Decisión'],
            ['value' => 'paralelo', 'label' => 'Paralelo']
        ];

        return [
            'gerencias' => $gerencias,
            'tipos_workflow' => $tipos,
            'tipos_paso' => $tiposPaso,
            'stats' => $this->getStats()
        ];
    }

    public function getByCode(string $code): ?Workflow
    {
        return $this->model->where('codigo', $code)->first();
    }

    protected function getStats(): array
    {
        return [
            'total' => $this->model->count(),
            'activos' => $this->model->where('activo', true)->count(),
            'en_progreso' => $this->model->whereHas('expedientes', function ($q) {
                $q->where('estado', 'en_progreso');
            })->count(),
            'total_pasos' => $this->model->with('steps')->get()->sum(function($workflow) {
                return $workflow->steps->count();
            })
        ];
    }
}
