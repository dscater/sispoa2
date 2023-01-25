<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\MemoriaOperacion;
use App\Models\MemoriaOperacionDetalle;
use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartidaController extends Controller
{
    public $validacion = [
        'partida' => 'required|min:1',
        'descripcion' => 'required|min:4',
    ];

    public function index()
    {
        $partidas = Partida::all();
        return response()->JSON(["partidas" => $partidas, "total" => count($partidas)]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion);
        Partida::create(array_map("mb_strtoupper", $request->all()));

        $user = Auth::user();
        Log::registrarLog("CREACIÓN", "PARTIDAS", "EL USUARIO $user->id REGISTRO UNA PARTIDA", $user);

        return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
    }

    public function show(Partida $partida)
    {
        return response()->JSON($partida);
    }

    public function update(Partida $partida, Request $request)
    {
        $request->validate($this->validacion);
        $partida->update(array_map("mb_strtoupper", $request->all()));

        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "PARTIDAS", "EL USUARIO $user->id MODIFICÓ UNA PARTIDA", $user);

        return response()->JSON(["sw" => true, "partida" => $partida, "msj" => "El registro se actualizó correctamente"]);
    }

    public function destroy(Partida $partida)
    {
        $existe = MemoriaOperacionDetalle::where("partida_id", $partida->id)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "partida" => $partida, "msj" => "No es posible eliminar este registro, porque esta siendo utilizado por otros modulos"]);
        }

        $partida->delete();

        $user = Auth::user();
        Log::registrarLog("ELIMINACIÓN", "PARTIDAS", "EL USUARIO $user->id ELIMINÓ UNA PARTIDA", $user);

        return response()->JSON(["sw" => true, "msj" => "El registro se eliminó correctamente"]);
    }
}
