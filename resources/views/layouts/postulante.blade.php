<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal del Postulante - Admisión FICCT</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-slate-900 text-slate-100 font-sans antialiased flex h-screen w-screen overflow-hidden">

    <!-- ══════════════════════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════════════════════ -->
    <aside class="w-64 bg-slate-800/60 backdrop-blur-md border-r border-slate-700/50 flex flex-col justify-between h-full shrink-0">

        <!-- Logo -->
        <div class="flex flex-col overflow-hidden">
            <div class="p-6 flex items-center gap-3 border-b border-slate-700/50 shrink-0">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                    <i data-lucide="graduation-cap" class="h-5 w-5"></i>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white tracking-wide">Admisión FICCT</h1>
                    <span class="text-[10px] uppercase font-bold text-blue-400 tracking-wider">PORTAL DEL POSTULANTE</span>
                </div>
            </div>

            <!-- Navegación -->
            <nav id="sidebar-menu" class="p-3 space-y-1 overflow-y-auto flex-1">

                <button data-module="perfil" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-white bg-indigo-600/80">
                    <i data-lucide="user" class="h-4 w-4 shrink-0"></i>
                    <span>Mi Perfil</span>
                </button>

                <button data-module="horario" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                    <i data-lucide="calendar-clock" class="h-4 w-4 shrink-0"></i>
                    <span>Horario</span>
                </button>

                <button data-module="resultados" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                    <i data-lucide="bar-chart-3" class="h-4 w-4 shrink-0"></i>
                    <span>Resultados</span>
                </button>

            </nav>
        </div>

        <!-- Pie del sidebar -->
        <div class="p-3 border-t border-slate-700/50 space-y-1 shrink-0">
            <div class="flex items-center gap-3 px-3 py-2">
                <div class="h-8 w-8 rounded-lg bg-slate-700 flex items-center justify-center font-bold text-sm text-indigo-400 border border-slate-600 shrink-0" id="sidebar-avatar">P</div>
                <div class="overflow-hidden">
                    <p id="sidebar-username" class="text-xs font-bold text-white truncate">Postulante</p>
                    <p id="sidebar-ci" class="text-[10px] text-slate-400 truncate">CI: —</p>
                </div>
            </div>
            <button onclick="handleLogout()" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-red-400 hover:bg-red-500/10 transition-all cursor-pointer">
                <i data-lucide="log-out" class="h-4 w-4 shrink-0"></i>
                <span>Cerrar Sesión</span>
            </button>
        </div>
    </aside>

    <!-- ══════════════════════════════════════════════════════════
         AREA PRINCIPAL
    ══════════════════════════════════════════════════════════ -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="h-16 bg-slate-800/40 backdrop-blur-md border-b border-slate-700/50 flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-2 text-sm text-slate-400">
                <span>Portal del Postulante</span>
                <i data-lucide="chevron-right" class="h-3 w-3"></i>
                <span id="header-page-title" class="text-white font-medium">Mi Perfil</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p id="header-username" class="text-xs font-bold text-white">Postulante</p>
                    <p id="header-ci" class="text-[10px] text-slate-400">CI: —</p>
                </div>
                <div class="h-9 w-9 rounded-xl bg-slate-700 flex items-center justify-center font-bold text-sm text-indigo-400 border border-slate-600" id="header-avatar">P</div>
            </div>
        </header>

        <!-- Módulos -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">

            <!-- Loading global -->
            <div id="loading-global" class="flex flex-col items-center justify-center py-24 text-slate-400">
                <i data-lucide="loader" class="h-8 w-8 animate-spin mb-3 text-indigo-400"></i>
                <p class="text-sm">Cargando información...</p>
            </div>

            <!-- Error global -->
            <div id="error-global" class="hidden bg-slate-800/40 border border-red-500/20 rounded-2xl p-6 flex items-start gap-3 max-w-2xl mx-auto">
                <i data-lucide="alert-triangle" class="h-5 w-5 shrink-0 mt-0.5 text-red-400"></i>
                <div>
                    <p class="text-sm font-bold text-red-400 mb-1">No se pudo cargar tu información</p>
                    <p id="error-global-msg" class="text-sm text-slate-400">Ocurrió un error al conectar con el servidor.</p>
                </div>
            </div>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: PERFIL                          ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-perfil" class="app-module hidden space-y-6 max-w-5xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Mi Perfil</h2>
                        <p class="text-sm text-slate-400">Tus datos personales y el estado de tu inscripción</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                        <i data-lucide="id-card" class="h-5 w-5"></i>
                    </div>
                </div>

                <!-- Datos personales -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                        <span class="px-2 py-0.5 rounded-md bg-blue-600/20 text-blue-400 text-[11px] font-bold tracking-wider">DATOS</span>
                        <h3 class="text-sm font-bold text-white">Datos Personales</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div class="space-y-1.5">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="user" class="h-3.5 w-3.5 text-slate-500"></i> Nombre Completo</p>
                            <p id="p-nombre" class="text-sm font-semibold text-white">—</p>
                        </div>
                        <div class="space-y-1.5">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="id-card" class="h-3.5 w-3.5 text-slate-500"></i> Cédula de Identidad</p>
                            <p id="p-ci" class="text-sm font-semibold text-white">—</p>
                        </div>
                        <div class="space-y-1.5">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="mail" class="h-3.5 w-3.5 text-slate-500"></i> Correo Electrónico</p>
                            <p id="p-correo" class="text-sm font-semibold text-white">—</p>
                        </div>
                        <div class="space-y-1.5">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="phone" class="h-3.5 w-3.5 text-slate-500"></i> Teléfono</p>
                            <p id="p-telefono" class="text-sm font-semibold text-white">—</p>
                        </div>
                        <div class="space-y-1.5">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="calendar" class="h-3.5 w-3.5 text-slate-500"></i> Fecha de Nacimiento</p>
                            <p id="p-nacimiento" class="text-sm font-semibold text-white">—</p>
                        </div>
                        <div class="space-y-1.5">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="cake" class="h-3.5 w-3.5 text-slate-500"></i> Edad</p>
                            <p id="p-edad" class="text-sm font-semibold text-white">—</p>
                        </div>
                        <div class="space-y-1.5">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="user-round" class="h-3.5 w-3.5 text-slate-500"></i> Sexo</p>
                            <p id="p-sexo" class="text-sm font-semibold text-white">—</p>
                        </div>
                        <div class="space-y-1.5">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="map-pin" class="h-3.5 w-3.5 text-slate-500"></i> Ciudad</p>
                            <p id="p-ciudad" class="text-sm font-semibold text-white">—</p>
                        </div>
                        <div class="space-y-1.5">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="school" class="h-3.5 w-3.5 text-slate-500"></i> Colegio de Procedencia</p>
                            <p id="p-colegio" class="text-sm font-semibold text-white">—</p>
                        </div>
                        <div class="space-y-1.5 md:col-span-2 lg:col-span-3">
                            <p class="field-label flex items-center gap-1.5"><i data-lucide="home" class="h-3.5 w-3.5 text-slate-500"></i> Dirección Domiciliaria</p>
                            <p id="p-direccion" class="text-sm font-semibold text-white">—</p>
                        </div>
                    </div>
                </div>

                <!-- Inscripción -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                        <span class="px-2 py-0.5 rounded-md bg-indigo-600/20 text-indigo-400 text-[11px] font-bold tracking-wider">INSCRIPCIÓN</span>
                        <h3 class="text-sm font-bold text-white">Mi Inscripción</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            <div class="space-y-1.5">
                                <p class="field-label flex items-center gap-1.5"><i data-lucide="calendar-range" class="h-3.5 w-3.5 text-slate-500"></i> Gestión</p>
                                <p id="p-gestion" class="text-sm font-semibold text-white">—</p>
                            </div>
                            <div class="space-y-1.5">
                                <p class="field-label flex items-center gap-1.5"><i data-lucide="activity" class="h-3.5 w-3.5 text-slate-500"></i> Estado de Inscripción</p>
                                <span id="p-estado" class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg border w-fit"></span>
                            </div>
                            <div class="space-y-1.5">
                                <p class="field-label flex items-center gap-1.5"><i data-lucide="calendar-check" class="h-3.5 w-3.5 text-slate-500"></i> Fecha de Inscripción</p>
                                <p id="p-fecha-inscripcion" class="text-sm font-semibold text-white">—</p>
                            </div>
                            <div class="space-y-1.5">
                                <p class="field-label flex items-center gap-1.5"><i data-lucide="file-check-2" class="h-3.5 w-3.5 text-slate-500"></i> Requisitos Entregados</p>
                                <p id="p-requisitos" class="text-sm font-semibold text-white">—</p>
                            </div>
                        </div>

                        <div class="border-t border-slate-700/50 pt-5">
                            <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mb-3">Carreras de Preferencia</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-950 border border-slate-700/60">
                                    <span class="h-8 w-8 rounded-lg bg-blue-600 text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Primera Opción</p>
                                        <p id="p-carrera1" class="text-sm font-semibold text-white">—</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-950 border border-slate-700/60">
                                    <span class="h-8 w-8 rounded-lg bg-indigo-500 text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Segunda Opción</p>
                                        <p id="p-carrera2" class="text-sm font-semibold text-white">—</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: HORARIO                         ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-horario" class="app-module hidden space-y-6 max-w-5xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Mi Horario</h2>
                        <p class="text-sm text-slate-400">Grupo, días, horas y aula asignados</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-emerald-600/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <i data-lucide="calendar-clock" class="h-5 w-5"></i>
                    </div>
                </div>

                <!-- Sin grupo asignado -->
                <div id="horario-sin-grupo" class="hidden bg-slate-800/40 border border-amber-500/20 rounded-2xl p-10 text-center">
                    <div class="h-14 w-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 mx-auto mb-4">
                        <i data-lucide="calendar-x" class="h-6 w-6"></i>
                    </div>
                    <h3 class="text-base font-bold text-white mb-1">Aún no tienes un grupo asignado</h3>
                    <p class="text-sm text-slate-400">Tu horario aparecerá aquí una vez que la administración te asigne a un grupo.</p>
                </div>

                <div id="horario-content" class="hidden space-y-6">
                    <!-- Info del grupo -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                            <div class="h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0"><i data-lucide="users" class="h-5 w-5"></i></div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Mi Grupo</p>
                                <p id="h-grupo" class="text-2xl font-bold text-white mt-1">—</p>
                            </div>
                        </div>
                        <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                            <div class="h-12 w-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0"><i data-lucide="user-round" class="h-5 w-5"></i></div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Estudiantes</p>
                                <p id="h-cantidad" class="text-2xl font-bold text-white mt-1">—</p>
                            </div>
                        </div>
                        <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                            <div class="h-12 w-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0"><i data-lucide="layers" class="h-5 w-5"></i></div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Capacidad Máxima</p>
                                <p id="h-capacidad" class="text-2xl font-bold text-white mt-1">—</p>
                            </div>
                        </div>
                    </div>

                    <!-- Horario semanal -->
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded-md bg-blue-600/20 text-blue-400 text-[11px] font-bold tracking-wider">HORARIO</span>
                            <h3 class="text-sm font-bold text-white">Horario Semanal de Clases</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Día</th>
                                        <th class="px-4 py-3 text-center">Hora Inicio</th>
                                        <th class="px-4 py-3 text-center">Hora Fin</th>
                                        <th class="px-4 py-3 text-center">Turno</th>
                                        <th class="px-4 py-3 text-left">Aula</th>
                                    </tr>
                                </thead>
                                <tbody id="h-tabla-horario" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                            </table>
                        </div>
                        <div id="h-sin-horario" class="hidden text-center py-10 text-sm text-slate-400">
                            Tu grupo aún no tiene horarios ni aulas asignadas.
                        </div>
                    </div>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: RESULTADOS                      ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-resultados" class="app-module hidden space-y-6 max-w-5xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Mis Resultados</h2>
                        <p class="text-sm text-slate-400">Notas por examen, promedio por materia y resultado final · Umbral: 60</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-purple-600/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                        <i data-lucide="bar-chart-3" class="h-5 w-5"></i>
                    </div>
                </div>

                <!-- Resumen / promedio final -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                            <i data-lucide="award" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Resultado Final</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Aprobación: promedio por materia ≥ 60 en los 3 exámenes</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mb-1">Promedio General</p>
                            <p id="r-promedio-general" class="text-4xl font-black text-white">—</p>
                        </div>
                        <span id="r-estado-final" class="inline-flex items-center gap-1.5 text-sm font-bold px-3 py-1.5 rounded-xl border"></span>
                    </div>
                </div>

                <!-- Sin evaluaciones -->
                <div id="r-sin-evaluaciones" class="hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl p-10 text-center">
                    <div class="h-14 w-14 rounded-2xl bg-slate-700/40 border border-slate-700/50 flex items-center justify-center text-slate-400 mx-auto mb-4">
                        <i data-lucide="clipboard-x" class="h-6 w-6"></i>
                    </div>
                    <h3 class="text-base font-bold text-white mb-1">Aún no tienes resultados registrados</h3>
                    <p class="text-sm text-slate-400">Tus notas aparecerán aquí una vez que rindas tus exámenes.</p>
                </div>

                <!-- Tabla de notas por materia -->
                <div id="r-tabla-container" class="hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                        <span class="px-2 py-0.5 rounded-md bg-blue-600/20 text-blue-400 text-[11px] font-bold tracking-wider">NOTAS</span>
                        <h3 class="text-sm font-bold text-white">Notas por Materia</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Materia</th>
                                    <th class="px-4 py-3 text-center" id="r-th-ex1">Examen 1</th>
                                    <th class="px-4 py-3 text-center" id="r-th-ex2">Examen 2</th>
                                    <th class="px-4 py-3 text-center" id="r-th-ex3">Examen 3</th>
                                    <th class="px-4 py-3 text-center">Promedio</th>
                                    <th class="px-4 py-3 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="r-tabla-materias" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Detalle de exámenes rendidos -->
                <div id="r-examenes-container" class="hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                        <span class="px-2 py-0.5 rounded-md bg-amber-600/20 text-amber-400 text-[11px] font-bold tracking-wider">EXÁMENES</span>
                        <h3 class="text-sm font-bold text-white">Exámenes Rendidos</h3>
                    </div>
                    <div id="r-examenes-list" class="p-6 space-y-3"></div>
                </div>
            </section>

        </main>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         ESTILOS
    ══════════════════════════════════════════════════════════ -->
    <style>
        .field-label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #64748b;
            /* slate-500 */
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>

    <script>
        lucide.createIcons();

        // ════════════════════════════════════════════════════
        // CONFIG / ESTADO
        // ════════════════════════════════════════════════════
        const API_BASE_URL = (() => {
            const host = window.location.hostname;
            if (host === 'localhost' || host === '127.0.0.1') return 'http://localhost:8000';
            return window.location.origin; // Railway: mismo dominio
        })();

        const UMBRAL_APROBACION = 60;

        const token = localStorage.getItem('token');
        const idPostulante = localStorage.getItem('id_postulante');

        if (!token) {
            window.location.href = '/login';
        }

        let postulanteData = null;
        let evaluacionesData = [];
        let grupoData = null;

        const PAGE_TITLES = {
            perfil: 'Mi Perfil',
            horario: 'Mi Horario',
            resultados: 'Mis Resultados',
        };

        // ════════════════════════════════════════════════════
        // HELPERS
        // ════════════════════════════════════════════════════
        function formatearFecha(fechaStr) {
            if (!fechaStr) return '—';
            const f = new Date(fechaStr + (fechaStr.length <= 10 ? 'T00:00:00' : ''));
            if (isNaN(f.getTime())) return fechaStr;
            return f.toLocaleDateString('es-BO', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        function formatearHora(horaStr) {
            if (!horaStr) return '—';
            return horaStr.substring(0, 5);
        }

        function badgeEstadoInscripcion(estado) {
            const map = {
                'PENDIENTE': 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                'PROCESADO': 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                'ANULADO': 'bg-red-500/10 text-red-400 border-red-500/20',
            };
            const icon = {
                'PENDIENTE': 'clock',
                'PROCESADO': 'check-circle-2',
                'ANULADO': 'x-circle',
            };
            const cls = map[estado] || 'bg-slate-700/40 text-slate-400 border-slate-600/40';
            const ic = icon[estado] || 'help-circle';
            return {
                cls,
                ic,
                label: estado || '—'
            };
        }

        async function apiFetch(path) {
            const res = await fetch(`${API_BASE_URL}${path}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
            });
            // ── DEBUG TEMPORAL ──
            const clone = res.clone();
            console.log('apiFetch', path, 'status:', res.status);
            clone.json().then(j => console.log('body:', j)).catch(() => {});


            if (res.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('id_postulante');
                console.log("Hola desde Laravel");
                //window.location.href = '/login';
                return null;
            }

            const json = await res.json();
            if (!res.ok || json.success === false) {
                throw new Error(json.message || `Error en ${path}`);
            }
            return json;
        }

        function mostrarError(msg) {
            document.getElementById('loading-global').classList.add('hidden');
            const err = document.getElementById('error-global');
            err.classList.remove('hidden');
            document.getElementById('error-global-msg').textContent = msg;
        }

        // ════════════════════════════════════════════════════
        // CARGA DE DATOS
        // ════════════════════════════════════════════════════
        async function cargarTodo() {
            try {
                // 1. Datos del postulante (perfil + inscripción + grupo)
                const resPost = await apiFetch(`/api/v1/postulante/me`);
                postulanteData = resPost.data;

                // 2. Evaluaciones / notas
                const resEval = await apiFetch(`/api/v1/postulante/evaluaciones`);
                evaluacionesData = resEval.data || [];

                // 3. Horario del grupo (si tiene grupo asignado)
                const resHorario = await apiFetch(`/api/v1/postulante/horario`);
                grupoData = resHorario.data?.grupo ?
                    {
                        ...resHorario.data.grupo,
                        horarios: resHorario.data.horarios
                    } :
                    null;

                renderHeader();
                renderPerfil();
                renderHorario();
                renderResultados();

                document.getElementById('loading-global').classList.add('hidden');
                mostrarModulo('perfil');

            } catch (e) {
                mostrarError(e.message || 'No se pudo conectar con el servidor.');
            }
        }

        // ════════════════════════════════════════════════════
        // RENDER: HEADER / SIDEBAR
        // ════════════════════════════════════════════════════
        function renderHeader() {
            const nombre = postulanteData.txt_nombre || 'Postulante';
            const inicial = nombre.trim().charAt(0).toUpperCase() || 'P';

            document.getElementById('header-username').textContent = nombre;
            document.getElementById('header-ci').textContent = `CI: ${postulanteData.txt_ci || '—'}`;
            document.getElementById('header-avatar').textContent = inicial;

            document.getElementById('sidebar-username').textContent = nombre;
            document.getElementById('sidebar-ci').textContent = `CI: ${postulanteData.txt_ci || '—'}`;
            document.getElementById('sidebar-avatar').textContent = inicial;
        }

        // ════════════════════════════════════════════════════
        // RENDER: PERFIL
        // ════════════════════════════════════════════════════
        function renderPerfil() {
            const p = postulanteData;

            document.getElementById('p-nombre').textContent = p.txt_nombre || '—';
            document.getElementById('p-ci').textContent = p.txt_ci || '—';
            document.getElementById('p-correo').textContent = p.txt_correo || '—';
            document.getElementById('p-telefono').textContent = p.txt_telefono || '—';
            document.getElementById('p-nacimiento').textContent = formatearFecha(p.fch_nacimiento);
            document.getElementById('p-edad').textContent = p.edad != null ? `${p.edad} años` : '—';

            const sexoMap = {
                M: 'Masculino',
                F: 'Femenino',
                X: 'Otro'
            };
            document.getElementById('p-sexo').textContent = sexoMap[p.chr_sexo] || p.chr_sexo || '—';

            document.getElementById('p-ciudad').textContent = p.txt_ciudad || '—';
            document.getElementById('p-colegio').textContent = p.txt_colegio || '—';
            document.getElementById('p-direccion').textContent = p.txt_direccion || '—';

            // Inscripción
            const ins = p.ultima_inscripcion;
            document.getElementById('p-gestion').textContent = p.gestion ?
                `${p.gestion.txt_periodo} - ${p.gestion.int_año}` :
                '—';
            document.getElementById('p-fecha-inscripcion').textContent = ins ?
                formatearFecha(ins.fch_inscripcion) :
                '—';
            document.getElementById('p-requisitos').textContent = p.requisitos_entregados != null ?
                `${p.requisitos_entregados} requisito(s)` :
                '—';

            const estadoBadge = document.getElementById('p-estado');
            if (ins) {
                const {
                    cls,
                    ic,
                    label
                } = badgeEstadoInscripcion(ins.txt_estado_inscripcion);
                estadoBadge.className = `inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg border w-fit ${cls}`;
                estadoBadge.innerHTML = `<i data-lucide="${ic}" class="h-3.5 w-3.5"></i> ${label}`;
            } else {
                estadoBadge.className = 'inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg border w-fit bg-slate-700/40 text-slate-400 border-slate-600/40';
                estadoBadge.innerHTML = `<i data-lucide="help-circle" class="h-3.5 w-3.5"></i> Sin inscripción`;
            }

            document.getElementById('p-carrera1').textContent = p.carrera_1 || 'N/A';
            document.getElementById('p-carrera2').textContent = p.carrera_2 || '-';

            lucide.createIcons();
        }

        // ════════════════════════════════════════════════════
        // RENDER: HORARIO
        // ════════════════════════════════════════════════════
        function renderHorario() {
            const sinGrupo = document.getElementById('horario-sin-grupo');
            const contenido = document.getElementById('horario-content');

            if (!postulanteData.grupo?.id_grupo) {
                sinGrupo.classList.remove('hidden');
                contenido.classList.add('hidden');
                return;
            }

            sinGrupo.classList.add('hidden');
            contenido.classList.remove('hidden');

            document.getElementById('h-grupo').textContent = postulanteData.grupo.txt_nombre || '—';

            if (grupoData) {
                document.getElementById('h-cantidad').textContent = grupoData.int_cantidad_estudiantes ?? '—';
                document.getElementById('h-capacidad').textContent = grupoData.int_capacidad_maxma ?? '—';
            } else {
                document.getElementById('h-cantidad').textContent = '—';
                document.getElementById('h-capacidad').textContent = '—';
            }

            const tbody = document.getElementById('h-tabla-horario');
            const sinHorario = document.getElementById('h-sin-horario');
            tbody.innerHTML = '';

            const horarios = grupoData?.horarios || [];

            if (horarios.length === 0) {
                sinHorario.classList.remove('hidden');
                return;
            }
            sinHorario.classList.add('hidden');

            const ordenDias = ['LUNES', 'MARTES', 'MIERCOLES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'SÁBADO', 'DOMINGO'];
            const horariosOrdenados = [...horarios].sort((a, b) => {
                const da = ordenDias.indexOf((a.txt_dia_semana || '').toUpperCase());
                const db = ordenDias.indexOf((b.txt_dia_semana || '').toUpperCase());
                if (da !== db) return da - db;
                return (a.tm_hora_inicio || '').localeCompare(b.tm_hora_inicio || '');
            });

            horariosOrdenados.forEach(h => {
                const aula = h.aula ?
                    `Piso ${h.aula.int_piso} - Aula ${h.aula.txt_nro_aula}` :
                    (h.id_aula ? `Aula #${h.id_aula}` : '—');

                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-800/60 transition-colors';
                tr.innerHTML = `
                <td class="px-4 py-3 font-semibold text-white">${h.txt_dia_semana || '—'}</td>
                <td class="px-4 py-3 text-center">${formatearHora(h.tm_hora_inicio)}</td>
                <td class="px-4 py-3 text-center">${formatearHora(h.tm_hora_final)}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center text-xs font-bold px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/20">
                        ${h.turno?.txt_nombre || h.txt_turno || '—'}
                    </span>
                </td>
                <td class="px-4 py-3">${aula}</td>
            `;
                tbody.appendChild(tr);
            });
        }

        // ════════════════════════════════════════════════════
        // RENDER: RESULTADOS
        // ════════════════════════════════════════════════════
        function renderResultados() {
            const sinEval = document.getElementById('r-sin-evaluaciones');
            const tablaCont = document.getElementById('r-tabla-container');
            const examCont = document.getElementById('r-examenes-container');

            if (!evaluacionesData || evaluacionesData.length === 0) {
                sinEval.classList.remove('hidden');
                tablaCont.classList.add('hidden');
                examCont.classList.add('hidden');
                document.getElementById('r-promedio-general').textContent = '—';
                const estadoBadge = document.getElementById('r-estado-final');
                estadoBadge.className = 'inline-flex items-center gap-1.5 text-sm font-bold px-3 py-1.5 rounded-xl border bg-slate-700/40 text-slate-400 border-slate-600/40';
                estadoBadge.innerHTML = `<i data-lucide="minus-circle" class="h-4 w-4"></i> Sin Evaluar`;
                lucide.createIcons();
                return;
            }

            sinEval.classList.add('hidden');
            tablaCont.classList.remove('hidden');
            examCont.classList.remove('hidden');

            // ── Agrupar notas por materia ──
            const materias = {};
            evaluacionesData.forEach(ev => {
                (ev.detalles || []).forEach(d => {
                    if (!materias[d.id_materia]) {
                        materias[d.id_materia] = {
                            nombre: d.txt_materia,
                            notas: {}
                        };
                    }
                    materias[d.id_materia].notas[ev.int_nro_examen] = d.num_nota;
                });
            });

            const examenesExistentes = [...new Set(evaluacionesData.map(e => e.int_nro_examen))].sort();
            [1, 2, 3].forEach(n => {
                const th = document.getElementById(`r-th-ex${n}`);
                if (examenesExistentes.includes(n)) {
                    th.classList.remove('hidden');
                    th.textContent = `Examen ${n}`;
                } else {
                    th.classList.add('hidden');
                }
            });

            const tbody = document.getElementById('r-tabla-materias');
            tbody.innerHTML = '';

            let sumaPromedios = 0;
            let totalMaterias = 0;
            let todasAprobadas = true;

            Object.values(materias).forEach(m => {
                const notas = [1, 2, 3].map(n => m.notas[n]).filter(v => v !== undefined && v !== null);
                const promedio = notas.length > 0 ?
                    notas.reduce((a, b) => a + Number(b), 0) / notas.length :
                    null;

                if (promedio !== null) {
                    sumaPromedios += promedio;
                    totalMaterias++;
                    if (promedio < UMBRAL_APROBACION) todasAprobadas = false;
                } else {
                    todasAprobadas = false;
                }

                const aprobado = promedio !== null && promedio >= UMBRAL_APROBACION;
                const estadoCls = promedio === null ?
                    'bg-slate-700/40 text-slate-400 border-slate-600/40' :
                    (aprobado ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20');
                const estadoLabel = promedio === null ? 'Pendiente' : (aprobado ? 'Aprobado' : 'Reprobado');

                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-800/60 transition-colors';

                let celdasNotas = '';
                [1, 2, 3].forEach(n => {
                    if (!examenesExistentes.includes(n)) return;
                    const nota = m.notas[n];
                    celdasNotas += `<td class="px-4 py-3 text-center font-semibold text-slate-200">${nota != null ? Number(nota).toFixed(2) : '—'}</td>`;
                });

                tr.innerHTML = `
                <td class="px-4 py-3 font-semibold text-white">${m.nombre}</td>
                ${celdasNotas}
                <td class="px-4 py-3 text-center font-black text-white">${promedio !== null ? promedio.toFixed(2) : '—'}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center text-xs font-bold px-2.5 py-1 rounded-lg border ${estadoCls}">${estadoLabel}</span>
                </td>
            `;
                tbody.appendChild(tr);
            });

            // ── Promedio general y estado final ──
            const promedioGeneral = totalMaterias > 0 ? (sumaPromedios / totalMaterias) : null;
            document.getElementById('r-promedio-general').textContent = promedioGeneral !== null ?
                promedioGeneral.toFixed(2) :
                '—';

            const estadoBadge = document.getElementById('r-estado-final');
            const evaluacionCompleta = totalMaterias === 4; // 4 materias del plan

            if (promedioGeneral === null) {
                estadoBadge.className = 'inline-flex items-center gap-1.5 text-sm font-bold px-3 py-1.5 rounded-xl border bg-slate-700/40 text-slate-400 border-slate-600/40';
                estadoBadge.innerHTML = `<i data-lucide="minus-circle" class="h-4 w-4"></i> Sin Evaluar`;
            } else if (!evaluacionCompleta) {
                estadoBadge.className = 'inline-flex items-center gap-1.5 text-sm font-bold px-3 py-1.5 rounded-xl border bg-amber-500/10 text-amber-400 border-amber-500/20';
                estadoBadge.innerHTML = `<i data-lucide="hourglass" class="h-4 w-4"></i> En Proceso`;
            } else if (todasAprobadas) {
                estadoBadge.className = 'inline-flex items-center gap-1.5 text-sm font-bold px-3 py-1.5 rounded-xl border bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                estadoBadge.innerHTML = `<i data-lucide="check-circle-2" class="h-4 w-4"></i> Aprobado`;
            } else {
                estadoBadge.className = 'inline-flex items-center gap-1.5 text-sm font-bold px-3 py-1.5 rounded-xl border bg-red-500/10 text-red-400 border-red-500/20';
                estadoBadge.innerHTML = `<i data-lucide="x-circle" class="h-4 w-4"></i> Reprobado`;
            }

            // ── Lista de exámenes rendidos ──
            const examList = document.getElementById('r-examenes-list');
            examList.innerHTML = '';

            evaluacionesData
                .sort((a, b) => a.int_nro_examen - b.int_nro_examen)
                .forEach(ev => {
                    const promedioExamen = (ev.detalles || []).length > 0 ?
                        ev.detalles.reduce((a, d) => a + Number(d.num_nota), 0) / ev.detalles.length :
                        null;

                    const div = document.createElement('div');
                    div.className = 'flex items-center justify-between p-4 rounded-xl bg-slate-950 border border-slate-700/60';
                    div.innerHTML = `
                    <div class="flex items-center gap-4">
                        <span class="h-10 w-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 text-sm font-bold flex items-center justify-center shrink-0">
                            E${ev.int_nro_examen}
                        </span>
                        <div>
                            <p class="text-sm font-bold text-white">Examen ${ev.int_nro_examen}</p>
                            <p class="text-xs text-slate-500">Fecha: ${formatearFecha(ev.fch_examen)}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest">Promedio del examen</p>
                        <p class="text-lg font-black text-white">${promedioExamen !== null ? promedioExamen.toFixed(2) : '—'}</p>
                    </div>
                `;
                    examList.appendChild(div);
                });

            lucide.createIcons();
        }

        // ════════════════════════════════════════════════════
        // NAVEGACIÓN ENTRE MÓDULOS
        // ════════════════════════════════════════════════════
        function mostrarModulo(moduleName) {
            document.querySelectorAll('.app-module').forEach(m => m.classList.add('hidden'));
            document.getElementById(`mod-${moduleName}`).classList.remove('hidden');

            const title = document.getElementById('header-page-title');
            if (title) title.textContent = PAGE_TITLES[moduleName] ?? moduleName;

            document.querySelectorAll('.nav-btn').forEach(btn => {
                const active = btn.dataset.module === moduleName;
                btn.className = active ?
                    'nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-white bg-indigo-600/80' :
                    'nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white';
            });

            lucide.createIcons();
        }

        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', () => mostrarModulo(btn.dataset.module));
        });

        // ════════════════════════════════════════════════════
        // LOGOUT
        // ════════════════════════════════════════════════════
        async function handleLogout() {
            try {
                await fetch(`${API_BASE_URL}/api/v1/auth/logout`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                });
            } catch (e) {
                /* ignorar errores de logout */ }

            localStorage.removeItem('token');
            localStorage.removeItem('id_postulante');
            window.location.href = '/login';
        }

        // ════════════════════════════════════════════════════
        // INIT
        // ════════════════════════════════════════════════════
        cargarTodo();
    </script>
</body>

</html>