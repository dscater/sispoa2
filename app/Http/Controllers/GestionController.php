<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;

class GestionController extends Controller
{
    public $validacion = [
        'gestion' => 'required|numeric',
        'descripcion' => 'required|min:4',
    ];

    public function index()
    {
        $gestions = Gestion::all();
        return response()->JSON(["gestions" => $gestions, "total" => count($gestions)]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate($this->validacion);
            Gestion::create(array_map("mb_strtoupper", $request->all()));

            $user = Auth::user();
            Log::registrarLog("CREACIÓN", "ADMINISTRACIÓN DE GESTIÓN", "EL USUARIO $user->id REGISTRO UNA NUEVA GESTIÓN", $user);

            // set status 0
            DB::update("UPDATE formulario_cuatro SET status = 0;");
            DB::update("UPDATE formulario_cinco SET status = 0;");
            DB::update("UPDATE detalle_formularios SET status = 0;");
            DB::update("UPDATE memoria_calculos SET status = 0;");
            DB::update("UPDATE certificacions SET status = 0;");

            DB::commit();
            return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
        } catch (\Exception $e) {
            FacadesLog::debug($e->getMessage());
            DB::rollBack();
            return response()->JSON(["sw" => false, "message" => "El registro se almacenó correctamente"], 500);
        }
    }

    public function show(Gestion $gestion)
    {
        return response()->JSON($gestion);
    }

    public function update(Gestion $gestion, Request $request)
    {
        $request->validate($this->validacion);
        $gestion->update(array_map("mb_strtoupper", $request->all()));

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "ADMINISTRACIÓN DE GESTIÓN", "EL USUARIO $user->id MODIFICÓ UNA NUEVA GESTIÓN", $user);

        return response()->JSON(["sw" => true, "gestion" => $gestion, "msj" => "El registro se actualizó correctamente"]);
    }

    public function destroy(Gestion $gestion)
    {
        $gestion->delete();
        $user = Auth::user();
        Log::registrarLog("ELIMINACIÓN", "ADMINISTRACIÓN DE GESTIÓN", "EL USUARIO $user->id ELIMINÓ UNA NUEVA GESTIÓN", $user);

        return response()->JSON(["sw" => true, "msj" => "El registro se eliminó correctamente"]);
    }
}
