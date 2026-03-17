<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Electivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'sector_id',
        'nombre',
        'descripcion_breve',
        'pdf_path',
        'nivel_aplicacion',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function area()
    {
        return $this->hasOneThrough(Area::class, Sector::class, 'id', 'id', 'sector_id', 'area_id');
    }

    public function selecciones()
    {
        return $this->hasMany(SeleccionElectivo::class);
    }
}
