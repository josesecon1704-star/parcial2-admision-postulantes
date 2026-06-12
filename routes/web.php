<?php
use Illuminate\Support\Facades\Route;

// Ruta 1: Pantalla de Login
Route::get('/login', function () {
    return view('auth.login');
});

// Ruta 1b: Registro público de postulante
Route::get('/registro-postulante', function () {
    return view('auth.registroPostulante');
});

// Ruta 1c: Página de retorno de Stripe Checkout (success_url / cancel_url)
Route::get('/pago-resultado', function () {
    return view('auth.pagoResultado');
});

// Ruta 2: para administradores y secretarias
Route::get('/layouts', function () {
    return view('layouts.admin');
});

Route::get('/prueba', function () {
    return view('layouts.admin2');
});
Route::get('/prueba2', function () {
    return view('layouts.admin3');
});

// Ruta 3: Portal del Postulante (postulante.blade.php)
// El control de acceso real ocurre en el frontend (token + id_postulante
// en localStorage) y en la API (middleware role:POSTULANTE).
Route::get('/portal-postulante', function () {
    return view('layouts.postulante');
});

// Ruta 4: Portal del Docente (docente.blade.php)
// El control de acceso real ocurre en el frontend (token en localStorage)
// y en la API (middleware role:DOCENTE).
Route::get('/portal-docente', function () {
    return view('layouts.docente');
});

// Redirección por defecto
Route::get('/', function () {
    return redirect('/login');
});