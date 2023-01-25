<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeiController extends Controller
{
    public function mision()
    {
        $html = view("vistas.mision")->render();
        return response()->JSON($html);
    }

    public function vision()
    {
        $html = view("vistas.vision")->render();
        return response()->JSON($html);
    }

    public function objetivos()
    {
        $html = view("vistas.objetivos")->render();
        return response()->JSON($html);
    }
}
