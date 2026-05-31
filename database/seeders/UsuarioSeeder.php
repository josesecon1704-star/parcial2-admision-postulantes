<?php

namespace Database\Seeders;

// ============================================================
// DESTINO: database/seeders/UsuarioSeeder.php
//
// Inserta en tbl_usuario usando columnas reales:
//   txt_username | txt_password | txt_email |
//   bol_estado | fch_creacion | id_rol
// ============================================================

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener id_rol de cada rol (PK real de tbl_rol)
        $roles = Rol::pluck('id_rol', 'txt_nombre');

        $usuarios = [
            [
                'id_rol'       => $roles['ADMINISTRADOR'],
                'txt_username' => 'admin',
                'txt_email'    => 'admin@ficct.edu.bo',
                'txt_password' => Hash::make('Admin123!'),
                'bol_estado'   => true,
                'fch_creacion' => now(),
            ],
            [
                'id_rol'       => $roles['DOCENTE'],
                'txt_username' => 'doc.perez',
                'txt_email'    => 'jperez@ficct.edu.bo',
                'txt_password' => Hash::make('Docente123!'),
                'bol_estado'   => true,
                'fch_creacion' => now(),
            ],
            [
                'id_rol'       => $roles['DOCENTE'],
                'txt_username' => 'doc.garcia',
                'txt_email'    => 'mgarcia@ficct.edu.bo',
                'txt_password' => Hash::make('Docente123!'),
                'bol_estado'   => true,
                'fch_creacion' => now(),
            ],
            [
                'id_rol'       => $roles['COORDINADOR'],
                'txt_username' => 'sec.rojas',
                'txt_email'    => 'lrojas@ficct.edu.bo',
                'txt_password' => Hash::make('Coord123!'),
                'bol_estado'   => true,
                'fch_creacion' => now(),
            ],
            [
                'id_rol'       => $roles['OPERADOR'],
                'txt_username' => 'dir.flores',
                'txt_email'    => 'cflores@ficct.edu.bo',
                'txt_password' => Hash::make('Operador123!'),
                'bol_estado'   => true,
                'fch_creacion' => now(),
            ],
        ];

        foreach ($usuarios as $data) {
            Usuario::firstOrCreate(
                ['txt_email' => $data['txt_email']],
                $data
            );
        }

        $this->command->info('✓ tbl_usuario: usuarios de prueba creados.');
        $this->command->table(
            ['Username', 'Email', 'Password', 'Rol'],
            [
                ['admin',      'admin@ficct.edu.bo',    'Admin123!',      'ADMINISTRADOR'],
                ['doc.perez',  'jperez@ficct.edu.bo',   'Docente123!',    'DOCENTE'],
                ['doc.garcia', 'mgarcia@ficct.edu.bo',  'Docente123!',    'DOCENTE'],
                ['sec.rojas',  'lrojas@ficct.edu.bo',   'Secretaria123!', 'SECRETARIA'],
                ['dir.flores', 'cflores@ficct.edu.bo',  'Director123!',   'DIRECTOR'],
            ]
        );
    }
}
