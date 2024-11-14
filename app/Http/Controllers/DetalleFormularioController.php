<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\DetalleFormulario;
use App\Models\DetalleOperacion;
use App\Models\Log;
use App\Models\MemoriaCalculo;
use App\Models\Operacion;
use App\Models\Unidad;
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
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "ENLACE" || Auth::user()->tipo == "FINANCIERA") {
            $detalle_formularios = DetalleFormulario::select("detalle_formularios.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "detalle_formularios.formulario_id")
                ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                ->where("formulario_cuatro.status", 1)
                ->get();
        } else {
            $detalle_formularios = DetalleFormulario::where("status", 1)->get();
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

    public function getTablaSubunidad(DetalleFormulario $detalle_formulario)
    {
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

    public function getTablaHtml(DetalleFormulario $detalle_formulario)
    {
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
                $html .= '
                        <tr>
                            <td>' . $detalle_operacion->codigo_tarea . '</td>
                            <td>' . $detalle_operacion->actividad_tarea . '</td>
                            <td>' . $detalle_operacion->pt_e . '</td>
                            <td>' . $detalle_operacion->pt_f . '</td>
                            <td>' . $detalle_operacion->pt_m . '</td>
                            <td>' . $detalle_operacion->st_a . '</td>
                            <td>' . $detalle_operacion->st_m . '</td>
                            <td>' . $detalle_operacion->st_j . '</td>
                            <td>' . $detalle_operacion->tt_j . '</td>
                            <td>' . $detalle_operacion->tt_a . '</td>
                            <td>' . $detalle_operacion->tt_s . '</td>
                            <td>' . $detalle_operacion->ct_o . '</td>
                            <td>' . $detalle_operacion->ct_n . '</td>
                            <td>' . $detalle_operacion->ct_d . '</td>
                            <td>' . date("d/m/Y", strtotime($detalle_operacion->inicio)) . '</td>
                            <td>' . date("d/m/Y", strtotime($detalle_operacion->final)) . '</td>
                        </tr>
                    ';
            }
        }



        $html .= '</tbody>';
        return response()->JSON([
            'sw' => true,
            'detalle_formulario' => $detalle_formulario->load("operacions.subdireccion"),
            "html" => $html,
        ], 200);
    }

    public function getTablaHtmlMeses(DetalleFormulario $detalle_formulario)
    {
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
                $html .= '
                        <tr>
                            <td>' . $detalle_operacion->codigo_tarea . '</td>
                            <td>' . $detalle_operacion->actividad_tarea . '</td>
                            <td>' . $detalle_operacion->pt_e . '</td>
                            <td>' . $detalle_operacion->pt_f . '</td>
                            <td>' . $detalle_operacion->pt_m . '</td>
                            <td>' . $detalle_operacion->st_a . '</td>
                            <td>' . $detalle_operacion->st_m . '</td>
                            <td>' . $detalle_operacion->st_j . '</td>
                            <td>' . $detalle_operacion->tt_j . '</td>
                            <td>' . $detalle_operacion->tt_a . '</td>
                            <td>' . $detalle_operacion->tt_s . '</td>
                            <td>' . $detalle_operacion->ct_o . '</td>
                            <td>' . $detalle_operacion->ct_n . '</td>
                            <td>' . $detalle_operacion->ct_d . '</td>
                            <td>' . date("d/m/Y", strtotime($detalle_operacion->inicio)) . '</td>
                            <td>' . date("d/m/Y", strtotime($detalle_operacion->final)) . '</td>
                        </tr>
                    ';
            }
        }



        $html .= '</tbody>';
        return response()->JSON([
            'sw' => true,
            'detalle_formulario' => $detalle_formulario->load("operacions.subdireccion"),
            "html" => $html,
        ], 200);
    }

    public function getGraficoFechas(DetalleFormulario $detalle_formulario, Request $request)
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

    public function getEjecucionFisicoGrafico(Request $request)
    {
        $detalle_formulario = DetalleFormulario::where("formulario_seleccionado", $request->formulario_id)->get()->first();
        $mes1 = $request->mes1;
        $mes2 = $request->mes2;

        $mes_ini = (int)$mes1;
        $mes_fin = (int)$mes2;
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

        if ($detalle_formulario->operacions) {
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

    public function getEjecucionFisico(Request $request)
    {
        $detalle_formulario = DetalleFormulario::where("formulario_seleccionado", $request->formulario_id)->where("status", 1)->get()->first();
        $mes_ini = (int)$request->mes1;
        $mes_fin = (int)$request->mes2;
        $mes_ini--;
        $mes_fin--;

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

        $meses_txt = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

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
                        <th colspan="3">' . $meses_txt[$mes_ini] . ' a ' . $meses_txt[$mes_fin] . '</th>
                    </tr>
                    <tr class="bg-primary">
                        <th>Programado</th>
                        <th>Ejecutado</th>
                        <th>Ejecutado %</th>
                    </tr>
                </thead>
        ';
        $html .= '<tbody>';

        $programados = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $ejecutados = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($detalle_formulario->operacions as $operacion) {
            if ($operacion->subdirecion) {
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

                $total_pro = 0;
                $total_eje = 0;
                for ($i = $mes_ini; $i <= $mes_fin; $i++) {
                    $total_pro += (float)$detalle_operacion[$meses[$i]];
                    $total_eje += (float)$detalle_operacion[$meses[$i] . '_eje'];
                }

                $porcentaje = 0;
                if ($total_pro > 0) {
                    $porcentaje = ($total_eje * 100) / $total_pro;
                }
                $html .= '
                    <tr>
                        <td class="text-center">' . $detalle_operacion->codigo_tarea . '</td>
                        <td class="text-center">' . $detalle_operacion->actividad_tarea . '</td>
                        <td class="text-center">' . $total_pro . '</td>
                        <td class="text-center">' . $total_eje . '</td>
                        <td class="text-center">' . $porcentaje . '%</td>
                    </tr>
                ';
            }
        }

        $suma_programados = $programados[0] +  $programados[1] +  $programados[2] + $programados[3] + $programados[4] + $programados[5] + $programados[6] + $programados[7] + $programados[8] + $programados[9] + $programados[10] + $programados[11];

        $html .= '</tbody>';
        return response()->JSON([
            'sw' => true,
            'detalle_formulario' => $detalle_formulario->load("operacions.subdireccion"),
            "html" => $html,

        ], 200);
    }

    public function getEjecucionFisicoUnidades(Request $request)
    {
        $mes_ini = (int)$request->mes1;
        $mes_fin = (int)$request->mes2;
        $mes_ini--;
        $mes_fin--;
        $unidades = Unidad::all();

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

        $meses_txt = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

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



        $html = '
            <thead>
                <tr>
                    <th rowspan="2">Unidad Organizacional</th>
                    <th colspan="2" class="text-center">' . $meses_txt[$mes_ini] . ' a ' . $meses_txt[$mes_fin] . '</th>
                </tr>
                <tr>
                    <th class="text-center">Programado</th>
                    <th class="text-center">Ejecutado</th>
                </tr>
            </thead>
        ';




        $array_meses2 = [
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

        $detalle_formularios = DetalleFormulario::select("detalle_formularios.*")
            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "detalle_formularios.formulario_id")
            ->where("formulario_cuatro.status", 1)
            ->get();
        $total_suma = 0;
        foreach ($detalle_formularios as $detalle_formulario) {
            foreach ($detalle_formulario->operacions as $operacion) {
                foreach ($operacion->detalle_operaciones as $detalle_operacion) {
                    foreach ($array_meses2 as $index_meses => $value) {
                        $total_suma += (float)$detalle_operacion[$index_meses];
                    }
                }
            }
        }

        foreach ($unidades as $item) {
            $categories[] = $item->nombre;


            // programados en rango meses
            $detalle_formularios = DetalleFormulario::select("detalle_formularios.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "detalle_formularios.formulario_id")
                ->where("formulario_cuatro.unidad_id", $item->id)
                ->where("formulario_cuatro.status", 1)
                ->get();

            $total_suma_rango = 0;
            $total_suma_rango_eje = 0;
            foreach ($detalle_formularios as $detalle_formulario) {
                foreach ($detalle_formulario->operacions as $operacion) {
                    foreach ($operacion->detalle_operaciones as $detalle_operacion) {
                        foreach ($array_meses as $index_meses => $value) {
                            $total_suma_rango += (float)$detalle_operacion[$index_meses];
                            $total_suma_rango_eje += (float)$detalle_operacion[$index_meses . "_eje"];
                        }
                    }
                }
            }

            $porcentaje = 0;
            $porcentaje2 = 0;

            if ($total_suma > 0) {
                $porcentaje = ($total_suma_rango * 100) / $total_suma;
                $porcentaje = round($porcentaje, 2);
            }

            if ($total_suma_rango > 0) {
                $porcentaje2 = ($total_suma_rango_eje * 100) / $total_suma_rango;
                $porcentaje2 = round($porcentaje2, 2);
            }
            $html .= '<tr>';
            $html .= '<td>' . $item->nombre . '</td>';
            $html .= '<td class="text-center">' . $porcentaje . '%</td>';
            $html .= '<td class="text-center">' . $porcentaje2 . '%</td>';
            $html .= '</tr>';

            $data[0]["data"][] = (float)$porcentaje;
            $data[1]["data"][] = (float)$porcentaje2;
        }

        $html .= '</tbody>';
        return response()->JSON([
            'sw' => true,
            "html" => $html,
            "categories" => $categories,
            "series" => $data,

        ], 200);
    }

    public function destroy(DetalleFormulario $detalle_formulario)
    {
        // validar si existe un registro de memoria de calculo
        $existe = MemoriaCalculo::where("formulario_id", $detalle_formulario->formulario_id)->where("status", 1)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "formulario_cuatro" => $detalle_formulario->formulario, "msj" => "No es posible eliminar este registro, debido a que su información esta siendo utilizada en Memoria de cálculo"]);
        }

        $existe = Certificacion::where("formulario_id", $detalle_formulario->formulario->id)->where("status", 1)->get();
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
