<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Certificación</title>
    <style type="text/css">
        * {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        @page {
            margin-top: 0.3cm;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 1cm;
            font-size: 7.5pt;
        }

        .logo {
            top: 0;
            left: 0;
            height: 100px;
            width: 200px;
        }

        .titulo {
            border: solid 1px;
            font-weight: bold;
            font-size: 1.5rem;
            text-align: center;
            padding: 3px;
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
            margin-bottom: 0x;
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
            top: 139px;
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
            top: 335px;
            right: -100px;
        }

        .final {
            top: 335px;
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
            margin-top: 40px;
            margin-left: auto;
            text-align: right;
        }

        .qr {
            width: 200px;
            margin-left: auto;
            text-align: right;
        }

        table.header {
            border-collapse: collapse;
        }

        table.header tbody tr td {
            padding: 5px;
        }

        table.header tbody td.logo {
            width: 200px;
        }

        table.header tbody td.logo img {
            width: 100%;
            height: 70px;
        }

        table.header tbody tr td.txt_correlativo {
            padding-left: 7px;
            padding-bottom: 0px;
            vertical-align: bottom;
        }

        table.header tbody tr td.valor_correlativo {
            vertical-align: top;
            padding-top: 0px;
            padding-left: 7px;
        }

        table.datos_personal {
            margin-top: 15px;
            border-collapse: collapse;
        }

        .td_txt_firma {
            vertical-align: top;
        }

        .border_top {
            border-top: solid 1px black;
        }

        .border_bottom {
            border-bottom: solid 1px black;
        }

        .border_left {
            border-left: solid 1px black;
        }

        .border_right {
            border-right: solid 1px black;
        }

        table.codigos_fechas {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @inject('verificacion_actividad', 'App\Models\VerificacionActividad')
    <table class="header">
        <tbody>
            <tr>
                <td class="logo" rowspan="4">
                    <img class="logo" src="{{ asset('imgs/' . $configuracion->first()->logo) }}" alt="Logo">
                </td>
                <td class="td_titulo" rowspan="4">
                    <div class="titulo">
                        CERTIFICACIÓN POA<br />GESTIÓN {{ $verificacion_actividad->first()->gestion }}
                    </div>
                </td>
                <td class="">
                </td>
            </tr>
            <tr>
                <td class="txt_correlativo">
                    N° Correlativo
                </td>
            </tr>
            <tr>
                <td class="valor_correlativo">
                    <div class="nro">{{ $certificacion->correlativo }}</div>
                </td>
            </tr>
            <tr>
                <td class="">
                </td>
            </tr>
        </tbody>
    </table>

    <table class="solicitante" border="1">
        <tbody>
            <tr>
                <td class="bold" width="25%">UNIDAD SOLICITANTE</td>
                <td class="gray bold">{{ $certificacion->formulario->unidad->nombre }}</td>
            </tr>
        </tbody>
    </table>

    <table class="datos_personal">
        <tbody>
            <tr>
                <td></td>
                <td class="centreado bold" width="41%">Datos del solicitante</td>
                <td></td>
                <td></td>
                <td class="centreado bold" width="41%">Datos del inmediato Superior que Aprueba</td>
            </tr>
            <tr>
                <td width="5%">Nombre:</td>
                <td class="gray border p-5">{{ $certificacion->solicitante->full_name }}</td>
                <td></td>
                <td width="5%">Nombre:</td>
                <td class="gray border p-5">{{ $certificacion->superior->full_name }}</td>
            </tr>
            <tr>
                <td>Cargo:</td>
                <td class="gray border p-5">{{ $certificacion->solicitante->cargo }}</td>
                <td></td>
                <td>Cargo:</td>
                <td class="gray border p-5">{{ $certificacion->superior->cargo }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="p-5" style="padding-bottom:0px;">
                    <div class="firma"></div>
                </td>
                <td></td>
                <td></td>
                <td class="p-5" style="padding-bottom:0px;">
                    <div class="firma"></div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="p-0 td_txt_firma centreado">Firma</td>
                <td></td>
                <td></td>
                <td class="p-0 td_txt_firma centreado">Firma</td>
            </tr>
        </tbody>

    </table>

    <table class="codigos_fechas" style="margin-top:15px;">
        <tbody>
            <tr>
                <td class="centreado bold bg_principal border_top border_bottom border_left border_right"
                    colspan="6">CÓDIGO SIGEP</td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="20%"
                    class="bold bg_principal centreado border_top border_bottom border_left border_right">Mes inicio
                </td>
                <td></td>
                <td width="20%"
                    class="bold bg_principal centreado border_top border_bottom border_left border_right">Mes final</td>
            </tr>
            <tr>
                <td class="centreado bold bg_principal border_left border_bottom">U.E.</td>
                <td class="centreado bold bg_principal border_left border_bottom">PROG</td>
                <td class="centreado bold bg_principal border_left border_bottom">PROY</td>
                <td class="centreado bold bg_principal border_left border_bottom">ACT.</td>
                <td class="centreado bold bg_principal border_left border_bottom">F.F.</td>
                <td class="centreado bold bg_principal border_left border_bottom border_right">O.F.</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="centreado border_left border_bottom border_right">
                    {{ date('d/m/Y', strtotime($certificacion->inicio)) }}</td>
                <td></td>
                <td class="centreado border_left border_bottom border_right">
                    {{ date('d/m/Y', strtotime($certificacion->final)) }}</td>
                </td>
            </tr>
            <tr>
                <td class="centreado border_left border_bottom">{{ $certificacion->certificacion_detalles[0]->memoria_operacion_detalle->ue }}
                </td>
                <td class="centreado border_left border_bottom">{{ $certificacion->certificacion_detalles[0]->memoria_operacion_detalle->prog }}
                </td>
                <td class="centreado border_left border_bottom">00</td>
                <td class="centreado border_left border_bottom">{{ $certificacion->certificacion_detalles[0]->memoria_operacion_detalle->act }}
                </td>
                <td class="centreado border_left border_bottom">42</td>
                <td class="centreado border_left border_bottom border_right">230</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <p class="titulo_desc bold" style="margin-bottom:0px;">DESCRIPCIÓN DE LA OPERACIÓN O ACTIVIDAD PROGRAMADA A SER
        EJECUTADA</p>
    <table class="collapse" border="1">
        <thead>
            <tr>
                <th class="bg_principal centreado">Cod.</th>
                <th class="bg_principal centreado">Acción de Corto Plazo</th>
                <th class="bg_principal centreado">Cod. Op.</th>
                <th class="bg_principal centreado">Operación</th>
                {{-- <th class="bg_principal centreado">Cod. Act.</th> --}}
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="bold">{!! str_replace('|', '<br>', $certificacion->codigo) !!}</td>
                <td class="centreado">{!! str_replace('|', '<br>', $certificacion->accion) !!}</td>
                <td class="bold">{{ $certificacion->memoria_operacion->operacion->codigo_operacion }}</td>
                <td>{{ $certificacion->memoria_operacion->operacion->operacion }}</td>
                {{-- <td class="bold">{{ $certificacion->memoria_operacion->codigo_actividad }}</td> --}}
            </tr>
        </tbody>
    </table>

    <table class="collapse" border="1" style="margin-top:15px">
        <thead>
            <tr>
                <th class="bg_principal centreado">Descripción de lo solicitado</th>
                <th class="bg_principal centreado">Partida</th>
                <th class="bg_principal centreado">Presup. Programado</th>
                <th class="bg_principal centreado">Presup. A Ejecutarse</th>
                <th class="bg_principal centreado">Saldo Presupuestario</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_ejecutarse = 0;
                $total_saldo = 0;
            @endphp
            @foreach ($certificacion->certificacion_detalles as $cd)
                <tr>
                    <td class="centreado">{{ $cd->memoria_operacion_detalle->m_partida->descripcion }}</td>
                    <td class="centreado">{{ $cd->memoria_operacion_detalle->partida }}</td>
                    <td class="centreado">{{ number_format($cd->memoria_operacion_detalle->total, 2) }}</td>
                    <td class="centreado">{{ number_format($cd->presupuesto_usarse, 2) }}</td>
                    @php
                        // $saldo = number_format((float) $cd->memoria_operacion_detalle->total - (float) $cd->presupuesto_usarse, 2);
                        $saldo = (float) $cd->saldo_total;
                        if ((float) $saldo == 0) {
                            $saldo = '-';
                        } else {
                            $saldo = number_format($saldo, 2);
                        }
                    @endphp
                    <td class="centreado">
                        {{ $saldo }}
                    </td>
                </tr>
                @php
                    $total_ejecutarse += (float) $cd->presupuesto_usarse;
                    if ($saldo != '-') {
                        $total_saldo += (float) $cd->saldo_total;
                    }
                @endphp
            @endforeach
            <tr>
                <td class="bg_principal bold text_right" colspan="3">TOTAL MONTO CERTIFICADO</td>
                <td class="centreado bg_principal bold">{{ number_format($total_ejecutarse, 2) }}</td>
                <td class="centreado bg_principal bold">{{ number_format($total_saldo, 2) }}</td>
            </tr>
        </tbody>
    </table>


    <table class="collapse" border="1" style="margin-top:15px">
        <thead>
            <tr>
                <th class="bg_principal centreado">Personal designado</th>
                <th class="bg_principal centreado">Departamento</th>
                <th class="bg_principal centreado">Municipio</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="centreado">{{ $certificacion->o_personal_designado->full_name }}</td>
                <td class="centreado">{{ $certificacion->departamento }}</td>
                <td class="centreado">{{ $certificacion->municipio }}</td>
            </tr>
        </tbody>
    </table>

    <table class="collapse" style="margin-top:15px;">
        <tbody>
            <tr>
                <td class="border bold p-5" width="40%">Verificación de la actividad en el POA
                    {{ $verificacion_actividad->first()->gestion }}
                </td>
                <td></td>
            </tr>
            <tr class="border">
                <td class="p-5" colspan="2">{{ $verificacion_actividad->first()->actividad }}</td>
            </tr>
        </tbody>
    </table>

    <p class="texto_unidad bold">UNIDAD DE PLANIFICACIÓN ESTRATEGICA</p>

    <table class="aprobados">
        <tbody>
            <tr>
                <td class="bold">Verificado por:</td>
                <td class="bold">Aprobado por:</td>
            </tr>
            <tr>
                <td class="border" style="height: 45px"></td>
                <td class="border" style="height: 45px"></td>
            </tr>
        </tbody>
    </table>

    <div class="qr">
        <img src="data:image/png;base64, {!! base64_encode(
            \QrCode::format('png')->size(150)->generate(
                    $certificacion->correlativo .
                        '|' .
                        $certificacion->solicitante->full_name .
                        '|' .
                        date('d/m/Y', strtotime($certificacion->inicio)) .
                        '|' .
                        date('d/m/Y', strtotime($certificacion->final)) .
                        '|' .
                        number_format($total_ejecutarse, 2),
                ),
        ) !!}">
    </div>
</body>

</html>
