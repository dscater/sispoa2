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
use Illuminate\Support\Facades\Log as FacadesLog;

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
        if (Auth::user()->tipo != 'ENLACE') {
            $this->validacion['formulario_seleccionado'] = 'required||unique:detalle_formularios,formulario_seleccionado,' . $detalle_formulario->id;
            $request->validate($this->validacion, $this->mensajes);
        }
        DB::beginTransaction();
        try {
            if (Auth::user()->tipo == 'ENLACE') {
                $id_operacions = $request->id_operacions;
                foreach ($id_operacions as $id_ope) {
                    $id_detalles = $request["actividads" . $id_ope];
                    foreach ($id_detalles as $key_det => $id_det) {
                        $pt_e = $request->hasFile("files_pt_e" . $id_ope . $id_det) ? $request->file("files_pt_e" . $id_ope . $id_det) : null;
                        $pt_f = $request->hasFile("files_pt_f" . $id_ope . $id_det) ? $request->file("files_pt_f" . $id_ope . $id_det) : null;
                        $pt_m = $request->hasFile("files_pt_m" . $id_ope . $id_det) ? $request->file("files_pt_m" . $id_ope . $id_det) : null;
                        $st_a = $request->hasFile("files_st_a" . $id_ope . $id_det) ? $request->file("files_st_a" . $id_ope . $id_det) : null;
                        $st_m = $request->hasFile("files_st_m" . $id_ope . $id_det) ? $request->file("files_st_m" . $id_ope . $id_det) : null;
                        $st_j = $request->hasFile("files_st_j" . $id_ope . $id_det) ? $request->file("files_st_j" . $id_ope . $id_det) : null;
                        $tt_j = $request->hasFile("files_tt_j" . $id_ope . $id_det) ? $request->file("files_tt_j" . $id_ope . $id_det) : null;
                        $tt_a = $request->hasFile("files_tt_a" . $id_ope . $id_det) ? $request->file("files_tt_a" . $id_ope . $id_det) : null;
                        $tt_s = $request->hasFile("files_tt_s" . $id_ope . $id_det) ? $request->file("files_tt_s" . $id_ope . $id_det) : null;
                        $ct_o = $request->hasFile("files_ct_o" . $id_ope . $id_det) ? $request->file("files_ct_o" . $id_ope . $id_det) : null;
                        $ct_n = $request->hasFile("files_ct_n" . $id_ope . $id_det) ? $request->file("files_ct_n" . $id_ope . $id_det) : null;
                        $ct_d = $request->hasFile("files_ct_d" . $id_ope . $id_det) ? $request->file("files_ct_d" . $id_ope . $id_det) : null;

                        // detalle
                        $detalle_operacion = DetalleOperacion::findOrFail($id_det);
                        $antiguo = $detalle_operacion->pt_e_file;
                        FacadesLog::debug($st_a);
                        if ($pt_e) {
                            $archivos_antiguos = $detalle_operacion->pt_e_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($pt_e as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "pt_e" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->pt_e_file = implode("|", $agregados);
                                $detalle_operacion->pt_e_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->pt_f_file;
                        if ($pt_f) {
                            $archivos_antiguos = $detalle_operacion->pt_f_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($pt_f as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "pt_f" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->pt_f_file = implode("|", $agregados);
                                $detalle_operacion->pt_f_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->pt_m_file;
                        if ($pt_m) {
                            $archivos_antiguos = $detalle_operacion->pt_m_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($pt_m as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "pt_m" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->pt_m_file = implode("|", $agregados);
                                $detalle_operacion->pt_m_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->st_a_file;
                        if ($st_a) {
                            $archivos_antiguos = $detalle_operacion->st_a_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($st_a as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "st_a" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->st_a_file = implode("|", $agregados);
                                $detalle_operacion->st_a_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->st_m_file;
                        if ($st_m) {
                            $archivos_antiguos = $detalle_operacion->st_m_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($st_m as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "st_m" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->st_m_file = implode("|", $agregados);
                                $detalle_operacion->st_m_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->st_j_file;
                        if ($st_j) {
                            $archivos_antiguos = $detalle_operacion->st_j_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($st_j as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "st_j" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->st_j_file = implode("|", $agregados);
                                $detalle_operacion->st_j_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->tt_j_file;
                        if ($tt_j) {
                            $archivos_antiguos = $detalle_operacion->tt_j_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($tt_j as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "tt_j" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->tt_j_file = implode("|", $agregados);
                                $detalle_operacion->tt_j_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->tt_a_file;
                        if ($tt_a) {
                            $archivos_antiguos = $detalle_operacion->tt_a_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($tt_a as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "tt_a" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->tt_a_file = implode("|", $agregados);
                                $detalle_operacion->tt_a_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->tt_s_file;
                        if ($tt_s) {
                            $archivos_antiguos = $detalle_operacion->tt_s_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($tt_s as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "tt_s" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->tt_s_file = implode("|", $agregados);
                                $detalle_operacion->tt_s_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->ct_o_file;
                        if ($ct_o) {
                            $archivos_antiguos = $detalle_operacion->ct_o_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($ct_o as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "ct_o" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->ct_o_file = implode("|", $agregados);
                                $detalle_operacion->ct_o_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->ct_n_file;
                        if ($ct_n) {
                            $archivos_antiguos = $detalle_operacion->ct_n_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($ct_n as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "ct_n" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->ct_n_file = implode("|", $agregados);
                                $detalle_operacion->ct_n_est = 1;
                            }
                        }

                        $antiguo = $detalle_operacion->ct_d_file;
                        if ($ct_d) {
                            $archivos_antiguos = $detalle_operacion->ct_d_array;
                            if ($archivos_antiguos) {
                                foreach ($archivos_antiguos as $value) {
                                    \File::delete(public_path("/files/" . $value));
                                }
                            }
                            $agregados = [];
                            foreach ($ct_d as $index_file => $file) {
                                $archivo = $file;
                                $ext = "." . $archivo->getClientOriginalExtension();
                                $nom_archivo = $index_file . "ct_d" . time() . $id_ope . $id_det . $key_det . $ext;
                                $archivo->move(public_path("/files/"), $nom_archivo);
                                $agregados[] = $nom_archivo;
                            }
                            if (count($agregados) > 0) {
                                $detalle_operacion->ct_d_file = implode("|", $agregados);
                                $detalle_operacion->ct_d_est = 1;
                            }
                        }

                        $detalle_operacion->save();
                    }
                }
            } else {
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
                // Log::registrarLog("MODIFICACIÓN", "DETALLE FORMULARIO CUATRO", "EL USUARIO $user->id MODIFICÓ UN DETALLE FORMULARIO CUATRO", $user);
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

    public function show(DetalleFormulario $detalle_formulario)
    {

        $total_programados = 0;
        $total_ejecutados = 0;
        $meses = [
            "pt_e", "pt_f", "pt_m",
            "st_a", "st_m", "st_j",
            "tt_j", "tt_a", "tt_s",
            "ct_o", "ct_n", "ct_d",
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
                $array_programados_p = 0;
            }
            if ($total_programados > 0) {
                $array_ejecutados_p[$mes] = ($array_ejecutados[$mes] * 100) / $total_programados;
                $array_ejecutados_p[$mes] = round($array_ejecutados_p[$mes], 2);
                if ($key_mes <= $num_mes_actual) {
                    $sum2 += (float)$array_ejecutados_p[$mes];
                }
            } else {
                $array_ejecutados_p = 0;
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
                "name" => "Ejecutados",
                "data" => [],
            ],    [
                "name" => "Programados",
                "data" => [],
            ]
        ];
        foreach ($array_meses as $key => $value) {
            $categories[] = $value;
            $data[0]["data"][] = (float)$array_ejecutados[$key];
            $data[1]["data"][] = (float)$array_programados[$key];
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
