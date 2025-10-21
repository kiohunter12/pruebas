<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialExpediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediente_id',
        'usuario_id',
        'accion',
        'estado_anterior',
        'estado_nuevo',
        'descripcion',
        'datos_adicionales',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expediente_id' => 'integer',
        'usuario_id' => 'integer',
        'datos_adicionales' => 'array',
    ];

    // Acciones del historial
    const ACCION_CREAR = 'crear';
    const ACCION_DERIVAR = 'derivar';
    const ACCION_REVISION_TECNICA = 'revision_tecnica';
    const ACCION_REVISION_LEGAL = 'revision_legal';
    const ACCION_EMITIR_RESOLUCION = 'emitir_resolucion';
    const ACCION_FIRMAR = 'firmar';
    const ACCION_NOTIFICAR = 'notificar';
    const ACCION_RECHAZAR = 'rechazar';
    const ACCION_ACTUALIZAR = 'actualizar';
    const ACCION_COMENTAR = 'comentar';

    public static function getAcciones(): array
    {
        return [
            self::ACCION_CREAR => 'Crear Expediente',
            self::ACCION_DERIVAR => 'Derivar Expediente',
            self::ACCION_REVISION_TECNICA => 'Revisión Técnica',
            self::ACCION_REVISION_LEGAL => 'Revisión Legal',
            self::ACCION_EMITIR_RESOLUCION => 'Emitir Resolución',
            self::ACCION_FIRMAR => 'Firmar Resolución',
            self::ACCION_NOTIFICAR => 'Notificar Ciudadano',
            self::ACCION_RECHAZAR => 'Rechazar Expediente',
            self::ACCION_ACTUALIZAR => 'Actualizar Expediente',
            self::ACCION_COMENTAR => 'Agregar Comentario',
        ];
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAccionTextoAttribute(): string
    {
        return self::getAcciones()[$this->accion] ?? 'Desconocida';
    }

    public function getEstadoAnteriorTextoAttribute(): string
    {
        return Expediente::getEstados()[$this->estado_anterior] ?? 'N/A';
    }

    public function getEstadoNuevoTextoAttribute(): string
    {
        return Expediente::getEstados()[$this->estado_nuevo] ?? 'N/A';
    }

    public function scopePorAccion($query, $accion)
    {
        return $query->where('accion', $accion);
    }

    public function scopePorUsuario($query, $userId)
    {
        return $query->where('usuario_id', $userId);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado_nuevo', $estado);
    }

    public function scopeRecientes($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
}
