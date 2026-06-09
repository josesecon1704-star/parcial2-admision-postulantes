<?php

// ============================================================
// DESTINO: database/migrations/2026_01_01_000004_add_id_grupo_to_inscripcion.php
// Agrega id_grupo a tbl_inscripcion y distribuye postulantes
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar columna id_grupo si no existe
        if (!Schema::hasColumn('tbl_inscripcion', 'id_grupo')) {
            DB::statement('
                ALTER TABLE tbl_inscripcion
                ADD COLUMN id_grupo INT,
                ADD CONSTRAINT fk_inscripcion_grupo
                    FOREIGN KEY (id_grupo)
                    REFERENCES tbl_grupo(id_grupo)
                    ON UPDATE CASCADE ON DELETE SET NULL
            ');
        }

        // Distribuir inscripciones procesadas en grupos de 70
        DB::statement("
            UPDATE tbl_inscripcion i
            SET id_grupo = g.id_grupo
            FROM (
                SELECT
                    id_inscripcion,
                    LEAST(
                        CEIL(ROW_NUMBER() OVER (ORDER BY id_inscripcion) / 70.0)::INT,
                        (SELECT COUNT(*) FROM tbl_grupo)
                    ) AS id_grupo
                FROM tbl_inscripcion
                WHERE txt_estado_inscripcion = 'PROCESADO'
            ) g
            WHERE i.id_inscripcion = g.id_inscripcion
        ");
    }

    public function down(): void
    {
        DB::statement('
            ALTER TABLE tbl_inscripcion
            DROP CONSTRAINT IF EXISTS fk_inscripcion_grupo,
            DROP COLUMN IF EXISTS id_grupo
        ');
    }
};
