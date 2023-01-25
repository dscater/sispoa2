<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormularioDosController extends Controller
{
    public function formulario_dos()
    {
        $formulario_dos = view("vistas.formulario_dos")->render();
        return response()->JSON($formulario_dos);
    }
}
