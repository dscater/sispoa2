<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\DetalleFormulario;
use App\Models\DetalleOperacion;
use App\Models\Log;
use App\Models\MemoriaCalculo;
use App\Models\Operacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetalleFormularioController extends Controller
{
    public $validacion = [
        'formulario_seleccionado' => 'required||unique:detalle_formularios,formulario_seleccionado',
    ];

    public $mensajes = [];

    public function index(Request $request)
    {
        $detalle_formularios = [];
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "ENLACE") {
            $detalle_formularios = DetalleFormulario::select("detalle_formularios.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "detalle_formularios.formulario_id")
                ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                ->get();
        } else {
            $detalle_formularios = DetalleFormulario::all();
        }
        return response()->JSON(['detalle_formularios' => $detalle_formularios, 'total' => count($detalle_formularios)], 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $request['fecha_registro'] = date('Y-m-d');
            $array_form_seleccionado = explode("|", $request["formulario_seleccionado"]);
            $request["formulario_id"] = $array_form_seleccionado[1];
            $nuevo_detalle_formulario = DetalleFormulario::create(array_map('mb_strtoupper', $request->except("data")));

            $data = $request->data;
            foreach ($data as $d) {
                $nueva_operacion = $nuevo_detalle_formulario->operacions()->create([
                    "codigo_operacion" => mb_strtoupper($d["codigo_operacion"]),
                    "subdireccion_id" => $d["subdireccion_id"] ? $d["subdireccion_id"] : null,
                    "operacion" => mb_strtoupper($d["operacion"]),
                    "ponderacion" => $d["ponderacion"],
                    "resultado_esperado" => mb_strtoupper($d["resultado_esperado"]),
                    "medios_verificacion" => mb_strtoupper($d["medios_verificacion"]),
                ]);
                foreach ($d["detalle_operaciones"] as $do) {
                    $nueva_operacion->detalle_operaciones()->create([
                        "ponderacion" => $nueva_operacion->ponderacion,
                        "resultado_esperado" => mb_strtoupper($nueva_operacion->resultado_esperado),
                        "medios_verificacion" => mb_strtoupper($nueva_operacion->medios_verificacion),
                        "codigo_tarea" => mb_strtoupper($do["codigo_tarea"]),
                        "actividad_tarea" => mb_strtoupper($do["actividad_tarea"]),
                        "pt_e" => mb_strtoupper($do["pt_e"]),
                        "pt_f" => mb_strtoupper($do["pt_f"]),
                        "pt_m" => mb_strtoupper($do["pt_m"]),
                        "st_a" => mb_strtoupper($do["st_a"]),
                        "st_m" => mb_strtoupper($do["st_m"]),
                        "st_j" => mb_strtoupper($do["st_j"]),
                        "tt_j" => mb_strtoupper($do["tt_j"]),
                        "tt_a" => mb_strtoupper($do["tt_a"]),
                        "tt_s" => mb_strtoupper($do["tt_s"]),
                        "ct_o" => mb_strtoupper($do["ct_o"]),
                        "ct_n" => mb_strtoupper($do["ct_n"]),
                        "ct_d" => mb_strtoupper($do["ct_d"]),
                        "inicio" => $do["inicio"],
                        "final" => $do["final"],
                    ]);
                }
            }

            $user = Auth::user();
            Log::registrarLog("CREACIÓN", "DETALLE FORMULARIO CUATRO", "EL USUARIO $user->id REGISTRO UN DETALLE FORMULARIO CUATRO", $user);
            DB::commit();

            return response()->JSON([
                'sw' => true,
                'detalle_formulario' => $nuevo_detalle_formulario,
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

    public function update(Request $request, DetalleFormulario $detalle_formulario)
    {
        $this->validacion['formulario_seleccionado'] = 'required||unique:detalle_formularios,formulario_seleccionado,' . $detalle_formulario->id;
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {

            $array_form_seleccionado = explode("|", $request["formulario_seleccionado"]);
            $request["formulario_id"] = $array_form_seleccionado[1];
            $detalle_formulario->update(array_map('mb_strtoupper', $request->except("data", "eliminados", "do_eliminados")));

            $eliminados = $request->eliminados;
            $do_eliminados = $request->do_eliminados;
            if (isset($eliminados) && count($eliminados) > 0) {
                foreach ($eliminados as $eliminado) {
                    $operacion = Operacion::find($eliminado);
                    DB::delete("DELETE FROM detalle_operacions WHERE operacion_id=$operacion->id");
                    $operacion->delete();
                }
            }
            if (isset($do_eliminados) && count($do_eliminados) > 0) {
                foreach ($do_eliminados as $eliminado) {
                    $do = DetalleOperacion::find($eliminado);
                    $do->delete();
                }
            }

            $data = $request->data;
            foreach ($data as $d) {
                if ($d["id"] == 0 || $d["id"] == "") {
                    $nueva_operacion = $detalle_formulario->operacions()->create([
                        "subdireccion_id" => $d["subdireccion_id"] ? $d["subdireccion_id"] : null,
                        "codigo_operacion" => mb_strtoupper($d["codigo_operacion"]),
                        "operacion" => mb_strtoupper($d["operacion"]),
                        "ponderacion" => $d["ponderacion"],
                        "resultado_esperado" => mb_strtoupper($d["resultado_esperado"]),
                        "medios_verificacion" => mb_strtoupper($d["medios_verificacion"]),
                    ]);
                } else {
                    $operacion = Operacion::find($d["id"]);
                    $nueva_operacion = $operacion;
                    $operacion->update([
                        "subdireccion_id" => $d["subdireccion_id"] ? $d["subdireccion_id"] : null,
                        "codigo_operacion" => mb_strtoupper($d["codigo_operacion"]),
                        "operacion" => mb_strtoupper($d["operacion"]),
                        "ponderacion" => $d["ponderacion"],
                        "resultado_esperado" => mb_strtoupper($d["resultado_esperado"]),
                        "medios_verificacion" => mb_strtoupper($d["medios_verificacion"]),
                    ]);
                }


                foreach ($d["detalle_operaciones"] as $do) {
                    if ($do["id"] == 0 || $do["id"] == "") {
                        $nueva_operacion->detalle_operaciones()->create([
                            "ponderacion" => $nueva_operacion->ponderacion,
                            "resultado_esperado" => mb_strtoupper($nueva_operacion->resultado_esperado),
                            "medios_verificacion" => mb_strtoupper($nueva_operacion->medios_verificacion),
                            "codigo_tarea" => mb_strtoupper($do["codigo_tarea"]),
                            "actividad_tarea" => mb_strtoupper($do["actividad_tarea"]),
                            "pt_e" => mb_strtoupper($do["pt_e"]),
                            "pt_f" => mb_strtoupper($do["pt_f"]),
                            "pt_m" => mb_strtoupper($do["pt_m"]),
                            "st_a" => mb_strtoupper($do["st_a"]),
                            "st_m" => mb_strtoupper($do["st_m"]),
                            "st_j" => mb_strtoupper($do["st_j"]),
                            "tt_j" => mb_strtoupper($do["tt_j"]),
                            "tt_a" => mb_strtoupper($do["tt_a"]),
                            "tt_s" => mb_strtoupper($do["tt_s"]),
                            "ct_o" => mb_strtoupper($do["ct_o"]),
                            "ct_n" => mb_strtoupper($do["ct_n"]),
                            "ct_d" => mb_strtoupper($do["ct_d"]),
                            "inicio" => $do["inicio"],
                            "final" => $do["final"],
                        ]);
                    } else {
                        $detalle_operacion = DetalleOperacion::find($do["id"]);
                        $detalle_operacion->update([
                            "ponderacion" => $nueva_operacion->ponderacion,
                            "resultado_esperado" => mb_strtoupper($nueva_operacion->resultado_esperado),
                            "medios_verificacion" => mb_strtoupper($nueva_operacion->medios_verificacion),
                            "codigo_tarea" => mb_strtoupper($do["codigo_tarea"]),
                            "actividad_tarea" => mb_strtoupper($do["actividad_tarea"]),
                            "pt_e" => mb_strtoupper($do["pt_e"]),
                            "pt_f" => mb_strtoupper($do["pt_f"]),
                            "pt_m" => mb_strtoupper($do["pt_m"]),
                            "st_a" => mb_strtoupper($do["st_a"]),
                            "st_m" => mb_strtoupper($do["st_m"]),
                            "st_j" => mb_strtoupper($do["st_j"]),
                            "tt_j" => mb_strtoupper($do["tt_j"]),
                            "tt_a" => mb_strtoupper($do["tt_a"]),
                            "tt_s" => mb_strtoupper($do["tt_s"]),
                            "ct_o" => mb_strtoupper($do["ct_o"]),
                            "ct_n" => mb_strtoupper($do["ct_n"]),
                            "ct_d" => mb_strtoupper($do["ct_d"]),
                            "inicio" => $do["inicio"],
                            "final" => $do["final"],
                        ]);
                    }
                }
            }

            $user = Auth::user();
            Log::registrarLog("MODIFICACIÓN", "DETALLE FORMULARIO CUATRO", "EL USUARIO $user->id MODIFICÓ UN DETALLE FORMULARIO CUATRO", $user);

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

    public function show(DetalleFormulario $detalle_formulario)
    {
        return response()->JSON([
            'sw' => true,
            'detalle_formulario' => $detalle_formulario->load("operacions.subdireccion")
        ], 200);
    }

    public function destroy(DetalleFormulario $detalle_formulario)
    {
        // validar si existe un registro de memoria de calculo
        $existe = MemoriaCalculo::where("formulario_id", $detalle_formulario->formulario_id)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "formulario_cuatro" => $detalle_formulario->formulario, "msj" => "No es posible eliminar este registro, debido a que su información esta siendo utilizada en Memoria de cálculo"]);
        }

        $existe = Certificacion::where("formulario_id", $detalle_formulario->formulario->id)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "formulario_cuatro" => $detalle_formulario->formulario, "msj" => "No es posible eliminar este registro, porque la información esta siendo utilizada en Certificaciones"]);
        }

        DB::beginTransaction();
        try {
            foreach ($detalle_formulario->operacions as $o) {
                DB::delete("DELETE FROM detalle_operacions WHERE operacion_id = $o->id");
                $o->delete();
            }

            $detalle_formulario->delete();

            $user = Auth::user();
            Log::registrarLog("ELIMINACIÓN", "DETALLE FORMULARIO CUATRO", "EL USUARIO $user->id ELIMINÓ UN DETALLE FORMULARIO CUATRO", $user);

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

    public function seguimiento_trimestral(Request $request)
    {
        $detalle_formulario = DetalleFormulario::where("formulario_seleccionado", $request->formulario_id)->get()->first();
        if ($detalle_formulario) {
            $html = view("parcial.seguimiento_trimestral", compact("detalle_formulario"))->render();
        } else {
            $html = '<h5 class="w-100 text-center">SIN RESULTADOS</h5>';
        }
        return response()->JSON($html);
    }
}
