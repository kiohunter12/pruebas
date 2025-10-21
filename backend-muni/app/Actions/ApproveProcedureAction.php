<?php

namespace App\Actions;

use App\Services\WorkflowTransitionService;
use App\Repositories\ProcedureRepository;
use App\Models\Expediente;
use Illuminate\Support\Facades\DB;

class ApproveProcedureAction
{
    protected WorkflowTransitionService $transitionService;
    protected ProcedureRepository $procedureRepo;

    public function __construct(
        WorkflowTransitionService $transitionService,
        ProcedureRepository $procedureRepo
    ) {
        $this->transitionService = $transitionService;
        $this->procedureRepo = $procedureRepo;
    }

    public function execute(int $procedureId, array $data = []): Expediente
    {
        DB::beginTransaction();
        try {
            $procedure = $this->procedureRepo->find($procedureId);
            $result = $this->transitionService->transition($procedure, 'approve', $data);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
