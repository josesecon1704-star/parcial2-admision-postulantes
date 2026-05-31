<?php

namespace Database\Seeders;

// ============================================================
// DESTINO: database/seeders/RolSeeder.php
//
// Inserta en tbl_rol usando las columnas reales:
//   txt_nombre | txt_descripcion | fch_creacion
// ============================================================

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'txt_nombre'      => 'ADMINISTRADOR',
                'txt_descripcion' => 'Acceso total al sistema',
                'fch_creacion'    => now(),
            ],
            [
                'txt_nombre'      => 'DOCENTE',
                'txt_descripcion' => 'Gestión académica y registro de notas',
                'fch_creacion'    => now(),
            ],
            [
                'txt_nombre'      => 'SECRETARIA',
                'txt_descripcion' => 'Registro de postulantes y soporte administrativo',
                'fch_creacion'    => now(),
            ],
            [
                'txt_nombre'      => 'POSTULANTE',
                'txt_descripcion' => 'Acceso limitado: ver inscripción y resultados',
                'fch_creacion'    => now(),
            ],
            [
                'txt_nombre'      => 'DIRECTOR',
                'txt_descripcion' => 'Supervisión general y acceso a reportes ejecutivos',
                'fch_creacion'    => now(),
            ],
        ];

        foreach ($roles as $rol) {
            // firstOrCreate evita duplicados si ya existen
            Rol::firstOrCreate(
                ['txt_nombre' => $rol['txt_nombre']],
                $rol
            );
        }

        $this->command->info('✓ tbl_rol: 5 roles creados/verificados.');
    }
}
