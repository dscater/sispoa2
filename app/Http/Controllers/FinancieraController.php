<?php

namespace App\Http\Controllers;

use App\Models\Financiera;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancieraController extends Controller
{
    public $validacion = [
        'descripcion' => 'required|min:4',
    ];

    public function index()
    {
        $financieras = Financiera::all();
        return response()->JSON(["financieras" => $financieras, "total" => count($financieras)]);
    }

    public function store(Request $request)
    {
        $this->validacion["archivo"] = 'required|image|mimes:jpeg,jpg,png|max:4096';
        $request->validate($this->validacion);
        $request["fecha_registro"] = date("Y-m-d");
        $financiera = Financiera::create(array_map("mb_strtoupper", $request->except("archivo")));
        if ($request->hasFile('archivo')) {
            $file = $request->archivo;
            $nom_archivo = time() . '_financiera' . $financiera->id . '.' . $file->getClientOriginalExtension();
            $financiera->archivo = $nom_archivo;
            $file->move(public_path() . '/files/', $nom_archivo);
            $financiera->save();
        }

        $user = Auth::user();
        Log::registrarLog("CREACIÓN", "FINANCIERO", "EL USUARIO $user->id REGISTRO UN FINANCIERO", $user);

        return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
    }

    public function show(Financiera $financiera)
    {
        return response()->JSON($financiera);
    }

    public function update(Financiera $financiera, Request $request)
    {
        if ($request->hasFile('archivo')) {
            $this->validacion["archivo"] = 'required|image|mimes:jpeg,jpg,png|max:4096';
        }
        $request->validate($this->validacion);
        $financiera->update(array_map("mb_strtoupper", $request->except("archivo")));
        if ($request->hasFile('archivo')) {
            $antiguo = $financiera->archivo;
            \File::delete(public_path() . "/files/" . $antiguo);

            $file = $request->archivo;
            $nom_archivo = time() . '_financiera' . $financiera->id . '.' . $file->getClientOriginalExtension();
            $financiera->archivo = $nom_archivo;
            $file->move(public_path() . '/files/', $nom_archivo);
            $financiera->save();
        }

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "FINANCIERO", "EL USUARIO $user->id MODIFICÓ UN FINANCIERO", $user);

        return response()->JSON(["sw" => true, "financiera" => $financiera, "msj" => "El registro se actualizó correctamente"]);
    }

    public function destroy(Financiera $financiera)
    {
        $antiguo = $financiera->archivo;
        \File::delete(public_path() . "/files/" . $antiguo);
        $financiera->delete();

        $user = Auth::user();
        Log::registrarLog("ELIMINACIÓN", "FINANCIERO", "EL USUARIO $user->id ELIMINÓ UN FINANCIERO", $user);

        return response()->JSON(["sw" => true, "financiera" => $financiera, "msj" => "El registro se actualizó correctamente"]);
    }
}
