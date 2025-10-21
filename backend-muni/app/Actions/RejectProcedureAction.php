<?php

namespace App\Actions;

use App\Services\WorkflowTransitionService;
use App\Repositories\ProcedureRepository;
use App\Models\Expediente;
use Illuminate\Support\Facades\DB;

class RejectProcedureAction
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
            
            // Validation: rejection reason is required
            if (empty($data['motivo_rechazo'])) {
                throw new \Exception('El motivo de rechazo es requerido');
            }
            
            $result = $this->transitionService->transition($procedure, 'reject', $data);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
