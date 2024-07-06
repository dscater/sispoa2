<?php

namespace App\Http\Controllers;

use App\Models\DetalleFormulario;
use App\Models\DetalleOperacion;
use App\Models\Log;
use App\Models\Operacion;
use App\Models\Semaforo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SemaforoController extends Controller
{
    public $validacion = [
        'descripcion' => 'required|min:4',
    ];

    public function index()
    {
        $semaforos = Semaforo::all();
        return response()->JSON(["semaforos" => $semaforos, "total" => count($semaforos)]);
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
}
