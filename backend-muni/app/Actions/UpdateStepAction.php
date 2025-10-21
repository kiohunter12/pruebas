<?php

namespace App\Actions;

use App\Models\WorkflowStep;
use App\Repositories\WorkflowStepRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdateStepAction
{
    protected WorkflowStepRepository $repository;

    public function __construct(WorkflowStepRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id, array $data): WorkflowStep
    {
        $step = $this->repository->find($id);
        $this->validate($id, $data);
        
        return DB::transaction(function () use ($id, $data) {
            return $this->repository->update($id, $data);
        });
    }

    protected function validate(int $id, array $data): void
    {
        $validator = Validator::make($data, [
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'sometimes|in:inicio,proceso,revision,aprobacion,fin',
            'orden' => 'sometimes|integer|min:1',
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
        $this->validateBusinessRules($id, $data);
    }

    protected function validateBusinessRules(int $id, array $data): void
    {
        $step = WorkflowStep::findOrFail($id);

        // Validate only one initial step per workflow
        if (isset($data['tipo']) && $data['tipo'] === 'inicio') {
            $existingInitial = WorkflowStep::where('workflow_id', $step->workflow_id)
                                         ->where('tipo', 'inicio')
                                         ->where('activo', true)
                                         ->where('id', '!=', $id)
                                         ->exists();
            
            if ($existingInitial) {
                throw new ValidationException(
                    Validator::make([], [])->errors()->add('tipo', 'Ya existe un paso inicial activo para este workflow')
                );
            }
        }

        // Validate orden is unique within workflow
        if (isset($data['orden'])) {
            $existingOrder = WorkflowStep::where('workflow_id', $step->workflow_id)
                                       ->where('orden', $data['orden'])
                                       ->where('id', '!=', $id)
                                       ->exists();
            
            if ($existingOrder) {
                throw new ValidationException(
                    Validator::make([], [])->errors()->add('orden', 'Ya existe un paso con este orden en el workflow')
                );
            }
        }

        // Validate responsable when tipo requires it
        $tipo = $data['tipo'] ?? $step->tipo;
        $responsableId = $data['responsable_id'] ?? $step->responsable_id;
        
        if (in_array($tipo, ['revision', 'aprobacion']) && empty($responsableId)) {
            throw new ValidationException(
                Validator::make([], [])->errors()->add('responsable_id', 'Los pasos de revisión y aprobación requieren un responsable')
            );
        }

        // Cannot deactivate if step has active expedientes
        if (isset($data['activo']) && !$data['activo'] && $step->expedientes()->count() > 0) {
            throw new ValidationException(
                Validator::make([], [])->errors()->add('activo', 'No se puede desactivar el paso porque tiene expedientes activos')
            );
        }

        // Cannot change workflow_id (immutable)
        if (isset($data['workflow_id']) && $data['workflow_id'] != $step->workflow_id) {
            throw new ValidationException(
                Validator::make([], [])->errors()->add('workflow_id', 'No se puede cambiar el workflow de un paso existente')
            );
        }
    }
}