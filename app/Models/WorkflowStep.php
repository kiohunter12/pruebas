<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowStep extends Model
{
    protected $fillable = [
        'workflow_id',
        'nombre',
        'descripcion',
        'orden',
        'gerencia_responsable_id',
        'tiempo_estimado',
        'es_automatico',
        'activo'
    ];

    protected $casts = [
        'es_automatico' => 'boolean',
        'activo' => 'boolean'
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function gerenciaResponsable(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_responsable_id');
    }

    public function transitionsFrom(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'from_step_id');
    }
}
