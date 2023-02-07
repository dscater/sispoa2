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
        'codigo_pei', 'objetivo_estrategico',
        'resultado_institucional', 'indicador', 'codigo_poa',
        'accion_corto', 'indicador_proceso', 'linea_base',
        'meta', 'presupuesto', 'ponderacion',
        'unidad_id', 'fecha_registro'
    ];

    protected $with = ["unidad"];

    protected $appends = ["estado_aprobado", "sw_aprobado", "codigo_pei1", "objetivo_estrategico1", "codigo_pei2", "objetivo_estrategico2", "codigo_pei3", "objetivo_estrategico3"];

    public function getCodigoPei1Attribute()
    {
        try {
            $array = explode(" | ", $this->codigo_pei);
            return isset(explode("-", $array[0])[0]) ? explode("-", $array[0])[0] : "";
        } catch (\Exception $e) {
            return $this->codigo_pei;
        }
    }
    public function getObjetivoEstrategico1Attribute()
    {
        try {
            $array = explode(" | ", $this->codigo_pei);
            return isset(explode("-", $array[0])[1]) ? explode("-", $array[0])[1] : "";
        } catch (\Exception $e) {
            return "";
        }
    }

    public function getCodigoPei2Attribute()
    {
        try {
            $array = explode(" | ", $this->codigo_pei);
            return isset(explode("-", $array[1])[0]) ? explode("-", $array[1])[0] : "";
        } catch (\Exception $e) {
            return "";
        }
    }
    public function getObjetivoEstrategico2Attribute()
    {
        try {
            $array = explode(" | ", $this->codigo_pei);
            return isset(explode("-", $array[1])[1]) ? explode("-", $array[1])[1] : "";
        } catch (\Exception $e) {
            return "";
        }
    }

    public function getCodigoPei3Attribute()
    {
        try {
            $array = explode(" | ", $this->codigo_pei);
            return isset(explode("-", $array[2])[0]) ? explode("-", $array[2])[0] : "";
        } catch (\Exception $e) {
            return "";
        }
    }
    public function getObjetivoEstrategico3Attribute()
    {
        try {
            $array = explode(" | ", $this->codigo_pei);
            return isset(explode("-", $array[2])[1]) ? explode("-", $array[2])[1] : "";
        } catch (\Exception $e) {
            return "";
        }
    }

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
        $aprobacion_formulario = Aprobacion::where("unidad_id", $this->unidad_id)->get()->first();
        if (!$aprobacion_formulario) {
            $aprobacion_formulario = Aprobacion::create([
                "unidad_id" => $this->unidad_id,
                "estado" => 0,
            ]);
        }

        return $aprobacion_formulario->estado === 1 ? "APROBADO" : "PENDIENTE";
    }
    public function getSwAprobadoAttribute()
    {
        $aprobacion_formulario = Aprobacion::where("unidad_id", $this->unidad_id)->get()->first();
        if (!$aprobacion_formulario) {
            $aprobacion_formulario = Aprobacion::create([
                "unidad_id" => $this->unidad_id,
                "estado" => 0,
            ]);
        }
        return $aprobacion_formulario->estado;
    }
}
