<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\CertificacionDetalle;
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
use App\Models\VerificacionActividad;
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

        $memoria_calulos = MemoriaCalculo::all();
        if ($filtro != "Todos") {
            switch ($filtro) {
                case "Unidad Organizacional":
                    $memoria_calulos = MemoriaCalculo::select("memoria_calculos.*")
                        ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                        ->where("formulario_cuatro.unidad_id", $unidad_id)
                        ->get();
                    break;
                case "Código PEI":
                    $memoria_calulos = MemoriaCalculo::where("formulario_seleccionado", $formulario_id)->get();
                    break;
                case "Rango de fechas":
                    $memoria_calulos = MemoriaCalculo::whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
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
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
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
                'color' => ['rgb' => '203764']
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
                'color' => ['rgb' => '203764']
            ],
        ];

        $estilo_conenido = [
            'font' => [
                'size' => 10,
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
                'color' => ['rgb' => '203764']
            ],
        ];


        $fila = 1;
        if (file_exists(public_path() . '/imgs/' . Configuracion::first()->logo2)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('logo2');
            $drawing->setDescription('logo2');
            $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo2); // put your path and image here
            $drawing->setCoordinates('A' . $fila);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(0);
            $drawing->setHeight(50);
            $drawing->setWorksheet($sheet);
        }
        if (file_exists(public_path() . '/imgs/' . Configuracion::first()->logo)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('logo');
            $drawing->setDescription('logo');
            $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo); // put your path and image here
            $drawing->setCoordinates('G' . $fila);
            $drawing->setOffsetX(0);
            $drawing->setOffsetY(0);
            $drawing->setHeight(50);
            $drawing->setWorksheet($sheet);
        }

        $fila = 2;

        foreach ($memoria_calulos as $memoria_calculo) {
            $sheet->setCellValue('A' . $fila, "SALDO PRESUPUESTARIO - GESTIÓN " . date("Y"));
            $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($styleTexto);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'CÓDIGO PEI');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, str_replace("|", "\n", $memoria_calculo->pei_text));
            // $enters = substr_count($memoria_calculo->formulario_cuatro->codigo_pei, "|");
            // $alto_fila = 25;
            // if ($enters > 1) {
            //     $alto_fila = $enters * 26;
            // }
            // $sheet->getRowDimension($fila)->setRowHeight($alto_fila);
            $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'OBJETIVO ESTRATÉGICO INSTITUCIONAL');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $memoria_calculo->formulario_cuatro ? $memoria_calculo->formulario_cuatro->accion_institucional : "");
            $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'INDICADOR');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $memoria_calculo->formulario_cuatro ? $memoria_calculo->formulario_cuatro->indicador : "");
            $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'CODIGO POA');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, str_replace("|", "\n", $memoria_calculo->poa_text));
            // $enters = substr_count($memoria_calculo->formulario_cuatro->codigo_poa, "|");
            // $alto_fila = 25;
            // if ($enters > 1) {
            //     $alto_fila = $enters * 26;
            // }
            // $sheet->getRowDimension($fila)->setRowHeight($alto_fila);
            $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'ACCIÓN DE CORTO PLAZO DE GESTIÓN');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $memoria_calculo->formulario_cuatro ? $memoria_calculo->formulario_cuatro->accion_corto : "");
            $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'RESULTADO ESPERADO GESTIÓN');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $memoria_calculo->formulario_cuatro ? $memoria_calculo->formulario_cuatro->resultado_esperado : "");
            $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'PRESUPUESTO PROGRAMADO GESTIÓN');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, number_format($memoria_calculo->formulario_cuatro ? $memoria_calculo->formulario_cuatro->presupuesto : 0, 2) . " ");
            $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'PONDERACIÓN %');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, number_format($memoria_calculo->formulario_cuatro ? $memoria_calculo->formulario_cuatro->ponderacion : 0, 2) . " ");
            $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'UNIDAD ORGANIZACIONAL');
            $sheet->getStyle('A' . $fila)->applyFromArray($styleArray);
            $sheet->setCellValue('B' . $fila, $memoria_calculo->formulario_cuatro ? $memoria_calculo->formulario_cuatro->unidad->nombre : "");
            $sheet->mergeCells("B" . $fila . ":G" . $fila);  //COMBINAR
            $sheet->getStyle('B' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);

            $fila++;
            $fila++;
            // $sheet->setCellValue('A' . $fila, 'Código tarea');
            // $sheet->mergeCells("A" . $fila . ":A" . ($fila + 1));  //COMBINAR CELDAS
            // $sheet->setCellValue('B' . $fila, 'Actividad/Tarea');
            // $sheet->mergeCells("B" . $fila . ":B" . ($fila + 1));  //COMBINAR CELDAS
            $sheet->setCellValue('A' . $fila, 'Partida');
            $sheet->mergeCells("A" . $fila . ":A" . ($fila + 1));  //COMBINAR CELDAS
            $sheet->setCellValue('B' . $fila, 'Presupuesto');
            $sheet->mergeCells("B" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, 'Ejecutado');
            $sheet->mergeCells("E" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('G' . $fila, 'Saldo');
            $sheet->mergeCells("G" . $fila . ":G" . ($fila + 1));  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($styleArray2);
            $fila++;
            $sheet->setCellValue('B' . $fila, 'Cantidad');
            $sheet->setCellValue('C' . $fila, 'Costo Unitario');
            $sheet->setCellValue('D' . $fila, 'Presupuesto vigente');
            $sheet->setCellValue('E' . $fila, 'Cantidad');
            $sheet->setCellValue('F' . $fila, 'Presupuesto Vigente');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($styleArray2);
            $fila++;
            $suma_ejecutados = 0;
            $suma_saldos = 0;
            foreach ($memoria_calculo->operacions as $operacion) {
                // $sheet->setCellValue('A' . $fila, $operacion->codigo_actividad);
                // $sheet->setCellValue('B' . $fila, $operacion->descripcion_actividad);

                foreach ($operacion->memoria_operacion_detalles as $mod) {
                    $sheet->setCellValue('A' . $fila, $mod->m_partida->partida);
                    $sheet->setCellValue('B' . $fila, $mod->cantidad);
                    $sheet->setCellValue('C' . $fila, number_format($mod->costo, 2) . " ");
                    $sheet->setCellValue('D' . $fila, number_format($mod->total, 2) . " ");
                    $cantidad_usado = CertificacionDetalle::select("certificacion_detalles.*")
                        ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
                        ->where('certificacions.mo_id', $operacion->id)
                        ->where("anulado", 0)
                        ->where("mod_id", $mod->id)
                        ->sum('cantidad_usar');
                    $total_usado = CertificacionDetalle::select("certificacion_detalles.*")
                        ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
                        ->where('certificacions.mo_id', $operacion->id)
                        ->where("anulado", 0)
                        ->where("mod_id", $mod->id)
                        ->sum('presupuesto_usarse');
                    $saldo = (float) $mod->total - (float) $total_usado;
                    $sheet->setCellValue('E' . $fila, $cantidad_usado);
                    $sheet->setCellValue('F' . $fila, $total_usado);
                    $sheet->setCellValue('G' . $fila, number_format($saldo, 2) . " ");
                    $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($estilo_conenido);
                    $fila++;
                    $suma_ejecutados += $total_usado;
                    $suma_saldos += $saldo;
                }
            }

            $sheet->setCellValue('A' . $fila, 'TOTAL');
            $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('D' . $fila, number_format($memoria_calculo->total_final, 2) . " ");
            $sheet->setCellValue('F' . $fila, number_format($suma_ejecutados, 2) . " ");
            $sheet->setCellValue('G' . $fila, number_format($suma_saldos, 2) . " ");
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($estilo_total);

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
        }
        $sheet->setCellValue('G' . $fila, self::getFechaTexto());
        $sheet->mergeCells("G" . $fila . ":G" . $fila);  //COMBINAR CELDAS

        foreach (range('A', 'I') as $columnID) {
            $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
        }

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.1);
        $sheet->getPageMargins()->setLeft(0.1);
        $sheet->getPageMargins()->setBottom(0.1);
        $sheet->getPageSetup()->setPrintArea('A:G');
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

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

    public static function saldo_presupuesto_excel($formularios) {}

    public function ejecucion_presupuestos(Request $request)
    {
        $filtro =  $request->filtro;
        $unidad_id =  $request->unidad_id;
        $formulario_id =  $request->formulario_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $filtro2 =  $request->filtro2;

        $memoria_calculos = [];
        $unidad = null;
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "MAE") {
            $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Código PEI":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                            ->where("memoria_calculos.formulario_seleccionado", $formulario_id)
                            ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                            ->whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])
                            ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                }
            }
        } else {
            $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                ->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Unidad Organizacional":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                            ->where("formulario_cuatro.unidad_id", $unidad_id)->get();
                        $unidad = Unidad::find($unidad_id);
                        break;
                    case "Código PEI":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                            ->where("memoria_calculos.formulario_seleccionado", $formulario_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                            ->whereBetween("memoria_calculos.fecha_registro", [$fecha_ini, $fecha_fin])->get();
                        break;
                }
            }
        }

        $pdf = PDF::loadView('reportes.ejecucion_presupuestos', compact('memoria_calculos', 'filtro', 'filtro2', 'unidad'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('EjecucionPresupuestos.pdf');
    }

    public function lista_certificacion(Request $request)
    {
        set_time_limit(0);
        $filtro = $request->filtro;
        $unidad_id = $request->unidad_id;
        $tipo = $request->tipo;

        $unidad = null;
        $certificacion_detalles = CertificacionDetalle::select("certificacion_detalles.*")
            ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "certificacions.formulario_id");
        if ($filtro != 'Todos' || Auth::user()->tipo == 'ENLACE') {
            if ($filtro != 'Todos' && Auth::user()->tipo == 'ADMINISTRADOR') {
                $request->validate([
                    "unidad_id" => "required"
                ], [
                    "unidad_id.required" => "Debes seleccionar una unidad"
                ]);
            } elseif (Auth::user()->tipo == 'ENLACE') {
                $unidad_id = Auth::user()->unidad_id;
            }
            $unidad = Unidad::find($unidad_id);
            $certificacion_detalles->where("formulario_cuatro.unidad_id", $unidad_id);
        }

        $certificacion_detalles = $certificacion_detalles->get();
        $html = "";
        if ($tipo == 'pdf') {
            foreach ($certificacion_detalles as $certificacion_detalle) {
                $html .= ' <tr>
                <td>' . $certificacion_detalle->certificacion->correlativo . '</td>
                <td>' . $certificacion_detalle->certificacion->formulario->unidad->nombre . '</td>
                <td>' . $certificacion_detalle->certificacion->solicitante->full_name . '</td>
                <td class="centreado">' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->ue . '|' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->prog . '|' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->act . '
                </td>
                <td>' . $certificacion_detalle->certificacion->memoria_operacion->operacion->codigo_operacion . '</td>
                <td>' . $certificacion_detalle->memoria_operacion_detalle->partida . '</td>
                <td>' . $certificacion_detalle->memoria_operacion_detalle->total . '</td>
                <td>' . $certificacion_detalle->certificacion->fecha_registro . '</td>
            </tr>';
            }

            $pdf = PDF::loadView('reportes.lista_certificacion', compact('certificacion_detalles', 'unidad', 'html'))->setPaper('legal', 'landscape');
            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
            return $pdf->download('lista_certificacion.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Certificacions')
                ->setSubject('Certificacions')
                ->setDescription('Certificacions')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $styleTexto = [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'family' => 'Times New Roman'
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
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
                    'size' => 10,
                    'color' => ['argb' => 'ffffff'],
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
                    'color' => ['rgb' => '203764']
                ],
            ];


            $styleArray2 = [
                'font' => [
                    'bold' => true,
                    'size' => 10,
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
                    'color' => ['rgb' => '203764']
                ],
            ];

            $estilo_conenido = [
                'font' => [
                    'size' => 10,
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
                    'size' => 10,
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
                    'size' => 10,
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
            if (file_exists(public_path() . '/imgs/' . Configuracion::first()->logo2)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo2');
                $drawing->setDescription('logo2');
                $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo2); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }
            if (file_exists(public_path() . '/imgs/' . Configuracion::first()->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo); // put your path and image here
                $drawing->setCoordinates('T' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, "REPORTE DE CERTIFICACIÓN ");
            $sheet->mergeCells("A" . $fila . ":H" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':H' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($styleTexto);
            $fila++;
            $fila++;
            $fila++;

            $sheet->setCellValue('A' . $fila, 'N° Correlativo');
            $sheet->setCellValue('B' . $fila, 'Unidad Organizacional');
            $sheet->setCellValue('C' . $fila, 'Nombre del solicitante');
            $sheet->setCellValue('D' . $fila, 'Categoría programatica');
            $sheet->setCellValue('E' . $fila, 'Cod. Operación');
            $sheet->setCellValue('F' . $fila, 'Partida');
            $sheet->setCellValue('G' . $fila, 'Monto');
            $sheet->setCellValue('H' . $fila, 'Fecha');
            $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($styleArray);
            $fila++;
            foreach ($certificacion_detalles as $certificacion_detalle) {
                $sheet->setCellValue('A' . $fila, $certificacion_detalle->certificacion->correlativo);
                $sheet->setCellValue('B' . $fila, $certificacion_detalle->certificacion->formulario->unidad->nombre);
                $sheet->setCellValue('C' . $fila, $certificacion_detalle->certificacion->solicitante->full_name);
                $sheet->setCellValue('D' . $fila, $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->ue . '|' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->prog . '|' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->act);
                $sheet->setCellValue('E' . $fila, $certificacion_detalle->certificacion->memoria_operacion->operacion->codigo_operacion);
                $sheet->setCellValue('F' . $fila, $certificacion_detalle->memoria_operacion_detalle->partida);
                $sheet->setCellValue('G' . $fila, $certificacion_detalle->memoria_operacion_detalle->total);
                $sheet->setCellValue('H' . $fila, $certificacion_detalle->certificacion->fecha_registro);
                $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($estilo_conenido);
                $fila++;
            }
            $fila++;

            $sheet->getColumnDimension('A')->setWidth(7);
            $sheet->getColumnDimension('B')->setWidth(35);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(7);
            $sheet->getColumnDimension('F')->setWidth(8);
            $sheet->getColumnDimension('G')->setWidth(14);
            $sheet->getColumnDimension('H')->setWidth(10);

            foreach (range('A', 'H') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:U');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // DESCARGA DEL ARCHIVO
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="lista_certificacion.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }
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
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "MAE") {
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

        $memoria_calculos = [];
        $unidad = null;
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "MAE") {
            $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "memoria_calculos.formulario_id")
                ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                ->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Código PEI":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "memoria_calculos.formulario_id")
                            ->where("memoria_calculos.formulario_seleccionado", $formulario_id)
                            ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "memoria_calculos.formulario_id")
                            ->whereBetween("memoria_calculos.fecha_registro", [$fecha_ini, $fecha_fin])
                            ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                }
            }
        } else {
            $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Unidad Organizacional":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "memoria_calculos.formulario_id")
                            ->where("formulario_cuatro.unidad_id", $unidad_id)->get();
                        $unidad = Unidad::find($unidad_id);
                        break;
                    case "Código PEI":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "memoria_calculos.formulario_id")
                            ->where("memoria_calculos.formulario_seleccionado", $formulario_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->whereBetween("fecha_registro", [$fecha_ini, $fecha_fin])->get();
                        break;
                }
            }
        }


        $array_tablas = [];

        $pdf = PDF::loadView('reportes.formulario_cinco', compact('memoria_calculos', "array_tablas", "filtro", "unidad"))->setPaper('legal', 'landscape');
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

        $memoria_calculos = [];
        $unidad = null;
        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "MAE") {
            $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Código PEI":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                            ->where("memoria_calculos.formulario_seleccionado", $formulario_id)
                            ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                            ->whereBetween("memoria_calculos.fecha_registro", [$fecha_ini, $fecha_fin])
                            ->where("formulario_cuatro.unidad_id", Auth::user()->unidad_id)
                            ->get();
                        break;
                }
            }
        } else {
            $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                ->get();
            if ($filtro != "Todos") {
                switch ($filtro) {
                    case "Unidad Organizacional":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                            ->where("formulario_cuatro.unidad_id", $unidad_id)->get();
                        $unidad = Unidad::find($unidad_id);
                        break;
                    case "Código PEI":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "memoria_calculos.formulario_id")
                            ->where("memoria_calculos.formulario_seleccionado", $formulario_id)
                            ->get();
                        break;
                    case "Rango de fechas":
                        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")
                            ->whereBetween("memoria_calculos.fecha_registro", [$fecha_ini, $fecha_fin])->get();
                        break;
                }
            }
        }


        $pdf = PDF::loadView('reportes.memoria_calculos', compact('memoria_calculos', "filtro", "unidad"))->setPaper('legal', 'landscape');
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

        $memoria_calculo = MemoriaCalculo::where("formulario_seleccionado", $formulario_id)->get()->first();
        $partida = Partida::find($partida_id);
        $memoria_operacion_detalles = null;
        $unidad = null;
        $formulario = null;
        if ($memoria_calculo) {
            $formulario = $memoria_calculo->formulario;
            $memoria_operacion_detalles = MemoriaOperacionDetalle::select("memoria_operacion_detalles.*")
                ->join("memoria_operacions", "memoria_operacions.id", "=", "memoria_operacion_detalles.memoria_operacion_id")
                ->where("memoria_operacions.memoria_id", $memoria_calculo->id)
                ->where("memoria_operacion_detalles.partida_id", $partida_id)
                ->get();
            $unidad = $memoria_calculo->formulario->unidad;
        }

        $pdf = PDF::loadView('reportes.saldos_partida', compact("memoria_operacion_detalles", "memoria_calculo", "formulario", "partida", "unidad"))->setPaper('legal', 'landscape');
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

        if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "MAE") {
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
            if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "MAE") {
                $formularios = FormularioCuatro::where("unidad_id", $unidad->id)->where("id", Auth::user()->unidad_id)->get();
            } else {
                $formularios = FormularioCuatro::where("unidad_id", $unidad->id)->get();
            }
            if ($filtro == "Rango de fechas") {
                if (Auth::user()->tipo == "JEFES DE UNIDAD" || Auth::user()->tipo == "DIRECTORES" || Auth::user()->tipo == "JEFES DE ÁREAS" || Auth::user()->tipo == "MAE") {
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
                                $total_usado = CertificacionDetalle::select("certificacion_detalles.*")
                                    ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
                                    ->where("certificacions.mo_id", $operacion->id)
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
                'family' => 'Times New Roman'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
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
                'size' => 10,
                'color' => ['argb' => 'ffffff'],
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
                'color' => ['rgb' => '203764']
            ],
        ];


        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 10,
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
                'color' => ['rgb' => '203764']
            ],
        ];

        $estilo_conenido = [
            'font' => [
                'size' => 10,
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
                'size' => 10,
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
                'size' => 10,
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
        if (file_exists(public_path() . '/imgs/' . Configuracion::first()->logo2)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('logo2');
            $drawing->setDescription('logo2');
            $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo2); // put your path and image here
            $drawing->setCoordinates('A' . $fila);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(0);
            $drawing->setHeight(60);
            $drawing->setWorksheet($sheet);
        }
        if (file_exists(public_path() . '/imgs/' . Configuracion::first()->logo)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('logo');
            $drawing->setDescription('logo');
            $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo); // put your path and image here
            $drawing->setCoordinates('T' . $fila);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(0);
            $drawing->setHeight(60);
            $drawing->setWorksheet($sheet);
        }

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
        $sheet->setCellValue('A' . $fila, "FORMULARIO 4");
        $sheet->mergeCells("A" . $fila . ":U" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':U' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($styleTextoForm);

        $fila++;
        $sheet->setCellValue('A' . $fila, 'CÓDIGO PEI');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, str_replace("|", "\n", $formulario_cuatro->codigo_pei));
        $enters = substr_count($formulario_cuatro->codigo_pei, "|");
        $alto_fila = 25;
        if ($enters > 1) {
            $alto_fila = $enters * 26;
        }
        $sheet->getRowDimension($fila)->setRowHeight($alto_fila);
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'RESULTADO INSTITUCIONAL');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, $formulario_cuatro->resultado_institucional);
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'INDICADOR DE PROCESO');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $enters = substr_count(nl2br($formulario_cuatro->indicador), "<br />");
        $alto_fila = 25;
        if ($enters > 1) {
            $alto_fila = $enters * 26;
        }
        $sheet->getRowDimension($fila)->setRowHeight($alto_fila);
        $sheet->setCellValue('D' . $fila, str_replace("<br />", "\n", nl2br($formulario_cuatro->indicador)));
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'CÓDIGO POA');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, str_replace("|", "\n", $formulario_cuatro->codigo_pei));
        $enters = substr_count($formulario_cuatro->codigo_poa, "|");
        $alto_fila = 25;
        if ($enters > 1) {
            $alto_fila = $enters * 26;
        }
        $sheet->getRowDimension($fila)->setRowHeight($alto_fila);
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $verificacion_actividad = VerificacionActividad::first();
        $sheet->setCellValue('A' . $fila, 'ACCIÓN DE CORTO PLAZO DE GESTIÓN ' .   ($verificacion_actividad ? $verificacion_actividad->gestion : date('Y')));
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, $formulario_cuatro->accion_corto);
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'INDICADOR DE PROCESO POA');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $sheet->setCellValue('D' . $fila, str_replace(',', "\n", $formulario_cuatro->indicador_proceso));
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'LINEA DE BASE');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $enters = substr_count(nl2br($formulario_cuatro->linea_base), "<br />");
        $alto_fila = 25;
        if ($enters > 1) {
            $alto_fila = $enters * 26;
        }
        $sheet->getRowDimension($fila)->setRowHeight($alto_fila);
        $sheet->setCellValue('D' . $fila, str_replace("<br />", "\n", nl2br($formulario_cuatro->linea_base)));
        $sheet->mergeCells("D" . $fila . ":U" . $fila);  //COMBINAR
        $sheet->getStyle('D' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido2);
        $fila++;
        $sheet->setCellValue('A' . $fila, 'META');
        $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ":C" . $fila)->applyFromArray($styleArray);
        $enters = substr_count(nl2br($formulario_cuatro->meta), "<br />");
        $alto_fila = 25;
        if ($enters > 1) {
            $alto_fila = $enters * 26;
        }
        $sheet->getRowDimension($fila)->setRowHeight($alto_fila);
        $sheet->setCellValue('D' . $fila, str_replace("<br />", "\n", nl2br($formulario_cuatro->meta)));
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
        $sheet->setCellValue('D' . $fila, $formulario_cuatro->ponderacion . "%");
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
                $sheet->setCellValue('C' . $fila, $operacion->ponderacion);
                $sheet->setCellValue('D' . $fila, $operacion->resultado_esperado);
                $sheet->setCellValue('E' . $fila, $operacion->medios_verificacion);
                $contador = $fila;
                foreach ($operacion->detalle_operaciones as $detalle_operacion) {
                    // $sheet->setCellValue('D' . $contador, $detalle_operacion->resultado_esperado);
                    // $sheet->setCellValue('E' . $contador, $detalle_operacion->medios_verificacion);
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
                $sheet->mergeCells("C" . $fila . ":C" . ($contador - 1));  //COMBINAR CELDAS
                $sheet->mergeCells("D" . $fila . ":D" . ($contador - 1));  //COMBINAR CELDAS
                $sheet->mergeCells("E" . $fila . ":E" . ($contador - 1));  //COMBINAR CELDAS
                $fila = $contador - 1;
                $sheet->getStyle('A' . $fila . ':U' . $fila)->applyFromArray($estilo_conenido);
                $fila++;
            }
        }
        $fila++;
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 10,
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
                'size' => 10,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'B4C6E7']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArray3 = [
            'font' => [
                'bold' => true,
                'size' => 10,
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
        $sheet->getRowDimension($fila)->setRowHeight(80);
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
        $sheet->getRowDimension($fila)->setRowHeight(30);
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
        $sheet->getRowDimension($fila)->setRowHeight(30);
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
        $sheet->setCellValue('S' . $fila, self::getFechaTexto());
        $sheet->mergeCells("S" . $fila . ":U" . $fila);  //COMBINAR CELDAS


        $sheet->getColumnDimension('A')->setWidth(6);
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

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.1);
        $sheet->getPageMargins()->setLeft(0.1);
        $sheet->getPageMargins()->setBottom(0.1);
        $sheet->getPageSetup()->setPrintArea('A:U');
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

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
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
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
                'size' => 10,
                'color' => ['argb' => 'ffffff'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '203764']
            ],
        ];


        $styleArray2 = [
            'font' => [
                'bold' => true,
                'size' => 10,
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
                'color' => ['rgb' => '203764']
            ],
        ];

        $estilo_conenido = [
            'font' => [
                'size' => 10,
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
                'size' => 10,
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
                'size' => 10,
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
                'size' => 9,
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

        $estilo_total2 = [
            'font' => [
                'size' => 9,
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
                'color' => ['rgb' => 'EEECE1']
            ],
        ];


        $fila = 1;

        if (file_exists(public_path() . "/imgs/" . Configuracion::first()->logo2)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('logo2');
            $drawing->setDescription('logo2');
            $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo2); // put your path and image here
            $drawing->setCoordinates('A' . $fila);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(0);
            $drawing->setHeight(60);
            $drawing->setWorksheet($sheet);
        }
        if (file_exists(public_path() . "/imgs/" . Configuracion::first()->logo)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('logo');
            $drawing->setDescription('logo');
            $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo); // put your path and image here
            $drawing->setCoordinates('O' . $fila);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(0);
            $drawing->setHeight(60);
            $drawing->setWorksheet($sheet);
        }

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
        $sheet->setCellValue('A' . $fila, "FORMULARIO 5");
        $sheet->mergeCells("A" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
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
                        // if ($sheet->getCell('C' . $fila_actividad)->getValue() == "") {
                        //     $sheet->setCellValue('C' . $fila_actividad, $operacion["codigo_tarea"]);
                        //     $sheet->mergeCells("C" . $fila_actividad . ":C" . ($fila_actividad + $operacion["rowspan"] - 1));  //COMBINAR CELDAS
                        // }
                        // if ($sheet->getCell('D' . $fila_actividad)->getValue() == "") {
                        //     $sheet->setCellValue('D' . $fila_actividad, $operacion["tarea"]);
                        //     $sheet->mergeCells("D" . $fila_actividad . ":D" . ($fila_actividad + $operacion["rowspan"] - 1));  //COMBINAR CELDAS
                        // }

                        $sheet->setCellValue('E' . $fila, $lugar["lugar"]);
                        $sheet->mergeCells("E" . $fila . ":E" . ($fila + $lugar["rowspan"] - 1));  //COMBINAR CELDAS

                        foreach ($lugar["responsables"] as $responsable) {
                            $sheet->setCellValue('F' . $fila, $responsable["responsable"]);
                            $sheet->mergeCells("F" . $fila . ":F" . ($fila + $responsable["rowspan"] - 1));  //COMBINAR CELDAS
                            $subtotal = 0;

                            foreach ($responsable["registros"] as $registro) {
                                $sheet->setCellValue('C' . $fila, $registro["cod_actividad_txt"]);
                                $sheet->setCellValue('D' . $fila, $registro["actividad_txt"]);
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
                                $subtotal += (float) $registro['total_actividad'];
                                $fila++;
                            }
                        }
                        $sheet->setCellValue('A' . $fila, 'TOTAL');
                        $sheet->mergeCells("A" . $fila . ":P" . $fila);  //COMBINAR CELDAS
                        $sheet->setCellValue('Q' . $fila, number_format($subtotal, 2));
                        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($estilo_total2);
                        $fila++;
                    }
                }
            }
        }
        $sheet->setCellValue('A' . $fila, 'TOTAL PRESUPUESTO DE LA/EL ' . $formulario_cinco->memoria->formulario->unidad->nombre);
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
                'color' => ['rgb' => 'B4C6E7']
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
        $sheet->mergeCells("C" . $fila . ":G" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('C' . $fila . ':G' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('H' . $fila, "REVISADO POR:");
        $sheet->mergeCells("H" . $fila . ":L" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, "APROBADO");
        $sheet->mergeCells("M" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray2);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(80);
        $sheet->setCellValue('A' . $fila, "FIRMA");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "");
        $sheet->mergeCells("C" . $fila . ":G" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('C' . $fila . ':G' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("H" . $fila . ":L" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, "");
        $sheet->mergeCells("M" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(30);
        $sheet->setCellValue('A' . $fila, "NOMBRE");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "");
        $sheet->mergeCells("C" . $fila . ":G" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("H" . $fila . ":L" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, "");
        $sheet->mergeCells("M" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(30);
        $sheet->setCellValue('A' . $fila, "CARGO");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "");
        $sheet->getStyle('C' . $fila . ':G' . $fila)->applyFromArray($styleArray3);
        $sheet->mergeCells("C" . $fila . ":G" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("H" . $fila . ":L" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('M' . $fila, "");
        $sheet->mergeCells("M" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($styleArray);
        $sheet->getStyle('H' . $fila . ':Q' . $fila)->applyFromArray($styleArray3);
        $fila++;
        $sheet->setCellValue('O' . $fila, self::getFechaTexto());
        $sheet->mergeCells("O" . $fila . ":Q" . $fila);  //COMBINAR CELDAS

        $sheet->getColumnDimension('A')->setWidth(7);
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

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.1);
        $sheet->getPageMargins()->setLeft(0.1);
        $sheet->getPageMargins()->setBottom(0.1);
        $sheet->getPageSetup()->setPrintArea('A:Q');
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

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
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                ],
            ],
        ];

        $estilo1 = [
            'font' => [
                'bold' => true,
                'size' => 10,
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
                'size' => 10,
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
                'size' => 10,
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
                'color' => ['rgb' => '203764']
            ],
        ];

        $estilo_conenido = [
            'font' => [
                'size' => 10,
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
                'size' => 10,
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
                'size' => 10,
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
                'size' => 10,
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
                'size' => 10,
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
                'color' => ['rgb' => '203764']
            ],
        ];

        $fila = 1;
        if (file_exists(public_path() . '/imgs/' . Configuracion::first()->logo2)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('logo2');
            $drawing->setDescription('logo2');
            $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo2); // put your path and image here
            $drawing->setCoordinates('A' . $fila);
            $drawing->setOffsetY(0);
            $drawing->setOffsetX(5);
            $drawing->setHeight(60);
            $drawing->setWorksheet($sheet);
        }
        if (file_exists(public_path() . '/imgs/' . Configuracion::first()->logo)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('logo');
            $drawing->setDescription('logo');
            $drawing->setPath(public_path() . '/imgs/' . Configuracion::first()->logo); // put your path and image here
            $drawing->setCoordinates('X' . $fila);
            $drawing->setOffsetY(0);
            $drawing->setOffsetX(5);
            $drawing->setHeight(60);
            $drawing->setWorksheet($sheet);
        }
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
                'size' => 10,
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
                'size' => 10,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'B4C6E7']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArray3 = [
            'font' => [
                'bold' => true,
                'size' => 10,
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
        $sheet->mergeCells("C" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('C' . $fila . ':I' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('J' . $fila, "REVISADO POR:");
        $sheet->mergeCells("J" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('R' . $fila, "APROBADO");
        $sheet->mergeCells("R" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray2);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(80);
        $sheet->setCellValue('A' . $fila, "FIRMA");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "");
        $sheet->mergeCells("C" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('C' . $fila . ':I' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('E' . $fila, "");
        $sheet->mergeCells("J" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("R" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(30);
        $sheet->setCellValue('A' . $fila, "NOMBRE");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "");
        $sheet->mergeCells("C" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('E' . $fila, "");
        $sheet->mergeCells("J" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('H' . $fila, "");
        $sheet->mergeCells("R" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray);
        $fila++;
        $sheet->getRowDimension($fila)->setRowHeight(30);
        $sheet->setCellValue('A' . $fila, "CARGO");
        $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('C' . $fila, "Jefe de Unidad de Planificación");
        $sheet->getStyle('C' . $fila . ':I' . $fila)->applyFromArray($styleArray3);
        $sheet->mergeCells("C" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('C' . $fila . ':I' . $fila)->applyFromArray($styleArray3);
        $sheet->setCellValue('E' . $fila, "");
        $sheet->mergeCells("J" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
        $sheet->setCellValue('R' . $fila, "Director General Ejecutivo");
        $sheet->mergeCells("R" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($styleArray);
        $sheet->getStyle('R' . $fila . ':AA' . $fila)->applyFromArray($styleArray3);
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
        $sheet->getColumnDimension('N')->setWidth(15);
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


        $sheet->getStyle('A:AA')->getAlignment()->setWrapText(true);

        // foreach (range('A', 'AA') as $columnID) {
        //     $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
        // }

        $sheet->getRowDimension(1)->setRowHeight(-1);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.1);
        $sheet->getPageMargins()->setLeft(0.1);
        $sheet->getPageMargins()->setBottom(0.1);
        $sheet->getPageSetup()->setPrintArea('A:AA');
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // DESCARGA DEL ARCHIVO
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MemoriaCalculo.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
