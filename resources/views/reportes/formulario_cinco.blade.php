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

        table thead {
            background: #0062A5;
            color: white;
            word-wrap: break-word;
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

        .border_top {
            border-top: solid 0.2px black;
        }

        .border_bottom {
            border-bottom: solid 0.2px black;
        }

        .border_right {
            border-right: solid 0.2px black;
        }

        .border_left {
            border-left: solid 0.2px black;
        }

        .salto_linea{
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        $contador = 0;
    @endphp
    @inject('configuracion', 'App\Models\Configuracion')
    @inject('o_certificacion', 'App\Models\Certificacion')
    @inject('o_verificacion_actividad', 'App\Models\VerificacionActividad')
    @inject('o_formulario_cinco_controller', 'App\Http\Controllers\FormularioCincoController')
    @foreach ($memoria_calculos as $memoria_calculo)
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
            @if (Auth::user()->tipo != 'SUPER USUARIO' || $filtro == 'Unidad Organizacional')
                @if ($filtro == 'Unidad Organizacional')
                    <h4 class="fecha">{{ $unidad->nombre }}</h4>
                @else
                    <h4 class="fecha">{{ Auth::user()->unidad->nombre }}</h4>
                @endif
            @endif
        </div>
        <table border="1" class="collapse">
            <tbody>
                <tr class="bg_principal">
                    <td class="bold p-5" width="10%">Código PEI:</td>
                    <td class="bold p-5">{!! str_replace(',', '<br>', $memoria_calculo->pei_text) !!}</td>
                    <td class="bold p-5" width="15%">Presupuesto programado:</td>
                    <td class="bold p-5">{{ number_format($memoria_calculo->formulario->presupuesto, 2) }}</td>
                </tr>
                <tr>
                    <td class="bold p-5">Unidad:</td>
                    <td class="bold p-5" colspan="3">{{ $memoria_calculo->formulario->unidad->nombre }}</td>
                </tr>
            </tbody>
        </table>
        @php
            $tabla = '<p class="centreado">SIN REGISTROS</p>';
            $formulario_cinco = $memoria_calculo->formulario_cinco;
            $array_registros = $o_formulario_cinco_controller::armaRepetidos($formulario_cinco);
            $verificacion_actividad = $o_verificacion_actividad::get()->first();
            $tabla = view('parcial.formulario_cinco2', compact('array_registros', 'formulario_cinco', 'verificacion_actividad'))->render();
        @endphp
        {!! $tabla !!}
        @php
            $contador++;
        @endphp
        @if ($contador < count($memoria_calculos))
            <div class="salto_linea"></div>
        @endif
    @endforeach
</body>

</html>
