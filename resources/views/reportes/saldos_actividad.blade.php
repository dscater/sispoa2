<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>SaldosOperacion</title>
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
            width: 500px;
            font-weight: bold;
            font-size: 1.5rem;
            text-align: center;
            padding: 3px;
            left: 35%;
            top: 20px;
        }

        .titulo2 {
            position: absolute;
            width: 500px;
            font-weight: bold;
            font-size: 1.5rem;
            text-align: center;
            padding: 3px;
            left: 35%;
            top: 60px;
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
    </style>
</head>

<body>
    @php
        $contador = 0;
    @endphp
    @inject('configuracion', 'App\Models\Configuracion')
    @inject('o_certificacion_detalles', 'App\Models\CertificacionDetalle')
    <img class="logo" src="{{ asset('imgs/' . $configuracion->first()->logo2) }}" alt="Logo">
    <img class="logo2" src="{{ asset('imgs/' . $configuracion->first()->logo) }}" alt="Logo">
    <div class="titulo">SALDOS DE PRESUPUESTOS POR OPERACIÓN<br />GESTIÓN {{ date('Y') }}</div>
    <div class="titulo2">{{ $unidad->nombre }}</div>
    <table border="1" class="collapse" style="margin-top:80px;">
        <tbody>
            <tr class="bg_principal">
                <td class="bold p-5" width="10%">Código PEI:</td>
                <td class="bold p-5">{!! str_replace(',', '<br>', $formulario->codigo_pei) !!}</td>
            </tr>
            <tr>
                <td class="bold p-5">Código operación:</td>
                <td class="bold p-5">{{ $operacion->codigo_operacion }}</td>
            </tr>
            <tr>
                <td class="bold p-5">Descripción:</td>
                <td class="bold p-5">{{ $operacion->operacion }}</td>
            </tr>
            {{-- <td class="bold p-5">Actividad:</td>
            <td class="bold p-5">{{ $actividad->actividad_tarea }}</td>
            </tr> --}}
        </tbody>
    </table>

    <table class="collapse tabla_detalle" border="1">
        <thead>
            <tr>
                <th>Partida</th>
                <th>Descripción</th>
                <th>Presupuesto programado</th>
                <th>Presupuesto certificado</th>
                <th>Saldo con reversión</th>
            </tr>
        </thead>
        <tbody>
            @php
                $suma_ejecutados = 0;
                $suma_saldos = 0;
            @endphp
            @php
                $memoria_calculos = App\Models\MemoriaCalculo::select('memoria_calculos.*')
                    ->join('memoria_operacions', 'memoria_operacions.memoria_id', '=', 'memoria_calculos.id')
                    ->where('formulario_id', $formulario->id)
                    ->where('memoria_operacions.operacion_id', $operacion->id)
                    ->get();
                // $existe = $actividad->operacion->detalle_formulario->formulario->memoria_calculo
                // ->operacions()
                // ->where('detalle_operacion_id', $actividad->id)
                // ->get();
            @endphp
            @if (count($memoria_calculos) > 0)
                @php
                    $suma_vigente = 0;
                    $suma_ejecutados = 0;
                    $suma_saldos = 0;
                @endphp
                @foreach ($memoria_calculos as $memoria)
                    @foreach ($memoria->operacions as $mo)
                        @foreach ($mo->memoria_operacion_detalles as $mod)
                            <tr>
                                <td class="centreado">{{ $mod->m_partida->partida }}</td>
                                <td class="">{{ $mod->m_partida->descripcion }}</td>
                                <td class="centreado">{{ $mod->total }}</td>
                                @php
                                    $cantidad_usado = $o_certificacion_detalles
                                        ->join(
                                            'certificacions',
                                            'certificacions.id',
                                            '=',
                                            'certificacion_detalles.certificacion_id',
                                        )
                                        ->where('certificacion_detalles.mo_id', $operacion->id)
                                        ->where('mod_id', $mod->id)
                                        ->where('anulado', 0)
                                        ->sum('cantidad_usar');
                                    $total_usado = $o_certificacion_detalles
                                        ->join(
                                            'certificacions',
                                            'certificacions.id',
                                            '=',
                                            'certificacion_detalles.certificacion_id',
                                        )
                                        ->where('certificacion_detalles.mo_id', $operacion->id)
                                        ->where('mod_id', $mod->id)
                                        ->where('anulado', 0)
                                        ->sum('presupuesto_usarse');
                                    $saldo = (float) $mod->total - (float) $total_usado;

                                    $suma_vigente += (float) $mod->total;
                                @endphp
                                <td class="centreado">{{ number_format($total_usado, 2) }}</td>
                                <td class="centreado">{{ number_format($saldo, 2) }}</td>
                            </tr>
                            @php
                                $suma_ejecutados += $total_usado;
                                $suma_saldos += $saldo;
                            @endphp
                        @endforeach
                    @endforeach
                @endforeach
                <tr>
                    <td class="centreado bold" colspan="2">TOTAL</td>
                    <td class="centreado bold">{{ number_format($suma_vigente, 2) }}</td>
                    <td class="centreado bold">{{ number_format($suma_ejecutados, 2) }}</td>
                    <td class="centreado bold">{{ number_format($suma_saldos, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="6" class="centreado">NO SE ENCONTRARÓN REGISTROS</td>
                </tr>
            @endif

        </tbody>
    </table>
</body>

</html>
