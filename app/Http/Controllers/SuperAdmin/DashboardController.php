<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Electivo;
use App\Models\Postulacion;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('superadmin.dashboard', [
            'totalUsers' => User::count(),
            'totalAlumnos' => Alumno::count(),
            'totalElectivos' => Electivo::count(),
            'totalPostulaciones' => Postulacion::count(),
            'recentUsers' => User::orderBy('created_at', 'desc')->take(20)->get(),
        ]);
    }
}
