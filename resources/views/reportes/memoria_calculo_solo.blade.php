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
        <h4 class="texto">Formulario 9</h4>
        <h4 class="texto">Memorias de Cálculo</h4>
        <h4 class="texto">MEMORIAS DE CÁLCULO POR CÓDIGO POA Y PARTIDA DE GASTO - GESTIÓN {{ date('Y') }}</h4>
        <h4 class="fecha">(Expresado en Bolivianos)</h4>
        @if ($formulario->unidad)
            <h4 class="fecha">{{ $formulario->unidad->nombre }}</h4>
        @endif
    </div>
    <table border="1">
        <tr>
            <tbody>
                <tr>
                    <td></td>
                    <td>CÓDIGO</td>
                    <td>DESCRIPCIÓN</td>
                </tr>
                <tr>
                    <td class="azul bold" width="15%">ENTIDAD:</td>
                    <td>385</td>
                    <td class="verde_claro bold">AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO - ASSUS
                    </td>
                </tr>
                <tr>
                    <td class="azul bold">UNIDAD ORGANIZACIONAL:</td>
                    <td></td>
                    <td class="verde_claro bold">{{ $formulario->unidad->nombre }}</td>
                </tr>
                <tr>
                    <td class="azul bold">DIRECCIÓN ADMINISTRATIVA:</td>
                    <td>1</td>
                    <td class="verde_claro bold">DIRECCIÓN ADMINISTRATIVA FINANCIERA</td>
                </tr>
                <tr>
                    <td class="azul bold">FUENTE:</td>
                    <td>42</td>
                    <td class="verde_claro bold">TRANSFERENCIAS DE RECURSOS ESPECÍFICOS</td>
                </tr>
                <tr>
                    <td class="azul bold">ORGANISMO FINANCIADOR:</td>
                    <td class="verde_claro bold">OTROS RECURSOS ESPECÍFICOS</td>
                </tr>
                <tr>
                    <td class="azul bold">CODIGO POA:</td>
                    <td class="verde_claro bold">{{ $formulario->codigo_poa_full }}</td>
                    <td class="verde_claro bold">{{ $formulario->accion_corto }}</td>
                </tr>
                <tr>
                    <td class="azul bold">UNIDAD EJECUTORA:
                    </td>
                    <td>1</td>
                    <td class="verde_claro bold">ADMINISTRACIÓN CENTRAL</td>
                </tr>
                <tr>
                    <td class="azul bold">PROGRAMA:</td>
                    <td>1</td>
                    <td class="verde_claro bold">ADMINISTRACIÓN Y GESTIÓN INSTITUCIONAL</td>
                </tr>
                <tr>
                    <td class="azul bold">ACTIVIDAD:
                    </td>
                    <td>1</td>
                    <td class="verde_claro bold">GESTIÓN EJECUTIVA</td>
                </tr>
            </tbody>
        </tr>
    </table>
    <table border="1">
        <thead>
            <tr>
                <th width="1%" rowspan="2" width="3%">UNIDAD EJECUTORA</th>
                <th width="1%" colspan="2">PRESUPUESTOS</th>
                <th width="1%" colspan="2">POA</th>
                <th rowspan="2">PARTIDA DE GASTO</th>
                <th rowspan="2">DESCRIPCIÓN</th>
                <th rowspan="2">N°</th>
                <th rowspan="2">DESCRIPCIÓN DETALLADA POR ITEM(BIEN O SERVICIO)</th>
                <th rowspan="2">CANTIDAD REQUERIDA</th>
                <th rowspan="2">UNIDAD</th>
                <th rowspan="2">PRECIO UNITARIO</th>
                <th rowspan="2">TOTAL</th>
                <th rowspan="2">JUSTIFICACIÓN</th>
                <th colspan="12">PROGRAMACIÓN MENSUAL</th>
                <th rowspan="2">TOTAL</th>
            </tr>
            <tr>
                <th>PROGRAMA</th>
                <th>ACTIVIDAD</th>
                <th>COD. OPERACIÓN</th>
                <th>COD. ACT./TAREA</th>
                <th width="4%">E</th>
                <th width="4%">F</th>
                <th width="4%">M</th>
                <th width="4%">A</th>
                <th width="4%">M</th>
                <th width="4%">J</th>
                <th width="4%">J</th>
                <th width="4%">A</th>
                <th width="4%">S</th>
                <th width="4%">O</th>
                <th width="4%">N</th>
                <th width="4%">D</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @foreach ($memoria_calculo->operacions as $index => $operacion)
                @if ($operacion->operacion->subdireccion)
                    <tr>
                        <td colspan="27">{{ $operacion->operacion->subdireccion->nombre }}</td>
                    </tr>
                @endif
                @foreach ($operacion->memoria_operacion_detalles as $mod)
                    <tr>
                        <td>{{ $mod->ue }}</td>
                        <td>{{ $mod->prog }}</td>
                        <td>{{ $mod->act }}</td>
                        <td>{{ $operacion->codigo_operacion }}</td>
                        <td>{{ $operacion->codigo_actividad }}</td>
                        <td>{{ $mod->partida }}</td>
                        <td>{{ $mod->descripcion }}</td>
                        <td>{{ $mod->nro }}</td>
                        <td>{{ $mod->descripcion_detallada }}</td>
                        <td>{{ $mod->cantidad }}</td>
                        <td>{{ $mod->unidad }}</td>
                        <td>{{ number_format($mod->costo, 2) }}</td>
                        <td>{{ number_format($mod->total, 2) }}</td>
                        <td>{{ $mod->justificacion }}</td>
                        <td>{{ number_format($mod->ene, 2) }}</td>
                        <td>{{ number_format($mod->feb, 2) }}</td>
                        <td>{{ number_format($mod->mar, 2) }}</td>
                        <td>{{ number_format($mod->abr, 2) }}</td>
                        <td>{{ number_format($mod->may, 2) }}</td>
                        <td>{{ number_format($mod->jun, 2) }}</td>
                        <td>{{ number_format($mod->jul, 2) }}</td>
                        <td>{{ number_format($mod->ago, 2) }}</td>
                        <td>{{ number_format($mod->sep, 2) }}</td>
                        <td>{{ number_format($mod->oct, 2) }}</td>
                        <td>{{ number_format($mod->nov, 2) }}</td>
                        <td>{{ number_format($mod->dic, 2) }}</td>
                        <td>{{ number_format($mod->total_actividad, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td colspan="9">TOTAL</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ number_format($memoria_calculo->total_actividades, 2) }}</td>
                <td></td>
                <td>{{ number_format($memoria_calculo->total_ene, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_feb, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_mar, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_abr, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_may, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_jun, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_jul, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_ago, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_sep, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_oct, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_nov, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_dic, 2) }}</td>
                <td>{{ number_format($memoria_calculo->total_final, 2) }}</td>
            </tr>
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
