<?php

namespace App\Actions;

use App\Repositories\WorkflowTransitionRepository;
use App\Models\WorkflowTransition;
use Illuminate\Support\Facades\DB;

class CreateTransitionAction
{
    protected WorkflowTransitionRepository $transitionRepo;

    public function __construct(WorkflowTransitionRepository $transitionRepo)
    {
        $this->transitionRepo = $transitionRepo;
    }

    public function execute(array $data): WorkflowTransition
    {
        DB::beginTransaction();
        try {
            // Validate business rules
            $this->validateBusinessRules($data);
            
            $transition = $this->transitionRepo->create(array_merge($data, [
                'activo' => $data['activo'] ?? true,
                'automatica' => $data['automatica'] ?? false
            ]));
            
            DB::commit();
            return $transition;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function validateBusinessRules(array $data): void
    {
        // Prevent circular transitions (step cannot transition to itself)
        if ($data['from_step_id'] === $data['to_step_id']) {
            throw new \Exception('Un paso no puede hacer transición a sí mismo');
        }

        // Check for duplicate transitions
        if (!empty($data['from_step_id'])) {
            $existing = $this->transitionRepo->findBySteps($data['from_step_id'], $data['to_step_id']);
            if ($existing) {
                throw new \Exception('Ya existe una transición entre estos pasos');
            }
        }
    }
}