<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LugarResponsable extends Model
{
    use HasFactory;

    protected $fillable = [
        'fco_id', 'lugar', 'responsable',
    ];

    protected $with = ["partidas", "actividad_tareas"];

    public function actividad_tareas()
    {
        return $this->hasMany(ActividadTarea::class, 'lugar_responsable_id');
    }

    public function partidas()
    {
        return $this->hasMany(Partida::class, 'lugar_responsable_id');
    }
}
