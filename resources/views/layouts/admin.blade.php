<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Administrative - Admisión FICCT</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-900 text-slate-100 font-sans antialiased flex h-screen w-screen overflow-hidden">

    <aside class="w-64 bg-slate-800/60 backdrop-blur-md border-r border-slate-700/50 flex flex-col justify-between h-full shrink-0">
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
            
            <nav id="sidebar-menu" class="p-4 space-y-1.5">
                <button onclick="switchModule('dashboard')" id="btn-dashboard" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all text-white bg-slate-700/50 cursor-pointer">
                    <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                    <span>Dashboard</span>
                </button>

                <button onclick="switchModule('usuarios')" id="btn-usuarios" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all text-slate-300 hover:bg-slate-700/30 hover:text-white cursor-pointer">
                    <i data-lucide="user-cog" class="h-4 w-4"></i>
                    <span>Control de Usuarios</span>
                </button>

                <button onclick="switchModule('gestion')" id="btn-gestion" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all text-slate-300 hover:bg-slate-700/30 hover:text-white cursor-pointer">
                    <i data-lucide="calendar-days" class="h-4 w-4"></i>
                    <span>Gestión Académica</span>
                </button>

                <button onclick="switchModule('postulante')" id="btn-postulante" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all text-slate-300 hover:bg-slate-700/30 hover:text-white cursor-pointer">
                    <i data-lucide="user-plus" class="h-4 w-4"></i>
                    <span>Registro de Postulantes</span>
                </button>

                <div class="space-y-1">
                    <button onclick="toggleSubMenu('sub-postulante')" class="flex items-center gap-3 w-full p-3 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <i data-lucide="users" class="h-5 w-5 text-blue-400"></i>
                        <span class="text-sm font-medium">Gestión de Personas</span>
                    </button>
                    
                    <div id="sub-postulante" class="ml-8 space-y-1 hidden">
                        <a href="#" onclick="showModule('mod-postulantes')" class="block p-2 text-xs text-slate-400 hover:text-white">Listar / Buscar (CU-09, 10) </a>
                        <a href="#" onclick="showModule('mod-docentes')" class="block p-2 text-xs text-slate-400 hover:text-white">Registrar Docente (CU-12)</a>
                    </div>
                </div>

                <button onclick="switchModule('examenes')" id="btn-examenes" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all text-slate-300 hover:bg-slate-700/30 hover:text-white cursor-pointer">
                    <i data-lucide="file-spreadsheet" class="h-4 w-4"></i>
                    <span>Exámenes / Notas</span>
                </button>

                <button onclick="switchModule('grupos')" id="btn-grupos" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all text-slate-300 hover:bg-slate-700/30 hover:text-white cursor-pointer">
                    <i data-lucide="users" class="h-4 w-4"></i>
                    <span>Asignación de Grupos</span>
                </button>

                <button onclick="switchModule('reportes')" id="btn-reportes" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all text-slate-300 hover:bg-slate-700/30 hover:text-white cursor-pointer">
                    <i data-lucide="pie-chart" class="h-4 w-4"></i>
                    <span>Reportes Analíticos</span>
                </button>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-700/50">
            <button onclick="handleLogout()" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-red-400 hover:bg-red-500/10 transition-all cursor-pointer">
                <i data-lucide="log-out" class="h-4 w-4"></i>
                <span>Cerrar Sesión</span>
            </button>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-slate-800/40 backdrop-blur-md border-b border-slate-700/50 flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-2 text-sm text-slate-400">
                <span>Panel Administrativo</span>
                <i data-lucide="chevron-right" class="h-3 w-3"></i>
                <span id="current-page-title" class="text-white font-medium">Escritorio de Control</span>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-xs font-bold text-white">Administrador</p>
                    <p class="text-[10px] text-slate-400">admin@uagrm.edu.bo</p>
                </div>
                <div class="h-9 w-9 rounded-xl bg-slate-700 flex items-center justify-center font-bold text-sm text-indigo-400 border border-slate-600">
                    A
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            <div id="mod-docentes" class="app-module hidden p-6">
    <h2 class="text-xl font-bold text-white mb-6">Registrar Nuevo Docente</h2>
    
    <form id="form-docente" class="bg-slate-800 p-6 rounded-xl border border-slate-700/50 max-w-lg">
        <div class="space-y-4">
            <div>
                <label class="text-xs text-slate-400 uppercase">Cédula de Identidad (CI)</label>
                <input type="text" id="doc_ci" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
            </div>
            <div>
                <label class="text-xs text-slate-400 uppercase">Nombre Completo</label>
                <input type="text" id="doc_nombre" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
            </div>
            <div>
                <label class="text-xs text-slate-400 uppercase">Teléfono</label>
                <input type="text" id="doc_telefono" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
            </div>
            <div>
                <label class="text-xs text-slate-400 uppercase">Correo Electrónico</label>
                <input type="email" id="doc_correo" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
            </div>
            <h3 class="text-indigo-400 font-bold border-b border-slate-700 pb-2 mt-4">Contratación</h3>
        <div>
            <label class="text-xs text-slate-400 uppercase">Fecha de Contrato</label>
            <input type="date" id="doc_fch_contrato" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
        </div>
        <div>
            <label class="text-xs text-slate-400 uppercase">Salario (Bs)</label>
            <input type="number" id="doc_salario" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
        </div>

        <h3 class="text-indigo-400 font-bold border-b border-slate-700 pb-2 mt-4">Profesión</h3>
        <div>
            <label class="text-xs text-slate-400 uppercase">ID Profesión</label>
            <input type="number" id="doc_id_profesion" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
        </div>
        <div>
            <label class="text-xs text-slate-400 uppercase">Título obtenido</label>
            <input type="text" id="doc_titulo" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
        </div>
        <div>
            <label class="text-xs text-slate-400 uppercase">Universidad</label>
            <input type="text" id="doc_universidad" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
        </div>
        <div>
            <label class="text-xs text-slate-400 uppercase">Fecha de Emisión</label>
            <input type="date" id="doc_fch_emision" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2 text-white">
        </div>
            
            <button type="button" onclick="guardarDocente()" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg">
                Registrar Docente
            </button>
        </div>
    </form>
</div>

            <div id="mod-postulantes" class="app-module hidden p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-white">Gestión de Postulantes</h2>
                    <input type="text" id="buscar-postulante" placeholder="Buscar por nombre o CI..." 
                        class="bg-slate-800 border border-slate-700 rounded-lg p-2 text-sm text-white w-64"
                        onkeyup="filtrarPostulantes()">
                </div>
                

                <div class="bg-slate-800/50 rounded-xl overflow-hidden border border-slate-700/50">
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
                        <tbody id="tabla-postulantes-body" class="text-slate-300">
                            </tbody>
                    </table>
                </div>
            </div>
            <div id="mod-dashboard" class="space-y-6 app-module">
                <div class="bg-slate-800/40 backdrop-blur-md border border-slate-700/50 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                    <h2 class="text-2xl font-bold text-white tracking-tight mb-2">Panel de Control de Admisión (CUP)</h2>
                    <p class="text-sm text-slate-400 max-w-2xl leading-relaxed">Monitoreo estadístico y administración centralizada del proceso de admisión preuniversitario para la facultad.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg shadow-slate-950/20">
                        <div class="h-12 w-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0"><i data-lucide="users" class="h-5 w-5"></i></div>
                        <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Inscritos</p><p id="stat-inscritos" class="text-3xl font-bold text-white mt-1">...</p></div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg shadow-slate-950/20">
                        <div class="h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0"><i data-lucide="check-circle" class="h-5 w-5"></i></div>
                        <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Aprobados</p><p id="stat-aprobados" class="text-3xl font-bold text-emerald-400 mt-1">...</p></div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg shadow-slate-950/20">
                        <div class="h-12 w-12 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 shrink-0"><i data-lucide="x-circle" class="h-5 w-5"></i></div>
                        <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Reprobados</p><p id="stat-reprobados" class="text-3xl font-bold text-red-400 mt-1">...</p></div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg shadow-slate-950/20">
                        <div class="h-12 w-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0"><i data-lucide="layers" class="h-5 w-5"></i></div>
                        <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Grupos Habilitados</p><p id="stat-grupos" class="text-3xl font-bold text-white mt-1">...</p></div>
                    </div>
                </div>
            </div>

            <div id="mod-usuarios" class="hidden space-y-6 max-w-5xl mx-auto app-module">
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
                        <input type="text" id="usr_username" required placeholder="Ej: mlopez" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider">Correo Electrónico</label>
                        <input type="email" id="usr_email" required placeholder="ejemplo@uagrm.edu.bo" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider">Contraseña</label>
                        <input type="password" id="usr_password" required placeholder="Contraseña (Mín. 8 caracteres, 1 Mayús., 1 Núm.)" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                        <input type="password" id="usr_password_confirmation" required placeholder="Confirma tu contraseña" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider">Rol de Sistema</label>
                        <select id="usr_rol" required class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2 text-white text-sm focus:outline-none focus:border-indigo-500">
                            <option value="" disabled selected>Seleccione un Rol</option>
                            <option value="1">ADMINISTRADOR</option>
                            <option value="2">SECRETARIA</option>
                            <option value="3">DOCENTE</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium py-2 px-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer h-[42px]">
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
                            <tr class="border-b border-slate-700/30 hover:bg-slate-800/20">
                                <td class="p-4">1</td>
                                <td class="p-4 font-semibold text-white">admin</td>
                                <td class="p-4">admin@uagrm.edu.bo</td>
                                <td class="p-4"><span class="px-2 py-0.5 rounded text-[11px] bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold">ADMINISTRADOR</span></td>
                                <td class="p-4"><span class="text-emerald-400 flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> Activo</span></td>
                                <td class="p-4 text-center"><button class="text-xs text-red-400 hover:underline cursor-pointer">Desactivar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="mod-gestion" class="hidden space-y-6 max-w-3xl mx-auto app-module">
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
                            <input type="number" id="gst_anio" value="2026" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs text-slate-400">Periodo Académico</label>
                            <select id="gst_periodo" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none">
                                <option value="I">Primer Semestre (Periodo I)</option>
                                <option value="II" selected>Segundo Semestre (Periodo II)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button onclick="guardarGestionVigente()" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-medium text-sm shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 cursor-pointer">
                            <i data-lucide="check-square" class="h-4 w-4"></i> Establecer Gestión Activa
                        </button>
                    </div>
                </div>
            </div>

            

            <div id="mod-postulante" class="hidden space-y-6 max-w-4xl mx-auto app-module">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div><h2 class="text-2xl font-bold text-white tracking-tight">Registro de Nuevo Postulante</h2><p class="text-sm text-slate-400">Introduce los datos oficiales para el ingreso al curso preuniversitario (CUP).</p></div>
                    <div class="h-10 w-10 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400"><i data-lucide="user-plus" class="h-5 w-5"></i></div>
                </div>

                <form id="form-registro-postulante" class="bg-slate-800/40 backdrop-blur-md border border-slate-700/50 rounded-2xl p-8 shadow-xl space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="text-cursor-input" class="h-3.5 w-3.5 text-slate-500"></i> Cédula de Identidad (C.I.) <span class="text-red-400">*</span></label>
                            <input type="text" id="txt_ci" required placeholder="Ej: 8765432 SC" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="user" class="h-3.5 w-3.5 text-slate-500"></i> Nombres y Apellidos <span class="text-red-400">*</span></label>
                            <input type="text" id="txt_nombre" required placeholder="Ej: Juan Pérez Mamani" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="mail" class="h-3.5 w-3.5 text-slate-500"></i> Correo Electrónico <span class="text-red-400">*</span></label>
                            <input type="email" id="txt_correo" required placeholder="ejemplo@uagrm.edu.bo" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="phone" class="h-3.5 w-3.5 text-slate-500"></i> Teléfono / Celular</label>
                            <input type="text" id="txt_telefono" placeholder="Ej: 78945612" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="calendar" class="h-3.5 w-3.5 text-slate-500"></i> Fecha de Nacimiento <span class="text-red-400">*</span></label>
                            <input type="date" id="fch_nacimiento" required class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="text" class="h-3.5 w-3.5 text-slate-500"></i> Sexo / Género <span class="text-red-400">*</span></label>
                            <select id="chr_sexo" required class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                                <option value="" disabled selected>Selecciona una opción</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="school" class="h-3.5 w-3.5 text-slate-500"></i> Colegio de Procedencia</label>
                            <input type="text" id="txt_colegio" placeholder="Ej: Colegio Nacional Florida" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="map-pin" class="h-3.5 w-3.5 text-slate-500"></i> Ciudad</label>
                            <input type="text" id="txt_ciudad" placeholder="Ej: Santa Cruz de la Sierra" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-1.5"><i data-lucide="home" class="h-3.5 w-3.5 text-slate-500"></i> Dirección Domiciliaria</label>
                        <input type="text" id="txt_direccion" placeholder="Ej: Av. Bush, 2do Anillo, Calle 5 Nro 45" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all">
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t border-slate-700/50 pt-6">
                        <button type="reset" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800 text-sm font-medium transition-all flex items-center gap-2 cursor-pointer"><i data-lucide="refresh-cw" class="h-4 w-4"></i> Limpiar</button>
                        <button type="submit" id="btn-registrar-postulante" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-medium text-sm shadow-lg shadow-blue-500/10 transition-all flex items-center gap-2 cursor-pointer"><i data-lucide="save" class="h-4 w-4"></i> Guardar Postulante</button>
                    </div>
                </form>
            </div>

            <div id="mod-examenes" class="hidden space-y-6 app-module">
                <div><h2 class="text-xl font-bold text-white">Registro de Calificaciones Preuniversitarias</h2><p class="text-xs text-slate-400">Administración de notas de exámenes oficiales por materias.</p></div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 text-center text-slate-500 text-sm">Módulo de calificaciones (Ciclo 2). Permite cargar notas de Computación, Matemáticas, Física e Inglés.</div>
            
                
            </div>

            <div id="mod-grupos" class="hidden app-module space-y-6 max-w-5xl mx-auto">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-white">Estado de Asignación de Grupos</h2>
        <button onclick="cargarGrupos()" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm transition-all cursor-pointer">
            Actualizar Lista
        </button>
    </div>

    <div id="lista-grupos" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

    <div class="border-t border-slate-700 pt-8 mt-10">
        <h3 class="text-lg font-bold text-slate-200 mb-4">Referencia de Infraestructura</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-800/50 p-5 rounded-xl border border-slate-700">
                <h4 class="text-xs font-bold text-indigo-400 uppercase mb-4 tracking-wider">Aulas Disponibles</h4>
                <div class="space-y-3" id="lista-aulas"></div>
            </div>
            <div class="bg-slate-800/50 p-5 rounded-xl border border-slate-700">
                <h4 class="text-xs font-bold text-indigo-400 uppercase mb-4 tracking-wider">Materias Vigentes</h4>
                <div class="flex flex-wrap gap-2" id="lista-materias"></div>
            </div>
        </div>
    </div>

    










            








            <div id="mod-reportes" class="hidden space-y-6 app-module">
                <div><h2 class="text-xl font-bold text-white">Reportes Estadísticos y de Rendimiento</h2><p class="text-xs text-slate-400">Exportación de datos de aprobados, reprobados y distribución por cupos.</p></div>
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 text-center text-slate-500 text-sm">Gráficos e indicadores avanzados exigidos por la facultad.</div>
            </div>
        </main>
    </div>

    <script>
        const API_BASE_URL = 'http://localhost:8000';

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            loadDashboardStats();
        });










        
        // ── ENRUTADOR DINÁMICO CORREGIDO (FASE 1) ──
        function switchModule(moduleName) {
            // Ocultar todos los módulos con la clase global
            document.querySelectorAll('.app-module').forEach(mod => mod.classList.add('hidden'));
            
            // Restablecer estilos de todos los botones en el sidebar
            document.querySelectorAll('#sidebar-menu button').forEach(btn => {
                btn.className = "w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all text-slate-300 hover:bg-slate-700/30 hover:text-white cursor-pointer";
            });

            // Mostrar el módulo objetivo
            const targetModule = document.getElementById(`mod-${moduleName}`);
            if (targetModule) targetModule.classList.remove('hidden');

            // Resaltar botón seleccionado
            const activeBtn = document.getElementById(`btn-${moduleName}`);
            if (activeBtn) activeBtn.className = "w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all text-white bg-slate-700/50 cursor-pointer";

            // Títulos de la cabecera
            const pageTitles = {
                'dashboard': 'Escritorio de Control',
                'usuarios': 'Control de Usuarios y Permisos',
                'gestion': 'Configuración de Gestión Vigente',
                'postulante': 'Registro de Postulantes',
                'examenes': 'Exámenes / Notas',
                'grupos': 'Asignación de Grupos',
                'reportes': 'Reportes Analíticos'
            };
            if (pageTitles[moduleName]) document.getElementById('current-page-title').textContent = pageTitles[moduleName];

            lucide.createIcons();
            if (moduleName === 'usuarios') {
                loadUsuarios();
            }
        }

        // Carga de Métricas desde PostgreSQL
        async function loadDashboardStats() {
            const token = localStorage.getItem('token');
            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/dashboard/metrics`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    return;
                }
                
                const result = await response.json();
                if (response.ok && result.success) {
                    const metrics = result.data;
                    document.getElementById('stat-inscritos').textContent = metrics.inscritos ?? 0;
                    document.getElementById('stat-aprobados').textContent = metrics.aprobados ?? 0;
                    document.getElementById('stat-reprobados').textContent = metrics.reprobados ?? 0;
                    document.getElementById('stat-grupos').textContent = metrics.grupos ?? 0;
                } else {
                    fallbackToZero();
                }
            } catch (error) {
                console.error("Error en Fetch:", error);
                fallbackToZero();
            }
        }

        // Enviar Formulario de Postulantes
        document.getElementById('form-registro-postulante').addEventListener('submit', async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('token');
            const btnGuardar = document.getElementById('btn-registrar-postulante');
            
            const datosPostulante = {
                txt_ci: document.getElementById('txt_ci').value,
                txt_nombre: document.getElementById('txt_nombre').value,
                txt_correo: document.getElementById('txt_correo').value,
                txt_telefono: document.getElementById('txt_telefono').value,
                fch_nacimiento: document.getElementById('fch_nacimiento').value,
                chr_sexo: document.getElementById('chr_sexo').value,
                txt_colegio: document.getElementById('txt_colegio').value,
                txt_ciudad: document.getElementById('txt_ciudad').value,
                txt_direccion: document.getElementById('txt_direccion').value
            };

            try {
                btnGuardar.disabled = true;
                const response = await fetch(`${API_BASE_URL}/api/v1/postulantes`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(datosPostulante)
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    alert("¡Postulante guardado con éxito en PostgreSQL!");
                    document.getElementById('form-registro-postulante').reset();
                    loadDashboardStats();
                } else {
                    alert("Error al guardar: " + (result.message || "Verifica los datos."));
                }
            } catch (error) {
                alert("No se pudo conectar con Laravel.");
            } finally {
                btnGuardar.disabled = false;
            }
        });







        // ── ESQUELETOS DE FUNCIONES PARA LA FASE 1 (CONECTAR CON TU BACKEND) ──
        async function guardarGestionVigente() {
            const token = localStorage.getItem('token');
            const anio = document.getElementById('gst_anio').value;
            const periodo = document.getElementById('gst_periodo').value;

            alert(`Configuración guardada para el CUP local: ${anio} - ${periodo}`);
            // Aquí harás tu fetch a: ${API_BASE_URL}/api/v1/gestiones
        }





        function fallbackToZero() {
            document.getElementById('stat-inscritos').textContent = "0";
            document.getElementById('stat-aprobados').textContent = "0";
            document.getElementById('stat-reprobados').textContent = "0";
            document.getElementById('stat-grupos').textContent = "0";
        }




        // ── CARGAR USUARIOS REALES DESDE LA BD ──
        async function loadUsuarios() {
            const token = localStorage.getItem('token');
            const tablaBody = document.getElementById('tabla-usuarios-body');
            
            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/usuarios`, {
                    method: 'GET',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });

                const result = await response.json();
                console.log("Estructura completa recibida:", result); // <-- ¡Mírame en la consola!

                if (response.ok && result.success) {
                    // AQUÍ ESTÁ EL TRUCO: Buscamos dónde están los datos
                    // Intentamos: result.data (si es arreglo), result.data.users, o result.data.data (si es paginado)
                    const usuarios = Array.isArray(result.data) ? result.data : 
                                    (result.data.users || result.data.data || []);
                    
                    if (usuarios.length === 0) {
                        console.warn("No se encontraron usuarios en los formatos esperados.");
                        return;
                    }

                    tablaBody.innerHTML = ''; 
                    usuarios.forEach(u => {
                        console.log("Objeto usuario recibido:", u);
                        const fila = document.createElement('tr');
                        fila.className = "border-b border-slate-700/30 hover:bg-slate-800/20";
                        fila.innerHTML = `
                            <td class="p-4">${u.id || ''}</td>
                            <td class="p-4 font-semibold text-white">${u.txt_username || u.username || 'N/A'}</td>
                            <td class="p-4">${u.txt_email || u.email || 'N/A'}</td>
                            <td class="p-4">
                            <span class="px-2 py-0.5 rounded text-[11px] bg-purple-500/10 text-purple-400 border border-purple-500/20 font-bold">
                                    ${u.rol ? u.rol.txt_nombre : 'SIN ROL'}
                                </span>
                            </td>
                            <td class="p-4 text-emerald-400">● Activo</td>
                            <td class="p-4 text-center"><button class="text-xs text-red-400 hover:underline">Desactivar</button></td>
                        `;
                        tablaBody.appendChild(fila);
                    });
                }
                } catch (error) {
                    console.error("Error al ejecutar loadUsuarios:", error);
                }
        }








        async function desactivarUsuario(id) {
            if(!confirm("¿Está seguro de desactivar este usuario?")) return;
            
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE_URL}/api/v1/usuarios/${id}/estado`, {
                method: 'PATCH',
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });

            if(response.ok) {
                alert("Usuario desactivado correctamente");
                loadUsuarios(); // Recarga la tabla
            }
        }





        // Capturar el envío del formulario de usuarios
        document.getElementById('form-registro-usuario').addEventListener('submit', async (e) => {
            e.preventDefault(); // Evita que la página se recargue
            
            const token = localStorage.getItem('token');
            const datosUsuario = {
                txt_username: document.getElementById('usr_username').value,
                txt_email: document.getElementById('usr_email').value,
                id_rol: document.getElementById('usr_rol').value, // El valor del select (1, 2, 3)
                txt_password: document.getElementById('usr_password').value,
                txt_password_confirmation: document.getElementById('usr_password_confirmation').value
            };

            try {
                const response = await fetch(`${API_BASE_URL}/api/v1/usuarios`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(datosUsuario)
                });

                const result = await response.json();
        
                if (response.ok && result.success) {
                    alert("¡Usuario creado con éxito en PostgreSQL!");
                    document.getElementById('form-registro-usuario').reset();
                    loadUsuarios(); 
                } else {
                    // ESTA ES LA CLAVE: Si es error 422, Laravel nos da los detalles
                    if (response.status === 422) {
                        console.error("Errores de validación:", result.errors);
                        // Construimos un mensaje legible para el usuario
                        let msg = "Error al crear:\n";
                        for (let key in result.errors) {
                            msg += `- ${result.errors[key].join(", ")}\n`;
                        }
                        alert(msg);
                    } else {
                        alert("Error: " + (result.message || "Verifica los datos."));
                    }
                }
            } catch (error) {
                console.error("Error:", error);
                alert("No se pudo conectar con el servidor.");
            }
        });
        function toggleSubMenu(id) {
            const sub = document.getElementById(id);
            sub.classList.toggle('hidden');
        }




        

        // --- FUNCIÓN PARA LISTAR Y BUSCAR (CU-10 y CU-09) ---
        async function loadPostulantes(terminoBusqueda = '') {
            const token = localStorage.getItem('token');
            
            // Si hay texto de búsqueda, usamos la ruta buscar; si no, el index
            const url = terminoBusqueda 
                ? `${API_BASE_URL}/api/v1/postulantes/buscar?q=${encodeURIComponent(terminoBusqueda)}` 
                : `${API_BASE_URL}/api/v1/postulantes`;

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                console.log("Datos recibidos del servidor:", result.data);
                // Si la respuesta es un objeto con propiedad 'data' (típico de Laravel)
                const postulantes = result.data.data || result.data; 

                if (result.success) {
                    // 2. Extraemos el array correctamente. 
                    // Como vimos en tu JSON, los datos están en result.data.data
                    const listaPostulantes = result.data.data;
                    
                    console.log("Postulantes a renderizar:", listaPostulantes);

                    // 3. Llamamos a renderizarTabla UNA SOLA VEZ
                    if (Array.isArray(listaPostulantes)) {
                            renderizarTabla(listaPostulantes);
                        } else {
                            console.error("El formato de datos no es un array:", listaPostulantes);
                        }
        } else {
            console.error("Error en la respuesta del servidor:", result);
        }
            } catch (error) {
                console.error("Error al obtener postulantes:", error);
            }
        }




        // Función para el buscador en tiempo real
        function filtrarPostulantes() {
            const query = document.getElementById('buscar-postulante').value;
            loadPostulantes(query);
        }
        async function eliminarPostulante(id) {
            if (!confirm("¿Está seguro de eliminar este postulante? Esta acción no se puede deshacer.")) return;

            const response = await fetch(`${API_BASE_URL}/api/v1/postulantes/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            });

            const result = await response.json();
            if (result.success) {
                alert(result.message);
                loadPostulantes(); // Recargar tabla
            } else {
                alert("Error: " + result.message); // Aquí mostrará el 409 Conflict si tiene inscripciones
            }
        }
        // app/Http/Controllers/Api/V1/DashboardController.php
        
        function fallbackToZero() {
            const stats = ['stat-inscritos', 'stat-aprobados', 'stat-reprobados', 'stat-grupos'];
            stats.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = '0';
            });
        }
        // Función para el buscador
        function filtrarPostulantes() {
            const query = document.getElementById('buscar-postulante').value;
            loadPostulantes(query);
        }

        // Función para cambiar submenús (Gestión de personas)
        function toggleSubMenu(id) {
            const sub = document.getElementById(id);
            if (sub) sub.classList.toggle('hidden');
        }

        // Función para mostrar módulos (cambiar pantallas)
        function showModule(moduleId) {
            document.querySelectorAll('.app-module').forEach(el => el.classList.add('hidden'));
            const active = document.getElementById(moduleId);
            if (active) active.classList.remove('hidden');
        }
        
        function handleLogout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }






        // --- FUNCIÓN PARA DIBUJAR LA TABLA DE POSTULANTES ---
        function renderizarTabla(data) {
            console.log("--- INICIANDO RENDERIZADO ---");
            console.log("Recibido en renderizarTabla:", data);
        const body = document.getElementById('tabla-postulantes-body');
        if (!body) {
            console.error("ERROR: No se encontró el elemento con ID 'tabla-postulantes-body'. Revisa tu HTML.");
            return;
        }
        // 2. Verificar si hay datos
    if (!data || data.length === 0) {
        console.warn("ADVERTENCIA: El array de datos está vacío.");
        return;
    }
        console.log("Datos válidos, comenzando a crear filas...");
        body.innerHTML = ''; 

        data.forEach((p,index) => {
            console.log(`Procesando fila ${index}:`, p.txt_nombre);
            // 1. Declaramos todas las variables necesarias
            const ci = p.txt_ci || 'N/A';
            const nombres = p.txt_nombre || '';
            const apellidos = p.txt_apellido || '';
            
            // 2. Manejamos las dos opciones de carrera de forma segura
            const c1 = p.carrera_1 || 'N/A';
            const c2 = p.carrera_2 || '-';
            const carreraDisplay = (c2 && c2 !== 'N/A') ? `${c1} / ${c2}` : c1;
            
            const estado = p.txt_estado_inscripcion || 'REGISTRADO';

            // 3. Usamos las variables declaradas arriba, NO uses p.carrera directamente
            body.innerHTML += `
            <tr class="border-b border-slate-700/30">
                <td class="p-4">${p.txt_ci}</td>
                <td class="p-4 font-medium text-white">${p.txt_nombre}</td>
                <td class="p-4">${c1}</td> 
                <td class="p-4">${c2}</td> 
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-bold">
                        ${estado}
                    </span>
                </td>
                <td class="p-4 text-center">
                    <button onclick="editarPostulante(${p.id_postulante})" class="text-indigo-400 hover:text-indigo-300 mr-2">Editar</button>
                    <button onclick="eliminarPostulante(${p.id_postulante})" class="text-red-400 hover:text-red-300">Eliminar</button>
                </td>
            </tr>
        `;
        });
    }






    
        // Llamar automáticamente al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            loadPostulantes();
        });
        function editarPostulante(id) {
            // 1. Buscamos los datos en la tabla actual o hacemos fetch a /api/v1/postulantes/{id}
            fetch(`${API_BASE_URL}/api/v1/postulantes/${id}`, {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            })
            .then(res => res.json())
            .then(result => {
                const p = result.data;
                document.getElementById('edit_id').value = p.id_postulante;
                document.getElementById('edit_ci').value = p.txt_ci;
                document.getElementById('edit_nombre').value = p.txt_nombre;
                document.getElementById('edit_apellido').value = p.txt_apellido;
                // Dentro de tu fetch en editarPostulante
                document.getElementById('edit_carrera1').value = p.txt_carrera1 || '';
                document.getElementById('edit_carrera2').value = p.txt_carrera2 || '';
                
                document.getElementById('modal-editar').classList.remove('hidden');
            });
        }






        document.getElementById('form-editar').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('edit_id').value;
            
            const datos = {
                txt_ci: document.getElementById('edit_ci').value,
                txt_nombre: document.getElementById('edit_nombre').value,
                txt_apellido: document.getElementById('edit_apellido').value,
                txt_carrera: document.getElementById('edit_carrera').value
            };

            const response = await fetch(`${API_BASE_URL}/api/v1/postulantes/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            });

            const result = await response.json();
            if (result.success) {
                alert("Actualizado correctamente");
                document.getElementById('modal-editar').classList.add('hidden');
                loadPostulantes(); // Recargar la tabla
            }
        });






function cerrarModal() {
    document.getElementById('modal-editar').classList.add('hidden');
}





        async function guardarDocente() {
            // 1. Crear el objeto JSON con los nombres exactos de la tabla
            const docenteData = {
                txt_ci: document.getElementById('doc_ci').value,
                txt_nombre: document.getElementById('doc_nombre').value,
                txt_telefono: document.getElementById('doc_telefono').value,
                txt_correo: document.getElementById('doc_correo').value,

                // Estructura de Contratación
                contratacion: {
                    fch_contrato: document.getElementById('doc_fch_contrato').value,
                    num_salario: document.getElementById('doc_salario').value
                },

                // Estructura de Profesiones (Array de objetos)
                profesiones: [
                    {
                        id_profesion: document.getElementById('doc_id_profesion').value,
                        txt_titulo: document.getElementById('doc_titulo').value,
                        txt_universidad: document.getElementById('doc_universidad').value,
                        fch_emicion: document.getElementById('doc_fch_emision').value
                    }
                ]
            };
            const token = localStorage.getItem('token');
            const headers = { 
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}` // <--- ESTO ES LO QUE TE FALTA
            };
            
            // 2. Enviar al servidor
            try {
                const response = await fetch('/api/v1/docentes', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // Esto es vital para que Laravel responda JSON
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(docenteData)
                });

                // LEER LA RESPUESTA COMO TEXTO PRIMERO
                const responseText = await response.text();
                
                // Si la respuesta no es OK, muestra el texto crudo
                if (!response.ok) {
                    console.error("ERROR DEL SERVIDOR:", responseText);
                    alert("Error " + response.status + ": Revisa la consola (F12) para ver el mensaje del servidor.");
                    return;
                }

                // Si es OK, intentamos convertir a JSON
                const result = JSON.parse(responseText);
                alert("Docente registrado correctamente");
                document.getElementById('form-docente').reset();

            } catch (error) {
                console.error("Error de JS:", error);
            }
        }









        async function cargarGrupos() {
    const contGrupos = document.getElementById('lista-grupos');
    const contAulas = document.getElementById('lista-aulas');
    const contMaterias = document.getElementById('lista-materias');
    
    // Indicamos carga
    contGrupos.innerHTML = '<p class="text-slate-500">Cargando...</p>';
    
    try {
        const token = localStorage.getItem('token');
        const headers = { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` };

        // 1. Ejecutamos las 3 llamadas en paralelo
        const [resG, resA, resM] = await Promise.all([
            fetch('/api/v1/grupos', { headers }),
            fetch('/api/v1/aulas', { headers }),
            fetch('/api/v1/materias', { headers })
        ]);

        const grupos = await resG.json();
        const aulas = await resA.json();
        const materias = await resM.json();

        // 2. Renderizar Grupos (Tu lógica original)
        if (grupos.success && grupos.data.length > 0) {
            contGrupos.innerHTML = grupos.data.map(g => `
                <div class="bg-slate-800 p-5 rounded-xl border border-slate-700 shadow-lg">
                    <h3 class="text-blue-400 font-bold text-lg">${g.txt_nombre}</h3>
                    <p class="text-sm text-slate-300 mt-2">Estudiantes: ${g.int_cantidad_estudiantes} / ${g.int_capacidad_maxma}</p>
                    <div class="mt-3 text-xs text-slate-500 bg-slate-900 p-2 rounded">
                        ${(g.horarios || []).map(h => `<div>${h.dia}: ${h.inicio}-${h.final} (${h.turno})</div>`).join('')}
                    </div>
                </div>
            `).join('');
        } else {
            contGrupos.innerHTML = '<p class="text-slate-500">No hay grupos asignados.</p>';
        }

        // 3. Renderizar Aulas (Usando la estructura que definiste)
        const listaA = Array.isArray(aulas) ? aulas : (aulas.data || []);
        contAulas.innerHTML = listaA.map(a => `
            <div class="text-white py-1 border-b border-slate-700"> ${a.txt_nro_aula}</div>
        `).join('');

        // 4. Renderizar Materias (Usando la estructura que definiste)
        const listaM = Array.isArray(materias) ? materias : (materias.data || []);
        contMaterias.innerHTML = listaM.map(m => `
            <span class="bg-slate-900 px-2 py-1 rounded text-xs text-white border border-slate-700">${m.txt_nombre}</span>
        `).join('');

    } catch (err) {
        console.error("Error al cargar datos:", err);
        contGrupos.innerHTML = '<p class="text-red-500">Error al cargar datos. Revisa la consola.</p>';
    }
}




















        function handleLogout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
    </script>
    <div id="modal-editar" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-800 p-6 rounded-xl w-full max-w-md shadow-2xl border border-slate-700">
        <h2 class="text-white text-lg font-bold mb-4">Editar Postulante</h2>
        <form id="form-editar">
            <input type="hidden" id="edit_id">
            
            <div class="mb-3">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Cédula de Identidad</label>
                <input type="text" id="edit_ci" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            
            <div class="flex gap-3">
                <div class="mb-3 flex-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nombre</label>
                    <input type="text" id="edit_nombre" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="mb-3 flex-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Apellido</label>
                    <input type="text" id="edit_apellido" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Primera Opción</label>
                <input type="text" id="edit_carrera1" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white">
            </div>
            <div class="mb-3">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Segunda Opción</label>
                <input type="text" id="edit_carrera2" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Teléfono</label>
                <input type="text" id="edit_telefono" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="cerrarModal()" class="px-4 py-2 text-slate-400 hover:text-white font-bold transition">Cancelar</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-lg font-bold transition shadow-lg shadow-blue-900/20">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
</div>


</body>
</html>