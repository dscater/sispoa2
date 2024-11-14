<?php

namespace App\Models;

use App\Http\Controllers\FormularioCuatroController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MemoriaCalculo extends Model
{
    use HasFactory;

    protected $fillable = [
        "formulario_id",
        "formulario_seleccionado",
        "total_actividades",
        "total_ene",
        "total_feb",
        "total_mar",
        "total_abr",
        "total_may",
        "total_jun",
        "total_jul",
        "total_ago",
        "total_sep",
        "total_oct",
        "total_nov",
        "total_dic",
        "total_final",
        "fecha_registro",
        "status",
    ];

    protected $with = ["formulario", "operacions"];
    protected $appends = ["estado_aprobado", "sw_aprobado", "pei_text", "poa_text"];

    public function getPeiTextAttribute()
    {
        return FormularioCuatroController::getPeiIndividual($this->formulario_seleccionado);
    }

    public function getPoaTextAttribute()
    {
        return FormularioCuatroController::getPoaIndividual($this->formulario_seleccionado);
    }

    public function formulario()
    {
        return $this->belongsTo(FormularioCuatro::class, 'formulario_id');
    }

    public function operacions()
    {
        return $this->hasMany(MemoriaOperacion::class, 'memoria_id');
    }

    public function formulario_cinco()
    {
        return $this->hasOne(FormularioCinco::class, 'memoria_id');
    }

    public function getEstadoAprobadoAttribute()
    {
        $aprobacion_formulario = Aprobacion::where("unidad_id", $this->formulario->unidad_id)->get()->first();
        if (!$aprobacion_formulario) {
            $aprobacion_formulario = Aprobacion::create([
                "unidad_id" => $this->formulario->unidad_id,
                "estado" => 0,
            ]);
        }

        return $aprobacion_formulario->estado === 1 ? "APROBADO" : "PENDIENTE";
    }

    public function getSwAprobadoAttribute()
    {
        $aprobacion_formulario = Aprobacion::where("unidad_id", $this->formulario->unidad_id)->get()->first();
        if (!$aprobacion_formulario) {
            $aprobacion_formulario = Aprobacion::create([
                "unidad_id" => $this->formulario->unidad_id,
                "estado" => 0,
            ]);
        }
        return $aprobacion_formulario->estado;
    }
}
