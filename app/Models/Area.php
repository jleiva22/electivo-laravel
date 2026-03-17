<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function sectores()
    {
        return $this->hasMany(Sector::class);
    }

    public function electivos()
    {
        return $this->hasManyThrough(Electivo::class, Sector::class);
    }

    public function reglasPostulacion()
    {
        return $this->hasMany(PostulacionReglaArea::class);
    }
}
