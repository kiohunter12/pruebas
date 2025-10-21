<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowTransition extends Model
{
    protected $table = 'workflow_transitions';

    protected $fillable = [
        'workflow_id',
        'from_step_id',
        'to_step_id',
        'nombre',
        'descripcion',
        'condicion',
        'reglas',
        'automatica',
        'orden',
        'activo'
    ];

    protected $casts = [
        'reglas' => 'array',
        'automatica' => 'boolean',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function fromStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'from_step_id');
    }

    public function toStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'to_step_id');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeAutomaticas($query)
    {
        return $query->where('automatica', true);
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden');
    }

    // Métodos útiles
    public function isAutomatic(): bool
    {
        return $this->automatica;
    }

    public function canExecute(Expediente $expediente, User $user): bool
    {
        // Evaluate if this transition can be executed based on rules
        if (empty($this->reglas)) {
            return true;
        }

        foreach ($this->reglas as $rule) {
            if (!$this->evaluateRule($rule, $expediente, $user)) {
                return false;
            }
        }

        return true;
    }

    protected function evaluateRule(array $rule, Expediente $expediente, User $user): bool
    {
        switch ($rule['type'] ?? 'role') {
            case 'role':
                return $user->hasRole($rule['value']);
            
            case 'gerencia':
                return $user->gerencia_id == $expediente->gerencia_id;
            
            case 'field':
                $field = $rule['field'];
                $operator = $rule['operator'] ?? '==';
                $value = $rule['value'];
                
                return $this->compareValues($expediente->$field, $operator, $value);
                
            default:
                return true;
        }
    }

    protected function compareValues($actual, string $operator, $expected): bool
    {
        return match ($operator) {
            '==' => $actual == $expected,
            '!=' => $actual != $expected,
            '>' => $actual > $expected,
            '<' => $actual < $expected,
            '>=' => $actual >= $expected,
            '<=' => $actual <= $expected,
            'in' => in_array($actual, (array)$expected),
            'not_in' => !in_array($actual, (array)$expected),
            default => false
        };
    }
}