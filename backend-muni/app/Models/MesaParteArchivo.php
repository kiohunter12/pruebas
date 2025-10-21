<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MesaParteArchivo extends Model
{
    use HasFactory;

    protected $table = 'mesa_partes_archivos';

    protected $fillable = [
        'mesa_parte_id',
        'nombre_original',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_mime',
        'tamaño',
        'descripcion',
        'subido_por'
    ];

    public function mesaParte(): BelongsTo
    {
        return $this->belongsTo(MesaParte::class);
    }

    public function subidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subido_por');
    }
}
