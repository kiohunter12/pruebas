<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gerencia_id',
        'activo',
        'estado',
        'dni',
        'telefono',
        'cargo',
        'ultimo_acceso'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean'
        ];
    }

    // Relación con Gerencia
    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class);
    }

    // Relación con expedientes asignados
    public function expedientesAsignados()
    {
        return $this->hasMany(Expediente::class, 'usuario_asignado_id');
    }

    // Relación con expedientes registrados
    public function expedientesRegistrados()
    {
        return $this->hasMany(Expediente::class, 'usuario_registro_id');
    }

    // Métodos de negocio
    public function perteneceAGerencia(int $gerenciaId): bool
    {
        return $this->gerencia_id === $gerenciaId;
    }

    public function puedeProcesarTramite(string $tipoTramite): bool
    {
        if (!$this->gerencia) {
            return false;
        }

        return $this->gerencia->puedeProcesarTramite($tipoTramite);
    }

    public function getGerenciaNombreAttribute(): string
    {
        return $this->gerencia ? $this->gerencia->nombre : 'Sin asignar';
    }
}
