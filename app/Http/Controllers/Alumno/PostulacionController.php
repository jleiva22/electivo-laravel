<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Electivo;
use App\Models\Postulacion;
use App\Models\PostulacionAlumno;
use App\Models\SeleccionElectivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostulacionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $alumno = $user->alumno;

        if (!$alumno) {
            return response()->json(['message' => 'Perfil de alumno no encontrado'], 404);
        }

        $postulacion = Postulacion::where('estado', 'activa')->latest('created_at')->first();

        if (!$postulacion) {
            return response()->json(['message' => 'No hay procesos activos'], 404);
        }

        $seleccion = PostulacionAlumno::with('selecciones.electivo.sector.area')
            ->where('postulacion_id', $postulacion->id)
            ->where('alumno_id', $alumno->id)
            ->first();

        $electivos = Electivo::with('sector.area')
            ->whereIn('nivel_aplicacion', ['comun', $alumno->nivel_actual])
            ->get();

        return response()->json([
            'postulacion' => $postulacion,
            'seleccion' => $seleccion,
            'electivos' => $electivos,
        ]);
    }

    public function selectElectivo(Request $request)
    {
        $request->validate([
            'electivo_id' => ['required', 'exists:electivos,id'],
        ]);

        $user = Auth::user();
        $alumno = $user->alumno;
        $electivo = Electivo::findOrFail($request->input('electivo_id'));

        $postulacion = Postulacion::where('estado', 'activa')->latest('created_at')->first();
        if (!$postulacion) {
            return response()->json(['message' => 'No hay procesos activos'], 404);
        }

        $postulacionAlumno = PostulacionAlumno::firstOrCreate([
            'postulacion_id' => $postulacion->id,
            'alumno_id' => $alumno->id,
        ]);

        if ($postulacionAlumno->estado_cierre) {
            return response()->json(['message' => 'La postulación está cerrada'], 422);
        }

        if (SeleccionElectivo::where('postulacion_alumno_id', $postulacionAlumno->id)
            ->where('electivo_id', $electivo->id)
            ->exists()) {
            return response()->json(['message' => 'Electivo ya seleccionado'], 422);
        }

        // Validar límite total
        $cantidadSeleccionada = SeleccionElectivo::where('postulacion_alumno_id', $postulacionAlumno->id)->count();
        if ($cantidadSeleccionada >= $postulacion->max_total_electivos) {
            return response()->json(['message' => 'Se alcanzó el máximo total de electivos'], 422);
        }

        // Validar límite por área
        $areaId = $electivo->sector->area->id;
        $reglaArea = $postulacion->reglasAreas()->where('area_id', $areaId)->first();
        if ($reglaArea) {
            $seleccionesPorArea = SeleccionElectivo::whereHas('electivo.sector', function ($q) use ($areaId) {
                $q->where('area_id', $areaId);
            })->where('postulacion_alumno_id', $postulacionAlumno->id)->count();

            if ($seleccionesPorArea >= $reglaArea->max_permitido) {
                return response()->json(['message' => 'Límite de electivos por área alcanzado'], 422);
            }
        }

        $seleccion = SeleccionElectivo::create([
            'postulacion_alumno_id' => $postulacionAlumno->id,
            'electivo_id' => $electivo->id,
        ]);

        return response()->json(['data' => $seleccion], 201);
    }

    public function removeElectivo(Request $request)
    {
        $request->validate([
            'seleccion_id' => ['required', 'exists:seleccion_electivos,id'],
        ]);

        $seleccion = SeleccionElectivo::findOrFail($request->input('seleccion_id'));
        $postulacionAlumno = $seleccion->postulacionAlumno;

        if ($postulacionAlumno->estado_cierre) {
            return response()->json(['message' => 'La postulación está cerrada'], 422);
        }

        $seleccion->delete();

        return response()->json(['message' => 'Electivo removido']);
    }

    public function finalizar(Request $request)
    {
        $user = Auth::user();
        $alumno = $user->alumno;

        $postulacion = Postulacion::where('estado', 'activa')->latest('created_at')->first();
        $postulacionAlumno = PostulacionAlumno::where('postulacion_id', $postulacion->id)
            ->where('alumno_id', $alumno->id)
            ->first();

        if (!$postulacionAlumno) {
            return response()->json(['message' => 'No existe postulación para este alumno'], 404);
        }

        $postulacionAlumno->update([
            'estado_cierre' => true,
            'fecha_finalizacion' => now(),
        ]);

        return response()->json(['message' => 'Postulación finalizada']);
    }
}
