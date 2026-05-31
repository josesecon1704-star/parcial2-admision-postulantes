<?php
use Illuminate\Support\Facades\Route;

// Ruta 1: Pantalla de Login
Route::get('/login', function () {
    return view('auth.login');
});

// Ruta 2: La SPA Única (Contiene Dashboard, Postulantes, Exámenes, Grupos y Reportes)
Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
});

// Redirección por defecto para comodidad en el examen
Route::get('/', function () {
    return redirect('/login');
});