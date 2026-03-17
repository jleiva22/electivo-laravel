<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Postulacion;
use App\Models\PostulacionReglaArea;
use Illuminate\Http\Request;

class PostulacionController extends Controller
{
    public function index()
    {
        $postulaciones = Postulacion::with('reglasAreas.area')->get();

        return response()->json(['data' => $postulaciones]);
    }

    public function show(Postulacion $postulacion)
    {
        $postulacion->load('reglasAreas.area');

        return response()->json(['data' => $postulacion]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descripcion' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'in:activa,cerrada'],
            'max_total_electivos' => ['required', 'integer', 'min:1'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_termino' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'reglas' => ['nullable', 'array'],
            'reglas.*.area_id' => ['required', 'exists:areas,id'],
            'reglas.*.max_permitido' => ['required', 'integer', 'min:1'],
        ]);

        $postulacion = Postulacion::create($data);

        if (!empty($data['reglas'])) {
            foreach ($data['reglas'] as $regla) {
                $postulacion->reglasAreas()->create($regla);
            }
        }

        return response()->json(['data' => $postulacion], 201);
    }

    public function update(Request $request, Postulacion $postulacion)
    {
        $data = $request->validate([
            'descripcion' => ['sometimes', 'required', 'string', 'max:255'],
            'estado' => ['sometimes', 'required', 'in:activa,cerrada'],
            'max_total_electivos' => ['sometimes', 'required', 'integer', 'min:1'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_termino' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'reglas' => ['nullable', 'array'],
            'reglas.*.id' => ['nullable', 'exists:postulacion_reglas_areas,id'],
            'reglas.*.area_id' => ['required', 'exists:areas,id'],
            'reglas.*.max_permitido' => ['required', 'integer', 'min:1'],
        ]);

        $postulacion->update($data);

        if (isset($data['reglas'])) {
            foreach ($data['reglas'] as $regla) {
                if (!empty($regla['id'])) {
                    $rule = $postulacion->reglasAreas()->find($regla['id']);
                    if ($rule) {
                        $rule->update($regla);
                    }
                    continue;
                }

                $postulacion->reglasAreas()->create($regla);
            }
        }

        return response()->json(['data' => $postulacion]);
    }

    public function close(Postulacion $postulacion)
    {
        $postulacion->update(['estado' => 'cerrada']);

        return response()->json(['data' => $postulacion]);
    }

    public function getAreas()
    {
        return response()->json(['data' => Area::all()]);
    }
}
