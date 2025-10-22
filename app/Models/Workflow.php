<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Workflow extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_tramite_id',
        'activo',
        'created_by'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowStep::class);
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class);
    }

    public function tipoTramite(): BelongsTo
    {
        return $this->belongsTo(TipoTramite::class);
    }
}
