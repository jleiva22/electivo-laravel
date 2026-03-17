<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Mockup de diseño de postulación a electivos (UI de alumno)
Route::get('/postulacion-mockup', function () {
    return view('postulacion-mockup');
});

// Mockup de interfaz de administración de postulaciones
Route::get('/admin-mockup', function () {
    return view('admin-mockup');
});
