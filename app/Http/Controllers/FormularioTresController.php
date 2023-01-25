<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormularioTresController extends Controller
{
    public function formulario_tres()
    {
        $formulario_tres = view("vistas.formulario_tres")->render();
        return response()->JSON($formulario_tres);
    }
}
