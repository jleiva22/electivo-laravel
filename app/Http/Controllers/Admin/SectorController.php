<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    public function index(Request $request)
    {
        $query = Sector::with('area');
        
        if ($areaId = $request->query('area_id')) {
            $query->where('area_id', $areaId);
        }

        return response()->json($query->get());
    }

    public function show(Sector $sector)
    {
        $sector->load('area');
        return response()->json($sector);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'area_id' => ['required', 'exists:areas,id'],
        ]);

        // Evitar sectores duplicados en la misma área
        $request->validate([
            'nombre' => ["unique:sectores,nombre,NULL,id,area_id,{$data['area_id']}"],
        ]);

        $sector = Sector::create($data);

        return response()->json($sector->load('area'), 201);
    }

    public function update(Request $request, Sector $sector)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'area_id' => ['required', 'exists:areas,id'],
        ]);

        // Validar unicidad ignorando el sector actual
        $request->validate([
            'nombre' => ["unique:sectores,nombre,{$sector->id},id,area_id,{$data['area_id']}"],
        ]);

        $sector->update($data);

        return response()->json($sector->load('area'));
    }

    public function destroy(Sector $sector)
    {
        if ($sector->electivos()->count() > 0) {
            return response()->json(['message' => 'No se puede eliminar el sector porque tiene electivos asociados.'], 422);
        }

        $sector->delete();

        return response()->json(['message' => 'Sector eliminado']);
    }
}
