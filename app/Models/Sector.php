<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'nombre',
        'descripcion',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function electivos()
    {
        return $this->hasMany(Electivo::class);
    }
}
