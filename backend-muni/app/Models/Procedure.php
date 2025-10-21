<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procedure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tupa_code',
        'name',
        'description',
        'requirements',
        'fee',
        'max_days',
        'department',
        'silence_type',
        'is_active'
    ];

    protected $casts = [
        'requirements' => 'array',
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
        'max_days' => 'integer'
    ];

    /**
     * Get the expedients for the procedure.
     */
    public function expedients(): HasMany
    {
        return $this->hasMany(Expedient::class);
    }

    /**
     * Scope a query to only include active procedures.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get procedure by TUPA code.
     */
    public function scopeByTupaCode($query, $tupaCode)
    {
        return $query->where('tupa_code', $tupaCode);
    }
}