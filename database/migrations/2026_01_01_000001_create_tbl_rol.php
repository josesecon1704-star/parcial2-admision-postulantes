<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ============================================================
// DESTINO: database/migrations/2026_01_01_000001_create_tbl_rol.php
//
// TABLA REAL EN PostgreSQL:
//   tbl_rol (id_rol, txt_nombre, txt_descripcion, fch_creacion)
//
// IMPORTANTE: Esta migración NO crea la tabla si ya existe.
// Si ya tienes la BD creada, puedes saltar esta migración y
// usar solo los Seeders para poblar datos.
// ============================================================

return new class extends Migration
{
    public function up(): void
    {
        // Si la tabla ya existe en PostgreSQL, no hacer nada
        if (Schema::hasTable('tbl_rol')) {
            return;
        }

        Schema::create('tbl_rol', function (Blueprint $table) {
            // id_rol INT GENERATED ALWAYS AS IDENTITY
            $table->id('id_rol');

            // txt_nombre VARCHAR(50) NOT NULL UNIQUE
            $table->string('txt_nombre', 50)->unique();

            // txt_descripcion VARCHAR(255) NULL
            $table->string('txt_descripcion', 255)->nullable();

            // fch_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('fch_creacion')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_rol');
    }
};
