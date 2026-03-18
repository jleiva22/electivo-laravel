<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        return response()->json(Area::all());
    }

    public function show(Area $area)
    {
        return response()->json($area);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:areas,nombre'],
        ]);

        $area = Area::create($data);

        return response()->json($area, 201);
    }

    public function update(Request $request, Area $area)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', "unique:areas,nombre,{$area->id}"],
        ]);

        $area->update($data);

        return response()->json($area);
    }

    public function destroy(Area $area)
    {
        // Check if there are related sectors/electivos
        if ($area->sectores()->count() > 0) {
            return response()->json(['message' => 'No se puede eliminar el área porque tiene sectores asociados.'], 422);
        }

        $area->delete();

        return response()->json(['message' => 'Área eliminada']);
    }
}
