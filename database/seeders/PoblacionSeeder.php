<?php

// ============================================================
// DESTINO: database/seeders/PoblacionSeeder.php
//
// Pobla la BD con 1000 postulantes + inscripciones, pagos,
// evaluaciones, notas y grupos calculados automáticamente.
//
// Ejecutar:
//   php artisan db:seed --class=PoblacionSeeder
//
// ADVERTENCIA: Limpia TODOS los datos antes de insertar.
// ============================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PoblacionSeeder extends Seeder
{
    // ── Configuración ────────────────────────────────────────
    const TOTAL_POSTULANTES  = 1000;
    const CAPACIDAD_GRUPO    = 70;    // máximo por grupo
    const UMBRAL_APROBACION  = 60.0;  // nota mínima aprobatoria

    public function run(): void
    {
        $this->command->info('🗑  Limpiando base de datos...');
        $this->limpiarBD();

        $this->command->info('🔧 Insertando estructura base...');
        $this->insertarEstructura();

        $this->command->info('👥 Generando 1000 postulantes...');
        $this->insertarPostulantes();

        $this->command->info('📝 Generando inscripciones y pagos...');
        $this->insertarInscripciones();

        $this->command->info('🏫 Calculando y creando grupos...');
        $this->crearGrupos();

        $this->command->info('📊 Generando evaluaciones y notas...');
        $this->insertarEvaluaciones();

        $this->command->info('');
        $this->command->info('✅ Población completada:');
        $this->command->info('   · ' . DB::table('tbl_postulante')->count()  . ' postulantes');
        $this->command->info('   · ' . DB::table('tbl_inscripcion')->count() . ' inscripciones');
        $this->command->info('   · ' . DB::table('tbl_grupo')->count()       . ' grupos');
        $this->command->info('   · ' . DB::table('tbl_evaluacion')->count()  . ' evaluaciones');
        $this->command->info('   · ' . DB::table('tbl_detalle_evaluacion')->count() . ' detalles de notas');
    }

    // ────────────────────────────────────────────────────────
    // LIMPIEZA
    // ────────────────────────────────────────────────────────
    private function limpiarBD(): void
    {
        DB::statement('SET session_replication_role = replica');

        $tablas = [
            'tbl_asignacion_docente', 'tbl_contratacion',
            'tbl_materia_profesion', 'tbl_docente_formacion', 'tbl_docente_profesion',
            'tbl_docente', 'tbl_formacion_academica', 'tbl_profesion',
            'tbl_detalle_evaluacion', 'tbl_evaluacion',
            'tbl_grupo_horario', 'tbl_grupo',
            'tbl_horario', 'tbl_aula', 'tbl_turno', 'tbl_materia',
            'tbl_pago', 'tbl_inscripcion_carrera', 'tbl_inscripcion',
            'tbl_gestion', 'tbl_carrera',
            'tbl_requisito_postulante', 'tbl_postulante', 'tbl_requisito',
            'tbl_reporte', 'tbl_auditoria', 'tbl_usuario', 'tbl_rol',
        ];

        foreach ($tablas as $tabla) {
            DB::statement("TRUNCATE TABLE {$tabla} RESTART IDENTITY CASCADE");
        }

        DB::statement('SET session_replication_role = DEFAULT');
        $this->command->info('   · BD limpiada correctamente.');
    }

    // ────────────────────────────────────────────────────────
    // ESTRUCTURA BASE (Roles, Usuarios, Materias, etc.)
    // ────────────────────────────────────────────────────────
    private function insertarEstructura(): void
    {
        // Roles
        DB::table('tbl_rol')->insert([
            ['txt_nombre' => 'ADMINISTRADOR', 'txt_descripcion' => 'Acceso total al sistema'],
            ['txt_nombre' => 'COORDINADOR',   'txt_descripcion' => 'Control de cupos y asignaciones'],
            ['txt_nombre' => 'OPERADOR',       'txt_descripcion' => 'Personal de ventanilla'],
            ['txt_nombre' => 'DOCENTE',        'txt_descripcion' => 'Carga horaria y notas'],
            ['txt_nombre' => 'SECRETARIA',     'txt_descripcion' => 'Registro y consulta'],
        ]);

        // Usuarios del sistema
        DB::table('tbl_usuario')->insert([
            ['txt_username' => 'admin',    'txt_password' => Hash::make('Admin123!'),      'txt_email' => 'admin@ficct.edu.bo',    'id_rol' => 1, 'bol_estado' => true],
            ['txt_username' => 'sec.ficct','txt_password' => Hash::make('Secretaria123!'), 'txt_email' => 'sec@ficct.edu.bo',      'id_rol' => 5, 'bol_estado' => true],
            ['txt_username' => 'coord.cup','txt_password' => Hash::make('Coord123!'),      'txt_email' => 'coord@ficct.edu.bo',    'id_rol' => 2, 'bol_estado' => true],
        ]);

        // Requisitos
        DB::table('tbl_requisito')->insert([
            ['txt_descripcion_requisito' => 'Título de Bachiller (Fotocopia Legalizada)'],
            ['txt_descripcion_requisito' => 'Cédula de Identidad Vigente (Fotocopia Simple)'],
            ['txt_descripcion_requisito' => 'Certificado de Nacimiento Original Computarizado'],
            ['txt_descripcion_requisito' => 'Formulario de Inscripción CUP Firmado'],
            ['txt_descripcion_requisito' => 'Dos Fotografías 4x4 Fondo Azul'],
        ]);

        // Carreras con cupos
        DB::table('tbl_carrera')->insert([
            ['txt_nombre' => 'Ingeniería de Sistemas',                   'int_cupo' => 200],
            ['txt_nombre' => 'Ingeniería Informática',                   'int_cupo' => 200],
            ['txt_nombre' => 'Ingeniería en Redes y Telecomunicaciones', 'int_cupo' => 150],
            ['txt_nombre' => 'Ingeniería Biomédica',                     'int_cupo' => 100],
            ['txt_nombre' => 'Ingeniería de Software',                   'int_cupo' => 150],
        ]);

        // Gestión
        DB::table('tbl_gestion')->insert([
            ['int_año' => 2026, 'txt_periodo' => 'CUP-2026'],
        ]);

        // Materias (4 del CUP)
        DB::table('tbl_materia')->insert([
            ['txt_nombre' => 'Computación'],
            ['txt_nombre' => 'Matemáticas'],
            ['txt_nombre' => 'Inglés'],
            ['txt_nombre' => 'Física'],
        ]);

        // Turnos
        DB::table('tbl_turno')->insert([
            ['txt_turno' => 'Mañana'],
            ['txt_turno' => 'Tarde'],
            ['txt_turno' => 'Noche'],
        ]);

        // Aulas (suficientes para 15 grupos)
        $aulas = [];
        foreach ([1, 2, 3, 4] as $piso) {
            foreach (['01', '02', '03', '04'] as $nro) {
                $aulas[] = ['int_piso' => $piso, 'txt_nro_aula' => "Aula {$piso}{$nro}"];
            }
        }
        DB::table('tbl_aula')->insert($aulas);

        // ── Horarios ─────────────────────────────────────────
        // Estructura basada en la imagen de referencia:
        // Cada materia ocupa 1h30 continuo, 4 materias al día = 6h
        // Los 4 bloques corren de lunes a viernes en el mismo horario.
        //
        // TURNO MAÑANA  (id_turno=1): 07:00 → 13:00
        //   Bloque 1: 07:00–08:30  (Materia 1)
        //   Bloque 2: 08:30–10:00  (Materia 2)
        //   Bloque 3: 10:00–11:30  (Materia 3)
        //   Bloque 4: 11:30–13:00  (Materia 4)
        //
        // TURNO TARDE   (id_turno=2): 14:00 → 20:00
        //   Bloque 1: 14:00–15:30  (Materia 1)
        //   Bloque 2: 15:30–17:00  (Materia 2)
        //   Bloque 3: 17:00–18:30  (Materia 3)
        //   Bloque 4: 18:30–20:00  (Materia 4)

        $dias = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];

        $bloquesMañana = [
            ['07:00:00', '08:30:00'],
            ['08:30:00', '10:00:00'],
            ['10:00:00', '11:30:00'],
            ['11:30:00', '13:00:00'],
        ];

        $bloquesTarde = [
            ['14:00:00', '15:30:00'],
            ['15:30:00', '17:00:00'],
            ['17:00:00', '18:30:00'],
            ['18:30:00', '20:00:00'],
        ];

        $horarios = [];
        foreach ($dias as $dia) {
            foreach ($bloquesMañana as $bloque) {
                $horarios[] = [
                    'txt_dia_semana' => $dia,
                    'tm_hora_inicio' => $bloque[0],
                    'tm_hora_final'  => $bloque[1],
                    'id_turno'       => 1, // Mañana
                ];
            }
            foreach ($bloquesTarde as $bloque) {
                $horarios[] = [
                    'txt_dia_semana' => $dia,
                    'tm_hora_inicio' => $bloque[0],
                    'tm_hora_final'  => $bloque[1],
                    'id_turno'       => 2, // Tarde
                ];
            }
        }
        // Total: 5 días × 8 bloques = 40 horarios
        DB::table('tbl_horario')->insert($horarios);

        // Profesiones y docentes
        DB::table('tbl_profesion')->insert([
            ['txt_descripcion' => 'Ingeniero de Sistemas de Información'],
            ['txt_descripcion' => 'Licenciado en Ciencias Matemáticas'],
            ['txt_descripcion' => 'Ingeniero de Ciencias de la Computación'],
            ['txt_descripcion' => 'Licenciado en Filología e Idiomas'],
            ['txt_descripcion' => 'Máster en Física Aplicada'],
        ]);

        DB::table('tbl_formacion_academica')->insert([
            ['txt_nombre' => 'Maestría en Educación Superior', 'txt_tipo' => 'MAESTRÍA',  'txt_institucion' => 'U.A.G.R.M.'],
            ['txt_nombre' => 'Diplomado en entornos virtuales','txt_tipo' => 'DIPLOMADO', 'txt_institucion' => 'INEGAS'],
        ]);

        // 20 docentes para cubrir los 15 grupos × 4 materias
        $docentes = [
            ['3341255-SC', 'Dr. Roberto Torrez Pardo',       '72114455', 'r.torrez@uagrm.edu.bo',      3],
            ['4912855-LP', 'Msc. Maria Laura Ugarte',         '60889911', 'mugarte@uagrm.edu.bo',        2],
            ['5712499-SC', 'Ing. Fernando Claros Soliz',      '76543210', 'fclaros@uagrm.edu.bo',        1],
            ['6823411-BEN','Lic. Sarah Evans Mitchell',       '70011552', 'sevans@uagrm.edu.bo',         4],
            ['3892144-SC', 'Msc. Jorge Justiniano Arteaga',   '61522334', 'jjustiniano@uagrm.edu.bo',    5],
            ['7821344-SC', 'Dr. Carlos Pereira Vaca',         '77889900', 'cpereira@uagrm.edu.bo',       3],
            ['8934211-SC', 'Lic. Ana Flores Gutierrez',       '78001122', 'aflores@uagrm.edu.bo',        2],
            ['9012345-LP', 'Ing. Miguel Rojas Salinas',       '71234567', 'mrojas@uagrm.edu.bo',         1],
            ['1123456-CB', 'Msc. Patricia Vargas Mendez',     '66778899', 'pvargas@uagrm.edu.bo',        5],
            ['2234567-SC', 'Dr. Luis Camacho Ortiz',          '79900112', 'lcamacho@uagrm.edu.bo',       3],
            ['3345678-SC', 'Lic. Carmen Suarez Blanco',       '62233445', 'csuarez@uagrm.edu.bo',        4],
            ['4456789-LP', 'Ing. Ricardo Montero Cruz',       '70112233', 'rmontero@uagrm.edu.bo',       1],
            ['5567890-SC', 'Msc. Elena Quiroga Pinto',        '68990011', 'equiroga@uagrm.edu.bo',       2],
            ['6678901-BN', 'Dr. Pablo Morales Vidal',         '77001234', 'pmorales@uagrm.edu.bo',       5],
            ['7789012-SC', 'Lic. Sofia Herrera Lagos',        '69112345', 'sherrera@uagrm.edu.bo',       4],
            ['8890123-CB', 'Ing. David Aguilar Roca',         '76223456', 'daguilar@uagrm.edu.bo',       3],
            ['9901234-SC', 'Msc. Lucia Mamani Condori',       '63334567', 'lmamani@uagrm.edu.bo',        2],
            ['1012345-LP', 'Dr. Oscar Quispe Huanca',         '78445678', 'oquispe@uagrm.edu.bo',        5],
            ['2123456-SC', 'Lic. Valeria Choque Rios',        '67556789', 'vchoque@uagrm.edu.bo',        4],
            ['3234567-SC', 'Ing. Marco Balcazar Vega',        '75667890', 'mbalcazar@uagrm.edu.bo',      1],
        ];

        foreach ($docentes as $d) {
            DB::table('tbl_docente')->insert([
                'txt_ci'       => $d[0],
                'txt_nombre'   => $d[1],
                'txt_telefono' => $d[2],
                'txt_correo'   => $d[3],
                'id_usuario'   => null,
            ]);
        }

        // Contrataciones
        foreach (range(1, 20) as $i) {
            DB::table('tbl_contratacion')->insert([
                'fch_contrato'   => '2026-01-15',
                'num_salario'    => rand(5500, 7500),
                'txt_estado'     => 'ACTIVO',
                'txt_observacion'=> 'Contrato CUP-2026',
                'id_docente'     => $i,
                'id_usuario'     => 1,
            ]);
        }
    }

    // ────────────────────────────────────────────────────────
    // 1000 POSTULANTES
    // ────────────────────────────────────────────────────────
    private function insertarPostulantes(): void
    {
        $nombres = ['Juan','María','Carlos','Ana','Luis','Rosa','Jorge','Elena','Pedro','Laura',
                    'Miguel','Carmen','Pablo','Sofía','Diego','Valentina','Andrés','Gabriela',
                    'Ricardo','Paola','Fernando','Claudia','Sergio','Daniela','Roberto','Patricia',
                    'Eduardo','Natalia','Alejandro','Verónica','Manuel','Lucía','Oscar','Isabel',
                    'Marcos','Rebeca','Gustavo','Monica','Rodrigo','Adriana','Héctor','Silvia',
                    'Ramón','Lorena','Ernesto','Gloria','Hugo','Alicia','Jesús','Pilar'];

        $apellidos = ['García','López','Martínez','Rodríguez','González','Pérez','Sánchez','Romero',
                      'Torres','Flores','Vargas','Mendoza','Castro','Ortega','Jiménez','Morales',
                      'Reyes','Cruz','Quispe','Mamani','Condori','Huanca','Chávez','Rojas','Medina',
                      'Suárez','Herrera','Aguilar','Gutiérrez','Espinoza','Blanco','Ramos','Pinto',
                      'Vega','Salinas','Montero','Quiroga','Camacho','Balcázar','Justiniano',
                      'Banegas','Soruco','Claros','Ugarte','Torrez','Pereira','Moreno','Campos',
                      'Ibáñez','Sandoval','Pacheco','Valenzuela','Navarro','Ramírez','Delgado'];

        $colegios = ['Colegio Nacional Florida','Colegio Marista','Colegio La Salle','Colegio Berea',
                     'Colegio San Agustín','Colegio Alemán','Colegio Adventista','Unidad Educativa UCEBOL',
                     'Colegio Christa McAuliffe','Colegio San Carlos','Colegio Los Pinos',
                     'Unidad Educativa Simón Bolívar','Colegio Don Bosco','Colegio Sagrado Corazón',
                     'Unidad Educativa 6 de Agosto','Colegio Anglo Americano','Colegio Santa Ana',
                     'Unidad Educativa Bolivia','Colegio Loyola','Colegio Franz Tamayo'];

        $ciudades = ['Santa Cruz','La Paz','Cochabamba','Sucre','Oruro','Potosí','Tarija','Trinidad','Cobija','Riberalta'];

        $departamentos = ['SC','LP','CB','CH','OR','PT','TJ','BN','PD','BE'];

        $sexos = ['M','F','M','M','F','F','M','F','M','F']; // distribución 50/50

        $postulantes = [];
        $usedCIs     = [];
        $usedCorreos = [];

        for ($i = 1; $i <= self::TOTAL_POSTULANTES; $i++) {
            $nombre1  = $nombres[array_rand($nombres)];
            $nombre2  = $nombres[array_rand($nombres)];
            $apellido1 = $apellidos[array_rand($apellidos)];
            $apellido2 = $apellidos[array_rand($apellidos)];
            $nombreCompleto = "{$nombre1} {$nombre2} {$apellido1} {$apellido2}";

            // CI único
            do {
                $numCI = rand(1000000, 9999999);
                $dep   = $departamentos[array_rand($departamentos)];
                $ci    = "{$numCI}-{$dep}";
            } while (in_array($ci, $usedCIs));
            $usedCIs[] = $ci;

            // Correo único
            $base    = strtolower(substr($nombre1, 0, 3) . substr($apellido1, 0, 4) . $i);
            $base    = preg_replace('/[^a-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $base));
            $correo  = "{$base}@gmail.com";
            $usedCorreos[] = $correo;

            $sexo    = $sexos[$i % 10];
            $anio    = rand(2004, 2008);
            $mes     = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
            $dia     = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
            $ciudad  = $ciudades[array_rand($ciudades)];
            $colegio = $colegios[array_rand($colegios)];

            $postulantes[] = [
                'txt_ci'         => $ci,
                'txt_nombre'     => $nombreCompleto,
                'txt_telefono'   => '7' . rand(1000000, 9999999),
                'txt_correo'     => $correo,
                'fch_nacimiento' => "{$anio}-{$mes}-{$dia}",
                'chr_sexo'       => $sexo,
                'txt_direccion'  => "Calle {$apellido1} #{$i}",
                'txt_colegio'    => $colegio,
                'txt_ciudad'     => $ciudad,
            ];

            // Insertar en lotes de 100
            if (count($postulantes) === 100) {
                DB::table('tbl_postulante')->insert($postulantes);
                $postulantes = [];
                $this->command->getOutput()->write('.');
            }
        }
        if (!empty($postulantes)) {
            DB::table('tbl_postulante')->insert($postulantes);
        }
        $this->command->info(' ✓');
    }

    // ────────────────────────────────────────────────────────
    // INSCRIPCIONES + CARRERAS + PAGOS + REQUISITOS
    // ────────────────────────────────────────────────────────
    private function insertarInscripciones(): void
    {
        $estados = ['PROCESADO','PROCESADO','PROCESADO','PROCESADO','PENDIENTE']; // 80% procesado
        $metodos = ['PAGO POR QR','TARJETA DE DEBITO','TRANSFERENCIA BANCARIA','PASARELA TIGO MONEY','EFECTIVO'];
        $carreras = DB::table('tbl_carrera')->pluck('id_carrera')->toArray();
        $requisitos = DB::table('tbl_requisito')->pluck('id_requisito')->toArray();

        $inscripciones    = [];
        $inscCarreras     = [];
        $pagos            = [];
        $reqPostulantes   = [];
        $idInscripcion    = 1;

        for ($idPost = 1; $idPost <= self::TOTAL_POSTULANTES; $idPost++) {
            $estado = $estados[array_rand($estados)];

            $inscripciones[] = [
                'fch_inscripcion'        => date('Y-m-d H:i:s', strtotime("-" . rand(1, 90) . " days")),
                'txt_estado_inscripcion' => $estado,
                'id_postulante'          => $idPost,
                'id_gestion'             => 1,
            ];

            // Elección de 2 carreras distintas
            shuffle($carreras);
            $inscCarreras[] = ['id_inscripcion' => $idInscripcion, 'id_carrera' => $carreras[0], 'int_prioridad' => 1];
            $inscCarreras[] = ['id_inscripcion' => $idInscripcion, 'id_carrera' => $carreras[1], 'int_prioridad' => 2];

            // Pago
            $pagos[] = [
                'num_monto'      => 350.00,
                'fch_pago'       => date('Y-m-d H:i:s', strtotime("-" . rand(1, 85) . " days")),
                'txt_estado'     => $estado === 'PROCESADO' ? 'APROBADO' : 'PENDIENTE',
                'txt_metodo'     => $metodos[array_rand($metodos)],
                'txt_referencia' => strtoupper(substr(md5($idPost), 0, 10)),
                'id_inscripcion' => $idInscripcion,
            ];

            // Requisitos: entre 3 y 5 requisitos por postulante
            shuffle($requisitos);
            $nReq = rand(3, 5);
            for ($r = 0; $r < $nReq; $r++) {
                $reqPostulantes[] = [
                    'id_requisito'   => $requisitos[$r],
                    'id_postulante'  => $idPost,
                ];
            }

            $idInscripcion++;

            // Insertar en lotes de 200
            if ($idPost % 200 === 0) {
                DB::table('tbl_inscripcion')->insert($inscripciones);
                DB::table('tbl_inscripcion_carrera')->insert($inscCarreras);
                DB::table('tbl_pago')->insert($pagos);
                // Requisitos: evitar duplicados con insertOrIgnore
                foreach (array_chunk($reqPostulantes, 500) as $chunk) {
                    DB::table('tbl_requisito_postulante')->insertOrIgnore($chunk);
                }
                $inscripciones = $inscCarreras = $pagos = $reqPostulantes = [];
                $this->command->getOutput()->write('.');
            }
        }

        // Insertar resto
        if (!empty($inscripciones)) {
            DB::table('tbl_inscripcion')->insert($inscripciones);
            DB::table('tbl_inscripcion_carrera')->insert($inscCarreras);
            DB::table('tbl_pago')->insert($pagos);
            foreach (array_chunk($reqPostulantes, 500) as $chunk) {
                DB::table('tbl_requisito_postulante')->insertOrIgnore($chunk);
            }
        }
        $this->command->info(' ✓');
    }

    // ────────────────────────────────────────────────────────
    // GRUPOS — calculados automáticamente
    // CEIL(total_inscritos / capacidad_max)
    // ────────────────────────────────────────────────────────
    private function crearGrupos(): void
    {
        $totalInscritos = DB::table('tbl_inscripcion')
            ->where('txt_estado_inscripcion', 'PROCESADO')
            ->count();

        $cantidadGrupos = (int) ceil($totalInscritos / self::CAPACIDAD_GRUPO);

        $this->command->info("   · {$totalInscritos} inscritos procesados → {$cantidadGrupos} grupos (CEIL({$totalInscritos}/{$cantidadGrupos}))");

        // Inscripciones procesadas para distribuir
        $inscripcionesProcesadas = DB::table('tbl_inscripcion')
            ->where('txt_estado_inscripcion', 'PROCESADO')
            ->pluck('id_inscripcion')
            ->toArray();

        shuffle($inscripcionesProcesadas);

        // Horarios por turno y por día
        // Mañana: 4 bloques × 5 días = 20 horarios (id 1-20)
        // Tarde:  4 bloques × 5 días = 20 horarios (id 21-40)
        $horariosMañana = DB::table('tbl_horario')->where('id_turno', 1)->get();
        $horariosTarde  = DB::table('tbl_horario')->where('id_turno', 2)->get();

        // Agrupar por día para asignar los 4 bloques juntos
        $diasMañana = [];
        foreach ($horariosMañana as $h) {
            $diasMañana[$h->txt_dia_semana][] = $h->id_horario;
        }

        $diasTarde = [];
        foreach ($horariosTarde as $h) {
            $diasTarde[$h->txt_dia_semana][] = $h->id_horario;
        }

        $diasOrdenados = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];
        $aulas         = DB::table('tbl_aula')->pluck('id_aula')->toArray();
        $chunks        = array_chunk($inscripcionesProcesadas, self::CAPACIDAD_GRUPO);

        foreach ($chunks as $idx => $inscIds) {
            $nroGrupo    = $idx + 1;
            $cantidad    = count($inscIds);
            $idInscripcion = $inscIds[0];

            // Alternar turno: grupos impares → mañana, pares → tarde
            $esMañana    = ($nroGrupo % 2 !== 0);
            $turnoNombre = $esMañana ? 'Mañana' : 'Tarde';
            $diasBloques = $esMañana ? $diasMañana : $diasTarde;

            // Elegir día rotando (5 días disponibles)
            $dia         = $diasOrdenados[$idx % count($diasOrdenados)];
            $bloquesDia  = $diasBloques[$dia] ?? [];

            $idAula = $aulas[$idx % count($aulas)];

            $idGrupo = DB::table('tbl_grupo')->insertGetId([
                'txt_nombre'               => "Grupo {$nroGrupo} ({$turnoNombre} - {$dia})",
                'int_cantidad_estudiantes' => $cantidad,
                'int_capacidad_maxma'      => self::CAPACIDAD_GRUPO,
                'id_inscripcion'           => $idInscripcion,
            ]);

            // Asignar los 4 bloques horarios del día al grupo
            foreach ($bloquesDia as $idHorario) {
                DB::table('tbl_grupo_horario')->insert([
                    'id_grupo'   => $idGrupo,
                    'id_horario' => $idHorario,
                    'id_aula'    => $idAula,
                ]);
            }
        }

        $this->asignarDocentes($cantidadGrupos);
    }

    private function asignarDocentes(int $cantidadGrupos): void
    {
        $grupos   = DB::table('tbl_grupo')->pluck('id_grupo')->toArray();
        $materias = DB::table('tbl_materia')->pluck('id_materia')->toArray(); // 4 materias
        $docentes = DB::table('tbl_docente')->pluck('id_docente')->toArray(); // 20 docentes

        // Contador de grupos por docente (máx 4)
        $gruposPorDocente = array_fill_keys($docentes, 0);
        $docenteIdx       = 0;

        foreach ($grupos as $idGrupo) {
            foreach ($materias as $idMateria) {
                // Buscar docente disponible (menos de 4 grupos)
                $intentos = 0;
                while ($gruposPorDocente[$docentes[$docenteIdx]] >= 4) {
                    $docenteIdx = ($docenteIdx + 1) % count($docentes);
                    $intentos++;
                    if ($intentos > count($docentes)) break; // todos con 4 grupos
                }

                $idDocente = $docentes[$docenteIdx];

                // Verificar que no exista ya esa asignación grupo+materia
                $existe = DB::table('tbl_asignacion_docente')
                    ->where('id_grupo', $idGrupo)
                    ->where('id_materia', $idMateria)
                    ->exists();

                if (!$existe && $intentos <= count($docentes)) {
                    DB::table('tbl_asignacion_docente')->insert([
                        'id_docente' => $idDocente,
                        'id_materia' => $idMateria,
                        'id_grupo'   => $idGrupo,
                    ]);
                    $gruposPorDocente[$idDocente]++;
                    $docenteIdx = ($docenteIdx + 1) % count($docentes);
                }
            }
        }
    }

    // ────────────────────────────────────────────────────────
    // EVALUACIONES — 3 exámenes × 4 materias por postulante
    // ────────────────────────────────────────────────────────
    private function insertarEvaluaciones(): void
    {
        $fechasExamen = ['2026-04-10', '2026-05-10', '2026-06-10'];
        $materias     = DB::table('tbl_materia')->pluck('id_materia')->toArray();

        $evaluaciones = [];
        $detalles     = [];
        $idEval       = 1;

        for ($idPost = 1; $idPost <= self::TOTAL_POSTULANTES; $idPost++) {

            // Decidir perfil de rendimiento del postulante
            // 55% aprobados, 35% reprobados, 10% sin evaluaciones
            $perfil = rand(1, 100);
            if ($perfil <= 10) continue; // sin evaluaciones (aún no rindió)

            $esAprobado = ($perfil <= 65); // 55% de los que rinden

            for ($nroExamen = 1; $nroExamen <= 3; $nroExamen++) {
                $evaluaciones[] = [
                    'int_nro_examen' => $nroExamen,
                    'fch_examen'     => $fechasExamen[$nroExamen - 1],
                    'id_postulante'  => $idPost,
                ];

                foreach ($materias as $idMateria) {
                    // Generar nota según perfil del postulante
                    if ($esAprobado) {
                        // Aprobado: notas entre 60 y 100 con variación natural
                        $base = rand(60, 95);
                        $variacion = rand(-5, 5); // variación entre exámenes
                        $nota = max(60, min(100, $base + $variacion));
                    } else {
                        // Reprobado: notas entre 10 y 65 (puede tener materias aprobadas)
                        $nota = rand(10, 65);
                        // 30% chance de reprobar fuerte en alguna materia
                        if (rand(1, 10) <= 3) {
                            $nota = rand(10, 45);
                        }
                    }

                    $detalles[] = [
                        'id_evaluacion' => $idEval,
                        'id_materia'    => $idMateria,
                        'num_nota'      => round($nota, 2),
                    ];
                }

                $idEval++;
            }

            // Insertar en lotes de 100 postulantes (= 300 eval, 1200 detalles)
            if ($idPost % 100 === 0) {
                DB::table('tbl_evaluacion')->insert($evaluaciones);
                foreach (array_chunk($detalles, 1000) as $chunk) {
                    DB::table('tbl_detalle_evaluacion')->insert($chunk);
                }
                $evaluaciones = [];
                $detalles     = [];
                $this->command->getOutput()->write('.');
            }
        }

        // Insertar resto
        if (!empty($evaluaciones)) {
            DB::table('tbl_evaluacion')->insert($evaluaciones);
        }
        if (!empty($detalles)) {
            foreach (array_chunk($detalles, 1000) as $chunk) {
                DB::table('tbl_detalle_evaluacion')->insert($chunk);
            }
        }
        $this->command->info(' ✓');
    }
}
