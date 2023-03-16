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

        .logo2 {
            position: absolute;
            top: -20px;
            right: 0;
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

        .salto_linea {
            page-break-after: always;
        }

        .titulo2 {
            width: 100%;
            font-weight: bold;
            font-size: 1.5rem;
            text-align: center;
            padding: 3px;
            left: 35%;
            top: 60px;
            margin-top: -10px;
        }
    </style>
</head>

<body>
    @php
        $contador = 0;
    @endphp
    @inject('configuracion', 'App\Models\Configuracion')
    @inject('o_certificacion_detalles', 'App\Models\CertificacionDetalle')
    @foreach ($formularios as $formulario)
        <img class="logo" src="{{ asset('imgs/' . $configuracion->first()->logo2) }}" alt="Logo">
        <img class="logo2" src="{{ asset('imgs/' . $configuracion->first()->logo) }}" alt="Logo">
        <div class="titulo">EJECUCIÓN DE PRESUPUESTO<br />GESTIÓN {{ date('Y') }}</div>
        @if (Auth::user()->tipo != 'SUPER USUARIO' || $filtro == 'Unidad Organizacional')
            @if ($filtro == 'Unidad Organizacional')
                <h4 class="titulo2">{{ $unidad->nombre }}</h4>
            @else
                <h4 class="titulo2">{{ Auth::user()->unidad->nombre }}</h4>
            @endif
        @endif
        <table border="1" class="collapse">
            <tbody>
                <tr class="bg_principal">
                    <td class="bold p-5" width="15%">Código PEI:</td>
                    <td class="bold p-5">{!! str_replace(',', '<br>', $formulario->codigo_pei) !!}</td>
                    <td class="bold p-5" width="15%">Presupuesto programado:</td>
                    <td class="bold p-5">{{ number_format($formulario->presupuesto, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="collapse tabla_detalle" border="1">
            <thead>
                <tr>
                    <th rowspan="2" width="5%">
                        Código tarea
                    </th>
                    <th rowspan="2">
                        Actividad/Tareas
                    </th>
                    <th rowspan="2">Partida</th>
                    <th rowspan="2">Descripción</th>
                    <th colspan="3">
                        Presupuesto
                    </th>
                    <th colspan="2">
                        Ejecutado
                    </th>
                    <th rowspan="2">Saldo</th>
                </tr>
                <tr>
                    <th>Cantidad</th>
                    <th>Costo Unitario</th>
                    <th>PRESUPUESTO VIGENTE</th>
                    <th>Cantidad</th>
                    <th>PRESUPUESTO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $suma_ejecutados = 0;
                    $suma_saldos = 0;
                @endphp
                @if ($formulario->memoria_calculo)
                    @foreach ($formulario->memoria_calculo->operacions as $operacion)
                        @foreach ($operacion->memoria_operacion_detalles as $mod)
                            @php
                                $cantidad_usado = $o_certificacion_detalles
                                    ->join('certificacions', 'certificacions.id', '=', 'certificacion_detalles.certificacion_id')
                                    ->where('anulado', 0)
                                    ->where('mo_id', $operacion->id)
                                    ->where('mod_id', $mod->id)
                                    ->sum('cantidad_usar');
                                $total_usado = $o_certificacion_detalles
                                    ->join('certificacions', 'certificacions.id', '=', 'certificacion_detalles.certificacion_id')
                                    ->where('anulado', 0)
                                    ->where('mo_id', $operacion->id)
                                    ->where('mod_id', $mod->id)
                                    ->sum('presupuesto_usarse');
                                $saldo = (float) $mod->total - (float) $total_usado;
                                $muestra_fila = true;
                            @endphp
                            @if ($filtro2 != 'Todos')
                                @php
                                    $muestra_fila = false;
                                    if ($saldo == 0) {
                                        $muestra_fila = true;
                                    }
                                @endphp
                            @endif

                            @if ($muestra_fila)
                                <tr>
                                    <td class="centreado">
                                        {{ $operacion->codigo_actividad }}
                                    </td>
                                    <td>{{ $operacion->descripcion_actividad }}</td>
                                    <td class="centreado">{{ $mod->m_partida->partida }}</td>
                                    <td class="centreado">{{ $mod->m_partida->descripcion }}</td>
                                    <td class="centreado">{{ $mod->cantidad }}</td>
                                    <td class="centreado">{{ $mod->costo }}</td>
                                    <td class="centreado">{{ $mod->total }}</td>
                                    <td class="centreado">{{ $cantidad_usado }}</td>
                                    <td class="centreado">{{ $total_usado }}</td>
                                    <td class="centreado">{{ $saldo }}</td>
                                </tr>
                            @endif
                            @php
                                if ($muestra_fila) {
                                    $suma_ejecutados += $total_usado;
                                    $suma_saldos += $saldo;
                                }
                            @endphp
                        @endforeach
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="centreado">AÚN NO SE REALIZO UN PRESUPUESTO</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">TOTAL</th>
                    @if ($formulario->memoria_calculo && $filtro2 == 'Todos')
                        <th>{{ number_format($formulario->memoria_calculo->total_final, 2) }}</th>
                    @elseif($formulario->memoria_calculo)
                        <th>{{ number_format($suma_ejecutados, 2) }}</th>
                    @else
                        <th>0.00</th>
                    @endif
                    <th></th>
                    <th>{{ number_format($suma_ejecutados, 2) }}</th>
                    <th>{{ number_format($suma_saldos, 2) }}</th>
                </tr>
            </tfoot>
        </table>
        @php
            $contador++;
        @endphp
        @if ($contador < count($formularios))
            <div class="salto_linea"></div>
        @endif
    @endforeach
</body>

</html>
