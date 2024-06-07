<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOperacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "operacion_id", "ponderacion", "resultado_esperado", "medios_verificacion",
        "codigo_tarea", "actividad_tarea",
        "pt_e", "pt_f", "pt_m",
        "st_a", "st_m", "st_j",
        "tt_j", "tt_a", "tt_s",
        "ct_o", "ct_n", "ct_d",
        "pt_e_file", "pt_f_file", "pt_m_file",
        "st_a_file", "st_m_file", "st_j_file",
        "tt_j_file", "tt_a_file", "tt_s_file",
        "ct_o_file", "ct_n_file", "ct_d_file",
        "pt_e_est", "pt_f_est", "pt_m_est", // estados 0:pendiente, 1:subido, 2:revisado, 3:rechazado
        "st_a_est", "st_m_est", "st_j_est", // estados 0:pendiente, 1:subido, 2:revisado, 3:rechazado
        "tt_j_est", "tt_a_est", "tt_s_est", // estados 0:pendiente, 1:subido, 2:revisado, 3:rechazado
        "ct_o_est", "ct_n_est", "ct_d_est", // estados 0:pendiente, 1:subido, 2:revisado, 3:rechazado
        "inicio", "final",
    ];

    protected $appends = [
        "pt_e_est_t",
        "pt_f_est_t",
        "pt_m_est_t",
        "st_a_est_t",
        "st_m_est_t",
        "st_j_est_t",
        "tt_j_est_t",
        "tt_a_est_t",
        "tt_s_est_t",
        "ct_o_est_t",
        "ct_n_est_t",
        "ct_d_est_t",

        "pt_e_url",
        "pt_f_url",
        "pt_m_url",
        "st_a_url",
        "st_m_url",
        "st_j_url",
        "tt_j_url",
        "tt_a_url",
        "tt_s_url",
        "ct_o_url",
        "ct_n_url",
        "ct_d_url",
    ];

    public function getPtEEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->pt_e_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->pt_e_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->pt_e_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getPtFEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->pt_f_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->pt_f_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->pt_f_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getPtMEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->pt_m_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->pt_m_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->pt_m_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getStAEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->st_a_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->st_a_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->st_a_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getStMEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->st_m_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->st_m_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->st_m_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getStJEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->st_j_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->st_j_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->st_j_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getTtJEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->tt_j_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->tt_j_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->tt_j_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getTtAEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->tt_a_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->tt_a_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->tt_a_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getTtSEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->tt_s_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->tt_s_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->tt_s_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getCtOEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->ct_o_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->ct_o_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->ct_o_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getCtNEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->ct_n_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->ct_n_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->ct_n_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }
    public function getCtDEstTAttribute()
    {
        $estado_text = 'PENDIENTE';
        if ($this->ct_d_est == 1) {
            $estado_text = 'SUBIDO';
        }
        if ($this->ct_d_est == 2) {
            $estado_text = 'REVISADO';
        }

        if ($this->ct_d_est == 3) {
            $estado_text = 'RECHAZADO';
        }
        return $estado_text;
    }

    public function getPtEUrlAttribute()
    {
        if ($this->pt_e_file && $this->pt_e_file != '') {
            return asset("/files/" . $this->pt_e_file);
        }
        return "";
    }
    public function getPtFUrlAttribute()
    {
        if ($this->pt_f_file && $this->pt_f_file != '') {
            return asset("/files/" . $this->pt_f_file);
        }
        return "";
    }
    public function getPtMUrlAttribute()
    {
        if ($this->pt_m_file && $this->pt_m_file != '') {
            return asset("/files/" . $this->pt_m_file);
        }
        return "";
    }
    public function getStAUrlAttribute()
    {
        if ($this->st_a_file && $this->st_a_file != '') {
            return asset("/files/" . $this->st_a_file);
        }
        return "";
    }
    public function getStMUrlAttribute()
    {
        if ($this->st_m_file && $this->st_m_file != '') {
            return asset("/files/" . $this->st_m_file);
        }
        return "";
    }
    public function getStJUrlAttribute()
    {
        if ($this->st_j_file && $this->st_j_file != '') {
            return asset("/files/" . $this->st_j_file);
        }
        return "";
    }
    public function getTtJUrlAttribute()
    {
        if ($this->tt_j_file && $this->tt_j_file != '') {
            return asset("/files/" . $this->tt_j_file);
        }
        return "";
    }
    public function getTtAUrlAttribute()
    {
        if ($this->tt_a_file && $this->tt_a_file != '') {
            return asset("/files/" . $this->tt_a_file);
        }
        return "";
    }
    public function getTtSUrlAttribute()
    {
        if ($this->tt_s_file && $this->tt_s_file != '') {
            return asset("/files/" . $this->tt_s_file);
        }
        return "";
    }
    public function getCtOUrlAttribute()
    {
        if ($this->ct_o_file && $this->ct_o_file != '') {
            return asset("/files/" . $this->ct_o_file);
        }
        return "";
    }
    public function getCtNUrlAttribute()
    {
        if ($this->ct_n_file && $this->ct_n_file != '') {
            return asset("/files/" . $this->ct_n_file);
        }
        return "";
    }
    public function getCtDUrlAttribute()
    {
        if ($this->ct_d_file && $this->ct_d_file != '') {
            return asset("/files/" . $this->ct_d_file);
        }
        return "";
    }

    public function operacion()
    {
        return $this->belongsTo(Operacion::class, 'operacion_id');
    }
}
