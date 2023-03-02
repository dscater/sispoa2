<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\Log;
use App\Models\MemoriaOperacion;
use App\Models\MemoriaOperacionDetalle;
use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class CertificacionController extends Controller
{
    public $validacion = [
        'formulario_id' => 'required',
        "mo_id" => "required",
        "mod_id" => "required",
        "cantidad_usar" => "required",
        "archivo" => "required",
        "correlativo" => "required",
        "solicitante_id" => "required",
        "superior_id" => "required",
        "inicio" => "required",
        "final" => "required",
        "personal_designado" => "required",
    ];

    public function index()
    {
        $certificacions = [];
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "ENLACE") {
            $certificacions = Certificacion::with("memoria_operacion_detalle")->select("certificacions.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "certificacions.formulario_id")
                ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                ->get();
        } else {
            $certificacions = Certificacion::with("memoria_operacion_detalle")->get();
        }
        return response()->JSON(["certificacions" => $certificacions, "total" => count($certificacions)]);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('archivo')) {
            $this->validacion['archivo'] = 'file';
        }
        $request->validate($this->validacion);

        DB::beginTransaction();
        try {
            $request["fecha_registro"] = date("Y-m-d");
            $request["estado"] = "PENDIENTE";
            $request["anulado"] = 0;
            $memoria_operacion_detalle = MemoriaOperacionDetalle::find($request->mod_id);
            $presupuesto_usarse = (float)$request->cantidad_usar * (float)$memoria_operacion_detalle->costo;
            $request["presupuesto_usarse"] = $presupuesto_usarse;
            $certificacion = Certificacion::create(array_map("mb_strtoupper", $request->except("archivo")));
            if ($request->hasFile('archivo')) {
                $file = $request->archivo;
                $nom_archivo = time() . '_' . $certificacion->id . '.' . $file->getClientOriginalExtension();
                $certificacion->archivo = $nom_archivo;
                $file->move(public_path() . '/archivos/', $nom_archivo);
                $certificacion->save();
            }

            $user = Auth::user();
            Log::registrarLog("CREACIÓN", "CERTIFICACIÓN POA", "EL USUARIO $user->id REGISTRO UNA CERTIFICACIÓN POA", $user);

            DB::commit();
            return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Certificacion $certificacion)
    {
        return response()->JSON($certificacion);
    }

    public function pdf(Certificacion $certificacion)
    {
        $certificacion = $certificacion->load("memoria_operacion_detalle");
        $pdf = PDF::loadView('reportes.certificacion', compact('certificacion'))->setPaper('letter', 'portrait');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('Certificacion.pdf');
    }

    public function update(Certificacion $certificacion, Request $request)
    {
        if ($request->hasFile('archivo')) {
            $this->validacion['archivo'] = 'file';
        }
        $request->validate($this->validacion);
        DB::beginTransaction();
        try {
            $memoria_operacion_detalle = MemoriaOperacionDetalle::find($request->mod_id);
            $presupuesto_usarse = (float)$request->cantidad_usar * (float)$memoria_operacion_detalle->costo;
            $request["presupuesto_usarse"] = $presupuesto_usarse;
            $certificacion->update(array_map("mb_strtoupper", $request->except("archivo")));

            if ($request->hasFile('archivo')) {
                $antiguo = $certificacion->archivo;
                if ($antiguo) {
                    \File::delete(public_path() . '/archivos/' . $antiguo);
                }
                $file = $request->archivo;
                $nom_archivo = time() . '_' . $certificacion->id . '.' . $file->getClientOriginalExtension();
                $certificacion->archivo = $nom_archivo;
                $file->move(public_path() . '/archivos/', $nom_archivo);
                $certificacion->save();
            }

            $user = Auth::user();
            Log::registrarLog("MODIFICACIÓN", "CERTIFICACIÓN POA", "EL USUARIO $user->id MODIFICÓ UNA CERTIFICACIÓN POA", $user);

            DB::commit();
            return response()->JSON(["sw" => true, "certificacion" => $certificacion, "msj" => "El registro se actualizó correctamente"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Certificacion $certificacion)
    {

        DB::beginTransaction();
        try {
            $antiguo = $certificacion->archivo;
            \File::delete(public_path() . '/archivos/' . $antiguo);
            $certificacion->delete();

            $user = Auth::user();
            Log::registrarLog("ELIMINACIÓN", "CERTIFICACIÓN POA", "EL USUARIO $user->id ELIMINÓ UNA CERTIFICACIÓN POA", $user);

            DB::commit();
            return response()->JSON(["sw" => true, "certificacion" => $certificacion, "msj" => "El registro se actualizó correctamente"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function anular(Certificacion $certificacion)
    {

        DB::beginTransaction();
        try {
            $certificacion->anulado = 1;
            $certificacion->save();

            $user = Auth::user();
            Log::registrarLog("ANULACIÓN", "CERTIFICACIÓN POA", "EL USUARIO $user->id ANULÓ UNA CERTIFICACIÓN POA", $user);

            DB::commit();
            return response()->JSON(["sw" => true, "certificacion" => $certificacion, "msj" => "El registro se anuló correctamente"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function activar(Certificacion $certificacion)
    {

        DB::beginTransaction();
        try {
            $certificacion->anulado = 0;
            $certificacion->save();

            $user = Auth::user();
            Log::registrarLog("ACTIVACIÓN", "CERTIFICACIÓN POA", "EL USUARIO $user->id REACTIVÓ UNA CERTIFICACIÓN POA", $user);

            DB::commit();
            return response()->JSON(["sw" => true, "certificacion" => $certificacion, "msj" => "El registro se reactivó correctamente"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function aprobar(Certificacion $certificacion)
    {
        $certificacion->estado = "APROBADO";
        $certificacion->save();

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "CERTIFICACIÓN POA", "EL USUARIO $user->id MODIFICÓ EL ESTADO DE UNA CERTIFICACIÓN POA A " . $certificacion->estado, $user);

        return response()->JSON(["sw" => true, "certificacion" => $certificacion, "msj" => "El registro se aprobó correctamente"]);
    }

    public function getNroCorrelativo()
    {
        $ultimo = Certificacion::get()->last();
        if ($ultimo) {
            return response()->JSON((int)$ultimo->correlativo + 1);
        }
        return response()->JSON(1);
    }
}
