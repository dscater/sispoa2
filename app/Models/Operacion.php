<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "detalle_formulario_id", 'subdireccion_id', "codigo_operacion", "operacion",
        "ponderacion", "resultado_esperado", "medios_verificacion",
    ];

    protected $with = ["detalle_operaciones"];
    protected $appends = ["total_porcentaje"];

    public function getTotalPorcentajeAttribute()
    {
        $total = DetalleOperacion::where("operacion_id", $this->id)->sum("ponderacion");
        return $total;
    }

    public function detalle_operaciones()
    {
        return $this->hasMany(DetalleOperacion::class, 'operacion_id');
    }

    public function detalle_formulario()
    {
        return $this->belongsTo(DetalleFormulario::class, 'detalle_formulario_id');
    }

    public function subdireccion()
    {
        return $this->belongsTo(Subdireccion::class, 'subdireccion_id');
    }
}
