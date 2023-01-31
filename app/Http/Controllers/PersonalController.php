<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\Log;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalController extends Controller
{
    public $validacion = [
        'nombre' => 'required|min:1',
        'paterno' => 'required|min:1',
        'cargo' => 'required|min:1',
    ];

    public function index()
    {
        $personals = Personal::orderBy("paterno", "asc")->get();
        return response()->JSON(["personals" => $personals, "total" => count($personals)]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion);
        // $request["fecha_registro"] = date("Y-m-d");
        $personal = Personal::create(array_map("mb_strtoupper", $request->except("archivo")));

        $user = Auth::user();
        Log::registrarLog("CREACIÓN", "PERSONAL", "EL USUARIO $user->id REGISTRO UN NUEVO PERSONAL", $user);

        return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
    }

    public function show(Personal $personal)
    {
        return response()->JSON($personal);
    }

    public function update(Personal $personal, Request $request)
    {
        $request->validate($this->validacion);
        $personal->update(array_map("mb_strtoupper", $request->except("archivo")));

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "PERSONAL", "EL USUARIO $user->id MODIFICÓ UN PERSONAL", $user);

        return response()->JSON(["sw" => true, "personal" => $personal, "msj" => "El registro se actualizó correctamente"]);
    }

    public function destroy(Personal $personal)
    {
        $existe = Certificacion::where("personal_designado", $personal->id)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "msj" => "El registro no puede ser eliminado debido a que se esta utilizando en Certificaciones"]);
        }

        $personal->delete();
        $user = Auth::user();
        Log::registrarLog("ELIMINACIÓN", "PERSONAL", "EL USUARIO $user->id ELIMINÓ UN PERSONAL", $user);

        return response()->JSON(["sw" => true, "personal" => $personal, "msj" => "El registro se actualizó correctamente"]);
    }
}
