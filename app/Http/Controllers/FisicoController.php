<?php

namespace App\Http\Controllers;

use App\Models\Fisico;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
