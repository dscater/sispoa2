<?php

namespace App\Http\Controllers;

use App\Models\DetalleFormulario;
use App\Models\DetalleOperacion;
use App\Models\Log;
use App\Models\Operacion;
use App\Models\Semaforo;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;

class SemaforoController extends Controller
{
    public $validacion = [
        'descripcion' => 'required|min:4',
    ];

    public function index()
    {
        // $semaforos = Semaforo::all();
        // return response()->JSON(["semaforos" => $semaforos, "total" => count($semaforos)]);

        $unidads = Unidad::all();

        $html = '<table class="table table-bordered">';
        $html .= '<thead class="bg-primary">';
        $html .= "<tr>";
        $html .= "<th>N°</th>";
        $html .= "<th>UNIDAD ORGANIZACIONAL</th>";
        $html .= "<th>MALO</th>";
        $html .= "<th>REGULAR</th>";
        $html .= "<th>BUENO</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        $meses = [
            "pt_e",
            "pt_f",
            "pt_m",
            "st_a",
            "st_m",
            "st_j",
            "tt_j",
            "tt_a",
            "tt_s",
            "ct_o",
            "ct_n",
            "ct_d",
        ];

        $fecha_actual = date("Y-m-d");
        foreach ($unidads as $key => $unidad) {
            $detalle_formularios = DetalleFormulario::select("detalle_formularios.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "detalle_formularios.formulario_id")
                ->where("unidad_id", $unidad->id)
                ->get();

            $total_actividades_programadas = 0;
            $total_actividades_ejecutadas = 0;
            $num_mes_actual = (int)date("m");
            $num_mes_actual--;
            foreach ($detalle_formularios as $df) {
                foreach ($df->operacions as $key_ope =>  $operacion) {
                    foreach ($operacion->detalle_operaciones as $do) {
                        foreach ($meses as $key_mes => $mes) {
                            // contabilizar actividades
                            $total_actividades_programadas += (int)$do[$mes];
                            $total_actividades_ejecutadas += (int)$do[$mes . '_eje'];
                        }
                    }
                }
            }

            $p_actividad_ejecutadas = 0;
            if ($total_actividades_programadas > 0) {
                $p_actividad_ejecutadas = round(($total_actividades_ejecutadas * 100) / $total_actividades_programadas, 2);
            }
            $clase = "";
            if ($p_actividad_ejecutadas <= 50) {
                $clase = "bg-malo";
            } elseif ($p_actividad_ejecutadas > 50 && $p_actividad_ejecutadas <= 80) {
                $clase = "bg-regular";
            } else {
                $clase = "bg-bueno";
            }

            $url = "/semaforos/ejecucion_fisica/actividades";
            $html .= "<tr>";
            $html .= "<td>" . ($key + 1) . "</td>";
            $html .= '<td>' .  $unidad->nombre . '</td>';
            if ($clase == 'bg-malo') {
                $html .= '<td class="text-center"><a href="' . $url . '" class="btn_semaforo malo"></a></td>';
                $html .= '<td class="text-center"><div class="btn_semaforo_vacio"></div></td>';
                $html .= '<td class="text-center"><div class="btn_semaforo_vacio"></div></td>';
            }
            if ($clase == 'bg-regular') {
                $html .= '<td class="text-center"><div class="btn_semaforo_vacio"></div></td>';
                $html .= '<td class="text-center"><a href="' . $url . '" class="btn_semaforo regular"></a></td>';
                $html .= '<td class="text-center"><div class="btn_semaforo_vacio"></div></td>';
            }
            if ($clase == 'bg-bueno') {
                $html .= '<td class="text-center"><div class="btn_semaforo_vacio"></div></td>';
                $html .= '<td class="text-center"><div class="btn_semaforo_vacio"></div></td>';
                $html .= '<td class="text-center"><a href="' . $url . '" class="btn_semaforo bueno"></a></td>';
            }
            $html .= "</tr>";
        }

        return response()->JSON([
            "html" => $html
        ]);
    }

    public function store(Request $request)
    {
        $this->validacion["archivo"] = 'required|image|mimes:jpeg,jpg,png|max:4096';
        $request->validate($this->validacion);
        $request["fecha_registro"] = date("Y-m-d");
        $semaforo = Semaforo::create(array_map("mb_strtoupper", $request->except("archivo")));
        if ($request->hasFile('archivo')) {
            $file = $request->archivo;
            $nom_archivo = time() . '_semaforo' . $semaforo->id . '.' . $file->getClientOriginalExtension();
            $semaforo->archivo = $nom_archivo;
            $file->move(public_path() . '/files/', $nom_archivo);
            $semaforo->save();
        }

        $user = Auth::user();
        Log::registrarLog("CREACIÓN", "SEMÁFORO", "EL USUARIO $user->id REGISTRO UN SEMÁFORO", $user);

        return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
    }

    public function show(Semaforo $semaforo)
    {
        return response()->JSON($semaforo);
    }

    public function update(Semaforo $semaforo, Request $request)
    {
        if ($request->hasFile('archivo')) {
            $this->validacion["archivo"] = 'required|image|mimes:jpeg,jpg,png|max:4096';
        }
        $request->validate($this->validacion);
        $semaforo->update(array_map("mb_strtoupper", $request->except("archivo")));
        if ($request->hasFile('archivo')) {
            $antiguo = $semaforo->archivo;
            \File::delete(public_path() . "/files/" . $antiguo);

            $file = $request->archivo;
            $nom_archivo = time() . '_semaforo' . $semaforo->id . '.' . $file->getClientOriginalExtension();
            $semaforo->archivo = $nom_archivo;
            $file->move(public_path() . '/files/', $nom_archivo);
            $semaforo->save();
        }

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "SEMÁFORO", "EL USUARIO $user->id MODIFICÓ UN SEMÁFORO", $user);

        return response()->JSON(["sw" => true, "semaforo" => $semaforo, "msj" => "El registro se actualizó correctamente"]);
    }

    public function destroy(Semaforo $semaforo)
    {
        $antiguo = $semaforo->archivo;
        \File::delete(public_path() . "/files/" . $antiguo);
        $semaforo->delete();

        $user = Auth::user();
        Log::registrarLog("ELIMINACIÓN", "SEMÁFORO", "EL USUARIO $user->id ELIMINÓ UN SEMÁFORO", $user);

        return response()->JSON(["sw" => true, "semaforo" => $semaforo, "msj" => "El registro se actualizó correctamente"]);
    }

    public function actualiza_estados(Request $request, DetalleFormulario $detalle_formulario)
    {
        DB::beginTransaction();
        try {
            $data = $request->data;
            foreach ($data as $d) {
                foreach ($d["detalle_operaciones"] as $do) {
                    $detalle_operacion = DetalleOperacion::find($do["id"]);
                    $detalle_operacion->pt_e_est = $do["pt_e_est"];
                    $detalle_operacion->pt_f_est = $do["pt_f_est"];
                    $detalle_operacion->pt_m_est = $do["pt_m_est"];
                    $detalle_operacion->st_a_est = $do["st_a_est"];
                    $detalle_operacion->st_m_est = $do["st_m_est"];
                    $detalle_operacion->st_j_est = $do["st_j_est"];
                    $detalle_operacion->tt_j_est = $do["tt_j_est"];
                    $detalle_operacion->tt_a_est = $do["tt_a_est"];
                    $detalle_operacion->tt_s_est = $do["tt_s_est"];
                    $detalle_operacion->ct_o_est = $do["ct_o_est"];
                    $detalle_operacion->ct_n_est = $do["ct_n_est"];
                    $detalle_operacion->ct_d_est = $do["ct_d_est"];

                    $detalle_operacion->pt_e_eje = $do["pt_e_eje"];
                    $detalle_operacion->pt_f_eje = $do["pt_f_eje"];
                    $detalle_operacion->pt_m_eje = $do["pt_m_eje"];
                    $detalle_operacion->st_a_eje = $do["st_a_eje"];
                    $detalle_operacion->st_m_eje = $do["st_m_eje"];
                    $detalle_operacion->st_j_eje = $do["st_j_eje"];
                    $detalle_operacion->tt_j_eje = $do["tt_j_eje"];
                    $detalle_operacion->tt_a_eje = $do["tt_a_eje"];
                    $detalle_operacion->tt_s_eje = $do["tt_s_eje"];
                    $detalle_operacion->ct_o_eje = $do["ct_o_eje"];
                    $detalle_operacion->ct_n_eje = $do["ct_n_eje"];
                    $detalle_operacion->ct_d_eje = $do["ct_d_eje"];
                    $detalle_operacion->save();
                }
            }
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'detalle_formulario' => $detalle_formulario,
                'msj' => 'El registro se actualizó de forma correcta'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getResumenSemaforo()
    {
        $unidads = Unidad::all();

        $html = "<table>";
        $html .= '<thead class="bg-primary">';
        $html .= "<tr>";
        $html .= "<th>N°</th>";
        $html .= "<th>Unidad Organizacional</th>";
        $html .= "<th>Total N° Operaciones Programadas</th>";
        $html .= "<th>N° Operaciones programadas a la fecha</th>";
        $html .= "<th>N° Operaciones ejecutadas</th>";
        $html .= "<th>% de actividades ejecutadas Anual</th>";
        $html .= "<th>% de actividades ejecutadas a la fecha</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";


        $fecha_actual = date("Y-m-d");
        $meses = [
            "pt_e",
            "pt_f",
            "pt_m",
            "st_a",
            "st_m",
            "st_j",
            "tt_j",
            "tt_a",
            "tt_s",
            "ct_o",
            "ct_n",
            "ct_d",
        ];

        $total1 = 0;
        $total2 = 0;
        $total3 = 0;

        foreach ($unidads as $key => $unidad) {
            $detalle_formularios = DetalleFormulario::select("detalle_formularios.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "detalle_formularios.formulario_id")
                ->where("unidad_id", $unidad->id)
                ->get();

            $total_operaciones = 0;
            $total_operaciones_fecha = 0;
            $total_operaciones_terminadas = 0;
            $total_actividades_programadas = 0;
            $total_actividades_ejecutadas = 0;
            $total_actividades_ejecutadas_fecha = 0;
            $total_actividades = 0;
            $num_mes_actual = (int)date("m");
            $num_mes_actual--;
            foreach ($detalle_formularios as $df) {
                // total programados
                $total_operaciones += count($df->operacions);

                foreach ($df->operacions as $key_ope =>  $operacion) {
                    $operacion_finalizada = false;
                    foreach ($operacion->detalle_operaciones as $do) {
                        $opreacion_completa = true;
                        foreach ($meses as $key_mes => $mes) {
                            // contabilizar actividades
                            $total_actividades_programadas += (int)$do[$mes];

                            $total_actividades += (int)$do[$mes];
                            // mes con actividad - verificacion de operacion
                            if ($do[$mes] != $do[$mes . '_eje']) {
                                $opreacion_completa = false;
                            }

                            // a la fecha
                            if ($key_mes <= $num_mes_actual) {
                                $total_actividades_ejecutadas_fecha += (int)$do[$mes . '_eje'];
                                FacadesLog::debug($do[$mes . '_eje']);
                            }
                            $total_actividades_ejecutadas += (int)$do[$mes . '_eje'];
                        }
                        // contabilizar opreacion switch
                        if ($opreacion_completa) {
                            $operacion_finalizada = true;
                        }

                        // a la fecha
                        if ($do->final <= $fecha_actual) {
                            $total_operaciones_fecha++;
                        }
                    }
                    if ($operacion_finalizada) {
                        $total_operaciones_terminadas++;
                    }
                }
            }


            if ($total_actividades_programadas > 0) {
                $p_actividad_ejecutadas = round(($total_actividades_ejecutadas * 100) / $total_actividades_programadas, 2);
                $p_actividad_ejecutadas_fecha = round(($total_actividades_ejecutadas_fecha * 100) / $total_actividades_programadas, 2);
            }

            $clase = "";
            if ($p_actividad_ejecutadas_fecha <= 50) {
                $clase = "bg-malo";
            } elseif ($p_actividad_ejecutadas_fecha > 50 && $p_actividad_ejecutadas_fecha <= 80) {
                $clase = "bg-regular";
            } else {
                $clase = "bg-bueno";
            }

            $url = "/semaforos/ejecucion_fisica/actividades/" . $unidad->id;
            $html .= "<tr>";
            $html .= "<td>" . ($key + 1) . "</td>";
            $html .= '<td><a href="' . $url . '">' .  $unidad->nombre . '</a></td>';
            $html .= '<td>' . $total_operaciones . '</td>';
            $html .= '<td>' . $total_operaciones_fecha . '</td>';
            $html .= '<td>' . $total_operaciones_terminadas . '</td>';
            $html .= '<td>' . $p_actividad_ejecutadas . '%</td>';
            $html .= '<td class="' . $clase . '">' . $p_actividad_ejecutadas_fecha . '%</td>';
            $html .= "</tr>";
            $total1 += (int)$total_operaciones;
            $total2 += (int)$total_operaciones_terminadas;
            $total3 += (float)$p_actividad_ejecutadas;
        }

        $html .= '<tr class="bg-primary">';
        $html .= '<td colspan="2" class="font-weight-bold">TOTAL</td>';
        $html .= '<td class="font-weight-bold">' . $total1 . '</td>';
        $html .= '<td></td>';
        $html .= '<td class="font-weight-bold">' . $total2 . '</td>';
        $html .= '<td class="font-weight-bold">' . $total3 . '</td>';
        $html .= '<td></td>';
        $html .= "</tr>";


        $html .= "</tbody>";
        $html .= "</table>";

        $html .= '<table class="table mt-3" style="margin-top:30px;">';
        $html .= "<tbody>";
        $html .= '<tr>
            <td>0% al 50%</td>
            <td class="bg-malo"></td>
        </tr>';
        $html .= '<tr>
            <td>51% al 80%</td>
            <td class="bg-regular"></td>
        </tr>';
        $html .= '<tr>
            <td>81% al 100%</td>
            <td class="bg-bueno"></td>
        </tr>';
        $html .= "</tbody>";
        $html .= "</table>";
        return response()->JSON([
            "html" => $html
        ]);
    }


    public function getResumenSemaforoDetalle()
    {
        $unidads = Unidad::all();

        $html = '<table class="table table-bordered">';
        $html .= '<thead class="bg-primary">';
        $html .= "<tr>";
        $html .= "<th>N°</th>";
        $html .= "<th>Unidad Organizacional</th>";
        $html .= "<th></th>";
        $html .= "<th>E</th>";
        $html .= "<th>F</th>";
        $html .= "<th>M</th>";
        $html .= "<th>A</th>";
        $html .= "<th>M</th>";
        $html .= "<th>J</th>";
        $html .= "<th>J</th>";
        $html .= "<th>A</th>";
        $html .= "<th>S</th>";
        $html .= "<th>O</th>";
        $html .= "<th>N</th>";
        $html .= "<th>D</th>";
        $html .= "<th>TOTAL</th>";
        $html .= "<th>% DE ACTIVIDADES EJECUTADAS ANUAL</th>";
        $html .= "<th>% DE ACTIVIDADES EJECUTADAS A LA FECHA</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";


        $fecha_actual = date("Y-m-d");
        $meses = [
            "pt_e",
            "pt_f",
            "pt_m",
            "st_a",
            "st_m",
            "st_j",
            "tt_j",
            "tt_a",
            "tt_s",
            "ct_o",
            "ct_n",
            "ct_d",
        ];

        $total1 = 0;
        $total2 = 0;
        $total3 = 0;

        foreach ($unidads as $key => $unidad) {
            $html .= '<tr class="">';

            $html .= '<td rowspan="2">' . ($key + 1) . '</td>';
            $html .= '<td rowspan="2">' .  $unidad->nombre . '</td>';
            $html .= '<td>PROGRAMADO</td>';
            $detalle_formularios = DetalleFormulario::select("detalle_formularios.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "detalle_formularios.formulario_id")
                ->where("unidad_id", $unidad->id)
                ->get();

            $num_mes_actual = (int)date("m");
            $num_mes_actual--;

            $array_programados = [
                "pt_e" => 0,
                "pt_f" => 0,
                "pt_m" => 0,
                "st_a" => 0,
                "st_m" => 0,
                "st_j" => 0,
                "tt_j" => 0,
                "tt_a" => 0,
                "tt_s" => 0,
                "ct_o" => 0,
                "ct_n" => 0,
                "ct_d" => 0,
            ];
            $array_ejecutados = [
                "pt_e" => 0,
                "pt_f" => 0,
                "pt_m" => 0,
                "st_a" => 0,
                "st_m" => 0,
                "st_j" => 0,
                "tt_j" => 0,
                "tt_a" => 0,
                "tt_s" => 0,
                "ct_o" => 0,
                "ct_n" => 0,
                "ct_d" => 0,
            ];

            $total_programados = 0;
            $total_ejecutados = 0;
            $total_ejecutados_fecha = 0;

            foreach ($detalle_formularios as $df) {
                foreach ($df->operacions as $key_ope =>  $operacion) {
                    foreach ($operacion->detalle_operaciones as $do) {
                        foreach ($meses as $key_mes => $mes) {
                            // contabilizar actividades
                            $total_programados += (int)$do[$mes];
                            $array_programados[$mes] += (int)$do[$mes];
                            $array_ejecutados[$mes] += (int)$do[$mes . '_eje'];
                            // a la fecha
                            if ($key_mes <= $num_mes_actual) {
                                $total_ejecutados_fecha += (int)$do[$mes . '_eje'];
                            }
                            $total_ejecutados += (int)$do[$mes . '_eje'];
                        }
                    }
                }
            }

            $fila2 = "<tr>";
            $fila2 .= "<td>EJECUTADO</td>";
            foreach ($meses as $key_mes => $mes) {
                $html .= '<td>' . $array_programados[$mes] . '</td>';
                $fila2 .= '<td>' .  $array_ejecutados[$mes] . '</td>';
            }
            $fila2 .= '<td>' .  $total_ejecutados . '</td>';
            $fila2 .= "</tr>";
            $html .= '<td>' . $total_programados . '</td>';

            $p_anual = 0;
            $p_fecha = 0;

            if ($total_programados > 0) {
                $p_anual = round(($total_ejecutados * 100) / $total_programados, 2);
            }

            if ($total_programados > 0) {
                $p_fecha = round(($total_ejecutados_fecha * 100) / $total_programados, 2);
            }

            $clase = "";
            if ($p_fecha <= 50) {
                $clase = "bg-malo";
            } elseif ($p_fecha > 50 && $p_fecha <= 80) {
                $clase = "bg-regular";
            } else {
                $clase = "bg-bueno";
            }

            $html .= '<td rowspan="2">' . $p_anual . '%</td>';
            $html .= '<td rowspan="2" class="' . $clase . '">' . $p_fecha . '%</td>';
            $html .= "</tr>";
            $html .= $fila2;
        }

        $html .= "</tbody>";
        $html .= "</table>";
        return response()->JSON([
            "html" => $html
        ]);
    }

    public function gerResumenFisicoUnidad(Request $request)
    {
        $detalle_formularios = DetalleFormulario::select("detalle_formularios.*")
            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "detalle_formularios.formulario_id")
            ->where("unidad_id", $request->unidad_id)
            ->get();

        $total_programados = 0;
        $total_ejecutados = 0;
        $meses = [
            "pt_e",
            "pt_f",
            "pt_m",
            "st_a",
            "st_m",
            "st_j",
            "tt_j",
            "tt_a",
            "tt_s",
            "ct_o",
            "ct_n",
            "ct_d",
        ];
        $array_meses = [
            "pt_e" => "Enero",
            "pt_f" => "Febrero",
            "pt_m" => "Marzo",
            "st_a" => "Abril",
            "st_m" => "Mayo",
            "st_j" => "Junio",
            "tt_j" => "Julio",
            "tt_a" => "Agosto",
            "tt_s" => "Septiembre",
            "ct_o" => "Octubre",
            "ct_n" => "Noviembre",
            "ct_d" => "Diciembre",
        ];

        $array_programados = [];
        $array_programados_p = [];
        $array_ejecutados = [];
        $array_ejecutados_p = [];
        foreach ($meses  as $value) {
            $array_programados[$value] = 0;
            $array_programados_p[$value] = 0;
            $array_ejecutados[$value] = 0;
            $array_ejecutados_p[$value] = 0;
        }

        foreach ($detalle_formularios as $detalle_formulario) {
            foreach ($detalle_formulario->operacions as $operacion) {
                foreach ($operacion->detalle_operaciones as $do) {
                    foreach ($meses as $mes) {
                        if ($do[$mes] && trim($do[$mes]) != '') {
                            $array_programados[$mes] += (float)$do[$mes];
                            $total_programados += (float)$do[$mes];
                            // ejecutado
                            if ($do[$mes . "_eje"] > 0) {
                                $array_ejecutados[$mes] += $do[$mes . "_eje"];
                                $total_ejecutados += (float)$do[$mes . "_eje"];
                            }
                        }
                    }
                }
            }
        }

        $a_la_fecha = 0;
        $sum1 = 0;
        $sum2 = 0;
        $num_mes_actual = (int)date("m");
        $num_mes_actual--;

        foreach ($meses as $key_mes => $mes) {
            if ($total_programados > 0) {
                $array_programados_p[$mes] = ($array_programados[$mes] * 100) / $total_programados;
                $array_programados_p[$mes] = round($array_programados_p[$mes], 2);
                if ($key_mes <= $num_mes_actual) {
                    $sum1 += (float)$array_programados_p[$mes];
                }
            } else {
                $array_programados_p[$mes] = 0;
            }
            if ($total_programados > 0) {
                $array_ejecutados_p[$mes] = ($array_ejecutados[$mes] * 100) / $total_programados;
                $array_ejecutados_p[$mes] = round($array_ejecutados_p[$mes], 2);
                if ($key_mes <= $num_mes_actual) {
                    $sum2 += (float)$array_ejecutados_p[$mes];
                }
            } else {
                $array_ejecutados_p[$mes] = 0;
            }
        }

        if ($sum1 > 0) {
            $a_la_fecha = $sum2 / $sum1;
        }
        $a_la_fecha = round($a_la_fecha, 2);

        $p_ejecutados = 0;
        if ($total_programados > 0) {
            $p_ejecutados = ($total_ejecutados * 100) / $total_programados;
            $p_ejecutados = round($p_ejecutados, 2);
        }

        $html = '
               <thead>
                    <tr class="bg-primary">
                        <th rowspan="3" width="5%">
                            Código Operación(1)
                        </th>
                        <th rowspan="3">Operación(2)</th>
                        <th rowspan="3">Ponderación</th>
                        <th rowspan="3">
                            Resultado intermedio Esperado(3)
                        </th>
                        <th rowspan="3">
                            Medios de verificación(4)
                        </th>
                        <th rowspan="3">Código tarea(5)</th>
                        <th rowspan="3">
                            Actividad/Tarea(6)
                        </th>
                        <th colspan="12">
                            Programación de ejecución de
                            operaciones y actividades(7)
                        </th>
                        <th colspan="2">
                            Fecha prevista de ejecución(8)
                        </th>
                    </tr>
                    <tr class="bg-primary">
                        <th colspan="3">1er Trim.</th>
                        <th colspan="3">2do Trim.</th>
                        <th colspan="3">3er Trim.</th>
                        <th colspan="3">4to Trim.</th>
                        <th rowspan="2">Inicio</th>
                        <th rowspan="2">Final</th>
                    </tr>
                    <tr class="bg-primary">
                        <th>E</th>
                        <th>F</th>
                        <th>M</th>
                        <th>A</th>
                        <th>M</th>
                        <th>J</th>
                        <th>J</th>
                        <th>A</th>
                        <th>S</th>
                        <th>O</th>
                        <th>N</th>
                        <th>D</th>
                    </tr>
                </thead>
        ';
        $html .= '<tbody>';

        $programados = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $ejecutados = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        foreach ($detalle_formularios as $detalle_formulario) {
            foreach ($detalle_formulario->operacions as $operacion) {
                if ($operacion->subdirecion) {
                    $html .= '
                    <tr
                        class="bg-primary"
                    >
                        <td colspan="21">
                            ' .
                        $operacion->subdireccion->nombre
                        . '
                        </td>
                    </tr>
                    ';
                    $programados = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                    $ejecutados = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                }
                $html .= '
                 <tr>
                    <td rowspan="' . (count($operacion->detalle_operaciones) + 1) . '">
                        ' . $operacion->codigo_operacion . '</td>
                    <td rowspan="' . (count($operacion->detalle_operaciones) + 1) . '">
                        ' . $operacion->operacion . '</td>
                    <td rowspan="' . (count($operacion->detalle_operaciones) + 1) . '">
                        ' . $operacion->ponderacion . '%</td>
                    <td rowspan="' . (count($operacion->detalle_operaciones) + 1) . '">
                        ' . $operacion->resultado_esperado . '</td>
                    <td rowspan="' . (count($operacion->detalle_operaciones) + 1) . '">
                        ' . $operacion->medios_verificacion . '</td>
                </tr>';

                foreach ($operacion->detalle_operaciones as $index_detalle => $detalle_operacion) {
                    $programados[0] += (float)$detalle_operacion->pt_e;
                    $programados[1] += (float)$detalle_operacion->pt_f;
                    $programados[2] += (float)$detalle_operacion->pt_m;
                    $programados[3] += (float)$detalle_operacion->st_a;
                    $programados[4] += (float)$detalle_operacion->st_m;
                    $programados[5] += (float)$detalle_operacion->st_j;
                    $programados[6] += (float)$detalle_operacion->tt_j;
                    $programados[7] += (float)$detalle_operacion->tt_a;
                    $programados[8] += (float)$detalle_operacion->tt_s;
                    $programados[9] += (float)$detalle_operacion->ct_o;
                    $programados[10] += (float)$detalle_operacion->ct_n;
                    $programados[11] += (float)$detalle_operacion->ct_d;

                    $ejecutados[0] += (float)$detalle_operacion->pt_e_eje;
                    $ejecutados[1] += (float)$detalle_operacion->pt_f_eje;
                    $ejecutados[2] += (float)$detalle_operacion->pt_m_eje;
                    $ejecutados[3] += (float)$detalle_operacion->st_a_eje;
                    $ejecutados[4] += (float)$detalle_operacion->st_m_eje;
                    $ejecutados[5] += (float)$detalle_operacion->st_j_eje;
                    $ejecutados[6] += (float)$detalle_operacion->tt_j_eje;
                    $ejecutados[7] += (float)$detalle_operacion->tt_a_eje;
                    $ejecutados[8] += (float)$detalle_operacion->tt_s_eje;
                    $ejecutados[9] += (float)$detalle_operacion->ct_o_eje;
                    $ejecutados[10] += (float)$detalle_operacion->ct_n_eje;
                    $ejecutados[11] += (float)$detalle_operacion->ct_d_eje;

                    $html .= '
                        <tr>
                            <td>' . $detalle_operacion->codigo_tarea . '</td>
                            <td>' . $detalle_operacion->actividad_tarea . '</td>
                            <td class="' . ((float)$detalle_operacion->pt_e > 0 ? ($detalle_operacion->pt_e == $detalle_operacion->pt_e_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->pt_e . '</td>
                            <td class="' . ((float)$detalle_operacion->pt_f > 0 ? ($detalle_operacion->pt_f == $detalle_operacion->pt_f_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->pt_f . '</td>
                            <td class="' . ((float)$detalle_operacion->pt_m > 0 ? ($detalle_operacion->pt_m == $detalle_operacion->pt_m_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->pt_m . '</td>
                            <td class="' . ((float)$detalle_operacion->st_a > 0 ? ($detalle_operacion->st_a == $detalle_operacion->st_a_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->st_a . '</td>
                            <td class="' . ((float)$detalle_operacion->st_m > 0 ? ($detalle_operacion->st_m == $detalle_operacion->st_m_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->st_m . '</td>
                            <td class="' . ((float)$detalle_operacion->st_j > 0 ? ($detalle_operacion->st_j == $detalle_operacion->st_j_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->st_j . '</td>
                            <td class="' . ((float)$detalle_operacion->tt_j > 0 ? ($detalle_operacion->tt_j == $detalle_operacion->tt_j_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->tt_j . '</td>
                            <td class="' . ((float)$detalle_operacion->tt_a > 0 ? ($detalle_operacion->tt_a == $detalle_operacion->tt_a_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->tt_a . '</td>
                            <td class="' . ((float)$detalle_operacion->tt_s > 0 ? ($detalle_operacion->tt_s == $detalle_operacion->tt_s_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->tt_s . '</td>
                            <td class="' . ((float)$detalle_operacion->ct_o > 0 ? ($detalle_operacion->ct_o == $detalle_operacion->ct_o_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->ct_o . '</td>
                            <td class="' . ((float)$detalle_operacion->ct_n > 0 ? ($detalle_operacion->ct_n == $detalle_operacion->ct_n_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->ct_n . '</td>
                            <td class="' . ((float)$detalle_operacion->ct_d > 0 ? ($detalle_operacion->ct_d == $detalle_operacion->ct_d_eje ? 'bg-success' : 'bg-warning') : '') . '">' . $detalle_operacion->ct_d . '</td>
                            <td>' . date("d/m/Y", strtotime($detalle_operacion->inicio)) . '</td>
                            <td>' . date("d/m/Y", strtotime($detalle_operacion->final)) . '</td>
                        </tr>
                    ';
                }

                if ($operacion->subdirecion) {
                    $suma_programados = $programados[0] +  $programados[1] +  $programados[2] + $programados[3] + $programados[4] + $programados[5] + $programados[6] + $programados[7] + $programados[8] + $programados[9] + $programados[10] + $programados[11];

                    $html .= '
                        <tr class="bg-cream">
                            <td colspan="7"></td>
                            <td>' . $programados[0] . '</td>
                            <td>' . $programados[1] . '</td>
                            <td>' . $programados[2] . '</td>
                            <td>' . $programados[3] . '</td>
                            <td>' . $programados[4] . '</td>
                            <td>' . $programados[5] . '</td>
                            <td>' . $programados[6] . '</td>
                            <td>' . $programados[7] . '</td>
                            <td>' . $programados[8] . '</td>
                            <td>' . $programados[9] . '</td>
                            <td>' . $programados[10] . '</td>
                            <td>' . $programados[11] . '</td>
                            <td colspan="2">' . $suma_programados . ' Programados</td>
                        </tr>
                    ';
                    //porcentajes
                    $html .= '
                        <tr class="bg-cream">
                            <td colspan="7"></td>
                            <td>' . round(($programados[0] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[1] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[2] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[3] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[4] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[5] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[6] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[7] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[8] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[9] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[10] * 100) / $suma_programados, 2) . '</td>
                            <td>' . round(($programados[11] * 100) / $suma_programados, 2) . '</td>
                            <td colspan="2">100%</td>
                        </tr>
                    ';

                    $suma_ejecutados = $ejecutados[0] +  $ejecutados[1] +  $ejecutados[2] + $ejecutados[3] + $ejecutados[4] + $ejecutados[5] + $ejecutados[6] + $ejecutados[7] + $ejecutados[8] + $ejecutados[9] + $ejecutados[10] + $ejecutados[11];

                    $html .= '
                        <tr class="bg-cream">
                            <td colspan="7"></td>
                            <td>' . $ejecutados[0] . '</td>
                            <td>' . $ejecutados[1] . '</td>
                            <td>' . $ejecutados[2] . '</td>
                            <td>' . $ejecutados[3] . '</td>
                            <td>' . $ejecutados[4] . '</td>
                            <td>' . $ejecutados[5] . '</td>
                            <td>' . $ejecutados[6] . '</td>
                            <td>' . $ejecutados[7] . '</td>
                            <td>' . $ejecutados[8] . '</td>
                            <td>' . $ejecutados[9] . '</td>
                            <td>' . $ejecutados[10] . '</td>
                            <td>' . $ejecutados[11] . '</td>
                            <td colspan="2">' . $suma_ejecutados . ' Ejecutados</td>
                        </tr>
                    ';
                    $ejecutados_p = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                    $ejecutados_p[0] = round(($ejecutados[0] * 100) / $suma_programados, 2);
                    $ejecutados_p[1] = round(($ejecutados[1] * 100) / $suma_programados, 2);
                    $ejecutados_p[2] = round(($ejecutados[2] * 100) / $suma_programados, 2);
                    $ejecutados_p[3] = round(($ejecutados[3] * 100) / $suma_programados, 2);
                    $ejecutados_p[4] = round(($ejecutados[4] * 100) / $suma_programados, 2);
                    $ejecutados_p[5] = round(($ejecutados[5] * 100) / $suma_programados, 2);
                    $ejecutados_p[6] = round(($ejecutados[6] * 100) / $suma_programados, 2);
                    $ejecutados_p[7] = round(($ejecutados[7] * 100) / $suma_programados, 2);
                    $ejecutados_p[8] = round(($ejecutados[8] * 100) / $suma_programados, 2);
                    $ejecutados_p[9] = round(($ejecutados[9] * 100) / $suma_programados, 2);
                    $ejecutados_p[10] = round(($ejecutados[10] * 100) / $suma_programados, 2);
                    $ejecutados_p[11] = round(($ejecutados[11] * 100) / $suma_programados, 2);

                    $suma_ejecutados_p = $ejecutados_p[0] +  $ejecutados_p[1] +  $ejecutados_p[2] + $ejecutados_p[3] + $ejecutados_p[4] + $ejecutados_p[5] + $ejecutados_p[6] + $ejecutados_p[7] + $ejecutados_p[8] + $ejecutados_p[9] + $ejecutados_p[10] + $ejecutados_p[11];

                    //porcentajes
                    $html .= '
                        <tr class="bg-cream">
                            <td colspan="7"></td>
                            <td>' . $ejecutados_p[0] . '</td>
                            <td>' . $ejecutados_p[1] . '</td>
                            <td>' . $ejecutados_p[2] . '</td>
                            <td>' . $ejecutados_p[3] . '</td>
                            <td>' . $ejecutados_p[4] . '</td>
                            <td>' . $ejecutados_p[5] . '</td>
                            <td>' . $ejecutados_p[6] . '</td>
                            <td>' . $ejecutados_p[7] . '</td>
                            <td>' . $ejecutados_p[8] . '</td>
                            <td>' . $ejecutados_p[9] . '</td>
                            <td>' . $ejecutados_p[10] . '</td>
                            <td>' . $ejecutados_p[11] . '</td>
                            <td colspan="2">' . $suma_ejecutados_p . ' Acumulados</td>
                        </tr>
                    ';
                }
            }
        }


        $suma_programados = $programados[0] +  $programados[1] +  $programados[2] + $programados[3] + $programados[4] + $programados[5] + $programados[6] + $programados[7] + $programados[8] + $programados[9] + $programados[10] + $programados[11];

        $html .= '
            <tr class="bg-cream">
                <td colspan="7"></td>
                <td>' . $programados[0] . '</td>
                <td>' . $programados[1] . '</td>
                <td>' . $programados[2] . '</td>
                <td>' . $programados[3] . '</td>
                <td>' . $programados[4] . '</td>
                <td>' . $programados[5] . '</td>
                <td>' . $programados[6] . '</td>
                <td>' . $programados[7] . '</td>
                <td>' . $programados[8] . '</td>
                <td>' . $programados[9] . '</td>
                <td>' . $programados[10] . '</td>
                <td>' . $programados[11] . '</td>
                <td colspan="2">' . $suma_programados . ' Programados</td>
            </tr>
        ';
        //porcentajes
        $html .= '
            <tr class="bg-cream">
                <td colspan="7"></td>
                <td>' . round(($programados[0] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[1] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[2] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[3] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[4] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[5] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[6] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[7] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[8] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[9] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[10] * 100) / $suma_programados, 2) . '</td>
                <td>' . round(($programados[11] * 100) / $suma_programados, 2) . '</td>
                <td colspan="2">100%</td>
            </tr>
        ';

        $suma_ejecutados = $ejecutados[0] +  $ejecutados[1] +  $ejecutados[2] + $ejecutados[3] + $ejecutados[4] + $ejecutados[5] + $ejecutados[6] + $ejecutados[7] + $ejecutados[8] + $ejecutados[9] + $ejecutados[10] + $ejecutados[11];

        $html .= '
            <tr class="bg-cream">
                <td colspan="7"></td>
                <td>' . $ejecutados[0] . '</td>
                <td>' . $ejecutados[1] . '</td>
                <td>' . $ejecutados[2] . '</td>
                <td>' . $ejecutados[3] . '</td>
                <td>' . $ejecutados[4] . '</td>
                <td>' . $ejecutados[5] . '</td>
                <td>' . $ejecutados[6] . '</td>
                <td>' . $ejecutados[7] . '</td>
                <td>' . $ejecutados[8] . '</td>
                <td>' . $ejecutados[9] . '</td>
                <td>' . $ejecutados[10] . '</td>
                <td>' . $ejecutados[11] . '</td>
                <td colspan="2">' . $suma_ejecutados . ' Ejecutados</td>
            </tr>
        ';
        $ejecutados_p = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $ejecutados_p[0] = round(($ejecutados[0] * 100) / $suma_programados, 2);
        $ejecutados_p[1] = round(($ejecutados[1] * 100) / $suma_programados, 2);
        $ejecutados_p[2] = round(($ejecutados[2] * 100) / $suma_programados, 2);
        $ejecutados_p[3] = round(($ejecutados[3] * 100) / $suma_programados, 2);
        $ejecutados_p[4] = round(($ejecutados[4] * 100) / $suma_programados, 2);
        $ejecutados_p[5] = round(($ejecutados[5] * 100) / $suma_programados, 2);
        $ejecutados_p[6] = round(($ejecutados[6] * 100) / $suma_programados, 2);
        $ejecutados_p[7] = round(($ejecutados[7] * 100) / $suma_programados, 2);
        $ejecutados_p[8] = round(($ejecutados[8] * 100) / $suma_programados, 2);
        $ejecutados_p[9] = round(($ejecutados[9] * 100) / $suma_programados, 2);
        $ejecutados_p[10] = round(($ejecutados[10] * 100) / $suma_programados, 2);
        $ejecutados_p[11] = round(($ejecutados[11] * 100) / $suma_programados, 2);

        $suma_ejecutados_p = $ejecutados_p[0] +  $ejecutados_p[1] +  $ejecutados_p[2] + $ejecutados_p[3] + $ejecutados_p[4] + $ejecutados_p[5] + $ejecutados_p[6] + $ejecutados_p[7] + $ejecutados_p[8] + $ejecutados_p[9] + $ejecutados_p[10] + $ejecutados_p[11];

        //porcentajes
        $html .= '
            <tr class="bg-cream">
                <td colspan="7"></td>
                <td>' . $ejecutados_p[0] . '</td>
                <td>' . $ejecutados_p[1] . '</td>
                <td>' . $ejecutados_p[2] . '</td>
                <td>' . $ejecutados_p[3] . '</td>
                <td>' . $ejecutados_p[4] . '</td>
                <td>' . $ejecutados_p[5] . '</td>
                <td>' . $ejecutados_p[6] . '</td>
                <td>' . $ejecutados_p[7] . '</td>
                <td>' . $ejecutados_p[8] . '</td>
                <td>' . $ejecutados_p[9] . '</td>
                <td>' . $ejecutados_p[10] . '</td>
                <td>' . $ejecutados_p[11] . '</td>
                <td colspan="2">' . $suma_ejecutados_p . ' Acumulados</td>
            </tr>
        ';


        $td_programados1 = "";
        $td_programados2 = "";
        $td_ejecutados1 = "";
        $td_ejecutados2 = "";

        foreach ($meses as $mes) {
            // programados 1
            $td_programados1 .= '<td>' . $array_programados[$mes] . '</td>';

            // programados 2
            $td_programados2 .= '<td>' . $array_programados_p[$mes] . '</td>';

            // ejecutados 1
            $td_ejecutados1 .= '<td>' . $array_ejecutados[$mes] . '</td>';

            // ejecutados 2
            $td_ejecutados2 .= '<td>' . $array_ejecutados_p[$mes] . '</td>';
        }

        $td_programados1 .= '<td colspan="2">' . $total_programados . ' Programados</td>';
        $td_programados2 .= '<td colspan="2">100%</td>';
        $td_ejecutados1 .= '<td colspan="2">' . $total_ejecutados . ' Ejecutados</td>';
        $td_ejecutados2 .= '<td colspan="2">' . $p_ejecutados . '% Acumulados</td>';

        $html .= '
            <tr>
                <td colspan="7"></td>
                ' . $td_programados1 . '
            </tr>
        ';

        $html .= '
            <tr>
                <td colspan="7"></td>
                ' . $td_programados2 . '
            </tr>
        ';

        $html .= '
            <tr>
                <td colspan="7"></td>
                ' . $td_ejecutados1 . '
            </tr>
        ';

        $html .= '
            <tr>
                <td colspan="7"></td>
                ' . $td_ejecutados2 . '
            </tr>
        ';

        $html .= '
            <tr>
                <td colspan="19"></td>
                <td colspan="2">
                    ' . $a_la_fecha . '% A la fecha
                </td>
            </tr>
        ';


        $html .= '</tbody>';
        return response()->JSON([
            'sw' => true,
            'detalle_formulario' => $detalle_formulario->load("operacions.subdireccion"),
            "html" => $html,

        ], 200);
    }

    public function getGraficoUnidad(Request $request)
    {
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $mes_ini = (int)date("m", strtotime($fecha_ini));
        $mes_fin = (int)date("m", strtotime($fecha_fin));

        $total_programados = 0;
        $total_ejecutados = 0;

        $meses = [
            "pt_e",
            "pt_f",
            "pt_m",
            "st_a",
            "st_m",
            "st_j",
            "tt_j",
            "tt_a",
            "tt_s",
            "ct_o",
            "ct_n",
            "ct_d",
        ];
        $array_meses = [
            "pt_e" => "Enero",
            "pt_f" => "Febrero",
            "pt_m" => "Marzo",
            "st_a" => "Abril",
            "st_m" => "Mayo",
            "st_j" => "Junio",
            "tt_j" => "Julio",
            "tt_a" => "Agosto",
            "tt_s" => "Septiembre",
            "ct_o" => "Octubre",
            "ct_n" => "Noviembre",
            "ct_d" => "Diciembre",
        ];

        if ($fecha_ini && $fecha_fin) {
            $mes_ini--;
            $mes_fin--;
            if ($mes_ini != $mes_fin) {
                $meses_aux = [];
                $array_meses_aux = [];
                for ($i = $mes_ini; $i <= $mes_fin; $i++) {
                    $meses_aux[] = $meses[$i];
                    $array_meses_aux[$meses[$i]] = $array_meses[$meses[$i]];
                }
                $meses = $meses_aux;
                $array_meses = $array_meses_aux;
            } else {
                $mes = $meses[$mes_ini];
                $mes_txt = $array_meses[$mes];
                $array_meses = [];
                $array_meses[$meses[$mes_ini]] = $mes_txt;
                $meses = [];
                $meses[] = $mes;
            }
        }


        $array_programados = [];
        $array_programados_p = [];
        $array_ejecutados = [];
        $array_ejecutados_p = [];
        foreach ($meses as $value) {
            $array_programados[$value] = 0;
            $array_programados_p[$value] = 0;
            $array_ejecutados[$value] = 0;
            $array_ejecutados_p[$value] = 0;
        }

        $detalle_formularios = DetalleFormulario::select("detalle_formularios.*")
            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "detalle_formularios.formulario_id")
            ->where("unidad_id", $request->unidad_id)
            ->get();


        foreach ($detalle_formularios as $detalle_formulario) {
            foreach ($detalle_formulario->operacions as $operacion) {
                foreach ($operacion->detalle_operaciones as $do) {
                    foreach ($meses as $mes) {
                        if ($do[$mes] && trim($do[$mes]) != '') {
                            $array_programados[$mes] += (float)$do[$mes];
                            $total_programados += (float)$do[$mes];
                            // ejecutado
                            if ($do[$mes . "_eje"] > 0) {
                                $array_ejecutados[$mes] += $do[$mes . "_eje"];
                                $total_ejecutados += (float)$do[$mes . "_eje"];
                            }
                        }
                    }
                }
            }
        }

        $a_la_fecha = 0;
        $sum1 = 0;
        $sum2 = 0;
        $num_mes_actual = (int)date("m");
        $num_mes_actual--;

        foreach ($meses as $key_mes => $mes) {
            if ($total_programados > 0) {
                $array_programados_p[$mes] = ($array_programados[$mes] * 100) / $total_programados;
                $array_programados_p[$mes] = round($array_programados_p[$mes], 2);
                if ($key_mes <= $num_mes_actual) {
                    $sum1 += (float)$array_programados_p[$mes];
                }
            } else {
                $array_programados_p[$mes] = 0;
            }
            if ($total_programados > 0) {
                $array_ejecutados_p[$mes] = ($array_ejecutados[$mes] * 100) / $total_programados;
                $array_ejecutados_p[$mes] = round($array_ejecutados_p[$mes], 2);
                if ($key_mes <= $num_mes_actual) {
                    $sum2 += (float)$array_ejecutados_p[$mes];
                }
            } else {
                $array_ejecutados_p[$mes] = 0;
            }
        }

        if ($sum1 > 0) {
            $a_la_fecha = $sum2 / $sum1;
        }
        $a_la_fecha = round($a_la_fecha, 2);

        $p_ejecutados = 0;
        if ($total_programados > 0) {
            $p_ejecutados = ($total_ejecutados * 100) / $total_programados;
            $p_ejecutados = round($p_ejecutados, 2);
        }


        $categories = [];

        $data = [
            [
                "name" => "Programados",
                "data" => [],
                "color" => "#1593c5",
            ],
            [
                "name" => "Ejecutados",
                "data" => [],
                "color" => "#02a767",
            ],
        ];
        foreach ($array_meses as $key => $value) {
            $categories[] = $value;
            $data[0]["data"][] = (float)$array_programados_p[$key];
            $data[1]["data"][] = (float)$array_ejecutados_p[$key];
        }

        return response()->JSON([
            'sw' => true,
            'detalle_formulario' => $detalle_formulario->load("operacions.subdireccion"),
            "total_programados" => $total_programados,
            "total_ejecutados" => $total_ejecutados,
            "a_la_fecha" => $a_la_fecha,
            "meses" => $meses,
            "array_programados" => $array_programados,
            "array_programados_p" => $array_programados_p,
            "array_ejecutados" => $array_ejecutados,
            "array_ejecutados_p" => $array_ejecutados_p,
            "p_ejecutados" => $p_ejecutados,
            "categories" => $categories,
            "series" => $data,
        ], 200);
    }
}
