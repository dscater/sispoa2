<?php

namespace App\Http\Controllers;

use App\Models\MemoriaOperacion;
use Illuminate\Http\Request;

class MemoriaOperacionController extends Controller
{
    public function memoriaOperacion(MemoriaOperacion $memoriaOperacion)
    {
        return response()->JSON($memoriaOperacion->load(["operacion"]));
    }
}
