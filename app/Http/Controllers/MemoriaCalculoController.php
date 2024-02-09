<?php

namespace App\Http\Controllers;

use App\Models\FormularioCuatro;
use App\Models\Log;
use App\Models\MemoriaCalculo;
use App\Models\MemoriaOperacion;
use App\Models\MemoriaOperacionDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log as Debug;

class MemoriaCalculoController extends Controller
{
    public $validacion = [
        'formulario_seleccionado' => 'required||unique:memoria_calculos,formulario_seleccionado',
    ];

    public $mensajes = [];

    public function index(Request $request)
    {
        $listado = [];
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "ENLACE") {
            $listado = MemoriaCalculo::select("memoria_calculos.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                ->get();
        } else {
            $listado = MemoriaCalculo::all();
        }
        return response()->JSON(['listado' => $listado, 'total' => count($listado)], 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            $request['fecha_registro'] = date('Y-m-d');
            $request["total_actividades"] = 0;
            $request["total_ene"] = 0;
            $request["total_feb"] = 0;
            $request["total_mar"] = 0;
            $request["total_abr"] = 0;
            $request["total_may"] = 0;
            $request["total_jun"] = 0;
            $request["total_jul"] = 0;
            $request["total_ago"] = 0;
            $request["total_sep"] = 0;
            $request["total_oct"] = 0;
            $request["total_nov"] = 0;
            $request["total_dic"] = 0;
            $request["total_final"] = 0;
            $array_form_seleccionado = explode("|", $request["formulario_seleccionado"]);
            $request["formulario_id"] = $array_form_seleccionado[1];
            $nuevo_memoria_calculo = MemoriaCalculo::create(array_map('mb_strtoupper', $request->except("data")));

            $total_actividades = 0;
            $total_ene = 0;
            $total_feb = 0;
            $total_mar = 0;
            $total_abr = 0;
            $total_may = 0;
            $total_jun = 0;
            $total_jul = 0;
            $total_ago = 0;
            $total_sep = 0;
            $total_oct = 0;
            $total_nov = 0;
            $total_dic = 0;
            $total_final = 0;

            $data = $request->data;
            foreach ($data as $d) {
                $nueva_operacion = $nuevo_memoria_calculo->operacions()->create([
                    "operacion_id" => mb_strtoupper($d["operacion_id"]),
                    // "detalle_operacion_id" => mb_strtoupper($d["detalle_operacion_id"]),
                    "ue" => mb_strtoupper($d["ue"]),
                    "prog" => mb_strtoupper($d["prog"]),
                    "act" => mb_strtoupper($d["act"]),
                    "lugar" => mb_strtoupper($d["lugar"]),
                    "responsable" => mb_strtoupper($d["responsable"]),
                    "justificacion" => mb_strtoupper($d["justificacion"]),
                    "total_operacion" => $d["total_operacion"],
                    "fecha_registro" => date("Y-m-d")
                ]);

                foreach ($d["memoria_operacion_detalles"] as $mod) {
                    $nuevo_detalle_operacion = $nueva_operacion->memoria_operacion_detalles()->create([
                        "ue" => mb_strtoupper($nueva_operacion->ue),
                        "prog" => mb_strtoupper($nueva_operacion->prog),
                        "act" => mb_strtoupper($nueva_operacion->act),
                        "lugar" => mb_strtoupper($nueva_operacion->lugar),
                        "responsable" => mb_strtoupper($nueva_operacion->responsable),
                        "justificacion" => mb_strtoupper($d["justificacion"]),
                        "partida_id" => mb_strtoupper($mod["partida_id"]),
                        "partida" => mb_strtoupper($mod["partida"]),
                        "nro" => mb_strtoupper($mod["nro"]),
                        "descripcion" => mb_strtoupper($mod["descripcion"]),
                        // "descripcion_detallada" => mb_strtoupper($mod["descripcion_detallada"]),
                        "cantidad" => $mod["cantidad"],
                        "unidad" => mb_strtoupper($mod["unidad"]),
                        "costo" => mb_strtoupper($mod["costo"]),
                        "total" => $mod["total"],
                        "justificacion" => mb_strtoupper($mod["justificacion"]),
                        "ene" => $mod["ene"],
                        "feb" => $mod["feb"],
                        "mar" => $mod["mar"],
                        "abr" => $mod["abr"],
                        "may" => $mod["may"],
                        "jun" => $mod["jun"],
                        "jul" => $mod["jul"],
                        "ago" => $mod["ago"],
                        "sep" => $mod["sep"],
                        "oct" => $mod["oct"],
                        "nov" => $mod["nov"],
                        "dic" => $mod["dic"],
                        "total_actividad" => $mod["total_actividad"]
                    ]);
                    $total_actividades += (float)$nuevo_detalle_operacion->total;
                    $total_ene += $nuevo_detalle_operacion->ene ? (float)$nuevo_detalle_operacion->ene : 0;
                    $total_feb += $nuevo_detalle_operacion->feb ? (float)$nuevo_detalle_operacion->feb : 0;
                    $total_mar += $nuevo_detalle_operacion->mar ? (float)$nuevo_detalle_operacion->mar : 0;
                    $total_abr += $nuevo_detalle_operacion->abr ? (float)$nuevo_detalle_operacion->abr : 0;
                    $total_may += $nuevo_detalle_operacion->may ? (float)$nuevo_detalle_operacion->may : 0;
                    $total_jun += $nuevo_detalle_operacion->jun ? (float)$nuevo_detalle_operacion->jun : 0;
                    $total_jul += $nuevo_detalle_operacion->jul ? (float)$nuevo_detalle_operacion->jul : 0;
                    $total_ago += $nuevo_detalle_operacion->ago ? (float)$nuevo_detalle_operacion->ago : 0;
                    $total_sep += $nuevo_detalle_operacion->sep ? (float)$nuevo_detalle_operacion->sep : 0;
                    $total_oct += $nuevo_detalle_operacion->oct ? (float)$nuevo_detalle_operacion->oct : 0;
                    $total_nov += $nuevo_detalle_operacion->nov ? (float)$nuevo_detalle_operacion->nov : 0;
                    $total_dic += $nuevo_detalle_operacion->dic ? (float)$nuevo_detalle_operacion->dic : 0;
                    $total_final += (float)$nuevo_detalle_operacion->total_actividad;
                }
            }

            $nuevo_memoria_calculo->total_actividades = number_format($total_actividades, 2, ".", "");
            $nuevo_memoria_calculo->total_ene = $total_ene;
            $nuevo_memoria_calculo->total_feb = $total_feb;
            $nuevo_memoria_calculo->total_mar = $total_mar;
            $nuevo_memoria_calculo->total_abr = $total_abr;
            $nuevo_memoria_calculo->total_may = $total_may;
            $nuevo_memoria_calculo->total_jun = $total_jun;
            $nuevo_memoria_calculo->total_jul = $total_jul;
            $nuevo_memoria_calculo->total_ago = $total_ago;
            $nuevo_memoria_calculo->total_sep = $total_sep;
            $nuevo_memoria_calculo->total_oct = $total_oct;
            $nuevo_memoria_calculo->total_nov = $total_nov;
            $nuevo_memoria_calculo->total_dic = $total_dic;
            $nuevo_memoria_calculo->total_final = $total_final;
            $nuevo_memoria_calculo->save();

            $nuevo_memoria_calculo->formulario_cinco()->create(["fecha_registro" => date("Y-m-d")]);

            $user = Auth::user();
            Log::registrarLog("CREACIÓN", "MEMORIA DE CÁLCULO", "EL USUARIO $user->id REGISTRO UNA MEMORIA DE CÁLCULO", $user);

            DB::commit();

            return response()->JSON([
                'sw' => true,
                'memoria_calculo' => $nuevo_memoria_calculo,
                'msj' => 'El registro se realizó de forma correcta',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, MemoriaCalculo $memoria_calculo)
    {
        $this->validacion['formulario_seleccionado'] = 'required|unique:memoria_calculos,formulario_seleccionado,' . $memoria_calculo->id;
        $request->validate($this->validacion, $this->mensajes);


        DB::beginTransaction();
        try {
            $request["total_actividades"] = 0;
            $request["total_ene"] = 0;
            $request["total_feb"] = 0;
            $request["total_mar"] = 0;
            $request["total_abr"] = 0;
            $request["total_may"] = 0;
            $request["total_jun"] = 0;
            $request["total_jul"] = 0;
            $request["total_ago"] = 0;
            $request["total_sep"] = 0;
            $request["total_oct"] = 0;
            $request["total_nov"] = 0;
            $request["total_dic"] = 0;
            $request["total_final"] = 0;
            $array_form_seleccionado = explode("|", $request["formulario_seleccionado"]);
            $request["formulario_id"] = $array_form_seleccionado[1];
            $memoria_calculo->update(array_map('mb_strtoupper', $request->except("data", "eliminados", "tareas_eliminados", "partidas_eliminados", "mod_eliminados")));

            $eliminados = $request->eliminados;
            if (isset($eliminados) && count($eliminados) > 0) {
                foreach ($eliminados as $eliminado) {
                    $operacion = MemoriaOperacion::find($eliminado);
                    $operacion->delete();
                }
            }


            $mod_eliminados = $request->mod_eliminados;
            if (isset($mod_eliminados) && count($mod_eliminados) > 0) {
                foreach ($mod_eliminados as $eliminado) {
                    $mod = MemoriaOperacionDetalle::find($eliminado);
                    $mod->delete();
                }
            }

            $total_actividades = 0;
            $total_ene = 0;
            $total_feb = 0;
            $total_mar = 0;
            $total_abr = 0;
            $total_may = 0;
            $total_jun = 0;
            $total_jul = 0;
            $total_ago = 0;
            $total_sep = 0;
            $total_oct = 0;
            $total_nov = 0;
            $total_dic = 0;
            $total_final = 0;

            $data = $request->data;
            foreach ($data as $d) {
                if (!isset($d["id"]) || $d["id"] == 0 || $d["id"] == "") {
                    $nueva_operacion = $memoria_calculo->operacions()->create([
                        "operacion_id" => mb_strtoupper($d["operacion_id"]),
                        // "detalle_operacion_id" => mb_strtoupper($d["detalle_operacion_id"]),
                        "ue" => mb_strtoupper($d["ue"]),
                        "prog" => mb_strtoupper($d["prog"]),
                        "act" => mb_strtoupper($d["act"]),
                        "lugar" => mb_strtoupper($d["lugar"]),
                        "responsable" => mb_strtoupper($d["responsable"]),
                        "justificacion" => mb_strtoupper($d["justificacion"]),
                        "total_operacion" => $d["total_operacion"],
                        "fecha_registro" => date("Y-m-d")
                    ]);
                } else {
                    // actualizar
                    $memoria_operacion = MemoriaOperacion::find($d["id"]);
                    $memoria_operacion->update([
                        "operacion_id" => mb_strtoupper($d["operacion_id"]),
                        // "detalle_operacion_id" => mb_strtoupper($d["detalle_operacion_id"]),
                        "ue" => mb_strtoupper($d["ue"]),
                        "prog" => mb_strtoupper($d["prog"]),
                        "act" => mb_strtoupper($d["act"]),
                        "lugar" => mb_strtoupper($d["lugar"]),
                        "responsable" => mb_strtoupper($d["responsable"]),
                        "justificacion" => mb_strtoupper($d["justificacion"]),
                        "total_operacion" => $d["total_operacion"],
                    ]);
                    $nueva_operacion = $memoria_operacion;
                }

                foreach ($d["memoria_operacion_detalles"] as $mod) {
                    if (!isset($mod["id"]) || $mod["id"] == 0 || $mod["id"] == "") {
                        $nuevo_detalle_operacion = $nueva_operacion->memoria_operacion_detalles()->create([
                            "ue" => mb_strtoupper($nueva_operacion->ue),
                            "prog" => mb_strtoupper($nueva_operacion->prog),
                            "act" => mb_strtoupper($nueva_operacion->act),
                            "lugar" => mb_strtoupper($nueva_operacion->lugar),
                            "responsable" => mb_strtoupper($nueva_operacion->responsable),
                            "justificacion" => mb_strtoupper($d["justificacion"]),
                            "partida_id" => mb_strtoupper($mod["partida_id"]),
                            "partida" => mb_strtoupper($mod["partida"]),
                            "nro" => mb_strtoupper($mod["nro"]),
                            "descripcion" => mb_strtoupper($mod["descripcion"]),
                            // "descripcion_detallada" => mb_strtoupper($mod["descripcion_detallada"]),
                            "cantidad" => $mod["cantidad"],
                            "unidad" => mb_strtoupper($mod["unidad"]),
                            "costo" => mb_strtoupper($mod["costo"]),
                            "total" => $mod["total"],
                            "justificacion" => mb_strtoupper($mod["justificacion"]),
                            "ene" => $mod["ene"],
                            "feb" => $mod["feb"],
                            "mar" => $mod["mar"],
                            "abr" => $mod["abr"],
                            "may" => $mod["may"],
                            "jun" => $mod["jun"],
                            "jul" => $mod["jul"],
                            "ago" => $mod["ago"],
                            "sep" => $mod["sep"],
                            "oct" => $mod["oct"],
                            "nov" => $mod["nov"],
                            "dic" => $mod["dic"],
                            "total_actividad" => $mod["total_actividad"]
                        ]);
                    } else {
                        $memoria_operacion_detalle = MemoriaOperacionDetalle::find($mod["id"]);
                        $memoria_operacion_detalle->update([
                            "ue" => mb_strtoupper($nueva_operacion->ue),
                            "prog" => mb_strtoupper($nueva_operacion->prog),
                            "act" => mb_strtoupper($nueva_operacion->act),
                            "lugar" => mb_strtoupper($nueva_operacion->lugar),
                            "responsable" => mb_strtoupper($nueva_operacion->responsable),
                            "justificacion" => mb_strtoupper($d["justificacion"]),
                            "partida_id" => mb_strtoupper($mod["partida_id"]),
                            "partida" => mb_strtoupper($mod["partida"]),
                            "nro" => mb_strtoupper($mod["nro"]),
                            "descripcion" => mb_strtoupper($mod["descripcion"]),
                            // "descripcion_detallada" => mb_strtoupper($mod["descripcion_detallada"]),
                            "cantidad" => $mod["cantidad"],
                            "unidad" => mb_strtoupper($mod["unidad"]),
                            "costo" => mb_strtoupper($mod["costo"]),
                            "total" => $mod["total"],
                            "justificacion" => mb_strtoupper($mod["justificacion"]),
                            "ene" => $mod["ene"],
                            "feb" => $mod["feb"],
                            "mar" => $mod["mar"],
                            "abr" => $mod["abr"],
                            "may" => $mod["may"],
                            "jun" => $mod["jun"],
                            "jul" => $mod["jul"],
                            "ago" => $mod["ago"],
                            "sep" => $mod["sep"],
                            "oct" => $mod["oct"],
                            "nov" => $mod["nov"],
                            "dic" => $mod["dic"],
                            "total_actividad" => $mod["total_actividad"]
                        ]);
                        $nuevo_detalle_operacion = $memoria_operacion_detalle;
                    }
                    $total_actividades += (float)$nuevo_detalle_operacion->total;
                    $total_ene += $nuevo_detalle_operacion->ene ? (float)$nuevo_detalle_operacion->ene : 0;
                    $total_feb += $nuevo_detalle_operacion->feb ? (float)$nuevo_detalle_operacion->feb : 0;
                    $total_mar += $nuevo_detalle_operacion->mar ? (float)$nuevo_detalle_operacion->mar : 0;
                    $total_abr += $nuevo_detalle_operacion->abr ? (float)$nuevo_detalle_operacion->abr : 0;
                    $total_may += $nuevo_detalle_operacion->may ? (float)$nuevo_detalle_operacion->may : 0;
                    $total_jun += $nuevo_detalle_operacion->jun ? (float)$nuevo_detalle_operacion->jun : 0;
                    $total_jul += $nuevo_detalle_operacion->jul ? (float)$nuevo_detalle_operacion->jul : 0;
                    $total_ago += $nuevo_detalle_operacion->ago ? (float)$nuevo_detalle_operacion->ago : 0;
                    $total_sep += $nuevo_detalle_operacion->sep ? (float)$nuevo_detalle_operacion->sep : 0;
                    $total_oct += $nuevo_detalle_operacion->oct ? (float)$nuevo_detalle_operacion->oct : 0;
                    $total_nov += $nuevo_detalle_operacion->nov ? (float)$nuevo_detalle_operacion->nov : 0;
                    $total_dic += $nuevo_detalle_operacion->dic ? (float)$nuevo_detalle_operacion->dic : 0;
                    $total_final += (float)$nuevo_detalle_operacion->total_actividad;
                }
            }

            $memoria_calculo->total_actividades = number_format($total_actividades, 2, ".", "");
            $memoria_calculo->total_ene = $total_ene;
            $memoria_calculo->total_feb = $total_feb;
            $memoria_calculo->total_mar = $total_mar;
            $memoria_calculo->total_abr = $total_abr;
            $memoria_calculo->total_may = $total_may;
            $memoria_calculo->total_jun = $total_jun;
            $memoria_calculo->total_jul = $total_jul;
            $memoria_calculo->total_ago = $total_ago;
            $memoria_calculo->total_sep = $total_sep;
            $memoria_calculo->total_oct = $total_oct;
            $memoria_calculo->total_nov = $total_nov;
            $memoria_calculo->total_dic = $total_dic;
            $memoria_calculo->total_final = $total_final;
            $memoria_calculo->save();

            if (!$memoria_calculo->formulario_cinco) {
                $memoria_calculo->formulario_cinco()->create(["fecha_registro" => date("Y-m-d")]);
            }

            $user = Auth::user();
            Log::registrarLog("MODIFICACIÓN", "MEMORIA DE CÁLCULO", "EL USUARIO $user->id MODIFICÓ UNA MEMORIA DE CÁLCULO", $user);

            DB::commit();
            return response()->JSON([
                'sw' => true,
                'memoria_calculo' => $memoria_calculo,
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

    public function show(MemoriaCalculo $memoria_calculo)
    {
        return response()->JSON([
            'sw' => true,
            'memoria_calculo' => $memoria_calculo->load("operacions.memoria_operacion_detalles", "operacions.detalle_operacion", "operacions.operacion.subdireccion")
        ], 200);
    }

    public function destroy(MemoriaCalculo $memoria_calculo)
    {
        $existe_certificaciones = MemoriaOperacion::where("memoria_id", $memoria_calculo->id)
            ->join("certificacions", "certificacions.mo_id", "=", "memoria_operacions.id")
            ->get();
        if (count($existe_certificaciones) > 0) {
            return response()->JSON([
                'sw' => false,
                'msj' => 'No es posible eliminar este registro debido a que existen certificaciones realizadas'
            ], 200);
        }


        DB::beginTransaction();
        try {
            $memoria_calculo->operacions()->delete();
            $memoria_calculo->formulario_cinco()->delete();
            $memoria_calculo->delete();

            $user = Auth::user();
            Log::registrarLog("ELIMINACIÓN", "MEMORIA DE CÁLCULO", "EL USUARIO $user->id ELIMINÓ UNA MEMORIA DE CÁLCULO", $user);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'msj' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOperaciones(Request $request)
    {
        $formulario_cuatro = FormularioCuatro::find($request->formulario_id);
        $memoria_calculo = $formulario_cuatro->memoria_calculo;
        if ($memoria_calculo) {
            $operaciones = $memoria_calculo->operacions;
            return response()->JSON($operaciones);
        }
        return response()->JSON([]);
    }
    public function getTabla(MemoriaCalculo $memoria_calculo)
    {
        $html = view("parcial.memoria_calculo", compact("memoria_calculo"))->render();
        return response()->JSON($html);
    }
}
