<?php

namespace App\Http\Controllers;

use App\Models\Anteproyecto;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnteproyectoController extends Controller
{
    public function index()
    {
        $anteproyectos = Anteproyecto::where("status", 1)->get();
        return response()->JSON(["anteproyectos" => $anteproyectos, "total" => count($anteproyectos)]);
    }

    public function store()
    {
        $o_anteproyecto = new Anteproyecto();
        $file_form4PDF = "";
        $file_form5PDF = "";
        $file_memoriaPDF = "";
        $file_certificacionPDF = "";


        $file_form4PDF = $o_anteproyecto->generaForm4PDF();
        $file_form5PDF = $o_anteproyecto->generaForm5PDF();
        $file_memoriaPDF = $o_anteproyecto->generaMemoriaPDF();
        $file_certificacionPDF = $o_anteproyecto->generaCertificacionPDF();
        $file_form4Excel = $o_anteproyecto->generaForm4Excel();
        $file_form5Excel = $o_anteproyecto->generaForm5Excel();
        $file_memoriaExcel = $o_anteproyecto->generaMemoriaExcel();
        $file_certificacionExcel = $o_anteproyecto->generaCertificacionExcel();

        $fecha = date("Y-m-d");
        $anteproyecto = Anteproyecto::create([
            "form4_excel" => $file_form4Excel,
            "form5_excel" => $file_form5Excel,
            "memoria_excel" => $file_memoriaExcel,
            "certificacion_excel" => $file_certificacionExcel,
            "form4_pdf" => $file_form4PDF,
            "form5_pdf" => $file_form5PDF,
            "memoria_pdf" => $file_memoriaPDF,
            "certificacion_pdf" => $file_certificacionPDF,
            "fecha_cierre" => $fecha
        ]);

        // $user = Auth::user();
        // Log::registrarLog("CREACIÓN", "ANTEPROYECTO POA", "EL USUARIO $user->id GENERÓ UN ANTEPROYECTO", $user);
        return response()->JSON(["sw" => true, "msj" => "El registro se almacenó correctamente"]);
    }
}
