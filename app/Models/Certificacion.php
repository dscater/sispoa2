<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "formulario_id", "mo_id", "mod_id", "cantidad_usar", "presupuesto_usarse", "archivo",
        "correlativo", "solicitante_id", "superior_id", "inicio", "final", "personal_designado", "departamento", "municipio",
        "estado", "fecha_registro", "anulado"
    ];

    protected $with = ["formulario", "memoria_operacion"];

    public function formulario()
    {
        return $this->belongsTo(FormularioCuatro::class, 'formulario_id');
    }

    public function memoria_operacion()
    {
        return $this->belongsTo(MemoriaOperacion::class, 'mo_id');
    }

    public function memoria_operacion_detalle()
    {
        return $this->belongsTo(MemoriaOperacionDetalle::class, 'mod_id');
    }

    public function personal_designado()
    {
        return $this->belongsTo(Personal::class, 'personal_designado');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function superior()
    {
        return $this->belongsTo(User::class, 'superior_id');
    }
}
