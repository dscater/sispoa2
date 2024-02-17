<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Memorias de Cálculo</title>
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

        .fecha {
            text-align: center;
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
    @foreach ($memoria_calculos as $memoria_calculo)
        <img class="logo" src="{{ asset('imgs/' . $configuracion->first()->logo2) }}" alt="Logo">
        <img class="logo2" src="{{ asset('imgs/' . $configuracion->first()->logo) }}" alt="Logo">
        <div class="titulo">MEMORIAS DE CÁLCULO<br />GESTIÓN {{ date('Y') }}</div>

        @if (Auth::user()->tipo != 'SUPER USUARIO' || $filtro == 'Unidad Organizacional')
            @if ($filtro == 'Unidad Organizacional')
                <h4 class="fecha">{{ $unidad->nombre }}</h4>
            @else
                <h4 class="fecha">{{ Auth::user()->unidad->nombre }}</h4>
            @endif
        @endif

        <table border="1" class="collapse" style="margin-top: 80px;">
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

        <table class="tabla_detalle" border="1">
            <thead class="bg-primary">
                <tr>
                    <th class="text-center" rowspan="2">
                        Unidad Ejecutora
                    </th>
                    <th class="text-center" colspan="2">
                        Presupuestos
                    </th>
                    <th class="text-center" colspan="2">
                        POA
                    </th>
                    <th class="text-center" rowspan="2">
                        Partida de gasto
                    </th>
                    <th class="text-center" rowspan="2">
                        N°
                    </th>
                    <th class="text-center" rowspan="2">
                        Descripción
                    </th>
                    <th class="text-center" rowspan="2">
                        Cantidad Requerida
                    </th>
                    <th class="text-center" rowspan="2">
                        Unidad
                    </th>
                    <th class="text-center" rowspan="2">
                        Precio Unitario
                    </th>
                    <th class="text-center" rowspan="2">
                        Total
                    </th>
                    <th class="text-center" rowspan="2">
                        Justificación
                    </th>
                    <th class="text-center" colspan="12">
                        PROGRAMACIÓN MENSUAL
                    </th>
                    <th class="text-center" rowspan="2">
                        Total
                    </th>
                </tr>
                <tr>
                    <th class="text-center">
                        Programa
                    </th>
                    <th class="text-center">
                        Actividad
                    </th>
                    <th class="text-center">
                        Cod. Operación
                    </th>
                    <th class="text-center">
                        Cod. Act./Tarea
                    </th>
                    <th class="text-center">
                        Enero
                    </th>
                    <th class="text-center">
                        Febrero
                    </th>
                    <th class="text-center">
                        Marzo
                    </th>
                    <th class="text-center">
                        Abril
                    </th>
                    <th class="text-center">
                        Mayo
                    </th>
                    <th class="text-center">
                        Junio
                    </th>
                    <th class="text-center">
                        Julio
                    </th>
                    <th class="text-center">
                        Agosto
                    </th>
                    <th class="text-center">
                        Septiembre
                    </th>
                    <th class="text-center">
                        Octubre
                    </th>
                    <th class="text-center">
                        Noviembre
                    </th>
                    <th class="text-center">
                        Diciembre
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($memoria_calculo->operacions as $operacion)
                    @foreach ($operacion->memoria_operacion_detalles as $mod)
                        <tr v-for="item in oMemoriaCalculo.operacions">
                            <td>{{ $mod->ue }}</td>
                            <td>{{ $mod->prog }}</td>
                            <td>{{ $mod->act }}</td>
                            <td>
                                {{ $operacion->codigo_operacion }}
                            </td>
                            <td>
                                {{ $operacion->codigo_actividad }}
                            </td>
                            <td>{{ $mod->partida }}</td>
                            <td>{{ $mod->nro }}</td>
                            <td>{{ $mod->descripcion }}</td>
                            <td>{{ $mod->cantidad }}</td>
                            <td>{{ $mod->unidad }}</td>
                            <td>{{ $mod->costo }}</td>
                            <td>{{ $mod->total }}</td>
                            <td>
                                {{ $mod->justificacion }}
                            </td>
                            <td>{{ $mod->ene }}</td>
                            <td>{{ $mod->feb }}</td>
                            <td>{{ $mod->mar }}</td>
                            <td>{{ $mod->abr }}</td>
                            <td>{{ $mod->may }}</td>
                            <td>{{ $mod->jun }}</td>
                            <td>{{ $mod->jul }}</td>
                            <td>{{ $mod->ago }}</td>
                            <td>{{ $mod->sep }}</td>
                            <td>{{ $mod->oct }}</td>
                            <td>{{ $mod->nov }}</td>
                            <td>{{ $mod->dic }}</td>
                            <td class="{{ (float) $mod->saldo == 0 ? 'fondo_rojo' : '' }} centreado">
                                {{ $mod->total_actividad }}
                                </>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <th colspan="8" class="text-center">TOTAL PARTIDA</th>
                    <th colspan="3"></th>
                    <th>
                        {{ $memoria_calculo->total_actividades }}
                    </th>
                    <th></th>
                    <th class="text-center">{{ $memoria_calculo->total_ene }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_feb }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_mar }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_abr }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_may }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_jun }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_jul }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_ago }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_sep }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_oct }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_nov }}</th>
                    <th class="text-center">{{ $memoria_calculo->total_dic }}</th>
                    <th class="text-center">
                        {{ $memoria_calculo->total_final }}
                    </th>
                </tr>
            </tbody>
        </table>
        @php
            $contador++;
        @endphp
        @if ($contador < count($memoria_calculos))
            <div class="salto_linea"></div>
        @endif
    @endforeach
</body>

</html>
