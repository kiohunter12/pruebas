<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gerencia extends Model
{
    use HasFactory;
    // Explicit table name to avoid incorrect pluralization (Doctrine inflector may
    // produce unexpected results for Spanish nouns). The migration creates
    // the `gerencias` table.
    protected $table = 'gerencias';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'tipo', // 'gerencia' o 'subgerencia'
        'gerencia_padre_id', // null para gerencias principales, ID para subgerencias
        'responsable_id', // Usuario responsable de la gerencia
        'cargo_responsable', // Cargo del responsable (Alcalde, Gerente, etc.)
        'activo',
        'orden',
        'flujos_permitidos', // JSON con tipos de trámite que puede procesar
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
        'flujos_permitidos' => 'array',
    ];

    // Tipos de gerencia
    const TIPO_GERENCIA = 'gerencia';
    const TIPO_SUBGERENCIA = 'subgerencia';

    // Estados
    const ESTADO_ACTIVA = true;
    const ESTADO_INACTIVA = false;

    public static function getTipos(): array
    {
        return [
            self::TIPO_GERENCIA => 'Gerencia',
            self::TIPO_SUBGERENCIA => 'Subgerencia',
        ];
    }

    public function getTipoTextoAttribute(): string
    {
        return self::getTipos()[$this->tipo] ?? 'Desconocido';
    }

    // Relaciones
    public function gerenciaPadre(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_padre_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function subgerencias(): HasMany
    {
        return $this->hasMany(Gerencia::class, 'gerencia_padre_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'gerencia_id');
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'gerencia_id');
    }

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }

    public function tipoTramites(): HasMany
    {
        return $this->hasMany(TipoTramite::class, 'gerencia_id');
    }

    public function workflows(): HasMany
    {
        return $this->hasMany(Workflow::class, 'gerencia_id');
    }

    public function workflowSteps(): HasMany
    {
        return $this->hasMany(WorkflowStep::class);
    }

    // Métodos de negocio
    public function esGerencia(): bool
    {
        return $this->tipo === self::TIPO_GERENCIA;
    }

    public function esSubgerencia(): bool
    {
        return $this->tipo === self::TIPO_SUBGERENCIA;
    }

    public function puedeProcesarTramite(string $tipoTramite): bool
    {
        if (empty($this->flujos_permitidos)) {
            return true; // Si no hay restricciones, puede procesar todo
        }

        return in_array($tipoTramite, $this->flujos_permitidos);
    }

    public function getFlujosPermitidosTextoAttribute(): string
    {
        if (empty($this->flujos_permitidos)) {
            return 'Todos los trámites';
        }

        $tipos = Expediente::getTiposTramite();
        $flujos = array_map(function($flujo) use ($tipos) {
            return $tipos[$flujo] ?? $flujo;
        }, $this->flujos_permitidos);

        return implode(', ', $flujos);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Use a custom Eloquent builder to transparently fix legacy/cached queries
     * that reference the wrong column name `activa`.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \App\Models\Builders\GerenciaBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new \App\Models\Builders\GerenciaBuilder($query);
    }

    public function scopeGerencias($query)
    {
        return $query->where('tipo', self::TIPO_GERENCIA);
    }

    public function scopeSubgerencias($query)
    {
        return $query->where('tipo', self::TIPO_SUBGERENCIA);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }

    public function scopePorFlujo($query, $tipoTramite)
    {
        return $query->where(function($q) use ($tipoTramite) {
            $q->whereNull('flujos_permitidos')
              ->orWhereJsonContains('flujos_permitidos', $tipoTramite);
        });
    }
}
