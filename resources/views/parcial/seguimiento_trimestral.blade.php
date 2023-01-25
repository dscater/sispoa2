<table class="tabla_detalle" border="1">
    <thead>
        <tr class="bg-primary">
            <th rowspan="3" width="5%">
                Código Operación(1)
            </th>
            <th rowspan="3">Operación(2)</th>
            <th rowspan="3">Ponderación</th>
            <th rowspan="3">
                Resultado intermedio Esperado(3)
            </th>
            <th rowspan="3">
                Medios de verificación(4)
            </th>
            <th rowspan="3">Código tarea(5)</th>
            <th rowspan="3">
                Actividad/Tarea(6)
            </th>
            <th colspan="12">
                Programación de ejecución de
                operaciones y actividades(7)
            </th>
            <th colspan="2">
                Fecha prevista de ejecución(8)
            </th>
        </tr>
        <tr class="bg-primary">
            <th colspan="3">1er Trim.</th>
            <th colspan="3">2do Trim.</th>
            <th colspan="3">3er Trim.</th>
            <th colspan="3">4to Trim.</th>
            <th rowspan="2">Inicio</th>
            <th rowspan="2">Final</th>
        </tr>
        <tr class="bg-primary">
            <th>E</th>
            <th>F</th>
            <th>M</th>
            <th>A</th>
            <th>M</th>
            <th>J</th>
            <th>J</th>
            <th>A</th>
            <th>S</th>
            <th>O</th>
            <th>N</th>
            <th>D</th>
        </tr>
    </thead>
    <tbody>
        @inject('o_memoria_operacion', 'App\Models\MemoriaOperacion')
        @php
            $fecha_actual = date('Y-m-d');
        @endphp
        @foreach ($detalle_formulario->operacions as $operacion)
            @php
                $rowspan = count($operacion->detalle_operaciones) + 1;
            @endphp
            <tr>
                <td rowspan="{{ $rowspan }}">
                    {{ $operacion->codigo_operacion }}
                </td>
                <td rowspan="{{ $rowspan }}">
                    {{ $operacion->operacion }}
                </td>
            </tr>
            @foreach ($operacion->detalle_operaciones as $detalle_operacion)
                {{-- CLASIFICAR COLORES --}}
                @php
                    $memoria = $o_memoria_operacion
                        ->where('operacion_id', $operacion->id)
                        ->where('detalle_operacion_id', $detalle_operacion->id)
                        ->get()
                        ->first();
                    $color = 'bg-red';
                    if ($memoria) {
                        $color = 'bg-green';
                        if ($fecha_actual >= $detalle_operacion->final) {
                            $color = 'bg-azul';
                        }
                    } else {
                        if ($fecha_actual >= $detalle_operacion->inicio && $fecha_actual <= $detalle_operacion->final) {
                            $color = 'bg-yellow';
                        } else {
                            $color = 'bg-red';
                        }
                    }
                    
                @endphp
                <tr class="{{ $color }}">
                    <td>
                        {{ $detalle_operacion->ponderacion }}%
                    </td>
                    <td>
                        {{ $detalle_operacion->resultado_esperado }}
                    </td>
                    <td>
                        {{ $detalle_operacion->medios_verificacion }}
                    </td>
                    <td>
                        {{ $detalle_operacion->codigo_tarea }}
                    </td>
                    <td>
                        {{ $detalle_operacion->actividad_tarea }}
                    </td>
                    <td>
                        {{ $detalle_operacion->pt_e }}
                    </td>
                    <td>
                        {{ $detalle_operacion->pt_f }}
                    </td>

                    <td>
                        {{ $detalle_operacion->pt_m }}
                    </td>

                    <td>
                        {{ $detalle_operacion->st_a }}
                    </td>

                    <td>
                        {{ $detalle_operacion->st_m }}
                    </td>

                    <td>
                        {{ $detalle_operacion->st_j }}
                    </td>

                    <td>
                        {{ $detalle_operacion->tt_j }}
                    </td>

                    <td>
                        {{ $detalle_operacion->tt_a }}
                    </td>

                    <td>
                        {{ $detalle_operacion->tt_s }}
                    </td>

                    <td>
                        {{ $detalle_operacion->ct_o }}
                    </td>

                    <td>
                        {{ $detalle_operacion->ct_n }}
                    </td>

                    <td>
                        {{ $detalle_operacion->ct_d }}
                    </td>
                    <td>
                        {{ date('d/m/Y', strtotime($detalle_operacion->inicio)) }}
                    </td>
                    <td>
                        {{ date('d/m/Y', strtotime($detalle_operacion->final)) }}
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
