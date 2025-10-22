<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Expediente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'expedientes';

    // Estados del expediente (compatibilidad con nombres anteriores en el código)
    const STATUS_INICIADO = 'iniciado';
    const STATUS_EN_PROCESO = 'en_proceso';
    const STATUS_OBSERVADO = 'observado';
    const STATUS_APROBADO = 'aprobado';
    const STATUS_RECHAZADO = 'rechazado';
    const STATUS_FINALIZADO = 'finalizado';
    const STATUS_ARCHIVADO = 'archivado';

    // Constantes usadas por el resto del código (nomenclatura en español)
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_EN_PROCESO = 'en_proceso';
    const ESTADO_OBSERVADO = 'observado';
    const ESTADO_APROBADO = 'aprobado';
    const ESTADO_RECHAZADO = 'rechazado';
    const ESTADO_FINALIZADO = 'finalizado';
    const ESTADO_ARCHIVADO = 'archivado';
    const ESTADO_EN_REVISION = 'en_revision';
    const ESTADO_REVISION_TECNICA = 'revision_tecnica';
    const ESTADO_REVISION_LEGAL = 'revision_legal';
    const ESTADO_RESOLUCION_EMITIDA = 'resolucion_emitida';
    const ESTADO_PENDIENTE_FIRMA = 'pendiente_firma';
    const ESTADO_FIRMADO = 'firmado';
    const ESTADO_NOTIFICADO = 'notificado';

    protected $fillable = [
        'numero',  // Nombre real de la columna en la BD
        'tracking_number',
        'citizen_id',
        'procedure_id',
        'workflow_id',
        'current_step_id',
        'tipo_tramite_id',
        'gerencia_id',
        'gerencia_padre_id',
        'responsable_id',
        'usuario_registro_id',
        'status',
        'estado',
        'priority',
        'subject',
        'description',
        'expected_response_date',
        'actual_response_date',
        'notes',
        'total_amount',
        'payment_status',
        'assigned_to',
        'metadata',
        // Campos adicionales para ciudadanos
        'numero_expediente',
        'solicitante_id',
        'solicitante_nombre',
        'solicitante_dni',
        'solicitante_email',
        'solicitante_telefono',
        'tipo_tramite',
        'asunto',
        'descripcion',
        'prioridad',
        'fecha_registro',
        'fecha_ingreso',
        'requiere_pago',
        'monto',
        'pagado'
    ];

    protected $casts = [
        'expected_response_date' => 'datetime',
        'actual_response_date' => 'datetime',
        'metadata' => 'array',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relaciones
    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    /**
     * Buscar un usuario responsable dentro de una gerencia por prioridad de roles.
     * Si no se encuentra en la gerencia, buscar en subgerencias.
     */
    protected function findResponsibleUserInGerencia(int $gerenciaId, array $priorityRoles)
    {
        // Buscar en la gerencia indicada por prioridad de roles
        foreach ($priorityRoles as $role) {
            $usuario = User::where('gerencia_id', $gerenciaId)
                ->whereHas('roles', function($q) use ($role) {
                    $q->where('name', $role);
                })
                ->first();

            if ($usuario) {
                return $usuario;
            }
        }

        // Si no hay en la gerencia, buscar en subgerencias
        $subGerencias = Gerencia::where('gerencia_padre_id', $gerenciaId)->pluck('id');
        if ($subGerencias->count() > 0) {
            foreach ($priorityRoles as $role) {
                $usuario = User::whereIn('gerencia_id', $subGerencias->toArray())
                    ->whereHas('roles', function($q) use ($role) {
                        $q->where('name', $role);
                    })
                    ->first();

                if ($usuario) {
                    return $usuario;
                }
            }
        }

        return null;
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function tipoTramite()
    {
        return $this->belongsTo(TipoTramite::class, 'tipo_tramite_id');
    }

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function subgerencia()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_padre_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(User::class, 'usuario_registro_id');
    }

    public function usuarioRevisionTecnica()
    {
        return $this->belongsTo(User::class, 'usuario_revision_tecnica_id');
    }

    public function usuarioRevisionLegal()
    {
        return $this->belongsTo(User::class, 'usuario_revision_legal_id');
    }

    public function usuarioResolucion()
    {
        return $this->belongsTo(User::class, 'usuario_resolucion_id');
    }

    public function usuarioFirma()
    {
        return $this->belongsTo(User::class, 'usuario_firma_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function files()
    {
        // Relación hacia documentos subidos del expediente
        return $this->hasMany(\App\Models\DocumentoExpediente::class, 'expediente_id');
    }

    // Alias para compatibilidad: algunos lugares usan 'documentos' en lugar de 'files'
    public function documentos()
    {
        return $this->files();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function actionLogs()
    {
        return $this->hasMany(ActionLog::class);
    }

    public function historial()
    {
        return $this->hasMany(HistorialExpediente::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // Métodos auxiliares
    public function isOverdue()
    {
        if (!$this->expected_response_date) {
            return false;
        }

        return Carbon::now()->isAfter($this->expected_response_date) && 
               !in_array($this->status, [self::STATUS_FINALIZADO, self::STATUS_ARCHIVADO]);
    }

    public function getDaysUntilDue()
    {
        if (!$this->expected_response_date) {
            return null;
        }

        return Carbon::now()->diffInDays($this->expected_response_date, false);
    }

    public function generateTrackingNumber()
    {
        $year = date('Y');
        $sequence = static::whereYear('created_at', $year)->count() + 1;
        $gerenciaCode = $this->gerencia ? $this->gerencia->code : 'GEN';
        
        return sprintf('%s-%04d-%06d', $gerenciaCode, $year, $sequence);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByGerencia($query, $gerenciaId)
    {
        return $query->where('gerencia_id', $gerenciaId);
    }

    public function scopeByCitizen($query, $citizenId)
    {
        return $query->where('citizen_id', $citizenId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_response_date', '<', Carbon::now())
                    ->whereNotIn('status', [self::STATUS_FINALIZADO, self::STATUS_ARCHIVADO]);
    }

    public function scopeByTrackingNumber($query, $trackingNumber)
    {
        return $query->where('tracking_number', $trackingNumber);
    }

    /**
     * Devuelve el array de estados (clave => etiqueta) usado por las vistas.
     */
    public static function getEstados(): array
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_EN_PROCESO => 'En proceso',
            self::ESTADO_OBSERVADO => 'Observado',
            self::ESTADO_APROBADO => 'Aprobado',
            self::ESTADO_RECHAZADO => 'Rechazado',
            self::ESTADO_FINALIZADO => 'Finalizado',
            self::ESTADO_ARCHIVADO => 'Archivado',
            self::ESTADO_EN_REVISION => 'En revisión',
            self::ESTADO_REVISION_TECNICA => 'Revisión técnica',
            self::ESTADO_REVISION_LEGAL => 'Revisión legal',
            self::ESTADO_RESOLUCION_EMITIDA => 'Resolución emitida',
            self::ESTADO_PENDIENTE_FIRMA => 'Pendiente de firma',
            self::ESTADO_FIRMADO => 'Firmado',
            self::ESTADO_NOTIFICADO => 'Notificado',
        ];
    }

    /**
     * Asignar automáticamente el expediente a una gerencia basándose en reglas de flujo
     */
    public function asignarAutomaticamente()
    {
        // Buscar una regla de flujo apropiada
        $regla = WorkflowRule::encontrarReglaParaTramite(
            $this->procedure->nombre ?? 'general',
            $this->subject
        );

        if ($regla) {
            $this->gerencia_id = $regla->gerencia_destino_id;
            $this->save();

            // Registrar en el historial
            HistorialExpediente::create([
                'expediente_id' => $this->id,
                'previous_status' => null,
                'new_status' => $this->status,
                'changed_by' => auth()->id(),
                'change_reason' => "Asignado automáticamente a {$regla->gerenciaDestino->nombre} por regla: {$regla->nombre_regla}",
                'change_date' => now(),
                'metadata' => [
                    'workflow_rule_id' => $regla->id,
                    'assignment_type' => 'automatic'
                ]
            ]);

            return $regla;
        }

        return null;
    }

    /**
     * Método estático para asignar automáticamente durante la creación
     */
    public static function crearConAsignacionAutomatica(array $datos)
    {
        $expediente = static::create($datos);
        $expediente->asignarAutomaticamente();
        $expediente->iniciarFlujoEtapas();
        return $expediente;
    }

    /**
     * Relación con el progreso de etapas del workflow
     */
    public function workflowProgress()
    {
        return $this->hasMany(ExpedienteWorkflowProgress::class);
    }

    /**
     * Relación con el workflow asignado
     */
    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    /**
     * Relación con el paso actual del workflow
     */
    public function currentStep()
    {
        return $this->belongsTo(WorkflowStep::class, 'current_step_id');
    }

    /**
     * Iniciar el flujo de etapas después de asignar a gerencia
     */
    public function iniciarFlujoEtapas()
    {
        if (!$this->gerencia_id) {
            return false;
        }

        // Obtener etapas de la gerencia
        $etapas = WorkflowStep::etapasDeGerencia($this->gerencia_id);
        
        foreach ($etapas as $etapa) {
            ExpedienteWorkflowProgress::create([
                'expediente_id' => $this->id,
                'workflow_step_id' => $etapa->id,
                'estado' => $etapa->orden === 1 ? 
                    ExpedienteWorkflowProgress::ESTADO_PENDIENTE : 
                    ExpedienteWorkflowProgress::ESTADO_PENDIENTE,
                'fecha_limite' => now()->addDays($etapa->dias_limite)
            ]);
        }

        // Iniciar la primera etapa
        $primeraEtapa = $this->workflowProgress()->first();
        if ($primeraEtapa) {
            $primeraEtapa->iniciar();
        }

        return true;
    }

    /**
     * Activar la siguiente etapa en la secuencia
     */
    public function activarSiguienteEtapa(WorkflowStep $siguienteEtapa)
    {
        \Log::info('🔄 Activando siguiente etapa', [
            'expediente' => $this->numero,
            'paso_siguiente' => $siguienteEtapa->nombre,
            'paso_siguiente_id' => $siguienteEtapa->id,
            'responsable_tipo' => $siguienteEtapa->responsable_tipo,
            'responsable_id' => $siguienteEtapa->responsable_id
        ]);
        
        // Actualizar el current_step_id del expediente
        $this->current_step_id = $siguienteEtapa->id;
        $this->save();

        // Determinar quién será el responsable de la siguiente etapa
        $responsableId = null;
        
        if ($siguienteEtapa->responsable_tipo === 'usuario' && $siguienteEtapa->responsable_id) {
            // Asignar al usuario específico definido en el workflow
            $responsableId = $siguienteEtapa->responsable_id;
            \Log::info('✅ Responsable es usuario', ['usuario_id' => $responsableId]);
        } elseif ($siguienteEtapa->responsable_tipo === 'gerencia' && $siguienteEtapa->responsable_id) {
            // Buscar un usuario de la gerencia especificada por prioridad de roles
            $priorityRoles = ['gerente', 'subgerente', 'jefe_gerencia'];
            $usuario = $this->findResponsibleUserInGerencia($siguienteEtapa->responsable_id, $priorityRoles);

            if ($usuario) {
                $responsableId = $usuario->id;
                \Log::info('✅ Responsable encontrado en gerencia (con prioridad)', [
                    'gerencia_id' => $siguienteEtapa->responsable_id,
                    'usuario' => $usuario->name,
                    'usuario_id' => $responsableId,
                    'rol' => $usuario->roles->pluck('name')->toArray()
                ]);
            } else {
                \Log::warning('⚠️ No se encontró usuario responsable en gerencia ni subgerencias', [
                    'gerencia_id' => $siguienteEtapa->responsable_id
                ]);
            }
        } elseif ($siguienteEtapa->responsable_tipo === 'rol') {
            // Buscar un usuario con el rol especificado en la misma gerencia
            $usuario = User::whereHas('roles', function($query) use ($siguienteEtapa) {
                $query->where('name', $siguienteEtapa->responsable_id);
            })
            ->where('gerencia_id', $this->gerencia_id)
            ->first();
            
            if ($usuario) {
                $responsableId = $usuario->id;
                \Log::info('✅ Responsable encontrado por rol', [
                    'rol' => $siguienteEtapa->responsable_id,
                    'usuario' => $usuario->name,
                    'usuario_id' => $responsableId
                ]);
            }
        }

        // Buscar o crear el progreso para la siguiente etapa
        $progreso = $this->workflowProgress()
            ->where('workflow_step_id', $siguienteEtapa->id)
            ->first();

        if ($progreso) {
            // Si existe, iniciarlo con el nuevo responsable
            $progreso->update([
                'estado' => ExpedienteWorkflowProgress::ESTADO_EN_PROCESO,
                'fecha_inicio' => now(),
                'asignado_a' => $responsableId,
            ]);
            \Log::info('✅ Progreso actualizado', ['progreso_id' => $progreso->id]);
        } else {
            // Si no existe, crearlo
            $progreso = ExpedienteWorkflowProgress::create([
                'expediente_id' => $this->id,
                'workflow_step_id' => $siguienteEtapa->id,
                'estado' => ExpedienteWorkflowProgress::ESTADO_EN_PROCESO,
                'fecha_inicio' => now(),
                'fecha_limite' => now()->addDays($siguienteEtapa->tiempo_estimado ?? 30),
                'asignado_a' => $responsableId,
            ]);
            \Log::info('✅ Progreso creado', [
                'progreso_id' => $progreso->id,
                'asignado_a' => $responsableId
            ]);
        }
        
        \Log::info('✅ Siguiente etapa activada exitosamente');
    }

    /**
     * Obtener la etapa actual en proceso
     */
    public function etapaActual()
    {
        return $this->workflowProgress()
            ->with('workflowStep')
            ->where('estado', ExpedienteWorkflowProgress::ESTADO_EN_PROCESO)
            ->first();
    }

    /**
     * Obtener todas las etapas pendientes
     */
    public function etapasPendientes()
    {
        return $this->workflowProgress()
            ->with('workflowStep')
            ->where('estado', ExpedienteWorkflowProgress::ESTADO_PENDIENTE)
            ->get();
    }

    /**
     * Verificar si puede avanzar a la siguiente etapa
     */
    public function puedeAvanzar()
    {
        $etapaActual = $this->etapaActual();
        if (!$etapaActual) {
            return false;
        }

        // Verificar si la etapa anterior está aprobada
        $etapaAnterior = $etapaActual->workflowStep->etapaAnterior();
        if ($etapaAnterior) {
            $progresoAnterior = $this->workflowProgress()
                ->where('workflow_step_id', $etapaAnterior->id)
                ->first();
            
            return $progresoAnterior && 
                   $progresoAnterior->estado === ExpedienteWorkflowProgress::ESTADO_APROBADO;
        }

        return true; // Primera etapa siempre puede proceder
    }

    // Eventos del modelo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expediente) {
            // Generar tracking_number solo si no se ha establecido numero
            if (!$expediente->tracking_number && !$expediente->numero) {
                $expediente->tracking_number = $expediente->generateTrackingNumber();
            }
            
            // Si no existe numero pero si tracking_number, copiar
            if (!$expediente->numero && $expediente->tracking_number) {
                $expediente->numero = $expediente->tracking_number;
            }
        });

        static::updating(function ($expediente) {
            // Registrar cambios en el historial
            if ($expediente->isDirty('status') || $expediente->isDirty('estado')) {
                $estadoAnterior = $expediente->getOriginal('estado') ?? $expediente->getOriginal('status');
                $estadoNuevo = $expediente->estado ?? $expediente->status;
                
                HistorialExpediente::create([
                    'expediente_id' => $expediente->id,
                    'usuario_id' => auth()->id(),
                    'accion' => 'Cambio de Estado',
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo' => $estadoNuevo,
                    'descripcion' => 'Estado actualizado automáticamente',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
        });
    }
}
