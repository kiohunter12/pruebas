<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gerencia extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'gerencia_padre_id',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }

    public function gerenciaPadre(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_padre_id');
    }

    public function subgerencias(): HasMany
    {
        return $this->hasMany(Gerencia::class, 'gerencia_padre_id');
    }
}
