<?php
namespace Database\Seeders;
// DESTINO: database/seeders/CatalogoSeeder.php
// Corre todos los catálogos del sistema en un solo seeder
use App\Models\Aula;
use App\Models\Carrera;
use App\Models\Gestion;
use App\Models\Materia;
use App\Models\Requisito;
use App\Models\Turno;
use Illuminate\Database\Seeder;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        // ── tbl_turno ────────────────────────────────────────
        foreach (['Mañana', 'Tarde', 'Noche'] as $t) {
            Turno::firstOrCreate(['txt_turno' => $t]);
        }
        $this->command->info('✓ tbl_turno: 3 turnos');

        // ── tbl_aula ─────────────────────────────────────────
        $aulas = [
            ['int_piso' => 1, 'txt_nro_aula' => '101'],
            ['int_piso' => 1, 'txt_nro_aula' => '102'],
            ['int_piso' => 2, 'txt_nro_aula' => '201'],
            ['int_piso' => 2, 'txt_nro_aula' => '202'],
            ['int_piso' => 3, 'txt_nro_aula' => '301'],
        ];
        foreach ($aulas as $a) {
            Aula::firstOrCreate(
                ['int_piso' => $a['int_piso'], 'txt_nro_aula' => $a['txt_nro_aula']]
            );
        }
        $this->command->info('✓ tbl_aula: 5 aulas');

        // ── tbl_materia (exactamente 4 — regla del examen) ───
        foreach (['Computación', 'Matemáticas', 'Inglés', 'Física'] as $m) {
            Materia::firstOrCreate(['txt_nombre' => $m]);
        }
        $this->command->info('✓ tbl_materia: 4 materias del examen');

        // ── tbl_carrera ──────────────────────────────────────
        $carreras = [
            ['txt_nombre' => 'Ingeniería de Sistemas',      'int_cupo' => 50],
            ['txt_nombre' => 'Ingeniería de Telecomunicaciones', 'int_cupo' => 40],
            ['txt_nombre' => 'Ingeniería Electrónica',      'int_cupo' => 35],
        ];
        foreach ($carreras as $c) {
            Carrera::firstOrCreate(['txt_nombre' => $c['txt_nombre']], $c);
        }
        $this->command->info('✓ tbl_carrera: 3 carreras FICCT');

        // ── tbl_gestion ──────────────────────────────────────
        Gestion::firstOrCreate(
            ['int_año' => 2026, 'txt_periodo' => 'I'],
        );
        $this->command->info('✓ tbl_gestion: gestión 2026-I');

        // ── tbl_requisito ────────────────────────────────────
        $requisitos = [
            'Título de Bachiller original',
            'Fotocopia de Cédula de Identidad',
            'Fotografías 4x4 fondo rojo (2 unidades)',
            'Formulario de inscripción firmado',
            'Recibo de pago de inscripción',
        ];
        foreach ($requisitos as $r) {
            Requisito::firstOrCreate(['txt_descripcion_requisito' => $r]);
        }
        $this->command->info('✓ tbl_requisito: 5 requisitos');
    }
}
