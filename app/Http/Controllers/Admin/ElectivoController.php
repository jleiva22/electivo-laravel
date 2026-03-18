<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Electivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ElectivoController extends Controller
{
    public function index()
    {
        $electivos = Electivo::with('sector.area')->get();
        return response()->json($electivos);
    }

    public function show(Electivo $electivo)
    {
        $electivo->load('sector.area');
        return response()->json($electivo);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sector_id' => ['required', 'exists:sectores,id'],
            'nombre' => ['required', 'string', 'max:255', 'unique:electivos,nombre'],
            'descripcion_breve' => ['required', 'string'],
            'pdf_path' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // 5MB max
            'nivel_aplicacion' => ['required', 'in:3,4,comun'],
        ]);

        if ($request->hasFile('pdf_path')) {
            $path = $request->file('pdf_path')->store('electivos', 'public');
            $data['pdf_path'] = $path;
        }

        $electivo = Electivo::create($data);

        return response()->json($electivo->load('sector.area'), 201);
    }

    public function update(Request $request, Electivo $electivo)
    {
        $data = $request->validate([
            'sector_id' => ['required', 'exists:sectores,id'],
            'nombre' => ['required', 'string', 'max:255', "unique:electivos,nombre,{$electivo->id}"],
            'descripcion_breve' => ['required', 'string'],
            'pdf_path' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'nivel_aplicacion' => ['required', 'in:3,4,comun'],
            'remove_pdf' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('pdf_path')) {
            // Delete old PDF
            if ($electivo->pdf_path) {
                Storage::disk('public')->delete($electivo->pdf_path);
            }
            $path = $request->file('pdf_path')->store('electivos', 'public');
            $data['pdf_path'] = $path;
        } elseif (!empty($data['remove_pdf']) && $data['remove_pdf']) {
            if ($electivo->pdf_path) {
                Storage::disk('public')->delete($electivo->pdf_path);
            }
            $data['pdf_path'] = null;
        }

        $electivo->update($data);

        return response()->json($electivo->load('sector.area'));
    }

    public function destroy(Electivo $electivo)
    {
        if ($electivo->selecciones()->count() > 0) {
            return response()->json(['message' => 'No se puede eliminar el electivo porque ya tiene estudiantes seleccionándolo.'], 422);
        }

        if ($electivo->pdf_path) {
            Storage::disk('public')->delete($electivo->pdf_path);
        }

        $electivo->delete();

        return response()->json(['message' => 'Electivo eliminado']);
    }
}
