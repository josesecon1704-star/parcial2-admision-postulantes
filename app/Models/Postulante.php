<?php

namespace App\Models;

// ============================================================
// DESTINO: app/Models/Postulante.php  (REEMPLAZAR el anterior)
//
// Tabla real: tbl_postulante
// Columnas:
//   id_postulante | txt_ci | txt_nombre | txt_telefono |
//   txt_correo    | fch_nacimiento | chr_sexo |
//   txt_direccion | txt_colegio | txt_ciudad
//
// Relaciones requeridas por PostulanteService::formatear():
//   - requisitos()    → BelongsToMany via tbl_requisito_postulante
//   - inscripciones() → HasMany via tbl_inscripcion
//   - evaluaciones()  → HasMany via tbl_evaluacion
//
// NOVEDAD: extends Authenticatable + implements JWTSubject
//   El guard JWT (api_postulante) requiere que el modelo cumpla
//   el contrato Illuminate\Contracts\Auth\Authenticatable
//   (getAuthIdentifierName, getAuthIdentifier, getAuthPassword, etc.).
//   Eso lo provee la clase base Authenticatable de Laravel — igual
//   que hace App\Models\Usuario.
//
//   El postulante NO está en tbl_usuario. Su "contraseña" es su CI
//   (texto plano, comparado directamente — no hay columna de password,
//   por eso getAuthPassword() devuelve '' y nunca se usa para Hash::check).
//   Login de postulante: AuthService::login() detecta por txt_correo
//   cuando no hay match en tbl_usuario, valida CI === password
//   y genera un JWT con claims custom { tipo: 'postulante', ... }.
// ============================================================

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Postulante extends Authenticatable implements JWTSubject
{
    protected $table      = 'tbl_postulante';
    protected $primaryKey = 'id_postulante';
    public    $timestamps = false;

    protected $fillable = [
        'txt_ci',
        'txt_nombre',
        'txt_telefono',
        'txt_correo',
        'fch_nacimiento',
        'chr_sexo',
        'txt_direccion',
        'txt_colegio',
        'txt_ciudad',
    ];

    protected $casts = [
        'fch_nacimiento' => 'date',
    ];

    // ── Relaciones ───────────────────────────────────────────

    /**
     * Requisitos entregados por el postulante.
     * Tabla pivote real: tbl_requisito_postulante
     * Necesario para loadCount('requisitos') en PostulanteService
     */
    public function requisitos(): BelongsToMany
    {
        return $this->belongsToMany(
            Requisito::class,           // Modelo relacionado
            'tbl_requisito_postulante', // Tabla pivote real en PostgreSQL
            'id_postulante',            // FK de esta tabla en el pivote
            'id_requisito'              // FK de la otra tabla en el pivote
        )->withPivot('fch_presentacion');
    }

    /**
     * Inscripciones del postulante.
     * Necesario para verificar inscripciones activas antes de eliminar
     * y para mostrar la última inscripción en show()
     */
    public function inscripciones(): HasMany
    {
        return $this->hasMany(
            Inscripcion::class,
            'id_postulante', // FK en tbl_inscripcion
            'id_postulante'  // PK en tbl_postulante
        );
    }

    /**
     * Evaluaciones (exámenes) del postulante.
     */
    public function evaluaciones(): HasMany
    {
        return $this->hasMany(
            Evaluacion::class,
            'id_postulante',
            'id_postulante'
        );
    }

    // ── Accessor: edad calculada desde fch_nacimiento ────────
    public function getEdadAttribute(): int
    {
        return $this->fch_nacimiento
            ? $this->fch_nacimiento->age
            : 0;
    }

    // ════════════════════════════════════════════════════════
    // Authenticatable: requerido por el guard JWT (api_postulante)
    // ════════════════════════════════════════════════════════

    /**
     * tbl_postulante no tiene columna de contraseña real.
     * El "password" (CI) se valida manualmente en AuthService::login()
     * comparando contra txt_ci, NO vía Hash::check ni este método.
     * Se sobreescribe para evitar que Eloquent intente leer una
     * columna 'password' inexistente.
     */
    public function getAuthPassword(): string
    {
        return '';
    }

    public function getAuthIdentifierName(): string
    {
        return 'id_postulante';
    }

    // ════════════════════════════════════════════════════════
    // JWT: requerido por tymon/jwt-auth (JWTSubject)
    // ════════════════════════════════════════════════════════

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey(); // id_postulante
    }

    /**
     * Claims custom para distinguir este token de uno de tbl_usuario.
     * 'tipo' => 'postulante' es la clave que usan JwtMiddleware/CheckRole
     * y el front (login.blade.php) para redirigir correctamente.
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'tipo'          => 'postulante',
            'rol'           => 'POSTULANTE',
            'id_postulante' => $this->id_postulante,
            'nombre'        => $this->txt_nombre,
            'email'         => $this->txt_correo,
        ];
    }
}