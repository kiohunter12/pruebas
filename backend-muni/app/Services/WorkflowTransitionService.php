<?php

namespace App\Services;

use App\Models\Expediente;
use App\Models\WorkflowTransition;
use App\Models\WorkflowStep;
use App\Models\WorkflowRule;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkflowTransitionService
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Execute a complete transition: validate, update procedure, log history.
     * Consolidates ALL transition logic in one place.
     */
    public function transition(Expediente $procedure, string $actionType, array $data = []): Expediente
    {
        // 1. Validate transition is allowed
        $nextStepId = $this->getNextStepId($procedure, $actionType);
        
        // 2. Validate user permissions
        $this->validateUserPermissions($procedure, $actionType);
        
        // 3. Validate TUPA deadlines and business rules
        $this->validateBusinessRules($procedure, $actionType, $data);
        
        // 4. Execute transition
        $procedure->update([
            'current_step_id' => $nextStepId,
            'estado' => $this->getNewStatus($actionType),
            'fecha_actualizacion' => now(),
            'updated_by' => Auth::id()
        ]);

        // 5. Calculate new deadlines based on TUPA rules
        $this->updateDeadlines($procedure, $nextStepId);
        
        // 6. Log transition in history
        $this->logTransition($procedure, $actionType, $data);
        
        // 7. Send notifications if needed
        $this->sendNotifications($procedure, $actionType);
        
        return $procedure->fresh();
    }

    protected function getNextStepId(Expediente $procedure, string $actionType): int
    {
        $currentStep = $procedure->currentStep;
        
        $transitions = WorkflowTransition::where('workflow_id', $procedure->workflow_id)
            ->where('from_step_id', $currentStep?->id)
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        foreach ($transitions as $transition) {
            if ($this->evaluateTransitionRules($transition, $procedure, $actionType)) {
                return $transition->to_step_id;
            }
        }

        throw new \Exception('No valid transition found for action: ' . $actionType);
    }

    protected function evaluateTransitionRules($transition, Expediente $procedure, string $actionType): bool
    {
        if (empty($transition->reglas)) {
            return true; // No rules = always valid
        }

        foreach ($transition->reglas as $rule) {
            if (!$this->evaluateRule($rule, $procedure, $actionType)) {
                return false;
            }
        }
        
        return true;
    }

    protected function evaluateRule(array $rule, Expediente $procedure, string $actionType): bool
    {
        switch ($rule['type'] ?? 'action') {
            case 'action':
                return ($rule['action'] ?? null) === $actionType;
            
            case 'role':
                $user = Auth::user();
                return $user && $user->hasRole($rule['role']);
            
            case 'gerencia':
                $user = Auth::user();
                return $user && $user->gerencia_id == $procedure->gerencia_id;
            
            case 'time_limit':
                $deadline = Carbon::parse($procedure->fecha_limite ?? $procedure->created_at);
                return now()->lte($deadline->addDays($rule['days'] ?? 0));
            
            case 'custom':
                return $this->evaluateCustomRule($rule, $procedure, $actionType);
                
            default:
                return true;
        }
    }

    protected function evaluateCustomRule(array $rule, Expediente $procedure, string $actionType): bool
    {
        // Extensible custom rule evaluation
        // Can be enhanced to support complex expressions, external validation, etc.
        return true;
    }

    protected function validateUserPermissions(Expediente $procedure, string $actionType): void
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('Usuario no autenticado');
        }

        // Permission validation based on action type
        switch ($actionType) {
            case 'approve':
                if (!$user->can('aprobar_expedientes')) {
                    throw new \Exception('No tiene permisos para aprobar expedientes');
                }
                break;
            
            case 'reject':
                if (!$user->can('rechazar_expedientes')) {
                    throw new \Exception('No tiene permisos para rechazar expedientes');
                }
                break;
                
            case 'transfer':
                if (!$user->can('transferir_expedientes')) {
                    throw new \Exception('No tiene permisos para transferir expedientes');
                }
                break;
        }
    }

    protected function validateBusinessRules(Expediente $procedure, string $actionType, array $data): void
    {
        // TUPA deadline validation
        if ($procedure->fecha_limite && now()->gt($procedure->fecha_limite)) {
            if (!Auth::user()->hasRole(['super-admin', 'gerente'])) {
                throw new \Exception('No se puede procesar: expediente fuera de plazo TUPA');
            }
        }

        // Required documents validation for certain actions
        if (in_array($actionType, ['approve', 'complete']) && $procedure->documentos()->count() == 0) {
            throw new \Exception('Documentos requeridos no han sido cargados');
        }

        // Custom validation based on action type
        switch ($actionType) {
            case 'reject':
                if (empty($data['motivo_rechazo'])) {
                    throw new \Exception('Motivo de rechazo es requerido');
                }
                break;
        }
    }

    protected function getNewStatus(string $actionType): string
    {
        return match ($actionType) {
            'approve' => 'aprobado',
            'reject' => 'rechazado',
            'complete' => 'completado',
            'transfer' => 'en_progreso',
            default => 'en_progreso'
        };
    }

    protected function updateDeadlines(Expediente $procedure, int $nextStepId): void
    {
        $nextStep = WorkflowStep::find($nextStepId);
        if ($nextStep && $nextStep->tiempo_estimado) {
            $procedure->update([
                'fecha_limite' => now()->addDays($nextStep->tiempo_estimado)
            ]);
        }
    }

    protected function logTransition(Expediente $procedure, string $actionType, array $data): void
    {
        \App\Models\HistorialExpediente::create([
            'expediente_id' => $procedure->id,
            'usuario_id' => Auth::id(),
            'accion' => $actionType,
            'descripcion' => $data['comentario'] ?? 'Transición automática',
            'datos_adicionales' => $data,
            'created_at' => now()
        ]);
    }

    protected function sendNotifications(Expediente $procedure, string $actionType): void
    {
        // Integration point for notification service
        if (class_exists('\App\Services\NotificationService')) {
            app(\App\Services\NotificationService::class)
                ->sendTransitionNotification($procedure, $actionType);
        }
    }
}
