<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Custom builder for Gerencia model.
 *
 * Some parts of the codebase (and cached views) incorrectly use the column
 * name "activa" when querying the `gerencias` table. The real column is
 * named `activo` (see migration). To avoid mass edits and regressions we
 * transparently rewrite simple where clauses that reference `activa` to
 * use `activo`.
 */
class GerenciaBuilder extends EloquentBuilder
{
    /**
     * Intercept simple where calls and rewrite the column name when needed.
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        // transform only simple string column names
        if (is_string($column) && $column === 'activa') {
            $column = 'activo';
        }

        return parent::where($column, $operator, $value, $boolean);
    }

    // If you discover other query helpers that cause issues (whereNull, whereIn, etc.)
    // you can override them here similarly.
}
