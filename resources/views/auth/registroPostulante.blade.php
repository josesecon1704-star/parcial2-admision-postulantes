<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Postulante - Admisión FICCT</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-slate-900 text-slate-100 font-sans antialiased min-h-screen py-10 px-4">

    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Encabezado -->
        <div class="text-center space-y-2">
            <div class="mx-auto h-12 w-12 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                <i data-lucide="graduation-cap" class="h-6 w-6"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Registro de Postulante</h1>
            <p class="text-sm text-slate-400">Completa tus datos para inscribirte al proceso de admisión FICCT</p>
            <a href="/login" class="inline-flex items-center gap-1.5 text-xs text-indigo-400 hover:text-indigo-300 transition-colors">
                <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i>
                Volver al inicio de sesión
            </a>
        </div>

        <!-- Alertas -->
        <div id="alertError" class="hidden p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-sm text-red-400 flex items-start gap-3">
            <i data-lucide="alert-circle" class="h-5 w-5 shrink-0 mt-0.5"></i>
            <span id="alertErrorMsg">Ocurrió un error.</span>
        </div>
        <div id="alertOk" class="hidden p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-sm text-emerald-400 flex items-start gap-3">
            <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0 mt-0.5"></i>
            <span id="alertOkMsg"></span>
        </div>

        <!-- Post-registro: opciones de pago de matrícula -->
        <div id="postRegistro" class="hidden bg-slate-800/50 backdrop-blur-md rounded-2xl border border-slate-700/50 shadow-xl p-6 sm:p-8 space-y-4 text-center">
            <div class="mx-auto h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                <i data-lucide="credit-card" class="h-6 w-6"></i>
            </div>
            <h3 class="text-base font-bold text-white">Matrícula de admisión: Bs 350.00</h3>
            <p class="text-xs text-slate-400">Puedes pagar ahora con tarjeta (vía pasarela segura) o hacerlo más tarde desde tu portal de postulante.</p>

            <div id="pagoError" class="hidden p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-xs text-red-400"></div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="button" id="btnPagarAhora" onclick="pagarAhora()"
                    class="flex-1 py-2.5 px-4 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-medium rounded-xl text-sm flex items-center justify-center gap-2 cursor-pointer transition-all">
                    <i data-lucide="credit-card" class="h-4 w-4"></i>
                    Pagar matrícula ahora
                </button>
                <a href="/login"
                    class="flex-1 py-2.5 px-4 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl text-sm flex items-center justify-center gap-2 transition-all">
                    Pagar después
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>
        </div>

        <form id="formRegistro" class="space-y-6 bg-slate-800/50 backdrop-blur-md rounded-2xl border border-slate-700/50 shadow-xl p-6 sm:p-8">

            <!-- Datos personales -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="user" class="h-4 w-4"></i>
                    Datos Personales
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">C.I. *</label>
                        <input type="text" id="txt_ci" required maxlength="20" placeholder="1234567-LP"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Nombres y Apellidos *</label>
                        <input type="text" id="txt_nombre" required maxlength="150"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Correo Electrónico *</label>
                        <input type="email" id="txt_correo" required maxlength="100"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Teléfono</label>
                        <input type="text" id="txt_telefono" maxlength="20"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Fecha de Nacimiento *</label>
                        <input type="date" id="fch_nacimiento" required
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Sexo *</label>
                        <select id="chr_sexo" required
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                            <option value="" disabled selected>— Selecciona —</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="X">Prefiero no decirlo</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Colegio</label>
                        <input type="text" id="txt_colegio" maxlength="150"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-400 uppercase">Ciudad</label>
                        <input type="text" id="txt_ciudad" maxlength="100"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs font-bold text-slate-400 uppercase">Dirección</label>
                        <input type="text" id="txt_direccion" maxlength="255"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <p class="text-[11px] text-slate-500">Tu contraseña para ingresar al portal será tu Carnet de Identidad (C.I.).</p>
            </div>

            <!-- Requisitos -->
            <div class="border-t border-slate-700/50 pt-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="clipboard-check" class="h-4 w-4"></i>
                        Requisitos que presentas
                    </h3>
                </div>
                <div id="lista-requisitos" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <p class="text-slate-500 text-xs col-span-2">Cargando requisitos...</p>
                </div>
            </div>

            <!-- Carreras -->
            <div class="border-t border-slate-700/50 pt-5">
                <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-2 mb-3">
                    <i data-lucide="graduation-cap" class="h-4 w-4"></i>
                    Carreras de Preferencia
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs text-slate-400 uppercase font-bold">1ra Opción *</label>
                        <div class="relative">
                            <select id="carrera_1" required
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500 appearance-none pr-8">
                                <option value="" disabled selected>— Selecciona carrera —</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs text-slate-400 uppercase font-bold">2da Opción *</label>
                        <div class="relative">
                            <select id="carrera_2" required
                                class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500 appearance-none pr-8">
                                <option value="" disabled selected>— Selecciona carrera —</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horario disponible -->
            <div class="border-t border-slate-700/50 pt-5">
                <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-2 mb-3">
                    <i data-lucide="calendar-clock" class="h-4 w-4"></i>
                    Horario Disponible
                </h3>
                <p class="text-[11px] text-slate-500 mb-3">Elige el horario en el que deseas asistir a tus clases (Lunes a Viernes, mismo horario diario). Solo se muestran horarios con cupo.</p>
                <div id="lista-horarios" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <p class="text-slate-500 text-xs col-span-2">Cargando horarios disponibles...</p>
                </div>
            </div>

            <button type="submit" id="btnRegistrar"
                class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all text-sm flex items-center justify-center gap-2 cursor-pointer">
                <span>Registrarme</span>
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </button>
        </form>
    </div>

    <script>
        lucide.createIcons();

        const API_BASE_URL = (() => {
            const host = window.location.hostname;
            if (host === 'localhost' || host === '127.0.0.1') return 'http://localhost:8000';
            return window.location.origin;
        })();

        let GRUPO_SELECCIONADO = null;
        let ID_INSCRIPCION_REGISTRADA = null;

        async function pagarAhora() {
            const btn = document.getElementById('btnPagarAhora');
            const errEl = document.getElementById('pagoError');
            errEl.classList.add('hidden');

            if (!ID_INSCRIPCION_REGISTRADA) {
                errEl.textContent = 'No se encontró la inscripción para procesar el pago.';
                errEl.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span>Redirigiendo a la pasarela de pago...</span>';

            try {
                const res = await fetch(`${API_BASE_URL}/api/v1/public/pagos/${ID_INSCRIPCION_REGISTRADA}/crear-sesion`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                });
                const result = await res.json();

                if (!result.success) throw new Error(result.message || 'No se pudo iniciar el pago.');

                window.location.href = result.data.checkout_url;

            } catch (err) {
                errEl.textContent = err.message;
                errEl.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="credit-card" class="h-4 w-4"></i> Pagar matrícula ahora';
                lucide.createIcons();
            }
        }

        function mostrarError(msg) {
            document.getElementById('alertOk').classList.add('hidden');
            const el = document.getElementById('alertError');
            document.getElementById('alertErrorMsg').textContent = msg;
            el.classList.remove('hidden');
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function mostrarOk(msg) {
            document.getElementById('alertError').classList.add('hidden');
            const el = document.getElementById('alertOk');
            document.getElementById('alertOkMsg').textContent = msg;
            el.classList.remove('hidden');
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        async function cargarOpciones() {
            try {
                const res = await fetch(`${API_BASE_URL}/api/v1/public/opciones-registro`, {
                    headers: { 'Accept': 'application/json' },
                });
                const result = await res.json();
                if (!result.success) throw new Error(result.message || 'Error al cargar opciones');

                const { carreras, requisitos, horarios_disponibles } = result.data;

                // Carreras
                const opts = carreras.map(c => `<option value="${c.id_carrera}">${c.txt_nombre}</option>`).join('');
                document.getElementById('carrera_1').innerHTML = '<option value="" disabled selected>— Selecciona 1ra opción —</option>' + opts;
                document.getElementById('carrera_2').innerHTML = '<option value="" disabled selected>— Selecciona 2da opción —</option>' + opts;

                // Requisitos
                const contReq = document.getElementById('lista-requisitos');
                contReq.innerHTML = requisitos.length
                    ? requisitos.map(r => `
                        <label class="flex items-center gap-3 p-3 bg-slate-900 rounded-xl border border-slate-700 cursor-pointer hover:border-amber-500/40 transition-all">
                            <input type="checkbox" name="requisito" value="${r.id_requisito}"
                                class="h-4 w-4 rounded accent-amber-500 cursor-pointer">
                            <span class="text-xs text-slate-300">${r.txt_descripcion_requisito}</span>
                        </label>`).join('')
                    : '<p class="text-slate-500 text-xs col-span-2">No hay requisitos configurados.</p>';

                // Horarios disponibles (selección única con radio)
                const contHor = document.getElementById('lista-horarios');
                contHor.innerHTML = horarios_disponibles.length
                    ? horarios_disponibles.map((g, i) => `
                        <label class="flex items-start gap-3 p-3 bg-slate-900 rounded-xl border border-slate-700 cursor-pointer hover:border-emerald-500/40 transition-all">
                            <input type="radio" name="grupo" value="${g.id_grupo}" ${i === 0 ? 'required' : ''}
                                class="h-4 w-4 mt-0.5 accent-emerald-500 cursor-pointer">
                            <div>
                                <p class="text-sm font-bold text-white">${g.txt_nombre}</p>
                                <p class="text-xs text-slate-400">${g.horario_resumen ?? '—'} · ${g.turno ?? ''}</p>
                                <p class="text-[11px] text-emerald-400 mt-0.5">${g.cupos_disponibles} cupos disponibles</p>
                            </div>
                        </label>`).join('')
                    : '<p class="text-amber-400 text-xs col-span-2">No hay horarios con cupo disponible en este momento. Contacta a secretaría.</p>';

                lucide.createIcons();
            } catch (err) {
                mostrarError('No se pudieron cargar las opciones de registro: ' + err.message);
            }
        }

        document.getElementById('formRegistro').addEventListener('submit', async (e) => {
            e.preventDefault();
            document.getElementById('alertError').classList.add('hidden');
            document.getElementById('alertOk').classList.add('hidden');

            const carrera1 = parseInt(document.getElementById('carrera_1').value);
            const carrera2 = parseInt(document.getElementById('carrera_2').value);

            if (carrera1 && carrera2 && carrera1 === carrera2) {
                mostrarError('La 1ra y 2da opción de carrera deben ser diferentes.');
                return;
            }

            const grupoInput = document.querySelector('input[name="grupo"]:checked');
            if (!grupoInput) {
                mostrarError('Debes elegir un horario disponible.');
                return;
            }

            const requisitosSeleccionados = [...document.querySelectorAll('input[name="requisito"]:checked')]
                .map(cb => parseInt(cb.value));

            const datos = {
                txt_ci:         document.getElementById('txt_ci').value.trim(),
                txt_nombre:     document.getElementById('txt_nombre').value.trim(),
                txt_correo:     document.getElementById('txt_correo').value.trim(),
                txt_telefono:   document.getElementById('txt_telefono').value.trim() || null,
                fch_nacimiento: document.getElementById('fch_nacimiento').value,
                chr_sexo:       document.getElementById('chr_sexo').value,
                txt_colegio:    document.getElementById('txt_colegio').value.trim()    || null,
                txt_ciudad:     document.getElementById('txt_ciudad').value.trim()     || null,
                txt_direccion:  document.getElementById('txt_direccion').value.trim()  || null,
                requisitos:     requisitosSeleccionados,
                carreras: [
                    { id_carrera: carrera1, int_prioridad: 1 },
                    { id_carrera: carrera2, int_prioridad: 2 },
                ],
                id_grupo: parseInt(grupoInput.value),
            };

            const btn = document.getElementById('btnRegistrar');
            btn.disabled = true;
            btn.innerHTML = '<span>Registrando...</span>';

            try {
                const res = await fetch(`${API_BASE_URL}/api/v1/public/postulantes`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(datos),
                });
                const result = await res.json();

                if (!result.success) {
                    if (result.errors) {
                        const primerError = Object.values(result.errors)[0][0];
                        throw new Error(primerError);
                    }
                    throw new Error(result.message || 'No se pudo completar el registro.');
                }

                mostrarOk(result.message + ` Grupo asignado: ${result.data.grupo}.`);
                document.getElementById('formRegistro').reset();
                document.getElementById('formRegistro').classList.add('hidden');

                ID_INSCRIPCION_REGISTRADA = result.data.id_inscripcion;
                document.getElementById('postRegistro').classList.remove('hidden');
                lucide.createIcons();

            } catch (err) {
                mostrarError(err.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>Registrarme</span><i data-lucide="arrow-right" class="h-4 w-4"></i>';
                lucide.createIcons();
            }
        });

        cargarOpciones();
    </script>
</body>

</html>