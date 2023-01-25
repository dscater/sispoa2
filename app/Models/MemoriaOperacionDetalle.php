<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoriaOperacionDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "memoria_operacion_id", "ue", "prog", "act", "lugar", "responsable", "partida_id",
        "partida", "descripcion", "nro", "descripcion_detallada", "cantidad", "unidad",
        "costo", "total", "justificacion", "ene", "feb", "mar", "abr", "may", "jun", "jul",
        "ago", "sep", "oct", "nov", "dic", "total_actividad",
    ];

    protected $appends = ["presupuesto", "saldo"];
    public function getPresupuestoAttribute()
    {
        return (float)$this->cantidad * (float)$this->costo;
    }

    public function getSaldoAttribute()
    {
        $total_usado = Certificacion::where('mo_id', $this->memoria_operacion_id)
            ->where('mod_id', $this->id)
            ->sum('presupuesto_usarse');
        $saldo = (float) $this->total - (float) $total_usado;
        return number_format($saldo, 2, '.', '');
    }

    public function memoria_operacion()
    {
        return $this->belongsTo(MemoriaOperacion::class, 'memoria_operacion_id');
    }

    public function m_partida()
    {
        return $this->belongsTo(Partida::class, 'partida_id');
    }
}
