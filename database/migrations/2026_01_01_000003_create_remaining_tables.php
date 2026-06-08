<?php

// ============================================================
// DESTINO: database/migrations/2026_01_01_000003_create_remaining_tables.php
//
// Crea todas las tablas del sistema EXCEPTO tbl_rol y tbl_usuario
// que ya tienen sus propias migraciones.
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── MÓDULO SEGURIDAD ──────────────────────────────────
        if (!Schema::hasTable('tbl_auditoria')) {
            DB::statement('
                CREATE TABLE tbl_auditoria (
                    id_auditoria          BIGINT GENERATED ALWAYS AS IDENTITY,
                    txt_tabla             VARCHAR(100) NOT NULL,
                    txt_operacion         VARCHAR(50)  NOT NULL,
                    txt_usuario           VARCHAR(100) DEFAULT CURRENT_USER NOT NULL,
                    fch_evento            TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                    json_datos_anteriores JSONB,
                    json_datos_nuevos     JSONB,
                    CONSTRAINT pk_auditoria PRIMARY KEY (id_auditoria)
                )
            ');
        }

        if (!Schema::hasTable('tbl_reporte')) {
            DB::statement("
                CREATE TABLE tbl_reporte (
                    id_reporte       INT GENERATED ALWAYS AS IDENTITY,
                    txt_tipo_reporte VARCHAR(100) NOT NULL,
                    fch_generacion   TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                    txt_formato      VARCHAR(20) NOT NULL,
                    txt_estado       VARCHAR(30) DEFAULT 'GENERADO' NOT NULL,
                    id_usuario       INT NOT NULL,
                    CONSTRAINT pk_reporte          PRIMARY KEY (id_reporte),
                    CONSTRAINT chk_reporte_formato CHECK (txt_formato IN ('PDF','XLSX','CSV','JSON')),
                    CONSTRAINT fk_reporte_usuario  FOREIGN KEY (id_usuario)
                        REFERENCES tbl_usuario(id_usuario) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ");
        }

        // ── MÓDULO ADMISIÓN ───────────────────────────────────
        if (!Schema::hasTable('tbl_requisito')) {
            DB::statement('
                CREATE TABLE tbl_requisito (
                    id_requisito              INT GENERATED ALWAYS AS IDENTITY,
                    txt_descripcion_requisito VARCHAR(255) NOT NULL,
                    CONSTRAINT pk_requisito PRIMARY KEY (id_requisito)
                )
            ');
        }

        if (!Schema::hasTable('tbl_postulante')) {
            DB::statement("
                CREATE TABLE tbl_postulante (
                    id_postulante  INT GENERATED ALWAYS AS IDENTITY,
                    txt_ci         VARCHAR(20)  NOT NULL,
                    txt_nombre     VARCHAR(150) NOT NULL,
                    txt_telefono   VARCHAR(20),
                    txt_correo     VARCHAR(100) NOT NULL,
                    fch_nacimiento DATE NOT NULL,
                    chr_sexo       CHAR(1) NOT NULL,
                    txt_direccion  VARCHAR(255),
                    txt_colegio    VARCHAR(150),
                    txt_ciudad     VARCHAR(100),
                    CONSTRAINT pk_postulante        PRIMARY KEY (id_postulante),
                    CONSTRAINT uq_postulante_ci     UNIQUE (txt_ci),
                    CONSTRAINT uq_postulante_correo UNIQUE (txt_correo),
                    CONSTRAINT chk_postulante_sexo  CHECK (chr_sexo IN ('M','F','X'))
                )
            ");
        }

        if (!Schema::hasTable('tbl_requisito_postulante')) {
            DB::statement('
                CREATE TABLE tbl_requisito_postulante (
                    id_requisito     INT NOT NULL,
                    id_postulante    INT NOT NULL,
                    fch_presentacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                    CONSTRAINT pk_requisito_postulante  PRIMARY KEY (id_requisito, id_postulante),
                    CONSTRAINT fk_req_post_requisito    FOREIGN KEY (id_requisito)
                        REFERENCES tbl_requisito(id_requisito) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fk_req_post_postulante   FOREIGN KEY (id_postulante)
                        REFERENCES tbl_postulante(id_postulante) ON UPDATE CASCADE ON DELETE CASCADE
                )
            ');
        }

        if (!Schema::hasTable('tbl_carrera')) {
            DB::statement('
                CREATE TABLE tbl_carrera (
                    id_carrera  INT GENERATED ALWAYS AS IDENTITY,
                    txt_nombre  VARCHAR(100) NOT NULL,
                    int_cupo    INT NOT NULL,
                    CONSTRAINT pk_carrera        PRIMARY KEY (id_carrera),
                    CONSTRAINT uq_carrera_nombre UNIQUE (txt_nombre),
                    CONSTRAINT chk_carrera_cupo  CHECK (int_cupo >= 0)
                )
            ');
        }

        if (!Schema::hasTable('tbl_gestion')) {
            DB::statement("
                CREATE TABLE tbl_gestion (
                    id_gestion  INT GENERATED ALWAYS AS IDENTITY,
                    int_año     INT NOT NULL,
                    txt_periodo VARCHAR(20) NOT NULL,
                    CONSTRAINT pk_gestion         PRIMARY KEY (id_gestion),
                    CONSTRAINT uq_gestion_periodo UNIQUE (int_año, txt_periodo)
                )
            ");
        }

        if (!Schema::hasTable('tbl_inscripcion')) {
            DB::statement("
                CREATE TABLE tbl_inscripcion (
                    id_inscripcion         INT GENERATED ALWAYS AS IDENTITY,
                    fch_inscripcion        TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                    txt_estado_inscripcion VARCHAR(30) DEFAULT 'PENDIENTE' NOT NULL,
                    id_postulante          INT NOT NULL,
                    id_gestion             INT NOT NULL,
                    CONSTRAINT pk_inscripcion             PRIMARY KEY (id_inscripcion),
                    CONSTRAINT fk_inscripcion_postulante  FOREIGN KEY (id_postulante)
                        REFERENCES tbl_postulante(id_postulante) ON UPDATE CASCADE ON DELETE RESTRICT,
                    CONSTRAINT fk_inscripcion_gestion     FOREIGN KEY (id_gestion)
                        REFERENCES tbl_gestion(id_gestion) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ");
        }

        if (!Schema::hasTable('tbl_inscripcion_carrera')) {
            DB::statement('
                CREATE TABLE tbl_inscripcion_carrera (
                    id_inscripcion INT NOT NULL,
                    id_carrera     INT NOT NULL,
                    int_prioridad  INT NOT NULL,
                    CONSTRAINT pk_inscripcion_carrera            PRIMARY KEY (id_inscripcion, id_carrera),
                    CONSTRAINT chk_inscripcion_carrera_prioridad CHECK (int_prioridad IN (1,2)),
                    CONSTRAINT fk_insc_carr_inscripcion          FOREIGN KEY (id_inscripcion)
                        REFERENCES tbl_inscripcion(id_inscripcion) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fk_insc_carr_carrera              FOREIGN KEY (id_carrera)
                        REFERENCES tbl_carrera(id_carrera) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        if (!Schema::hasTable('tbl_pago')) {
            DB::statement('
                CREATE TABLE tbl_pago (
                    id_pago        INT GENERATED ALWAYS AS IDENTITY,
                    num_monto      NUMERIC(10,2) NOT NULL,
                    fch_pago       TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                    txt_estado     VARCHAR(50) NOT NULL,
                    txt_metodo     VARCHAR(100) NOT NULL,
                    txt_referencia VARCHAR(100),
                    id_inscripcion INT NOT NULL,
                    CONSTRAINT pk_pago            PRIMARY KEY (id_pago),
                    CONSTRAINT uq_pago_inscripcion UNIQUE (id_inscripcion),
                    CONSTRAINT chk_pago_monto      CHECK (num_monto >= 0),
                    CONSTRAINT fk_pago_inscripcion FOREIGN KEY (id_inscripcion)
                        REFERENCES tbl_inscripcion(id_inscripcion) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        // ── MÓDULO ACADÉMICO ──────────────────────────────────
        if (!Schema::hasTable('tbl_turno')) {
            DB::statement('
                CREATE TABLE tbl_turno (
                    id_turno  INT GENERATED ALWAYS AS IDENTITY,
                    txt_turno VARCHAR(50) NOT NULL,
                    CONSTRAINT pk_turno        PRIMARY KEY (id_turno),
                    CONSTRAINT uq_turno_nombre UNIQUE (txt_turno)
                )
            ');
        }

        if (!Schema::hasTable('tbl_aula')) {
            DB::statement('
                CREATE TABLE tbl_aula (
                    id_aula      INT GENERATED ALWAYS AS IDENTITY,
                    int_piso     INT NOT NULL,
                    txt_nro_aula VARCHAR(20) NOT NULL,
                    CONSTRAINT pk_aula               PRIMARY KEY (id_aula),
                    CONSTRAINT uq_aula_identificador UNIQUE (int_piso, txt_nro_aula)
                )
            ');
        }

        if (!Schema::hasTable('tbl_horario')) {
            DB::statement('
                CREATE TABLE tbl_horario (
                    id_horario     INT GENERATED ALWAYS AS IDENTITY,
                    txt_dia_semana VARCHAR(20) NOT NULL,
                    tm_hora_inicio TIME NOT NULL,
                    tm_hora_final  TIME NOT NULL,
                    id_turno       INT NOT NULL,
                    CONSTRAINT pk_horario        PRIMARY KEY (id_horario),
                    CONSTRAINT chk_horario_horas CHECK (tm_hora_final > tm_hora_inicio),
                    CONSTRAINT fk_horario_turno  FOREIGN KEY (id_turno)
                        REFERENCES tbl_turno(id_turno) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        if (!Schema::hasTable('tbl_grupo')) {
            DB::statement('
                CREATE TABLE tbl_grupo (
                    id_grupo                 INT GENERATED ALWAYS AS IDENTITY,
                    txt_nombre               VARCHAR(50) NOT NULL,
                    int_cantidad_estudiantes INT DEFAULT 0 NOT NULL,
                    int_capacidad_maxma      INT DEFAULT 70 NOT NULL,
                    id_inscripcion           INT NOT NULL,
                    CONSTRAINT pk_grupo             PRIMARY KEY (id_grupo),
                    CONSTRAINT chk_grupo_cantidades CHECK (
                        int_cantidad_estudiantes <= int_capacidad_maxma
                        AND int_cantidad_estudiantes >= 0
                    ),
                    CONSTRAINT fk_grupo_inscripcion FOREIGN KEY (id_inscripcion)
                        REFERENCES tbl_inscripcion(id_inscripcion) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        if (!Schema::hasTable('tbl_grupo_horario')) {
            DB::statement('
                CREATE TABLE tbl_grupo_horario (
                    id_grupo   INT NOT NULL,
                    id_horario INT NOT NULL,
                    id_aula    INT NOT NULL,
                    CONSTRAINT pk_grupo_horario    PRIMARY KEY (id_grupo, id_horario),
                    CONSTRAINT fk_grup_hor_grupo   FOREIGN KEY (id_grupo)
                        REFERENCES tbl_grupo(id_grupo) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fk_grup_hor_horario FOREIGN KEY (id_horario)
                        REFERENCES tbl_horario(id_horario) ON UPDATE CASCADE ON DELETE RESTRICT,
                    CONSTRAINT fk_grup_hor_aula    FOREIGN KEY (id_aula)
                        REFERENCES tbl_aula(id_aula) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        if (!Schema::hasTable('tbl_materia')) {
            DB::statement('
                CREATE TABLE tbl_materia (
                    id_materia INT GENERATED ALWAYS AS IDENTITY,
                    txt_nombre VARCHAR(100) NOT NULL,
                    CONSTRAINT pk_materia        PRIMARY KEY (id_materia),
                    CONSTRAINT uq_materia_nombre UNIQUE (txt_nombre)
                )
            ');
        }

        if (!Schema::hasTable('tbl_evaluacion')) {
            DB::statement('
                CREATE TABLE tbl_evaluacion (
                    id_evaluacion  INT GENERATED ALWAYS AS IDENTITY,
                    int_nro_examen INT DEFAULT 1 NOT NULL,
                    fch_examen     DATE NOT NULL,
                    id_postulante  INT NOT NULL,
                    CONSTRAINT pk_evaluacion             PRIMARY KEY (id_evaluacion),
                    CONSTRAINT fk_evaluacion_postulante  FOREIGN KEY (id_postulante)
                        REFERENCES tbl_postulante(id_postulante) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        if (!Schema::hasTable('tbl_detalle_evaluacion')) {
            DB::statement('
                CREATE TABLE tbl_detalle_evaluacion (
                    id_evaluacion INT NOT NULL,
                    id_materia    INT NOT NULL,
                    num_nota      NUMERIC(5,2) NOT NULL,
                    CONSTRAINT pk_detalle_evaluacion  PRIMARY KEY (id_evaluacion, id_materia),
                    CONSTRAINT chk_nota               CHECK (num_nota >= 0 AND num_nota <= 100),
                    CONSTRAINT fk_det_eval_evaluacion FOREIGN KEY (id_evaluacion)
                        REFERENCES tbl_evaluacion(id_evaluacion) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fk_det_eval_materia    FOREIGN KEY (id_materia)
                        REFERENCES tbl_materia(id_materia) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        // Vista de promedios ponderados
        DB::statement("
            CREATE OR REPLACE VIEW vw_promedio_postulante AS
            SELECT
                e.id_postulante,
                d.id_materia,
                SUM(
                    CASE e.int_nro_examen
                        WHEN 1 THEN d.num_nota * 0.30
                        WHEN 2 THEN d.num_nota * 0.30
                        WHEN 3 THEN d.num_nota * 0.40
                    END
                ) AS promedio_ponderado,
                AVG(d.num_nota) AS promedio_simple
            FROM tbl_evaluacion e
            JOIN tbl_detalle_evaluacion d ON d.id_evaluacion = e.id_evaluacion
            GROUP BY e.id_postulante, d.id_materia
        ");

        // ── MÓDULO DOCENTE ────────────────────────────────────
        if (!Schema::hasTable('tbl_profesion')) {
            DB::statement('
                CREATE TABLE tbl_profesion (
                    id_profesion    INT GENERATED ALWAYS AS IDENTITY,
                    txt_descripcion VARCHAR(150) NOT NULL,
                    CONSTRAINT pk_profesion      PRIMARY KEY (id_profesion),
                    CONSTRAINT uq_profesion_desc UNIQUE (txt_descripcion)
                )
            ');
        }

        if (!Schema::hasTable('tbl_formacion_academica')) {
            DB::statement('
                CREATE TABLE tbl_formacion_academica (
                    id_formacion    INT GENERATED ALWAYS AS IDENTITY,
                    txt_nombre      VARCHAR(150) NOT NULL,
                    txt_tipo        VARCHAR(50)  NOT NULL,
                    txt_institucion VARCHAR(150) NOT NULL,
                    CONSTRAINT pk_formacion_academica PRIMARY KEY (id_formacion)
                )
            ');
        }

        if (!Schema::hasTable('tbl_docente')) {
            DB::statement('
                CREATE TABLE tbl_docente (
                    id_docente   INT GENERATED ALWAYS AS IDENTITY,
                    txt_ci       VARCHAR(20)  NOT NULL,
                    txt_nombre   VARCHAR(100) NOT NULL,
                    txt_telefono VARCHAR(20),
                    txt_correo   VARCHAR(100) NOT NULL,
                    id_usuario   INT,
                    CONSTRAINT pk_docente         PRIMARY KEY (id_docente),
                    CONSTRAINT uq_docente_ci      UNIQUE (txt_ci),
                    CONSTRAINT uq_docente_correo  UNIQUE (txt_correo),
                    CONSTRAINT fk_docente_usuario FOREIGN KEY (id_usuario)
                        REFERENCES tbl_usuario(id_usuario) ON UPDATE CASCADE ON DELETE SET NULL
                )
            ');
        }

        if (!Schema::hasTable('tbl_docente_profesion')) {
            DB::statement('
                CREATE TABLE tbl_docente_profesion (
                    id_docente      INT NOT NULL,
                    id_profesion    INT NOT NULL,
                    txt_titulo      VARCHAR(100) NOT NULL,
                    txt_universidad VARCHAR(150) NOT NULL,
                    fch_emicion     DATE NOT NULL,
                    CONSTRAINT pk_docente_profesion  PRIMARY KEY (id_docente, id_profesion),
                    CONSTRAINT fk_doc_prof_docente   FOREIGN KEY (id_docente)
                        REFERENCES tbl_docente(id_docente) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fk_doc_prof_profesion FOREIGN KEY (id_profesion)
                        REFERENCES tbl_profesion(id_profesion) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        if (!Schema::hasTable('tbl_docente_formacion')) {
            DB::statement('
                CREATE TABLE tbl_docente_formacion (
                    id_docente   INT NOT NULL,
                    id_formacion INT NOT NULL,
                    CONSTRAINT pk_docente_formacion  PRIMARY KEY (id_docente, id_formacion),
                    CONSTRAINT fk_doc_form_docente   FOREIGN KEY (id_docente)
                        REFERENCES tbl_docente(id_docente) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fk_doc_form_formacion FOREIGN KEY (id_formacion)
                        REFERENCES tbl_formacion_academica(id_formacion) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        if (!Schema::hasTable('tbl_materia_profesion')) {
            DB::statement('
                CREATE TABLE tbl_materia_profesion (
                    id_materia   INT NOT NULL,
                    id_profesion INT NOT NULL,
                    CONSTRAINT pk_materia_profesion  PRIMARY KEY (id_materia, id_profesion),
                    CONSTRAINT fk_mat_prof_materia   FOREIGN KEY (id_materia)
                        REFERENCES tbl_materia(id_materia) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fk_mat_prof_profesion FOREIGN KEY (id_profesion)
                        REFERENCES tbl_profesion(id_profesion) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ');
        }

        if (!Schema::hasTable('tbl_contratacion')) {
            DB::statement("
                CREATE TABLE tbl_contratacion (
                    id_contratacion INT GENERATED ALWAYS AS IDENTITY,
                    fch_contrato    DATE NOT NULL,
                    num_salario     NUMERIC(10,2) NOT NULL,
                    txt_estado      VARCHAR(30) DEFAULT 'ACTIVO' NOT NULL,
                    txt_observacion TEXT,
                    id_docente      INT NOT NULL,
                    id_usuario      INT NOT NULL,
                    CONSTRAINT pk_contratacion          PRIMARY KEY (id_contratacion),
                    CONSTRAINT chk_contratacion_salario CHECK (num_salario >= 0),
                    CONSTRAINT fk_contratacion_docente  FOREIGN KEY (id_docente)
                        REFERENCES tbl_docente(id_docente) ON UPDATE CASCADE ON DELETE RESTRICT,
                    CONSTRAINT fk_contratacion_usuario  FOREIGN KEY (id_usuario)
                        REFERENCES tbl_usuario(id_usuario) ON UPDATE CASCADE ON DELETE RESTRICT
                )
            ");
        }

        if (!Schema::hasTable('tbl_asignacion_docente')) {
            DB::statement('
                CREATE TABLE tbl_asignacion_docente (
                    id_asignacion INT GENERATED ALWAYS AS IDENTITY,
                    id_docente    INT NOT NULL,
                    id_materia    INT NOT NULL,
                    id_grupo      INT NOT NULL,
                    CONSTRAINT pk_asignacion_docente       PRIMARY KEY (id_asignacion),
                    CONSTRAINT uq_asignacion_grupo_materia UNIQUE (id_grupo, id_materia),
                    CONSTRAINT fk_asig_doc_docente         FOREIGN KEY (id_docente)
                        REFERENCES tbl_docente(id_docente) ON UPDATE CASCADE ON DELETE RESTRICT,
                    CONSTRAINT fk_asig_doc_materia         FOREIGN KEY (id_materia)
                        REFERENCES tbl_materia(id_materia) ON UPDATE CASCADE ON DELETE RESTRICT,
                    CONSTRAINT fk_asig_doc_grupo           FOREIGN KEY (id_grupo)
                        REFERENCES tbl_grupo(id_grupo) ON UPDATE CASCADE ON DELETE CASCADE
                )
            ');
        }
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS tbl_asignacion_docente CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_contratacion CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_materia_profesion CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_docente_formacion CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_docente_profesion CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_docente CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_formacion_academica CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_profesion CASCADE');
        DB::statement('DROP VIEW  IF EXISTS vw_promedio_postulante CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_detalle_evaluacion CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_evaluacion CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_materia CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_grupo_horario CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_grupo CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_horario CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_aula CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_turno CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_pago CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_inscripcion_carrera CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_inscripcion CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_gestion CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_carrera CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_requisito_postulante CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_postulante CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_requisito CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_reporte CASCADE');
        DB::statement('DROP TABLE IF EXISTS tbl_auditoria CASCADE');
    }
};
