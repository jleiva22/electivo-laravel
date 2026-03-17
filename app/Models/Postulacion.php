<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    use HasFactory;

    protected $table = 'postulaciones';

    protected $fillable = [
        'descripcion',
        'estado',
        'max_total_electivos',
        'fecha_inicio',
        'fecha_termino',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_termino' => 'datetime',
    ];

    public function reglasAreas()
    {
        return $this->hasMany(PostulacionReglaArea::class);
    }

    public function alumnos()
    {
        return $this->hasMany(PostulacionAlumno::class);
    }
}
