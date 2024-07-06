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

        "pt_e_eje",
        "pt_f_eje",
        "pt_m_eje",
        "st_a_eje",
        "st_m_eje",
        "st_j_eje",
        "tt_j_eje",
        "tt_a_eje",
        "tt_s_eje",
        "ct_o_eje",
        "ct_n_eje",
        "ct_d_eje",

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

        "pt_e_array",
        "pt_f_array",
        "pt_m_array",
        "st_a_array",
        "st_m_array",
        "st_j_array",
        "tt_j_array",
        "tt_a_array",
        "tt_s_array",
        "ct_o_array",
        "ct_n_array",
        "ct_d_array",
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

    // URLS
    public function getPtEUrlAttribute()
    {
        if ($this->pt_e_file) {
            $files = explode("|", $this->pt_e_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getPtFUrlAttribute()
    {
        if ($this->pt_f_file) {
            $files = explode("|", $this->pt_f_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getPtMUrlAttribute()
    {
        if ($this->pt_m_file) {
            $files = explode("|", $this->pt_m_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getStAUrlAttribute()
    {
        if ($this->st_a_file) {
            $files = explode("|", $this->st_a_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getStMUrlAttribute()
    {
        if ($this->st_m_file) {
            $files = explode("|", $this->st_m_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getStJUrlAttribute()
    {
        if ($this->st_j_file) {
            $files = explode("|", $this->st_j_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getTtJUrlAttribute()
    {
        if ($this->tt_j_file) {
            $files = explode("|", $this->tt_j_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getTtAUrlAttribute()
    {
        if ($this->tt_a_file) {
            $files = explode("|", $this->tt_a_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getTtSUrlAttribute()
    {
        if ($this->tt_s_file) {
            $files = explode("|", $this->tt_s_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getCtOUrlAttribute()
    {
        if ($this->ct_o_file) {
            $files = explode("|", $this->ct_o_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getCtNUrlAttribute()
    {
        if ($this->ct_n_file) {
            $files = explode("|", $this->ct_n_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getCtDUrlAttribute()
    {
        if ($this->ct_d_file) {
            $files = explode("|", $this->ct_d_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = asset("/files/" . $value);
                }
                return $archivos;
            }
        }
        return null;
    }

    // FILES ARRAY
    public function getPtEArrayAttribute()
    {
        if ($this->pt_e_file) {
            $files = explode("|", $this->pt_e_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getPtFArrayAttribute()
    {
        if ($this->pt_f_file) {
            $files = explode("|", $this->pt_f_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getPtMArrayAttribute()
    {
        if ($this->pt_m_file) {
            $files = explode("|", $this->pt_m_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getStAArrayAttribute()
    {
        if ($this->st_a_file) {
            $files = explode("|", $this->st_a_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getStMArrayAttribute()
    {
        if ($this->st_m_file) {
            $files = explode("|", $this->st_m_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getStJArrayAttribute()
    {
        if ($this->st_j_file) {
            $files = explode("|", $this->st_j_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getTtJArrayAttribute()
    {
        if ($this->tt_j_file) {
            $files = explode("|", $this->tt_j_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getTtAArrayAttribute()
    {
        if ($this->tt_a_file) {
            $files = explode("|", $this->tt_a_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getTtSArrayAttribute()
    {
        if ($this->tt_s_file) {
            $files = explode("|", $this->tt_s_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getCtOArrayAttribute()
    {
        if ($this->ct_o_file) {
            $files = explode("|", $this->ct_o_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getCtNArrayAttribute()
    {
        if ($this->ct_n_file) {
            $files = explode("|", $this->ct_n_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }
    public function getCtDArrayAttribute()
    {
        if ($this->ct_d_file) {
            $files = explode("|", $this->ct_d_file);
            if (count($files) > 0) {
                $archivos = [];
                foreach ($files as $value) {
                    $archivos[] = $value;
                }
                return $archivos;
            }
        }
        return null;
    }

    public function operacion()
    {
        return $this->belongsTo(Operacion::class, 'operacion_id');
    }
}
