<?php

namespace App\Http\Controllers;

use App\Models\ActividadTarea;
use App\Models\FCOperacion;
use App\Models\FormularioCinco;
use App\Models\FormularioCuatro;
use App\Models\LugarResponsable;
use App\Models\MemoriaCalculo;
use App\Models\MemoriaOperacion;
use App\Models\MemoriaOperacionDetalle;
use App\Models\Operacion;
use App\Models\Partida;
use App\Models\VerificacionActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FormularioCincoController extends Controller
{
    public $validacion = [
        'formulario_id' => 'required||unique:formulario_cinco,formulario_id',
    ];

    public $mensajes = [];

    public function index(Request $request)
    {
        $listado = [];
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "ENLACE") {
            $listado = FormularioCinco::select("formulario_cinco.*")
                ->join("memoria_calculos", "memoria_calculos.id", "=", "formulario_cinco.memoria_id")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                ->where("unidad_id", Auth::user()->unidad_id)
                ->get();
        } else {
            $listado = FormularioCinco::all();
        }
        return response()->JSON(['listado' => $listado, 'total' => count($listado)], 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        $request['total_formulario'] = 0;
        $nuevo_formulario_cinco = FormularioCinco::create(array_map('mb_strtoupper', $request->except("data")));

        $data = $request->data;
        $total_formulario = 0;
        foreach ($data as $d) {
            $nueva_operacion = $nuevo_formulario_cinco->operacions()->create([
                "operacion_id" => mb_strtoupper($d["operacion_id"]),
                "total_operacion" => mb_strtoupper($d["total_operacion"]),
            ]);
            foreach ($d["lugar_responsables"] as $lugar_responsable) {
                $nuevo_lugar_responsable = $nueva_operacion->lugar_responsables()->create([
                    "lugar" => mb_strtoupper($lugar_responsable["lugar"]),
                    "responsable" => mb_strtoupper($lugar_responsable["responsable"]),
                ]);
                foreach ($lugar_responsable["actividad_tareas"] as $tarea) {
                    $nueva_tarea = $nuevo_lugar_responsable->actividad_tareas()->create([
                        "fco_id" => $nueva_operacion->id,
                        "detalle_operacion_id" => $tarea["detalle_operacion_id"]
                    ]);
                    foreach ($tarea["partidas"] as $partida) {
                        $nueva_partida = $nueva_tarea->partidas()->create([
                            "lugar_responsable_id" => $nueva_tarea->lugar_responsable_id,
                            "partida" => $partida["partida"],
                            "descripcion" => mb_strtoupper($partida["descripcion"]),
                            "cantidad" => $partida["cantidad"],
                            "unidad" => $partida["unidad"],
                            "costo" => $partida["costo"],
                            "t42" => $partida["t42"],
                            "ue" => $partida["ue"],
                            "prog" => $partida["prog"],
                            "act" => $partida["act"],
                            "otros" => $partida["otros"],
                        ]);
                        $total_formulario += (float)$nueva_partida->cantidad * (float)$nueva_partida->costo;
                    }
                }
            }
        }
        $nuevo_formulario_cinco->total_formulario = $total_formulario;
        $nuevo_formulario_cinco->save();

        return response()->JSON([
            'sw' => true,
            'formulario_cinco' => $nuevo_formulario_cinco,
            'msj' => 'El registro se realizó de forma correcta',
        ], 200);
    }

    public function update(Request $request, FormularioCinco $formulario_cinco)
    {
        $this->validacion['formulario_id'] = 'required||unique:formulario_cinco,formulario_id,' . $formulario_cinco->id;
        $request->validate($this->validacion, $this->mensajes);
        $formulario_cinco->update(array_map('mb_strtoupper', $request->except("data", "eliminados", "tareas_eliminados", "partidas_eliminados")));

        $eliminados = $request->eliminados;
        if (isset($eliminados) && count($eliminados) > 0) {
            foreach ($eliminados as $eliminado) {
                $operacion = FCOperacion::find($eliminado);
                foreach ($operacion->lugar_responsables as $lugar) {
                    DB::delete("DELETE FROM partidas WHERE lugar_responsable_id=$lugar->id");
                    DB::delete("DELETE FROM actividad_tareas WHERE lugar_responsable_id=$lugar->id");
                    $lugar->delete();
                }
                $operacion->delete();
            }
        }

        $tareas_eliminados = $request->tareas_eliminados;
        if (isset($tareas_eliminados) && count($tareas_eliminados) > 0) {
            foreach ($tareas_eliminados as $eliminado) {
                $tarea = ActividadTarea::find($eliminado);
                $tarea->delete();
            }
        }

        $partidas_eliminados = $request->partidas_eliminados;
        if (isset($partidas_eliminados) && count($partidas_eliminados) > 0) {
            foreach ($partidas_eliminados as $eliminado) {
                $partida = Partida::find($eliminado);
                $partida->delete();
            }
        }

        $data = $request->data;
        $total_formulario = 0;
        foreach ($data as $d) {
            if ($d["id"] == 0 || $d["id"] == "") {
                // crear
                $nueva_operacion = $formulario_cinco->operacions()->create([
                    "operacion_id" => mb_strtoupper($d["operacion_id"]),
                    "total_operacion" => mb_strtoupper($d["total_operacion"]),
                ]);
            } else {
                // actualizar
                $fco = FCOperacion::find($d["id"]);
                $fco->update([
                    "operacion_id" => mb_strtoupper($d["operacion_id"]),
                    "total_operacion" => mb_strtoupper($d["total_operacion"]),
                ]);
                $nueva_operacion = $fco;
            }
            foreach ($d["lugar_responsables"] as $lugar_responsable) {
                if ($lugar_responsable["id"] == 0 || $lugar_responsable["id"] == "") {
                    $nuevo_lugar_responsable = $nueva_operacion->lugar_responsables()->create([
                        "lugar" => mb_strtoupper($lugar_responsable["lugar"]),
                        "responsable" => mb_strtoupper($lugar_responsable["responsable"]),
                    ]);
                } else {
                    $lr = LugarResponsable::find($lugar_responsable["id"]);
                    $lr->update([
                        "lugar" => mb_strtoupper($lugar_responsable["lugar"]),
                        "responsable" => mb_strtoupper($lugar_responsable["responsable"]),
                    ]);
                    $nuevo_lugar_responsable = $lr;
                }
                foreach ($lugar_responsable["actividad_tareas"] as $tarea) {
                    if ($tarea["id"] == 0 || $tarea["id"] == "") {
                        $nueva_tarea = $nuevo_lugar_responsable->actividad_tareas()->create([
                            "fco_id" => $nueva_operacion->id,
                            "detalle_operacion_id" => $tarea["detalle_operacion_id"]
                        ]);
                    } else {
                        $t = ActividadTarea::find($tarea["id"]);
                        $t->update([
                            "fco_id" => $nueva_operacion->id,
                            "detalle_operacion_id" => $tarea["detalle_operacion_id"]
                        ]);
                        $nueva_tarea = $t;
                    }
                    foreach ($tarea["partidas"] as $partida) {
                        if ($partida["id"] == 0 || $partida["id"] == "") {
                            $nueva_partida = $nueva_tarea->partidas()->create([
                                "lugar_responsable_id" => $nueva_tarea->lugar_responsable_id,
                                "partida" => $partida["partida"],
                                "descripcion" => mb_strtoupper($partida["descripcion"]),
                                "cantidad" => $partida["cantidad"],
                                "unidad" => $partida["unidad"],
                                "costo" => $partida["costo"],
                                "t42" => $partida["t42"],
                                "ue" => $partida["ue"],
                                "prog" => $partida["prog"],
                                "act" => $partida["act"],
                                "otros" => $partida["otros"],
                            ]);
                        } else {
                            $p = Partida::find($partida["id"]);
                            $p->update([
                                "lugar_responsable_id" => $nueva_tarea->lugar_responsable_id,
                                "partida" => $partida["partida"],
                                "descripcion" => mb_strtoupper($partida["descripcion"]),
                                "cantidad" => $partida["cantidad"],
                                "unidad" => $partida["unidad"],
                                "costo" => $partida["costo"],
                                "t42" => $partida["t42"],
                                "ue" => $partida["ue"],
                                "prog" => $partida["prog"],
                                "act" => $partida["act"],
                                "otros" => $partida["otros"],
                            ]);
                            $nueva_partida = $p;
                        }
                        $total_formulario += (float)$nueva_partida->cantidad * (float)$nueva_partida->costo;
                    }
                }
            }
        }
        $formulario_cinco->total_formulario = $total_formulario;
        $formulario_cinco->save();

        return response()->JSON([
            'sw' => true,
            'formulario_cinco' => $formulario_cinco,
            'msj' => 'El registro se actualizó de forma correcta'
        ], 200);
    }

    public function show(FormularioCinco $formulario_cinco)
    {
        // armar repetidos
        $array_registros = [];
        $codigos = DB::select("SELECT DISTINCT operacion_id FROM memoria_operacions WHERE memoria_id = $formulario_cinco->memoria_id");

        foreach ($codigos as $cod) {
            $operacion = Operacion::find($cod->operacion_id);
            $array_registros[] = [
                "codigo_operacion" => $operacion->codigo_operacion,
                "descripcion_operacion" => $operacion->operacion,
                "actividads" => MemoriaOperacion::where("memoria_id", $formulario_cinco->memoria_id)->where("operacion_id", $cod->operacion_id)->get()
            ];
        }

        return response()->JSON([
            'sw' => true,
            'formulario_cinco' => $formulario_cinco,
            "array_registros" => $array_registros
        ], 200);
    }

    public function destroy(FormularioCinco $formulario_cinco)
    {
        foreach ($formulario_cinco->operacions as $operacion) {
            foreach ($operacion->lugar_responsables as $lugar) {
                DB::delete("DELETE FROM partidas WHERE lugar_responsable_id=$lugar->id");
                DB::delete("DELETE FROM actividad_tareas WHERE lugar_responsable_id=$lugar->id");
                $lugar->delete();
            }
            $operacion->delete();
        }
        $formulario_cinco->delete();
        return response()->JSON([
            'sw' => true,
            'msj' => 'El registro se eliminó correctamente'
        ], 200);
    }

    public function getOperaciones(Request $request)
    {
        $formulario_cuatro = FormularioCuatro::find($request->formulario_id);
        $formulario_cinco = $formulario_cuatro->formulario_cinco;
        if ($formulario_cinco) {
            $operaciones = $formulario_cinco->operacions;
            return response()->JSON($operaciones);
        }
        return response()->JSON([]);
    }
    public function getTabla(FormularioCinco $formulario_cinco)
    {
        // armar repetidos
        $array_registros = FormularioCincoController::armaRepetidos($formulario_cinco);
        $verificacion_actividad = VerificacionActividad::get()->first();
        $html = view("parcial.formulario_cinco", compact("array_registros", "formulario_cinco", "verificacion_actividad"))->render();
        return response()->JSON($html);
    }

    public static function armaRepetidos($formulario_cinco)
    {
        $array_registros = [];
        $operaciones = $formulario_cinco->memoria->operacions;
        foreach ($operaciones as $operacion) {
            $rowspan_operacion = 0;
            // buscar los lugares sin repetir de la operacion_actual
            // obtener los lugares sin repetir
            $lugares = MemoriaOperacionDetalle::select("lugar")
                ->where("memoria_operacion_id", $operacion->id)
                ->distinct("lugar")
                ->get()
                ->pluck("lugar")
                ->toArray();

            $array_lugares = [];
            foreach ($lugares as $lugar) {
                $rowspan_lugar = 0;
                // armar los responsables sin repetir
                $resposanbles = MemoriaOperacionDetalle::select("responsable")
                    ->where("memoria_operacion_id", $operacion->id)
                    ->where("lugar", $lugar)
                    ->distinct("responsable")
                    ->get()
                    ->pluck("responsable")
                    ->toArray();

                $array_responsables = [];
                foreach ($resposanbles as $responsable) {
                    $registros = MemoriaOperacionDetalle::where("memoria_operacion_id", $operacion->id)
                        ->where("lugar", $lugar)
                        ->where("responsable", $responsable)
                        ->get();
                    $array_responsables[] = [
                        "responsable" => $responsable,
                        "registros" => $registros,
                        "rowspan" => count($registros),
                    ];
                    $rowspan_operacion += count($registros);
                    $rowspan_lugar += count($registros);
                }

                // unir los registros los responsables con lugares
                $array_lugares[] = [
                    "lugar" => $lugar,
                    "rowspan" => $rowspan_lugar,
                    "responsables" =>  $array_responsables
                ];
            }

            // unir los registros de lugares y responsables con el array $array_lugares
            $array_registros[] = [
                "operacion_id" => $operacion->operacion_id,
                "codigo_operacion" => $operacion->codigo_operacion,
                "subdireccion" => $operacion->operacion->subdireccion,
                "operacion" => $operacion->operacion->operacion,
                "codigo_tarea" => $operacion->codigo_actividad,
                "tarea" => "",
                // "tarea" => $operacion->detalle_operacion->actividad_tarea,
                "rowspan" => $rowspan_operacion,
                "lugares" => $array_lugares
            ];
        }

        // agrupar por operaciones
        $operaciones_filtro = DB::select("SELECT DISTINCT(operacion_id) FROM memoria_operacions WHERE memoria_id = " . $formulario_cinco->memoria->id . ";");
        $array_registros = FormularioCincoController::agruparOperaciones($operaciones_filtro, $array_registros);

        return $array_registros;
    }

    public static function agruparOperaciones($operaciones, $registros)
    {
        $nuevo_array = [];
        $suma_rowspan = [];
        $suma_registros = [];
        foreach ($operaciones as $op) {
            $operacion = Operacion::find($op->operacion_id);
            $suma_rowspan[$op->operacion_id] = 0;

            foreach ($registros as $value) {
                if ($value["operacion_id"] == $op->operacion_id) {
                    $suma_rowspan[$op->operacion_id] += (int)$value["rowspan"];
                    $suma_registros[$op->operacion_id][] = $value;
                }
            }

            $nuevo_array[] = [
                "rowspan" => $suma_rowspan[$op->operacion_id],
                "codigo_operacion" => $operacion->codigo_operacion,
                "operacion" => $operacion->operacion,
                "registros" => $suma_registros[$op->operacion_id],
            ];
        }
        return $nuevo_array;
    }
}
