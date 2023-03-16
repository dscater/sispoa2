<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario 5</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1.5cm;
            margin-bottom: 0.7cm;
            margin-left: 0.7cm;
            margin-right: 0.7cm;
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
            font-size: 11pt;
        }

        .texto {
            width: 600px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 0.9em;
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

        table tbody tr {
            /* page-break-before: avoid; */
        }

        table thead {
            background: #0062A5;
            color: white;
        }

        table thead tr th {
            padding: 3px;
            font-size: 0.7em;
            word-wrap: break-word;
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

        table.tabla_detalle thead tr th {
            font-size: 0.6em;
        }

        table.tabla_detalle tbody tr td {
            font-size: 0.5em;
        }

        .cabecera th {
            background: #0062A5;
            color: white;
            padding: 3px;
            font-size: 0.7rem !important;
            text-align: center;
            border: solid 1px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @inject('o_certificacion', 'App\Models\Certificacion')
    @inject('o_formulario_cinco_controller', 'App\Http\Controllers\FormularioCincoController')
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
        <h4 class="texto">FORMULARIO 5</h4>
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
                    <td class="verde_claro bold">{{ $formulario->codigo_pei }}</td>
                </tr>
                <tr>
                    <td class="azul bold">OBJETIVO ESTRATÉGICO INSTITUCIONAL</td>
                    <td class="verde_claro bold">{{ $formulario->meta }}</td>
                </tr>
                <tr>
                    <td class="azul bold">INDICADOR</td>
                    <td class="verde_claro bold">{{ $formulario->indicador }}</td>
                </tr>
                <tr>
                    <td class="azul bold">CÓDIGO POA</td>
                    <td class="verde_claro bold">{{ $formulario->codigo_poa }}</td>
                </tr>
                <tr>
                    <td class="azul bold">ACCIÓN DE CORTO PLAZO DE GESTIÓN</td>
                    <td class="verde_claro bold">{{ $formulario->accion_corto }}</td>
                </tr>
                <tr>
                    <td class="azul bold">RESULTADO ESPERADO GESTIÓN</td>
                    <td class="verde_claro bold">{{ $formulario->resultado_institucional }}</td>
                </tr>
                <tr>
                    <td class="azul bold">PRESUPUESTO PROGRAMADO GESTIÓN</td>
                    <td class="verde_claro bold">{{ number_format($formulario->presupuesto, 2) }}</td>
                </tr>
                <tr>
                    <td class="azul bold">PONDERACIÓN %</td>
                    <td class="verde_claro bold">{{ number_format($formulario->ponderacion, 2) }}</td>
                </tr>
                <tr>
                    <td class="azul bold">UNIDAD ORGANIZACIONAL</td>
                    <td class="verde_claro bold">{{ $formulario->unidad->nombre }}</td>
                </tr>
            </tbody>
        </tr>
    </table>
    @php
        $tabla = '<p class="centreado">SIN REGISTROS</p>';
        if ($formulario->memoria_calculo) {
            $formulario_cinco = $formulario->memoria_calculo->formulario_cinco;
            $array_registros = $o_formulario_cinco_controller::armaRepetidos($formulario_cinco);
            $tabla = view('parcial.formulario_cinco2', compact('array_registros', 'formulario_cinco'))->render();
        }
    @endphp
    {!! $tabla !!}
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
