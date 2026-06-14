<?php

namespace App\Models;

// ============================================================
// Mapea exactamente la tabla tbl_usuario de tu PostgreSQL:
//   id_usuario | txt_username | txt_password | txt_email |
//   bol_estado | fch_ultimo_acceso | fch_creacion |
//   fch_actualizacion | id_rol
// ============================================================

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // ── Tabla real en PostgreSQL ────────────────────────────
    protected $table = 'tbl_usuario';

    // ── PK no estándar ──────────────────────────────────────
    protected $primaryKey = 'id_usuario';

    // ── Sin timestamps automáticos de Laravel ───────────────
    // (la tabla tiene sus propias columnas fch_creacion / fch_actualizacion)
    public $timestamps = false;

    // ── Campos asignables ───────────────────────────────────
    protected $fillable = [
        'id_rol',
        'txt_username',
        'txt_password',
        'txt_email',
        'bol_estado',
        'fch_ultimo_acceso',
        'fch_creacion',
        'fch_actualizacion',
    ];

    // ── Campos ocultos en respuestas JSON ───────────────────
    protected $hidden = [
        'txt_password',
    ];

    protected $casts = [
        'bol_estado'         => 'boolean',
        'fch_ultimo_acceso'  => 'datetime',
        'fch_creacion'       => 'datetime',
        'fch_actualizacion'  => 'datetime',
    ];

    // ── CRÍTICO: Laravel busca 'password' por defecto ───────
    // Como tu columna se llama txt_password, lo indicamos aquí
    public function getAuthPassword(): string
    {
        return $this->txt_password;
    }
    
    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }

    // ── JWT: requerido por tymon/jwt-auth ───────────────────
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey(); // devuelve id_usuario
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'rol'      => $this->rol->txt_nombre ?? null,
            'username' => $this->txt_username,
            'email'    => $this->txt_email,
        ];
    }

    // ── Relaciones ──────────────────────────────────────────
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // ── Helpers de negocio ──────────────────────────────────
    public function tieneRol(string $nombreRol): bool
    {
        return $this->rol?->txt_nombre === $nombreRol;
    }

    public function esAdmin(): bool
    {
        return $this->tieneRol('ADMINISTRADOR');
    }
}
