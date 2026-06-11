<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal del Docente - Admisión FICCT</title>
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
                    <span class="text-[10px] uppercase font-bold text-blue-400 tracking-wider">PORTAL DEL DOCENTE</span>
                </div>
            </div>

            <!-- Navegación -->
            <nav id="sidebar-menu" class="p-3 space-y-1 overflow-y-auto flex-1">

                <button data-module="perfil" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-white bg-indigo-600/80">
                    <i data-lucide="user" class="h-4 w-4 shrink-0"></i>
                    <span>Mi Perfil</span>
                </button>

                <button data-module="carga" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all cursor-pointer text-slate-400 hover:bg-slate-700/40 hover:text-white">
                    <i data-lucide="calendar-clock" class="h-4 w-4 shrink-0"></i>
                    <span>Carga Horaria</span>
                </button>

            </nav>
        </div>

        <!-- Pie del sidebar -->
        <div class="p-3 border-t border-slate-700/50 space-y-1 shrink-0">
            <div class="flex items-center gap-3 px-3 py-2">
                <div class="h-8 w-8 rounded-lg bg-slate-700 flex items-center justify-center font-bold text-sm text-indigo-400 border border-slate-600 shrink-0" id="sidebar-avatar">D</div>
                <div class="overflow-hidden">
                    <p id="sidebar-username" class="text-xs font-bold text-white truncate">Docente</p>
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
                <span>Portal del Docente</span>
                <i data-lucide="chevron-right" class="h-3 w-3"></i>
                <span id="header-page-title" class="text-white font-medium">Mi Perfil</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p id="header-username" class="text-xs font-bold text-white">Docente</p>
                    <p id="header-ci" class="text-[10px] text-slate-400">CI: —</p>
                </div>
                <div class="h-9 w-9 rounded-xl bg-slate-700 flex items-center justify-center font-bold text-sm text-indigo-400 border border-slate-600" id="header-avatar">D</div>
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
                        <p class="text-sm text-slate-400">Tus datos personales, profesiones y formación académica</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-indigo-600/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <i data-lucide="user" class="h-5 w-5"></i>
                    </div>
                </div>

                <!-- Datos personales -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="px-2 py-0.5 rounded-md bg-indigo-600/20 text-indigo-400 text-[11px] font-bold tracking-wider">DATOS PERSONALES</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nombre completo</p>
                            <p id="d-nombre" class="text-base font-semibold text-white">—</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Carnet de Identidad</p>
                            <p id="d-ci" class="text-base font-semibold text-white">—</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Correo</p>
                            <p id="d-correo" class="text-base font-semibold text-white">—</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Teléfono</p>
                            <p id="d-telefono" class="text-base font-semibold text-white">—</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Usuario</p>
                            <p id="d-username" class="text-base font-semibold text-white">—</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Carga actual</p>
                            <p id="d-carga" class="text-base font-semibold text-white">—</p>
                        </div>
                    </div>
                </div>

                <!-- Contratación -->
                <div id="d-contratacion-wrap" class="hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="px-2 py-0.5 rounded-md bg-emerald-600/20 text-emerald-400 text-[11px] font-bold tracking-wider">CONTRATACIÓN</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Fecha de contrato</p>
                            <p id="d-fch-contrato" class="text-base font-semibold text-white">—</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Salario</p>
                            <p id="d-salario" class="text-base font-semibold text-white">—</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Estado</p>
                            <p id="d-estado-contrato" class="text-base font-semibold text-emerald-400">—</p>
                        </div>
                    </div>
                </div>

                <!-- Profesiones -->
                <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                        <span class="px-2 py-0.5 rounded-md bg-blue-600/20 text-blue-400 text-[11px] font-bold tracking-wider">PROFESIONES</span>
                        <h3 class="text-sm font-bold text-white">Títulos y Universidades</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-800/80 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Profesión</th>
                                    <th class="px-4 py-3 text-left">Título</th>
                                    <th class="px-4 py-3 text-left">Universidad</th>
                                    <th class="px-4 py-3 text-center">Emisión</th>
                                </tr>
                            </thead>
                            <tbody id="d-tabla-profesiones" class="divide-y divide-slate-700/30 text-slate-300"></tbody>
                        </table>
                    </div>
                    <div id="d-sin-profesiones" class="hidden text-center py-8 text-sm text-slate-400">
                        No hay profesiones registradas.
                    </div>
                </div>

                <!-- Formaciones académicas -->
                <div id="d-formaciones-wrap" class="hidden bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
                        <span class="px-2 py-0.5 rounded-md bg-amber-600/20 text-amber-400 text-[11px] font-bold tracking-wider">FORMACIÓN</span>
                        <h3 class="text-sm font-bold text-white">Formación Académica Adicional</h3>
                    </div>
                    <div id="d-lista-formaciones" class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-3"></div>
                </div>
            </section>

            <!-- ╔══════════════════════════════════════╗
                 ║  MOD: CARGA HORARIA                   ║
                 ╚══════════════════════════════════════╝ -->
            <section id="mod-carga" class="app-module hidden space-y-6 max-w-6xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Carga Horaria</h2>
                        <p class="text-sm text-slate-400">Grupos, materias, horarios y aulas asignadas</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-emerald-600/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <i data-lucide="calendar-clock" class="h-5 w-5"></i>
                    </div>
                </div>

                <!-- Resumen -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                        <div class="h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0"><i data-lucide="layout-grid" class="h-5 w-5"></i></div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Grupos a cargo</p>
                            <p id="c-total-grupos" class="text-2xl font-bold text-white mt-1">—</p>
                        </div>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 flex items-center gap-5 shadow-lg">
                        <div class="h-12 w-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0"><i data-lucide="layers" class="h-5 w-5"></i></div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Máximo permitido</p>
                            <p id="c-max-grupos" class="text-2xl font-bold text-white mt-1">4</p>
                        </div>
                    </div>
                </div>

                <!-- Sin grupos asignados -->
                <div id="carga-sin-grupos" class="hidden bg-slate-800/40 border border-amber-500/20 rounded-2xl p-10 text-center">
                    <div class="h-14 w-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 mx-auto mb-4">
                        <i data-lucide="calendar-x" class="h-6 w-6"></i>
                    </div>
                    <h3 class="text-base font-bold text-white mb-1">Aún no tienes grupos asignados</h3>
                    <p class="text-sm text-slate-400">Tu carga horaria aparecerá aquí una vez que la administración te asigne grupos.</p>
                </div>

                <!-- Tarjetas por grupo -->
                <div id="c-grupos-container" class="space-y-6"></div>
            </section>

        </main>
    </div>

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

        const token = localStorage.getItem('token');

        if (!token) {
            window.location.href = '/login';
        }

        let docenteData = null;
        let cargaData = [];

        const PAGE_TITLES = {
            perfil: 'Mi Perfil',
            carga: 'Carga Horaria',
        };

        const ORDEN_DIAS = ['LUNES', 'MARTES', 'MIERCOLES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'SÁBADO', 'DOMINGO'];

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

        function authHeaders() {
            return {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            };
        }

        async function apiFetch(path) {
            const res = await fetch(`${API_BASE_URL}${path}`, {
                headers: authHeaders(),
            });

            if (res.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('rol');
                window.location.href = '/login';
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
                // 1. Perfil del docente
                const resMe = await apiFetch(`/api/v1/docentes/me`);
                docenteData = resMe.data;

                // 2. Carga horaria (grupos, materias, horarios)
                const resCarga = await apiFetch(`/api/v1/docentes/mi-carga`);
                cargaData = resCarga.data || [];

                renderHeader();
                renderPerfil();
                renderCarga();

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
            const nombre = docenteData.txt_nombre || 'Docente';
            const inicial = nombre.trim().charAt(0).toUpperCase() || 'D';

            document.getElementById('header-username').textContent = nombre;
            document.getElementById('header-ci').textContent = `CI: ${docenteData.txt_ci || '—'}`;
            document.getElementById('header-avatar').textContent = inicial;

            document.getElementById('sidebar-username').textContent = nombre;
            document.getElementById('sidebar-ci').textContent = `CI: ${docenteData.txt_ci || '—'}`;
            document.getElementById('sidebar-avatar').textContent = inicial;
        }

        // ════════════════════════════════════════════════════
        // RENDER: PERFIL
        // ════════════════════════════════════════════════════
        function renderPerfil() {
            const d = docenteData;

            document.getElementById('d-nombre').textContent = d.txt_nombre || '—';
            document.getElementById('d-ci').textContent = d.txt_ci || '—';
            document.getElementById('d-correo').textContent = d.txt_correo || '—';
            document.getElementById('d-telefono').textContent = d.txt_telefono || '—';
            document.getElementById('d-username').textContent = d.username || '—';
            document.getElementById('d-carga').textContent = `${d.total_grupos ?? 0} / ${d.maximo_grupos ?? 4} grupos`;

            // Contratación
            if (d.contratacion) {
                document.getElementById('d-contratacion-wrap').classList.remove('hidden');
                document.getElementById('d-fch-contrato').textContent = formatearFecha(d.contratacion.fch_contrato);
                document.getElementById('d-salario').textContent = d.contratacion.num_salario != null ?
                    `Bs. ${Number(d.contratacion.num_salario).toFixed(2)}` : '—';
                document.getElementById('d-estado-contrato').textContent = d.contratacion.txt_estado || '—';
            }

            // Profesiones
            const tbody = document.getElementById('d-tabla-profesiones');
            const sinProf = document.getElementById('d-sin-profesiones');
            tbody.innerHTML = '';

            const profesiones = d.profesiones || [];
            if (profesiones.length === 0) {
                sinProf.classList.remove('hidden');
            } else {
                sinProf.classList.add('hidden');
                profesiones.forEach(p => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-800/60 transition-colors';
                    tr.innerHTML = `
                        <td class="px-4 py-3 font-semibold text-white">${p.txt_descripcion || '—'}</td>
                        <td class="px-4 py-3">${p.txt_titulo || '—'}</td>
                        <td class="px-4 py-3">${p.txt_universidad || '—'}</td>
                        <td class="px-4 py-3 text-center">${formatearFecha(p.fch_emicion)}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // Formaciones
            const formaciones = (d.formaciones || []).filter(f => f.txt_nombre);
            if (formaciones.length > 0) {
                document.getElementById('d-formaciones-wrap').classList.remove('hidden');
                const cont = document.getElementById('d-lista-formaciones');
                cont.innerHTML = formaciones.map(f => `
                    <div class="flex items-center gap-3 p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <div class="h-9 w-9 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0">
                            <i data-lucide="award" class="h-4 w-4"></i>
                        </div>
                        <span class="text-sm text-slate-200 font-medium">${f.txt_nombre}</span>
                    </div>
                `).join('');
            }

            lucide.createIcons();
        }

        // ════════════════════════════════════════════════════
        // RENDER: CARGA HORARIA
        // ════════════════════════════════════════════════════
        function renderCarga() {
            document.getElementById('c-total-grupos').textContent = cargaData.length;
            document.getElementById('c-max-grupos').textContent = '4';

            const cont = document.getElementById('c-grupos-container');
            const sinGrupos = document.getElementById('carga-sin-grupos');
            cont.innerHTML = '';

            if (cargaData.length === 0) {
                sinGrupos.classList.remove('hidden');
                return;
            }
            sinGrupos.classList.add('hidden');

            cargaData.forEach(g => {
                const horarios = [...(g.horarios || [])].sort((a, b) => {
                    const da = ORDEN_DIAS.indexOf((a.dia || '').toUpperCase());
                    const db = ORDEN_DIAS.indexOf((b.dia || '').toUpperCase());
                    if (da !== db) return da - db;
                    return (a.inicio || '').localeCompare(b.inicio || '');
                });

                const filas = horarios.map(h => {
                    const aula = h.aula ?
                        `Piso ${h.aula.int_piso} - Aula ${h.aula.txt_nro_aula}` :
                        '—';
                    return `
                        <tr class="hover:bg-slate-800/60 transition-colors">
                            <td class="px-4 py-3 font-semibold text-white">${h.dia || '—'}</td>
                            <td class="px-4 py-3 text-center">${formatearHora(h.inicio)}</td>
                            <td class="px-4 py-3 text-center">${formatearHora(h.final)}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center text-xs font-bold px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    ${h.turno || '—'}
                                </span>
                            </td>
                            <td class="px-4 py-3">${aula}</td>
                        </tr>
                    `;
                }).join('');

                const card = document.createElement('div');
                card.className = 'bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden';
                card.innerHTML = `
                    <div class="px-6 py-4 border-b border-slate-700/50 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded-md bg-emerald-600/20 text-emerald-400 text-[11px] font-bold tracking-wider">${g.grupo || '—'}</span>
                            <h3 class="text-sm font-bold text-white">${g.materia || '—'}</h3>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <i data-lucide="users" class="h-3.5 w-3.5"></i>
                            <span>${g.estudiantes ?? '—'} / ${g.capacidad ?? '—'} estudiantes</span>
                        </div>
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
                            <tbody class="divide-y divide-slate-700/30 text-slate-300">
                                ${filas || `<tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">Sin horarios asignados</td></tr>`}
                            </tbody>
                        </table>
                    </div>
                `;
                cont.appendChild(card);
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
                /* ignorar errores de logout */
            }

            localStorage.removeItem('token');
            localStorage.removeItem('rol');
            window.location.href = '/login';
        }

        // ════════════════════════════════════════════════════
        // INIT
        // ════════════════════════════════════════════════════
        cargarTodo();
    </script>
</body>

</html>
