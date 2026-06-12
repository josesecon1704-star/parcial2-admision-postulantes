<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Pago - Admisión FICCT</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-slate-900 text-slate-100 font-sans antialiased flex h-screen w-screen items-center justify-center p-4">

    <div class="w-full max-w-md p-8 space-y-6 bg-slate-800/50 backdrop-blur-md rounded-2xl border border-slate-700/50 shadow-xl text-center">

        <!-- Cargando -->
        <div id="estado-cargando">
            <div class="mx-auto h-12 w-12 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400 mb-4">
                <i data-lucide="loader" class="h-6 w-6 animate-spin"></i>
            </div>
            <h2 class="text-lg font-bold text-white">Verificando tu pago...</h2>
            <p class="text-sm text-slate-400 mt-1">Esto solo tomará un momento.</p>
        </div>

        <!-- Pagado -->
        <div id="estado-pagado" class="hidden">
            <div class="mx-auto h-14 w-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mb-4">
                <i data-lucide="check-circle-2" class="h-7 w-7"></i>
            </div>
            <h2 class="text-xl font-bold text-white">¡Pago confirmado!</h2>
            <p class="text-sm text-slate-400 mt-1">Tu matrícula de <span id="monto-pagado" class="font-semibold text-emerald-400">Bs 350.00</span> fue registrada correctamente.</p>
            <a href="/login" class="inline-flex items-center gap-2 mt-6 py-2.5 px-5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-medium rounded-xl text-sm transition-all">
                Ir a iniciar sesión
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </a>
        </div>

        <!-- Pendiente / no pagado -->
        <div id="estado-pendiente" class="hidden">
            <div class="mx-auto h-14 w-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 mb-4">
                <i data-lucide="clock" class="h-7 w-7"></i>
            </div>
            <h2 class="text-xl font-bold text-white">Pago pendiente</h2>
            <p class="text-sm text-slate-400 mt-1">Aún no detectamos la confirmación de tu pago. Si ya pagaste, espera unos segundos y vuelve a intentar, o realiza el pago más tarde desde tu portal.</p>
            <a href="/login" class="inline-flex items-center gap-2 mt-6 py-2.5 px-5 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl text-sm transition-all">
                Ir a iniciar sesión
            </a>
        </div>

        <!-- Cancelado -->
        <div id="estado-cancelado" class="hidden">
            <div class="mx-auto h-14 w-14 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 mb-4">
                <i data-lucide="x-circle" class="h-7 w-7"></i>
            </div>
            <h2 class="text-xl font-bold text-white">Pago cancelado</h2>
            <p class="text-sm text-slate-400 mt-1">No se realizó ningún cargo. Puedes pagar tu matrícula más tarde desde tu portal de postulante.</p>
            <a href="/login" class="inline-flex items-center gap-2 mt-6 py-2.5 px-5 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl text-sm transition-all">
                Ir a iniciar sesión
            </a>
        </div>

        <!-- Error -->
        <div id="estado-error" class="hidden">
            <div class="mx-auto h-14 w-14 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 mb-4">
                <i data-lucide="alert-triangle" class="h-7 w-7"></i>
            </div>
            <h2 class="text-xl font-bold text-white">No se pudo verificar el pago</h2>
            <p id="error-msg" class="text-sm text-slate-400 mt-1">Ocurrió un error inesperado.</p>
            <a href="/login" class="inline-flex items-center gap-2 mt-6 py-2.5 px-5 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl text-sm transition-all">
                Ir a iniciar sesión
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();

        const API_BASE_URL = window.location.origin;

        function mostrar(id) {
            ['estado-cargando', 'estado-pagado', 'estado-pendiente', 'estado-cancelado', 'estado-error']
                .forEach(s => document.getElementById(s).classList.add('hidden'));
            document.getElementById(id).classList.remove('hidden');
            lucide.createIcons();
        }

        async function verificar() {
            const params = new URLSearchParams(window.location.search);
            const sessionId = params.get('session_id');
            const status = params.get('status');

            // El usuario canceló el pago en Stripe (cancel_url)
            if (status === 'cancelado' && !sessionId) {
                mostrar('estado-cancelado');
                return;
            }

            if (!sessionId) {
                mostrar('estado-error');
                document.getElementById('error-msg').textContent = 'No se proporcionó información del pago.';
                return;
            }

            try {
                const res = await fetch(`${API_BASE_URL}/api/v1/public/pagos/estado/${encodeURIComponent(sessionId)}`, {
                    headers: { 'Accept': 'application/json' },
                });
                const result = await res.json();

                if (!result.success) throw new Error(result.message || 'Error al verificar el pago.');

                const { payment_status, estado_pago, monto } = result.data;

                if (payment_status === 'paid' || estado_pago === 'APROBADO') {
                    if (monto != null) {
                        document.getElementById('monto-pagado').textContent = `Bs ${Number(monto).toFixed(2)}`;
                    }
                    mostrar('estado-pagado');
                } else {
                    mostrar('estado-pendiente');
                }

            } catch (err) {
                mostrar('estado-error');
                document.getElementById('error-msg').textContent = err.message;
            }
        }

        verificar();
    </script>
</body>

</html>
