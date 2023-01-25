<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoriaCalculo extends Model
{
    use HasFactory;

    protected $fillable = [
        "formulario_id", "total_actividades", "total_ene", "total_feb",
        "total_mar", "total_abr", "total_may", "total_jun", "total_jul",
        "total_ago", "total_sep", "total_oct", "total_nov", "total_dic",
        "total_final", "fecha_registro",
    ];

    protected $with = ["formulario", "operacions"];
    protected $appends = ["estado_aprobado"];

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
        $configuracion_modulos = ConfiguracionModulo::where("modulo", "APROBAR FORMULARIOS")->get()->first();
        return $configuracion_modulos->editar === 1 ? "APROBADO" : "PENDIENTE";
    }
}
