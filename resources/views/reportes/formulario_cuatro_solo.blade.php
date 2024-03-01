<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario4</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1.5cm;
            margin-bottom: 1cm;
            margin-left: 1cm;
            margin-right: 1cm;
            border: 5px solid blue;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
        }

        table thead tr th,
        tbody tr td {
            font-size: 0.63em;
            word-wrap: break-word;
        }

        thead tr th {
            border-left: solid 0.7px black;
            border-bottom: solid 0.7px black;
        }

        thead tr th:last-child {
            border-right: solid 0.7px black;
        }

        tbody.info_table tr td {
            border-left: solid 0.7px black;
            border-bottom: solid 0.7px black;
        }

        tbody.info_table tr td:last-child {
            border-right: solid 0.7px black;
        }

        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            width: 200px;
            height: 90px;
            top: -20px;
            left: -20px;
        }

        .logo2 img {
            position: absolute;
            width: 200px;
            height: 90px;
            top: -20px;
            right: -20px;
        }

        h2.titulo {
            width: 600px;
            margin: auto;
            margin-top: 15px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 600px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .fecha {
            width: 600px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: normal;
            font-size: 0.85em;
        }

        .total {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        table thead tr th {
            padding: 3px;
            font-size: 0.7em;
        }

        table tbody tr td {
            padding: 3px;
            font-size: 0.55em;
            word-wrap: break-word;
        }

        table tbody tr td.franco {
            background: red;
            color: white;
        }

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .datos {
            margin-left: 15px;
            border-top: solid 1px;
            border-collapse: collapse;
            width: 250px;
        }

        .txt {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
        }

        .txt_center {
            font-weight: bold;
            text-align: center;
        }

        .cumplimiento {
            position: absolute;
            width: 150px;
            right: 0px;
            top: 86px;
        }

        .p_cump {
            color: red;
            font-size: 1.2em;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .azul {
            background: #0062A5;
            color: white;
        }

        .verde_claro {
            background: #EBF1DE;
        }

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }

        .beis {
            background: #EEECE1;
        }

        .bold {
            font-weight: bold;
        }

        .celeste {
            background: #DAEEF3;
        }

        .none-border-bot {
            border-bottom: none !important;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @inject('verificacion_actividad', 'App\Models\VerificacionActividad')
    <div class="encabezado">
        <div class="logo">
            <img src="{{ asset('imgs/' . $configuracion->first()->logo2) }}">
        </div>
        <div class="logo2">
            <img src="{{ asset('imgs/' . $configuracion->first()->logo) }}">
        </div>
        <h2 class="titulo">
            {{ $configuracion->first()->razon_social }}
        </h2>
        <h4 class="texto">FORMULARIO 4</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
        @if ($formulario->unidad)
            <h4 class="fecha">{{ $formulario->unidad->nombre }}</h4>
        @endif
    </div>
    <table border="1">
        <tr>
            <tbody>
                <tr>
                    <td class="azul bold" width="15%">CÓDIGO PEI</td>
                    <td class="verde_claro bold">{!! str_replace('|', '<br/>', $formulario->codigo_pei) !!}</td>
                </tr>
                <tr>
                    <td class="azul bold" width="15%">RESULTADO INSTITUCIONAL</td>
                    <td class="verde_claro bold">{{ $formulario->resultado_institucional }}</td>
                </tr>
                <tr>
                    <td class="azul bold">INDICADOR DE PROCESO</td>
                    <td class="verde_claro bold">{!! nl2br($formulario->indicador) !!}</td>
                </tr>
                <tr>
                    <td class="azul bold">CÓDIGO POA</td>
                    <td class="verde_claro bold">{!! str_replace('|', '<br/>', $formulario->codigo_poa) !!}</td>
                </tr>
                <tr>
                    <td class="azul bold">ACCIÓN DE CORTO PLAZO DE GESTIÓN
                        {{ $verificacion_actividad->first() ? $verificacion_actividad->first()->gestion : date('Y') }}
                    </td>
                    <td class="verde_claro bold">{{ $formulario->accion_corto }}</td>
                </tr>
                <tr>
                    <td class="azul bold">INDICADOR DE PROCESO POA</td>
                    <td class="verde_claro bold">{!! str_replace(',', '<br/>', $formulario->indicador_proceso) !!}</td>
                </tr>
                <tr>
                    <td class="azul bold">LINEA DE BASE
                        {{ $verificacion_actividad->first() ? $verificacion_actividad->first()->gestion : date('Y') }}
                    </td>
                    <td class="verde_claro bold">{!! nl2br($formulario->linea_base) !!}</td>
                </tr>
                <tr>
                    <td class="azul bold">META
                        {{ $verificacion_actividad->first() ? $verificacion_actividad->first()->gestion : date('Y') }}
                    </td>
                    <td class="verde_claro bold">{!! nl2br($formulario->meta) !!}</td>
                </tr>
                <tr>
                    <td class="azul bold">PRESUPUESTO PROGRAMADO GESTIÓN</td>
                    <td class="verde_claro bold">{{ number_format($formulario->presupuesto, 2) }}</td>
                </tr>
                <tr>
                    <td class="azul bold">PONDERACIÓN %</td>
                    <td class="verde_claro bold">{{ $formulario->ponderacion }}%</td>
                </tr>
                <tr>
                    <td class="azul bold">UNIDAD ORGANIZACIONAL</td>
                    <td class="verde_claro bold">{{ $formulario->unidad->nombre }}</td>
                </tr>
            </tbody>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th rowspan="3" width="3%">Código de Operación(1)</th>
                <th rowspan="3">Operación (2)</th>
                <th rowspan="3" width="3%">Ponderación %</th>
                <th rowspan="3">Resultado intermedio(3)</th>
                <th rowspan="3">Medios de verificación(4)</th>
                <th rowspan="3" width="3%">Código Act. (5)</th>
                <th rowspan="3">Actividad/Tarea (6)</th>
                <th colspan="12">Programación de ejecución de opreaciones y actividades (7)</th>
                <th colspan="2">Fecha prevista de ejecución (8)</th>
            </tr>
            <tr>
                <th colspan="3">1er Trim.</th>
                <th colspan="3">2do Trim.</th>
                <th colspan="3">3er Trim.</th>
                <th colspan="3">4to Trim.</th>
                <th rowspan="2" width="5%">INICIO</th>
                <th rowspan="2" width="5%">FINAL</th>
            </tr>
            <tr>
                <th width="2%">E</th>
                <th width="2%">F</th>
                <th width="2%">M</th>
                <th width="2%">A</th>
                <th width="2%">M</th>
                <th width="2%">J</th>
                <th width="2%">J</th>
                <th width="2%">A</th>
                <th width="2%">S</th>
                <th width="2%">O</th>
                <th width="2%">N</th>
                <th width="2%">D</th>
            </tr>
        </thead>
        <tbody class="info_table">
            @php
                $cont = 1;
            @endphp
            @if ($formulario->detalle_formulario)
                @foreach ($formulario->detalle_formulario->operacions as $operacion)
                    @foreach ($operacion->detalle_operaciones as $key => $detalle_operacion)
                        @php
                            $total_filas = count($operacion->detalle_operaciones);
                            $numero_fila = round($total_filas / 2, 0);
                            $numero_fila--;
                        @endphp
                        @if ($key == $numero_fila)
                            <tr>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                    {{ $operacion->codigo_operacion }}</td>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                    {{ $operacion->operacion }}
                                </td>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                    {{ $operacion->ponderacion }}</td>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                    {{ $operacion->resultado_esperado }}</td>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                    {{ $operacion->medios_verificacion }}</td>
                                <td>{{ $detalle_operacion->codigo_tarea }}</td>
                                <td>{{ $detalle_operacion->actividad_tarea }}</td>
                                <td class="{{ $detalle_operacion->pt_e ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->pt_e }}</td>
                                <td class="{{ $detalle_operacion->pt_f ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->pt_f }}</td>
                                <td class="{{ $detalle_operacion->pt_m ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->pt_m }}</td>
                                <td class="{{ $detalle_operacion->st_a ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->st_a }}</td>
                                <td class="{{ $detalle_operacion->st_m ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->st_m }}</td>
                                <td class="{{ $detalle_operacion->st_j ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->st_j }}</td>
                                <td class="{{ $detalle_operacion->tt_j ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->tt_j }}</td>
                                <td class="{{ $detalle_operacion->tt_a ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->tt_a }}</td>
                                <td class="{{ $detalle_operacion->tt_s ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->tt_s }}</td>
                                <td class="{{ $detalle_operacion->ct_o ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->ct_o }}</td>
                                <td class="{{ $detalle_operacion->ct_n ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->ct_n }}</td>
                                <td class="{{ $detalle_operacion->ct_d ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->ct_d }}</td>
                                <td>{{ date('d/m/Y', strtotime($detalle_operacion->inicio)) }}</td>
                                <td>{{ date('d/m/Y', strtotime($detalle_operacion->final)) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                </td>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                </td>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                </td>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                </td>
                                <td
                                    class="{{ $key < count($operacion->detalle_operaciones) - 1 ? 'none-border-bot' : '' }}">
                                </td>
                                <td>{{ $detalle_operacion->codigo_tarea }}</td>
                                <td>{{ $detalle_operacion->actividad_tarea }}</td>
                                <td class="{{ $detalle_operacion->pt_e ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->pt_e }}</td>
                                <td class="{{ $detalle_operacion->pt_f ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->pt_f }}</td>
                                <td class="{{ $detalle_operacion->pt_m ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->pt_m }}</td>
                                <td class="{{ $detalle_operacion->st_a ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->st_a }}</td>
                                <td class="{{ $detalle_operacion->st_m ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->st_m }}</td>
                                <td class="{{ $detalle_operacion->st_j ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->st_j }}</td>
                                <td class="{{ $detalle_operacion->tt_j ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->tt_j }}</td>
                                <td class="{{ $detalle_operacion->tt_a ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->tt_a }}</td>
                                <td class="{{ $detalle_operacion->tt_s ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->tt_s }}</td>
                                <td class="{{ $detalle_operacion->ct_o ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->ct_o }}</td>
                                <td class="{{ $detalle_operacion->ct_n ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->ct_n }}</td>
                                <td class="{{ $detalle_operacion->ct_d ? 'beis bold' : '' }}">
                                    {{ $detalle_operacion->ct_d }}</td>
                                <td>{{ date('d/m/Y', strtotime($detalle_operacion->inicio)) }}</td>
                                <td>{{ date('d/m/Y', strtotime($detalle_operacion->final)) }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            @endif
        </tbody>
    </table>
    <table border="1">
        <tbody>
            <tr>
                <td class="celeste bold" width="10%"></td>
                <td class="celeste bold">ELABORADO POR:</td>
                <td class="celeste bold">REVISADO POR:</td>
                <td class="celeste bold">APROBADO</td>
            </tr>
            <tr>
                <td class="bold" height="25px">FIRMA</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="bold" height="25px">NOMBRE</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="bold" height="25px">CARGO</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    @php
        $meses_text = [
            '01' => 'enero',
            '02' => 'febrero',
            '03' => 'marzo',
            '04' => 'abril',
            '05' => 'mayo',
            '06' => 'junio',
            '07' => 'julio',
            '08' => 'agosto',
            '09' => 'septiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'diciembre',
        ];

        $fecha = 'La Paz ' . date('d') . ' de ' . $meses_text[date('m')] . ' de ' . date('Y');
    @endphp
    <table>
        <tr>
            <td style="text-align:right; font-size:0.85em;">{{ $fecha }}</td>
        </tr>
    </table>
</body>

</html>
