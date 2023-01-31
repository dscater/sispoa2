<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FormularioCuatro extends Model
{
    use HasFactory;

    protected $table = "formulario_cuatro";

    protected $fillable = [
        'codigo_pei', 'resultado_institucional', 'indicador', 'codigo_poa',
        'accion_corto', 'indicador_proceso', 'linea_base', 'meta', 'presupuesto', 'ponderacion',
        'unidad_id', 'fecha_registro'
    ];

    protected $with = ["unidad"];

    protected $appends = ["estado_aprobado", "sw_aprobado"];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function detalle_formulario()
    {
        return $this->hasOne(DetalleFormulario::class, 'formulario_id');
    }

    public function memoria_calculo()
    {
        return $this->hasOne(MemoriaCalculo::class, 'formulario_id');
    }

    public function certificacions()
    {
        return $this->hasMany(Certificacion::class, 'formulario_id');
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
