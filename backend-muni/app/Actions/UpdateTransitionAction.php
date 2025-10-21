<?php

namespace App\Actions;

use App\Repositories\WorkflowTransitionRepository;
use App\Models\WorkflowTransition;
use Illuminate\Support\Facades\DB;

class UpdateTransitionAction
{
    protected WorkflowTransitionRepository $transitionRepo;

    public function __construct(WorkflowTransitionRepository $transitionRepo)
    {
        $this->transitionRepo = $transitionRepo;
    }

    public function execute(int $id, array $data): WorkflowTransition
    {
        DB::beginTransaction();
        try {
            $transition = $this->transitionRepo->find($id);
            
            // Validate business rules for updates
            $this->validateBusinessRules($transition, $data);
            
            $updatedTransition = $this->transitionRepo->update($id, $data);
            
            DB::commit();
            return $updatedTransition;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function validateBusinessRules(WorkflowTransition $transition, array $data): void
    {
        // Prevent circular transitions
        if (isset($data['from_step_id']) && isset($data['to_step_id']) && 
            $data['from_step_id'] === $data['to_step_id']) {
            throw new \Exception('Un paso no puede hacer transición a sí mismo');
        }

        // Check if transition is being used by active procedures
        if (isset($data['activo']) && $data['activo'] === false) {
            // Here you could add logic to check if transition is actively being used
            // by procedures in progress
        }
    }
}