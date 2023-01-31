<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetalleFormulario extends Model
{
    use HasFactory;

    protected $fillable = [
        'formulario_id', 'fecha_registro',
    ];

    protected $with = ["formulario", "operacions"];
    protected $appends = ["estado_aprobado", "sw_aprobado"];

    public function formulario()
    {
        return $this->belongsTo(FormularioCuatro::class, 'formulario_id');
    }

    public function operacions()
    {
        return $this->hasMany(Operacion::class, 'detalle_formulario_id');
    }

    public function getEstadoAprobadoAttribute()
    {
        $aprobacion_formulario = Aprobacion::where("unidad_id", Auth::user()->unidad_id)->get()->first();
        if (!$aprobacion_formulario) {
            $aprobacion_formulario = Aprobacion::create([
                "unidad_id" => Auth::user()->unidad_id,
                "estado" => 0,
            ]);
        }

        return $aprobacion_formulario->estado === 1 ? "APROBADO" : "PENDIENTE";
    }
    public function getSwAprobadoAttribute()
    {
        $aprobacion_formulario = Aprobacion::where("unidad_id", Auth::user()->unidad_id)->get()->first();
        if (!$aprobacion_formulario) {
            $aprobacion_formulario = Aprobacion::create([
                "unidad_id" => Auth::user()->unidad_id,
                "estado" => 0,
            ]);
        }
        return $aprobacion_formulario->estado;
    }
}
