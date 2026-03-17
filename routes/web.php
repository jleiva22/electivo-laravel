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

// Frontend de la app (consume APIs de la misma app)
Route::get('/alumno', function () {
    return view('alumno');
});

Route::get('/admin', function () {
    return view('admin');
});

// API/Controladores de administración
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('postulaciones', [\App\Http\Controllers\Admin\PostulacionController::class, 'index']);
    Route::get('postulaciones/{postulacion}', [\App\Http\Controllers\Admin\PostulacionController::class, 'show']);
    Route::post('postulaciones', [\App\Http\Controllers\Admin\PostulacionController::class, 'store']);
    Route::put('postulaciones/{postulacion}', [\App\Http\Controllers\Admin\PostulacionController::class, 'update']);
    Route::post('postulaciones/{postulacion}/close', [\App\Http\Controllers\Admin\PostulacionController::class, 'close']);
    Route::get('areas', [\App\Http\Controllers\Admin\PostulacionController::class, 'getAreas']);
});

// API/Controladores alumno
Route::prefix('alumno')->name('alumno.')->group(function () {
    Route::get('postulacion', [\App\Http\Controllers\Alumno\PostulacionController::class, 'index']);
    Route::post('postulacion/seleccionar', [\App\Http\Controllers\Alumno\PostulacionController::class, 'selectElectivo']);
    Route::post('postulacion/remover', [\App\Http\Controllers\Alumno\PostulacionController::class, 'removeElectivo']);
    Route::post('postulacion/finalizar', [\App\Http\Controllers\Alumno\PostulacionController::class, 'finalizar']);
});
