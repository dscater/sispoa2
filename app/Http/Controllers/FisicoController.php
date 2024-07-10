<?php

namespace App\Http\Controllers;

use App\Models\DetalleFormulario;
use App\Models\Fisico;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class FisicoController extends Controller
{
    public $validacion = [
        'descripcion' => 'required|min:4',
    ];

    public function index()
    {
        $fisicos = Fisico::all();
        return response()->JSON(["fisicos" => $fisicos, "total" => count($fisicos)]);
    }

    public function store(Request $request)
    {
        $this->validacion["archivo"] = 'required|image|mimes:jpeg,jpg,png|max:4096';
        $request->validate($this->validacion);
        $request["fecha_registro"] = date("Y-m-d");
        $fisico = Fisico::create(array_map("mb_strtoupper", $request->except("archivo")));
        if ($request->hasFile('archivo')) {
            $file = $request->archivo;
            $nom_archivo = time() . '_fisico' . $fisico->id . '.' . $file->getClientOriginalExtension();
            $fisico->archivo = $nom_archivo;
            $file->move(public_path() . '/files/', $nom_archivo);
            $fisico->save();
        }

        $user = Auth::user();
        Log::registrarLog("CREACIÓN", "FÍSICO", "EL USUARIO $user->id REGISTRO UN FÍSICO", $user);

        return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
    }

    public function show(Fisico $fisico)
    {
        return response()->JSON($fisico);
    }

    public function update(Fisico $fisico, Request $request)
    {
        if ($request->hasFile('archivo')) {
            $this->validacion["archivo"] = 'required|image|mimes:jpeg,jpg,png|max:4096';
        }
        $request->validate($this->validacion);
        $fisico->update(array_map("mb_strtoupper", $request->except("archivo")));
        if ($request->hasFile('archivo')) {
            $antiguo = $fisico->archivo;
            \File::delete(public_path() . "/files/" . $antiguo);

            $file = $request->archivo;
            $nom_archivo = time() . '_fisico' . $fisico->id . '.' . $file->getClientOriginalExtension();
            $fisico->archivo = $nom_archivo;
            $file->move(public_path() . '/files/', $nom_archivo);
            $fisico->save();
        }

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "FÍSICO", "EL USUARIO $user->id MODIFICÓ UN FÍSICO", $user);

        return response()->JSON(["sw" => true, "fisico" => $fisico, "msj" => "El registro se actualizó correctamente"]);
    }

    public function destroy(Fisico $fisico)
    {
        $antiguo = $fisico->archivo;
        \File::delete(public_path() . "/files/" . $antiguo);
        $fisico->delete();

        $user = Auth::user();
        Log::registrarLog("ELIMINACIÓN", "FÍSICO", "EL USUARIO $user->id ELIMINÓ UN FÍSICO", $user);

        return response()->JSON(["sw" => true, "fisico" => $fisico, "msj" => "El registro se actualizó correctamente"]);
    }

    public function exportar(DetalleFormulario $detalle_formulario)
    {
        $formulario = $detalle_formulario->formulario;

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


        $pdf = PDF::loadView('reportes.formulario_cuatro_fisico', compact(
            'formulario',
            'detalle_formulario',
            "total_programados",
            "total_ejecutados",
            "meses",
            "array_programados",
            "array_programados_p",
            "array_ejecutados",
            "array_ejecutados_p",
            "p_ejecutados",
            "a_la_fecha"
        ))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('formulario_cuatro_solo.pdf');
    }
}
