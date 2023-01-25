<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\VerificacionActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificacionActividadController extends Controller
{
    public function getVerificacionActividad()
    {
        $verificacion_actividad = VerificacionActividad::first();
        if ($verificacion_actividad) {
            return response()->JSON([
                'sw' => true,
                'verificacion_actividad' => $verificacion_actividad
            ]);
        }
        return response()->JSON([
            'sw' => false,
            'msj' => 'No se encontró ninguna configuración'
        ], 200);
    }

    public function update(Request $request)
    {
        $validacion = [
            'gestion' => 'required',
            'actividad' => 'required|min:4',
        ];
        $mensajes =  [];

        $request->validate($validacion, $mensajes);
        $verificacion_actividad = VerificacionActividad::first();
        if ($verificacion_actividad) {
            $verificacion_actividad->update(array_map('mb_strtoupper', $request->except("actividad")));
            $verificacion_actividad->actividad = $request->actividad;
            $verificacion_actividad->save();

            $user = Auth::user();
            Log::registrarLog("MODIFICACIÓN", "VERIFICACIÓN DE LA ACTIVDAD POA", "EL USUARIO $user->id MODIFICÓ LA VERIFICACIÓN DE LA ACTIVDAD POA", $user);

            return response()->JSON([
                'sw' => true,
                'msj' => 'Los datos se actualizarón de forma correcta',
                'verificacion_actividad' => $verificacion_actividad
            ], 200);
        }

        return response()->JSON([
            'sw' => false,
            'msj' => 'No se encontró ninguna configuración'
        ], 200);
    }
}
