<?php

namespace App\Http\Controllers;

use App\Models\ActividadRealizada;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActividadRealizadaController extends Controller
{
    public $validacion = [
        'descripcion' => 'required|min:4',
    ];

    public function index()
    {
        $actividad_realizadas = ActividadRealizada::all();
        return response()->JSON(["actividad_realizadas" => $actividad_realizadas, "total" => count($actividad_realizadas)]);
    }

    public function store(Request $request)
    {
        $this->validacion["archivo"] = 'required|file|mimes:pdf|max:4096';
        $request->validate($this->validacion);
        $request["fecha_registro"] = date("Y-m-d");
        $actividad_realizada = ActividadRealizada::create(array_map("mb_strtoupper", $request->except("archivo")));
        if ($request->hasFile('archivo')) {
            $file = $request->archivo;
            $nom_archivo = time() . '_actividad_realizada' . $actividad_realizada->id . '.' . $file->getClientOriginalExtension();
            $actividad_realizada->archivo = $nom_archivo;
            $file->move(public_path() . '/files/', $nom_archivo);
            $actividad_realizada->save();
        }

        $user = Auth::user();
        Log::registrarLog("CREACIÓN", "INFORME DE ACTIVIDAD REALIZADA", "EL USUARIO $user->id REGISTRO UN INFORME DE ACTIVIDAD REALIZADA", $user);

        return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
    }

    public function show(ActividadRealizada $actividad_realizada)
    {
        return response()->JSON($actividad_realizada);
    }

    public function update(ActividadRealizada $actividad_realizada, Request $request)
    {
        if ($request->hasFile('archivo')) {
            $this->validacion["archivo"] = 'required|file|mimes:pdf|max:4096';
        }
        $request->validate($this->validacion);
        $actividad_realizada->update(array_map("mb_strtoupper", $request->except("archivo")));
        if ($request->hasFile('archivo')) {
            $antiguo = $actividad_realizada->archivo;
            \File::delete(public_path() . "/files/" . $antiguo);

            $file = $request->archivo;
            $nom_archivo = time() . '_actividad_realizada' . $actividad_realizada->id . '.' . $file->getClientOriginalExtension();
            $actividad_realizada->archivo = $nom_archivo;
            $file->move(public_path() . '/files/', $nom_archivo);
            $actividad_realizada->save();
        }

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "INFORME DE ACTIVIDAD REALIZADA", "EL USUARIO $user->id MODIFICÓ UN INFORME DE ACTIVIDAD REALIZADA", $user);

        return response()->JSON(["sw" => true, "actividad_realizada" => $actividad_realizada, "msj" => "El registro se actualizó correctamente"]);
    }

    public function destroy(ActividadRealizada $actividad_realizada)
    {
        $antiguo = $actividad_realizada->archivo;
        \File::delete(public_path() . "/files/" . $antiguo);
        $actividad_realizada->delete();

        $user = Auth::user();
        Log::registrarLog("ELIMINACIÓN", "INFORME DE ACTIVIDAD REALIZADA", "EL USUARIO $user->id ELIMINÓ UN INFORME DE ACTIVIDAD REALIZADA", $user);

        return response()->JSON(["sw" => true, "actividad_realizada" => $actividad_realizada, "msj" => "El registro se actualizó correctamente"]);
    }

    public function archivo(ActividadRealizada $actividad_realizada)
    {
        return response()->download(public_path() . "/files/" . $actividad_realizada->archivo, $actividad_realizada->archivo);
    }
}
