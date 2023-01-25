<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario 5</title>
    <style type="text/css">
        * {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        @page {
            margin-top: 1.5cm;
            margin-bottom: 1cm;
            margin-left: 1cm;
            margin-right: 1cm;
            font-size: 9pt;
        }

        .logo {
            top: 0;
            left: 0;
            height: 100px;
            width: 200px;
        }

        .fondo_rojo {
            background: #DC3545;
            color: white;
        }

        .titulo {
            position: absolute;
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

        .salto_linea {
            page-break-after: always;
        }

        .tabla_detalle {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    @php
        $contador = 0;
    @endphp
    @inject('configuracion', 'App\Models\Configuracion')
    @inject('o_certificacion', 'App\Models\Certificacion')
    @inject('o_formulario_cinco_controller', 'App\Http\Controllers\FormularioCincoController')
    @foreach ($formularios as $formulario)
        <img class="logo" src="{{ asset('imgs/' . $configuracion->first()->logo) }}" alt="Logo">
        <div class="titulo">FORMULARIO 5<br />GESTIÓN {{ date('Y') }}</div>
        <table border="1" class="collapse">
            <tbody>
                <tr class="bg_principal">
                    <td class="bold p-5" width="10%">Código PEI:</td>
                    <td class="bold p-5">{!! str_replace(',', '<br>', $formulario->codigo_pei) !!}</td>
                    <td class="bold p-5" width="15%">Presupuesto programado:</td>
                    <td class="bold p-5">{{ number_format($formulario->presupuesto, 2) }}</td>
                </tr>
                <tr>
                    <td class="bold p-5">Unidad:</td>
                    <td class="bold p-5" colspan="3">{{ $formulario->unidad->nombre }}</td>
                </tr>
            </tbody>
        </table>
        @php
            $tabla = '<p class="centreado">SIN REGISTROS</p>';
            if ($formulario->memoria_calculo) {
                $formulario_cinco = $formulario->memoria_calculo->formulario_cinco;
                $array_registros = $o_formulario_cinco_controller::armaRepetidos($formulario_cinco);
                $tabla = view('reportes.parcial.formulario_cinco', compact('array_registros', 'formulario_cinco'))->render();
            }
        @endphp
        {!! $tabla !!}
        {{-- <table class="tabla_detalle" border="1">
            <thead class="bg-primary">
                <tr>
                    <th colspan="17">
                        PLAN OPERATIVO ANUAL GESTIÓN
                        2022
                    </th>
                </tr>
                <tr>
                    <th rowspan="3">
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
                    <th>PRESUPUESTO VIGENTE</th>
                    <th>UE</th>
                    <th>PROG</th>
                    <th>ACT</th>
                    <th>OTROS</th>
                </tr>
            </thead>
            <tbody>
                @if ($formulario->memoria_calculo)
                    @foreach ($formulario->memoria_calculo->operacions as $operacion)
                        <tr>
                            <td>
                                {{ $operacion->codigo_operacion }}
                            </td>
                            <td>
                                {{ $operacion->descripcion_operacion }}
                            </td>
                            <td>
                                {{ $operacion->codigo_actividad }}
                            </td>
                            <td>
                                {{ $operacion->descripcion_actividad }}
                            </td>
                            <td>
                                {{ $operacion->lugar }}
                            </td>
                            <td>
                                {{ $operacion->responsable }}
                            </td>
                            <td>
                                {{ $operacion->partida }}
                            </td>
                            <td>
                                {{ $operacion->descripcion }}
                            </td>
                            <td>
                                {{ $operacion->cantidad }}
                            </td>
                            <td>
                                {{ $operacion->unidad }}
                            </td>
                            <td>
                                {{ $operacion->costo }}
                            </td>
                            <td>
                                {{ $operacion->total }}
                            </td>
                            <td>
                                {{ $operacion->ue }}
                            </td>
                            <td>
                                {{ $operacion->prog }}
                            </td>
                            <td>
                                {{ $operacion->act }}
                            </td>
                            <td>
                                {{ $operacion->justificacion }}
                            </td>
                            <td>
                                {{ $operacion->total_operacion }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-primary">
                        <th colspan="16">TOTAL</th>
                        <th>
                            {{ $formulario->memoria_calculo->total_final }}
                        </th>
                    </tr>
                @else
                    <tr>
                        <td colspan="17" class="centreado">NO SE ENCONTRARON REGISTROS</td>
                    </tr>
                @endif
            </tbody>
        </table> --}}
        @php
            $contador++;
        @endphp
        @if ($contador < count($formularios))
            <div class="salto_linea"></div>
        @endif
    @endforeach
</body>

</html>
