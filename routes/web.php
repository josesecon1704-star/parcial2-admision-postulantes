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
Route::get('/prueba2', function () {
    return view('layouts.admin3');
});

// Ruta 3: Portal del Postulante (postulante.blade.php)
Route::get('/portal-postulante', function () {
    return view('layouts.postulante');
});

// Ruta 4: Portal del Docente (docente.blade.php)
Route::get('/portal-docente', function () {
    return view('layouts.docente');
});

// Redirección por defecto
Route::get('/', function () {
    return redirect('/login');
});