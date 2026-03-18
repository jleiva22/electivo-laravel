<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rut',
        'nombre',
        'apellido',
        'curso',
        'nivel_actual',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postulaciones()
    {
        return $this->hasMany(PostulacionAlumno::class);
    }

    public function selecciones()
    {
        return $this->hasManyThrough(SeleccionElectivo::class, PostulacionAlumno::class);
    }
}
