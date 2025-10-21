<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoExpediente extends Model
{
    use HasFactory;

    /**
     * Explicit table name: migration created 'documentos_expediente'
     * (note pluralization differs from Eloquent default for this class name).
     */
    protected $table = 'documentos_expediente';

    protected $fillable = [
        'expediente_id',
        'nombre',
        'descripcion',
        'tipo_documento',
        'archivo',
        'extension',
        'tamaño',
        'mime_type',
        'usuario_subio_id',
        'requerido',
        'aprobado',
        'observaciones',
    ];

    protected $casts = [
        'expediente_id' => 'integer',
        'usuario_subio_id' => 'integer',
        'requerido' => 'boolean',
        'aprobado' => 'boolean',
        'tamaño' => 'integer',
    ];

    // Tipos de documento
    const TIPO_DNI = 'dni';
    const TIPO_PLANOS = 'planos';
    const TIPO_ESTUDIOS = 'estudios';
    const TIPO_CERTIFICADOS = 'certificados';
    const TIPO_OTROS = 'otros';

    public static function getTiposDocumento(): array
    {
        return [
            self::TIPO_DNI => 'DNI',
            self::TIPO_PLANOS => 'Planos',
            self::TIPO_ESTUDIOS => 'Estudios',
            self::TIPO_CERTIFICADOS => 'Certificados',
            self::TIPO_OTROS => 'Otros',
        ];
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function usuarioSubio(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_subio_id');
    }

    public function getTipoDocumentoTextoAttribute(): string
    {
        return self::getTiposDocumento()[$this->tipo_documento] ?? 'Desconocido';
    }

    public function getTamañoFormateadoAttribute(): string
    {
        $bytes = $this->tamaño;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getUrlDescargaAttribute(): string
    {
        return route('documentos.download', $this->id);
    }

    public function scopeRequeridos($query)
    {
        return $query->where('requerido', true);
    }

    public function scopeAprobados($query)
    {
        return $query->where('aprobado', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }
}
