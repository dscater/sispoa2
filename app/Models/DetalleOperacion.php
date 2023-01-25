<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOperacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "operacion_id", "ponderacion", "resultado_esperado", "medios_verificacion",
        "codigo_tarea", "actividad_tarea", "pt_e", "pt_f",
        "pt_m", "st_a", "st_m", "st_j", "tt_j", "tt_a", "tt_s", "ct_o",
        "ct_n", "ct_d", "inicio", "final",
    ];

    public function operacion()
    {
        return $this->belongsTo(Operacion::class, 'operacion_id');
    }
}
