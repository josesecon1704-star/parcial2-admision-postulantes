<?php
namespace Database\Seeders;
// DESTINO: database/seeders/DatabaseSeeder.php  (REEMPLAZAR)
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('══════════════════════════════════════');
        $this->command->info('  Sistema Admisión FICCT — Seeders   ');
        $this->command->info('══════════════════════════════════════');

        // PoblacionSeeder SOLO en local — en Railway borraría todos los datos
        if (app()->environment('local')) {
            $this->call([
                PoblacionSeeder::class,
                RolSeeder::class,
                UsuarioSeeder::class,
            ]);
            $this->command->info('✓ Seeders ejecutados (entorno local).');
        } else {
            // En producción: solo insertar roles y usuario admin si no existen
            $this->command->warn('⚠ Entorno producción — PoblacionSeeder omitido.');

            $totalRoles = DB::table('tbl_rol')->count();
            if ($totalRoles === 0) {
                $this->call([RolSeeder::class]);
                $this->command->info('✓ RolSeeder ejecutado (tabla vacía).');
            } else {
                $this->command->info("· tbl_rol ya tiene {$totalRoles} roles — omitido.");
            }

            $totalUsuarios = DB::table('tbl_usuario')->count();
            if ($totalUsuarios === 0) {
                $this->call([UsuarioSeeder::class]);
                $this->command->info('✓ UsuarioSeeder ejecutado (tabla vacía).');
            } else {
                $this->command->info("· tbl_usuario ya tiene {$totalUsuarios} usuarios — omitido.");
            }
        }

        $this->command->info('✓ Proceso completado.');
    }
}