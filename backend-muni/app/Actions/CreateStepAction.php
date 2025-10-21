<?php

namespace App\Actions;

use App\Models\WorkflowStep;
use App\Repositories\WorkflowStepRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateStepAction
{
    protected WorkflowStepRepository $repository;

    public function __construct(WorkflowStepRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(array $data): WorkflowStep
    {
        $this->validate($data);
        
        return DB::transaction(function () use ($data) {
            // Auto-assign orden if not provided
            if (!isset($data['orden'])) {
                $data['orden'] = $this->getNextOrder($data['workflow_id']);
            }
            
            // Set default values
            $data['activo'] = $data['activo'] ?? true;
            
            return $this->repository->create($data);
        });
    }

    protected function validate(array $data): void
    {
        $validator = Validator::make($data, [
            'workflow_id' => 'required|exists:workflows,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:inicio,proceso,revision,aprobacion,fin',
            'orden' => 'nullable|integer|min:1',
            'configuracion' => 'nullable|array',
            'tiempo_limite_dias' => 'nullable|integer|min:1|max:365',
            'activo' => 'boolean',
            'responsable_id' => 'nullable|integer',
            'responsable_tipo' => 'nullable|string|in:usuario,gerencia,externo',
            'requiere_documentos' => 'boolean',
            'documentos_requeridos' => 'nullable|array',
            'permite_delegacion' => 'boolean',
            'es_paralelo' => 'boolean',
            'notificaciones_activas' => 'boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Business rule validations
        $this->validateBusinessRules($data);
    }

    protected function validateBusinessRules(array $data): void
    {
        // Validate only one initial step per workflow
        if ($data['tipo'] === 'inicio') {
            $existingInitial = WorkflowStep::where('workflow_id', $data['workflow_id'])
                                         ->where('tipo', 'inicio')
                                         ->where('activo', true)
                                         ->exists();
            
            if ($existingInitial) {
                throw new ValidationException(
                    Validator::make([], [])->errors()->add('tipo', 'Ya existe un paso inicial activo para este workflow')
                );
            }
        }

        // Validate orden is unique within workflow
        if (isset($data['orden'])) {
            $existingOrder = WorkflowStep::where('workflow_id', $data['workflow_id'])
                                       ->where('orden', $data['orden'])
                                       ->exists();
            
            if ($existingOrder) {
                throw new ValidationException(
                    Validator::make([], [])->errors()->add('orden', 'Ya existe un paso con este orden en el workflow')
                );
            }
        }

        // Validate responsable when tipo requires it
        if (in_array($data['tipo'], ['revision', 'aprobacion']) && empty($data['responsable_id'])) {
            throw new ValidationException(
                Validator::make([], [])->errors()->add('responsable_id', 'Los pasos de revisión y aprobación requieren un responsable')
            );
        }
    }

    protected function getNextOrder(int $workflowId): int
    {
        $maxOrder = WorkflowStep::where('workflow_id', $workflowId)->max('orden');
        return ($maxOrder ?? 0) + 1;
    }
}