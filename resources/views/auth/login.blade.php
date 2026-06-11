<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admisión FICCT - Iniciar Sesión</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-900 flex h-screen w-screen items-center justify-center font-sans antialiased text-slate-200">

    <div class="w-full max-w-md p-8 space-y-6 bg-slate-800/50 backdrop-blur-md rounded-2xl border border-slate-700/50 shadow-xl">

        <div class="text-center space-y-2">
            <div class="mx-auto h-12 w-12 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                <i data-lucide="graduation-cap" class="h-6 w-6"></i>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-white">Admisión FICCT</h2>
            <p class="text-sm text-slate-400">Introduce tus credenciales para acceder</p>
        </div>

        <div id="errorAlert" class="hidden p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-sm text-red-400 flex items-start gap-3">
            <i data-lucide="alert-circle" class="h-5 w-5 shrink-0 mt-0.5"></i>
            <span id="errorMessage">Usuario o contraseña incorrectos.</span>
        </div>

        <form id="loginForm" class="space-y-4">
            <div class="space-y-1.5">
                <label for="email" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Correo Electrónico</label>
                <div class="relative flex items-center">
                    <i data-lucide="mail" class="absolute left-3.5 h-5 w-5 text-slate-500"></i>
                    <input type="email" id="email" required placeholder="admin@ficct.edu.bo"
                        class="w-full pl-11 pr-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="password" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Contraseña</label>
                <div class="relative flex items-center">
                    <i data-lucide="lock" class="absolute left-3.5 h-5 w-5 text-slate-500"></i>
                    <input type="password" id="password" required placeholder="••••••••"
                        class="w-full pl-11 pr-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm">
                </div>
                <p class="text-[11px] text-slate-500 pl-1">Si eres postulante, tu contraseña es tu Cédula de Identidad (CI).</p>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-medium rounded-xl shadow-lg shadow-blue-500/20 transition-all text-sm flex items-center justify-center gap-2 cursor-pointer">
                <span>Ingresar al Sistema</span>
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </button>
        </form>
    </div>

    <script>
    lucide.createIcons();

    const loginForm = document.getElementById('loginForm');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');
    const submitBtn = document.getElementById('submitBtn');

    window.addEventListener('pageshow', () => {
        loginForm.reset();
        errorAlert.classList.add('hidden');
    });

    loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorAlert.classList.add('hidden');

    const emailValue = document.getElementById('email').value.trim();
    const passwordValue = document.getElementById('password').value;

    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span>Autenticando...</span>`;

    try {
        const baseUrl = window.location.origin;

        const response = await fetch(`${baseUrl}/api/v1/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                txt_email: emailValue,
                txt_password: passwordValue
            })
        });

        const result = await response.json();
        console.log("Respuesta de la API de la FICCT:", result);

        if (response.ok && result.success) {

            const data = result.data || {};

            // ── EXTRACCIÓN ULTRA PRECISA DEL TOKEN ANIDADO ──
            let tokenReal = null;

            if (data.token) {
                // buildTokenResponse() devuelve { access_token, token_type, expires_in }
                tokenReal = data.token.access_token || data.token;
            }

            if (!tokenReal || typeof tokenReal !== 'string') {
                throw new Error("Estructura de token ilegible por el cliente web.");
            }

            // Guardamos el token puro (ej: eyJhbG...)
            localStorage.setItem('token', tokenReal);

            // ── REDIRECCIÓN SEGÚN TIPO DE USUARIO ──────────
            if (data.tipo === 'postulante') {
                // Postulante: NO está en tbl_usuario.
                // Guardamos su id para que postulante.blade.php
                // pueda usarlo si lo necesita (aunque los endpoints
                // /api/v1/postulante/* ya resuelven todo por el JWT).
                localStorage.setItem('id_postulante', data.postulante.id_postulante);
                localStorage.removeItem('rol');

                loginForm.reset();
                window.location.href = '/portal-postulante';

            } else {
                // Personal administrativo: ADMINISTRADOR / SECRETARIA / DOCENTE
                localStorage.setItem('rol', data.usuario.rol);
                localStorage.removeItem('id_postulante');

                loginForm.reset();

                if (data.usuario.rol === 'DOCENTE') {
                    window.location.href = '/portal-docente';
                } else {
                    window.location.href = '/prueba2';
                }
            }

        } else {
            throw new Error(result.message || 'Las credenciales no coinciden.');
        }

    } catch (error) {
        errorMessage.textContent = error.message;
        errorAlert.classList.remove('hidden');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<span>Ingresar al Sistema</span><i data-lucide="arrow-right" class="h-4 w-4"></i>`;
        lucide.createIcons();
    }

    });
</script>
</body>
</html>