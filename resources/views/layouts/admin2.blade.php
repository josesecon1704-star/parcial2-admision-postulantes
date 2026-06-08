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
        <div>
            <div class="p-6 flex items-center gap-3 border-b border-slate-700/50">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                    <i data-lucide="graduation-cap" class="h-5 w-5"></i>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white tracking-wide">Admisión FICCT</h1>
                    <span class="text-[10px] uppercase font-bold text-blue-400 tracking-wider">PANEL ADMINISTRATIVO</span>
                </div>
            </div>

            <!-- ── Navegación por módulos ────────────────────── -->
            <nav id="sidebar-menu" class="p-3 space-y-4 overflow-y-auto">

                <!-- Grupo: Principal -->
                <div>
                    <p class="px-3 pt-1 pb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Principal</p>
                    <div class="space-y-0.5">
                        <button data-module="dashboard" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <i data-lucide="layout-dashboard" class="h-4 w-4 shrink-0"></i>
                            <span>Dashboard</span>
                        </button>
                    </div>
                </div>

                <!-- Grupo: Administración -->
                <div>
                    <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Administración</p>
                    <div class="space-y-0.5">
                        <button data-module="usuarios" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <i data-lucide="user-cog" class="h-4 w-4 shrink-0"></i>
                            <span>Control de Usuarios</span>
                        </button>
                        <button data-module="gestion" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <i data-lucide="calendar-days" class="h-4 w-4 shrink-0"></i>
                            <span>Gestión Académica</span>
                        </button>
                    </div>
                </div>

                <!-- Grupo: Personas -->
                <div>
                    <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Personas</p>
                    <div class="space-y-0.5">
                        <button data-module="postulante" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <i data-lucide="user-plus" class="h-4 w-4 shrink-0"></i>
                            <span>Registro Postulante</span>
                        </button>
                        <button data-module="postulantes" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <i data-lucide="users" class="h-4 w-4 shrink-0"></i>
                            <span>Listar Postulantes</span>
                        </button>
                        <button data-module="docentes" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <i data-lucide="briefcase" class="h-4 w-4 shrink-0"></i>
                            <span>Registrar Docente</span>
                        </button>
                    </div>
                </div>

                <!-- Grupo: Académico -->
                <div>
                    <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Académico</p>
                    <div class="space-y-0.5">
                        <button data-module="grupos" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <i data-lucide="layout-grid" class="h-4 w-4 shrink-0"></i>
                            <span>Asignación de Grupos</span>
                        </button>
                        <button data-module="examenes" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <i data-lucide="file-spreadsheet" class="h-4 w-4 shrink-0"></i>
                            <span>Exámenes / Notas</span>
                        </button>
                    </div>
                </div>

                <!-- Grupo: Reportes -->
                <div>
                    <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Reportes</p>
                    <div class="space-y-0.5">
                        <button data-module="reportes" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer">
                            <i data-lucide="pie-chart" class="h-4 w-4 shrink-0"></i>
                            <span>Reportes Analíticos</span>
                        </button>
                    </div>
                </div>

            </nav>
        </div>

        <!-- Pie del sidebar: info de usuario + cerrar sesión -->
        <div class="p-3 border-t border-slate-700/50 space-y-2">
            <div class="flex items-center gap-3 px-3 py-2">
                <div id="sidebar-avatar" class="h-8 w-8 rounded-lg bg-slate-700 flex items-center justify-center font-bold text-sm text-indigo-400 border border-slate-600 shrink-0">
                    A
                </div>
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
         ÁREA PRINCIPAL
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
                <div id="header-avatar" class="h-9 w-9 rounded-xl bg-slate-700 flex items-center justify-center font-bold text-sm text-indigo-400 border border-slate-600">
                    A
                </div>
            </div>
        </header>

        <!-- Contenido principal (módulos) -->
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
                        <div class="h-12 w-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                            <i data-lucide="users" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Inscritos</p>
                            <p id="stat-inscritos" class="text-3xl font-bold text-white mt-1">—</p>
                        </div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                        <div class="h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                            <i data-lucide="check-circle" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Aprobados</p>
                            <p id="stat-aprobados" class="text-3xl font-bold text-emerald-400 mt-1">—</p>
                        </div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                        <div class="h-12 w-12 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 shrink-0">
                            <i data-lucide="x-circle" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Reprobados</p>
                            <p id="stat-reprobados" class="text-3xl font-bold text-red-400 mt-1">—</p>
                        </div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                        <div class="h-12 w-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0">
                            <i data-lucide="layers" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Grupos Habilitados</p>
                            <p id="stat-grupos" class="text-3xl font-bold text-white mt-1">—</p>
                        </div>
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
                        <p class="text-sm text-slate-400">Crea, modifica y asigna roles a los operadores del sistema.</p>
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
                                <th class="p-4">ID</th>
                                <th class="p-4">Usuario</th>
                                <th class="p-4">Correo</th>
                                <th class="p-4">Rol Asignado</th>
                                <th class="p-4">Estado</th>
                                <th class="p-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-usuarios-body" class="text-sm text-slate-300 divide-y divide-slate-700/30">
                            <!-- Se llena dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: GESTIÓN ACADÉMICA              ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-gestion" class="app-module hidden space-y-6 max-w-3xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Configuración de Gestión Académica</h2>
                        <p class="text-sm text-slate-400">Define el año, periodo y rango activo para los cursos preuniversitarios (CUP).</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-amber-600/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                        <i data-lucide="calendar-days" class="h-5 w-5"></i>
                    </div>
                </div>

                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 space-y-4">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Gestión del CUP en Curso</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs text-slate-400">Año de la Gestión</label>
                            <input type="number" id="gst_anio" value="2026"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs text-slate-400">Periodo Académico</label>
                            <select id="gst_periodo"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none">
                                <option value="I">Primer Semestre (Periodo I)</option>
                                <option value="II" selected>Segundo Semestre (Periodo II)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button onclick="guardarGestionVigente()"
                            class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-medium text-sm shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 cursor-pointer">
                            <i data-lucide="check-square" class="h-4 w-4"></i> Establecer Gestión Activa
                        </button>
                    </div>
                </div>
            </section>

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
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                                <i data-lucide="text-cursor-input" class="h-3.5 w-3.5 text-slate-500"></i> C.I. <span class="text-red-400">*</span>
                            </label>
                            <input type="text" id="txt_ci" required placeholder="Ej: 8765432 SC"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                                <i data-lucide="user" class="h-3.5 w-3.5 text-slate-500"></i> Nombres y Apellidos <span class="text-red-400">*</span>
                            </label>
                            <input type="text" id="txt_nombre" required placeholder="Ej: Juan Pérez Mamani"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                                <i data-lucide="mail" class="h-3.5 w-3.5 text-slate-500"></i> Correo Electrónico <span class="text-red-400">*</span>
                            </label>
                            <input type="email" id="txt_correo" required placeholder="ejemplo@uagrm.edu.bo"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                                <i data-lucide="phone" class="h-3.5 w-3.5 text-slate-500"></i> Teléfono / Celular
                            </label>
                            <input type="text" id="txt_telefono" placeholder="Ej: 78945612"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                                <i data-lucide="calendar" class="h-3.5 w-3.5 text-slate-500"></i> Fecha de Nacimiento <span class="text-red-400">*</span>
                            </label>
                            <input type="date" id="fch_nacimiento" required
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                                <i data-lucide="text" class="h-3.5 w-3.5 text-slate-500"></i> Sexo / Género <span class="text-red-400">*</span>
                            </label>
                            <select id="chr_sexo" required
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                                <option value="" disabled selected>Selecciona una opción</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                                <i data-lucide="school" class="h-3.5 w-3.5 text-slate-500"></i> Colegio de Procedencia
                            </label>
                            <input type="text" id="txt_colegio" placeholder="Ej: Colegio Nacional Florida"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="h-3.5 w-3.5 text-slate-500"></i> Ciudad
                            </label>
                            <input type="text" id="txt_ciudad" placeholder="Ej: Santa Cruz de la Sierra"
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5">
                            <i data-lucide="home" class="h-3.5 w-3.5 text-slate-500"></i> Dirección Domiciliaria
                        </label>
                        <input type="text" id="txt_direccion" placeholder="Ej: Av. Bush, 2do Anillo, Calle 5 Nro 45"
                            class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                    </div>
                    <div class="flex items-center justify-end gap-4 border-t border-slate-700/50 pt-6">
                        <button type="reset"
                            class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800 text-sm font-medium transition-all flex items-center gap-2 cursor-pointer">
                            <i data-lucide="refresh-cw" class="h-4 w-4"></i> Limpiar
                        </button>
                        <button type="submit" id="btn-registrar-postulante"
                            class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-medium text-sm shadow-lg shadow-blue-500/10 transition-all flex items-center gap-2 cursor-pointer">
                            <i data-lucide="save" class="h-4 w-4"></i> Guardar Postulante
                        </button>
                    </div>
                </form>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: LISTAR POSTULANTES (CU-09/10)  ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-postulantes" class="app-module hidden space-y-6">
                <div class="flex justify-between items-center border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Gestión de Postulantes</h2>
                        <p class="text-sm text-slate-400">Listado, búsqueda y acciones sobre postulantes registrados.</p>
                    </div>
                    <input type="text" id="buscar-postulante" placeholder="Buscar por nombre o CI..."
                        class="bg-slate-800 border border-slate-700 rounded-xl p-2.5 text-sm text-white w-64 focus:outline-none focus:border-blue-500"
                        oninput="filtrarPostulantes()">
                </div>

                <div class="bg-slate-800/50 rounded-2xl overflow-hidden border border-slate-700/50">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-800 text-slate-400 uppercase text-xs">
                            <tr>
                                <th class="p-4">CI</th>
                                <th class="p-4">Nombre Completo</th>
                                <th class="p-4">1ra Opción</th>
                                <th class="p-4">2da Opción</th>
                                <th class="p-4">Estado</th>
                                <th class="p-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-postulantes-body" class="text-slate-300 divide-y divide-slate-700/30">
                            <!-- Se llena dinámicamente -->
                        </tbody>
                    </table>
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
                    <!-- Datos personales -->
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

                    <!-- Contratación -->
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

                    <!-- Profesión -->
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
                 ║  MOD: GRUPOS (CU-14, CU-18/19/20)   ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-grupos" class="app-module hidden space-y-6 max-w-5xl mx-auto">
                <div class="flex justify-between items-center border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Estado de Asignación de Grupos</h2>
                        <p class="text-sm text-slate-400">Grupos generados, aulas e infraestructura disponible.</p>
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
                            <div class="space-y-2" id="lista-aulas">
                                <p class="text-slate-500 text-xs">Sin cargar.</p>
                            </div>
                        </div>
                        <div class="bg-slate-800/50 p-5 rounded-xl border border-slate-700">
                            <h4 class="text-xs font-bold text-indigo-400 uppercase mb-4 tracking-wider">Materias Vigentes</h4>
                            <div class="flex flex-wrap gap-2" id="lista-materias">
                                <p class="text-slate-500 text-xs">Sin cargar.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: EXÁMENES / NOTAS               ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-examenes" class="app-module hidden space-y-6">
                <div class="border-b border-slate-700/50 pb-4">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Registro de Calificaciones Preuniversitarias</h2>
                    <p class="text-sm text-slate-400">Administración de notas de exámenes oficiales por materias.</p>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center text-slate-500 text-sm">
                    <i data-lucide="construction" class="h-10 w-10 mx-auto mb-3 text-amber-500/50"></i>
                    <p class="font-medium text-slate-400">Módulo de calificaciones — Ciclo 2</p>
                    <p class="text-xs mt-1">Permitirá cargar notas de Computación, Matemáticas, Física e Inglés.</p>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: REPORTES                       ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-reportes" class="app-module hidden space-y-6">
                <div class="border-b border-slate-700/50 pb-4">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Reportes Estadísticos y de Rendimiento</h2>
                    <p class="text-sm text-slate-400">Exportación de datos de aprobados, reprobados y distribución por cupos.</p>
                </div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-8 text-center text-slate-500 text-sm">
                    <i data-lucide="bar-chart-2" class="h-10 w-10 mx-auto mb-3 text-blue-500/50"></i>
                    <p class="font-medium text-slate-400">Módulo de reportes — Ciclo 2</p>
                    <p class="text-xs mt-1">Gráficos e indicadores avanzados exigidos por la facultad.</p>
                </div>
            </section>

        </main>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         MODAL: EDITAR POSTULANTE
    ══════════════════════════════════════════════════════════ -->
    <div id="modal-editar" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-slate-800 p-6 rounded-2xl w-full max-w-md shadow-2xl border border-slate-700">
            <h2 class="text-white text-lg font-bold mb-5">Editar Postulante</h2>
            <form id="form-editar" class="space-y-3">
                <input type="hidden" id="edit_id">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" id="edit_ci" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nombre</label>
                        <input type="text" id="edit_nombre" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Apellido</label>
                        <input type="text" id="edit_apellido" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Primera Opción</label>
                    <input type="text" id="edit_carrera1" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Segunda Opción</label>
                    <input type="text" id="edit_carrera2" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Teléfono</label>
                    <input type="text" id="edit_telefono" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-700">
                    <button type="button" onclick="cerrarModal()" class="px-4 py-2 text-slate-400 hover:text-white font-bold transition cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-xl font-bold transition shadow-lg cursor-pointer">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         JAVASCRIPT
    ══════════════════════════════════════════════════════════ -->
    <script>
    'use strict';

    // ─────────────────────────────────────────────────────────
    // CONFIGURACIÓN GLOBAL
    // ─────────────────────────────────────────────────────────
    const API_BASE_URL = 'http://localhost:8000';

    const PAGE_TITLES = {
        dashboard:   'Escritorio de Control',
        usuarios:    'Control de Usuarios y Permisos',
        gestion:     'Configuración de Gestión Vigente',
        postulante:  'Registro de Postulante',
        postulantes: 'Listar / Buscar Postulantes',
        docentes:    'Registrar Docente',
        examenes:    'Exámenes / Notas',
        grupos:      'Asignación de Grupos',
        reportes:    'Reportes Analíticos',
    };

    function getToken() {
        const token = localStorage.getItem('token');
        if (!token) window.location.href = '/login';
        return token;
    }

    function authHeaders() {
        return {
            'Authorization': `Bearer ${getToken()}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        };
    }

    // ─────────────────────────────────────────────────────────
    // ROUTER DE MÓDULOS — único punto de navegación
    // ─────────────────────────────────────────────────────────
    function switchModule(moduleName) {
        // Ocultar todos los módulos
        document.querySelectorAll('.app-module').forEach(mod => mod.classList.add('hidden'));

        // Mostrar el módulo solicitado
        const target = document.getElementById(`mod-${moduleName}`);
        if (target) target.classList.remove('hidden');

        // Actualizar título del header
        const title = document.getElementById('header-page-title');
        if (title) title.textContent = PAGE_TITLES[moduleName] ?? moduleName;

        // Actualizar estado activo en el sidebar
        document.querySelectorAll('.nav-btn').forEach(btn => {
            const isActive = btn.dataset.module === moduleName;
            btn.className = isActive
                ? 'nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-white bg-indigo-600/80'
                : 'nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white';
        });

        lucide.createIcons();

        // Callbacks por módulo al activarse
        const onActivate = {
            usuarios:    () => loadUsuarios(),
            postulantes: () => loadPostulantes(),
            grupos:      () => cargarGrupos(),
        };
        if (onActivate[moduleName]) onActivate[moduleName]();
    }

    // ─────────────────────────────────────────────────────────
    // HELPERS UI
    // ─────────────────────────────────────────────────────────
    function handleLogout() {
        localStorage.removeItem('token');
        window.location.href = '/login';
    }

    function cerrarModal() {
        document.getElementById('modal-editar').classList.add('hidden');
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
                const estado    = u.bol_estado ? '<span class="text-emerald-400 flex items-center gap-1.5 justify-center"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>Activo</span>'
                                               : '<span class="text-slate-500">Inactivo</span>';
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-800/20 transition-colors';
                tr.innerHTML = `
                    <td class="p-4 text-slate-400">${u.id_usuario ?? '—'}</td>
                    <td class="p-4 font-semibold text-white">${u.txt_username ?? '—'}</td>
                    <td class="p-4 text-slate-300">${u.txt_email ?? '—'}</td>
                    <td class="p-4"><span class="px-2 py-0.5 rounded text-[11px] bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-bold">${rolNombre}</span></td>
                    <td class="p-4">${estado}</td>
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
    // MÓDULO: GESTIÓN ACADÉMICA
    // ─────────────────────────────────────────────────────────
    async function guardarGestionVigente() {
        const anio    = document.getElementById('gst_anio').value;
        const periodo = document.getElementById('gst_periodo').value;
        // TODO: fetch POST ${API_BASE_URL}/api/v1/gestiones
        alert(`Gestión guardada: ${anio} - Periodo ${periodo}`);
    }

    // ─────────────────────────────────────────────────────────
    // MÓDULO: REGISTRO POSTULANTE
    // ─────────────────────────────────────────────────────────
    document.getElementById('form-registro-postulante').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btn-registrar-postulante');
        const datos = {
            txt_ci:         document.getElementById('txt_ci').value,
            txt_nombre:     document.getElementById('txt_nombre').value,
            txt_correo:     document.getElementById('txt_correo').value,
            txt_telefono:   document.getElementById('txt_telefono').value,
            fch_nacimiento: document.getElementById('fch_nacimiento').value,
            chr_sexo:       document.getElementById('chr_sexo').value,
            txt_colegio:    document.getElementById('txt_colegio').value,
            txt_ciudad:     document.getElementById('txt_ciudad').value,
            txt_direccion:  document.getElementById('txt_direccion').value,
        };
        try {
            btn.disabled = true;
            const res    = await fetch(`${API_BASE_URL}/api/v1/postulantes`, { method: 'POST', headers: authHeaders(), body: JSON.stringify(datos) });
            const result = await res.json();
            if (res.ok && result.success) {
                alert('¡Postulante guardado con éxito!');
                e.target.reset();
                loadDashboardStats();
            } else {
                alert('Error: ' + (result.message ?? 'Verifica los datos.'));
            }
        } catch (err) {
            alert('No se pudo conectar con el servidor.');
        } finally {
            btn.disabled = false;
        }
    });

    // ─────────────────────────────────────────────────────────
    // MÓDULO: LISTAR POSTULANTES (CU-09 / CU-10)
    // ─────────────────────────────────────────────────────────
    async function loadPostulantes(q = '') {
        const url = q
            ? `${API_BASE_URL}/api/v1/postulantes/buscar?q=${encodeURIComponent(q)}`
            : `${API_BASE_URL}/api/v1/postulantes`;
        try {
            const res    = await fetch(url, { headers: authHeaders() });
            const result = await res.json();
            if (!result.success) return;
            // El API puede devolver paginado (data.data) o directo (data)
            const lista = Array.isArray(result.data) ? result.data : (result.data?.data ?? []);
            renderTablaPostulantes(lista);
        } catch (err) {
            console.error('loadPostulantes:', err);
        }
    }

    function filtrarPostulantes() {
        loadPostulantes(document.getElementById('buscar-postulante').value);
    }

    function renderTablaPostulantes(data) {
        const body = document.getElementById('tabla-postulantes-body');
        if (!body) return;
        if (!data.length) {
            body.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-slate-500 text-sm">No se encontraron postulantes.</td></tr>';
            return;
        }
        body.innerHTML = data.map(p => {
            const c1     = p.carrera_1 ?? 'N/A';
            const c2     = p.carrera_2 ?? '—';
            const estado = p.txt_estado_inscripcion ?? 'REGISTRADO';
            return `
            <tr class="hover:bg-slate-800/20 transition-colors">
                <td class="p-4">${p.txt_ci}</td>
                <td class="p-4 font-medium text-white">${p.txt_nombre}</td>
                <td class="p-4 text-sm text-slate-300">${c1}</td>
                <td class="p-4 text-sm text-slate-300">${c2}</td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-bold">${estado}</span>
                </td>
                <td class="p-4 text-center space-x-2">
                    <button onclick="editarPostulante(${p.id_postulante})" class="text-indigo-400 hover:text-indigo-300 text-xs cursor-pointer">Editar</button>
                    <button onclick="eliminarPostulante(${p.id_postulante})" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">Eliminar</button>
                </td>
            </tr>`;
        }).join('');
    }

    async function eliminarPostulante(id) {
        if (!confirm('¿Está seguro de eliminar este postulante? Esta acción no se puede deshacer.')) return;
        const res    = await fetch(`${API_BASE_URL}/api/v1/postulantes/${id}`, { method: 'DELETE', headers: authHeaders() });
        const result = await res.json();
        alert(result.success ? result.message : 'Error: ' + result.message);
        if (result.success) loadPostulantes();
    }

    async function editarPostulante(id) {
        const res    = await fetch(`${API_BASE_URL}/api/v1/postulantes/${id}`, { headers: authHeaders() });
        const result = await res.json();
        const p      = result.data;
        document.getElementById('edit_id').value       = p.id_postulante;
        document.getElementById('edit_ci').value       = p.txt_ci       ?? '';
        document.getElementById('edit_nombre').value   = p.txt_nombre   ?? '';
        document.getElementById('edit_apellido').value = p.txt_apellido ?? '';
        document.getElementById('edit_carrera1').value = p.txt_carrera1 ?? '';
        document.getElementById('edit_carrera2').value = p.txt_carrera2 ?? '';
        document.getElementById('edit_telefono').value = p.txt_telefono ?? '';
        document.getElementById('modal-editar').classList.remove('hidden');
    }

    document.getElementById('form-editar').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id    = document.getElementById('edit_id').value;
        const datos = {
            txt_ci:       document.getElementById('edit_ci').value,
            txt_nombre:   document.getElementById('edit_nombre').value,
            txt_apellido: document.getElementById('edit_apellido').value,
            txt_carrera1: document.getElementById('edit_carrera1').value,
            txt_carrera2: document.getElementById('edit_carrera2').value,
            txt_telefono: document.getElementById('edit_telefono').value,
        };
        const res    = await fetch(`${API_BASE_URL}/api/v1/postulantes/${id}`, { method: 'PUT', headers: authHeaders(), body: JSON.stringify(datos) });
        const result = await res.json();
        if (result.success) {
            alert('Postulante actualizado correctamente.');
            cerrarModal();
            loadPostulantes();
        } else {
            alert('Error: ' + (result.message ?? 'No se pudo guardar.'));
        }
    });

    // ─────────────────────────────────────────────────────────
    // MÓDULO: REGISTRAR DOCENTE (CU-12)
    // ─────────────────────────────────────────────────────────
    async function guardarDocente() {
        const datos = {
            txt_ci:      document.getElementById('doc_ci').value,
            txt_nombre:  document.getElementById('doc_nombre').value,
            txt_telefono: document.getElementById('doc_telefono').value,
            txt_correo:  document.getElementById('doc_correo').value,
            contratacion: {
                fch_contrato: document.getElementById('doc_fch_contrato').value,
                num_salario:  document.getElementById('doc_salario').value,
            },
            profesiones: [{
                id_profesion:  document.getElementById('doc_id_profesion').value,
                txt_titulo:    document.getElementById('doc_titulo').value,
                txt_universidad: document.getElementById('doc_universidad').value,
                fch_emicion:   document.getElementById('doc_fch_emision').value,  // typo intencional del API
            }],
        };
        try {
            const res  = await fetch(`${API_BASE_URL}/api/v1/docentes`, { method: 'POST', headers: authHeaders(), body: JSON.stringify(datos) });
            const text = await res.text();
            if (!res.ok) { console.error('ERROR DOCENTE:', text); alert(`Error ${res.status}: revisa la consola.`); return; }
            alert('Docente registrado correctamente.');
            document.getElementById('form-docente').reset();
        } catch (err) {
            console.error('guardarDocente:', err);
        }
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

            // Grupos
            if (grupos.success && grupos.data.length > 0) {
                contGrupos.innerHTML = grupos.data.map(g => `
                    <div class="bg-slate-800 p-5 rounded-xl border border-slate-700 shadow-lg">
                        <h3 class="text-blue-400 font-bold text-lg">${g.txt_nombre}</h3>
                        <p class="text-sm text-slate-300 mt-2">Estudiantes: ${g.int_cantidad_estudiantes} / ${g.int_capacidad_maxma}</p>
                        <div class="mt-3 text-xs text-slate-500 bg-slate-900 p-2 rounded space-y-0.5">
                            ${(g.horarios ?? []).map(h => `<div>${h.dia}: ${h.inicio}–${h.final} (${h.turno})</div>`).join('')}
                        </div>
                    </div>`).join('');
            } else {
                contGrupos.innerHTML = '<p class="text-slate-500 text-sm">No hay grupos asignados.</p>';
            }

            // Aulas
            const listaA = Array.isArray(aulas) ? aulas : (aulas.data ?? []);
            contAulas.innerHTML = listaA.map(a =>
                `<div class="text-white text-sm py-1 border-b border-slate-700/50 last:border-0">${a.txt_nro_aula}</div>`
            ).join('') || '<p class="text-slate-500 text-xs">Sin aulas.</p>';

            // Materias
            const listaM = Array.isArray(materias) ? materias : (materias.data ?? []);
            contMaterias.innerHTML = listaM.map(m =>
                `<span class="bg-slate-900 px-2.5 py-1 rounded-lg text-xs text-white border border-slate-700">${m.txt_nombre}</span>`
            ).join('') || '<p class="text-slate-500 text-xs">Sin materias.</p>';

        } catch (err) {
            console.error('cargarGrupos:', err);
            contGrupos.innerHTML = '<p class="text-red-400 text-sm">Error al cargar datos. Revisa la consola.</p>';
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
