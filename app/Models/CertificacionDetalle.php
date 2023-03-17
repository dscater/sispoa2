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
    
    public function memoria_operacion()
    {
        return $this->belongsTo(MemoriaOperacion::class, 'mo_id');
    }

    public function memoria_operacion_detalle()
    {
        return $this->belongsTo(MemoriaOperacionDetalle::class, 'mod_id');
    }
}
