<table class="tabla_detalle" border="1">
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
        @php
            $tarea_actual = 0;
            $muestra = true;
        @endphp
        @foreach ($array_registros as $index_registro => $registro)
            @if (isset($registro['lugares']))
                @foreach ($registro['lugares'] as $index_lugar => $lugar)
                    @foreach ($lugar['responsables'] as $index_responsable => $responsable)
                        @foreach ($responsable['registros'] as $index_registro_rep => $registro_resp)
                            @if ($index_lugar == 0 && $index_responsable == 0 && $index_registro_rep == 0)
                                @if ($registro['subdireccion'])
                                    <tr>
                                        <td colspan="17" class="centreado bg_principal">
                                            {{ $registro['subdireccion']->nombre }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td rowspan="{{ $registro['rowspan'] }}">{{ $registro['codigo_operacion'] }}</td>
                                    <td rowspan="{{ $registro['rowspan'] }}">{{ $registro['operacion'] }}</td>
                                    <td rowspan="{{ $registro['rowspan'] }}">{{ $registro['codigo_tarea'] }}</td>
                                    <td rowspan="{{ $registro['rowspan'] }}">{{ $registro['tarea'] }}</td>
                                    <td rowspan="{{ $lugar['rowspan'] }}">{{ $lugar['lugar'] }}</td>
                                    <td rowspan="{{ $responsable['rowspan'] }}">{{ $responsable['responsable'] }}</td>
                                    <td>{{ $registro_resp->partida }}</td>
                                    <td>{{ $registro_resp->descripcion }}</td>
                                    <td>{{ $registro_resp->cantidad }}</td>
                                    <td>{{ $registro_resp->unidad }}</td>
                                    <td>{{ $registro_resp->costo }}</td>
                                    <td>{{ $registro_resp->total }}</td>
                                    <td>{{ $registro_resp->ue }}</td>
                                    <td>{{ $registro_resp->prog }}</td>
                                    <td>{{ $registro_resp->act }}</td>
                                    <td>{{ $registro_resp->justificacion }}</td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }} centreado">
                                        {{ $registro_resp->total_actividad }}</>
                                </tr>
                            @elseif ($index_responsable == 0 && $index_registro_rep == 0)
                                <tr>
                                    <td rowspan="{{ $lugar['rowspan'] }}">{{ $lugar['lugar'] }}
                                    </td>
                                    <td rowspan="{{ $responsable['rowspan'] }}">{{ $responsable['responsable'] }}</td>
                                    <td>{{ $registro_resp->partida }}</td>
                                    <td>{{ $registro_resp->descripcion }}</td>
                                    <td>{{ $registro_resp->cantidad }}</td>
                                    <td>{{ $registro_resp->unidad }}</td>
                                    <td>{{ $registro_resp->costo }}</td>
                                    <td>{{ $registro_resp->total }}</td>
                                    <td>{{ $registro_resp->ue }}</td>
                                    <td>{{ $registro_resp->prog }}</td>
                                    <td>{{ $registro_resp->act }}</td>
                                    <td>{{ $registro_resp->justificacion }}</td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }} centreado">
                                        {{ $registro_resp->total_actividad }}</>
                                </tr>
                            @elseif ($index_registro_rep == 0)
                                <tr>
                                    <td rowspan="{{ $responsable['rowspan'] }}">{{ $responsable['responsable'] }}</td>
                                    <td>{{ $registro_resp->partida }}</td>
                                    <td>{{ $registro_resp->descripcion }}</td>
                                    <td>{{ $registro_resp->cantidad }}</td>
                                    <td>{{ $registro_resp->unidad }}</td>
                                    <td>{{ $registro_resp->costo }}</td>
                                    <td>{{ $registro_resp->total }}</td>
                                    <td>{{ $registro_resp->ue }}</td>
                                    <td>{{ $registro_resp->prog }}</td>
                                    <td>{{ $registro_resp->act }}</td>
                                    <td>{{ $registro_resp->justificacion }}</td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }} centreado">
                                        {{ $registro_resp->total_actividad }}</>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $registro_resp->partida }}</td>
                                    <td>{{ $registro_resp->descripcion }}</td>
                                    <td>{{ $registro_resp->cantidad }}</td>
                                    <td>{{ $registro_resp->unidad }}</td>
                                    <td>{{ $registro_resp->costo }}</td>
                                    <td>{{ $registro_resp->total }}</td>
                                    <td>{{ $registro_resp->ue }}</td>
                                    <td>{{ $registro_resp->prog }}</td>
                                    <td>{{ $registro_resp->act }}</td>
                                    <td>{{ $registro_resp->justificacion }}</td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }} centreado">
                                        {{ $registro_resp->total_actividad }}</>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            @endif
        @endforeach
        <tr class="bg-primary">
            <th colspan="16">TOTAL</th>
            <th class="centreado">{{ number_format($formulario_cinco->memoria->total_final, 2) }}</th>
        </tr>
    </tbody>
</table>
