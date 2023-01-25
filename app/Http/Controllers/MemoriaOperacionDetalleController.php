<?php

namespace App\Http\Controllers;

use App\Models\MemoriaOperacionDetalle;
use Illuminate\Http\Request;

class MemoriaOperacionDetalleController extends Controller
{
    public function getDetalles(Request $request)
    {
        $memoria_operacion_detalles = MemoriaOperacionDetalle::where("memoria_operacion_id", $request->id)->get();
        return response()->JSON($memoria_operacion_detalles->load("memoria_operacion"));
    }
}
