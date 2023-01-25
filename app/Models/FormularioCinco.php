<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioCinco extends Model
{
    use HasFactory;
    protected $table = "formulario_cinco";

    protected $fillable = ['memoria_id', "fecha_registro"];

    protected $with = ["memoria"];
    protected $appends = ["estado_aprobado"];
    
    public function memoria()
    {
        return $this->belongsTo(MemoriaCalculo::class, 'memoria_id');
    }

    public function getEstadoAprobadoAttribute()
    {
        $configuracion_modulos = ConfiguracionModulo::where("modulo", "APROBAR FORMULARIOS")->get()->first();
        return $configuracion_modulos->editar === 1 ? "APROBADO" : "PENDIENTE";
    }
}
