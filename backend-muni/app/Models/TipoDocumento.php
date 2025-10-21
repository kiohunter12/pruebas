<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'campos_requeridos',
        'requiere_firma',
        'vigencia_dias',
        'activo'
    ];

    protected $casts = [
        'campos_requeridos' => 'array',
        'requiere_firma' => 'boolean',
        'activo' => 'boolean'
    ];

    // Scope para obtener solo los tipos activos
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    public function mesaPartes(): HasMany
    {
        return $this->hasMany(MesaParte::class);
    }

    /**
     * Relación many-to-many: tipos de trámite que requieren este documento.
     */
    public function tiposTramite(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            TipoTramite::class,
            'tipo_tramite_tipo_documento',
            'tipo_documento_id',
            'tipo_tramite_id'
        )->withTimestamps();
    }
}
