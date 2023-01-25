<?php

namespace App\Http\Controllers;

use App\Models\DetalleOperacion;
use App\Models\Operacion;
use Illuminate\Http\Request;

class OperacionController extends Controller
{

    public $validacion = [];

    public $mensajes = [
        "ponderacion.*.required" => "Este campo es obligatorio",
        "resultado_esperado.*.required" => "Este campo es obligatorio",
        "medios_verificacion.*.required" => "Este campo es obligatorio",
        "codigo_tarea.*.required" => "Este campo es obligatorio",
        "actividad_tarea.*.required" => "Este campo es obligatorio",
        "pt_e.*.required" => "Este campo es obligatorio",
        "pt_f.*.required" => "Este campo es obligatorio",
        "pt_m.*.required" => "Este campo es obligatorio",
        "st_a.*.required" => "Este campo es obligatorio",
        "st_m.*.required" => "Este campo es obligatorio",
        "st_j.*.required" => "Este campo es obligatorio",
        "tt_j.*.required" => "Este campo es obligatorio",
        "tt_a.*.required" => "Este campo es obligatorio",
        "tt_s.*.required" => "Este campo es obligatorio",
        "ct_o.*.required" => "Este campo es obligatorio",
        "ct_n.*.required" => "Este campo es obligatorio",
        "ct_d.*.required" => "Este campo es obligatorio",
        "inicio.*.required" => "Este campo es obligatorio",
        "inicio.*.date" => "Debes ingresar una fecha valida",
        "final.*.required" => "Este campo es obligatorio",
        "final.*.date" => "Debes ingresar una fecha valida",
    ];


    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        $request['fecha_registro'] = date('Y-m-d');
        $nueva_operacion = Operacion::create(array_map('mb_strtoupper', $request->except("do_id", "operacion_id", "ponderacion", "resultado_esperado", "medios_verificacion", "codigo_tarea", "actividad_tarea", "pt_e", "pt_f", "pt_m", "st_a", "st_m", "st_j", "tt_j", "tt_a", "tt_s", "ct_o", "ct_n", "ct_d", "inicio", "final")));
        // REGISTRAR LOS DETALLES DE OPERACION
        $do_id = $request->do_id;
        $ponderacion = $request->ponderacion;
        $resultado_esperado = $request->resultado_esperado;
        $medios_verificacion = $request->medios_verificacion;
        $codigo_tarea = $request->codigo_tarea;
        $actividad_tarea = $request->actividad_tarea;
        $pt_e = $request->pt_e;
        $pt_f = $request->pt_f;
        $pt_m = $request->pt_m;
        $st_a = $request->st_a;
        $st_m = $request->st_m;
        $st_j = $request->st_j;
        $tt_j = $request->tt_j;
        $tt_a = $request->tt_a;
        $tt_s = $request->tt_s;
        $ct_o = $request->ct_o;
        $ct_n = $request->ct_n;
        $ct_d = $request->ct_d;
        $inicio = $request->inicio;
        $final = $request->final;
        for ($i = 0; $i < count($ponderacion); $i++) {
            if ($do_id[$i] != 0) {
                $detalle_operacion = DetalleOperacion::find($do_id[$i]);
                $detalle_operacion->update([
                    "operacion_id" => $nueva_operacion->id,
                    "ponderacion" => $ponderacion[$i],
                    "resultado_esperado" => mb_strtoupper($resultado_esperado[$i]),
                    "medios_verificacion" => mb_strtoupper($medios_verificacion[$i]),
                    "codigo_tarea" => $codigo_tarea[$i],
                    "actividad_tarea" => mb_strtoupper($actividad_tarea[$i]),
                    "pt_e" => $pt_e[$i],
                    "pt_f" => $pt_f[$i],
                    "pt_m" => $pt_m[$i],
                    "st_a" => $st_a[$i],
                    "st_m" => $st_m[$i],
                    "st_j" => $st_j[$i],
                    "tt_j" => $tt_j[$i],
                    "tt_a" => $tt_a[$i],
                    "tt_s" => $tt_s[$i],
                    "ct_o" => $ct_o[$i],
                    "ct_n" => $ct_n[$i],
                    "ct_d" => $ct_d[$i],
                    "inicio" => $inicio[$i],
                    "final" => $final[$i],
                ]);
            } else {
                DetalleOperacion::create([
                    "operacion_id" => $nueva_operacion->id,
                    "ponderacion" => $ponderacion[$i],
                    "resultado_esperado" => mb_strtoupper($resultado_esperado[$i]),
                    "medios_verificacion" => mb_strtoupper($medios_verificacion[$i]),
                    "codigo_tarea" => $codigo_tarea[$i],
                    "actividad_tarea" => mb_strtoupper($actividad_tarea[$i]),
                    "pt_e" => $pt_e[$i],
                    "pt_f" => $pt_f[$i],
                    "pt_m" => $pt_m[$i],
                    "st_a" => $st_a[$i],
                    "st_m" => $st_m[$i],
                    "st_j" => $st_j[$i],
                    "tt_j" => $tt_j[$i],
                    "tt_a" => $tt_a[$i],
                    "tt_s" => $tt_s[$i],
                    "ct_o" => $ct_o[$i],
                    "ct_n" => $ct_n[$i],
                    "ct_d" => $ct_d[$i],
                    "inicio" => $inicio[$i],
                    "final" => $final[$i],
                ]);
            }
        }
        return response()->JSON([
            'sw' => true,
            'operacion' => $nueva_operacion,
            'msj' => 'El registro se realizó de forma correcta',
        ], 200);
    }

    public function update(Operacion $operacion, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $operacion->update(array_map('mb_strtoupper', $request->except("detalle_formulario_id", "do_id", "operacion_id", "ponderacion", "resultado_esperado", "medios_verificacion", "codigo_tarea", "actividad_tarea", "pt_e", "pt_f", "pt_m", "st_a", "st_m", "st_j", "tt_j", "tt_a", "tt_s", "ct_o", "ct_n", "ct_d", "inicio", "final")));

        // REGISTRAR LOS DETALLES DE OPERACION
        $do_id = $request->do_id;
        $ponderacion = $request->ponderacion;
        $resultado_esperado = $request->resultado_esperado;
        $medios_verificacion = $request->medios_verificacion;
        $codigo_tarea = $request->codigo_tarea;
        $actividad_tarea = $request->actividad_tarea;
        $pt_e = $request->pt_e;
        $pt_f = $request->pt_f;
        $pt_m = $request->pt_m;
        $st_a = $request->st_a;
        $st_m = $request->st_m;
        $st_j = $request->st_j;
        $tt_j = $request->tt_j;
        $tt_a = $request->tt_a;
        $tt_s = $request->tt_s;
        $ct_o = $request->ct_o;
        $ct_n = $request->ct_n;
        $ct_d = $request->ct_d;
        $inicio = $request->inicio;
        $final = $request->final;
        for ($i = 0; $i < count($ponderacion); $i++) {
            if ($do_id[$i] != 0) {
                $detalle_operacion = DetalleOperacion::find($do_id[$i]);
                $detalle_operacion->update([
                    "operacion_id" => $operacion->id,
                    "ponderacion" => $ponderacion[$i],
                    "resultado_esperado" => mb_strtoupper($resultado_esperado[$i]),
                    "medios_verificacion" => mb_strtoupper($medios_verificacion[$i]),
                    "codigo_tarea" => $codigo_tarea[$i],
                    "actividad_tarea" => mb_strtoupper($actividad_tarea[$i]),
                    "pt_e" => $pt_e[$i],
                    "pt_f" => $pt_f[$i],
                    "pt_m" => $pt_m[$i],
                    "st_a" => $st_a[$i],
                    "st_m" => $st_m[$i],
                    "st_j" => $st_j[$i],
                    "tt_j" => $tt_j[$i],
                    "tt_a" => $tt_a[$i],
                    "tt_s" => $tt_s[$i],
                    "ct_o" => $ct_o[$i],
                    "ct_n" => $ct_n[$i],
                    "ct_d" => $ct_d[$i],
                    "inicio" => $inicio[$i],
                    "final" => $final[$i],
                ]);
            } else {
                DetalleOperacion::create([
                    "operacion_id" => $operacion->id,
                    "ponderacion" => $ponderacion[$i],
                    "resultado_esperado" => mb_strtoupper($resultado_esperado[$i]),
                    "medios_verificacion" => mb_strtoupper($medios_verificacion[$i]),
                    "codigo_tarea" => $codigo_tarea[$i],
                    "actividad_tarea" => mb_strtoupper($actividad_tarea[$i]),
                    "pt_e" => $pt_e[$i],
                    "pt_f" => $pt_f[$i],
                    "pt_m" => $pt_m[$i],
                    "st_a" => $st_a[$i],
                    "st_m" => $st_m[$i],
                    "st_j" => $st_j[$i],
                    "tt_j" => $tt_j[$i],
                    "tt_a" => $tt_a[$i],
                    "tt_s" => $tt_s[$i],
                    "ct_o" => $ct_o[$i],
                    "ct_n" => $ct_n[$i],
                    "ct_d" => $ct_d[$i],
                    "inicio" => $inicio[$i],
                    "final" => $final[$i],
                ]);
            }
        }
        return response()->JSON([
            'sw' => true,
            'operacion' => $operacion,
            'msj' => 'El registro se realizó de forma correcta',
        ], 200);
    }

    public function destroy(Operacion $operacion)
    {
        $operacion->delete();
        return response()->JSON(true);
    }

    public function getTareas(Request $request)
    {
        $operacion = Operacion::find($request->id);
        return response()->JSON($operacion->detalle_operaciones);
    }
}
