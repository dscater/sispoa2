<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificacionDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "certificacion_id",
        "mo_id",
        "mod_id",
        "total_cantidad",
        "cantidad_usar",
        "saldo_cantidad",
        "total",
        "presupuesto_usarse",
        "saldo_total",
    ];


    protected $appends = ["cantidad_usar_aux", "presupuesto_usarse_aux"];

    public function getCantidadUsarAuxAttribute()
    {
        return $this->cantidad_usar;
    }

    public function getPresupuestoUsarseAuxAttribute()
    {
        return $this->presupuesto_usarse;
    }

    public function certificacion()
    {
        return $this->belongsTo(Certificacion::class, 'certificacion_id');
    }

    public function memoria_operacion()
    {
        return $this->belongsTo(MemoriaOperacion::class, 'mo_id');
    }

    public function memoria_operacion_detalle()
    {
        return $this->belongsTo(MemoriaOperacionDetalle::class, 'mod_id');
    }
}
