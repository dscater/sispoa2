<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>EjecucionPresupuestos</title>
    <style type="text/css">
        * {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        @page {
            margin-top: 0.3cm;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 1cm;
            font-size: 9pt;
        }

        .logo {
            top: 0;
            left: 0;
            height: 100px;
            width: 200px;
        }

        .titulo {
            position: absolute;
            border: solid 1px;
            width: 350px;
            font-weight: bold;
            font-size: 1.5rem;
            text-align: center;
            padding: 3px;
            left: 35%;
            top: 20px;
        }

        .correlativo {
            width: 120px;
            position: absolute;
            right: 0px;
            top: 10px;
        }

        .nro {
            border: solid 1px;
            padding: 4px;
        }

        .gray {
            background: #F2F2F2;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            margin: auto;
        }

        .solicitante {
            border-collapse: collapse;
        }

        .solicitante tbody tr td {
            padding: 4px;
        }

        .centreado {
            text-align: center;
        }

        .border {
            border: solid 1px black;
        }

        .firma {
            height: 45px;
            width: 80%;
            margin: auto;
            border: solid 1px;
        }

        .p-0 {
            padding: 0px;
        }

        .p-5 {
            padding: 5px;
        }

        .datos_solicitante {
            width: 48%;
            margin-left: 0px;
            margin-top: 15px;
        }

        .datos_superior {
            position: absolute;
            width: 48%;
            margin-right: 0px;
            top: 147px;
        }

        .bg_principal {
            background: #0062A5;
            color: white;
        }

        .sigep {
            border-collapse: collapse;
            width: 35%;
            margin-left: 0px;
            margin-top: 15px;
        }

        .sigep tbody tr td {
            padding: 5px;
        }

        .inicio,
        .final {
            position: absolute;
            width: 20%;
            border-collapse: collapse;
        }

        .inicio {
            top: 350px;
            right: -100px;
        }

        .final {
            top: 350px;
            right: -250px;
        }

        .collapse {
            border-collapse: collapse;
        }

        .text_right {
            text-align: right;
        }

        .texto_unidad {
            width: 50%;
            margin-top: 55px;
            margin-left: auto;
            text-align: right;
        }

        .tabla_detalle thead tr th,
        .tabla_detalle tbody tr td {
            font-size: 0.7rem;
        }

        .tabla_detalle tbody tr td {
            padding: 2px;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @foreach ($formularios as $formulario)
        <img class="logo" src="{{ asset('imgs/' . $configuracion->first()->logo) }}" alt="Logo">
        <div class="titulo">EJECUCIÓN DE PRESUPUESTO<br />GESTIÓN {{ date('Y') }}</div>
        <table border="1" class="collapse">
            <tbody>
                <tr class="bg_principal">
                    <td class="bold p-5" width="15%">Código PEI:</td>
                    <td class="bold p-5">{{ $formulario->codigo_pei }}</td>
                    <td class="bold p-5" width="15%">Presupuesto programado:</td>
                    <td class="bold p-5">{{ number_format($formulario->presupuesto, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="collapse tabla_detalle" border="1">
            <thead>
                <tr>
                    <th colspan="17">
                        PLAN OPERATIVO ANUAL GESTIÓN
                        2022
                    </th>
                </tr>
                <tr>
                    <th rowspan="3" width="2%">
                        Código Operación(1)
                    </th>
                    <th rowspan="3">
                        Operación(2)
                    </th>
                    <th rowspan="3">
                        Código tarea(3)
                    </th>
                    <th rowspan="3">
                        Actividad/Tareas(4)
                    </th>
                    <th rowspan="3">
                        Lugar de ejecución de la
                        Operación(5)
                    </th>
                    <th rowspan="3">
                        Responsable de ejecución de
                        la Operación/Tarea (6)
                    </th>
                    <th colspan="11">
                        Desglose Presupuestario
                    </th>
                </tr>
                <tr>
                    <th rowspan="2">Partida(7)</th>
                    <th rowspan="2">
                        Descripción(8)
                    </th>
                    <th rowspan="2">Cantidad(9)</th>
                    <th rowspan="2">Unida(10)</th>
                    <th rowspan="2">
                        Costo Unitario(11)
                    </th>
                    <th colspan="4">
                        Recursos Internos(12)
                    </th>
                    <th>Recursos externos(13)</th>
                    <th rowspan="2">
                        TOTAL (por Operación)(14)
                    </th>
                </tr>
                <tr>
                    <th>TRANSF 42</th>
                    <th>UE</th>
                    <th>PROG</th>
                    <th>ACT</th>
                    <th>OTROS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tarea_actual = 0;
                    $muestra = true;
                @endphp
                @foreach ($formulario->formulario_cinco->operacions as $index_operacion => $operacion)
                    @foreach ($operacion->lugar_responsables as $index_lugar => $lugar)
                        @foreach ($lugar->partidas as $index_partida => $partida)
                            <tr>
                                @if ($index_lugar == 0 && $index_partida == 0)
                                    <td class="centreado" rowspan="{{ $operacion->total_partidas }}">
                                        {{ $operacion->operacion->codigo_operacion }}
                                    </td>
                                    <td rowspan="{{ $operacion->total_partidas }}">
                                        {{ $operacion->operacion->operacion }}
                                    </td>
                                @endif
                                {{-- VERIFICAR TAREA --}}
                                @php
                                    if ($partida->actividad_tarea_id == $tarea_actual) {
                                        $muestra = false;
                                    } else {
                                        $tarea_actual = $partida->actividad_tarea_id;
                                        $muestra = true;
                                    }
                                @endphp
                                @if ($muestra)
                                    <td rowspan="{{ $partida->total_por_actividad }}">
                                        {{ $partida->actividad_tarea->detalle_operacion->codigo_tarea }}</td>
                                    <td rowspan="{{ $partida->total_por_actividad }}">
                                        {{ $partida->actividad_tarea->descripcion }}</td>
                                @endif
                                @if ($index_partida == 0)
                                    <td rowspan="{{ count($lugar->partidas) }}">
                                        {{ $lugar->lugar }}
                                    </td>
                                    <td rowspan="{{ count($lugar->partidas) }}">
                                        {{ $lugar->responsable }}
                                    </td>
                                @endif
                                <td>{{ $partida->partida }}</td>
                                <td>{{ $partida->descripcion }}</td>
                                <td>{{ $partida->cantidad }}</td>
                                <td>{{ $partida->unidad }}</td>
                                <td>{{ $partida->costo }}</td>
                                <td>{{ $partida->t42 }}</td>
                                <td>{{ $partida->ue }}</td>
                                <td>{{ $partida->prog }}</td>
                                <td>{{ $partida->act }}</td>
                                <td>{{ $partida->otros }}</td>
                                @if ($index_lugar == 0 && $index_partida == 0)
                                    <td rowspan="{{ $operacion->total_partidas }}">
                                        {{ number_format($operacion->total_operacion, 2) }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="16">TOTAL</th>
                    <th>{{ number_format($formulario->formulario_cinco->total_formulario, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    @endforeach
</body>

</html>
