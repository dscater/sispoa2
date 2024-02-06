<?php

namespace App\Models;

use App\Http\Controllers\FormularioCuatroController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetalleFormulario extends Model
{
    use HasFactory;

    protected $fillable = [
        'formulario_id', 'formulario_seleccionado', 'fecha_registro',
    ];

    protected $with = ["formulario", "operacions"];
    protected $appends = ["estado_aprobado", "sw_aprobado", "pei_text"];

    public function getPeiTextAttribute()
    {
        return FormularioCuatroController::getPeiIndividual($this->formulario_seleccionado);
    }

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
