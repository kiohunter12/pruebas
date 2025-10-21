<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStepDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_step_id',
        'tipo_documento_id',
        'es_obligatorio',
        'orden',
        'descripcion',
    ];

    protected $casts = [
        'es_obligatorio' => 'boolean',
        'orden' => 'integer',
    ];

    public function workflowStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }
}