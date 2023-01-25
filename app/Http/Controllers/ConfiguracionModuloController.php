<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionModulo;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfiguracionModuloController extends Controller
{
    public function index()
    {
        $configuracion_modulos = ConfiguracionModulo::where("modulo", "!=", "APROBAR FORMULARIOS")->get();
        return response()->JSON(["configuracion_modulos" => $configuracion_modulos, "total" => count($configuracion_modulos)]);
    }


    public function get_aprobar_formularios()
    {
        $configuracion_modulos = ConfiguracionModulo::where("modulo", "APROBAR FORMULARIOS")->get();
        return response()->JSON(["configuracion_modulos" => $configuracion_modulos, "total" => count($configuracion_modulos)]);
    }

    public function get_aprobados()
    {
        $configuracion_modulos = ConfiguracionModulo::where("modulo", "APROBAR FORMULARIOS")->get()->first();
        return response()->JSON($configuracion_modulos->editar === 1 ? true : false);
    }

    public function update(Request $request, ConfiguracionModulo $configuracion_modulo)
    {
        $configuracion_modulo->update($request->all());
        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "CONFIGURACIÓN DE MODULOS", "EL USUARIO $user->id REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR", $user);
        return response()->JSON(["sw" => true, "configuracion_modulo" => $configuracion_modulo, "msj" => "El registro se actualizó correctamente"]);
    }
}
