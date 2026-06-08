<?php
namespace Database\Seeders;
// DESTINO: database/seeders/DatabaseSeeder.php  (REEMPLAZAR)
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('══════════════════════════════════════');
        $this->command->info('  Sistema Admisión FICCT — Seeders   ');
        $this->command->info('══════════════════════════════════════');

        $this->call([
            PoblacionSeeder::class,
            RolSeeder::class,       // tbl_rol
            UsuarioSeeder::class,   // tbl_usuario
            //CatalogoSeeder::class,  // tbl_turno, tbl_aula, tbl_materia, tbl_carrera, tbl_gestion, tbl_requisito
        ]);

        $this->command->info('✓ Todos los seeders ejecutados.');
    }
}