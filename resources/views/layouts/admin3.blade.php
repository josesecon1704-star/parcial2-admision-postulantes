<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Administrativo - Admisión FICCT</title>
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
                    <span class="text-[10px] uppercase font-bold text-blue-400 tracking-wider">PANEL ADMINISTRATIVO</span>
                </div>
            </div>

            <!-- Navegación por módulos agrupados -->
            <nav id="sidebar-menu" class="p-3 space-y-1 overflow-y-auto flex-1">

                <!-- ── Dashboard ─────────────────────────── -->
                <button data-module="dashboard" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                    <i data-lucide="layout-dashboard" class="h-4 w-4 shrink-0"></i>
                    <span>Dashboard</span>
                </button>

                <!-- ── Control de Usuarios ────────────────── -->
                <button data-module="usuarios" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                    <i data-lucide="user-cog" class="h-4 w-4 shrink-0"></i>
                    <span>Control de Usuarios</span>
                </button>

                <!-- ── Postulantes ────────────────────────── -->
                <div>
                    <button onclick="toggleGroup('grp-postulantes')"
                        class="group-toggle w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                        <span class="flex items-center gap-3">
                            <i data-lucide="users" class="h-4 w-4 shrink-0"></i>
                            Postulantes
                        </span>
                        <i data-lucide="chevron-down" class="h-3.5 w-3.5 shrink-0 transition-transform duration-200" id="icon-grp-postulantes"></i>
                    </button>
                    <div id="grp-postulantes" class="hidden mt-0.5 ml-4 pl-3 border-l border-slate-700/50 space-y-0.5 pb-1">
                        <button data-module="post-edicion" class="nav-btn sub-item w-full flex items-center gap-2 px-2 py-2 text-xs font-medium rounded-md text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all cursor-pointer">
                            <i data-lucide="pencil" class="h-3.5 w-3.5 shrink-0"></i>
                            Añadir / Modificar
                        </button>
                        <button data-module="post-buscar" class="nav-btn sub-item w-full flex items-center gap-2 px-2 py-2 text-xs font-medium rounded-md text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all cursor-pointer">
                            <i data-lucide="search" class="h-3.5 w-3.5 shrink-0"></i>
                            Buscar
                        </button>
                    </div>
                </div>

                <!-- ── Control de Docentes ────────────────── -->
                <div>
                    <button onclick="toggleGroup('grp-docentes')"
                        class="group-toggle w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                        <span class="flex items-center gap-3">
                            <i data-lucide="briefcase" class="h-4 w-4 shrink-0"></i>
                            Control de Docentes
                        </span>
                        <i data-lucide="chevron-down" class="h-3.5 w-3.5 shrink-0 transition-transform duration-200" id="icon-grp-docentes"></i>
                    </button>
                    <div id="grp-docentes" class="hidden mt-0.5 ml-4 pl-3 border-l border-slate-700/50 space-y-0.5 pb-1">
                        <button data-module="docentes" class="nav-btn sub-item w-full flex items-center gap-2 px-2 py-2 text-xs font-medium rounded-md text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all cursor-pointer">
                            <i data-lucide="user-round-plus" class="h-3.5 w-3.5 shrink-0"></i>
                            Registrar Docente
                        </button>
                        <button data-module="docente-asignar" class="nav-btn sub-item w-full flex items-center gap-2 px-2 py-2 text-xs font-medium rounded-md text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all cursor-pointer">
                            <i data-lucide="user-round-check" class="h-3.5 w-3.5 shrink-0"></i>
                            Asignar Docente
                        </button>
                    </div>
                </div>

                <!-- ── Asignación de Grupos ────────────────── -->
                <button data-module="grupos" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                    <i data-lucide="layout-grid" class="h-4 w-4 shrink-0"></i>
                    <span>Asignación de Grupos</span>
                </button>

                <!-- ── Modificar (Notas) ──────────────────── -->
                <div>
                    <button onclick="toggleGroup('grp-notas')"
                        class="group-toggle w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                        <span class="flex items-center gap-3">
                            <i data-lucide="file-spreadsheet" class="h-4 w-4 shrink-0"></i>
                            Modificar
                        </span>
                        <i data-lucide="chevron-down" class="h-3.5 w-3.5 shrink-0 transition-transform duration-200" id="icon-grp-notas"></i>
                    </button>
                    <div id="grp-notas" class="hidden mt-0.5 ml-4 pl-3 border-l border-slate-700/50 space-y-0.5 pb-1">
                        <button data-module="notas" class="nav-btn sub-item w-full flex items-center gap-2 px-2 py-2 text-xs font-medium rounded-md text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all cursor-pointer">
                            <i data-lucide="clipboard-pen" class="h-3.5 w-3.5 shrink-0"></i>
                            Notas
                        </button>
                    </div>
                </div>

                <!-- ── Reportes Analíticos ────────────────── -->
                <button data-module="reportes" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                    <i data-lucide="pie-chart" class="h-4 w-4 shrink-0"></i>
                    <span>Reportes Analíticos</span>
                </button>

            </nav>
        </div>

        <!-- Pie del sidebar -->
        <div class="p-3 border-t border-slate-700/50 space-y-1 shrink-0">
            <div class="flex items-center gap-3 px-3 py-2">
                <div class="h-8 w-8 rounded-lg bg-slate-700 flex items-center justify-center font-bold text-sm text-indigo-400 border border-slate-600 shrink-0">A</div>
                <div class="overflow-hidden">
                    <p id="sidebar-username" class="text-xs font-bold text-white truncate">Administrador</p>
                    <p id="sidebar-email" class="text-[10px] text-slate-400 truncate">admin@uagrm.edu.bo</p>
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
                <span>Panel Administrativo</span>
                <i data-lucide="chevron-right" class="h-3 w-3"></i>
                <span id="header-page-title" class="text-white font-medium">Escritorio de Control</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p id="header-username" class="text-xs font-bold text-white">Administrador</p>
                    <p id="header-email" class="text-[10px] text-slate-400">admin@uagrm.edu.bo</p>
                </div>
                <div class="h-9 w-9 rounded-xl bg-slate-700 flex items-center justify-center font-bold text-sm text-indigo-400 border border-slate-600">A</div>
            </div>
        </header>

        <!-- Módulos -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: DASHBOARD                      ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-dashboard" class="app-module space-y-6">
                <div class="bg-slate-800/40 backdrop-blur-md border border-slate-700/50 rounded-2xl p-8 shadow-xl">
                    <h2 class="text-2xl font-bold text-white tracking-tight mb-2">Panel de Control de Admisión (CUP)</h2>
                    <p class="text-sm text-slate-400 max-w-2xl leading-relaxed">Monitoreo estadístico y administración centralizada del proceso de admisión preuniversitario para la facultad.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                        <div class="h-12 w-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0"><i data-lucide="users" class="h-5 w-5"></i></div>
                        <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Inscritos</p><p id="stat-inscritos" class="text-3xl font-bold text-white mt-1">—</p></div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                        <div class="h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0"><i data-lucide="check-circle" class="h-5 w-5"></i></div>
                        <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Aprobados</p><p id="stat-aprobados" class="text-3xl font-bold text-emerald-400 mt-1">—</p></div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                        <div class="h-12 w-12 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 shrink-0"><i data-lucide="x-circle" class="h-5 w-5"></i></div>
                        <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Reprobados</p><p id="stat-reprobados" class="text-3xl font-bold text-red-400 mt-1">—</p></div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                        <div class="h-12 w-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0"><i data-lucide="layers" class="h-5 w-5"></i></div>
                        <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Grupos Habilitados</p><p id="stat-grupos" class="text-3xl font-bold text-white mt-1">—</p></div>
                    </div>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: USUARIOS                       ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-usuarios" class="app-module hidden space-y-6 max-w-5xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Administración de Personal de Admisión</h2>
                        <p class="text-sm text-slate-400">Crea, modifica y asigna roles a los operadores del sistema (Administrador, Secretaria, Docente).</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-indigo-600/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <i data-lucide="user-cog" class="h-5 w-5"></i>
                    </div>
                </div>

                <form id="form-registro-usuario" class="bg-slate-800/40 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider">Nombre de Usuario</label>
                        <input type="text" id="usr_username" required placeholder="Ej: mlopez"
                            class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider">Correo Electrónico</label>
                        <input type="email" id="usr_email" required placeholder="ejemplo@uagrm.edu.bo"
                            class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider">Contraseña</label>
                        <input type="password" id="usr_password" required placeholder="Mín. 8 car., 1 Mayús., 1 Núm."
                            class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                        <input type="password" id="usr_password_confirmation" required placeholder="Confirma tu contraseña"
                            class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider">Rol de Sistema</label>
                        <select id="usr_rol" required
                            class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                            <option value="" disabled selected>Seleccione un Rol</option>
                            <option value="1">ADMINISTRADOR</option>
                            <option value="2">SECRETARIA</option>
                            <option value="3">DOCENTE</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="md:col-span-4 w-full bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium py-2.5 px-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="user-plus" class="h-4 w-4"></i> Crear Cuenta
                    </button>
                </form>

                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-800/80 border-b border-slate-700/50 text-slate-400 text-xs uppercase font-bold">
                                <th class="p-4">ID</th><th class="p-4">Usuario</th><th class="p-4">Correo</th>
                                <th class="p-4">Rol</th><th class="p-4">Estado</th><th class="p-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-usuarios-body" class="text-sm text-slate-300 divide-y divide-slate-700/30"></tbody>
                    </table>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════════════════════════╗
                 ║  MOD: POST-EDICION                                         ║
                 ║  CU-06 Registrar · CU-07 Modificar · CU-08 Eliminar        ║
                 ╚══════════════════════════════════════════════════════════╝ -->
            <section id="mod-post-edicion" class="app-module hidden space-y-6 max-w-5xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Postulantes — Añadir / Modificar</h2>
                        <p class="text-sm text-slate-400">CU-06 Registrar · CU-07 Modificar datos · CU-08 Eliminar registro</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                        <i data-lucide="pencil" class="h-5 w-5"></i>
                    </div>
                </div>

                <!-- ══════ CU-06: REGISTRAR POSTULANTE ══════ -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <button onclick="toggleSection('sec-cu06', 'ico-cu06')"
                        class="w-full flex items-center justify-between px-6 py-4 border-b border-slate-700/50 hover:bg-slate-800/60 transition-all cursor-pointer">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded-md bg-blue-600/20 text-blue-400 text-[11px] font-bold tracking-wider">CU-06</span>
                            <h3 class="text-sm font-bold text-white">Registrar Nuevo Postulante</h3>
                        </div>
                        <i data-lucide="chevron-down" id="ico-cu06" class="h-4 w-4 text-slate-400 transition-transform duration-200 rotate-180"></i>
                    </button>
                    <div id="sec-cu06" class="p-6 space-y-5">
                        <form id="form-registro-postulante">
                            <!-- Campos del postulante (tbl_postulante) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                <div class="space-y-1.5">
                                    <label class="field-label flex items-center gap-1.5"><i data-lucide="id-card" class="h-3.5 w-3.5 text-slate-500"></i> Cédula de Identidad (CI) <span class="text-red-400">*</span></label>
                                    <input type="text" id="txt_ci" required maxlength="20" placeholder="Ej: 8765432 SC"
                                        class="field-input w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="field-label flex items-center gap-1.5"><i data-lucide="user" class="h-3.5 w-3.5 text-slate-500"></i> Nombres y Apellidos <span class="text-red-400">*</span></label>
                                    <input type="text" id="txt_nombre" required maxlength="150" placeholder="Ej: Juan Pérez Mamani"
                                        class="field-input w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="field-label flex items-center gap-1.5"><i data-lucide="mail" class="h-3.5 w-3.5 text-slate-500"></i> Correo Electrónico <span class="text-red-400">*</span></label>
                                    <input type="email" id="txt_correo" required maxlength="100" placeholder="ejemplo@uagrm.edu.bo"
                                        class="field-input w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="field-label flex items-center gap-1.5"><i data-lucide="phone" class="h-3.5 w-3.5 text-slate-500"></i> Teléfono / Celular</label>
                                    <input type="text" id="txt_telefono" maxlength="20" placeholder="Ej: 78945612"
                                        class="field-input w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="field-label flex items-center gap-1.5"><i data-lucide="calendar" class="h-3.5 w-3.5 text-slate-500"></i> Fecha de Nacimiento <span class="text-red-400">*</span></label>
                                    <input type="date" id="fch_nacimiento" required
                                        class="field-input w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="field-label flex items-center gap-1.5"><i data-lucide="user-round" class="h-3.5 w-3.5 text-slate-500"></i> Sexo <span class="text-red-400">*</span></label>
                                    <select id="chr_sexo" required
                                        class="field-input w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500 transition-all">
                                        <option value="" disabled selected>Selecciona</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                        <option value="X">Prefiero no decirlo</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="field-label flex items-center gap-1.5"><i data-lucide="school" class="h-3.5 w-3.5 text-slate-500"></i> Colegio de Procedencia</label>
                                    <input type="text" id="txt_colegio" maxlength="150" placeholder="Ej: Colegio Nacional Florida"
                                        class="field-input w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="field-label flex items-center gap-1.5"><i data-lucide="map-pin" class="h-3.5 w-3.5 text-slate-500"></i> Ciudad</label>
                                    <input type="text" id="txt_ciudad" maxlength="100" placeholder="Ej: Santa Cruz de la Sierra"
                                        class="field-input w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                                <div class="space-y-1.5 md:col-span-2">
                                    <label class="field-label flex items-center gap-1.5"><i data-lucide="home" class="h-3.5 w-3.5 text-slate-500"></i> Dirección Domiciliaria</label>
                                    <input type="text" id="txt_direccion" maxlength="255" placeholder="Ej: Av. Bush, 2do Anillo, Calle 5 Nro 45"
                                        class="field-input w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                            </div>

                            <!-- Requisitos físicos (tbl_requisito / tbl_requisito_postulante) -->
                            <div class="border-t border-slate-700/50 pt-5">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center gap-2">
                                        <i data-lucide="clipboard-check" class="h-4 w-4"></i>
                                        Verificación de Requisitos Físicos
                                    </h4>
                                    <span class="text-[10px] text-slate-500">El trigger valida que todos estén presentados antes de inscribir</span>
                                </div>
                                <div id="lista-requisitos" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <p class="text-slate-500 text-xs col-span-2">Cargando requisitos...</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-700/50">
                                <button type="reset"
                                    class="px-4 py-2 rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800 text-sm font-medium transition-all flex items-center gap-2 cursor-pointer">
                                    <i data-lucide="refresh-cw" class="h-4 w-4"></i> Limpiar
                                </button>
                                <button type="submit" id="btn-registrar-postulante"
                                    class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-medium text-sm shadow-lg transition-all flex items-center gap-2 cursor-pointer">
                                    <i data-lucide="save" class="h-4 w-4"></i> Guardar Postulante
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ══════ CU-07 + CU-08: TABLA MODIFICAR / ELIMINAR ══════ -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700/50">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded-md bg-amber-600/20 text-amber-400 text-[11px] font-bold tracking-wider">CU-07</span>
                            <span class="px-2 py-0.5 rounded-md bg-red-600/20 text-red-400 text-[11px] font-bold tracking-wider">CU-08</span>
                            <h3 class="text-sm font-bold text-white">Modificar / Eliminar Postulante</h3>
                        </div>
                        <input type="text" id="buscar-post-edicion" placeholder="Buscar por CI o nombre..."
                            class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white w-52 focus:outline-none focus:border-blue-500"
                            oninput="filtrarTablaEdicion()">
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-800/80 text-slate-400 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">CI</th>
                                    <th class="px-4 py-3">Nombre Completo</th>
                                    <th class="px-4 py-3">Correo</th>
                                    <th class="px-4 py-3">Teléfono</th>
                                    <th class="px-4 py-3">Ciudad</th>
                                    <th class="px-4 py-3">Carreras (1ra / 2da)</th>
                                    <th class="px-4 py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-post-edicion-body" class="text-slate-300 divide-y divide-slate-700/30">
                                <tr><td colspan="7" class="p-6 text-center text-slate-500 text-xs">Cargando postulantes...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Modal edición inline CU-07 -->
            <div id="modal-editar-post" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                <div class="bg-slate-800 rounded-2xl w-full max-w-2xl shadow-2xl border border-slate-700 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded-md bg-amber-600/20 text-amber-400 text-[11px] font-bold">CU-07</span>
                            <h2 class="text-white text-base font-bold">Modificar Datos del Postulante</h2>
                        </div>
                        <button onclick="cerrarModalPost()" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>
                    <form id="form-editar-post" class="p-6 space-y-4">
                        <input type="hidden" id="edit_id_post">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase">C.I. *</label>
                                <input type="text" id="edit_ci" maxlength="20"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase">Nombres y Apellidos *</label>
                                <input type="text" id="edit_nombre" maxlength="150"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase">Correo *</label>
                                <input type="email" id="edit_correo" maxlength="100"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase">Teléfono</label>
                                <input type="text" id="edit_telefono" maxlength="20"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase">Fecha Nacimiento</label>
                                <input type="date" id="edit_fch_nacimiento"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase">Sexo</label>
                                <select id="edit_sexo"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="X">Prefiero no decirlo</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase">Colegio</label>
                                <input type="text" id="edit_colegio" maxlength="150"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase">Ciudad</label>
                                <input type="text" id="edit_ciudad" maxlength="100"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-xs font-bold text-slate-400 uppercase">Dirección</label>
                                <input type="text" id="edit_direccion" maxlength="255"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-700">
                            <button type="button" onclick="cerrarModalPost()"
                                class="px-4 py-2 text-slate-400 hover:text-white text-sm font-medium transition cursor-pointer">Cancelar</button>
                            <button type="submit"
                                class="px-6 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-bold rounded-xl transition shadow-lg cursor-pointer flex items-center gap-2">
                                <i data-lucide="save" class="h-4 w-4"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: REGISTRO POSTULANTE (CU-06)    ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-postulante" class="app-module hidden space-y-6 max-w-4xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Registro de Nuevo Postulante</h2>
                        <p class="text-sm text-slate-400">Introduce los datos oficiales para el ingreso al curso preuniversitario (CUP).</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                        <i data-lucide="user-plus" class="h-5 w-5"></i>
                    </div>
                </div>
                <form id="form-registro-postulante" class="bg-slate-800/40 backdrop-blur-md border border-slate-700/50 rounded-2xl p-8 shadow-xl space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="text-cursor-input" class="h-3.5 w-3.5 text-slate-500"></i> C.I. <span class="text-red-400">*</span></label>
                            <input type="text" id="txt_ci" required placeholder="Ej: 8765432 SC"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="user" class="h-3.5 w-3.5 text-slate-500"></i> Nombres y Apellidos <span class="text-red-400">*</span></label>
                            <input type="text" id="txt_nombre" required placeholder="Ej: Juan Pérez Mamani"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="mail" class="h-3.5 w-3.5 text-slate-500"></i> Correo Electrónico <span class="text-red-400">*</span></label>
                            <input type="email" id="txt_correo" required placeholder="ejemplo@uagrm.edu.bo"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="phone" class="h-3.5 w-3.5 text-slate-500"></i> Teléfono / Celular</label>
                            <input type="text" id="txt_telefono" placeholder="Ej: 78945612"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="calendar" class="h-3.5 w-3.5 text-slate-500"></i> Fecha de Nacimiento <span class="text-red-400">*</span></label>
                            <input type="date" id="fch_nacimiento" required
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="text" class="h-3.5 w-3.5 text-slate-500"></i> Sexo / Género <span class="text-red-400">*</span></label>
                            <select id="chr_sexo" required
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                                <option value="" disabled selected>Selecciona una opción</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="school" class="h-3.5 w-3.5 text-slate-500"></i> Colegio de Procedencia</label>
                            <input type="text" id="txt_colegio" placeholder="Ej: Colegio Nacional Florida"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="map-pin" class="h-3.5 w-3.5 text-slate-500"></i> Ciudad</label>
                            <input type="text" id="txt_ciudad" placeholder="Ej: Santa Cruz de la Sierra"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="home" class="h-3.5 w-3.5 text-slate-500"></i> Dirección Domiciliaria</label>
                        <input type="text" id="txt_direccion" placeholder="Ej: Av. Bush, 2do Anillo, Calle 5 Nro 45"
                            class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                    </div>
                    <div class="flex items-center justify-end gap-4 border-t border-slate-700/50 pt-6">
                        <button type="reset" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800 text-sm font-medium transition-all flex items-center gap-2 cursor-pointer">
                            <i data-lucide="refresh-cw" class="h-4 w-4"></i> Limpiar
                        </button>
                        <button type="submit" id="btn-registrar-postulante"
                            class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-medium text-sm shadow-lg shadow-blue-500/10 transition-all flex items-center gap-2 cursor-pointer">
                            <i data-lucide="save" class="h-4 w-4"></i> Guardar Postulante
                        </button>
                    </div>
                </form>
            </section>

            <!-- ╔══════════════════════════════════════════════════════════╗
                 ║  MOD: POST-BUSCAR                                          ║
                 ║  CU-09 Buscar · CU-10 Listar · CU-18 Por grupo            ║
                 ╚══════════════════════════════════════════════════════════╝ -->
            <section id="mod-post-buscar" class="app-module hidden space-y-5">

                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Postulantes — Buscar</h2>
                        <p class="text-sm text-slate-400">CU-09 Buscar · CU-10 Listar · CU-18 Ver por grupo</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-indigo-600/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <i data-lucide="search" class="h-5 w-5"></i>
                    </div>
                </div>

                <!-- Barra de controles: búsqueda + filtros + ordenación -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-4 flex flex-wrap gap-3 items-end">

                    <!-- Búsqueda por nombre / CI (CU-09) -->
                    <div class="flex-1 min-w-52 space-y-1">
                        <label class="text-[10px] uppercase font-bold text-slate-500 tracking-widest">Buscar</label>
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-500 pointer-events-none"></i>
                            <input type="text" id="pb-buscar" placeholder="Nombre o CI..."
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl pl-9 pr-4 py-2.5 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-all"
                                oninput="pbAplicarFiltros()">
                        </div>
                    </div>

                    <!-- Filtro por grupo (CU-18) -->
                    <div class="min-w-40 space-y-1">
                        <label class="text-[10px] uppercase font-bold text-slate-500 tracking-widest">Grupo</label>
                        <select id="pb-grupo" onchange="pbAplicarFiltros()"
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition-all">
                            <option value="">Todos los grupos</option>
                        </select>
                    </div>

                    <!-- Filtro por gestión -->
                    <div id="pb-wrap-gestion" class="min-w-40 space-y-1">
                        <label class="text-[10px] uppercase font-bold text-slate-500 tracking-widest">Gestión</label>
                        <select id="pb-gestion" onchange="pbAplicarFiltros()"
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition-all">
                            <option value="">Todas las gestiones</option>
                        </select>
                    </div>

                    <!-- Ordenación -->
                    <div class="min-w-44 space-y-1">
                        <label class="text-[10px] uppercase font-bold text-slate-500 tracking-widest">Ordenar por</label>
                        <select id="pb-orden" onchange="pbAplicarFiltros()"
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition-all">
                            <option value="nombre_asc">Nombre A → Z</option>
                            <option value="nombre_desc">Nombre Z → A</option>
                            <option value="ci_asc">CI Ascendente</option>
                            <option value="ci_desc">CI Descendente</option>
                        </select>
                    </div>

                    <!-- Botón actualizar -->
                    <button onclick="pbCargar()" title="Recargar desde la API"
                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl flex items-center gap-2 transition-all cursor-pointer shrink-0">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i> Actualizar
                    </button>

                    <!-- Chip contador -->
                    <div class="flex items-center gap-2 ml-auto shrink-0">
                        <span id="pb-contador" class="px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-400 font-medium">— resultados</span>
                    </div>
                </div>

                <!-- Tabla principal (CU-10 + CU-18) -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                <tr>
                                    <!-- Cabeceras ordenables -->
                                    <th class="px-5 py-3 cursor-pointer hover:text-white transition select-none" onclick="pbOrdenarPor('ci_asc','ci_desc')">
                                        <span class="flex items-center gap-1.5">CI <i data-lucide="chevrons-up-down" class="h-3 w-3"></i></span>
                                    </th>
                                    <th class="px-5 py-3 cursor-pointer hover:text-white transition select-none" onclick="pbOrdenarPor('nombre_asc','nombre_desc')">
                                        <span class="flex items-center gap-1.5">Nombre Completo <i data-lucide="chevrons-up-down" class="h-3 w-3"></i></span>
                                    </th>
                                    <th class="px-5 py-3">Correo</th>
                                    <th class="px-5 py-3">Teléfono</th>
                                    <th class="px-5 py-3">F. Nacimiento</th>
                                    <th class="px-5 py-3">Sexo</th>
                                    <th class="px-5 py-3">Ciudad</th>
                                    <th class="px-5 py-3">Colegio</th>
                                    <th class="px-5 py-3 text-indigo-400">1ra Opción</th>
                                    <th class="px-5 py-3 text-purple-400">2da Opción</th>
                                    <th class="px-5 py-3">Grupo</th>
                                    <th class="px-5 py-3">Gestión</th>
                                    <th class="px-5 py-3">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="pb-tabla-body" class="text-slate-300 divide-y divide-slate-700/30">
                                <tr>
                                    <td colspan="13" class="px-5 py-10 text-center">
                                        <div class="flex flex-col items-center gap-2 text-slate-500">
                                            <i data-lucide="loader" class="h-6 w-6 animate-spin"></i>
                                            <span class="text-xs">Cargando postulantes...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer de la tabla: paginación simple -->
                    <div id="pb-paginacion" class="hidden flex items-center justify-between px-5 py-3 border-t border-slate-700/50">
                        <span id="pb-pag-info" class="text-xs text-slate-500"></span>
                        <div class="flex gap-2">
                            <button id="pb-btn-prev" onclick="pbCambiarPagina(-1)"
                                class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-xs transition cursor-pointer disabled:opacity-40">
                                ← Anterior
                            </button>
                            <button id="pb-btn-next" onclick="pbCambiarPagina(1)"
                                class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-xs transition cursor-pointer disabled:opacity-40">
                                Siguiente →
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: REGISTRAR DOCENTE (CU-12)      ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-docentes" class="app-module hidden space-y-6 max-w-2xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Registrar Nuevo Docente</h2>
                        <p class="text-sm text-slate-400">Datos personales, contratación y profesión del docente.</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                        <i data-lucide="briefcase" class="h-5 w-5"></i>
                    </div>
                </div>
                <form id="form-docente" class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 space-y-5">
                    <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-wider pb-2 border-b border-slate-700/50">Datos Personales</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">Cédula de Identidad (CI) *</label>
                            <input type="text" id="doc_ci" required class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">Nombre Completo *</label>
                            <input type="text" id="doc_nombre" required class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">Teléfono</label>
                            <input type="text" id="doc_telefono" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">Correo Electrónico *</label>
                            <input type="email" id="doc_correo" required class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>

                    <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-wider pb-2 border-b border-slate-700/50 mt-2">Contratación</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">Fecha de Contrato</label>
                            <input type="date" id="doc_fch_contrato" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">Salario (Bs)</label>
                            <input type="number" id="doc_salario" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>

                    <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-wider pb-2 border-b border-slate-700/50 mt-2">Profesión</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">ID Profesión</label>
                            <input type="number" id="doc_id_profesion" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">Título Obtenido</label>
                            <input type="text" id="doc_titulo" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">Universidad</label>
                            <input type="text" id="doc_universidad" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs text-slate-400 uppercase font-bold">Fecha de Emisión</label>
                            <input type="date" id="doc_fch_emision" class="w-full bg-slate-950 border border-slate-700 rounded-xl p-2.5 text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="guardarDocente()"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all flex items-center gap-2 cursor-pointer text-sm">
                            <i data-lucide="save" class="h-4 w-4"></i> Registrar Docente
                        </button>
                    </div>
                </form>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: GRUPOS (CU-14/18/19/20)        ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-grupos" class="app-module hidden space-y-6 max-w-5xl mx-auto">
                <div class="flex justify-between items-center border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Estado de Asignación de Grupos</h2>
                        <p class="text-sm text-slate-400">Grupos generados automáticamente, aulas e infraestructura disponible.</p>
                    </div>
                    <button onclick="cargarGrupos()"
                        class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl text-sm transition-all cursor-pointer flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i> Actualizar
                    </button>
                </div>
                <div id="lista-grupos" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <p class="text-slate-500 text-sm col-span-3">Haz clic en "Actualizar" para cargar los grupos.</p>
                </div>
                <div class="border-t border-slate-700/50 pt-6">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Referencia de Infraestructura</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-800/50 p-5 rounded-xl border border-slate-700">
                            <h4 class="text-xs font-bold text-indigo-400 uppercase mb-4 tracking-wider">Aulas Disponibles</h4>
                            <div class="space-y-2" id="lista-aulas"><p class="text-slate-500 text-xs">Sin cargar.</p></div>
                        </div>
                        <div class="bg-slate-800/50 p-5 rounded-xl border border-slate-700">
                            <h4 class="text-xs font-bold text-indigo-400 uppercase mb-4 tracking-wider">Materias Vigentes</h4>
                            <div class="flex flex-wrap gap-2" id="lista-materias"><p class="text-slate-500 text-xs">Sin cargar.</p></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: BUSCAR POSTULANTE (CU-09)      ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-postulante-buscar" class="app-module hidden space-y-6 max-w-4xl mx-auto">
                <div class="border-b border-slate-700/50 pb-4">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Buscar Postulante</h2>
                    <p class="text-sm text-slate-400">CU-09 — Búsqueda individual por nombre, CI u otros criterios.</p>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center text-slate-500">
                    <i data-lucide="search" class="h-10 w-10 mx-auto mb-3 text-blue-500/50"></i>
                    <p class="font-medium text-slate-400">Módulo en desarrollo — Ciclo 2</p>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: POSTULANTES POR GRUPO (CU-18)  ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-postulante-por-grupo" class="app-module hidden space-y-6 max-w-5xl mx-auto">
                <div class="border-b border-slate-700/50 pb-4">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Estudiantes por Grupo</h2>
                    <p class="text-sm text-slate-400">CU-18 — Listar postulantes filtrados por grupo con campo de grupo adicional.</p>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center text-slate-500">
                    <i data-lucide="layers" class="h-10 w-10 mx-auto mb-3 text-indigo-500/50"></i>
                    <p class="font-medium text-slate-400">Módulo en desarrollo — Ciclo 2</p>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: MODIFICAR POSTULANTE (CU-07)   ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-postulante-modificar" class="app-module hidden space-y-6 max-w-4xl mx-auto">
                <div class="border-b border-slate-700/50 pb-4">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Modificar Datos del Postulante</h2>
                    <p class="text-sm text-slate-400">CU-07 — Edición de datos de un postulante ya registrado.</p>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center text-slate-500">
                    <i data-lucide="user-pen" class="h-10 w-10 mx-auto mb-3 text-amber-500/50"></i>
                    <p class="font-medium text-slate-400">Módulo en desarrollo — Ciclo 2</p>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: ELIMINAR POSTULANTE (CU-08)    ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-postulante-eliminar" class="app-module hidden space-y-6 max-w-4xl mx-auto">
                <div class="border-b border-slate-700/50 pb-4">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Eliminar Registro de Postulante</h2>
                    <p class="text-sm text-slate-400">CU-08 — Eliminación controlada de postulantes sin inscripciones activas.</p>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center text-slate-500">
                    <i data-lucide="trash-2" class="h-10 w-10 mx-auto mb-3 text-red-500/50"></i>
                    <p class="font-medium text-slate-400">Módulo en desarrollo — Ciclo 2</p>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: ASIGNAR POSTULANTE (CU-20)     ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-postulante-asignar" class="app-module hidden space-y-6 max-w-4xl mx-auto">
                <div class="border-b border-slate-700/50 pb-4">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Asignar Postulantes</h2>
                    <p class="text-sm text-slate-400">CU-20 — Asignación de postulantes a grupos del CUP.</p>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center text-slate-500">
                    <i data-lucide="user-check" class="h-10 w-10 mx-auto mb-3 text-emerald-500/50"></i>
                    <p class="font-medium text-slate-400">Módulo en desarrollo — Ciclo 2</p>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: ASIGNAR DOCENTE (CU-19)        ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-docente-asignar" class="app-module hidden space-y-6 max-w-4xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Asignar Docente a Grupo</h2>
                        <p class="text-sm text-slate-400">CU-19 — Busca por CI, confirma datos del docente y selecciona grupo + materia.</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-emerald-600/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <i data-lucide="user-round-check" class="h-5 w-5"></i>
                    </div>
                </div>

                <!-- PASO 1: Buscar docente por CI -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                        <span class="h-6 w-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                        <h3 class="text-sm font-bold text-white">Buscar Docente por CI</h3>
                    </div>
                    <div class="p-5">
                        <div class="flex gap-3">
                            <input type="text" id="da-ci-input"
                                placeholder="Ingresa la Cédula de Identidad del docente..."
                                class="flex-1 bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition-all"
                                onkeydown="if(event.key==='Enter') daBuscarDocente()">
                            <button onclick="daBuscarDocente()" id="da-btn-buscar"
                                class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-600 text-white text-sm font-medium rounded-xl flex items-center gap-2 transition-all cursor-pointer shrink-0">
                                <i data-lucide="search" class="h-4 w-4"></i> Buscar
                            </button>
                        </div>

                        <!-- Resultado: datos del docente -->
                        <div id="da-docente-card" class="hidden mt-4 p-4 bg-slate-900 rounded-xl border border-emerald-500/30 space-y-3">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-emerald-600/20 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                                        <i data-lucide="briefcase" class="h-5 w-5"></i>
                                    </div>
                                    <div>
                                        <p id="da-doc-nombre" class="text-white font-bold text-sm"></p>
                                        <p id="da-doc-ci" class="text-slate-400 text-xs font-mono"></p>
                                    </div>
                                </div>
                                <span id="da-doc-grupos-badge"
                                    class="px-2.5 py-1 rounded-lg text-[11px] font-bold border shrink-0"></span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 pt-1 border-t border-slate-700/50">
                                <div>
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Correo</p>
                                    <p id="da-doc-correo" class="text-slate-300 text-xs mt-0.5"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Teléfono</p>
                                    <p id="da-doc-telefono" class="text-slate-300 text-xs mt-0.5"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Profesión</p>
                                    <p id="da-doc-profesion" class="text-slate-300 text-xs mt-0.5"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Grupos asignados</p>
                                    <p id="da-doc-grupos-texto" class="text-slate-300 text-xs mt-0.5"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Estado: no encontrado -->
                        <div id="da-docente-error" class="hidden mt-4 p-4 bg-red-900/20 border border-red-500/30 rounded-xl flex items-center gap-3">
                            <i data-lucide="user-x" class="h-5 w-5 text-red-400 shrink-0"></i>
                            <p class="text-red-300 text-sm">No se encontró ningún docente con esa CI. Verifica el dato ingresado.</p>
                        </div>
                    </div>
                </div>

                <!-- PASO 2: Seleccionar grupo y materia -->
                <div id="da-paso2" class="hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                        <span class="h-6 w-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                        <h3 class="text-sm font-bold text-white">Seleccionar Materia y Grupo</h3>
                    </div>
                    <div class="p-5 space-y-5">

                        <!-- 1. Primero: materia (fija, siempre las 4) -->
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider">
                                Materia a impartir <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <select id="da-sel-materia" onchange="daOnCambiarMateria()"
                                    class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 appearance-none pr-8">
                                    <option value="" disabled selected>— Selecciona una materia —</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- 2. Luego: grupo (se filtra según materia + horario del docente) -->
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider">
                                Grupo disponible <span class="text-red-400">*</span>
                                <span class="normal-case text-slate-500 font-normal ml-1">(materia libre + sin conflicto de horario)</span>
                            </label>
                            <div class="relative">
                                <select id="da-sel-grupo" onchange="daOnCambiarGrupo()"
                                    class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 appearance-none pr-8">
                                    <option value="" disabled selected>— Primero selecciona una materia —</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- 3. Horario del grupo elegido (aparece solo cuando hay grupo seleccionado) -->
                        <div id="da-horario-cont" class="hidden space-y-2">
                            <p class="text-xs uppercase font-bold text-slate-400 tracking-wider">Horario del grupo seleccionado</p>
                            <div id="da-horario-body" class="bg-slate-900 rounded-xl border border-slate-700 overflow-hidden"></div>
                        </div>

                        <div class="flex justify-end pt-2 border-t border-slate-700/50">
                            <button onclick="daConfirmarAsignacion()" id="da-btn-confirmar"
                                class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl flex items-center gap-2 transition-all cursor-pointer">
                                <i data-lucide="check" class="h-4 w-4"></i> Confirmar Asignación
                            </button>
                        </div>
                    </div>
                </div>
            </section>


            <!-- ╔══════════════════════════════════════════════════════════╗
                 ║  MOD: NOTAS (CU-21 Registrar · CU-22 Editar)            ║
                 ╚══════════════════════════════════════════════════════════╝ -->
            <section id="mod-notas" class="app-module hidden space-y-6 max-w-5xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Modificar — Notas</h2>
                        <p class="text-sm text-slate-400">CU-21 Registrar · CU-22 Editar — Computación, Matemáticas, Inglés, Física</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                        <i data-lucide="clipboard-pen" class="h-5 w-5"></i>
                    </div>
                </div>

                <!-- PASO 1: Buscar postulante -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                        <span class="h-6 w-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                        <h3 class="text-sm font-bold text-white">Buscar Postulante</h3>
                    </div>
                    <div class="p-5">
                        <div class="flex gap-3">
                            <input type="text" id="notas-ci-input"
                                placeholder="Ingresa el nombre o CI del postulante..."
                                class="flex-1 bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all"
                                onkeydown="if(event.key==='Enter') notasBuscarPostulante()">
                            <button onclick="notasBuscarPostulante()" id="notas-btn-buscar"
                                class="px-5 py-2.5 bg-blue-700 hover:bg-blue-600 text-white text-sm font-medium rounded-xl flex items-center gap-2 transition-all cursor-pointer shrink-0">
                                <i data-lucide="search" class="h-4 w-4"></i> Buscar
                            </button>
                        </div>

                        <!-- Card del postulante encontrado -->
                        <div id="notas-postulante-card" class="hidden mt-4 p-4 bg-slate-900 rounded-xl border border-blue-500/30 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-blue-600/20 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                                    <i data-lucide="user" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <p id="notas-post-nombre" class="text-white font-bold text-sm"></p>
                                    <p id="notas-post-ci" class="text-slate-400 text-xs font-mono"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6 text-xs">
                                <div>
                                    <p class="text-slate-500 uppercase font-bold tracking-wider text-[10px]">Correo</p>
                                    <p id="notas-post-correo" class="text-slate-300 mt-0.5"></p>
                                </div>
                                <div>
                                    <p class="text-slate-500 uppercase font-bold tracking-wider text-[10px]">1ra Opción</p>
                                    <p id="notas-post-carrera1" class="text-indigo-300 mt-0.5"></p>
                                </div>
                                <div>
                                    <p class="text-slate-500 uppercase font-bold tracking-wider text-[10px]">2da Opción</p>
                                    <p id="notas-post-carrera2" class="text-purple-300 mt-0.5"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Error: no encontrado -->
                        <div id="notas-postulante-error" class="hidden mt-4 p-4 bg-red-900/20 border border-red-500/30 rounded-xl flex items-center gap-3">
                            <i data-lucide="user-x" class="h-5 w-5 text-red-400 shrink-0"></i>
                            <p class="text-red-300 text-sm">No se encontró ningún postulante con ese dato.</p>
                        </div>
                    </div>
                </div>

                <!-- PASO 2: Evaluaciones del postulante (3 exámenes × 4 materias) -->
                <div id="notas-paso2" class="hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <span class="h-6 w-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">2</span>
                            Evaluaciones — 3 exámenes por materia
                        </h3>
                        <button onclick="notasAgregarExamen()" id="notas-btn-agregar"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl flex items-center gap-2 transition-all cursor-pointer">
                            <i data-lucide="plus" class="h-3.5 w-3.5"></i> Nuevo Examen
                        </button>
                    </div>

                    <!-- Tabs: Examen 1, 2, 3 -->
                    <div id="notas-tabs" class="flex gap-2"></div>

                    <!-- Panel de notas del examen activo -->
                    <div id="notas-panel" class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Materia</th>
                                        <th class="px-4 py-3 text-center">Nota <span class="text-slate-600 normal-case">/100</span></th>
                                        <th class="px-4 py-3 text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="notas-tabla-body" class="divide-y divide-slate-700/30"></tbody>
                                <!-- Fila de promedio ponderado total -->
                                <tfoot>
                                    <tr id="notas-fila-promedio" class="hidden bg-slate-800/60 border-t-2 border-slate-600">
                                        <td class="px-6 py-3 font-bold text-white text-xs uppercase tracking-wider">Promedio del examen</td>
                                        <td id="notas-promedio-valor" class="px-4 py-3 text-center font-bold text-lg"></td>
                                        <td id="notas-promedio-estado" class="px-4 py-3 text-center"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-slate-700/50 flex items-center justify-between">
                            <p id="notas-fecha-examen" class="text-xs text-slate-500"></p>
                            <button onclick="notasGuardar()" id="notas-btn-guardar"
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl flex items-center gap-2 transition-all cursor-pointer">
                                <i data-lucide="save" class="h-4 w-4"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Estado vacío -->
                <div id="notas-vacio" class="hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center space-y-3">
                    <i data-lucide="file-plus" class="h-10 w-10 mx-auto text-slate-600"></i>
                    <p class="text-slate-400 font-medium text-sm">Este postulante aún no tiene exámenes registrados.</p>
                    <button onclick="notasAgregarExamen()"
                        class="mx-auto px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl flex items-center gap-2 transition-all cursor-pointer">
                        <i data-lucide="plus" class="h-4 w-4"></i> Registrar primer examen
                    </button>
                </div>
            </section>

            <!-- Alias para CU-22 (mismo panel) -->
            <section id="mod-notas-editar" class="app-module hidden"></section>


            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: EXAMENES / NOTAS               ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-examenes" class="app-module hidden space-y-6">
                <div class="border-b border-slate-700/50 pb-4">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Registro de Calificaciones Preuniversitarias</h2>
                    <p class="text-sm text-slate-400">Administración de notas de exámenes oficiales por materias.</p>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center text-slate-500">
                    <i data-lucide="construction" class="h-10 w-10 mx-auto mb-3 text-amber-500/50"></i>
                    <p class="font-medium text-slate-400">Módulo de calificaciones — Ciclo 2</p>
                    <p class="text-xs mt-1">Permitirá cargar notas de Computación, Matemáticas, Física e Inglés.</p>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: REPORTES                       ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-reportes" class="app-module hidden space-y-6">

                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Reportes Analíticos</h2>
                        <p class="text-sm text-slate-400">CU-28 al CU-34 — Aprobación: promedio por materia ≥ 60 en los 3 exámenes</p>
                    </div>
                    <button onclick="reportesCargar()" id="rep-btn-actualizar"
                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl flex items-center gap-2 transition-all cursor-pointer shrink-0">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i> Actualizar datos
                    </button>
                </div>

                <!-- Estado: cargando -->
                <div id="rep-cargando" class="hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center">
                    <i data-lucide="loader" class="h-8 w-8 mx-auto mb-3 text-indigo-400 animate-spin"></i>
                    <p class="text-slate-400 text-sm">Calculando reportes...</p>
                </div>

                <!-- ── CU-34: Indicadores (tarjetas rápidas) ─────────── -->
                <div id="rep-indicadores" class="hidden grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-5 text-center">
                        <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mb-2">Total Inscritos</p>
                        <p id="rep-ind-total" class="text-4xl font-black text-white">—</p>
                    </div>
                    <div class="bg-slate-800/40 border border-emerald-500/20 rounded-2xl p-5 text-center">
                        <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mb-2">Aprobados</p>
                        <p id="rep-ind-aprobados" class="text-4xl font-black text-emerald-400">—</p>
                        <p id="rep-ind-aprobados-pct" class="text-xs text-slate-500 mt-1"></p>
                    </div>
                    <div class="bg-slate-800/40 border border-red-500/20 rounded-2xl p-5 text-center">
                        <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mb-2">Reprobados</p>
                        <p id="rep-ind-reprobados" class="text-4xl font-black text-red-400">—</p>
                        <p id="rep-ind-reprobados-pct" class="text-xs text-slate-500 mt-1"></p>
                    </div>
                    <div class="bg-slate-800/40 border border-amber-500/20 rounded-2xl p-5 text-center">
                        <p class="text-[10px] uppercase font-bold text-slate-500 tracking-widest mb-2">Grupos Habilitados</p>
                        <p id="rep-ind-grupos" class="text-4xl font-black text-amber-400">—</p>
                    </div>
                </div>

                <!-- Tabs de reportes -->
                <div id="rep-tabs" class="hidden flex flex-wrap gap-2">
                    <button onclick="repMostrarSeccion('lista')"     data-rep="lista"     class="rep-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-800 text-slate-400 hover:text-white">📋 Lista General</button>
                    <button onclick="repMostrarSeccion('resultados')" data-rep="resultados" class="rep-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-800 text-slate-400 hover:text-white">✅ Resultados</button>
                    <button onclick="repMostrarSeccion('promedios')"  data-rep="promedios"  class="rep-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-800 text-slate-400 hover:text-white">📊 Promedios</button>
                    <button onclick="repMostrarSeccion('grupos')"     data-rep="grupos"     class="rep-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-800 text-slate-400 hover:text-white">🏫 Grupos</button>
                    <button onclick="repMostrarSeccion('materias')"   data-rep="materias"   class="rep-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-800 text-slate-400 hover:text-white">📚 Por Materia</button>
                    <button onclick="repMostrarSeccion('docentes')"   data-rep="docentes"   class="rep-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer bg-slate-800 text-slate-400 hover:text-white">👨‍🏫 Docentes</button>
                </div>

                <!-- ── CU-28: Lista general de postulantes ───────────── -->
                <div id="rep-sec-lista" class="rep-sec hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white">CU-28 — Lista General de Postulantes</h3>
                        <span id="rep-lista-count" class="text-xs text-slate-400"></span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">CI</th>
                                    <th class="px-4 py-3 text-left">Nombre</th>
                                    <th class="px-4 py-3">Ciudad</th>
                                    <th class="px-4 py-3">Colegio</th>
                                    <th class="px-4 py-3">1ra Opción</th>
                                    <th class="px-4 py-3">2da Opción</th>
                                    <th class="px-4 py-3 text-center">Resultado</th>
                                </tr>
                            </thead>
                            <tbody id="rep-lista-body" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ── CU-29: Reporte de resultados ──────────────────── -->
                <div id="rep-sec-resultados" class="rep-sec hidden space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Aprobados -->
                        <div class="bg-slate-800/40 border border-emerald-500/20 rounded-2xl overflow-hidden">
                            <div class="px-6 py-4 border-b border-emerald-500/20 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-emerald-400 flex items-center gap-2">
                                    <i data-lucide="check-circle" class="h-4 w-4"></i> Aprobados
                                </h3>
                                <span id="rep-aprobados-count" class="text-xs text-slate-400"></span>
                            </div>
                            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase sticky top-0">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Nombre</th>
                                            <th class="px-4 py-2 text-center">Promedio</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rep-aprobados-body" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Reprobados -->
                        <div class="bg-slate-800/40 border border-red-500/20 rounded-2xl overflow-hidden">
                            <div class="px-6 py-4 border-b border-red-500/20 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-red-400 flex items-center gap-2">
                                    <i data-lucide="x-circle" class="h-4 w-4"></i> Reprobados
                                </h3>
                                <span id="rep-reprobados-count" class="text-xs text-slate-400"></span>
                            </div>
                            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase sticky top-0">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Nombre</th>
                                            <th class="px-4 py-2 text-center">Promedio</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rep-reprobados-body" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── CU-30: Promedios generales ────────────────────── -->
                <div id="rep-sec-promedios" class="rep-sec hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50">
                        <h3 class="text-sm font-bold text-white">CU-30 — Promedios por Postulante</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Promedio = (E1 + E2 + E3) / 3 por materia · Aprobado si todas las materias ≥ 60</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Postulante</th>
                                    <th class="px-4 py-3 text-center">Computación</th>
                                    <th class="px-4 py-3 text-center">Matemáticas</th>
                                    <th class="px-4 py-3 text-center">Inglés</th>
                                    <th class="px-4 py-3 text-center">Física</th>
                                    <th class="px-4 py-3 text-center">Prom. General</th>
                                    <th class="px-4 py-3 text-center">Resultado</th>
                                </tr>
                            </thead>
                            <tbody id="rep-promedios-body" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ── CU-31: Grupos habilitados ─────────────────────── -->
                <div id="rep-sec-grupos" class="rep-sec hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50">
                        <h3 class="text-sm font-bold text-white">CU-31 — Grupos Habilitados</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Grupo</th>
                                    <th class="px-4 py-3 text-center">Estudiantes</th>
                                    <th class="px-4 py-3 text-center">Capacidad</th>
                                    <th class="px-4 py-3 text-center">Cupos libres</th>
                                    <th class="px-4 py-3">Horarios</th>
                                    <th class="px-4 py-3">Docentes asignados</th>
                                </tr>
                            </thead>
                            <tbody id="rep-grupos-body" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ── CU-32: Estadísticas por materia ───────────────── -->
                <div id="rep-sec-materias" class="rep-sec hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50">
                        <h3 class="text-sm font-bold text-white">CU-32 — Estadísticas por Materia</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Aprobado por materia = promedio ≥ 60</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Materia</th>
                                    <th class="px-4 py-3 text-center">Aprobados</th>
                                    <th class="px-4 py-3 text-center">Reprobados</th>
                                    <th class="px-4 py-3 text-center">Promedio general</th>
                                    <th class="px-4 py-3 text-center">Nota más alta</th>
                                    <th class="px-4 py-3 text-center">Nota más baja</th>
                                </tr>
                            </thead>
                            <tbody id="rep-materias-body" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ── CU-33: Docentes por grupo ─────────────────────── -->
                <div id="rep-sec-docentes" class="rep-sec hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50">
                        <h3 class="text-sm font-bold text-white">CU-33 — Docentes por Grupo</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Grupo</th>
                                    <th class="px-4 py-3">Horario</th>
                                    <th class="px-4 py-3">Computación</th>
                                    <th class="px-4 py-3">Matemáticas</th>
                                    <th class="px-4 py-3">Inglés</th>
                                    <th class="px-4 py-3">Física</th>
                                </tr>
                            </thead>
                            <tbody id="rep-docentes-body" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                        </table>
                    </div>
                </div>

            </section>


        </main>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         MODAL: EDITAR POSTULANTE
    ══════════════════════════════════════════════════════════ -->
    <!-- ══════════════════════════════════════════════════════════
         JAVASCRIPT — organizado por secciones
    ══════════════════════════════════════════════════════════ -->
    <script>
    'use strict';

    // ─────────────────────────────────────────────────────────
    // CONFIGURACIÓN GLOBAL
    // ─────────────────────────────────────────────────────────
    // Detecta automáticamente la URL del API según el entorno
    // - En local: usa localhost:8000
    // - En Railway/producción: usa la misma URL del frontend (mismo dominio)
    const API_BASE_URL = (() => {
        const host = window.location.hostname;
        if (host === 'localhost' || host === '127.0.0.1') {
            return 'http://localhost:8000';
        }
        // En producción el frontend y el API están en el mismo servidor Laravel
        return window.location.origin;
    })();

    const PAGE_TITLES = {
        dashboard:         'Escritorio de Control',
        usuarios:          'Control de Usuarios y Permisos',
        'post-edicion':    'Postulantes — Añadir / Modificar',
        'post-buscar':     'Postulantes — Buscar',
        docentes:          'Registrar Docente',
        'docente-asignar': 'Asignar Docente a Grupo',
        grupos:            'Asignación de Grupos',
        notas:             'Modificar — Notas',
        reportes:          'Reportes Analíticos',
    };

    const MODULE_INIT = {
        usuarios:          () => loadUsuarios(),
        'post-edicion':    () => { loadRequisitos(); loadTablaEdicion(); },
        'post-buscar':     () => loadPostulantes(),
        docentes:          () => loadProfesiones(),
        'docente-asignar': () => daInit(),
        reportes:          () => reportesCargar(),
        'notas-registrar': () => notasInit(),
        notas:             () => notasInit(),
        'notas-editar':    () => switchModule('notas'),
    };

    function getToken() {
        const t = localStorage.getItem('token');
        if (!t) window.location.href = '/login';
        return t;
    }

    function authHeaders() {
        return {
            'Authorization': `Bearer ${getToken()}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        };
    }

    // ─────────────────────────────────────────────────────────
    // SUBMENÚS Y SECCIONES COLAPSABLES
    // ─────────────────────────────────────────────────────────
    function toggleGroup(id) {
        const panel = document.getElementById(id);
        const icon  = document.getElementById('icon-' + id);
        if (!panel) return;
        const isOpen = !panel.classList.contains('hidden');
        panel.classList.toggle('hidden');
        if (icon) icon.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    function toggleSection(panelId, iconId) {
        const panel = document.getElementById(panelId);
        const icon  = document.getElementById(iconId);
        if (!panel) return;
        const isOpen = !panel.classList.contains('hidden');
        panel.classList.toggle('hidden');
        if (icon) icon.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    // ─────────────────────────────────────────────────────────
    // ROUTER DE MÓDULOS
    // ─────────────────────────────────────────────────────────
    function switchModule(moduleName) {
        document.querySelectorAll('.app-module').forEach(m => m.classList.add('hidden'));

        const target = document.getElementById(`mod-${moduleName}`);
        if (target) target.classList.remove('hidden');

        const title = document.getElementById('header-page-title');
        if (title) title.textContent = PAGE_TITLES[moduleName] ?? moduleName;

        // Estilos activos: aplica a botones normales y sub-items por igual
        document.querySelectorAll('.nav-btn').forEach(btn => {
            const active = btn.dataset.module === moduleName;
            if (btn.classList.contains('sub-item')) {
                btn.className = active
                    ? 'nav-btn sub-item w-full text-left px-2 py-1.5 text-xs rounded-md cursor-pointer bg-indigo-600/70 text-white font-semibold'
                    : 'nav-btn sub-item w-full text-left px-2 py-1.5 text-xs rounded-md text-slate-400 hover:text-white hover:bg-slate-700/30 transition-all cursor-pointer';
            } else {
                btn.className = active
                    ? 'nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-white bg-indigo-600/80'
                    : 'nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white';
            }
        });

        lucide.createIcons();

        if (MODULE_INIT[moduleName]) MODULE_INIT[moduleName]();
    }

    // ─────────────────────────────────────────────────────────
    // HELPERS UI
    // ─────────────────────────────────────────────────────────
    function handleLogout() {
        localStorage.removeItem('token');
        window.location.href = '/login';
    }

    function mostrarToast(msg, tipo = 'ok') {
        const t = document.createElement('div');
        t.className = `fixed bottom-6 right-6 z-[999] flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl text-sm font-medium transition-all
            ${tipo === 'ok' ? 'bg-emerald-700 text-white' : 'bg-red-700 text-white'}`;
        t.innerHTML = `<i data-lucide="${tipo === 'ok' ? 'check-circle' : 'x-circle'}" class="h-4 w-4"></i>${msg}`;
        document.body.appendChild(t);
        lucide.createIcons();
        setTimeout(() => t.remove(), 3500);
    }

    function fallbackStats() {
        ['stat-inscritos', 'stat-aprobados', 'stat-reprobados', 'stat-grupos']
            .forEach(id => { const el = document.getElementById(id); if (el) el.textContent = '0'; });
    }

    // ─────────────────────────────────────────────────────────
    // MÓDULO: DASHBOARD
    // ─────────────────────────────────────────────────────────
    async function loadDashboardStats() {
        try {
            const res = await fetch(`${API_BASE_URL}/api/v1/dashboard/metrics`, { headers: authHeaders() });
            if (res.status === 401) { localStorage.removeItem('token'); window.location.href = '/login'; return; }
            const result = await res.json();
            if (res.ok && result.success) {
                const m = result.data;
                document.getElementById('stat-inscritos').textContent  = m.inscritos  ?? 0;
                document.getElementById('stat-aprobados').textContent  = m.aprobados  ?? 0;
                document.getElementById('stat-reprobados').textContent = m.reprobados ?? 0;
                document.getElementById('stat-grupos').textContent     = m.grupos     ?? 0;
            } else {
                fallbackStats();
            }
        } catch (err) {
            console.error('loadDashboardStats:', err);
            fallbackStats();
        }
    }

    // ─────────────────────────────────────────────────────────
    // MÓDULO: USUARIOS
    // ─────────────────────────────────────────────────────────
    async function loadUsuarios() {
        const body = document.getElementById('tabla-usuarios-body');
        body.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-slate-500 text-sm">Cargando...</td></tr>';
        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/usuarios`, { headers: authHeaders() });
            const result = await res.json();
            if (!res.ok || !result.success) { body.innerHTML = ''; return; }

            const usuarios = Array.isArray(result.data) ? result.data
                           : (result.data.users || result.data.data || []);
            body.innerHTML = '';
            usuarios.forEach(u => {
                const rolNombre = u.rol?.txt_nombre ?? 'SIN ROL';
                const estadoHtml = u.bol_estado
                    ? '<span class="text-emerald-400 flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>Activo</span>'
                    : '<span class="text-slate-500">Inactivo</span>';
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-800/20 transition-colors';
                tr.innerHTML = `
                    <td class="p-4 text-slate-400">${u.id_usuario ?? '—'}</td>
                    <td class="p-4 font-semibold text-white">${u.txt_username ?? '—'}</td>
                    <td class="p-4 text-slate-300">${u.txt_email ?? '—'}</td>
                    <td class="p-4"><span class="px-2 py-0.5 rounded text-[11px] bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-bold">${rolNombre}</span></td>
                    <td class="p-4">${estadoHtml}</td>
                    <td class="p-4 text-center">
                        <button onclick="desactivarUsuario(${u.id_usuario})" class="text-xs text-red-400 hover:text-red-300 hover:underline cursor-pointer">Desactivar</button>
                    </td>`;
                body.appendChild(tr);
            });
        } catch (err) {
            console.error('loadUsuarios:', err);
        }
    }

    async function desactivarUsuario(id) {
        if (!confirm('¿Está seguro de desactivar este usuario?')) return;
        const res = await fetch(`${API_BASE_URL}/api/v1/usuarios/${id}/estado`, {
            method: 'PATCH', headers: authHeaders(),
        });
        if (res.ok) { alert('Usuario desactivado correctamente.'); loadUsuarios(); }
    }

    document.getElementById('form-registro-usuario').addEventListener('submit', async (e) => {
        e.preventDefault();
        const datos = {
            txt_username:              document.getElementById('usr_username').value,
            txt_email:                 document.getElementById('usr_email').value,
            id_rol:                    document.getElementById('usr_rol').value,
            txt_password:              document.getElementById('usr_password').value,
            txt_password_confirmation: document.getElementById('usr_password_confirmation').value,
        };
        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/usuarios`, { method: 'POST', headers: authHeaders(), body: JSON.stringify(datos) });
            const result = await res.json();
            if (res.ok && result.success) {
                alert('¡Usuario creado con éxito!');
                document.getElementById('form-registro-usuario').reset();
                loadUsuarios();
            } else if (res.status === 422) {
                const msgs = Object.values(result.errors ?? {}).flat().join('\n');
                alert('Error de validación:\n' + msgs);
            } else {
                alert('Error: ' + (result.message ?? 'Verifica los datos.'));
            }
        } catch (err) {
            alert('No se pudo conectar con el servidor.');
        }
    });

    // ─────────────────────────────────────────────────────────
    // MÓDULO: POST-EDICION — CU-06 Registrar · CU-07 Modificar · CU-08 Eliminar
    // ─────────────────────────────────────────────────────────

    // ── Cargar requisitos físicos desde la API (tbl_requisito) ──
    async function loadRequisitos() {
        const cont = document.getElementById('lista-requisitos');
        if (!cont) return;
        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/requisitos`, { headers: authHeaders() });
            const result = await res.json();
            const lista  = Array.isArray(result.data) ? result.data : (result.data?.data ?? []);
            if (!lista.length) {
                cont.innerHTML = '<p class="text-slate-500 text-xs col-span-2">No hay requisitos configurados.</p>';
                return;
            }
            cont.innerHTML = lista.map(r => `
                <label class="flex items-center gap-3 p-3 bg-slate-900 rounded-xl border border-slate-700 cursor-pointer hover:border-amber-500/40 transition-all">
                    <input type="checkbox" name="requisito" value="${r.id_requisito}"
                        class="h-4 w-4 rounded accent-amber-500 cursor-pointer">
                    <span class="text-xs text-slate-300">${r.txt_descripcion_requisito}</span>
                </label>`).join('');
        } catch (err) {
            cont.innerHTML = '<p class="text-red-400 text-xs col-span-2">Error al cargar requisitos.</p>';
        }
    }

    // ── CU-06: Guardar nuevo postulante ──
    document.getElementById('form-registro-postulante').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btn-registrar-postulante');
        const requisitosSeleccionados = [...document.querySelectorAll('input[name="requisito"]:checked')]
            .map(cb => parseInt(cb.value));

        const datos = {
            txt_ci:         document.getElementById('txt_ci').value.trim(),
            txt_nombre:     document.getElementById('txt_nombre').value.trim(),
            txt_correo:     document.getElementById('txt_correo').value.trim(),
            txt_telefono:   document.getElementById('txt_telefono').value.trim() || null,
            fch_nacimiento: document.getElementById('fch_nacimiento').value,
            chr_sexo:       document.getElementById('chr_sexo').value,
            txt_colegio:    document.getElementById('txt_colegio').value.trim() || null,
            txt_ciudad:     document.getElementById('txt_ciudad').value.trim()  || null,
            txt_direccion:  document.getElementById('txt_direccion').value.trim() || null,
            requisitos:     requisitosSeleccionados,
        };
        try {
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader" class="h-4 w-4 animate-spin"></i> Guardando...';
            lucide.createIcons();
            const res    = await fetch(`${API_BASE_URL}/api/v1/postulantes`, {
                method: 'POST', headers: authHeaders(), body: JSON.stringify(datos)
            });
            const result = await res.json();
            if (res.ok && result.success) {
                mostrarToast('Postulante registrado correctamente.', 'ok');
                e.target.reset();
                document.querySelectorAll('input[name="requisito"]').forEach(cb => cb.checked = false);
                loadTablaEdicion();
                loadDashboardStats();
            } else if (res.status === 422) {
                const msgs = Object.values(result.errors ?? {}).flat().join('\n');
                alert('Validación:\n' + msgs);
            } else {
                alert('Error: ' + (result.message ?? 'Verifica los datos.'));
            }
        } catch (err) {
            alert('No se pudo conectar con el servidor.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="save" class="h-4 w-4"></i> Guardar Postulante';
            lucide.createIcons();
        }
    });

    // ── Tabla CU-07/CU-08: carga lista base y enriquece con detalle individual ──
    // El API de listado solo devuelve: id_postulante, txt_nombre, txt_ci, carrera_1, carrera_2.
    // Para los demás campos (correo, teléfono, ciudad, etc.) se pide GET /postulantes/{id}.
    async function loadTablaEdicion(q = '') {
        const body = document.getElementById('tabla-post-edicion-body');
        if (!body) return;
        body.innerHTML = `<tr><td colspan="7" class="p-6 text-center text-slate-500 text-xs">
            <div class="flex items-center justify-center gap-2"><i data-lucide="loader" class="h-4 w-4 animate-spin"></i> Cargando...</div>
        </td></tr>`;
        lucide.createIcons();
        try {
            const url    = q
                ? `${API_BASE_URL}/api/v1/postulantes/buscar?q=${encodeURIComponent(q)}`
                : `${API_BASE_URL}/api/v1/postulantes`;
            const res    = await fetch(url, { headers: authHeaders() });
            const result = await res.json();
            let lista    = Array.isArray(result.data) ? result.data : (result.data?.data ?? []);

            // El listado solo trae id, nombre, ci, carrera_1, carrera_2.
            // Siempre enriquecer para obtener correo, teléfono, ciudad, etc.
            if (lista.length > 0) {
                lista = await pbEnriquecerLista(lista);
            }

            if (!lista.length) {
                body.innerHTML = `<tr><td colspan="7" class="p-6 text-center text-slate-500 text-xs">No se encontraron postulantes.</td></tr>`;
                return;
            }

            body.innerHTML = lista.map(p => {
                const c1 = p.carrera_1 ?? pbExtraerCarrera(p, 1);
                const c2 = p.carrera_2 ?? pbExtraerCarrera(p, 2);
                return `
                <tr class="hover:bg-slate-800/20 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-slate-300 whitespace-nowrap">${p.txt_ci ?? '—'}</td>
                    <td class="px-4 py-3 font-semibold text-white whitespace-nowrap">${p.txt_nombre ?? '—'}</td>
                    <td class="px-4 py-3 text-slate-400 text-xs">${p.txt_correo ?? '—'}</td>
                    <td class="px-4 py-3 text-slate-400 text-xs whitespace-nowrap">${p.txt_telefono ?? '—'}</td>
                    <td class="px-4 py-3 text-slate-400 text-xs whitespace-nowrap">${p.txt_ciudad ?? '—'}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 text-[11px] whitespace-nowrap">${c1}</span>
                        ${(c2 && c2 !== '—' && c2 !== '-') ? `<span class="ml-1 px-2 py-0.5 rounded-md bg-purple-500/10 text-purple-300 border border-purple-500/20 text-[11px] whitespace-nowrap">${c2}</span>` : ''}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="abrirModalEditarPost(${p.id_postulante})"
                                class="flex items-center gap-1 px-3 py-1.5 bg-amber-600/20 hover:bg-amber-600/40 text-amber-400 text-xs font-medium rounded-lg transition-all cursor-pointer">
                                <i data-lucide="pencil" class="h-3.5 w-3.5"></i> Editar
                            </button>
                            <button onclick="eliminarPostulante(${p.id_postulante}, '${(p.txt_nombre ?? '').replace(/'/g, "\\'")}')"
                                class="flex items-center gap-1 px-3 py-1.5 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-xs font-medium rounded-lg transition-all cursor-pointer">
                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i> Eliminar
                            </button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
            lucide.createIcons();
        } catch (err) {
            console.error('loadTablaEdicion:', err);
            body.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-400 text-xs">Error al cargar datos.</td></tr>`;
        }
    }

    function filtrarTablaEdicion() {
        loadTablaEdicion(document.getElementById('buscar-post-edicion').value);
    }

    // ── CU-07: Abrir modal de edición ──
    async function abrirModalEditarPost(id) {
        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/postulantes/${id}`, { headers: authHeaders() });
            const result = await res.json();
            const p      = result.data ?? result;
            document.getElementById('edit_id_post').value      = p.id_postulante;
            document.getElementById('edit_ci').value           = p.txt_ci         ?? '';
            document.getElementById('edit_nombre').value       = p.txt_nombre     ?? '';
            document.getElementById('edit_correo').value       = p.txt_correo     ?? '';
            document.getElementById('edit_telefono').value     = p.txt_telefono   ?? '';
            document.getElementById('edit_fch_nacimiento').value = p.fch_nacimiento ?? '';
            document.getElementById('edit_sexo').value         = p.chr_sexo       ?? 'M';
            document.getElementById('edit_colegio').value      = p.txt_colegio    ?? '';
            document.getElementById('edit_ciudad').value       = p.txt_ciudad     ?? '';
            document.getElementById('edit_direccion').value    = p.txt_direccion  ?? '';
            document.getElementById('modal-editar-post').classList.remove('hidden');
            lucide.createIcons();
        } catch (err) {
            alert('No se pudo cargar el postulante.');
        }
    }

    function cerrarModalPost() {
        document.getElementById('modal-editar-post').classList.add('hidden');
    }

    document.getElementById('form-editar-post').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id    = document.getElementById('edit_id_post').value;
        const datos = {
            txt_ci:         document.getElementById('edit_ci').value.trim(),
            txt_nombre:     document.getElementById('edit_nombre').value.trim(),
            txt_correo:     document.getElementById('edit_correo').value.trim(),
            txt_telefono:   document.getElementById('edit_telefono').value.trim() || null,
            fch_nacimiento: document.getElementById('edit_fch_nacimiento').value,
            chr_sexo:       document.getElementById('edit_sexo').value,
            txt_colegio:    document.getElementById('edit_colegio').value.trim()  || null,
            txt_ciudad:     document.getElementById('edit_ciudad').value.trim()   || null,
            txt_direccion:  document.getElementById('edit_direccion').value.trim()|| null,
        };
        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/postulantes/${id}`, {
                method: 'PUT', headers: authHeaders(), body: JSON.stringify(datos)
            });
            const result = await res.json();
            if (result.success) {
                mostrarToast('Postulante actualizado correctamente.', 'ok');
                cerrarModalPost();
                loadTablaEdicion();
            } else {
                alert('Error: ' + (result.message ?? 'No se pudo guardar.'));
            }
        } catch (err) {
            alert('No se pudo conectar con el servidor.');
        }
    });

    // ── CU-08: Eliminar postulante ──
    async function eliminarPostulante(id, nombre) {
        if (!confirm(`¿Eliminar el registro de "${nombre}"?\n\nEsta acción no se puede deshacer.`)) return;
        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/postulantes/${id}`, {
                method: 'DELETE', headers: authHeaders()
            });
            const result = await res.json();
            if (result.success) {
                mostrarToast(result.message ?? 'Postulante eliminado.', 'ok');
                loadTablaEdicion();
                loadDashboardStats();
            } else {
                alert('Error: ' + (result.message ?? 'No se pudo eliminar.'));
            }
        } catch (err) {
            alert('No se pudo conectar con el servidor.');
        }
    }

    // ─────────────────────────────────────────────────────────
    // MÓDULO: POST-BUSCAR — CU-09 Buscar · CU-10 Listar · CU-18 Por grupo
    // ─────────────────────────────────────────────────────────

    const PB = {
        datos:    [],
        filtrado: [],
        pagina:   1,
        porPagina: 20,
    };

    // ── Helper: extrae carrera por prioridad de todas las estructuras posibles que devuelva el API ──
    function pbExtraerCarrera(p, prioridad) {
        // Estructura 1: inscripciones → carreras con pivot prioridad
        const insc = p.inscripciones?.[0] ?? p.inscripcion ?? null;
        if (insc?.carreras?.length) {
            const c = insc.carreras.find(c => c.pivot?.int_prioridad === prioridad || c.int_prioridad === prioridad);
            if (c) return c.txt_nombre;
        }
        // Estructura 2: campos planos
        if (prioridad === 1) return p.carrera_1 ?? p.primera_opcion ?? p.txt_carrera1 ?? '—';
        if (prioridad === 2) return p.carrera_2 ?? p.segunda_opcion ?? p.txt_carrera2 ?? '—';
        return '—';
    }

    // ── Helper: enriquece la lista con detalle completo (cuando el API no incluye relaciones) ──
    // Para no saturar el servidor, procesa en lotes de 5 peticiones concurrentes.
    async function pbEnriquecerLista(lista) {
        const tamLote = 5;
        const resultado = [];
        for (let i = 0; i < lista.length; i += tamLote) {
            const lote = lista.slice(i, i + tamLote);
            const detallados = await Promise.all(
                lote.map(async p => {
                    try {
                        const r = await fetch(`${API_BASE_URL}/api/v1/postulantes/${p.id_postulante}`, { headers: authHeaders() });
                        const j = await r.json();
                        return j.data ?? j ?? p;
                    } catch {
                        return p; // Si falla, usa el dato parcial
                    }
                })
            );
            resultado.push(...detallados);
        }
        return resultado;
    }

    // ── Carga inicial: postulantes enriquecidos + selector de grupos ──
    // El listado solo trae: id, nombre, ci, carrera_1, carrera_2.
    // Se enriquece siempre con GET /postulantes/{id} para obtener todos los campos.
    async function pbCargar() {
        pbSetCargando(true);
        try {
            const h = authHeaders();

            // Gestiones no tiene endpoint propio aún — se carga solo grupos
            const [resP, resG] = await Promise.all([
                fetch(`${API_BASE_URL}/api/v1/postulantes`, { headers: h }),
                fetch(`${API_BASE_URL}/api/v1/grupos`,      { headers: h }),
            ]);
            const [rP, rG] = await Promise.all([resP.json(), resG.json()]);

            // Listado base (id, nombre, ci, carrera_1, carrera_2)
            let listaBase = Array.isArray(rP.data) ? rP.data : (rP.data?.data ?? []);

            // Enriquecer SIEMPRE con detalle individual (el listado no incluye los otros campos)
            let lista = listaBase.length > 0 ? await pbEnriquecerLista(listaBase) : [];

            // Preservar carrera_1 / carrera_2 del listado si el detalle no las trae
            lista = lista.map((p, i) => ({
                carrera_1: listaBase[i]?.carrera_1 ?? '—',
                carrera_2: listaBase[i]?.carrera_2 ?? '—',
                ...p,
            }));

            PB.datos = lista;

            // Selector de grupos
            const grupos = Array.isArray(rG.data) ? rG.data : (rG.data?.data ?? []);
            const selGrupo = document.getElementById('pb-grupo');
            if (selGrupo) {
                selGrupo.innerHTML = '<option value="">Todos los grupos</option>' +
                    grupos.map(g => `<option value="${g.id_grupo}">${g.txt_nombre}</option>`).join('');
            }

            // Ocultar filtro de gestión ya que el endpoint no existe aún
            const wrapGestion = document.getElementById('pb-wrap-gestion');
            if (wrapGestion) wrapGestion.classList.add('hidden');

            pbAplicarFiltros();
        } catch (err) {
            console.error('pbCargar:', err);
            document.getElementById('pb-tabla-body').innerHTML =
                `<tr><td colspan="13" class="px-5 py-8 text-center text-red-400 text-xs">
                    Error al cargar datos. Verifica la conexión con el servidor.</td></tr>`;
        } finally {
            pbSetCargando(false);
        }
    }

    // ── Aplica búsqueda + filtros + orden al dataset local ──
    function pbAplicarFiltros() {
        const q      = (document.getElementById('pb-buscar')?.value ?? '').toLowerCase().trim();
        const grupo  = document.getElementById('pb-grupo')?.value  ?? '';
        const orden  = document.getElementById('pb-orden')?.value  ?? 'nombre_asc';

        let lista = PB.datos.slice();

        // Búsqueda por nombre o CI (CU-09)
        if (q) {
            lista = lista.filter(p =>
                (p.txt_nombre ?? '').toLowerCase().includes(q) ||
                (p.txt_ci     ?? '').toLowerCase().includes(q)
            );
        }

        // Filtro por grupo (CU-18)
        if (grupo) {
            lista = lista.filter(p =>
                String(p.id_grupo ?? p.grupo?.id_grupo ?? '') === grupo
            );
        }

        // Ordenación
        lista.sort((a, b) => {
            switch (orden) {
                case 'nombre_asc':  return (a.txt_nombre ?? '').localeCompare(b.txt_nombre ?? '');
                case 'nombre_desc': return (b.txt_nombre ?? '').localeCompare(a.txt_nombre ?? '');
                case 'ci_asc':      return (a.txt_ci ?? '').localeCompare(b.txt_ci ?? '');
                case 'ci_desc':     return (b.txt_ci ?? '').localeCompare(a.txt_ci ?? '');
                default:            return 0;
            }
        });

        PB.filtrado = lista;
        PB.pagina   = 1;
        pbRenderTabla();
    }

    // ── Toggle de ordenación desde cabecera de columna ──
    function pbOrdenarPor(asc, desc) {
        const sel = document.getElementById('pb-orden');
        sel.value = sel.value === asc ? desc : asc;
        pbAplicarFiltros();
    }

    // ── Renderiza la página actual de la tabla ──
    function pbRenderTabla() {
        const body    = document.getElementById('pb-tabla-body');
        const total   = PB.filtrado.length;
        const inicio  = (PB.pagina - 1) * PB.porPagina;
        const pagina  = PB.filtrado.slice(inicio, inicio + PB.porPagina);

        document.getElementById('pb-contador').textContent =
            total === 0 ? 'Sin resultados' : `${total} postulante${total !== 1 ? 's' : ''}`;

        if (!pagina.length) {
            body.innerHTML = `<tr><td colspan="13" class="px-5 py-10 text-center text-slate-500 text-xs">
                No se encontraron postulantes con los filtros seleccionados.</td></tr>`;
            document.getElementById('pb-paginacion').classList.add('hidden');
            return;
        }

        const sexoLabel = { M: 'Masculino', F: 'Femenino', X: 'Otro' };

        const estadoClass = {
            APROBADO:   'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            REPROBADO:  'bg-red-500/10 text-red-400 border-red-500/20',
            PROCESADO:  'bg-blue-500/10 text-blue-400 border-blue-500/20',
            REGISTRADO: 'bg-slate-700/40 text-slate-400 border-slate-600/30',
        };

        body.innerHTML = pagina.map(p => {
            // carrera_1 y carrera_2 vienen del listado base (preservados en el merge de pbCargar)
            const c1 = p.carrera_1 && p.carrera_1 !== 'N/A' ? p.carrera_1 : (pbExtraerCarrera(p, 1));
            const c2 = p.carrera_2 && p.carrera_2 !== '-'   ? p.carrera_2 : (pbExtraerCarrera(p, 2));

            // Grupo desde el detalle enriquecido
            const grupoNombre = p.grupo?.txt_nombre ?? p.txt_grupo ?? '—';

            // Gestión desde el detalle enriquecido
            const g = p.gestion ?? p.inscripciones?.[0]?.gestion ?? null;
            const gestionLabel = g ? `${g.int_año} P${g.txt_periodo}` : '—';

            const insc   = p.inscripciones?.[0] ?? p.inscripcion ?? {};
            const estado = insc.txt_estado_inscripcion ?? p.txt_estado_inscripcion ?? 'REGISTRADO';
            const eClass = estadoClass[estado] ?? estadoClass.REGISTRADO;

            return `<tr class="hover:bg-slate-800/30 transition-colors">
                <td class="px-5 py-3 font-mono text-xs text-slate-300 whitespace-nowrap">${p.txt_ci ?? '—'}</td>
                <td class="px-5 py-3 font-semibold text-white whitespace-nowrap">${p.txt_nombre ?? '—'}</td>
                <td class="px-5 py-3 text-slate-400 text-xs">${p.txt_correo ?? '—'}</td>
                <td class="px-5 py-3 text-slate-400 text-xs whitespace-nowrap">${p.txt_telefono ?? '—'}</td>
                <td class="px-5 py-3 text-slate-400 text-xs whitespace-nowrap">${p.fch_nacimiento ?? '—'}</td>
                <td class="px-5 py-3 text-slate-400 text-xs">${sexoLabel[p.chr_sexo] ?? p.chr_sexo ?? '—'}</td>
                <td class="px-5 py-3 text-slate-400 text-xs">${p.txt_ciudad ?? '—'}</td>
                <td class="px-5 py-3 text-slate-400 text-xs max-w-[130px] truncate" title="${p.txt_colegio ?? ''}">${p.txt_colegio ?? '—'}</td>
                <td class="px-5 py-3 whitespace-nowrap">
                    <span class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 text-[11px] font-medium">${c1}</span>
                </td>
                <td class="px-5 py-3 whitespace-nowrap">
                    <span class="px-2 py-0.5 rounded-md bg-purple-500/10 text-purple-300 border border-purple-500/20 text-[11px] font-medium">${c2}</span>
                </td>
                <td class="px-5 py-3 text-slate-400 text-xs whitespace-nowrap">${grupoNombre}</td>
                <td class="px-5 py-3 text-slate-400 text-xs whitespace-nowrap">${gestionLabel}</td>
                <td class="px-5 py-3 whitespace-nowrap">
                    <span class="px-2 py-0.5 rounded-md border text-[10px] font-bold uppercase ${eClass}">${estado}</span>
                </td>
            </tr>`;
        }).join('');

        lucide.createIcons();

        // Paginación
        const totalPags = Math.ceil(total / PB.porPagina);
        const pag = document.getElementById('pb-paginacion');
        if (totalPags > 1) {
            pag.classList.remove('hidden');
            document.getElementById('pb-pag-info').textContent =
                `Página ${PB.pagina} de ${totalPags} · ${total} resultados`;
            document.getElementById('pb-btn-prev').disabled = PB.pagina <= 1;
            document.getElementById('pb-btn-next').disabled = PB.pagina >= totalPags;
        } else {
            pag.classList.add('hidden');
        }
    }

    function pbCambiarPagina(delta) {
        const totalPags = Math.ceil(PB.filtrado.length / PB.porPagina);
        PB.pagina = Math.max(1, Math.min(PB.pagina + delta, totalPags));
        pbRenderTabla();
    }

    // ── Spinner mientras carga ──
    function pbSetCargando(on) {
        if (on) {
            document.getElementById('pb-tabla-body').innerHTML =
                `<tr><td colspan="13" class="px-5 py-10 text-center">
                    <div class="flex flex-col items-center gap-2 text-slate-500">
                        <i data-lucide="loader" class="h-6 w-6 animate-spin"></i>
                        <span class="text-xs">Cargando postulantes...</span>
                    </div>
                </td></tr>`;
            lucide.createIcons();
        }
    }

    // Alias que necesita MODULE_INIT
    function loadPostulantes() { pbCargar(); }

    // ─────────────────────────────────────────────────────────
    // MÓDULO: REGISTRAR DOCENTE (CU-12)
    // ─────────────────────────────────────────────────────────
    async function guardarDocente() {
        const datos = {
            txt_ci:       document.getElementById('doc_ci').value,
            txt_nombre:   document.getElementById('doc_nombre').value,
            txt_telefono: document.getElementById('doc_telefono').value,
            txt_correo:   document.getElementById('doc_correo').value,
            contratacion: {
                fch_contrato: document.getElementById('doc_fch_contrato').value,
                num_salario:  document.getElementById('doc_salario').value,
            },
            profesiones: [{
                id_profesion:    document.getElementById('doc_id_profesion').value,
                txt_titulo:      document.getElementById('doc_titulo').value,
                txt_universidad: document.getElementById('doc_universidad').value,
                fch_emicion:     document.getElementById('doc_fch_emision').value, // typo intencional del API
            }],
        };
        try {
            const res  = await fetch(`${API_BASE_URL}/api/v1/docentes`, { method: 'POST', headers: authHeaders(), body: JSON.stringify(datos) });
            const text = await res.text();
            if (!res.ok) { console.error('ERROR DOCENTE:', text); alert(`Error ${res.status}: revisa la consola (F12).`); return; }
            alert('Docente registrado correctamente.');
            document.getElementById('form-docente').reset();
        } catch (err) {
            console.error('guardarDocente:', err);
        }
    }

    // ─────────────────────────────────────────────────────────
    // MÓDULO: ASIGNAR DOCENTE (CU-19)
    // ─────────────────────────────────────────────────────────
    const DA = { idDocente: null, nombreDocente: '', grupos: [], materias: [] };

    async function daInit() {
        DA.idDocente = null; DA.nombreDocente = ''; DA.grupos = []; DA.materias = [];
        document.getElementById('da-ci-input').value = '';
        document.getElementById('da-docente-card').classList.add('hidden');
        document.getElementById('da-docente-error').classList.add('hidden');
        document.getElementById('da-paso2').classList.add('hidden');
        document.getElementById('da-horario-cont').classList.add('hidden');
        try {
            const h = authHeaders();
            const [resG, resM] = await Promise.all([
                fetch(`${API_BASE_URL}/api/v1/grupos`,   { headers: h }),
                fetch(`${API_BASE_URL}/api/v1/materias`, { headers: h }),
            ]);
            const [rG, rM] = await Promise.all([resG.json(), resM.json()]);
            DA.grupos   = rG.data ?? [];
            DA.materias = Array.isArray(rM.data) ? rM.data : (rM.data?.data ?? []);
        } catch (err) { console.error('daInit:', err); }
    }

    async function daBuscarDocente() {
        const ci  = document.getElementById('da-ci-input').value.trim();
        const btn = document.getElementById('da-btn-buscar');
        if (!ci) { alert('Ingresa una Cédula de Identidad.'); return; }
        document.getElementById('da-docente-card').classList.add('hidden');
        document.getElementById('da-docente-error').classList.add('hidden');
        document.getElementById('da-paso2').classList.add('hidden');
        document.getElementById('da-horario-cont').classList.add('hidden');
        btn.disabled = true;
        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/docentes/buscar-ci?ci=${encodeURIComponent(ci)}`, { headers: authHeaders() });
            const result = await res.json();
            if (!res.ok || !result.success || !result.data) {
                document.getElementById('da-docente-error').classList.remove('hidden');
                DA.idDocente = null; return;
            }
            const d = result.data;
            DA.idDocente     = d.id_docente;
            DA.nombreDocente = d.txt_nombre ?? '';
            document.getElementById('da-doc-nombre').textContent    = d.txt_nombre   ?? '—';
            document.getElementById('da-doc-ci').textContent        = d.txt_ci       ?? '—';
            document.getElementById('da-doc-correo').textContent    = d.txt_correo   ?? '—';
            document.getElementById('da-doc-telefono').textContent  = d.txt_telefono ?? '—';
            document.getElementById('da-doc-profesion').textContent =
                d.profesiones?.map(p => p.txt_descripcion ?? p.txt_titulo).join(', ') ?? '—';
            const total = d.total_grupos_asignados ?? 0;
            const badge = document.getElementById('da-doc-grupos-badge');
            document.getElementById('da-doc-grupos-texto').textContent = `${total} de 4 (máx. FICCT)`;
            badge.textContent = total >= 4 ? '⚠ Carga máxima' : `${4 - total} cupo${4 - total !== 1 ? 's' : ''} libre${4 - total !== 1 ? 's' : ''}`;
            badge.className   = `px-2.5 py-1 rounded-lg text-[11px] font-bold border shrink-0 ${total >= 4 ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'}`;
            document.getElementById('da-docente-card').classList.remove('hidden');
            lucide.createIcons();
            if (total < 4) {
                document.getElementById('da-sel-materia').innerHTML =
                    '<option value="" disabled selected>— Selecciona una materia —</option>' +
                    DA.materias.map(m => `<option value="${m.id_materia}">${m.txt_nombre}</option>`).join('');
                document.getElementById('da-sel-grupo').innerHTML =
                    '<option value="" disabled selected>— Primero selecciona una materia —</option>';
                document.getElementById('da-paso2').classList.remove('hidden');
            }
        } catch (err) {
            console.error('daBuscarDocente:', err);
            document.getElementById('da-docente-error').classList.remove('hidden');
        } finally { btn.disabled = false; }
    }

    function daHorariosOcupados() {
        const ocupados = [];
        DA.grupos.forEach(g => {
            if ((g.docentes_asignados ?? []).some(a => a.docente === DA.nombreDocente)) {
                (g.horarios ?? []).forEach(h => ocupados.push({ dia: h.dia, inicio: h.inicio, final: h.final }));
            }
        });
        return ocupados;
    }

    function daOnCambiarMateria() {
        const idMateria     = parseInt(document.getElementById('da-sel-materia').value) || null;
        const materiaNombre = DA.materias.find(m => m.id_materia === idMateria)?.txt_nombre ?? null;
        const horariosOcup  = daHorariosOcupados();
        const selG          = document.getElementById('da-sel-grupo');
        document.getElementById('da-horario-cont').classList.add('hidden');
        selG.value = '';
        if (!materiaNombre) {
            selG.innerHTML = '<option value="" disabled selected>— Primero selecciona una materia —</option>';
            return;
        }
        const disponibles = DA.grupos.filter(g => {
            if ((g.docentes_asignados ?? []).some(a => a.materia === materiaNombre)) return false;
            if ((g.horarios ?? []).some(h => horariosOcup.some(o => o.dia === h.dia && o.inicio === h.inicio && o.final === h.final))) return false;
            return true;
        });
        selG.innerHTML = disponibles.length
            ? '<option value="" disabled selected>— Selecciona un grupo —</option>' +
              disponibles.map(g => {
                  const hrs = (g.horarios ?? []).map(h => `${h.dia} ${h.inicio.slice(0,5)}-${h.final.slice(0,5)}`).join(' / ') || 'Sin horario';
                  return `<option value="${g.id_grupo}">${g.txt_nombre} · ${hrs}</option>`;
              }).join('')
            : '<option value="" disabled selected>— Sin grupos disponibles —</option>';
    }

    function daOnCambiarGrupo() {
        const idGrupo = parseInt(document.getElementById('da-sel-grupo').value);
        if (!idGrupo) { document.getElementById('da-horario-cont').classList.add('hidden'); return; }
        const grupo    = DA.grupos.find(g => g.id_grupo === idGrupo);
        const horarios = grupo?.horarios ?? [];
        const body     = document.getElementById('da-horario-body');
        body.innerHTML = horarios.length
            ? horarios.map((h, i) => `
                <div class="flex items-center gap-4 px-4 py-3 ${i > 0 ? 'border-t border-slate-700/50' : ''}">
                    <span class="px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-[11px] font-bold w-24 text-center">${h.dia ?? '—'}</span>
                    <span class="text-white text-sm font-medium">${(h.inicio ?? '').slice(0,5)} → ${(h.final ?? '').slice(0,5)}</span>
                    <span class="px-2 py-0.5 rounded-md bg-slate-700 text-slate-300 text-[11px]">${h.turno ?? '—'}</span>
                </div>`).join('')
            : '<p class="px-4 py-3 text-slate-500 text-xs">Sin horarios asignados aún.</p>';
        document.getElementById('da-horario-cont').classList.remove('hidden');
    }

    async function daConfirmarAsignacion() {
        const idGrupo   = document.getElementById('da-sel-grupo').value;
        const idMateria = document.getElementById('da-sel-materia').value;
        if (!DA.idDocente) { alert('Primero busca un docente.'); return; }
        if (!idMateria)    { alert('Selecciona una materia.'); return; }
        if (!idGrupo)      { alert('Selecciona un grupo.'); return; }
        const grupo   = DA.grupos.find(g => g.id_grupo === parseInt(idGrupo));
        const materia = DA.materias.find(m => m.id_materia === parseInt(idMateria));
        if (!confirm(`¿Asignar a ${DA.nombreDocente}\npara "${materia?.txt_nombre}" en ${grupo?.txt_nombre}?`)) return;
        const btn = document.getElementById('da-btn-confirmar');
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" class="h-4 w-4 animate-spin"></i> Asignando...';
        lucide.createIcons();
        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/grupos/${idGrupo}/asignar-docente`, {
                method: 'POST', headers: authHeaders(),
                body: JSON.stringify({ id_docente: DA.idDocente, id_materia: parseInt(idMateria) }),
            });
            const result = await res.json();
            if (res.ok && result.success) {
                mostrarToast(`¡${DA.nombreDocente} asignado a ${grupo?.txt_nombre} — ${materia?.txt_nombre}!`, 'ok');
                await daInit();
            } else {
                alert(result.message ?? 'No se pudo completar la asignación.');
            }
        } catch (err) {
            alert('Error de conexión con el servidor.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i> Confirmar Asignación';
            lucide.createIcons();
        }
    }

    // ─────────────────────────────────────────────────────────
    // MÓDULO: NOTAS (CU-21 Registrar · CU-22 Editar)
    // Estado local del módulo
    // ─────────────────────────────────────────────────────────
    const NOTAS = {
        idPostulante: null,
        evaluaciones: [],   // [{id_evaluacion, int_nro_examen, fch_examen, detalles:[...]}]
        materias:     [],   // [{id_materia, txt_nombre}]
        examenActivo: null, // int_nro_examen activo en el tab
    };

    function notasInit() {
        NOTAS.idPostulante = null;
        NOTAS.evaluaciones = [];
        NOTAS.examenActivo = null;
        document.getElementById('notas-ci-input').value = '';
        document.getElementById('notas-postulante-card').classList.add('hidden');
        document.getElementById('notas-postulante-error').classList.add('hidden');
        document.getElementById('notas-paso2').classList.add('hidden');
        document.getElementById('notas-vacio').classList.add('hidden');
    }

    // PASO 1: buscar postulante por CI o nombre
    async function notasBuscarPostulante() {
        const q   = document.getElementById('notas-ci-input').value.trim();
        const btn = document.getElementById('notas-btn-buscar');
        if (!q) { alert('Ingresa un nombre o CI.'); return; }

        document.getElementById('notas-postulante-card').classList.add('hidden');
        document.getElementById('notas-postulante-error').classList.add('hidden');
        document.getElementById('notas-paso2').classList.add('hidden');
        document.getElementById('notas-vacio').classList.add('hidden');
        btn.disabled = true;

        try {
            const res    = await fetch(
                `${API_BASE_URL}/api/v1/postulantes/buscar?q=${encodeURIComponent(q)}`,
                { headers: authHeaders() }
            );
            const result = await res.json();
            const lista  = Array.isArray(result.data) ? result.data : (result.data?.data ?? []);

            if (!lista.length) {
                document.getElementById('notas-postulante-error').classList.remove('hidden');
                NOTAS.idPostulante = null;
                return;
            }

            // Tomar el primero (búsqueda exacta por CI devuelve solo uno)
            const p = lista[0];
            NOTAS.idPostulante = p.id_postulante;

            document.getElementById('notas-post-nombre').textContent   = p.txt_nombre  ?? '—';
            document.getElementById('notas-post-ci').textContent       = p.txt_ci      ?? '—';
            document.getElementById('notas-post-correo').textContent   = p.txt_correo  ?? '—';
            document.getElementById('notas-post-carrera1').textContent = p.carrera_1   ?? '—';
            document.getElementById('notas-post-carrera2').textContent = p.carrera_2   ?? '—';
            document.getElementById('notas-postulante-card').classList.remove('hidden');
            lucide.createIcons();

            // Cargar evaluaciones y materias en paralelo
            await notasCargarEvaluaciones();

        } catch (err) {
            console.error('notasBuscarPostulante:', err);
            document.getElementById('notas-postulante-error').classList.remove('hidden');
        } finally {
            btn.disabled = false;
        }
    }

    // Cargar evaluaciones del postulante + materias
    async function notasCargarEvaluaciones() {
        try {
            const h = authHeaders();
            const [resE, resM] = await Promise.all([
                fetch(`${API_BASE_URL}/api/v1/evaluaciones?id_postulante=${NOTAS.idPostulante}`, { headers: h }),
                fetch(`${API_BASE_URL}/api/v1/materias`, { headers: h }),
            ]);
            const [rE, rM] = await Promise.all([resE.json(), resM.json()]);

            NOTAS.materias     = Array.isArray(rM.data) ? rM.data : (rM.data?.data ?? []);
            NOTAS.evaluaciones = Array.isArray(rE.data) ? rE.data : (rE.data?.data ?? []);

            if (!NOTAS.evaluaciones.length) {
                document.getElementById('notas-vacio').classList.remove('hidden');
                document.getElementById('notas-paso2').classList.add('hidden');
                return;
            }

            document.getElementById('notas-vacio').classList.add('hidden');
            document.getElementById('notas-paso2').classList.remove('hidden');

            // Activar el primer examen
            NOTAS.examenActivo = NOTAS.evaluaciones[0].int_nro_examen;
            notasRenderTabs();
            notasRenderPanel();

        } catch (err) {
            console.error('notasCargarEvaluaciones:', err);
        }
    }

    // Renderiza los tabs Examen 1, 2, 3
    function notasRenderTabs() {
        const cont = document.getElementById('notas-tabs');
        cont.innerHTML = NOTAS.evaluaciones.map(ev => {
            const activo = ev.int_nro_examen === NOTAS.examenActivo;
            return `<button onclick="notasActivarTab(${ev.int_nro_examen})"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all cursor-pointer ${activo
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
                    : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700'}">
                Examen ${ev.int_nro_examen}
                <span class="ml-1.5 text-[10px] opacity-60">${ev.fch_examen ?? ''}</span>
            </button>`;
        }).join('');

        // Botón agregar solo si hay menos de 3 exámenes
        const btnAgregar = document.getElementById('notas-btn-agregar');
        if (btnAgregar) btnAgregar.classList.toggle('hidden', NOTAS.evaluaciones.length >= 3);
    }

    function notasActivarTab(nroExamen) {
        NOTAS.examenActivo = nroExamen;
        notasRenderTabs();
        notasRenderPanel();
    }

    // Renderiza la tabla de notas del examen activo
    function notasRenderPanel() {
        const ev = NOTAS.evaluaciones.find(e => e.int_nro_examen === NOTAS.examenActivo);
        if (!ev) return;

        const body  = document.getElementById('notas-tabla-body');
        const fecha = document.getElementById('notas-fecha-examen');
        fecha.textContent = ev.fch_examen ? `Fecha: ${ev.fch_examen}` : '';

        // Usar detalles de la evaluación directamente (ya traen txt_materia y num_nota)
        // Si no hay detalles aún, usar NOTAS.materias como plantilla vacía
        const filas = ev.detalles?.length
            ? ev.detalles
            : NOTAS.materias.map(m => ({
                id_materia: m.id_materia,
                txt_materia: m.txt_nombre,
                num_nota: '',
            }));

        body.innerHTML = filas.map(det => {
            const nota   = det.num_nota ?? '';
            const estado = nota !== ''
                ? parseFloat(nota) >= 51
                    ? '<span class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[11px] font-bold">APROBADO</span>'
                    : '<span class="px-2 py-0.5 rounded-md bg-red-500/10 text-red-400 border border-red-500/20 text-[11px] font-bold">REPROBADO</span>'
                : '<span class="text-slate-600 text-xs">—</span>';

            return `<tr class="hover:bg-slate-800/20 transition-colors">
                <td class="px-6 py-3 font-medium text-white">${det.txt_materia ?? '—'}</td>
                <td class="px-4 py-3 text-center">
                    <input type="number" min="0" max="100" step="0.01"
                        value="${nota}"
                        data-ev="${ev.id_evaluacion}" data-mat="${det.id_materia}"
                        class="nota-input w-24 bg-slate-950 border border-slate-700 rounded-lg px-2 py-1.5 text-white text-center text-sm focus:outline-none focus:border-blue-500 transition-all"
                        oninput="notasActualizarEstado(this)">
                </td>
                <td class="px-4 py-3 text-center" id="estado-${ev.id_evaluacion}-${det.id_materia}">${estado}</td>
            </tr>`;
        }).join('');

        notasRecalcularPromedio(ev);
    }

    // Actualiza el badge de estado al escribir una nota
    function notasActualizarEstado(input) {
        const evId  = input.dataset.ev;
        const matId = input.dataset.mat;
        const nota  = parseFloat(input.value);
        const cel   = document.getElementById(`estado-${evId}-${matId}`);
        if (!cel) return;
        if (isNaN(nota) || input.value === '') {
            cel.innerHTML = '<span class="text-slate-600 text-xs">—</span>';
        } else {
            cel.innerHTML = nota >= 51
                ? '<span class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[11px] font-bold">APROBADO</span>'
                : '<span class="px-2 py-0.5 rounded-md bg-red-500/10 text-red-400 border border-red-500/20 text-[11px] font-bold">REPROBADO</span>';
        }
        // Recalcular promedio del examen activo
        const ev = NOTAS.evaluaciones.find(e => e.int_nro_examen === NOTAS.examenActivo);
        if (ev) notasRecalcularPromedio(ev);
    }

    // Calcula y muestra el promedio del examen activo en el pie de tabla
    function notasRecalcularPromedio(ev) {
        const inputs = document.querySelectorAll('.nota-input');
        const notas  = [...inputs].map(i => parseFloat(i.value)).filter(v => !isNaN(v));
        const fila   = document.getElementById('notas-fila-promedio');
        const valEl  = document.getElementById('notas-promedio-valor');
        const estEl  = document.getElementById('notas-promedio-estado');
        if (!fila || !notas.length) { fila?.classList.add('hidden'); return; }
        const prom = notas.reduce((a, b) => a + b, 0) / notas.length;
        valEl.innerHTML = `<span class="${prom >= 51 ? 'text-emerald-400' : 'text-red-400'}">${prom.toFixed(2)}</span>`;
        estEl.innerHTML = prom >= 51
            ? '<span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold">APROBADO</span>'
            : '<span class="px-3 py-1 rounded-lg bg-red-500/10 text-red-400 border border-red-500/20 text-xs font-bold">REPROBADO</span>';
        fila.classList.remove('hidden');
    }

    // Registrar nuevo examen (CU-21)
    async function notasAgregarExamen() {
        if (!NOTAS.idPostulante) { alert('Primero busca un postulante.'); return; }
        const nroNuevo = NOTAS.evaluaciones.length + 1;
        if (nroNuevo > 3) { alert('Máximo 3 exámenes por postulante.'); return; }

        const fecha = prompt(`Fecha del Examen ${nroNuevo} (YYYY-MM-DD):`, new Date().toISOString().slice(0, 10));
        if (!fecha) return;

        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/evaluaciones`, {
                method: 'POST', headers: authHeaders(),
                body: JSON.stringify({
                    id_postulante: NOTAS.idPostulante,
                    int_nro_examen: nroNuevo,
                    fch_examen: fecha,
                }),
            });
            const result = await res.json();
            if (res.ok && result.success) {
                mostrarToast(`Examen ${nroNuevo} creado. Ingresa las notas y guarda.`, 'ok');
                await notasCargarEvaluaciones();
                notasActivarTab(nroNuevo);
            } else {
                alert(result.message ?? 'No se pudo crear el examen.');
            }
        } catch (err) {
            alert('Error de conexión.');
        }
    }

    // Guardar notas del examen activo (CU-21 / CU-22)
    async function notasGuardar() {
        const ev = NOTAS.evaluaciones.find(e => e.int_nro_examen === NOTAS.examenActivo);
        if (!ev) return;

        const btn = document.getElementById('notas-btn-guardar');
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" class="h-4 w-4 animate-spin"></i> Guardando...';
        lucide.createIcons();

        // Recopilar una nota por materia
        const detalles = [...document.querySelectorAll('.nota-input')].map(input => ({
            id_materia: parseInt(input.dataset.mat),
            num_nota:   parseFloat(input.value) || 0,
        }));

        try {
            const res    = await fetch(`${API_BASE_URL}/api/v1/evaluaciones/${ev.id_evaluacion}/detalles`, {
                method: 'PUT', headers: authHeaders(),
                body: JSON.stringify({ detalles }),
            });
            const result = await res.json();
            if (res.ok && result.success) {
                mostrarToast('Notas guardadas correctamente.', 'ok');
                await notasCargarEvaluaciones();
            } else {
                alert(result.message ?? 'Error al guardar.');
            }
        } catch (err) {
            alert('Error de conexión.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="save" class="h-4 w-4"></i> Guardar Cambios';
            lucide.createIcons();
        }
    }

    // ─────────────────────────────────────────────────────────
    // MÓDULO: REPORTES ANALÍTICOS (CU-28 al CU-34)
    // Umbral de aprobación: promedio por materia >= 60
    // Promedio = (Examen1 + Examen2 + Examen3) / 3
    // ─────────────────────────────────────────────────────────
    const UMBRAL = 60;
    const REP    = { postulantes: [], grupos: [], evaluaciones: {} };

    // Helpers de cálculo
    function repPromMateria(evals, idMateria) {
        // evals = array de {int_nro_examen, detalles:[{id_materia, num_nota}]}
        const notas = evals
            .map(ev => ev.detalles?.find(d => d.id_materia === idMateria)?.num_nota)
            .filter(n => n != null)
            .map(Number);
        if (!notas.length) return null;
        return notas.reduce((a, b) => a + b, 0) / notas.length;
    }

    function repEsAprobado(evals, materias) {
        // Aprobado = promedio >= 60 en TODAS las materias
        return materias.every(m => {
            const prom = repPromMateria(evals, m.id_materia);
            return prom != null && prom >= UMBRAL;
        });
    }

    function repBadge(aprobado) {
        return aprobado
            ? '<span class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">APROBADO</span>'
            : '<span class="px-2 py-0.5 rounded-md bg-red-500/10 text-red-400 border border-red-500/20 text-[10px] font-bold">REPROBADO</span>';
    }

    function repCelda(prom) {
        if (prom == null) return '<span class="text-slate-600">—</span>';
        const color = prom >= UMBRAL ? 'text-emerald-400' : 'text-red-400';
        return `<span class="font-bold ${color}">${prom.toFixed(2)}</span>`;
    }

    // Carga todos los datos necesarios en paralelo
    async function reportesCargar() {
        const btn = document.getElementById('rep-btn-actualizar');
        btn.disabled = true;
        document.getElementById('rep-cargando').classList.remove('hidden');
        document.getElementById('rep-indicadores').classList.add('hidden');
        document.getElementById('rep-tabs').classList.add('hidden');
        document.querySelectorAll('.rep-sec').forEach(s => s.classList.add('hidden'));

        try {
            const h = authHeaders();
            const [resP, resG, resM] = await Promise.all([
                fetch(`${API_BASE_URL}/api/v1/postulantes`, { headers: h }),
                fetch(`${API_BASE_URL}/api/v1/grupos`,      { headers: h }),
                fetch(`${API_BASE_URL}/api/v1/materias`,    { headers: h }),
            ]);
            const [rP, rG, rM] = await Promise.all([resP.json(), resG.json(), resM.json()]);

            REP.postulantes = Array.isArray(rP.data) ? rP.data : (rP.data?.data ?? []);
            REP.grupos      = rG.data ?? [];
            REP.materias    = Array.isArray(rM.data) ? rM.data : (rM.data?.data ?? []);

            // Cargar evaluaciones de cada postulante en lotes de 5
            REP.evaluaciones = {};
            const lote = 5;
            for (let i = 0; i < REP.postulantes.length; i += lote) {
                const batch = REP.postulantes.slice(i, i + lote);
                await Promise.all(batch.map(async p => {
                    try {
                        const r = await fetch(
                            `${API_BASE_URL}/api/v1/evaluaciones?id_postulante=${p.id_postulante}`,
                            { headers: h }
                        );
                        const j = await r.json();
                        REP.evaluaciones[p.id_postulante] = Array.isArray(j.data) ? j.data : (j.data?.data ?? []);
                    } catch { REP.evaluaciones[p.id_postulante] = []; }
                }));
            }

            reportesRenderizar();

        } catch (err) {
            console.error('reportesCargar:', err);
            mostrarToast('Error al cargar datos de reportes.', 'error');
        } finally {
            document.getElementById('rep-cargando').classList.add('hidden');
            btn.disabled = false;
        }
    }

    function reportesRenderizar() {
        const posts   = REP.postulantes;
        const grupos  = REP.grupos;
        const mats    = REP.materias;
        const evals   = REP.evaluaciones;

        // Calcular aprobación de cada postulante
        const resultados = posts.map(p => {
            const evs     = evals[p.id_postulante] ?? [];
            const aprobado = evs.length > 0 && repEsAprobado(evs, mats);
            const promsMateria = mats.map(m => ({
                id: m.id_materia, nombre: m.txt_nombre,
                prom: repPromMateria(evs, m.id_materia),
            }));
            const promGeneral = promsMateria.map(m => m.prom).filter(v => v != null);
            const promTotal   = promGeneral.length
                ? promGeneral.reduce((a, b) => a + b, 0) / promGeneral.length
                : null;
            return { ...p, aprobado, promsMateria, promTotal, evs };
        });

        const aprobados   = resultados.filter(r => r.aprobado);
        const reprobados  = resultados.filter(r => !r.aprobado);
        const totalConEval = resultados.filter(r => r.evs.length > 0).length;

        // ── CU-34: Indicadores ───────────────────────────────
        document.getElementById('rep-ind-total').textContent      = posts.length;
        document.getElementById('rep-ind-aprobados').textContent  = aprobados.length;
        document.getElementById('rep-ind-reprobados').textContent = reprobados.length;
        document.getElementById('rep-ind-grupos').textContent     = grupos.length;
        if (totalConEval > 0) {
            document.getElementById('rep-ind-aprobados-pct').textContent  = `${((aprobados.length / totalConEval) * 100).toFixed(1)}%`;
            document.getElementById('rep-ind-reprobados-pct').textContent = `${((reprobados.length / totalConEval) * 100).toFixed(1)}%`;
        }
        document.getElementById('rep-indicadores').classList.remove('hidden');

        // ── CU-28: Lista general ─────────────────────────────
        document.getElementById('rep-lista-count').textContent = `${posts.length} postulantes`;
        document.getElementById('rep-lista-body').innerHTML = resultados.map(r => `
            <tr class="hover:bg-slate-800/20">
                <td class="px-4 py-3 font-mono text-xs">${r.txt_ci ?? '—'}</td>
                <td class="px-4 py-3 font-medium text-white">${r.txt_nombre ?? '—'}</td>
                <td class="px-4 py-3 text-xs">${r.txt_ciudad ?? '—'}</td>
                <td class="px-4 py-3 text-xs max-w-[120px] truncate">${r.txt_colegio ?? '—'}</td>
                <td class="px-4 py-3 text-xs text-indigo-300">${r.carrera_1 ?? '—'}</td>
                <td class="px-4 py-3 text-xs text-purple-300">${r.carrera_2 ?? '—'}</td>
                <td class="px-4 py-3 text-center">${r.evs.length ? repBadge(r.aprobado) : '<span class="text-slate-600 text-xs">Sin eval.</span>'}</td>
            </tr>`).join('');

        // ── CU-29: Resultados ────────────────────────────────
        document.getElementById('rep-aprobados-count').textContent  = `${aprobados.length} estudiantes`;
        document.getElementById('rep-reprobados-count').textContent = `${reprobados.length} estudiantes`;
        document.getElementById('rep-aprobados-body').innerHTML = aprobados.map(r => `
            <tr class="hover:bg-slate-800/20">
                <td class="px-4 py-2 text-white text-xs">${r.txt_nombre}</td>
                <td class="px-4 py-2 text-center text-emerald-400 font-bold text-xs">${r.promTotal?.toFixed(2) ?? '—'}</td>
            </tr>`).join('') || '<tr><td colspan="2" class="px-4 py-4 text-center text-slate-500 text-xs">Sin aprobados</td></tr>';
        document.getElementById('rep-reprobados-body').innerHTML = reprobados.map(r => `
            <tr class="hover:bg-slate-800/20">
                <td class="px-4 py-2 text-white text-xs">${r.txt_nombre}</td>
                <td class="px-4 py-2 text-center text-red-400 font-bold text-xs">${r.promTotal?.toFixed(2) ?? '—'}</td>
            </tr>`).join('') || '<tr><td colspan="2" class="px-4 py-4 text-center text-slate-500 text-xs">Sin reprobados</td></tr>';

        // ── CU-30: Promedios generales ───────────────────────
        document.getElementById('rep-promedios-body').innerHTML = resultados.map(r => {
            const cols = mats.map(m => {
                const pm = r.promsMateria.find(p => p.id === m.id_materia);
                return `<td class="px-4 py-3 text-center">${repCelda(pm?.prom ?? null)}</td>`;
            }).join('');
            return `<tr class="hover:bg-slate-800/20">
                <td class="px-4 py-3 font-medium text-white text-xs">${r.txt_nombre}</td>
                ${cols}
                <td class="px-4 py-3 text-center font-bold">${repCelda(r.promTotal)}</td>
                <td class="px-4 py-3 text-center">${r.evs.length ? repBadge(r.aprobado) : '<span class="text-slate-600 text-xs">—</span>'}</td>
            </tr>`;
        }).join('');

        // ── CU-31: Grupos habilitados ────────────────────────
        document.getElementById('rep-grupos-body').innerHTML = grupos.map(g => {
            const horarios  = (g.horarios ?? []).map(h => `${h.dia} ${(h.inicio??'').slice(0,5)}-${(h.final??'').slice(0,5)}`).join('<br>') || '—';
            const docentes  = (g.docentes_asignados ?? []).map(d => `<span class="text-xs text-slate-300">${d.docente} <span class="text-slate-500">(${d.materia})</span></span>`).join('<br>') || '<span class="text-slate-500 text-xs">Sin asignar</span>';
            const cupos     = g.cupos_disponibles ?? (g.int_capacidad_maxma - g.int_cantidad_estudiantes);
            const cuposColor = cupos > 20 ? 'text-emerald-400' : cupos > 0 ? 'text-amber-400' : 'text-red-400';
            return `<tr class="hover:bg-slate-800/20">
                <td class="px-4 py-3 font-bold text-white">${g.txt_nombre}</td>
                <td class="px-4 py-3 text-center">${g.int_cantidad_estudiantes} / ${g.int_capacidad_maxma}</td>
                <td class="px-4 py-3 text-center">${g.int_capacidad_maxma}</td>
                <td class="px-4 py-3 text-center font-bold ${cuposColor}">${cupos}</td>
                <td class="px-4 py-3 text-xs leading-relaxed">${horarios}</td>
                <td class="px-4 py-3 text-xs leading-relaxed">${docentes}</td>
            </tr>`;
        }).join('');

        // ── CU-32: Estadísticas por materia ─────────────────
        document.getElementById('rep-materias-body').innerHTML = mats.map(m => {
            const notasTodas = resultados.flatMap(r => {
                const prom = repPromMateria(r.evs, m.id_materia);
                return prom != null ? [prom] : [];
            });
            const aprobM  = notasTodas.filter(n => n >= UMBRAL).length;
            const reprobM = notasTodas.filter(n => n < UMBRAL).length;
            const promG   = notasTodas.length ? notasTodas.reduce((a, b) => a + b, 0) / notasTodas.length : null;
            const max     = notasTodas.length ? Math.max(...notasTodas) : null;
            const min     = notasTodas.length ? Math.min(...notasTodas) : null;
            return `<tr class="hover:bg-slate-800/20">
                <td class="px-4 py-3 font-bold text-white">${m.txt_nombre}</td>
                <td class="px-4 py-3 text-center text-emerald-400 font-bold">${aprobM}</td>
                <td class="px-4 py-3 text-center text-red-400 font-bold">${reprobM}</td>
                <td class="px-4 py-3 text-center">${repCelda(promG)}</td>
                <td class="px-4 py-3 text-center text-blue-300 font-bold">${max?.toFixed(2) ?? '—'}</td>
                <td class="px-4 py-3 text-center text-amber-300 font-bold">${min?.toFixed(2) ?? '—'}</td>
            </tr>`;
        }).join('');

        // ── CU-33: Docentes por grupo ────────────────────────
        document.getElementById('rep-docentes-body').innerHTML = grupos.map(g => {
            const horario = (g.horarios ?? []).map(h => `${h.dia} ${(h.inicio??'').slice(0,5)}`).join(', ') || '—';
            const getDoc  = (matNombre) => {
                const a = (g.docentes_asignados ?? []).find(d => d.materia === matNombre);
                return a ? `<span class="text-xs text-white">${a.docente}</span>` : '<span class="text-slate-600 text-xs">—</span>';
            };
            return `<tr class="hover:bg-slate-800/20">
                <td class="px-4 py-3 font-bold text-white">${g.txt_nombre}</td>
                <td class="px-4 py-3 text-xs text-slate-400">${horario}</td>
                <td class="px-4 py-3">${getDoc('Computación')}</td>
                <td class="px-4 py-3">${getDoc('Matemáticas')}</td>
                <td class="px-4 py-3">${getDoc('Inglés')}</td>
                <td class="px-4 py-3">${getDoc('Física')}</td>
            </tr>`;
        }).join('');

        // Mostrar tabs y primera sección
        document.getElementById('rep-tabs').classList.remove('hidden');
        repMostrarSeccion('lista');
        lucide.createIcons();
    }

    function repMostrarSeccion(nombre) {
        document.querySelectorAll('.rep-sec').forEach(s => s.classList.add('hidden'));
        document.querySelectorAll('.rep-tab').forEach(t => {
            const activo = t.dataset.rep === nombre;
            t.className = `rep-tab px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer ${activo
                ? 'bg-indigo-600 text-white shadow-lg'
                : 'bg-slate-800 text-slate-400 hover:text-white'}`;
        });
        const sec = document.getElementById(`rep-sec-${nombre}`);
        if (sec) sec.classList.remove('hidden');
    }

    // ─────────────────────────────────────────────────────────
    // MÓDULO: GRUPOS (CU-14)
    // ─────────────────────────────────────────────────────────
    async function cargarGrupos() {
        const contGrupos   = document.getElementById('lista-grupos');
        const contAulas    = document.getElementById('lista-aulas');
        const contMaterias = document.getElementById('lista-materias');
        contGrupos.innerHTML = '<p class="text-slate-500 text-sm col-span-3">Cargando...</p>';

        try {
            const h = authHeaders();
            const [resG, resA, resM] = await Promise.all([
                fetch(`${API_BASE_URL}/api/v1/grupos`,   { headers: h }),
                fetch(`${API_BASE_URL}/api/v1/aulas`,    { headers: h }),
                fetch(`${API_BASE_URL}/api/v1/materias`, { headers: h }),
            ]);
            const [grupos, aulas, materias] = await Promise.all([resG.json(), resA.json(), resM.json()]);

            contGrupos.innerHTML = (grupos.success && grupos.data.length > 0)
                ? grupos.data.map(g => `
                    <div class="bg-slate-800 p-5 rounded-xl border border-slate-700 shadow-lg">
                        <h3 class="text-blue-400 font-bold text-lg">${g.txt_nombre}</h3>
                        <p class="text-sm text-slate-300 mt-2">Estudiantes: ${g.int_cantidad_estudiantes} / ${g.int_capacidad_maxma}</p>
                        <div class="mt-3 text-xs text-slate-500 bg-slate-900 p-2 rounded space-y-0.5">
                            ${(g.horarios ?? []).map(h => `<div>${h.dia}: ${h.inicio}–${h.final} (${h.turno})</div>`).join('')}
                        </div>
                    </div>`).join('')
                : '<p class="text-slate-500 text-sm">No hay grupos asignados.</p>';

            const listaA = Array.isArray(aulas) ? aulas : (aulas.data ?? []);
            contAulas.innerHTML = listaA.map(a =>
                `<div class="text-white text-sm py-1 border-b border-slate-700/50 last:border-0">${a.txt_nro_aula}</div>`
            ).join('') || '<p class="text-slate-500 text-xs">Sin aulas.</p>';

            const listaM = Array.isArray(materias) ? materias : (materias.data ?? []);
            contMaterias.innerHTML = listaM.map(m =>
                `<span class="bg-slate-900 px-2.5 py-1 rounded-lg text-xs text-white border border-slate-700">${m.txt_nombre}</span>`
            ).join('') || '<p class="text-slate-500 text-xs">Sin materias.</p>';

        } catch (err) {
            console.error('cargarGrupos:', err);
            document.getElementById('lista-grupos').innerHTML = '<p class="text-red-400 text-sm">Error al cargar. Revisa la consola.</p>';
        }
    }

    // ─────────────────────────────────────────────────────────
    // INIT
    // ─────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        // Registrar clicks del sidebar de forma declarativa
        document.querySelectorAll('.nav-btn[data-module]').forEach(btn => {
            btn.addEventListener('click', () => switchModule(btn.dataset.module));
        });

        lucide.createIcons();
        switchModule('dashboard');
        loadDashboardStats();
    });
    </script>

</body>
</html>