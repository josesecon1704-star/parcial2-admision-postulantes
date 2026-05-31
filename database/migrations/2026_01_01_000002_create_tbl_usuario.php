<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ============================================================
// DESTINO: database/migrations/2026_01_01_000002_create_tbl_usuario.php
//
// TABLA REAL EN PostgreSQL:
//   tbl_usuario (id_usuario, txt_username, txt_password, txt_email,
//                bol_estado, fch_ultimo_acceso, fch_creacion,
//                fch_actualizacion, id_rol)
//
// NOTA: Laravel por defecto busca 'users'. En config/auth.php
// apuntamos al modelo Usuario que usa esta tabla.
// ============================================================

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar la tabla 'users' de Laravel por defecto si existe
        Schema::dropIfExists('users');

        if (Schema::hasTable('tbl_usuario')) {
            return;
        }

        Schema::create('tbl_usuario', function (Blueprint $table) {
            // id_usuario INT GENERATED ALWAYS AS IDENTITY
            $table->id('id_usuario');

            // txt_username VARCHAR(50) NOT NULL UNIQUE
            $table->string('txt_username', 50)->unique();

            // txt_password VARCHAR(255) NOT NULL
            $table->string('txt_password', 255);

            // txt_email VARCHAR(100) NOT NULL UNIQUE
            $table->string('txt_email', 100)->unique();

            // bol_estado BOOLEAN DEFAULT TRUE NOT NULL
            $table->boolean('bol_estado')->default(true);

            // fch_ultimo_acceso TIMESTAMP NULL
            $table->timestamp('fch_ultimo_acceso')->nullable();

            // fch_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('fch_creacion')->useCurrent();

            // fch_actualizacion TIMESTAMP NULL
            $table->timestamp('fch_actualizacion')->nullable();

            // FK → tbl_rol
            $table->integer('id_rol')->unsigned();
            $table->foreign('id_rol', 'fk_usuario_rol')
                  ->references('id_rol')
                  ->on('tbl_rol')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_usuario');
    }
};
