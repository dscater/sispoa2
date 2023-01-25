<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Subdireccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubdireccionController extends Controller
{
    public $validacion = [
        'nombre' => 'required|min:1',
    ];

    public function index()
    {
        $subdireccions = Subdireccion::all();
        return response()->JSON(["subdireccions" => $subdireccions, "total" => count($subdireccions)]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion);
        $subdireccion = Subdireccion::create(array_map("mb_strtoupper", $request->except("archivo")));

        $user = Auth::user();
        Log::registrarLog("CREACIÓN", "SUBDIRECCIONES", "EL USUARIO $user->id REGISTRO UNA SUBDIRECCIÓN", $user);

        return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
    }

    public function show(Subdireccion $subdireccion)
    {
        return response()->JSON($subdireccion);
    }

    public function update(Subdireccion $subdireccion, Request $request)
    {
        $request->validate($this->validacion);
        $subdireccion->update(array_map("mb_strtoupper", $request->except("archivo")));

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "SUBDIRECCIONES", "EL USUARIO $user->id MODIFICÓ UNA SUBDIRECCIÓN", $user);

        return response()->JSON(["sw" => true, "subdireccion" => $subdireccion, "msj" => "El registro se actualizó correctamente"]);
    }

    public function destroy(Subdireccion $subdireccion)
    {
        $subdireccion->delete();

        $user = Auth::user();
        Log::registrarLog("ELIMINACIÓN", "SUBDIRECCIONES", "EL USUARIO $user->id ELIMINÓ UNA SUBDIRECCIÓN", $user);

        return response()->JSON(["sw" => true, "subdireccion" => $subdireccion, "msj" => "El registro se actualizó correctamente"]);
    }
}
