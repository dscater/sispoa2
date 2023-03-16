<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "formulario_id",
        // "mo_id",
        // "mod_id",
        // "total_cantidad",
        // "cantidad_usar",
        // "saldo_cantidad",
        // "total",
        // "presupuesto_usarse",
        // "saldo_total",
        "archivo",
        "correlativo", "solicitante_id", "superior_id",
        "inicio", "final",
        "personal_designado",
        "departamento", "municipio",
        "estado", "fecha_registro", "anulado"
    ];

    protected $with = ["formulario"];

    public function certificacion_detalles()
    {
        return $this->hasMany(CertificacionDetalle::class, 'certificacion_id');
    }

    public function formulario()
    {
        return $this->belongsTo(FormularioCuatro::class, 'formulario_id');
    }

    public function o_personal_designado()
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
