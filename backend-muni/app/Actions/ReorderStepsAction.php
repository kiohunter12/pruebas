<?php

namespace App\Actions;

use App\Repositories\WorkflowStepRepository;
use Illuminate\Support\Facades\DB;

class ReorderStepsAction
{
    protected WorkflowStepRepository $repository;

    public function __construct(WorkflowStepRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $workflowId, array $steps): void
    {
        DB::transaction(function () use ($workflowId, $steps) {
            $this->repository->reorderSteps($workflowId, $steps);
        });
    }
}