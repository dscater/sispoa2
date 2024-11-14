<?php

namespace App\Models;

use App\Http\Controllers\FormularioCincoController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log as FacadesLog;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Anteproyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        "form4_excel",
        "form5_excel",
        "memoria_excel",
        "certificacion_excel",
        "form4_pdf",
        "form5_pdf",
        "memoria_pdf",
        "certificacion_pdf",
        "fecha_cierre",
    ];

    protected $appends = ["fecha_cierre_t", "url_form4_pdf", "url_form5_pdf", "url_memoria_pdf", "url_certificacion_pdf", "url_form4_excel", "url_form5_excel", "url_memoria_excel", "url_certificacion_excel"];

    public function getFechaCierreTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_cierre));
    }

    public function getUrlForm4PdfAttribute()
    {
        return asset("files/anteproyectos/" . $this->form4_pdf);
    }
    public function getUrlForm5PdfAttribute()
    {
        return asset("files/anteproyectos/" . $this->form5_pdf);
    }
    public function getUrlMemoriaPdfAttribute()
    {
        return asset("files/anteproyectos/" . $this->memoria_pdf);
    }
    public function getUrlCertificacionPdfAttribute()
    {
        return asset("files/anteproyectos/" . $this->certificacion_pdf);
    }

    public function getUrlForm4ExcelAttribute()
    {
        return asset("files/anteproyectos/" . $this->form4_excel);
    }
    public function getUrlForm5ExcelAttribute()
    {
        return asset("files/anteproyectos/" . $this->form5_excel);
    }
    public function getUrlMemoriaExcelAttribute()
    {
        return asset("files/anteproyectos/" . $this->memoria_excel);
    }
    public function getUrlCertificacionExcelAttribute()
    {
        return asset("files/anteproyectos/" . $this->certificacion_excel);
    }

    // FUNCIONES
    public function generaForm4PDF()
    {
        $path_files = public_path("files/anteproyectos/");
        $nom_file = "f4" . time() . ".pdf";

        $formularios = FormularioCuatro::where("status", 1)->get();
        $pdf = PDF::loadView('reportes.anteproyectos.form4', compact('formularios'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        // Guardar el archivo en la carpeta especificada
        $pdf->save($path_files . $nom_file);
        return  $nom_file;
    }
    public function generaForm5PDF()
    {
        $path_files = public_path("files/anteproyectos/");
        $nom_file = "f5" . time() . ".pdf";
        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")->where("status", 1)->get();
        $pdf = PDF::loadView('reportes.anteproyectos.form5', compact('memoria_calculos'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        // Guardar el archivo en la carpeta especificada
        $pdf->save($path_files . $nom_file);
        return  $nom_file;
    }
    public function generaMemoriaPDF()
    {
        $path_files = public_path("files/anteproyectos/");
        $nom_file = "m0" . time() . ".pdf";

        $memoria_calculos = MemoriaCalculo::select("memoria_calculos.*")->where("status", 1)->get();

        $pdf = PDF::loadView('reportes.anteproyectos.memoria', compact('memoria_calculos'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        $pdf->save($path_files . $nom_file);
        return $nom_file;
    }
    public function generaCertificacionPDF()
    {
        $path_files = public_path("files/anteproyectos/");
        $nom_file = "c0" . time() . ".pdf";

        set_time_limit(0);
        $certificacion_detalles = CertificacionDetalle::select("certificacion_detalles.*")
            ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "certificacions.formulario_id");
        $certificacion_detalles = $certificacion_detalles->where("certificacions.status", 1)->get();
        $html = "";
        foreach ($certificacion_detalles as $certificacion_detalle) {
            if ($certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle && $certificacion_detalle->certificacion->memoria_operacion) {
                $html .= ' <tr>
                     <td>' . $certificacion_detalle->certificacion->correlativo . '</td>
                     <td>' . $certificacion_detalle->certificacion->formulario->unidad->nombre . '</td>
                     <td>' . $certificacion_detalle->certificacion->solicitante->full_name . '</td>';
                $html .= '<td class="centreado">' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->ue . '|' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->prog . '|' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->act . '
                     </td> ';
                $html .= '<td>' . $certificacion_detalle->certificacion->memoria_operacion->operacion->codigo_operacion . '</td>';
                $html .= '<td>' . $certificacion_detalle->memoria_operacion_detalle->partida . '</td>
                     <td>' . $certificacion_detalle->presupuesto_usarse . '</td>';

                $html .= '
                     <td>' . $certificacion_detalle->certificacion->fecha_registro . '</td>
                 </tr>';
            }
        }

        $pdf = PDF::loadView('reportes.anteproyectos.certificacion', compact('certificacion_detalles', 'html'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $pdf->save($path_files . $nom_file);

        return $nom_file;
    }

    public $styleTexto = [
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

    public $styleTextoForm = [
        'font' => [
            'bold' => true,
            'size' => 10,
        ],
    ];

    public $styleArray = [
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


    public $styleArray2 = [
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

    public $estilo_conenido = [
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

    public $estilo1 = [
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
    public $estilo2 = [
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



    public $estilo_conenido1 = [
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


    public $estilo_conenido2 = [
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

    public $estilo_conenido3 = [
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

    public $estilo_total = [
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

    public $estilo_total2 = [
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

    public $estilo_conenido_rojo = [
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

    public function generaForm4Excel()
    {
        $path_files = public_path("files/anteproyectos/");
        $nom_file = "f4" . time() . ".xlsx";


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
        $sheet->setCellValue('A' . $fila, "FORMULARIO 4");
        $sheet->mergeCells("A" . $fila . ":I" . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':I' . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':I' . $fila)->applyFromArray($this->styleTextoForm);
        $fila++;
        $fila++;
        $sheet->setCellValue('A' . $fila, 'Código PEI');
        $sheet->setCellValue('B' . $fila, 'Indicador de proceso');
        $sheet->setCellValue('C' . $fila, 'Código POA');
        $sheet->setCellValue('D' . $fila, 'Indicador de proceso POA');
        $sheet->setCellValue('E' . $fila, 'Resultado Esperado Gestión');
        $sheet->setCellValue('F' . $fila, 'Presupuesto programado Gestión');
        $sheet->setCellValue('G' . $fila, 'Ponderación %');
        $sheet->setCellValue('H' . $fila, 'Unidad Organizacional');
        $sheet->setCellValue('I' . $fila, 'Fecha de Registro');
        $sheet->getStyle('A' . $fila . ':I' . $fila)->applyFromArray($this->styleArray2);
        $fila++;
        $fila++;

        $formularios = FormularioCuatro::where("status", 1)->get();
        foreach ($formularios as $formulario) {
            $sheet->setCellValue('A' . $fila, str_replace(',', '<br>', $formulario->codigo_pei));
            $sheet->setCellValue('B' . $fila, $formulario->indicador);
            $sheet->setCellValue('C' . $fila, $formulario->codigo_poa);
            $sheet->setCellValue('D' . $fila, $formulario->indicador_proceso);
            $sheet->setCellValue('E' . $fila, $formulario->resultado_institucional);
            $sheet->setCellValue('F' . $fila, $formulario->presupuesto);
            $sheet->setCellValue('G' . $fila, $formulario->ponderacion);
            $sheet->setCellValue('H' . $fila, $formulario->unidad->nombre);
            $sheet->setCellValue('I' . $fila, date('d/m/Y', strtotime($formulario->fecha_registro)));
            $fila++;
        }


        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(10);

        foreach (range('A', 'U') as $columnID) {
            $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
        }

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.1);
        $sheet->getPageMargins()->setLeft(0.1);
        $sheet->getPageMargins()->setBottom(0.1);
        $sheet->getPageSetup()->setPrintArea('A:I');
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // Guardar el archivo de Excel en la ruta especificada
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($path_files . $nom_file);

        return $nom_file;
    }
    public function generaForm5Excel()
    {
        $path_files = public_path("files/anteproyectos/");
        $nom_file = "f5" . time() . ".xlsx";

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


        $formulario_cincos = FormularioCinco::where("status", 1)->get();
        $fila = 1;
        foreach ($formulario_cincos as $formulario_cinco) {
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

            $fila++;
            $sheet->setCellValue('A' . $fila, "FORMULARIO 5");
            $sheet->mergeCells("A" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('L' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('L' . $fila . ':M' . $fila)->applyFromArray($this->styleTexto);
            $fila++;

            $sheet->setCellValue('A' . $fila, 'CÓDIGO PEI');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($this->styleArray);
            $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->codigo_pei);
            $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
            $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido2);

            $fila++;
            $sheet->setCellValue('A' . $fila, 'OBJETIVO ESTRATÉGICO INSTITUCIONAL');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($this->styleArray);
            $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->accion_institucional);
            $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
            $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido2);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'INDICADOR');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($this->styleArray);
            $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->indicador);
            $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
            $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido2);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'CODIGO POA');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($this->styleArray);
            $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->codigo_poa);
            $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
            $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido2);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'ACCIÓN DE CORTO PLAZO DE GESTIÓN');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($this->styleArray);
            $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->accion_corto);
            $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
            $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido2);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'RESULTADO ESPERADO GESTIÓN');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($this->styleArray);
            $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->resultado_esperado);
            $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
            $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido2);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'PRESUPUESTO PROGRAMADO GESTIÓN');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($this->styleArray);
            $sheet->setCellValue('C' . $fila, number_format($formulario_cinco->memoria->formulario->presupuesto, 2) . " ");
            $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
            $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido2);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'PONDERACIÓN %');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($this->styleArray);
            $sheet->setCellValue('C' . $fila, number_format($formulario_cinco->memoria->formulario->ponderacion, 2) . " ");
            $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
            $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido2);
            $fila++;
            $sheet->setCellValue('A' . $fila, 'UNIDAD ORGANIZACIONAL');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . 'B' . $fila)->applyFromArray($this->styleArray);
            $sheet->setCellValue('C' . $fila, $formulario_cinco->memoria->formulario->unidad->nombre);
            $sheet->mergeCells("C" . $fila . ":Q" . $fila);  //COMBINAR
            $sheet->getStyle('C' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido2);

            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, "PLAN OPERATIVO ANUAL");
            $sheet->mergeCells("A" . $fila . ":Q" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':Q' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($this->styleArray2);
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

            $sheet->getStyle('A' . ($fila - 2) . ':Q' . ($fila - 2))->applyFromArray($this->styleArray2);
            $sheet->getStyle('A' . ($fila - 1) . ':Q' . ($fila - 1))->applyFromArray($this->styleArray2);
            $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($this->styleArray2);
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
                            $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($this->styleArray2);
                            $fila++;
                            $fila_rowspan = $fila;
                        }

                        $sheet->setCellValue('A' . $fila_rowspan, $ar["codigo_operacion"]);
                        $sheet->setCellValue('B' . $fila_rowspan, $ar["operacion"]);
                        $suma_fila = (int)$ar["rowspan"] - 1;
                        if ($suma_fila > 1) {
                            $sheet->mergeCells("A" . $fila . ":A" . ($fila + $suma_fila));  //COMBINAR CELDAS
                            $sheet->mergeCells("B" . $fila . ":B" . ($fila + $suma_fila));  //COMBINAR CELDAS
                        }
                        $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido);

                        $fila_actividad = $fila;
                        foreach ($operacion["lugares"] as $lugar) {
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
                                    $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_conenido);
                                    if ($registro["saldo"] == 0) {
                                        $sheet->getStyle('Q' . $fila)->applyFromArray($this->estilo_conenido_rojo);
                                    }
                                    $subtotal += (float) $registro['total_actividad'];
                                    $fila++;
                                }
                            }
                            $sheet->setCellValue('A' . $fila, 'TOTAL');
                            $sheet->mergeCells("A" . $fila . ":P" . $fila);  //COMBINAR CELDAS
                            $sheet->setCellValue('Q' . $fila, number_format($subtotal, 2));
                            $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_total2);
                            $fila++;
                        }
                    }
                }
            }
            $sheet->setCellValue('A' . $fila, 'TOTAL PRESUPUESTO DE LA/EL ' . $formulario_cinco->memoria->formulario->unidad->nombre);
            $sheet->mergeCells("A" . $fila . ":P" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('Q' . $fila, number_format($formulario_cinco->memoria->total_final, 2));
            $sheet->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($this->estilo_total);

            $fila++;
            $fila++;
            $fila++;
            $fila++;
        }


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
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($path_files . $nom_file);

        return $nom_file;
    }
    public function generaMemoriaExcel()
    {
        $path_files = public_path("files/anteproyectos/");
        $nom_file = "m0" . time() . ".xlsx";

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

        $memoria_calculos = MemoriaCalculo::where("status", 1)->get();
        $fila = 1;
        foreach ($memoria_calculos as $memoria_calculo) {
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
            $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($this->styleTexto);
            $fila++;
            $sheet->setCellValue('A' . $fila, "(Expresado en Bolivianos)");
            $sheet->mergeCells("A" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':AA' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($this->styleTexto);
            $fila++;

            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, "CODIGO");
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, "DESCRIPCIÓN");
            $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo2);

            $fila++;
            $sheet->getRowDimension($fila)->setRowHeight(40);
            $sheet->setCellValue('A' . $fila, "ENTIDAD:");
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, "385");
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, "AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO - ASSUS");
            $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo2);
            $fila++;
            $sheet->getRowDimension($fila)->setRowHeight(40);
            $sheet->setCellValue('A' . $fila, "UNIDAD ORGANIZACIONAL:");
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, $memoria_calculo->formulario->unidad->nombre);
            $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo2);
            $fila++;
            $sheet->getRowDimension($fila)->setRowHeight(40);
            $sheet->setCellValue('A' . $fila, "DIRECCIÓN ADMINISTRATIVA:");
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, "1");
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, "DIRECCIÓN ADMINISTRATIVA FINANCIERA");
            $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo2);
            $fila++;
            $sheet->getRowDimension($fila)->setRowHeight(40);
            $sheet->setCellValue('A' . $fila, "FUENTE:");
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, "42");
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, "TRANSFERENCIAS DE RECURSOS ESPECÍFICOS");
            $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo2);
            $fila++;
            $sheet->getRowDimension($fila)->setRowHeight(40);
            $sheet->setCellValue('A' . $fila, "ORGANISMO FINANCIADOR:");
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, "230");
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, "OTROS RECURSOS ESPECÍFICOS");
            $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo2);

            $fila++;
            $fila++;
            $sheet->getRowDimension($fila)->setRowHeight(40);
            $sheet->setCellValue('A' . $fila, 'CODIGO POA:');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, str_replace(" | ", "\n", $memoria_calculo->formulario->codigo_poa_full));
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, $memoria_calculo->formulario->accion_corto);
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo_conenido1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo_conenido1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo_conenido2);
            $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $fila++;
            $sheet->getRowDimension($fila)->setRowHeight(40);
            $sheet->setCellValue('A' . $fila, 'UNIDAD EJECUTORA:');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, "1");
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, "ADMINISTRACIÓN CENTRAL");
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo_conenido1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo_conenido1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo_conenido2);
            $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $fila++;
            $sheet->getRowDimension($fila)->setRowHeight(40);
            $sheet->setCellValue('A' . $fila, 'PROGRAMA:');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, "1");
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, "ADMINISTRACIÓN Y GESTIÓN INSTITUCIONAL");
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo_conenido1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo_conenido1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo_conenido2);
            $sheet->mergeCells("E" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
            $fila++;
            $sheet->getRowDimension($fila)->setRowHeight(40);
            $sheet->setCellValue('A' . $fila, 'ACTIVIDAD:');
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, "1");
            $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, "GESTIÓN EJECUTIVA");
            $sheet->getStyle('A' . $fila . ":B" . $fila)->applyFromArray($this->estilo_conenido1);
            $sheet->getStyle('E' . $fila . ":AA" . $fila)->applyFromArray($this->estilo_conenido1);
            $sheet->getStyle('C' . $fila . ":D" . $fila)->applyFromArray($this->estilo_conenido2);
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
            $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($this->styleArray2);
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
            $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($this->styleArray2);
            $fila++;
            foreach ($memoria_calculo->operacions as $operacion) {
                if ($operacion->operacion->subdireccion) {
                    $sheet->setCellValue('A' . $fila, $operacion->operacion->subdireccion->nombre);
                    $sheet->mergeCells("A" . $fila . ":AA" . $fila);  //COMBINAR CELDAS
                    $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($this->styleArray2);
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
                    $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($this->estilo_conenido);
                    if ($mod->saldo == 0) {
                        $sheet->getStyle('AA' . $fila)->applyFromArray($this->estilo_conenido_rojo);
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
            $sheet->getStyle('A' . $fila . ':AA' . $fila)->applyFromArray($this->estilo_total);
            $fila++;
            $fila++;
            $fila++;
            $fila++;
        }


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

        $sheet->getRowDimension(1)->setRowHeight(-1);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.1);
        $sheet->getPageMargins()->setLeft(0.1);
        $sheet->getPageMargins()->setBottom(0.1);
        $sheet->getPageSetup()->setPrintArea('A:AA');
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($path_files . $nom_file);

        return $nom_file;
    }
    public function generaCertificacionExcel()
    {
        $path_files = public_path("files/anteproyectos/");
        $nom_file = "c0" . time() . ".xlsx";

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
        $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->styleTexto);
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
        $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->styleArray);

        $certificacion_detalles = CertificacionDetalle::select("certificacion_detalles.*")
            ->join("certificacions", "certificacions.id", "=", "certificacion_detalles.certificacion_id")
            ->join("formulario_cuatro", "formulario_cuatro.id", "=", "certificacions.formulario_id");
        $certificacion_detalles = $certificacion_detalles->where("certificacions.status", 1)->get();

        $fila++;
        foreach ($certificacion_detalles as $certificacion_detalle) {
            if ($certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle && $certificacion_detalle->certificacion->memoria_operacion) {
                $sheet->setCellValue('A' . $fila, $certificacion_detalle->certificacion->correlativo);
                $sheet->setCellValue('B' . $fila, $certificacion_detalle->certificacion->formulario->unidad->nombre);
                $sheet->setCellValue('C' . $fila, $certificacion_detalle->certificacion->solicitante->full_name);
                $sheet->setCellValue('D' . $fila, $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->ue . '|' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->prog . '|' . $certificacion_detalle->certificacion->certificacion_detalles[0]->memoria_operacion_detalle->act);

                $sheet->setCellValue('F' . $fila, $certificacion_detalle->memoria_operacion_detalle->partida);
                $sheet->setCellValue('G' . $fila, $certificacion_detalle->presupuesto_usarse);
                $sheet->setCellValue('E' . $fila, $certificacion_detalle->certificacion->memoria_operacion->operacion->codigo_operacion);
            }
            $sheet->setCellValue('H' . $fila, $certificacion_detalle->certificacion->fecha_registro);
            $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->estilo_conenido);
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
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($path_files . $nom_file);
        return $nom_file;
    }
}
