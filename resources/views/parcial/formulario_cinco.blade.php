<table class="tabla_detalle" border="1">
    <tbody>
        <tr class="bg-primary cabecera">
            <th colspan="15">
                PLAN OPERATIVO ANUAL GESTIÓN
                {{ $verificacion_actividad ? $verificacion_actividad->gestion : date('Y') }}
            </th>
        </tr>
        <tr class="bg-primary cabecera">
            <th rowspan="3" width="3%">
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
            <th rowspan="2">
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
                        @php
                            $subtotal = 0;
                        @endphp
                        @foreach ($responsable['registros'] as $index_registro_rep => $registro_resp)
                            @if ($index_registro == 0 && $index_lugar == 0 && $index_responsable == 0 && $index_registro_rep == 0)
                                @if ($registro['subdireccion'])
                                    <tr>
                                        <td colspan="17" class="bg-primary">
                                            {{ $registro['subdireccion']->nombre }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td rowspan="{{ $ar['rowspan'] }}">{{ $registro['codigo_operacion'] }}</td>
                                    <td rowspan="{{ $ar['rowspan'] }}">{{ $registro['operacion'] }}</td>
                                    {{-- <td>{{ $registro_resp->cod_actividad_txt }}</td> --}}
                                    {{-- <td>{{ $registro_resp->actividad_txt }}</td> --}}
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
                                    <td></td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }}">
                                        {{ $registro_resp->total_actividad }}</td>
                                </tr>
                            @elseif ($index_lugar == 0 && $index_responsable == 0 && $index_registro_rep == 0)
                                <tr>
                                    {{-- <td>{{ $registro_resp->cod_actividad_txt }}</td> --}}
                                    {{-- <td>{{ $registro_resp->actividad_txt }}</td> --}}
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
                                    <td></td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }}">
                                        {{ $registro_resp->total_actividad }}</td>
                                </tr>
                            @elseif ($index_registro_rep == 0)
                                <tr>
                                    {{-- <td>{{ $registro_resp->cod_actividad_txt }}</td> --}}
                                    {{-- <td>{{ $registro_resp->actividad_txt }}</td> --}}
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
                                    <td></td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }}">
                                        {{ $registro_resp->total_actividad }}</td>
                                </tr>
                            @else
                                <tr>
                                    {{-- <td>{{ $registro_resp->cod_actividad_txt }}</td> --}}
                                    {{-- <td>{{ $registro_resp->actividad_txt }}</td> --}}
                                    <td>{{ $registro_resp->partida }}</td>
                                    <td>{{ $registro_resp->descripcion }}</td>
                                    <td>{{ $registro_resp->cantidad }}</td>
                                    <td>{{ $registro_resp->unidad }}</td>
                                    <td>{{ $registro_resp->costo }}</td>
                                    <td>{{ $registro_resp->total }}</td>
                                    <td>{{ $registro_resp->ue }}</td>
                                    <td>{{ $registro_resp->prog }}</td>
                                    <td>{{ $registro_resp->act }}</td>
                                    <td></td>
                                    <td class="{{ (float) $registro_resp->saldo == 0 ? 'fondo_rojo' : '' }}">
                                        {{ $registro_resp->total_actividad }}</td>
                                </tr>
                            @endif
                            @php
                                $subtotal += (float) $registro_resp->total_actividad;
                            @endphp
                        @endforeach
                        <tr>
                            <td class="crema" colspan="14">TOTAL</td>
                            <td class="crema">{{ number_format($subtotal, 2, '.', '') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
        <tr class="bg-primary">
            <th colspan="14">TOTAL PRESUPUESTO DE LA/EL {{ $formulario_cinco->memoria->formulario->unidad->nombre }}
            </th>
            <th class="text-center">{{ number_format($formulario_cinco->memoria->total_final, 2) }}</th>
        </tr>
    </tbody>
</table>
