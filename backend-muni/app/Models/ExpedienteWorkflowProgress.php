<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ExpedienteWorkflowProgress extends Model
{
    protected $table = 'expediente_workflow_progress';

    protected $fillable = [
        'expediente_id',
        'workflow_step_id',
        'estado',
        'asignado_a',
        'fecha_inicio',
        'fecha_limite',
        'fecha_completado',
        'completado_por',
        'comentarios',
        'motivo_rechazo',
        'documentos_adjuntos',
        'metadata'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_limite' => 'datetime', 
        'fecha_completado' => 'datetime',
        'documentos_adjuntos' => 'array',
        'metadata' => 'array'
    ];

    // Estados posibles
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_EN_PROCESO = 'en_proceso';
    const ESTADO_APROBADO = 'aprobado';
    const ESTADO_RECHAZADO = 'rechazado';
    const ESTADO_OBSERVADO = 'observado';

    /**
     * Relación con expediente
     */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    /**
     * Relación con etapa del flujo
     */
    public function workflowStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class);
    }

    /**
     * Usuario asignado a esta etapa
     */
    public function asignado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    /**
     * Usuario que completó la etapa
     */
    public function completadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completado_por');
    }

    /**
     * Scope para etapas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    /**
     * Scope para etapas en proceso
     */
    public function scopeEnProceso($query)
    {
        return $query->where('estado', self::ESTADO_EN_PROCESO);
    }

    /**
     * Scope para etapas vencidas
     */
    public function scopeVencidas($query)
    {
        return $query->where('fecha_limite', '<', Carbon::now())
                    ->whereIn('estado', [self::ESTADO_PENDIENTE, self::ESTADO_EN_PROCESO]);
    }

    /**
     * Marcar etapa como en proceso
     */
    public function iniciar($usuarioId = null)
    {
        $this->update([
            'estado' => self::ESTADO_EN_PROCESO,
            'fecha_inicio' => Carbon::now(),
            'asignado_a' => $usuarioId ?? auth()->id()
        ]);
    }

    /**
     * Aprobar la etapa y pasar a la siguiente
     */
    public function aprobar($comentarios = null, $documentos = null)
    {
        $this->update([
            'estado' => self::ESTADO_APROBADO,
            'fecha_completado' => Carbon::now(),
            'completado_por' => auth()->id(),
            'comentarios' => $comentarios,
            'documentos_adjuntos' => $documentos
        ]);

        // Activar siguiente etapa si existe
        $siguienteEtapa = $this->workflowStep->siguienteEtapa();
        if ($siguienteEtapa) {
            $this->expediente->activarSiguienteEtapa($siguienteEtapa);
        } else {
            // Es la última etapa, finalizar expediente
            $this->expediente->update(['status' => Expediente::STATUS_FINALIZADO]);
        }
    }

    /**
     * Rechazar la etapa
     */
    public function rechazar($motivo, $comentarios = null)
    {
        $this->update([
            'estado' => self::ESTADO_RECHAZADO,
            'fecha_completado' => Carbon::now(),
            'completado_por' => auth()->id(),
            'motivo_rechazo' => $motivo,
            'comentarios' => $comentarios
        ]);

        // Marcar expediente como rechazado
        $this->expediente->update(['status' => Expediente::STATUS_RECHAZADO]);
    }
}
