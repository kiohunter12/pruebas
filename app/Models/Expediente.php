<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expediente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero_expediente',
        'solicitante_nombre',
        'solicitante_email', 
        'solicitante_telefono',
        'solicitante_dni',
        'tipo_tramite_id',
        'gerencia_id',
        'estado',
        'prioridad',
        'observaciones'
    ];

    // Estados posibles del expediente
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_EN_PROCESO = 'en_proceso';
    const ESTADO_OBSERVADO = 'observado'; 
    const ESTADO_APROBADO = 'aprobado';
    const ESTADO_RECHAZADO = 'rechazado';

    public function gerencia(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function documentos(): HasMany 
    {
        return $this->hasMany(DocumentoExpediente::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialExpediente::class);
    }
}
