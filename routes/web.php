<?php
use Illuminate\Support\Facades\Route;

// Ruta 1: Pantalla de Login
Route::get('/login', function () {
    return view('auth.login');
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

// Redirección por defecto
Route::get('/', function () {
    return redirect('/login');
});