<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Builders\WorkflowRuleBuilder;

class WorkflowRule extends Model
{
    protected $fillable = [
        'nombre_regla',
        'tipo_tramite', 
        'palabra_clave',
        'gerencia_destino_id',
        'prioridad',
        'activa',
        'descripcion',
        'condiciones_adicionales',
        'created_by'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'prioridad' => 'integer',
        'condiciones_adicionales' => 'array'
    ];

    /**
     * Relación con la gerencia destino
     */
    public function gerenciaDestino(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_destino_id');
    }

    /**
     * Relación con el usuario que creó la regla
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope para obtener solo reglas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Use custom Eloquent builder for this model so we can transparently
     * accept legacy calls using "activo" as column name.
     */
    public function newEloquentBuilder($query)
    {
        return new WorkflowRuleBuilder($query);
    }

    /**
     * Scope para ordenar por prioridad
     */
    public function scopePorPrioridad($query)
    {
        return $query->orderBy('prioridad', 'desc');
    }

    /**
     * Buscar la regla más apropiada para un trámite
     */
    public static function encontrarReglaParaTramite($tipoTramite, $asunto = null)
    {
        $query = static::activas()->porPrioridad();

        // Buscar por tipo exacto primero
        $reglaExacta = $query->clone()->where('tipo_tramite', $tipoTramite)->first();
        if ($reglaExacta) {
            return $reglaExacta;
        }

        // Si hay asunto, buscar por palabra clave
        if ($asunto) {
            $reglaPorPalabra = $query->clone()
                ->whereNotNull('palabra_clave')
                ->get()
                ->first(function ($regla) use ($asunto) {
                    $palabrasClave = explode('|', $regla->palabra_clave);
                    foreach ($palabrasClave as $palabra) {
                        if (stripos($asunto, trim($palabra)) !== false) {
                            return true;
                        }
                    }
                    return false;
                });
            
            if ($reglaPorPalabra) {
                return $reglaPorPalabra;
            }
        }

        // Buscar regla genérica (tipo_tramite = 'general')
        return $query->clone()->where('tipo_tramite', 'general')->first();
    }
}
