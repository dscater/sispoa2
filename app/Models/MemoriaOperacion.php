<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoriaOperacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "memoria_id",
        "operacion_id",
        "detalle_operacion_id",
        "ue", "prog", "act", "lugar", "responsable",
        "justificacion",
        "total_operacion",
        "fecha_registro",
    ];

    protected $appends = ["presupuesto", "descripcion_actividad", "descripcion_operacion", "codigo_operacion", "operacion_txt", "codigo_actividad"];
    protected $with = ["detalle_operacion"];

    public function getPresupuestoAttribute()
    {
        return (float)$this->cantidad * (float)$this->costo;
    }

    public function getCodigoActividadAttribute()
    {
        $detalle_operacion = DetalleOperacion::find($this->detalle_operacion_id);
        if ($detalle_operacion) {
            return $detalle_operacion->codigo_tarea;
        }
        return "";
    }

    public function getCodigoOperacionAttribute()
    {
        $operacion = Operacion::find($this->operacion_id);
        return $operacion->codigo_operacion;
    }

    public function getOperacionTxtAttribute()
    {
        $operacion = Operacion::find($this->operacion_id);
        return $operacion->operacion;
    }

    public function getDescripcionActividadAttribute()
    {
        $detalle_operacion = DetalleOperacion::find($this->detalle_operacion_id);
        if ($detalle_operacion) {
            return $detalle_operacion->actividad_tarea;
        }
        return "";
    }

    public function getDescripcionOperacionAttribute()
    {
        $operacion = Operacion::find($this->operacion_id);
        return $operacion->operacion;
    }

    public function memoria()
    {
        return $this->belongsTo(MemoriaCalculo::class, 'memoria_id');
    }

    public function operacion()
    {
        return $this->belongsTo(Operacion::class, 'operacion_id');
    }

    public function detalle_operacion()
    {
        return $this->belongsTo(DetalleOperacion::class, 'detalle_operacion_id');
    }

    public function memoria_operacion_detalles()
    {
        return $this->hasMany(MemoriaOperacionDetalle::class, 'memoria_operacion_id');
    }

    public function certificacions()
    {
        return $this->hasMany(Certificacion::class, 'mo_id');
    }

    public function setTotalOperacionAttribute($value)
    {
        $this->attributes["total_operacion"] = (float)$this->ene + (float)$this->feb + (float)$this->mar + (float)$this->abr + (float)$this->may + (float)$this->jun + (float)$this->jul + (float)$this->ago + (float)$this->sep + (float)$this->oct + (float)$this->nov + (float)$this->dic;
    }
}
