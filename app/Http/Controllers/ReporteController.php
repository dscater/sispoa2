<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\Configuracion;
use App\Models\DetalleOperacion;
use App\Models\Financiera;
use App\Models\Fisico;
use App\Models\FormularioCinco;
use App\Models\FormularioCuatro;
use App\Models\MemoriaCalculo;
use App\Models\MemoriaOperacion;
use App\Models\MemoriaOperacionDetalle;
use App\Models\Operacion;
use App\Models\Partida;
use App\Models\Semaforo;
use App\Models\Unidad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ReporteController extends Controller
{
    public static function getFechaTexto()
    {
        $meses_text = [
            "01" => "enero",
            "02" => "febrero",
            "03" => "marzo",
            "04" => "abril",
            "05" => "mayo",
            "06" => "junio",
            "07" => "julio",
            "08" => "agosto",
            "09" => "septiembre",
            "10" => "octubre",
            "11" => "noviembre",
            "12" => "diciembre",
        ];

        $fecha = "La Paz " . date("d") . " de " . $meses_text[date("m")] . " de " . date("Y");
        return $fecha;
    }

    public function usuarios(Request $request)
    {
        $filtro =  $request->filtro;
        $usuarios = User::where('id', '!=', 1)->get();
        if ($filtro == 'Rango de fechas') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
            $usuarios = User::where('id', '!=', 1)->whereBetween('fecha_registro', [$request->fecha_ini, $request->fecha_fin])->get();
        }

        if ($filtro == 'Tipo de usuario') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios = User::where('id', '!=', 1)->where('tipo', $request->tipo)->get();
        }

        if ($filtro == 'Unidad Organizacional') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios = User::where('id', '!=', 1)->where('unidad_id', $request->unidad_id)->get();
        }

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('Usuarios.pdf');
    }

    public function saldo_presupuesto(Request $request)
    {
        $filtro =  $request->filtro;
        $unidad_id =  $request->unidad_id;
        $formulario_id =  $request->formulario_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $formularios = FormularioCuatro::all();
        if ($filtro != "Todos") {
            switch ($filtro) {
                case "Unidad Organizacional":
                    $formularios = FormularioCuatro::where("unidad_id", $unidad_id)->get();
                    break;
                case "Código PEI":
                    $formularios = FormularioCuatro::where("id", $formulario_id)->get();
                    break;
                case "Rango de fechas":
                    $formularios = FormularioCuatro::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
                    break;
            }
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("ADMIN")
            ->setLastModifiedBy('Administración')
            ->setTitle('Formularios')
            ->setSubject('Formularios')
            ->setDescription('Formularios')
            ->setKeywords('PHPSpreadsheet')
            ->setCategory('Listado');

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

        $styleTexto = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 9,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '0062A5']
            ],
        ];

        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 9,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '0062A5']
            ],
        ];

        $estilo_conenido = [
            'font' => [
                'size' => 8,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $estilo_total = [
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '0062A5']
            ],
        ];


        $fila = 1;
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('logo');
        $drawing->setDescription('logo');
        $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo); // put your path and image here
        $drawing->setCoordinates('A' . $fila);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(0);
        $drawing->setHeight(50);
        $drawing->setWorksheet($sheet);
        $fila = 2;

        foreach ($formularios as $formulario_cuatro) {
            $sheet->setCellValue('A' . $fila, "SALDO PRESUPUESTARIO - GESTIÓN " . date("Y"));
            $sheet->mergeCells("A" . $fila . ":I" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':I' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':I' . $fila)->applyFromArray($styleTexto);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'CÓDIGO PEI');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, str_replace(",", "\n", $formulario_cuatro->codigo_pei));
            $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'OBJETIVO ESTRATÉGICO INSTITUCIONAL');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $formulario_cuatro->accion_institucional);
            $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'INDICADOR');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $formulario_cuatro->indicador);
            $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'CODIGO POA');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, str_replace(",", "\n", $formulario_cuatro->codigo_poa));
            $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'ACCIÓN DE CORTO PLAZO DE GESTIÓN');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $formulario_cuatro->accion_corto);
            $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'RESULTADO ESPERADO GESTIÓN');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $formulario_cuatro->resultado_esperado);
            $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'PRESUPUESTO PROGRAMADO GESTIÓN');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, number_format($formulario_cuatro->presupuesto, 2) . " ");
            $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'PONDERACIÓN %');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, number_format($formulario_cuatro->ponderacion, 2) . " ");
            $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'UNIDAD ORGANIZACIONAL');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $formulario_cuatro->unidad->nombre);
            $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);

            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'Código tarea');
            $sheet->mergeCells("A" . $fila . ":A" . ($fila + 1));  //COMBINAR CELDAS
            $sheet->setCellValue('B' . $fila, 'Actividad/Tarea');
            $sheet->mergeCells("B" . $fila . ":B" . ($fila + 1));  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, 'Partida');
            $sheet->mergeCells("C" . $fila . ":C" . ($fila + 1));  //COMBINAR CELDAS
            $sheet->setCellValue('D' . $fila, 'Presupuesto');
            $sheet->mergeCells("D" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('G' . $fila, 'Ejecutado');
            $sheet->mergeCells("G" . $fila . ":H" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('I' . $fila, 'Saldo');
            $sheet->mergeCells("I" . $fila . ":I" . ($fila + 1));  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':I' . $fila)->applyFromArray($styleArray2);
            $fila++;
            $sheet->setCellValue('D' . $fila, 'Cantidad');
            $sheet->setCellValue('E' . $fila, 'Costo Unitario');
            $sheet->setCellValue('F' . $fila, 'Presupuesto vigente');
            $sheet->setCellValue('G' . $fila, 'Cantidad');
            $sheet->setCellValue('H' . $fila, 'Presupuesto Vigente');
            $sheet->getStyle('A' . $fila . ':I' . $fila)->applyFromArray($styleArray2);
            $fila++;
            $suma_ejecutados = 0;
            $suma_saldos = 0;
            if ($formulario_cuatro->memoria_calculo) {
                foreach ($formulario_cuatro->memoria_calculo->operacions as $operacion) {
                    $sheet->setCellValue('A' . $fila, $operacion->codigo_actividad);
                    $sheet->setCellValue('B' . $fila, $operacion->descripcion_actividad);

                    foreach ($operacion->memoria_operacion_detalles as $mod) {
                        $sheet->setCellValue('C' . $fila, $mod->m_partida->partida);
                        $sheet->setCellValue('D' . $fila, $mod->cantidad);
                        $sheet->setCellValue('E' . $fila, number_format($mod->costo, 2) . " ");
                        $sheet->setCellValue('F' . $fila, number_format($mod->total, 2) . " ");
                        $cantidad_usado = Certificacion::where('mo_id', $operacion->id)
                            ->where("anulado", 0)
                            ->where("mod_id", $mod->id)
                            ->sum('cantidad_usar');
                        $total_usado = Certificacion::where('mo_id', $operacion->id)
                            ->where("anulado", 0)
                            ->where("mod_id", $mod->id)
                            ->sum('presupuesto_usarse');
                        $saldo = (float) $mod->total - (float) $total_usado;
                        $sheet->setCellValue('G' . $fila, $cantidad_usado);
                        $sheet->setCellValue('H' . $fila, $total_usado);
                        $sheet->setCellValue('I' . $fila, number_format($saldo, 2) . " ");
                        $sheet->getStyle('A' . $fila . ':I' . $fila)->applyFromArray($estilo_conenido);
                        $fila++;
                        $suma_ejecutados += $total_usado;
                        $suma_saldos += $saldo;
                    }
                }

                $sheet->setCellValue('A' . $fila, 'TOTAL');
                $sheet->mergeCells("A" . $fila . ":E" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('F' . $fila, number_format($formulario_cuatro->memoria_calculo->total_final, 2) . " ");
                $sheet->setCellValue('H' . $fila, number_format($suma_ejecutados, 2) . " ");
                $sheet->setCellValue('I' . $fila, number_format($suma_saldos, 2) . " ");
                $sheet->getStyle('A' . $fila . ':I' . $fila)->applyFromArray($estilo_total);
            }

            $sheet->getColumnDimension('A')->setWidth(30);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(10);
            $sheet->getColumnDimension('I')->setWidth(10);
            $fila++;
            $fila++;
            $fila++;
        }
        $sheet->setCellValue('G' . $fila, self::getFechaTexto());
        $sheet->mergeCells("G" . $fila . ":I" . $fila);  //COMBINAR CELDAS

        foreach (range('A', 'I') as $columnID) {
            $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
        }

        // DESCARGA DEL ARCHIVO
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SaldoPresupuestario.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

        // $pdf = PDF::loadView('reportes.saldo_presupuesto', compact('formularios'))->setPaper('legal', 'landscape');
        // // ENUMERAR LAS PÁGINAS USANDO CANVAS
        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf->get_canvas();
        // $alto = $canvas->get_height();
        // $ancho = $canvas->get_width();
        // $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        // return $pdf->download('EjecucionPresupuestos.pdf');
    }

    public static function saldo_presupuesto_excel($formularios)
    {
    }

    public function ejecucion_presupuestos(Request $request)
    {
        $filtro =  $request->filtro;
        $unidad_id =  $request->unidad_id;
        $formulario_id =  $request->formulario_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;


        $formularios = [];
        $unidad = null;
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS") {
            $formularios = FormularioCuatro::where("unidad_id", Auth::user()->unidad_id)->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Código PEI":
                        $formularios = FormularioCuatro::where("id", $formulario_id)
                            ->where("unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $formularios = FormularioCuatro::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])
                            ->where("unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                }
            }
        } else {
            $formularios = FormularioCuatro::all();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Unidad Organizacional":
                        $formularios = FormularioCuatro::where("unidad_id", $unidad_id)->get();
                        $unidad = Unidad::find($unidad_id);
                        break;
                    case "Código PEI":
                        $formularios = FormularioCuatro::where("id", $formulario_id)->get();
                        break;
                    case "Rango de fechas":
                        $formularios = FormularioCuatro::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
                        break;
                }
            }
        }

        $pdf = PDF::loadView('reportes.ejecucion_presupuestos', compact('formularios', 'filtro', 'unidad'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('EjecucionPresupuestos.pdf');
    }

    public function formulario_cuatro(Request $request)
    {
        $filtro =  $request->filtro;
        $unidad_id =  $request->unidad_id;
        $formulario_id =  $request->formulario_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $formularios = [];
        $unidad = null;
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS") {
            $formularios = FormularioCuatro::where("unidad_id", Auth::user()->unidad_id)->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Código PEI":
                        $formularios = FormularioCuatro::where("id", $formulario_id)
                            ->where("unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $formularios = FormularioCuatro::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])
                            ->where("unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                }
            }
        } else {
            $formularios = FormularioCuatro::all();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Unidad Organizacional":
                        $formularios = FormularioCuatro::where("unidad_id", $unidad_id)->get();
                        $unidad = Unidad::find($unidad_id);
                        break;
                    case "Código PEI":
                        $formularios = FormularioCuatro::where("id", $formulario_id)->get();
                        break;
                    case "Rango de fechas":
                        $formularios = FormularioCuatro::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
                        break;
                }
            }
        }



        $pdf = PDF::loadView('reportes.formulario_cuatro', compact('formularios', 'filtro', 'unidad'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('formulario_cuatro.pdf');
    }
    public function formulario_cinco(Request $request)
    {
        $filtro =  $request->filtro;
        $unidad_id =  $request->unidad_id;
        $formulario_id =  $request->formulario_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $formularios = [];
        $unidad = null;
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS") {
            $formularios = FormularioCuatro::where("unidad_id", Auth::user()->unidad_id)->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Código PEI":
                        $formularios = FormularioCuatro::where("id", $formulario_id)
                            ->where("unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $formularios = FormularioCuatro::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])
                            ->where("unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                }
            }
        } else {
            $formularios = FormularioCuatro::all();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Unidad Organizacional":
                        $formularios = FormularioCuatro::where("unidad_id", $unidad_id)->get();
                        $unidad = Unidad::find($unidad_id);
                        break;
                    case "Código PEI":
                        $formularios = FormularioCuatro::where("id", $formulario_id)->get();
                        break;
                    case "Rango de fechas":
                        $formularios = FormularioCuatro::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
                        break;
                }
            }
        }


        $array_tablas = [];
        // foreach ($formularios as $formulario) {
        //     $formulario_cinco = $formulario->memoria_calculo->formulario_cinco;
        //     $array_registros = FormularioCincoController::armaRepetidos($formulario_cinco);
        //     $tabla = view('reportes.parcial.formulario_cinco', compact('array_registros', 'formulario_cinco'))->render();
        //     $array_tablas[$formulario->id] = $tabla;
        // }

        $pdf = PDF::loadView('reportes.formulario_cinco', compact('formularios', "array_tablas", "filtro", "unidad"))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('formulario_cinco.pdf');
    }
    public function memoria_calculos(Request $request)
    {
        $filtro =  $request->filtro;
        $unidad_id =  $request->unidad_id;
        $formulario_id =  $request->formulario_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $formularios = [];
        $unidad = null;
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS") {
            $formularios = FormularioCuatro::where("unidad_id", Auth::user()->unidad_id)->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Código PEI":
                        $formularios = FormularioCuatro::where("id", $formulario_id)
                            ->where("unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $formularios = FormularioCuatro::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])
                            ->where("unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                }
            }
        } else {
            $formularios = FormularioCuatro::all();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Unidad Organizacional":
                        $formularios = FormularioCuatro::where("unidad_id", $unidad_id)->get();
                        $unidad = Unidad::find($unidad_id);
                        break;
                    case "Código PEI":
                        $formularios = FormularioCuatro::where("id", $formulario_id)->get();
                        break;
                    case "Rango de fechas":
                        $formularios = FormularioCuatro::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
                        break;
                }
            }
        }


        $pdf = PDF::loadView('reportes.memoria_calculos', compact('formularios', "filtro", "unidad"))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('memoria_calculos.pdf');
    }
    public function saldos_actividad(Request $request)
    {
        $unidad_id =  $request->unidad_id;
        $formulario_id =  $request->formulario_id;
        $operacion_id =  $request->operacion_id;
        $actividad_id =  $request->actividad_id;
        $actividad = DetalleOperacion::find($actividad_id);
        $unidad = Unidad::find($unidad_id);

        $pdf = PDF::loadView('reportes.saldos_actividad', compact("actividad", "unidad"))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('saldos_actividad.pdf');
    }
    public function saldos_partida(Request $request)
    {
        $formulario_id =  $request->formulario_id;
        $partida_id =  $request->partida_id;

        $formulario = FormularioCuatro::find($formulario_id);
        $partida = Partida::find($partida_id);
        $memoria_operacion_detalles = null;
        if ($formulario->memoria_calculo) {
            $memoria_operacion_detalles = MemoriaOperacionDetalle::select("memoria_operacion_detalles.*")
                ->join("memoria_operacions", "memoria_operacions.id", "=", "memoria_operacion_detalles.memoria_operacion_id")
                ->where("memoria_operacions.memoria_id", $formulario->memoria_calculo->id)
                ->where("memoria_operacion_detalles.partida_id", $partida_id)
                ->get();
        }

        $unidad = $formulario->unidad;
        $pdf = PDF::loadView('reportes.saldos_partida', compact("memoria_operacion_detalles", "formulario", "partida", "unidad"))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('saldos_partida.pdf');
    }
    public function fisicos(Request $request)
    {
        $filtro =  $request->filtro;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $fisicos = Fisico::all();
        if ($filtro != "Todos") {
            $fisicos = Fisico::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
        }

        $pdf = PDF::loadView('reportes.fisicos', compact("fisicos"))->setPaper('letter', 'portrait');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('fisicos.pdf');
    }
    public function financieros(Request $request)
    {
        $filtro =  $request->filtro;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $financieros = Financiera::all();
        if ($filtro != "Todos") {
            $financieros = Financiera::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
        }

        $pdf = PDF::loadView('reportes.financieros', compact("financieros"))->setPaper('letter', 'portrait');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('financieros.pdf');
    }
    public function semaforos(Request $request)
    {
        $filtro =  $request->filtro;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $semaforos = Semaforo::all();
        if ($filtro != "Todos") {
            $semaforos = Semaforo::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
        }

        $pdf = PDF::loadView('reportes.semaforos', compact("semaforos"))->setPaper('letter', 'portrait');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('semaforos.pdf');
    }

    public function ejecucion_presupuestos_g(Request $request)
    {
        $filtro =  $request->filtro;
        if ($filtro == 'Rango de fechas') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
        }
        $unidad_id =  $request->unidad_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS") {
            $unidads = Unidad::where("id", Auth::user()->unidad_id)->get();
        } else {
            $unidads = Unidad::all();
            if ($filtro != "Todos") {
                if ($filtro == "Unidad Organizacional") {
                    $unidads = Unidad::where("id", $unidad_id)->get();
                }
            }
        }

        $categories = [];
        $presupuestos = [];
        $ejecutados = [];
        $saldos = [];


        foreach ($unidads as $unidad) {
            $categories[] = $unidad->nombre;
            if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS") {
                $formularios = FormularioCuatro::where("unidad_id", $unidad->id)->where("id", Auth::user()->unidad_id)->get();
            } else {
                $formularios = FormularioCuatro::where("unidad_id", $unidad->id)->get();
            }
            if ($filtro == "Rango de fechas") {
                if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS") {
                    $formularios = FormularioCuatro::where("unidad_id", $unidad->id)->whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->where("id", Auth::user()->unidad_id)->get();
                } else {
                    $formularios = FormularioCuatro::where("unidad_id", $unidad->id)->whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
                }
            }
            if (count($formularios) > 0) {
                // buscar los valores
                $suma_presupuestos = 0;
                $suma_ejecutados = 0;
                $suma_saldos = 0;
                foreach ($formularios as $formulario) {
                    if ($formulario->memoria_calculo) {
                        foreach ($formulario->memoria_calculo->operacions as $operacion) {
                            foreach ($operacion->memoria_operacion_detalles as $mod) {
                                $total_usado = Certificacion::where("mo_id", $operacion->id)
                                    ->where("anulado", 0)
                                    ->where("mod_id", $mod->id)
                                    ->sum("presupuesto_usarse");
                                $suma_ejecutados += (float)$total_usado;
                                $saldo = (float) $mod->total - (float) $total_usado;
                                $suma_saldos += (float)$saldo;
                            }
                        }
                        $suma_presupuestos += (float)$formulario->memoria_calculo->total_final;
                    }
                }
                $presupuestos[] =  (float)number_format($suma_presupuestos, 2, ".", "");
                $ejecutados[] =  (float)number_format($suma_ejecutados, 2, ".", "");
                $saldos[] =  (float)number_format($suma_saldos, 2, ".", "");
            } else {
                $presupuestos[] = 0;
                $ejecutados[] = 0;
                $saldos[] = 0;
            }
        }

        return response()->JSON([
            "categories" => $categories,
            "presupuestos" => $presupuestos,
            "ejecutados" => $ejecutados,
            "saldos" => $saldos,
        ]);
    }

    public function formulario_cuatro_excel(Request $request)
    {
        $formulario_cuatro = FormularioCuatro::find($request->id);
        if ($request->tipo == 'pdf') {
            $formulario = $formulario_cuatro;
            $pdf = PDF::loadView('reportes.formulario_cuatro_solo', compact('formulario'))->setPaper('legal', 'landscape');
            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

            return $pdf->download('formulario_cuatro_solo.pdf');
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("ADMIN")
            ->setLastModifiedBy('Administración')
            ->setTitle('Formularios')
            ->setSubject('Formularios')
            ->setDescription('Formularios')
            ->setKeywords('PHPSpreadsheet')
            ->setCategory('Listado');

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

        $styleTexto = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $styleTextoForm = [
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
        ];

        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 9,
                'color' => ['argb' => 'ffffff'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '0062A5']
            ],
        ];


        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 9,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '0062A5']
            ],
        ];

        $estilo_conenido = [
            'font' => [
                'size' => 8,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $estilo_conenido2 = [
            'font' => [
                'size' => 8,
                'bold' => true
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'EBF1DE']
            ],
        ];

        $estilo_conenido3 = [
            'font' => [
                'size' => 8,
                'bold' => true
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'EEECE1']
            ],
        ];

        $fila = 1;
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('logo');
        $drawing->setDescription('logo');
        $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo); // put your path and image here
        $drawing->setCoordinates('A' . $fila);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(0);
        $drawing->setHeight(60);
        $drawing->setWorksheet($sheet);

        $fila = 2;
        $sheet->setCellValue('A' . $fila, "MATRIZ - PROGRAMACIÓN OPERATIVA ANUAL - GESTIÓN " . date("Y"));
        $sheet->mergeCells("A" . $fila . ":U" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':U' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($styleTexto);
        $fila++;
        $sheet->setCellValue('A' . $fila, "ARTICULACIÓN DE ACCIONES Y OPERACIONES");
        $sheet->mergeCells("A" . $fila . ":U" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':U' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($styleTexto);
        $fila++;
        $sheet->setCellValue('L' . $fila, "FORMULARIO 4");
        $sheet->mergeCells("L" . $fila . ":M" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('L' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('L' . $fila . ':M' . $fila)->applyFromArray($styleTextoForm);

        $fila++;
        $sheet->setCellValue('A' . $fila, 'CÓDIGO PEI');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, str_replace(",", "\n", $formulario_cuatro->codigo_pei));
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'OBJETIVO ESTRATÉGICO INSTITUCIONAL');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, $formulario_cuatro->meta);
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'INDICADOR');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, $formulario_cuatro->indicador);
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'CODIGO POA');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, str_replace(",", "\n", $formulario_cuatro->codigo_poa));
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getRowDimension($fila)->setRowHeight(-1);
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'ACCIÓN DE CORTO PLAZO DE GESTIÓN');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, $formulario_cuatro->accion_corto);
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'RESULTADO ESPERADO GESTIÓN');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, $formulario_cuatro->resultado_institucional);
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'PRESUPUESTO PROGRAMADO GESTIÓN');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, number_format($formulario_cuatro->presupuesto, 2) . " ");
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'PONDERACIÓN %');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, number_format($formulario_cuatro->ponderacion, 2) . " ");
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'UNIDAD ORGANIZACIONAL');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, $formulario_cuatro->unidad->nombre);
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);

        $fila++;
        $fila++;
        $sheet->setCellValue('A' . $fila, 'Código Operación(1)');
        $sheet->mergeCells("A" . $fila . ":A" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('B' . $fila, 'Operación(2)');
        $sheet->mergeCells("B" . $fila . ":B" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, 'Ponderación');
        $sheet->mergeCells("C" . $fila . ":C" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('D' . $fila, 'Resultado intermedio esperado(3)');
        $sheet->mergeCells("D" . $fila . ":D" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, 'Medios de verificación(4)');
        $sheet->mergeCells("E" . $fila . ":E" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('F' . $fila, 'Código Act.(5)');
        $sheet->mergeCells("F" . $fila . ":F" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('G' . $fila, 'Actividad/Tarea(6)');
        $sheet->mergeCells("G" . $fila . ":G" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, 'Programación de ejecución de operaciones y actividades(7)');
        $sheet->mergeCells("H" . $fila . ":S" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('T' . $fila, 'Fecha prevista de ejecución(8)');
        $sheet->mergeCells("T" . $fila . ":U" . $fila);  //COMBINAR CELDAS
        $fila++;
        $sheet->setCellValue('H' . $fila, '1er Trim.');
        $sheet->mergeCells("H" . $fila . ":J" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('K' . $fila, '2do Trim.');
        $sheet->mergeCells("K" . $fila . ":M" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('N' . $fila, '3er Trim.');
        $sheet->mergeCells("N" . $fila . ":P" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('Q' . $fila, '4to Trim.');
        $sheet->mergeCells("Q" . $fila . ":S" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('T' . $fila, 'Inicio');
        $sheet->setCellValue('U' . $fila, 'Final');
        $fila++;
        $sheet->setCellValue('H' . $fila, 'E');
        $sheet->setCellValue('I' . $fila, 'F');
        $sheet->setCellValue('J' . $fila, 'M');
        $sheet->setCellValue('K' . $fila, 'A');
        $sheet->setCellValue('L' . $fila, 'M');
        $sheet->setCellValue('M' . $fila, 'J');
        $sheet->setCellValue('N' . $fila, 'J');
        $sheet->setCellValue('O' . $fila, 'A');
        $sheet->setCellValue('P' . $fila, 'S');
        $sheet->setCellValue('Q' . $fila, 'O');
        $sheet->setCellValue('R' . $fila, 'N');
        $sheet->setCellValue('S' . $fila, 'D');

        $sheet->getStyle('A' . ($fila - 2) . ':U' . ($fila - 2))->applyFromArray($styleArray2);
        $sheet->getStyle('A' . ($fila - 1) . ':U' . ($fila - 1))->applyFromArray($styleArray2);
        $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($styleArray2);
        $fila++;
        if ($formulario_cuatro->detalle_formulario) {
            foreach ($formulario_cuatro->detalle_formulario->operacions as $operacion) {
                $sheet->setCellValue('A' . $fila, $operacion->codigo_operacion);
                $sheet->setCellValue('B' . $fila, $operacion->operacion);
                $contador = $fila;
                foreach ($operacion->detalle_operaciones as $detalle_operacion) {
                    $sheet->setCellValue('C' . $contador, $detalle_operacion->ponderacion);
                    $sheet->setCellValue('D' . $contador, $detalle_operacion->resultado_esperado);
                    $sheet->setCellValue('E' . $contador, $detalle_operacion->medios_verificacion);
                    $sheet->setCellValue('F' . $contador, $detalle_operacion->codigo_tarea);
                    $sheet->setCellValue('G' . $contador, $detalle_operacion->actividad_tarea);
                    $sheet->setCellValue('H' . $contador, $detalle_operacion->pt_e);
                    if ($detalle_operacion->pt_e) {
                        $sheet->getStyle("H" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('I' . $contador, $detalle_operacion->pt_f);
                    if ($detalle_operacion->pt_f) {
                        $sheet->getStyle("I" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('J' . $contador, $detalle_operacion->pt_m);
                    if ($detalle_operacion->pt_m) {
                        $sheet->getStyle("J" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('K' . $contador, $detalle_operacion->st_a);
                    if ($detalle_operacion->st_a) {
                        $sheet->getStyle("K" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('L' . $contador, $detalle_operacion->st_m);
                    if ($detalle_operacion->st_m) {
                        $sheet->getStyle("L" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('M' . $contador, $detalle_operacion->st_j);
                    if ($detalle_operacion->st_j) {
                        $sheet->getStyle("M" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('N' . $contador, $detalle_operacion->tt_j);
                    if ($detalle_operacion->tt_j) {
                        $sheet->getStyle("N" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('O' . $contador, $detalle_operacion->tt_a);
                    if ($detalle_operacion->tt_a) {
                        $sheet->getStyle("O" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('P' . $contador, $detalle_operacion->tt_s);
                    if ($detalle_operacion->tt_s) {
                        $sheet->getStyle("P" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('Q' . $contador, $detalle_operacion->ct_o);
                    if ($detalle_operacion->ct_o) {
                        $sheet->getStyle("Q" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('R' . $contador, $detalle_operacion->ct_n);
                    if ($detalle_operacion->ct_n) {
                        $sheet->getStyle("R" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('S' . $contador, $detalle_operacion->ct_d);
                    if ($detalle_operacion->ct_d) {
                        $sheet->getStyle("S" . $contador)->applyFromArray($estilo_conenido3);
                    }
                    $sheet->setCellValue('T' . $contador, date("d/m/Y", strtotime($detalle_operacion->inicio)));
                    $sheet->setCellValue('U' . $contador, date("d/m/Y", strtotime($detalle_operacion->final)));
                    $sheet->getStyle('A' . $contador . ':U' . $contador)->applyFromArray($estilo_conenido);
                    $contador++;
                }
                $sheet->mergeCells("A" . $fila . ":A" . ($contador - 1));  //COMBINAR CELDAS
                $sheet->mergeCells("B" . $fila . ":B" . ($contador - 1));  //COMBINAR CELDAS
                $fila = $contador - 1;
                $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido);
                $fila++;
            }
        }
        $fila++;
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'DAEEF3']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArray3 = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];


        $sheet->setCellValue('A' . $fila, "");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "ELABORADO POR:");
        $sheet->mergeCells("C" . $fila . ":E" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('C' . $fila . ':E' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('F' . $fila, "REVISADO POR:");
        $sheet->mergeCells("F" . $fila . ":H" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('I' . $fila, "APROBADO");
        $sheet->mergeCells("I" . $fila . ":U" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($styleArray2);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "FIRMA");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "");
        $sheet->mergeCells("C" . $fila . ":E" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('C' . $fila . ':E' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('F' . $fila, "");
        $sheet->mergeCells("F" . $fila . ":H" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('I' . $fila, "");
        $sheet->mergeCells("I" . $fila . ":U" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "NOMBRE");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "");
        $sheet->mergeCells("C" . $fila . ":E" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('F' . $fila, "");
        $sheet->mergeCells("F" . $fila . ":H" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('I' . $fila, "");
        $sheet->mergeCells("I" . $fila . ":U" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "CARGO");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "");
        $sheet->getStyle('C' . $fila . ':E' . $fila)->applyFromArray($styleArray3);
        $sheet->mergeCells("C" . $fila . ":E" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('F' . $fila, "");
        $sheet->mergeCells("F" . $fila . ":H" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('I' . $fila, "");
        $sheet->mergeCells("I" . $fila . ":U" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($styleArray);
        $sheet->getStyle('H' . $fila . ':U' . $fila)->applyFromArray($styleArray3);
        $fila++;
        $fila++;
        $sheet->setCellValue('S' . $fila, self::getFechaTexto());
        $sheet->mergeCells("S" . $fila . ":U" . $fila);  //COMBINAR CELDAS


        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(2);
        $sheet->getColumnDimension('I')->setWidth(2);
        $sheet->getColumnDimension('J')->setWidth(2);
        $sheet->getColumnDimension('K')->setWidth(2);
        $sheet->getColumnDimension('L')->setWidth(2);
        $sheet->getColumnDimension('M')->setWidth(2);
        $sheet->getColumnDimension('N')->setWidth(2);
        $sheet->getColumnDimension('O')->setWidth(2);
        $sheet->getColumnDimension('P')->setWidth(2);
        $sheet->getColumnDimension('Q')->setWidth(2);
        $sheet->getColumnDimension('R')->setWidth(2);
        $sheet->getColumnDimension('S')->setWidth(2);
        $sheet->getColumnDimension('T')->setWidth(15);
        $sheet->getColumnDimension('U')->setWidth(15);

        foreach (range('A', 'U') as $columnID) {
            $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
        }

        // DESCARGA DEL ARCHIVO
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Usuarios.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function formulario_cinco_excel(Request $request)
    {
        $formulario_cinco = FormularioCinco::find($request->id);

        if ($request->tipo == 'pdf') {
            $formulario = $formulario_cinco->memoria->formulario;

            $pdf = PDF::loadView('reportes.formulario_cinco_solo', compact('formulario'))->setPaper('legal', 'landscape');
            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

            return $pdf->download('formulario_cinco.pdf');
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("ADMIN")
            ->setLastModifiedBy('Administración')
            ->setTitle('Formularios')
            ->setSubject('Formularios')
            ->setDescription('Formularios')
            ->setKeywords('PHPSpreadsheet')
            ->setCategory('Listado');

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

        $styleTexto = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $styleTextoForm = [
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
        ];

        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 9,
                'color' => ['argb' => 'ffffff'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '0062A5']
            ],
        ];


        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 9,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '0062A5']
            ],
        ];

        $estilo_conenido = [
            'font' => [
                'size' => 8,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $estilo_conenido_rojo = [
            'font' => [
                'size' => 8,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'DC3545']
            ],
        ];

        $estilo_conenido2 = [
            'font' => [
                'size' => 8,
                'bold' => true
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'EBF1DE']
            ],
        ];

        $estilo_total = [
            'font' => [
                'size' => 11,
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'A6A6A6']
            ],
        ];


        $fila = 1;
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('logo');
        $drawing->setDescription('logo');
        $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo); // put your path and image here
        $drawing->setCoordinates('A' . $fila);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(0);
        $drawing->setHeight(60);
        $drawing->setWorksheet($sheet);

        $fila = 2;
        $sheet->setCellValue('A' . $fila, "MATRIZ - PROGRAMACIÓN OPERATIVA ANUAL - GESTIÓN " . date("Y"));
        $sheet->mergeCells("A" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleTexto);
        $fila++;
        $sheet->setCellValue('A' . $fila, "ARTICULACIÓN OPERACIONES, TAREAS Y PRESUPUESTO");
        $sheet->mergeCells("A" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleTexto);
        $fila++;
        $sheet->setCellValue('L' . $fila, "FORMULARIO 5");
        $sheet->mergeCells("L" . $fila . ":M" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('L' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('L' . $fila . ':M' . $fila)->applyFromArray($styleTextoForm);
        $fila++;

        $sheet->setCellValue('A' . $fila, 'CÓDIGO PEI');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->codigo_pei);
        $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
        $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido2);

        $fila++;
        $sheet->setCellValue('A' . $fila, 'OBJETIVO ESTRATÉGICO INSTITUCIONAL');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->accion_institucional);
        $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
        $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'INDICADOR');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->indicador);
        $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
        $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'CODIGO POA');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->codigo_poa);
        $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
        $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'ACCIÓN DE CORTO PLAZO DE GESTIÓN');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->accion_corto);
        $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
        $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'RESULTADO ESPERADO GESTIÓN');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->resultado_esperado);
        $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
        $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'PRESUPUESTO PROGRAMADO GESTIÓN');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('C' . $fila, number_format($formulario_cinco->memoria->formulario->presupuesto, 2) . " ");
        $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
        $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'PONDERACIÓN %');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('C' . $fila, number_format($formulario_cinco->memoria->formulario->ponderacion, 2) . " ");
        $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
        $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'UNIDAD ORGANIZACIONAL');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->unidad->nombre);
        $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
        $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido2);

        $fila++;
        $fila++;
        $sheet->setCellValue('A' . $fila, "PLAN OPERATIVO ANUAL GESTIÓN " . date("Y"));
        $sheet->mergeCells("A" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'Código Operación(1)');
        $sheet->mergeCells("A" . $fila . ":A" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('B' . $fila, 'Operación(2)');
        $sheet->mergeCells("B" . $fila . ":B" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, 'Código tarea(3)');
        $sheet->mergeCells("C" . $fila . ":C" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('D' . $fila, 'Actividad/Tareas(4)');
        $sheet->mergeCells("D" . $fila . ":D" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, 'Lugar de ejecución de la Operación(5)');
        $sheet->mergeCells("E" . $fila . ":E" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('F' . $fila, 'Responsable de ejecución de la Operación/Tarea(6)');
        $sheet->mergeCells("F" . $fila . ":F" . ($fila + 2));  //COMBINAR CELDAS
        $sheet->setCellValue('G' . $fila, 'Desglose Presupuestario');
        $sheet->mergeCells("G" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $fila++;
        $sheet->setCellValue('G' . $fila, 'Partida(7)');
        $sheet->mergeCells("G" . $fila . ":G" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, 'Descripción(8)');
        $sheet->mergeCells("H" . $fila . ":H" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('I' . $fila, 'Cantidad(9)');
        $sheet->mergeCells("I" . $fila . ":I" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('J' . $fila, 'Unidad de medida(10)');
        $sheet->mergeCells("J" . $fila . ":J" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('K' . $fila, 'Costo Unitario(11)');
        $sheet->mergeCells("K" . $fila . ":K" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('L' . $fila, 'Recursos Internos(12)');
        $sheet->mergeCells("L" . $fila . ":O" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('P' . $fila, 'Recursos externo(13)');
        $sheet->setCellValue('Q' . $fila, 'TOTAL (por Operación)(14)');
        $sheet->mergeCells("Q" . $fila . ":Q" . ($fila + 1));  //COMBINAR CELDAS
        $fila++;
        $sheet->setCellValue('L' . $fila, 'PRESUPUESTO VIGENTE');
        $sheet->setCellValue('M' . $fila, 'UE');
        $sheet->setCellValue('N' . $fila, 'PROG');
        $sheet->setCellValue('O' . $fila, 'ACT');
        $sheet->setCellValue('P' . $fila, 'OTROS');

        $sheet->getStyle('A' . ($fila - 2) . ':Q' . ($fila - 2))->applyFromArray($styleArray2);
        $sheet->getStyle('A' . ($fila - 1) . ':Q' . ($fila - 1))->applyFromArray($styleArray2);
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray2);
        $fila++;

        // armar repetidos
        $array_registros = FormularioCincoController::armaRepetidos($formulario_cinco);

        if (count($array_registros) > 0) {

            foreach ($array_registros as $ar) {
                $fila_rowspan = $fila;

                foreach ($ar["registros"] as $operacion) {
                    if ($operacion['subdireccion']) {
                        $sheet->setCellValue('A' . $fila, $operacion['subdireccion']->nombre);
                        $sheet->mergeCells("A" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
                        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray2);
                        $fila++;
                        $fila_rowspan = $fila;
                    }

                    if ($sheet->getCell('A' . $fila_rowspan)->getValue() == "") {
                        $sheet->setCellValue('A' . $fila_rowspan, $ar["codigo_operacion"]);
                        $sheet->setCellValue('B' . $fila_rowspan, $ar["operacion"]);

                        $sheet->mergeCells("A" . $fila . ":A" . ($fila + $ar["rowspan"] - 1));  //COMBINAR CELDAS
                        $sheet->mergeCells("B" . $fila . ":B" . ($fila + $ar["rowspan"] - 1));  //COMBINAR CELDAS
                    }


                    $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido);
                    $fila_actividad = $fila;
                    foreach ($operacion["lugares"] as $lugar) {
                        if ($sheet->getCell('C' . $fila_actividad)->getValue() == "") {
                            $sheet->setCellValue('C' . $fila_actividad, $operacion["codigo_tarea"]);
                            $sheet->mergeCells("C" . $fila_actividad . ":C" . ($fila_actividad + $operacion["rowspan"] - 1));  //COMBINAR CELDAS
                        }
                        if ($sheet->getCell('D' . $fila_actividad)->getValue() == "") {
                            $sheet->setCellValue('D' . $fila_actividad, $operacion["tarea"]);
                            $sheet->mergeCells("D" . $fila_actividad . ":D" . ($fila_actividad + $operacion["rowspan"] - 1));  //COMBINAR CELDAS
                        }

                        $sheet->setCellValue('E' . $fila, $lugar["lugar"]);
                        $sheet->mergeCells("E" . $fila . ":E" . ($fila + $lugar["rowspan"] - 1));  //COMBINAR CELDAS

                        foreach ($lugar["responsables"] as $responsable) {
                            $sheet->setCellValue('F' . $fila, $responsable["responsable"]);
                            $sheet->mergeCells("F" . $fila . ":F" . ($fila + $responsable["rowspan"] - 1));  //COMBINAR CELDAS

                            foreach ($responsable["registros"] as $registro) {
                                $sheet->setCellValue('G' . $fila, $registro["partida"]);
                                $sheet->setCellValue('H' . $fila, $registro["descripcion"]);
                                $sheet->setCellValue('I' . $fila, $registro["cantidad"]);
                                $sheet->setCellValue('J' . $fila, $registro["unidad"]);
                                $sheet->setCellValue('K' . $fila, number_format($registro["costo"], 2) . " ");
                                $sheet->setCellValue('L' . $fila, number_format($registro["total"], 2) . " ");
                                $sheet->setCellValue('M' . $fila, $registro["ue"]);
                                $sheet->setCellValue('N' . $fila, $registro["prog"]);
                                $sheet->setCellValue('O' . $fila, $registro["act"]);
                                // $sheet->setCellValue('P' . $fila, $registro["justificacion"]);
                                $sheet->setCellValue('Q' . $fila, number_format($registro["total_actividad"], 2) . " ");
                                $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($estilo_conenido);
                                if ($registro["saldo"] == 0) {
                                    $sheet->getStyle('Q' . $fila)->applyFromArray($estilo_conenido_rojo);
                                }

                                $fila++;
                            }
                        }
                    }
                }
            }
        }
        $sheet->setCellValue('A' . $fila, 'TOTAL PRESUPUESTO DE LA UNIDAD DE PLANIFICACIÓN');
        $sheet->mergeCells("A" . $fila . ":P" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('Q' . $fila, number_format($formulario_cinco->memoria->total_final, 2));
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($estilo_total);

        $fila++;
        $fila++;

        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'DAEEF3']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArray3 = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];


        $sheet->setCellValue('A' . $fila, "");
        $sheet->setCellValue('B' . $fila, "ELABORADO POR:");
        $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('H' . $fila, "REVISADO POR:");
        $sheet->mergeCells("H" . $fila . ":L" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, "APROBADO");
        $sheet->mergeCells("M" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray2);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "FIRMA");
        $sheet->setCellValue('B' . $fila, "");
        $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("H" . $fila . ":L" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, "");
        $sheet->mergeCells("M" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "NOMBRE");
        $sheet->setCellValue('B' . $fila, "");
        $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("H" . $fila . ":L" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, "");
        $sheet->mergeCells("M" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "CARGO");
        $sheet->setCellValue('B' . $fila, "");
        $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($styleArray3);
        $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("H" . $fila . ":L" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, "");
        $sheet->mergeCells("M" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray);
        $sheet->getStyle('H' . $fila . ':Q' . $fila)->applyFromArray($styleArray3);
        $fila++;
        $fila++;
        $sheet->setCellValue('O' . $fila, self::getFechaTexto());
        $sheet->mergeCells("O" . $fila . ":Q" . $fila);  //COMBINAR CELDAS

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(6);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(5);
        $sheet->getColumnDimension('J')->setWidth(10);
        $sheet->getColumnDimension('K')->setWidth(10);
        $sheet->getColumnDimension('L')->setWidth(10);
        $sheet->getColumnDimension('M')->setWidth(5);
        $sheet->getColumnDimension('N')->setWidth(5);
        $sheet->getColumnDimension('O')->setWidth(5);
        $sheet->getColumnDimension('P')->setWidth(10);
        $sheet->getColumnDimension('Q')->setWidth(10);

        foreach (range('A', 'Q') as $columnID) {
            $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
        }

        // DESCARGA DEL ARCHIVO
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="FormularioCinco.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function memoria_calculo_excel(Request $request)
    {
        $memoria_calculo = MemoriaCalculo::find($request->id);
        if ($request->tipo == 'pdf') {
            $formulario = $memoria_calculo->formulario;
            $pdf = PDF::loadView('reportes.memoria_calculo_solo', compact('formulario', 'memoria_calculo'))->setPaper('legal', 'landscape');
            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

            return $pdf->download('memoria_calculo_solo.pdf');
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("ADMIN")
            ->setLastModifiedBy('Administración')
            ->setTitle('Formularios')
            ->setSubject('Formularios')
            ->setDescription('Formularios')
            ->setKeywords('PHPSpreadsheet')
            ->setCategory('Listado');

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

        $styleTexto = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $estilo1 = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'DCE6F1']
            ],
        ];
        $estilo2 = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'DCE6F1']
            ],
        ];

        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 9,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '0062A5']
            ],
        ];

        $estilo_conenido = [
            'font' => [
                'size' => 8,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $estilo_conenido_rojo = [
            'font' => [
                'size' => 8,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'DC3545']
            ],
        ];

        $estilo_conenido1 = [
            'font' => [
                'size' => 8,
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $estilo_conenido2 = [
            'font' => [
                'size' => 8,
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $estilo_total = [
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '0062A5']
            ],
        ];

        $fila = 1;
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('logo');
        $drawing->setDescription('logo');
        $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo); // put your path and image here
        $drawing->setCoordinates('A' . $fila);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(0);
        $drawing->setHeight(60);
        $drawing->setWorksheet($sheet);
        $sheet->setCellValue('A' . $fila, "Formulario 9");
        $sheet->mergeCells("A" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->getAlignment()->setHorizontal('center');
        $fila++;
        $sheet->setCellValue('A' . $fila, "Memorias de Cálculo");
        $sheet->mergeCells("A" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->getAlignment()->setHorizontal('center');
        $fila++;
        $sheet->setCellValue('A' . $fila, "MEMORIAS DE CÁLCULO POR CÓDIGO POA Y PARTIDA DE GASTO - GESTIÓN " . date("Y"));
        $sheet->mergeCells("A" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleTexto);
        $fila++;
        $sheet->setCellValue('A' . $fila, "(Expresado en Bolivianos)");
        $sheet->mergeCells("A" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleTexto);
        $fila++;

        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "CODIGO");
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "DESCRIPCIÓN");
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo2);

        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "ENTIDAD:");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "385");
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO - ASSUS");
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo2);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "UNIDAD ORGANIZACIONAL:");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, $memoria_calculo->formulario->unidad->nombre);
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo2);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "DIRECCIÓN ADMINISTRATIVA:");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "1");
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "DIRECCIÓN ADMINISTRATIVA FINANCIERA");
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo2);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "FUENTE:");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "42");
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "TRANSFERENCIAS DE RECURSOS ESPECÍFICOS");
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo2);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "ORGANISMO FINANCIADOR:");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "230");
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "OTROS RECURSOS ESPECÍFICOS");
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo2);

        $fila++;
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, 'CODIGO POA:');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, str_replace(" | ", "\n", $memoria_calculo->formulario->codigo_poa_full));
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, $memoria_calculo->formulario->accion_corto);
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo_conenido1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo_conenido1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo_conenido2);
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, 'UNIDAD EJECUTORA:');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "1");
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "ADMINISTRACIÓN CENTRAL");
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo_conenido1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo_conenido1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo_conenido2);
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, 'PROGRAMA:');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "1");
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "ADMINISTRACIÓN Y GESTIÓN INSTITUCIONAL");
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo_conenido1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo_conenido1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo_conenido2);
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, 'ACTIVIDAD:');
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "1");
        $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "GESTIÓN EJECUTIVA");
        $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($estilo_conenido1);
        $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($estilo_conenido1);
        $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($estilo_conenido2);
        $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $fila++;

        $fila++;
        $sheet->setCellValue('A' . $fila, 'UNIDAD EJECUTORA');
        $sheet->mergeCells("A" . $fila . ":A" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('B' . $fila, 'PRESUPUESTOS');
        $sheet->mergeCells("B" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('D' . $fila, 'POA');
        $sheet->mergeCells("D" . $fila . ":E" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('F' . $fila, 'PARTIDA DE GASTO');
        $sheet->mergeCells("F" . $fila . ":F" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('G' . $fila, 'DESCRIPCIÓN');
        $sheet->mergeCells("G" . $fila . ":G" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, 'N°');
        $sheet->mergeCells("H" . $fila . ":H" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('I' . $fila, 'DESCRIPCIÓN DETALLADA POR ITEM(BIEN O SERVICIO)');
        $sheet->mergeCells("I" . $fila . ":I" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('J' . $fila, 'CANTIDAD REQUERIDA');
        $sheet->mergeCells("J" . $fila . ":J" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('K' . $fila, 'UNIDAD');
        $sheet->mergeCells("K" . $fila . ":K" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('L' . $fila, 'PRECIO UNITARIO');
        $sheet->mergeCells("L" . $fila . ":L" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, 'TOTAL');
        $sheet->mergeCells("M" . $fila . ":M" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('N' . $fila, 'JUSTIFICACIÓN');
        $sheet->mergeCells("N" . $fila . ":N" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->setCellValue('O' . $fila, 'PROGRAMACIÓN MENSUAL');
        $sheet->mergeCells("O" . $fila . ":Z" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('AA' . $fila, 'TOTAL');
        $sheet->mergeCells("AA" . $fila . ":AA" . ($fila + 1));  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray2);
        $fila++;
        $sheet->setCellValue('B' . $fila, 'PROGRAMA');
        $sheet->setCellValue('C' . $fila, 'ACTIVIDAD');
        $sheet->setCellValue('D' . $fila, 'COD. OPERACIÓN');
        $sheet->setCellValue('E' . $fila, 'COD. ACT./TAREA');
        $sheet->setCellValue('O' . $fila, 'ENERO');
        $sheet->setCellValue('P' . $fila, 'FEBRERO');
        $sheet->setCellValue('Q' . $fila, 'MARZO');
        $sheet->setCellValue('R' . $fila, 'ABRIL');
        $sheet->setCellValue('S' . $fila, 'MAYO');
        $sheet->setCellValue('T' . $fila, 'JUNIO');
        $sheet->setCellValue('U' . $fila, 'JULIO');
        $sheet->setCellValue('V' . $fila, 'AGOSTO');
        $sheet->setCellValue('W' . $fila, 'SEPTIEMBRE');
        $sheet->setCellValue('X' . $fila, 'OCTUBRE');
        $sheet->setCellValue('Y' . $fila, 'NOVIEMBRE');
        $sheet->setCellValue('Z' . $fila, 'DICIEMBRE');
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray2);
        $fila++;
        foreach ($memoria_calculo->operacions as $operacion) {
            if ($operacion->operacion->subdireccion) {
                $sheet->setCellValue('A' . $fila, $operacion->operacion->subdireccion->nombre);
                $sheet->mergeCells("A" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray2);
                $fila++;
            }

            $sheet->setCellValue('D' . $fila, $operacion->codigo_operacion);
            $sheet->setCellValue('E' . $fila, $operacion->codigo_actividad);
            $sheet->mergeCells("D" . $fila . ":D" . ($fila + count($operacion->memoria_operacion_detalles) - 1));  //COMBINAR CELDAS
            $sheet->mergeCells("E" . $fila . ":E" . ($fila + count($operacion->memoria_operacion_detalles) - 1));  //COMBINAR CELDAS

            foreach ($operacion->memoria_operacion_detalles as $mod) {
                $sheet->setCellValue('A' . $fila, $mod->ue);
                $sheet->setCellValue('B' . $fila, $mod->prog);
                $sheet->setCellValue('C' . $fila, $mod->act);
                $sheet->setCellValue('F' . $fila, $mod->partida);
                $sheet->setCellValue('G' . $fila, $mod->descripcion);
                $sheet->setCellValue('H' . $fila, $mod->nro);
                $sheet->setCellValue('I' . $fila, $mod->descripcion_detallada);
                $sheet->setCellValue('J' . $fila, $mod->cantidad);
                $sheet->setCellValue('K' . $fila, $mod->unidad);
                $sheet->setCellValue('L' . $fila, number_format($mod->costo, 2) . " ");
                $sheet->setCellValue('M' . $fila, number_format($mod->total, 2) . " ");
                $sheet->setCellValue('N' . $fila, $mod->justificacion);
                $sheet->setCellValue('O' . $fila, number_format($mod->ene, 2) . " ");
                $sheet->setCellValue('P' . $fila, number_format($mod->feb, 2) . " ");
                $sheet->setCellValue('Q' . $fila, number_format($mod->mar, 2) . " ");
                $sheet->setCellValue('R' . $fila, number_format($mod->abr, 2) . " ");
                $sheet->setCellValue('S' . $fila, number_format($mod->may, 2) . " ");
                $sheet->setCellValue('T' . $fila, number_format($mod->jun, 2) . " ");
                $sheet->setCellValue('U' . $fila, number_format($mod->jul, 2) . " ");
                $sheet->setCellValue('V' . $fila, number_format($mod->ago, 2) . " ");
                $sheet->setCellValue('W' . $fila, number_format($mod->sep, 2) . " ");
                $sheet->setCellValue('X' . $fila, number_format($mod->oct, 2) . " ");
                $sheet->setCellValue('Y' . $fila, number_format($mod->nov, 2) . " ");
                $sheet->setCellValue('Z' . $fila, number_format($mod->dic, 2) . " ");
                $sheet->setCellValue('AA' . $fila, number_format($mod->total_actividad, 2) . " ");
                $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($estilo_conenido);
                if ($mod->saldo == 0) {
                    $sheet->getStyle('AA' . $fila)->applyFromArray($estilo_conenido_rojo);
                }
                $fila++;
            }
        }
        $sheet->setCellValue('A' . $fila, 'TOTAL');
        $sheet->mergeCells("A" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, number_format($memoria_calculo->total_actividades, 2) . " ");
        $sheet->setCellValue('O' . $fila, number_format($memoria_calculo->total_ene, 2) . " ");
        $sheet->setCellValue('P' . $fila, number_format($memoria_calculo->total_feb, 2) . " ");
        $sheet->setCellValue('Q' . $fila, number_format($memoria_calculo->total_mar, 2) . " ");
        $sheet->setCellValue('R' . $fila, number_format($memoria_calculo->total_abr, 2) . " ");
        $sheet->setCellValue('S' . $fila, number_format($memoria_calculo->total_may, 2) . " ");
        $sheet->setCellValue('T' . $fila, number_format($memoria_calculo->total_jun, 2) . " ");
        $sheet->setCellValue('U' . $fila, number_format($memoria_calculo->total_jul, 2) . " ");
        $sheet->setCellValue('V' . $fila, number_format($memoria_calculo->total_ago, 2) . " ");
        $sheet->setCellValue('W' . $fila, number_format($memoria_calculo->total_sep, 2) . " ");
        $sheet->setCellValue('X' . $fila, number_format($memoria_calculo->total_oct, 2) . " ");
        $sheet->setCellValue('Y' . $fila, number_format($memoria_calculo->total_nov, 2) . " ");
        $sheet->setCellValue('Z' . $fila, number_format($memoria_calculo->total_dic, 2) . " ");
        $sheet->setCellValue('AA' . $fila, number_format($memoria_calculo->total_final, 2) . " ");
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($estilo_total);

        $fila++;
        $fila++;

        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'DAEEF3']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArray3 = [
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->setCellValue('A' . $fila, "");
        $sheet->setCellValue('B' . $fila, "ELABORADO POR:");
        $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('J' . $fila, "REVISADO POR:");
        $sheet->mergeCells("J" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('R' . $fila, "APROBADO");
        $sheet->mergeCells("R" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray2);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "FIRMA");
        $sheet->setCellValue('B' . $fila, "");
        $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('E' . $fila, "");
        $sheet->mergeCells("J" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("R" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "NOMBRE");
        $sheet->setCellValue('B' . $fila, "");
        $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "");
        $sheet->mergeCells("J" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("R" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(40);
        $sheet->setCellValue('A' . $fila, "CARGO");
        $sheet->setCellValue('B' . $fila, "Jefe de Unidad de Planificación");
        $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($styleArray3);
        $sheet->mergeCells("B" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('B' . $fila . ':I' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('E' . $fila, "");
        $sheet->mergeCells("J" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('R' . $fila, "Director General Ejecutivo");
        $sheet->mergeCells("R" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray);
        $sheet->getStyle('R' . $fila . ':AA' . $fila)->applyFromArray($styleArray3);
        $fila++;
        $fila++;
        $sheet->setCellValue('X' . $fila, self::getFechaTexto());
        $sheet->mergeCells("X" . $fila . ":AA" . $fila);  //COMBINAR CELDAS

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(5);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(5);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(4);
        $sheet->getColumnDimension('K')->setWidth(10);
        $sheet->getColumnDimension('L')->setWidth(9);
        $sheet->getColumnDimension('M')->setWidth(10);
        $sheet->getColumnDimension('N')->setWidth(10);
        $sheet->getColumnDimension('O')->setWidth(6);
        $sheet->getColumnDimension('P')->setWidth(6);
        $sheet->getColumnDimension('Q')->setWidth(6);
        $sheet->getColumnDimension('R')->setWidth(6);
        $sheet->getColumnDimension('S')->setWidth(6);
        $sheet->getColumnDimension('T')->setWidth(6);
        $sheet->getColumnDimension('U')->setWidth(6);
        $sheet->getColumnDimension('V')->setWidth(6);
        $sheet->getColumnDimension('W')->setWidth(6);
        $sheet->getColumnDimension('X')->setWidth(6);
        $sheet->getColumnDimension('Y')->setWidth(6);
        $sheet->getColumnDimension('Z')->setWidth(6);
        $sheet->getColumnDimension('AA')->setWidth(10);

        foreach (range('A', 'Z') as $columnID) {
            $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
        }

        // DESCARGA DEL ARCHIVO
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MemoriaCalculo.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
