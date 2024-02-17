<table class="tabla_detalle">
    <tbody>
        <tr class="bg-primary cabecera">
            <th colspan="15" class="border_left">
                PLAN OPERATIVO ANUAL GESTIÓN
                {{ $verificacion_actividad ? $verificacion_actividad->gestion : date('Y') }}
            </th>
        </tr>
        <tr class="bg-primary cabecera">
            <th rowspan="3" width="3%" class="border_left">
                Código Operación(1)
            </th>
            <th rowspan="3">
                Operación(2)
            </th>
            {{-- <th rowspan="3" width="3%">
                Código tarea(3)
            </th>
            <th rowspan="3">
                Actividad/Tareas(4)
            </th> --}}
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
        <tr class="bg-primary cabecera">
            <th rowspan="2" width="3%">Partida(7)</th>
            <th rowspan="2">
                Descripción(8)
            </th>
            <th rowspan="2" width="3%">Cantidad(9)</th>
            <th rowspan="2" width="5%">Unidad(10)</th>
            <th rowspan="2" width="6%">
                Costo Unitario(11)
            </th>
            <th colspan="4">
                Recursos Internos(12)
            </th>
            <th>Recursos externos(13)</th>
            <th rowspan="2" width="8%">
                TOTAL (por Operación)(14)
            </th>
        </tr>
        <tr class="bg-primary cabecera">
            <th width="6%">PRESUPUESTO VIGENTE</th>
            <th width="2%">UE</th>
            <th width="2%">PROG</th>
            <th width="2%">ACT</th>
            <th>OTROS</th>
        </tr>
        @php
            $tarea_actual = 0;
            $muestra = true;
        @endphp
        @foreach ($array_registros as $i_p => $ar)
            @foreach ($ar['registros'] as $index_registro => $registro)
                @foreach ($registro['lugares'] as $index_lugar => $lugar)
                    @foreach ($lugar['responsables'] as $index_responsable => $responsable)
                        @foreach ($responsable['registros'] as $index_registro_rep => $registro_resp)
                            @if ($index_registro == 0 && $index_lugar == 0 && $index_responsable == 0 && $index_registro_rep == 0)
                                @if ($registro['subdireccion'])
                                    <tr>
                                        <td colspan="15" class="bg-primary border_left border_right">
                                            {{ $registro['subdireccion']->nombre }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="border_left border_top">{{ $registro['codigo_operacion'] }}</td>
                                    <td class="border_left border_top">{{ $registro['operacion'] }}</td>
                                    {{-- <td class="border_left border_top">{{ $registro['codigo_tarea'] }}</td> --}}
                                    {{-- <td class="border_left border_top">{{ $registro['tarea'] }}</td> --}}
                                    <td class="border_left border_top">{{ $lugar['lugar'] }}</td>
                                    <td class="border_left border_top">{{ $responsable['responsable'] }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->partida }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->descripcion }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->cantidad }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->unidad }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->costo }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->total }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->ue }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->prog }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->act }}</td>
                                    <td class="border_left border_top"></td>
                                    <td
                                        class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }}  border_left border_right border_top">
                                        {{ $registro_resp->total_actividad }}</td>
                                </tr>
                            @elseif ($index_lugar == 0 && $index_responsable == 0 && $index_registro_rep == 0)
                                <tr>
                                    <td class="border_left"></td>
                                    <td class="border_left"></td>
                                    {{-- <td class="border_left border_top">{{ $registro['codigo_tarea'] }}</td> --}}
                                    {{-- <td class="border_left border_top">{{ $registro['tarea'] }}</td> --}}
                                    <td class="border_left border_top">{{ $lugar['lugar'] }}</td>
                                    <td class="border_left border_top">{{ $responsable['responsable'] }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->partida }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->descripcion }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->cantidad }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->unidad }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->costo }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->total }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->ue }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->prog }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->act }}</td>
                                    <td class="border_left border_top"></td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }} border_left border_right border_top">
                                        {{ $registro_resp->total_actividad }}</td>
                                </tr>
                            @elseif ($index_registro_rep == 0)
                                <tr>
                                    {{-- <td class="border_left"></td> --}}
                                    {{-- <td class="border_left"></td> --}}
                                    <td class="border_left"></td>
                                    <td class="border_left"></td>
                                    <td class="border_left"></td>
                                    <td class="border_left"></td>
                                    <td class="border_left border_top">{{ $registro_resp->partida }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->descripcion }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->cantidad }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->unidad }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->costo }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->total }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->ue }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->prog }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->act }}</td>
                                    <td class="border_left border_top"></td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }} border_left border_right border_top">
                                        {{ $registro_resp->total_actividad }}</td>
                                </tr>
                            @else
                                <tr>
                                    {{-- <td class="border_left"></td> --}}
                                    {{-- <td class="border_left"></td> --}}
                                    <td class="border_left"></td>
                                    <td class="border_left"></td>
                                    <td class="border_left"></td>
                                    <td class="border_left"></td>
                                    <td class="border_left border_top">{{ $registro_resp->partida }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->descripcion }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->cantidad }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->unidad }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->costo }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->total }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->ue }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->prog }}</td>
                                    <td class="border_left border_top">{{ $registro_resp->act }}</td>
                                    <td class="border_left border_top"></td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }} border_left border_right border_top">
                                        {{ $registro_resp->total_actividad }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
        <tr class="bg-primary">
            <th colspan="14" class="border_left border_bottom border_top border_right">TOTAL PRESUPUESTO DE LA/EL {{ $formulario_cinco->memoria->formulario->unidad->nombre }}</th>
            <th class="text-center border_left border_bottom border_top border_right">{{ number_format($formulario_cinco->memoria->total_final, 2) }}</th>
        </tr>
    </tbody>
</table>
