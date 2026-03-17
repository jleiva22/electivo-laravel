<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostulacionReglaArea extends Model
{
    use HasFactory;

    protected $table = 'postulacion_reglas_areas';

    protected $fillable = [
        'postulacion_id',
        'area_id',
        'max_permitido',
    ];

    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
