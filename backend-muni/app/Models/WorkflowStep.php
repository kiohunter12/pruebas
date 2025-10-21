<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowStep extends Model
{
    protected $table = 'workflow_steps';

    protected $fillable = [
        'workflow_id',
        'nombre',
        'codigo',
        'descripcion',
        'orden',
        'tipo',
        'configuracion',
        'condiciones',
        'acciones',
        'requiere_aprobacion',
        'tiempo_estimado',
        'responsable_tipo',
        'responsable_id',
        'activo'
    ];

    protected $casts = [
        'configuracion' => 'array',
        'condiciones' => 'array',
        'acciones' => 'array',
        'requiere_aprobacion' => 'boolean',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function transitionsFrom(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'from_step_id');
    }

    public function transitionsTo(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'to_step_id');
    }

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class, 'current_step_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    /**
     * Relación con los documentos requeridos en este paso
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(WorkflowStepDocument::class);
    }

    /**
     * Relación con los tipos de documentos requeridos
     */
    public function tiposDocumentos()
    {
        return $this->belongsToMany(TipoDocumento::class, 'workflow_step_documents')
            ->withPivot('es_obligatorio', 'orden', 'descripcion')
            ->orderBy('orden');
    }

    // Scopes y métodos útiles
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function isInitialStep(): bool
    {
        return $this->tipo === 'inicio';
    }

    public function isFinalStep(): bool
    {
        return $this->tipo === 'fin';
    }

    public function requiresApproval(): bool
    {
        return $this->requiere_aprobacion;
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden');
    }

    public function nextSteps()
    {
        return static::where('workflow_id', $this->workflow_id)
            ->where('orden', '>', $this->orden)
            ->orderBy('orden')
            ->get();
    }

    public function previousSteps()
    {
        return static::where('workflow_id', $this->workflow_id)
            ->where('orden', '<', $this->orden)
            ->orderBy('orden', 'desc')
            ->get();
    }

    /**
     * Obtener la siguiente etapa en el workflow
     */
    public function siguienteEtapa()
    {
        return static::where('workflow_id', $this->workflow_id)
            ->where('orden', '>', $this->orden)
            ->orderBy('orden')
            ->first();
    }

    /**
     * Obtener la etapa anterior en el workflow
     */
    public function etapaAnterior()
    {
        return static::where('workflow_id', $this->workflow_id)
            ->where('orden', '<', $this->orden)
            ->orderBy('orden', 'desc')
            ->first();
    }

    /**
     * Verificar si es la última etapa del workflow
     */
    public function esUltimaEtapa(): bool
    {
        return $this->siguienteEtapa() === null;
    }

    /**
     * Verificar si es la primera etapa del workflow
     */
    public function esPrimeraEtapa(): bool
    {
        return $this->etapaAnterior() === null;
    }

    /**
     * Obtener etapas por gerencia
     */
    public static function etapasDeGerencia($gerenciaId)
    {
        return static::where('gerencia_id', $gerenciaId)
            ->orderBy('orden')
            ->get();
    }
}
