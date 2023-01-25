<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormularioUnoController extends Controller
{
    public function formulario_uno()
    {
        $formulario_uno = view("vistas.formulario_uno")->render();
        return response()->JSON($formulario_uno);
    }
}
