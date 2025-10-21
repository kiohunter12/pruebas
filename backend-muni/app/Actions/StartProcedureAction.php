<?php

namespace App\Actions;

use App\Repositories\WorkflowRepository;
use App\Repositories\ProcedureRepository;
use App\Services\WorkflowTransitionService;
use App\Models\Expediente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StartProcedureAction
{
    protected WorkflowRepository $workflowRepo;
    protected ProcedureRepository $procedureRepo;
    protected WorkflowTransitionService $transitionService;

    public function __construct(
        WorkflowRepository $workflowRepo,
        ProcedureRepository $procedureRepo,
        WorkflowTransitionService $transitionService
    ) {
        $this->workflowRepo = $workflowRepo;
        $this->procedureRepo = $procedureRepo;
        $this->transitionService = $transitionService;
    }

    public function execute(array $data): Expediente
    {
        DB::beginTransaction();
        try {
            // Get workflow and initial step
            $workflow = $this->workflowRepo->find($data['workflow_id']);
            $initialStep = $workflow->getInitialStep();
            
            if (!$initialStep) {
                throw new \Exception('Workflow no tiene paso inicial definido');
            }

            // Calculate TUPA deadline
            $tupaDeadline = $this->calculateTupaDeadline($workflow, $initialStep);

            // Create expediente with initial data
            $expedienteData = array_merge($data, [
                'numero' => $this->generateProcedureNumber(),
                'estado' => 'iniciado',
                'current_step_id' => $initialStep->id,
                'fecha_limite' => $tupaDeadline,
                'user_id' => Auth::id(),
                'created_by' => Auth::id()
            ]);

            $expediente = $this->procedureRepo->create($expedienteData);
            
            // Log initial creation in history
            \App\Models\HistorialExpediente::create([
                'expediente_id' => $expediente->id,
                'usuario_id' => Auth::id(),
                'accion' => 'iniciar',
                'descripcion' => 'Expediente iniciado',
                'created_at' => now()
            ]);

            DB::commit();
            return $expediente;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function generateProcedureNumber(): string
    {
        $year = date('Y');
        $sequence = \DB::table('expedientes')->whereYear('created_at', $year)->count() + 1;
        return sprintf('%s-%06d', $year, $sequence);
    }

    protected function calculateTupaDeadline($workflow, $initialStep): ?\Carbon\Carbon
    {
        if ($initialStep->tiempo_estimado) {
            return now()->addDays($initialStep->tiempo_estimado);
        }
        
        // Default TUPA deadline for municipal procedures
        return now()->addDays(30);
    }
}
