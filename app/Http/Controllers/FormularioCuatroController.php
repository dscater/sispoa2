<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\DetalleFormulario;
use App\Models\FormularioCinco;
use App\Models\FormularioCuatro;
use App\Models\Log;
use App\Models\MemoriaCalculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FormularioCuatroController extends Controller
{
    public $validacion = [
        'codigo_pei' => 'required|min:1',
        'objetivo_estrategico' => 'required|min:1',
        'resultado_institucional' => 'required|min:1',
        'indicador' => 'required|min:1',
        'codigo_poa' => 'required|min:1',
        'accion_corto' => 'required|min:1',
        'linea_base' => 'required|min:1',
        'meta' => 'required|min:1',
        'presupuesto' => 'required|numeric',
        'ponderacion' => 'required|numeric',
        'unidad_id' => 'required',
    ];

    public $mensajes = [
        "codigo_pei.min" => "Debes ingresar al menos un código",
        "codigo_poa.min" => "Debes ingresar al menos un código",
    ];

    public function index(Request $request)
    {
        $listado = [];
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "ENLACE") {
            $listado = FormularioCuatro::where("unidad_id", Auth::user()->unidad_id)->get();
        } else {
            $listado = FormularioCuatro::all();
        }
        return response()->JSON(['listado' => $listado, 'total' => count($listado)], 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            $request['fecha_registro'] = date('Y-m-d');
            unset($request["objetivo_estrategico"]);
            $nuevo_formulario_cuatro = FormularioCuatro::create(array_map('mb_strtoupper', $request->all()));

            $user = Auth::user();
            Log::registrarLog("CREACIÓN", "FORMULARIO CUATRO", "EL USUARIO $user->id REGISTRO UN FORMULARIO CUATRO", $user);

            DB::commit();
            return response()->JSON([
                'sw' => true,
                'formulario_cuatro' => $nuevo_formulario_cuatro,
                'msj' => 'El registro se realizó de forma correcta',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, FormularioCuatro $formulario_cuatro)
    {
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            unset($request["objetivo_estrategico"]);
            $formulario_cuatro->update(array_map('mb_strtoupper', $request->all()));

            $user = Auth::user();
            Log::registrarLog("MODIFICACIÓN", "FORMULARIO CUATRO", "EL USUARIO $user->id MODIFICÓ UN FORMULARIO CUATRO", $user);

            DB::commit();
            return response()->JSON([
                'sw' => true,
                'formulario_cuatro' => $formulario_cuatro,
                'msj' => 'El registro se actualizó de forma correcta'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(FormularioCuatro $formulario_cuatro)
    {
        return response()->JSON([
            'sw' => true,
            'formulario_cuatro' => $formulario_cuatro
        ], 200);
    }

    public function destroy(FormularioCuatro $formulario_cuatro)
    {

        $existe = DetalleFormulario::where("formulario_id", $formulario_cuatro->id)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "formulario_cuatro" => $formulario_cuatro, "msj" => "No es posible eliminar este registro, porque esta siendo utilizado por otros modulos"]);
        }

        $existe = Certificacion::where("formulario_id", $formulario_cuatro->id)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "formulario_cuatro" => $formulario_cuatro, "msj" => "No es posible eliminar este registro, porque esta siendo utilizado por otros modulos"]);
        }

        $existe = MemoriaCalculo::where("formulario_id", $formulario_cuatro->id)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "formulario_cuatro" => $formulario_cuatro, "msj" => "No es posible eliminar este registro, porque esta siendo utilizado por otros modulos"]);
        }

        DB::beginTransaction();
        try {
            $formulario_cuatro->delete();
            $user = Auth::user();
            Log::registrarLog("ELIMINACIÓN", "FORMULARIO CUATRO", "EL USUARIO $user->id ELIMINÓ UN FORMULARIO CUATRO", $user);

            DB::commit();
            return response()->JSON([
                'sw' => true,
                'msj' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function getPorUnidad(Request $request)
    {
        $formularios = FormularioCuatro::where("unidad_id", $request->id)->get();
        return response()->JSON($formularios);
    }

    public function getOperaciones(Request $request)
    {
        $formulario_cuatro = FormularioCuatro::find($request->id);
        $operaciones = $formulario_cuatro->detalle_formulario->operacions;
        return response()->JSON($operaciones);
    }
}
