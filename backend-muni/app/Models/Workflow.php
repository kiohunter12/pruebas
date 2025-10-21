<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends Model
{
    protected $table = 'workflows';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'tipo',
        'configuracion',
        'activo',
        'gerencia_id',
        'created_by'
    ];

    protected $casts = [
        'configuracion' => 'array',
        'activo' => 'boolean'
    ];

    public function gerencia(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowStep::class, 'workflow_id')->orderBy('orden');
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'workflow_id');
    }

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class, 'workflow_id');
    }

    public function getStepById($stepId)
    {
        return $this->steps()->find($stepId);
    }

    public function getInitialStep()
    {
        return $this->steps()->where('tipo', 'inicio')->first() ?? $this->steps()->orderBy('orden')->first();
    }

    public function getFinalSteps()
    {
        return $this->steps()->where('tipo', 'fin')->get();
    }

    public function getNextSteps($currentStepId)
    {
        return $this->transitions()->where('from_step_id', $currentStepId)->with('toStep')->get()->pluck('toStep');
    }

    public function getPreviousSteps($currentStepId)
    {
        return $this->transitions()->where('to_step_id', $currentStepId)->with('fromStep')->get()->pluck('fromStep');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorGerencia($query, $gerenciaId)
    {
        return $query->where('gerencia_id', $gerenciaId);
    }
}
