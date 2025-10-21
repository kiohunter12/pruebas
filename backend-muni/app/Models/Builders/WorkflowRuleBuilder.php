<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Custom builder for WorkflowRule model.
 * It rewrites queries that reference the column name "activo" to the real
 * column name "activa" so existing incorrect calls like
 * WorkflowRule::where('activo', true) keep working without changing all
 * occurrences in the codebase.
 */
class WorkflowRuleBuilder extends EloquentBuilder
{
    /**
     * Intercept where calls and rewrite the column name when needed.
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        // only transform simple string column names
        if (is_string($column) && $column === 'activo') {
            $column = 'activa';
        }

        return parent::where($column, $operator, $value, $boolean);
    }

    // Optionally you could override other where* helpers if you discover more patterns
}
