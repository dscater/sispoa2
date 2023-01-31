<?php

namespace App\Http\Controllers;

use App\Models\Aprobacion;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AprobacionController extends Controller
{
    public function index()
    {
        $aprobacion_formulario = Aprobacion::where("unidad_id", Auth::user()->unidad_id)->get()->first();
        if (!$aprobacion_formulario) {
            $aprobacion_formulario = Aprobacion::create([
                "unidad_id" => Auth::user()->unidad_id,
                "estado" => 0,
            ]);
        }
        return response()->JSON(["aprobacion_formulario" => $aprobacion_formulario, "total" => 1]);
    }

    public function update(Request $request, Aprobacion $aprobacion)
    {
        $aprobacion->update($request->all());
        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "APROBACIÓN DE FORMULARIOS", "EL USUARIO $user->id REALIZÓ UNA APROBACIÓN/PENDIENTE DE FORMULARIOS UNIDAD " . $user->unidad->nombre, $user);
        return response()->JSON(["sw" => true, "aprobacion" => $aprobacion, "msj" => "El registro se actualizó correctamente"]);
    }
}
