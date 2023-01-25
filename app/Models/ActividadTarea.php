<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadTarea extends Model
{
    use HasFactory;

    protected $fillable = ['fco_id', 'detalle_operacion_id', 'lugar_responsable_id',];

    protected $appends = ["descripcion"];
    protected $with = ["detalle_operacion", "partidas"];

    public function getDescripcionAttribute()
    {
        $detalle_operacion = DetalleOperacion::find($this->detalle_operacion_id);
        return $detalle_operacion->actividad_tarea;
    }

    public function lugar_responsable()
    {
        return $this->belongsTo(LugarResponsable::class, 'lugar_responsable_id');
    }

    public function detalle_operacion()
    {
        return $this->belongsTo(DetalleOperacion::class, 'detalle_operacion_id');
    }

    public function partidas()
    {
        return $this->hasMany(Partida::class, 'actividad_tarea_id');
    }
}
