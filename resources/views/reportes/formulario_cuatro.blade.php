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
            margin-top: 2cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
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
            width: 450px;
            margin: auto;
            margin-top: 15px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .fecha {
            width: 250px;
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

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
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
        @if (Auth::user()->tipo != 'SUPER USUARIO' || $filtro == 'Unidad Organizacional')
            @if ($filtro == 'Unidad Organizacional')
                <h4 class="fecha">{{ $unidad->nombre }}</h4>
            @else
                <h4 class="fecha">{{ Auth::user()->unidad->nombre }}</h4>
            @endif
        @endif
    </div>
    <table border="1">
        <thead>
            <tr>
                <th width="7%">Código PEI</th>
                <th>Acción Institucional específica</th>
                <th>Indicador de Proceso</th>
                <th>Código POA</th>
                <th>Acción de Corto Plazo de Gestión</th>
                <th>Resultado Esperado Gestión</th>
                <th>Presupuesto Programado Gestión</th>
                <th>Ponderación %</th>
                <th>Unidad Organizacional</th>
                <th width="6%">Fecha de Registro</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @foreach ($formularios as $formulario)
                <tr>
                    <td class="centreado">{!! str_replace(',', '<br>', $formulario->codigo_pei) !!}</td>
                    <td>{{ $formulario->accion_institucional }}</td>
                    <td>{{ $formulario->indicador }}</td>
                    <td class="centreado">{{ $formulario->codigo_poa }}</td>
                    <td>{{ $formulario->accion_corto }}</td>
                    <td>{{ $formulario->resultado_esperado }}</td>
                    <td class="centreado">{{ $formulario->presupuesto }}</td>
                    <td class="centreado">{{ $formulario->ponderacion }}%</td>
                    <td>{{ $formulario->unidad->nombre }}</td>
                    <td class="centreado">{{ date('d/m/Y', strtotime($formulario->fecha_registro)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
