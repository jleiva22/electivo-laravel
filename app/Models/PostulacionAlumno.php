<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostulacionAlumno extends Model
{
    use HasFactory;

    protected $table = 'postulacion_alumnos';

    protected $fillable = [
        'postulacion_id',
        'alumno_id',
        'estado_cierre',
        'fecha_finalizacion',
    ];

    protected $casts = [
        'estado_cierre' => 'boolean',
        'fecha_finalizacion' => 'datetime',
    ];

    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class);
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function selecciones()
    {
        return $this->hasMany(SeleccionElectivo::class);
    }
}
