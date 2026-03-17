<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeleccionElectivo extends Model
{
    use HasFactory;

    protected $table = 'seleccion_electivos';

    protected $fillable = [
        'postulacion_alumno_id',
        'electivo_id',
    ];

    public function postulacionAlumno()
    {
        return $this->belongsTo(PostulacionAlumno::class);
    }

    public function electivo()
    {
        return $this->belongsTo(Electivo::class);
    }
}
