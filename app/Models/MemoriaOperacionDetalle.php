<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MemoriaOperacionDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "memoria_operacion_id", "ue", "prog", "act", "lugar", "responsable", "partida_id",
        "partida", "descripcion", "nro", "descripcion_detallada", "cantidad", "unidad",
        "costo", "total", "justificacion", "ene", "feb", "mar", "abr", "may", "jun", "jul",
        "ago", "sep", "oct", "nov", "dic", "total_actividad",
    ];

    protected $appends = ["presupuesto", "saldo", "saldo_aux", "saldo_cantidad", "saldo_cantidad_aux", "cod_actividad_txt", "actividad_txt"];

    public function getCodActividadTxtAttribute()
    {
        $index_detalle = 0;
        $memoria_operacion = MemoriaOperacion::find($this->memoria_operacion_id);
        $value = "";
        if($memoria_operacion && $memoria_operacion->operacion_id){
            $operacion_formulario = Operacion::find($memoria_operacion->operacion_id);
    
            $index_detalle = self::getIndexDetalle($this->id, $memoria_operacion);
            if ($operacion_formulario) {
                // Accediendo a detalle formulario
                if (isset($operacion_formulario->detalle_operaciones[$index_detalle])) {
                    $value = $operacion_formulario->detalle_operaciones[$index_detalle]->codigo_tarea;
                }
            }
        }
        return $value;
    }

    public function getActividadTxtAttribute()
    {
        $index_detalle = 0;
        $memoria_operacion = MemoriaOperacion::find($this->memoria_operacion_id);
        $value = "";
        if($memoria_operacion && $memoria_operacion->operacion_id){
            $operacion_formulario = Operacion::find($memoria_operacion->operacion_id);
    
            $index_detalle = self::getIndexDetalle($this->id, $memoria_operacion);
            if ($operacion_formulario) {
                // Accediendo a detalle formulario
                if (isset($operacion_formulario->detalle_operaciones[$index_detalle])) {
                    $value = $operacion_formulario->detalle_operaciones[$index_detalle]->actividad_tarea;
                }
            }
        }
        return $value;
    }

    public static function getIndexDetalle($id, $memoria_operacion)
    {
        $index_detalle = 0;
        foreach ($memoria_operacion->memoria_operacion_detalles as $key => $mod) {
            if ($id == $mod->id) {
                $index_detalle = $key;
                break;
            }
        }

        return $index_detalle;
    }

    public function getPresupuestoAttribute()
    {
        return (float)$this->cantidad * (float)$this->costo;
    }

    public function getSaldoAuxAttribute()
    {
        $total_usado = CertificacionDetalle::select("certificacion_detalles.*")
            ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
            ->where('certificacions.mo_id', $this->memoria_operacion_id)
            ->where('mod_id', $this->id)
            ->where("certificacions.anulado", 0)
            ->sum('certificacion_detalles.presupuesto_usarse');


        $saldo = (float) $this->total - (float) $total_usado;


        return number_format($saldo, 2, '.', '');
    }

    public function getSaldoAttribute()
    {
        $total_usado = CertificacionDetalle::select("certificacion_detalles.*")
            ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
            ->where('certificacions.mo_id', $this->memoria_operacion_id)
            ->where('mod_id', $this->id)
            ->where("certificacions.anulado", 0)
            ->sum('certificacion_detalles.presupuesto_usarse');


        $saldo = (float) $this->total - (float) $total_usado;


        return number_format($saldo, 2, '.', '');
    }

    public function getSaldoCantidadAuxAttribute()
    {
        $total_usado = CertificacionDetalle::select("certificacion_detalles.*")
            ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
            ->where('certificacions.mo_id', $this->memoria_operacion_id)
            ->where('mod_id', $this->id)
            ->where("certificacions.anulado", 0)
            ->sum('certificacion_detalles.cantidad_usar');
        $saldo = (float) $this->cantidad - (float) $total_usado;
        return (float)(number_format($saldo, 2, ".", ""));
    }

    public function getSaldoCantidadAttribute()
    {
        $total_usado = CertificacionDetalle::select("certificacion_detalles.*")
            ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
            ->where('certificacions.mo_id', $this->memoria_operacion_id)
            ->where('mod_id', $this->id)
            ->where("certificacions.anulado", 0)
            ->sum('certificacion_detalles.cantidad_usar');
        $saldo = (float) $this->cantidad - (float) $total_usado;
        return (float)(number_format($saldo, 2, ".", ""));
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
