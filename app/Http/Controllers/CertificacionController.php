<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\CertificacionDetalle;
use App\Models\Log;
use App\Models\MemoriaOperacion;
use App\Models\MemoriaOperacionDetalle;
use App\Models\Partida;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;
use PDF;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

class CertificacionController extends Controller
{
    public $validacion = [
        'formulario_id' => 'required',
        'poa_seleccionado' => 'required',
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
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "ENLACE" || Auth::user()->tipo == "FINANCIERA") {
            $certificacions = Certificacion::with("certificacion_detalles.memoria_operacion", "certificacion_detalles.memoria_operacion_detalle")->with("o_personal_designado")
                ->select("certificacions.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "certificacions.formulario_id")
                ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                ->where("certificacions.status", 1)
                ->orderBy("created_at", "desc")
                ->get()->distinct("certificacions.correlativo");
        } else {
            $certificacions = Certificacion::with("certificacion_detalles.memoria_operacion", "certificacion_detalles.memoria_operacion_detalle")->with("o_personal_designado")
                ->where("certificacions.status", 1)
                ->orderBy("created_at", "desc")->get()->distinct("certificacions.correlativo");
        }
        return response()->JSON(["certificacions" => $certificacions, "total" => count($certificacions)]);
    }

    public function paginado(Request $request)
    {
        $certificacions = [];
        $buscar = $request->buscar;
        $cod_ope = $request->cod_ope;
        $partida = $request->partida;
        $correlativo = $request->correlativo;
        $monto = $request->monto;
        // $sortBy = $request->sortBy;
        // $sortDesc = $request->sortDesc;

        $certificacions = Certificacion::with(["certificacion_detalles.memoria_operacion", "certificacion_detalles.memoria_operacion_detalle", "o_personal_designado"])->select("certificacions.*")
            ->select("certificacions.*")
            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "certificacions.formulario_id")
            ->join("unidads", "unidads.id", "=", "formulario_cuatro.unidad_id")
            ->join("certificacion_detalles", "certificacion_detalles.certificacion_id", "=", "certificacions.id")
            ->join("memoria_operacions", "memoria_operacions.id", "=", "certificacion_detalles.mo_id")
            ->join("operacions", "operacions.id", "=", "memoria_operacions.operacion_id")
            ->join("memoria_operacion_detalles", "memoria_operacion_detalles.id", "=", "certificacion_detalles.mod_id")
            ->join("personals", "personals.id", "=", "certificacions.personal_designado");
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "ENLACE") {
            $certificacions->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id);
        }
        if (Auth::user()->tipo  == 'FINANCIERA') {
            $certificacions->where("certificacions.estado", 'APROBADO');
        }

        if ($buscar && trim($buscar)) {
            $certificacions->where(function ($query) use ($buscar) {
                $query->where("certificacions.correlativo", "LIKE", "%$buscar%")
                    ->orWhere("unidads.nombre", "LIKE", "%$buscar%")
                    ->orWhere(DB::raw("CONCAT(personals.nombre,personals.paterno,personals.materno)"), "LIKE", "%$buscar%")
                    ->orWhere("certificacions.departamento", "LIKE", "%$buscar%")
                    ->orWhere("certificacions.municipio", "LIKE", "%$buscar%")
                    ->orWhere("certificacion_detalles.presupuesto_usarse", "LIKE", "%$buscar%")
                    ->orWhere("certificacions.inicio", "LIKE", "%$buscar%")
                    ->orWhere("certificacions.final", "LIKE", "%$buscar%")
                    ->orWhere("certificacions.fecha_registro", "LIKE", "%$buscar%")
                    ->orWhere("certificacions.estado", "LIKE", "%$buscar%");
            });
        }

        if ($cod_ope) {
            $certificacions->where("operacions.codigo_operacion", trim($cod_ope));
        }
        if ($partida) {
            $certificacions->where("memoria_operacion_detalles.partida", trim($partida));
        }

        if ($correlativo) {
            $certificacions->where("certificacions.correlativo", trim($correlativo));
        }

        if ($monto) {
            $certificacions->where("certificacion_detalles.presupuesto_usarse", trim($monto));
        }

        // if (isset($request->value) && $request->value != "") {
        //     $value = $request->value;
        //     $certificacions->orWhere("certificacions.id", "LIKE", "%$value%")
        //         ->orWhere("certificacions.codigo", "LIKE", "%$value%")
        //         ->orWhere("certificacions.accion", "LIKE", "%$value%")
        //         ->orWhere(DB::raw("CONCAT(personals.nombre,personals.paterno,personals.materno)", "LIKE", "%$value%"))
        //         ->orWhere("certificacions.departamento", "LIKE", "%$value%")
        //         ->orWhere("certificacions.municipio", "LIKE", "%$value%")
        //         ->orWhere("certificacions.correlativo", "LIKE", "%$value%");
        // }

        if ($request->sortBy) {
            $desc =  $request->sortDesc === 'true' ? 'DESC' : 'ASC';
            $col = $request->sortBy;
            $certificacions->orderBy("certificacions." . $request->sortBy, $desc);
        } else {
            $certificacions->orderBy("certificacions.correlativo", "DESC");
        }

        $certificacions = $certificacions->where("certificacions.status", 1)
            ->distinct("certificacions.id")->paginate($request->per_page);

        return response()->JSON(['certificacions' => $certificacions, 'total' => count($certificacions)], 200);
    }

    public function store(Request $request)
    {
        // if ($request->hasFile('archivo')) {
        //     $this->validacion['archivo'] = 'file';
        // }
        $request->validate($this->validacion);
        if (!isset($request->cantidad_usar) || !isset($request->presupuesto_usarse) || count($request->cantidad_usar) <= 0 || count($request->presupuesto_usarse) <= 0) {
            throw new Exception("Debes seleccionar/ingresar al menos una Operación|Tarea/actividad|Partida");
        }
        $errors = self::validaDetallesOperacion($request->cantidad_usar, $request->presupuesto_usarse);
        if (count($errors) > 0) {
            return response()->JSON([
                "errors" => $errors,
            ], 422);
        }

        $error_presupuestos = CertificacionController::validarValoresPresupuestoUsar($request->presupuesto_usarse, $request->mod_id);
        if ($error_presupuestos[0]) {
            throw new Exception($error_presupuestos[1]);
        }
        DB::beginTransaction();
        try {

            $ultimo = Certificacion::get()->last();
            if ($ultimo) {
                $request["correlativo"] = (int)$ultimo->correlativo + 1;
            } else {
                $request["correlativo"] = 1;
            }

            $request["fecha_registro"] = date("Y-m-d");
            $request["estado"] = "PENDIENTE";
            $request["anulado"] = 0;
            $memoria_operacion_detalle = MemoriaOperacionDetalle::find($request->mod_id);
            // $presupuesto_usarse = (float)$request->cantidad_usar * (float)$memoria_operacion_detalle->costo;
            // $request["presupuesto_usarse"] = $presupuesto_usarse;
            $certificacion = Certificacion::create(array_map("mb_strtoupper", $request->except("archivo", "mod_id", "cantidad_usar", "presupuesto_usarse", "ids")));

            $mod_id = $request->mod_id;
            $cantidad_usar = $request->cantidad_usar;
            $presupuesto_usarse = $request->presupuesto_usarse;
            for ($i = 0; $i < count($mod_id); $i++) {
                $certificacion_detalle = $certificacion->certificacion_detalles()->create([
                    "mo_id" => $certificacion->mo_id,
                    "mod_id" => $mod_id[$i],
                    "total_cantidad" => 0,
                    "cantidad_usar" => $cantidad_usar[$i],
                    "saldo_cantidad" => 0,
                    "total" => 0,
                    "presupuesto_usarse" => $presupuesto_usarse[$i],
                    "saldo_total" => 0,
                ]);
                CertificacionController::calculaSaldo($certificacion_detalle);
            }

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
        return response()->JSON($certificacion->load("certificacion_detalles.memoria_operacion_detalle"));
    }

    public function pdf(Certificacion $certificacion)
    {
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
        if (!isset($request->cantidad_usar) || !isset($request->presupuesto_usarse) || count($request->cantidad_usar) <= 0 || count($request->presupuesto_usarse) <= 0) {
            throw new Exception("Debes seleccionar/ingresar al menos una Operación|Tarea/actividad|Partida");
        }

        $errors = self::validaDetallesOperacion($request->cantidad_usar, $request->presupuesto_usarse);
        if (count($errors) > 0) {
            return response()->JSON([
                "errors" => $errors,
            ], 422);
        }

        DB::beginTransaction();
        try {
            $certificacion->update(array_map("mb_strtoupper", $request->except("archivo", "mod_id", "cantidad_usar", "presupuesto_usarse", "eliminados", "ids")));
            if ($request->eliminados) {
                $eliminados = $request->eliminados;
                for ($i = 0; $i < count($eliminados); $i++) {
                    $certificacion_detalle = CertificacionDetalle::find($eliminados[$i]);
                    $valor_mo_id = $certificacion_detalle->memoria_operacion_detalle->memoria_operacion_id;
                    $valor_mod_id = $certificacion_detalle->memoria_operacion_detalle->id;
                    $certificacion_detalle->delete();
                    CertificacionController::recalculaSaldosOperacion($valor_mo_id, $valor_mod_id);
                }
            }
            $ids = $request->ids;
            $mod_id = $request->mod_id;
            $cantidad_usar = $request->cantidad_usar;
            $presupuesto_usarse = $request->presupuesto_usarse;
            for ($i = 0; $i < count($ids); $i++) {
                if ($ids[$i] != 0) {
                    // update
                    $certificacion_detalle = CertificacionDetalle::find($ids[$i]);
                    $valor_mo_id = $certificacion_detalle->memoria_operacion_detalle->memoria_operacion_id;
                    $valor_mod_id = $certificacion_detalle->memoria_operacion_detalle->id;
                    $certificacion_detalle->update([
                        "mo_id" => $certificacion->mo_id,
                        "mod_id" => $mod_id[$i],
                        "total_cantidad" => 0,
                        "cantidad_usar" => $cantidad_usar[$i],
                        "saldo_cantidad" => 0,
                        "total" => 0,
                        "presupuesto_usarse" => $presupuesto_usarse[$i],
                        "saldo_total" => 0,
                    ]);
                    // FacadesLog::debug("0: " . $certificacion_detalle->saldo_cantidad);
                    CertificacionController::recalculaSaldosOperacion($valor_mo_id, $valor_mod_id);
                } else {
                    // create
                    $certificacion_detalle = $certificacion->certificacion_detalles()->create([
                        "mo_id" => $certificacion->mo_id,
                        "mod_id" => $mod_id[$i],
                        "total_cantidad" => 0,
                        "cantidad_usar" => $cantidad_usar[$i],
                        "saldo_cantidad" => 0,
                        "total" => 0,
                        "presupuesto_usarse" => $presupuesto_usarse[$i],
                        "saldo_total" => 0,
                    ]);
                    CertificacionController::calculaSaldo($certificacion_detalle);
                }
            }

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

    public static function validarValoresPresupuestoUsar($array_presupuestos, $array_mod_id)
    {
        $error = false;
        $msg = "";
        if (count($array_presupuestos) <= 0) {
            $error = true;
            $msg = "Debes seleccionar al menos una Operación|Tarea/Actividad";
        }
        foreach ($array_presupuestos as $key => $presupuesto) {
            if ($presupuesto < 0) {
                $error = true;
                $msg = "Los valores del Monto a Utilizar no pueden ser menores a 0";
            }

            // buscar si supera el total del mod_id
            $mod = MemoriaOperacionDetalle::find($array_mod_id[$key]);
            if ($mod) {
                if ($presupuesto > $mod->total) {
                    $error = true;
                    if (strlen($msg) > 0) {
                        $msg .= ". ";
                    }
                    $msg .= "Los valores del Monto a Utilizar no pueden ser mayores al total del detalle de operación";
                }
            }
        }

        return [$error, $msg];
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

    public function desaprobar(Certificacion $certificacion)
    {
        $certificacion->estado = "PENDIENTE";
        $certificacion->save();

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "CERTIFICACIÓN POA", "EL USUARIO $user->id MODIFICÓ EL ESTADO DE UNA CERTIFICACIÓN POA A " . $certificacion->estado, $user);

        return response()->JSON(["sw" => true, "certificacion" => $certificacion, "msj" => "El registro se modificó correctamente"]);
    }

    public function update_revertir(Certificacion $certificacion, Request $request)
    {
        $certificacion_detalles = $request->certificacion_detalles;

        foreach ($certificacion_detalles as $cd) {
            $certificacion_detalle = CertificacionDetalle::find($cd["id"]);
            $certificacion_detalle->update(["revertir" => $cd["revertir"]]);
        }

        return response()->JSON(["sw" => true, "certificacion" => $certificacion, "msj" => "El registro se modificó correctamente"]);
    }

    public function getNroCorrelativo()
    {
        $ultimo = Certificacion::where("status", 1)->get()->last();
        if ($ultimo) {
            return response()->JSON((int)$ultimo->correlativo + 1);
        }
        return response()->JSON(1);
    }

    public static function calculaSaldo($certificacion_detalle)
    {
        // FacadesLog::debug("1: " . $certificacion_detalle->saldo_cantidad);
        $ultimo = CertificacionDetalle::select("certificacion_detalles.*")
            ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
            ->where("mod_id", $certificacion_detalle->mod_id)
            ->where("certificacion_detalles.mo_id", $certificacion_detalle->mo_id)
            ->where("certificacion_detalles.id", "<", $certificacion_detalle->id)
            ->where("anulado", 0)
            ->where("certificacions.status", 1)
            ->orderBy("certificacion_detalles.created_at", "asc")->get()->last();
        if ($ultimo) {
            $certificacion_detalle->total_cantidad = $certificacion_detalle->memoria_operacion_detalle->cantidad;
            $certificacion_detalle->saldo_cantidad = (float)$ultimo->saldo_cantidad - (float)$certificacion_detalle->cantidad_usar;
            $certificacion_detalle->total = $certificacion_detalle->memoria_operacion_detalle->total;
            $certificacion_detalle->saldo_total = (float)$ultimo->saldo_total - (float)$certificacion_detalle->presupuesto_usarse;
            // FacadesLog::debug("AAAA");
        } else {
            $certificacion_detalle->total_cantidad = $certificacion_detalle->memoria_operacion_detalle->cantidad;
            $certificacion_detalle->saldo_cantidad = (float)$certificacion_detalle->memoria_operacion_detalle->cantidad - (float)$certificacion_detalle->cantidad_usar;
            $certificacion_detalle->total = $certificacion_detalle->memoria_operacion_detalle->total;
            $certificacion_detalle->saldo_total = (float)$certificacion_detalle->memoria_operacion_detalle->total - (float)$certificacion_detalle->presupuesto_usarse;
            // FacadesLog::debug("BBBB");
        }
        $certificacion_detalle->save();
        // FacadesLog::debug("2: " . $certificacion_detalle->saldo_cantidad);
        // FacadesLog::debug("------------------------------------------");
        return true;
    }

    public function corrige_certificacions()
    {
        CertificacionController::recalculaSaldos();
        return 'Certificaciones corregidas<br><a href="/">Volver al inicio</a>';
    }

    public static function recalculaSaldos()
    {
        $certificacions = Certificacion::where("status", 1)->get();
        foreach ($certificacions as $certificacion) {
            CertificacionController::calculaSaldo($certificacion);
        }
        return true;
    }

    public static function recalculaSaldosOperacion($mo_id, $mod_id)
    {
        $certificacion_detalles = CertificacionDetalle::select("certificacion_detalles.*")
            ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
            ->where('certificacion_detalles.mo_id', $mo_id)
            ->where('mod_id', $mod_id)
            ->where("anulado", 0)
            ->where("certificacions.status", 1)
            ->get();
        foreach ($certificacion_detalles as $certificacion_detalle) {
            CertificacionController::calculaSaldo($certificacion_detalle);
        }
        return true;
    }

    public function archivo(Certificacion $certificacion) {}

    public static function validaDetallesOperacion($cantidad_usar, $presupuesto_usarse)
    {
        $errors = [];
        for ($i = 0; $i < count($cantidad_usar); $i++) {
            if ((float)$cantidad_usar[$i] <= 0) {
                // FacadesLog::debug($cantidad_usar[$i]);
                $errors["cantidad_usar"] = ["Las cantidades ingresadas no pueden ser menores o iguales a 0"];
                $errors["cantidad_usar_" . $i] = ["Las cantidad ingresada debe ser mayor a 0"];
            }

            if ((float)$presupuesto_usarse[$i] <= 0) {
                // FacadesLog::debug($presupuesto_usarse[$i]);
                $errors["cantidad_usar"] = ["Los montos ingresados no pueden ser menores o iguales a 0"];
                $errors["presupuesto_usarse_" . $i] = ["El monto ingresa debe ser mayor a 0"];
            }
        }
        // FacadesLog::debug($errors);

        return $errors;
    }

    public function corrige_correlativos()
    {
        $certificacions = Certificacion::where("status", 1)->orderBy("created_at", "asc")->get();
        foreach ($certificacions as $key => $certificacion) {
            $certificacion->correlativo = (int)$key + 1;
            $certificacion->save();
        }
        return 'Certificaciones corregidas<br><a href="/">Volver al inicio</a>';
    }

    // fix registros
    public function fix_registros()
    {
        $certificacions = Certificacion::where("status", 1)->get();
        foreach ($certificacions as $certificacion) {
            foreach ($certificacion->certificacion_detalles as $key => $certificacion_detalle) {
                $presupuesto_usarse = $certificacion_detalle->presupuesto_usarse;
                // $cantidad_usar = $presupuesto_usarse *
            }
        }
    }
}
