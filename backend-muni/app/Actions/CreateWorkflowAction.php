<?php

namespace App\Actions;

use App\Repositories\WorkflowRepository;
use App\Services\DocumentValidationService;
use App\Models\Workflow;
use Illuminate\Support\Facades\DB;

class CreateWorkflowAction
{
    protected WorkflowRepository $workflowRepo;
    protected DocumentValidationService $documentValidator;

    public function __construct(
        WorkflowRepository $workflowRepo,
        DocumentValidationService $documentValidator
    ) {
        $this->workflowRepo = $workflowRepo;
        $this->documentValidator = $documentValidator;
    }

    public function execute(array $data): Workflow
    {
        DB::beginTransaction();
        try {
            // Validate TUPA compliance for workflow configuration
            $this->validateWorkflowTupa($data);
            
            $workflow = $this->workflowRepo->create(array_merge($data, [
                'created_by' => auth()->id(),
                'codigo' => $data['codigo'] ?? $this->generateWorkflowCode($data['nombre'])
            ]));
            
            DB::commit();
            return $workflow;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function validateWorkflowTupa(array $data): void
    {
        // Validate TUPA categories and deadlines
        $tupaCategories = $this->documentValidator->getTupaCategories();
        
        if (isset($data['tipo']) && !array_key_exists($data['tipo'], $tupaCategories)) {
            // Allow custom types but warn about TUPA compliance
            \Log::warning('Workflow created with non-standard TUPA type', ['tipo' => $data['tipo']]);
        }
    }

    protected function generateWorkflowCode(string $name): string
    {
        $code = strtoupper(str_replace(' ', '_', substr($name, 0, 20)));
        $counter = 1;
        
        while ($this->workflowRepo->getByCode($code . ($counter > 1 ? "_$counter" : ''))) {
            $counter++;
        }
        
        return $code . ($counter > 1 ? "_$counter" : '');
    }
}