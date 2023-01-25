<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFormulario extends Model
{
    use HasFactory;

    protected $fillable = [
        'formulario_id', 'fecha_registro',
    ];

    protected $with = ["formulario", "operacions"];
    protected $appends = ["estado_aprobado"];
    
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
        $configuracion_modulos = ConfiguracionModulo::where("modulo", "APROBAR FORMULARIOS")->get()->first();
        return $configuracion_modulos->editar === 1 ? "APROBADO" : "PENDIENTE";
    }
}
