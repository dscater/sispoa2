<?php

namespace App\Http\Controllers;

use App\Models\ActividadTarea;
use Illuminate\Http\Request;

class ActividadTareaController extends Controller
{
    public function getPartidas(Request $request)
    {
        $actividad_tarea_id = $request->actividad_tarea_id;
        $actividad_tarea = ActividadTarea::find($actividad_tarea_id);
        return response()->JSON($actividad_tarea->partidas);
    }
}
