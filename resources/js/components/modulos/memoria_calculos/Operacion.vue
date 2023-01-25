<template>
    <div class="card">
        <div
            class="card-body detalle contenedor_operacion"
            :class="[(index + 1) % 2 == 0 ? 'bg-white' : '']"
        >
            <span
                class="rounded-circle numero_detalle"
                v-text="index + 1"
            ></span>
            <button
                class="btn btn-danger rounded-circle btnQuitar"
                @click="quitar"
            >
                X
            </button>
            <div class="row">
                <div class="form-group col-md-3 mt-3">
                    <label
                        :class="{
                            'text-danger': errors.codigo_operacion,
                        }"
                        >Código de Operación*</label
                    >
                    <el-select
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.operacion_id,
                        }"
                        v-model="o_Operacion.operacion_id"
                        clearable
                        @change="getTextoOperacion"
                    >
                        <el-option
                            v-for="item in listOperaciones"
                            :key="item.id"
                            :value="item.id"
                            :label="item.codigo_operacion"
                        >
                        </el-option>
                    </el-select>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.codigo_operacion"
                        v-text="errors.codigo_operacion[0]"
                    ></span>
                </div>

                <div class="form-group col-md-9 mt-3">
                    <label>Operación*</label>

                    <el-input
                        type="textarea"
                        autosize
                        placeholder="Operación"
                        v-model="texto_operacion"
                        clearable
                        readonly
                    >
                    </el-input>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3 mt-3">
                    <label
                        :class="{
                            'text-danger': errors.detalle_operacion_id,
                        }"
                        >Código Actividad/Tarea*</label
                    >
                    <el-select
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.detalle_operacion_id,
                        }"
                        v-model="o_Operacion.detalle_operacion_id"
                        clearable
                        @change="getTextoActividad"
                    >
                        <el-option
                            v-for="(
                                actividad, index
                            ) in o_Operacion.detalle_operaciones"
                            :key="actividad.id"
                            :value="actividad.id"
                            :label="actividad.codigo_tarea"
                        >
                        </el-option>
                    </el-select>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.detalle_operacion_id"
                        v-text="errors.detalle_operacion_id[0]"
                    ></span>
                </div>

                <div class="form-group col-md-9 mt-3">
                    <label>Activiadad/Tarea*</label>

                    <el-input
                        type="textarea"
                        autosize
                        placeholder="Actividad/Tarea"
                        v-model="texto_actividad"
                        clearable
                        readonly
                    >
                    </el-input>
                </div>
            </div>
            <div
                class="row detalle"
                v-for="(
                    item_mod, index_mod
                ) in o_Operacion.memoria_operacion_detalles"
                :key="index_mod"
            >
                <span
                    class="numero_operacion_detalle_tarea"
                    v-text="index + 1 + '-' + (index_mod + 1) + ' Detalle'"
                ></span>
                <div class="col-md-12">
                    <div class="card">
                        <button
                            class="btn btn-danger rounded-circle btnQuitar"
                            @click="quitarDetalle(index_mod, item_mod.id)"
                            v-if="index_mod > 0"
                        >
                            X
                        </button>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label
                                        :class="{
                                            'text-danger': errors.ue,
                                        }"
                                        >Unidad ejecutora*</label
                                    >
                                    <el-input
                                        class="w-100"
                                        :class="{
                                            'is-invalid': errors.ue,
                                        }"
                                        v-model="item_mod.ue"
                                        clearable
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.ue"
                                        v-text="errors.ue[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-2">
                                    <label
                                        :class="{
                                            'text-danger': errors.prog,
                                        }"
                                        >Programa*</label
                                    >
                                    <el-input
                                        class="w-100"
                                        :class="{
                                            'is-invalid': errors.prog,
                                        }"
                                        v-model="item_mod.prog"
                                        clearable
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.prog"
                                        v-text="errors.prog[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-2">
                                    <label
                                        :class="{
                                            'text-danger': errors.act,
                                        }"
                                        >Actividad*</label
                                    >
                                    <el-input
                                        class="w-100"
                                        :class="{
                                            'is-invalid': errors.act,
                                        }"
                                        v-model="item_mod.act"
                                        clearable
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.act"
                                        v-text="errors.act[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label
                                        :class="{
                                            'text-danger': errors.lugar,
                                        }"
                                        >Lugar de Ejecución de la
                                        Operación*</label
                                    >
                                    <el-input
                                        class="w-100"
                                        :class="{
                                            'is-invalid': errors.lugar,
                                        }"
                                        v-model="item_mod.lugar"
                                        clearable
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.lugar"
                                        v-text="errors.lugar[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label
                                        :class="{
                                            'text-danger': errors.responsable,
                                        }"
                                        >Responsable de Ejecución de la
                                        Operación / Tarea*</label
                                    >
                                    <el-input
                                        class="w-100"
                                        :class="{
                                            'is-invalid': errors.responsable,
                                        }"
                                        v-model="item_mod.responsable"
                                        clearable
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.responsable"
                                        v-text="errors.responsable[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label
                                        :class="{
                                            'text-danger': errors.partida_id,
                                        }"
                                        >Partida de gasto*</label
                                    >
                                    <el-select
                                        class="w-100"
                                        :class="{
                                            'is-invalid': errors.partida_id,
                                        }"
                                        v-model="item_mod.partida_id"
                                        @change="getTextoPartida(index_mod)"
                                    >
                                        <el-option
                                            v-for="partida in listPartidas"
                                            :key="partida.id"
                                            :value="partida.id"
                                            :label="partida.partida"
                                        ></el-option>
                                    </el-select>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.partida_id"
                                        v-text="errors.partida_id[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label
                                        :class="{
                                            'text-danger': errors.descripcion,
                                        }"
                                        >Descripción*</label
                                    >
                                    <el-input
                                        type="textarea"
                                        autosize
                                        class="w-100"
                                        :class="{
                                            'is-invalid': errors.descripcion,
                                        }"
                                        v-model="item_mod.descripcion"
                                        readonly
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.descripcion"
                                        v-text="errors.descripcion[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-1">
                                    <label
                                        :class="{
                                            'text-danger': errors.nro,
                                        }"
                                        >Nro*</label
                                    >
                                    <el-input
                                        class="w-100"
                                        :class="{
                                            'is-invalid': errors.nro,
                                        }"
                                        v-model="item_mod.nro"
                                        clearable
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.nro"
                                        v-text="errors.nro[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label
                                        :class="{
                                            'text-danger':
                                                errors.descripcion_detallada,
                                        }"
                                        >Descripción detallada por item (bien o
                                        servicio)*</label
                                    >
                                    <el-input
                                        type="textarea"
                                        autosize
                                        class="w-100"
                                        :class="{
                                            'is-invalid':
                                                errors.descripcion_detallada,
                                        }"
                                        v-model="item_mod.descripcion_detallada"
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.descripcion_detallada"
                                        v-text="errors.descripcion_detallada[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label
                                        :class="{
                                            'text-danger': errors.cantidad,
                                        }"
                                        >Cantidad*</label
                                    >
                                    <input
                                        type="number"
                                        step="0.01"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.cantidad,
                                        }"
                                        v-model="item_mod.cantidad"
                                        clearable
                                        @change="calculaTotal(index_mod)"
                                        @keyup="calculaTotal(index_mod)"
                                    />
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.cantidad"
                                        v-text="errors.cantidad[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label
                                        :class="{
                                            'text-danger': errors.unidad,
                                        }"
                                        >Unidad*</label
                                    >
                                    <el-input
                                        class="w-full"
                                        :class="{
                                            'is-invalid': errors.unidad,
                                        }"
                                        v-model="item_mod.unidad"
                                        clearable
                                    ></el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.unidad"
                                        v-text="errors.unidad[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label
                                        :class="{
                                            'text-danger': errors.costo,
                                        }"
                                        >Precio Unitario*</label
                                    >
                                    <input
                                        type="number"
                                        step="0.01"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.costo,
                                        }"
                                        v-model="item_mod.costo"
                                        clearable
                                        @change="calculaTotal(index_mod)"
                                        @keyup="calculaTotal(index_mod)"
                                    />
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.costo"
                                        v-text="errors.costo[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label
                                        :class="{
                                            'text-danger': errors.total,
                                        }"
                                        >Total*</label
                                    >
                                    <el-input
                                        class="w-full"
                                        placeholder="Total"
                                        :class="{
                                            'is-invalid': errors.total,
                                            monto_invalido:
                                                error_totales[index_mod],
                                            monto_valido:
                                                !error_totales[index_mod],
                                        }"
                                        v-model="item_mod.total"
                                        clearable
                                        readonly
                                    ></el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.total"
                                        v-text="errors.total[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label
                                        :class="{
                                            'text-danger': errors.justificacion,
                                        }"
                                        >Justificación*</label
                                    >
                                    <el-input
                                        type="textarea"
                                        autosize
                                        class="w-100"
                                        :class="{
                                            'is-invalid': errors.justificacion,
                                        }"
                                        v-model="item_mod.justificacion"
                                        clearable
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.justificacion"
                                        v-text="errors.justificacion[0]"
                                    ></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 contenedor_tabla">
                                    <table
                                        class="table table-bordered tabla_programacion"
                                    >
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-center"
                                                    colspan="12"
                                                >
                                                    Programación Mensual
                                                </th>
                                            </tr>
                                            <tr>
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
                                                <th class="text-center">
                                                    TOTAL
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="detalle_trimestres">
                                            <tr>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.ene"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.feb"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.mar"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.abr"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.may"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.jun"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.jul"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.ago"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.sep"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.oct"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.nov"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        v-model="item_mod.dic"
                                                        @change="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        @keypress.enter.prevent="
                                                            calculaTotalOperacionMeses(
                                                                index_mod
                                                            )
                                                        "
                                                        class="form-control"
                                                    />
                                                </td>
                                                <td
                                                    class="text-center font-weight-bold text-md align-middle"
                                                    :class="{
                                                        'bg-danger':
                                                            error_totales[
                                                                index_mod
                                                            ],
                                                        'bg-success':
                                                            !error_totales[
                                                                index_mod
                                                            ],
                                                    }"
                                                    v-text="
                                                        parseFloat(
                                                            item_mod.total_actividad
                                                        ).toFixed(2)
                                                    "
                                                ></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTON AGREGAR DETALLE -->
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <button
                                class="btn btn-default btn-flat btn-block"
                                @click="agregarDetalle"
                            >
                                <i class="fa fa-plus"></i>
                                Agregar detalle
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body bg-primary">
                            <h4 class="text-md">
                                TOTAL OPERACIÓN:
                                {{
                                    o_Operacion.total_operacion &&
                                    o_Operacion.total_operacion != ""
                                        ? parseFloat(
                                              o_Operacion.total_operacion
                                          ).toFixed(2)
                                        : "0.00"
                                }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>
<script>
export default {
    props: {
        index: {
            type: Number,
            default: 0,
        },
        formulario_id: {
            type: String,
            default: "",
        },
        operacion: {
            type: Object,
            default: {
                id: 0,
                memoria_id: "",
                operacion_id: "",
                detalle_operacion_id: "",
                total_operacion: 0,
                memoria_operacion_detalles: [
                    {
                        ue: "",
                        prog: "",
                        act: "",
                        lugar: "",
                        responsable: "",
                        partida: "",
                        nro: "",
                        descripcion: "",
                        cantidad: "",
                        unidad: "",
                        costo: "",
                        total: 0,
                        justificacion: "",
                        ene: "",
                        feb: "",
                        mar: "",
                        abr: "",
                        may: "",
                        jun: "",
                        jul: "",
                        ago: "",
                        sep: "",
                        oct: "",
                        nov: "",
                        dic: "",
                        total_actividad: 0,
                    },
                ],
            },
        },
        accion: {
            type: String,
            default: "create",
        },
    },
    data() {
        return {
            sw_accion: this.accion,
            errors: [],
            o_Operacion: this.operacion,
            formulario_cuatro_id: this.formulario_id,
            listOperaciones: [],
            listPartidas: [],
            texto_operacion: "",
            texto_actividad: "",
            error_totales: [],
        };
    },
    mounted() {
        this.getOperacionesFormulario();
        this.getPartidas();
        if (this.operacion.id != 0) {
            this.o_Operacion.memoria_operacion_detalles.forEach(
                (elem_detalle, index) => {
                    this.verificaTotales(index);
                    // setTimeout(() => {
                    //     this.getTextoPartida(index);
                    // }, 300);
                }
            );
        }
    },
    watch: {
        formulario_id(newVal, oldVal) {
            this.formulario_cuatro_id = newVal;
            this.getOperacionesFormulario();
            this.o_Operacion.operacion_id = "";
            this.texto_operacion = "";
        },
        operacion(newVal, oldVal) {
            this.o_Operacion = newVal;
        },
    },
    methods: {
        // OBTENER LAS PARTIDAS
        getPartidas() {
            axios.get("/admin/partidas").then((response) => {
                this.listPartidas = response.data.partidas;
            });
        },
        getTextoPartida(index_mod = null) {
            console.log(
                this.o_Operacion.memoria_operacion_detalles[index_mod].id
            );
            if (index_mod != null) {
                let item = this.listPartidas.filter(
                    (value) =>
                        value.id ==
                        this.o_Operacion.memoria_operacion_detalles[index_mod]
                            .partida_id
                );
                if (item.length > 0) {
                    this.o_Operacion.memoria_operacion_detalles[
                        index_mod
                    ].partida = item[0].partida;
                    this.o_Operacion.memoria_operacion_detalles[
                        index_mod
                    ].descripcion = item[0].descripcion;
                } else {
                    this.o_Operacion.memoria_operacion_detalles[
                        index_mod
                    ].partida = "";
                    this.o_Operacion.memoria_operacion_detalles[
                        index_mod
                    ].descripcion = "";
                }
            }
        },
        // OBTENER LAS OPERACIONES DEL FORMULARIO CUATRO
        getOperacionesFormulario() {
            axios
                .get("/admin/formulario_cuatro/getOperaciones", {
                    params: { id: this.formulario_cuatro_id },
                })
                .then((response) => {
                    this.listOperaciones = response.data;
                    if (this.o_Operacion.operacion_id != "") {
                        this.getTextoOperacion();
                    }
                });
        },
        getTextoOperacion() {
            let item = this.listOperaciones.filter(
                (value) => value.id == this.o_Operacion.operacion_id
            );
            if (item.length > 0) {
                // get actividades de la operacion
                axios
                    .get("/admin/operacions/getTareas", {
                        params: { id: this.o_Operacion.operacion_id },
                    })
                    .then((response) => {
                        if (this.o_Operacion.id != 0) {
                            let aux_texto = this.texto_operacion;
                            this.texto_operacion = "";
                            this.texto_operacion = aux_texto;
                        }
                        this.texto_operacion = item[0].operacion;
                        if (this.o_Operacion.operacion_id == "") {
                            this.o_Operacion.detalle_operacion_id = "";
                        }
                        this.o_Operacion.detalle_operaciones = response.data;
                        if (this.o_Operacion.detalle_operacion_id != "") {
                            this.getTextoActividad();
                        }
                    });
            } else {
                this.o_Operacion.detalle_operaciones = [];
                this.texto_operacion = "";
            }
        },
        getTextoActividad() {
            let item = this.o_Operacion.detalle_operaciones.filter(
                (value) => value.id == this.o_Operacion.detalle_operacion_id
            );
            if (item.length > 0) {
                this.texto_actividad = item[0].actividad_tarea;
            } else {
                this.texto_actividad = "";
            }
        },

        // QUITAR OPERACION
        quitar() {
            this.$emit("quitar", this.index, this.operacion);
        },
        // DETALLES OPERACIONES
        agregarDetalle() {
            this.o_Operacion.memoria_operacion_detalles.push({
                id: 0,
                memoria_operacion_id: 0,
                ue: "",
                prog: "",
                act: "",
                lugar: "",
                responsable: "",
                partida: "",
                nro: "",
                descripcion: "",
                cantidad: "",
                unidad: "",
                costo: "",
                total: 0,
                justificacion: "",
                ene: "",
                feb: "",
                mar: "",
                abr: "",
                may: "",
                jun: "",
                jul: "",
                ago: "",
                sep: "",
                oct: "",
                nov: "",
                dic: "",
                total_actividad: 0,
            });
        },
        quitarDetalle(index_mod, id) {
            this.o_Operacion.memoria_operacion_detalles.splice(index_mod, 1);
            if (id != 0) {
                this.$emit("quitar_detalle", id);
            }
        },
        // FIN DETALLES
        calculaTotal(index_mod) {
            let total = 0;
            if (
                this.o_Operacion.memoria_operacion_detalles[index_mod]
                    .cantidad != "" &&
                this.o_Operacion.memoria_operacion_detalles[index_mod].costo !=
                    ""
            ) {
                this.o_Operacion.memoria_operacion_detalles[index_mod].total =
                    parseFloat(
                        this.o_Operacion.memoria_operacion_detalles[index_mod]
                            .cantidad
                    ) *
                    parseFloat(
                        this.o_Operacion.memoria_operacion_detalles[index_mod]
                            .costo
                    );
                this.o_Operacion.memoria_operacion_detalles[index_mod].total =
                    this.o_Operacion.memoria_operacion_detalles[
                        index_mod
                    ].total.toFixed(2);
            }
            this.calculaTotalOperacion();
            this.verificaTotales(index_mod);
        },
        calculaTotalOperacionMeses(index_mod) {
            this.o_Operacion.memoria_operacion_detalles[
                index_mod
            ].total_actividad = 0;
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].ene) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].ene
                );
                this.o_Operacion.ene = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].ene
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].feb) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].feb
                );
                this.o_Operacion.feb = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].feb
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].mar) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].mar
                );
                this.o_Operacion.mar = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].mar
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].abr) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].abr
                );
                this.o_Operacion.abr = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].abr
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].may) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].may
                );
                this.o_Operacion.may = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].may
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].jun) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].jun
                );
                this.o_Operacion.jun = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].jun
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].jul) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].jul
                );
                this.o_Operacion.jul = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].jul
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].ago) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].ago
                );
                this.o_Operacion.ago = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].ago
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].sep) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].sep
                );
                this.o_Operacion.sep = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].sep
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].oct) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].oct
                );
                this.o_Operacion.oct = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].oct
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].nov) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].nov
                );
                this.o_Operacion.nov = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].nov
                ).toFixed(2);
            }
            if (this.o_Operacion.memoria_operacion_detalles[index_mod].dic) {
                this.o_Operacion.memoria_operacion_detalles[
                    index_mod
                ].total_actividad += parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].dic
                );
                this.o_Operacion.dic = parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].dic
                ).toFixed(2);
            }
            this.verificaTotales(index_mod);
        },
        verificaTotales(index_mod) {
            this.error_totales[index_mod] = false;
            if (
                parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod].total
                ) !=
                parseFloat(
                    this.o_Operacion.memoria_operacion_detalles[index_mod]
                        .total_actividad
                )
            ) {
                this.error_totales[index_mod] = true;
            }

            let existe_error_totales = this.error_totales.filter(
                (element) => element == true
            );
            if (existe_error_totales.length > 0) {
                this.$emit("sw_guardar", false);
            } else {
                this.$emit("sw_guardar", true);
            }
        },
        calculaTotalOperacion() {
            let total = 0;
            this.o_Operacion.memoria_operacion_detalles.forEach((item) => {
                total += parseFloat(item.total);
            });
            this.o_Operacion.total_operacion = total;
        },
    },
};
</script>
<style>
.row.detalle {
    position: relative;
}

.numero_detalle {
    padding: 2px 0px;
    background: var(--principal);
    width: 37px;
    height: 37px;
    position: absolute;
    left: -15px;
    top: -15px;
    color: white;
    text-align: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.numero_operacion_detalle {
    padding: 2px;
    background: var(--secondary);
    width: auto;
    height: auto;
    position: absolute;
    z-index: 100;
    left: -15px;
    top: -15px;
    color: white;
    text-align: center;
    font-weight: bold;
    font-size: 0.75rem;
    border-radius: 5px;
}

.numero_operacion_detalle_tarea {
    padding: 2px;
    background: var(--secondary);
    width: auto;
    height: auto;
    position: absolute;
    z-index: 100;
    left: -2px;
    top: -15px;
    color: white;
    text-align: center;
    font-weight: bold;
    font-size: 0.7rem;
    border-radius: 3px;
}

.btnQuitar {
    padding: 0px;
    width: 30px;
    height: 30px;
    position: absolute;
    right: -10px;
    top: -10px;
}

.fila_tarea,
.fila_partida {
    position: relative;
}

.fila_tarea {
    border: solid 1px #20c997 !important;
    padding-bottom: 5px;
}
.fila_partida {
    border: solid 1px #cfcdcd !important;
}

.fila_tarea input,
.fila_tarea textarea,
.fila_tarea select,
.contenedor_operacion input,
.contenedor_operacion textarea,
.contenedor_operacion select,
.fila_partida input,
.fila_partida textarea,
.fila_partida select {
    font-size: 0.75rem;
}

.fila_tarea .btnQuitar,
.fila_partida .btnQuitar {
    right: -20px;
    top: -10px;
    width: 25px;
    height: 25px;
    font-size: 0.85rem;
}

.contenedor_tabla .card-header,
.contenedor_tabla .card-body {
    padding: 10px;
}

.contenedor_tabla .card-body {
    overflow: auto;
}

.titulo_destalle {
    font-size: 1.1rem;
}

td.eliminar {
    position: relative;
}

td.eliminar .btnQuitar {
    width: 20px;
    height: 20px;
    position: absolute;
    font-size: 0.8rem;
    right: -13px;
    top: 0px;
}

.contenedor_operacion label {
    margin: 0px;
    position: absolute;
    font-size: 0.7rem;
    top: -13px;
    left: 12px;
    padding: 0px 5px;
    background: white;
    z-index: 3;
    border-radius: 9px;
    max-width: 100%;
    word-wrap: none;
}

textarea[readonly] {
    background: #ebebeb;
}

.contenedor_operacion hr {
    border-top: solid 3px black;
}

.tabla_programacion thead tr th,
.tabla_programacion tbody tr td {
    font-size: 0.7em;
}

.tabla_programacion tbody tr td input {
    padding: 2px;
}

.monto_invalido input {
    background: #dc3545;
    color: white;
}
.monto_valido input {
    background: #28a745;
    color: white;
}
</style>
