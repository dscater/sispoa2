<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfiguracionController extends Controller
{
    public function getConfiguracion()
    {
        $configuracion = Configuracion::first();
        if ($configuracion) {
            return response()->JSON([
                'sw' => true,
                'configuracion' => $configuracion
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
            'nombre_sistema' => 'required|min:4',
            'alias' => 'required|min:1',
            'razon_social' => 'required|min:4',
            'ciudad' => 'required|min:4',
            'dir' => 'required|min:4',
            'fono' => 'required|min:4',
            'correo' => 'nullable|email',
            'actividad' => 'required|min:4',
        ];
        $mensajes =  [
            'nombre_sistema.required' => 'Este campo es obligatorio',
            'nombre_sistema.min' => 'Debes ingresar al menos 4 carácteres',
            'razon_social.required' => 'Este campo es obligatorio',
            'razon_social.min' => 'Debes ingresar al menos 4 carácteres',
            'nit.required' => 'Este campo es obligatorio',
            'ciudad.required' => 'Este campo es obligatorio',
            'ciudad.min' => 'Debes ingresar al menos 4 carácteres',
            'dir.required' => 'Este campo es obligatorio',
            'dir.min' => 'Debes ingresar al menos 4 carácteres',
            'fono.required' => 'Este campo es obligatorio',
            'fono.min' => 'Debes ingresar al menos 4 carácteres',
            'actividad.required' => 'Este campo es obligatorio',
            'actividad.min' => 'Debes ingresar al menos 4 carácteres',
        ];

        if ($request->hasFile('logo')) {
            $validacion['logo'] = 'image|mimes:jpeg,jpg,png|max:4096';
        }
        $request->validate($validacion, $mensajes);
        $configuracion = Configuracion::first();
        if ($configuracion) {
            $configuracion->update(array_map('mb_strtoupper', $request->except('logo')));
            if ($request->hasFile('logo')) {
                $antiguo = $configuracion->logo;
                \File::delete(public_path() . '/imgs/' . $antiguo);
                $file = $request->logo;
                $nombre = time() . '_logo.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/imgs/', $nombre);
                $configuracion->logo = $nombre;
                $configuracion->save();
            }

            if ($request->hasFile('logo2')) {
                $antiguo = $configuracion->logo2;
                \File::delete(public_path() . '/imgs/' . $antiguo);
                $file = $request->logo2;
                $nombre = time() . '_logo2.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/imgs/', $nombre);
                $configuracion->logo2 = $nombre;
                $configuracion->save();
            }

            $user = Auth::user();
            Log::registrarLog("MODIFICACIÓN", "CONFIGURACIÓN", "EL USUARIO $user->id MODIFICÓ LA CONFIGURACIÓN DEL SISTEMA", $user);

            return response()->JSON([
                'sw' => true,
                'msj' => 'Los datos se actualizarón de forma correcta',
                'configuracion' => $configuracion
            ], 200);
        }
        return response()->JSON([
            'sw' => false,
            'msj' => 'No se encontró ninguna configuración'
        ], 200);
    }
}
