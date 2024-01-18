<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="card-title w-full font-weight-bold">
                        <i class="fas fa-edit"></i>
                        Editar Registro
                    </h3>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="contenedor_pasos text-center">
                        <button
                            class="paso"
                            v-for="(paso, index) in listPasos"
                            :key="index"
                            :class="{
                                active: nro_paso == paso.nro,
                                error: paso.error,
                            }"
                            @click="cambiaPaso(paso.nro)"
                        >
                            <div class="nro_paso" v-text="paso.nro"></div>
                            <div class="txt">
                                {{ paso.label }}
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 ml-auto mr-auto">
                    <div class="row">
                        <div class="col-md-6 p-0 mb-3">
                            <el-button
                                v-if="nro_paso > 1"
                                class="btn btn-primary bg-light btn-flat btn-block"
                                :loading="enviando"
                                @click="cambiaPaso(nro_paso - 1)"
                                ><i class="fa fa-arrow-left"></i>
                                Anterior</el-button
                            >
                        </div>
                        <div class="col-md-6 p-0 mb-3">
                            <el-button
                                v-if="nro_paso < listPasos.length"
                                class="btn btn-primary bg-light btn-flat btn-block"
                                :loading="enviando"
                                @click="cambiaPaso(nro_paso + 1)"
                                >Siguiente <i class="fa fa-arrow-right"></i
                            ></el-button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div
                    v-if="nro_paso == 1"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <label
                        :class="{
                            'text-danger': errors.formulario_id,
                        }"
                        >Seleccionar código POA*</label
                    >
                    <el-select
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.poa_seleccionado,
                        }"
                        v-model="oCertificacion.poa_seleccionado"
                        clearable
                        @change="getOperacionesMemoriaCalculo"
                    >
                        <el-option
                            v-for="(item, index_form) in listFormularios"
                            :key="index_form"
                            :value="item.poa_seleccionado"
                            :label="item.codigo_poa"
                        >
                        </el-option>
                    </el-select>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.poa_seleccionado"
                        v-text="errors.poa_seleccionado[0]"
                    ></span>
                </div>
                <div
                    v-if="nro_paso == 2"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <label
                        :class="{
                            'text-danger': errors.mo_id,
                        }"
                        >Seleccionar código de operación*</label
                    >
                    <el-select
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.mo_id,
                        }"
                        v-model="oCertificacion.mo_id"
                        clearable
                        @change="
                            getDetalles();
                            oCertificacion.certificacion_detalles.map(
                                (elem) => {
                                    elem.mod_id = '';
                                }
                            );
                            listDetalles = [];
                        "
                    >
                        <el-option
                            v-for="item in listOperaciones"
                            :key="item.id"
                            :value="item.id"
                            :label="
                                item.codigo_operacion +
                                ' | ' +
                                item.codigo_actividad +
                                ': ' +
                                item.descripcion_actividad
                            "
                        >
                        </el-option>
                    </el-select>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.mo_id"
                        v-text="errors.mo_id[0]"
                    ></span>
                    <template
                        v-for="(
                            certificacion_detalle, index
                        ) in oCertificacion.certificacion_detalles"
                    >
                        <label
                            :class="{
                                'text-danger': errors.mod_id,
                            }"
                            >Seleccionar detalle {{ index + 1 }}*
                            <button
                                type="button"
                                v-if="index > 0"
                                class="btn btn-danger btn-xs btn-flat"
                                @click="
                                    quitarDetalle(
                                        index,
                                        certificacion_detalle.id
                                    )
                                "
                            >
                                <i class="fa fa-times"></i></button
                        ></label>
                        <el-select
                            filterable
                            class="w-100 d-block"
                            :class="{
                                'is-invalid': errors.mod_id,
                            }"
                            v-model="certificacion_detalle.mod_id"
                            clearable
                            @change="
                                getDetalleOperacion(index);
                                if (accion != 'edit') {
                                    certificacion_detalle.cantidad_usar =
                                        certificacion_detalle.memoria_operacion_detalle.saldo_cantidad;
                                }
                            "
                        >
                            <el-option
                                v-for="item in listDetalles"
                                :key="item.id"
                                :value="item.id"
                                :label="item.partida + ' - ' + item.descripcion"
                            >
                            </el-option>
                        </el-select>
                        <span
                            class="error invalid-feedback"
                            v-if="errors.mod_id"
                            v-text="errors.mod_id[0]"
                        ></span>
                        <div class="row mt-1" v-if="oCertificacion.mo_id != ''">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="w-full text-center">
                                            Detalle
                                        </h4>
                                        <p>
                                            <strong>Descripción: </strong
                                            >{{
                                                certificacion_detalle
                                                    .memoria_operacion_detalle
                                                    .descripcion
                                            }}
                                        </p>
                                        <p>
                                            <strong
                                                >Cantidad requerida actual: </strong
                                            >{{
                                                certificacion_detalle
                                                    .memoria_operacion_detalle
                                                    .cantidad
                                            }}
                                        </p>
                                        <p>
                                            <strong>Unidad: </strong
                                            >{{
                                                certificacion_detalle
                                                    .memoria_operacion_detalle
                                                    .unidad
                                            }}
                                        </p>
                                        <p>
                                            <strong>Costo Unitario: </strong
                                            >{{
                                                certificacion_detalle
                                                    .memoria_operacion_detalle
                                                    .costo
                                            }}
                                        </p>
                                        <p>
                                            <strong>Total: </strong
                                            >{{
                                                certificacion_detalle
                                                    .memoria_operacion_detalle
                                                    .total
                                            }}
                                        </p>
                                        <p>
                                            <strong>Saldo: </strong>
                                            <span
                                                class="text-md"
                                                :class="{
                                                    'text-danger font-weight-bold':
                                                        parseFloat(
                                                            certificacion_detalle
                                                                .memoria_operacion_detalle
                                                                .saldo
                                                        ) <= 0,
                                                }"
                                            >
                                                {{
                                                    certificacion_detalle
                                                        .memoria_operacion_detalle
                                                        .saldo
                                                }}</span
                                            >
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button
                                type="button"
                                class="btn btn-primary btn-block"
                                @click="agregarDetalle"
                            >
                                Agregar detalle
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    v-if="nro_paso == 3"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 border-right-0 p-3"
                >
                    <div
                        class="row mt-2"
                        v-for="(
                            certificacion_detalle, index_cantidad
                        ) in oCertificacion.certificacion_detalles"
                    >
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="mb-0">{{
                                        certificacion_detalle
                                            .memoria_operacion_detalle
                                            .descripcion
                                    }}</label>
                                </div>
                                <div class="col-md-6">
                                    <!-- CANTIDAD -->
                                    <label
                                        :class="{
                                            'text-danger':
                                                errors[
                                                    'cantidad_usar_' +
                                                        index_cantidad
                                                ],
                                        }"
                                        >Ingresar cantidad a utilizar*</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                errors[
                                                    'cantidad_usar_' +
                                                        index_cantidad
                                                ],
                                        }"
                                        step="0.01"
                                        v-model="
                                            certificacion_detalle.cantidad_usar
                                        "
                                        @change="
                                            validaCantidadUsar(index_cantidad)
                                        "
                                        @keyup="
                                            validaCantidadUsar(index_cantidad)
                                        "
                                    />
                                    <span
                                        class="error invalid-feedback"
                                        v-if="
                                            errors[
                                                'cantidad_usar_' +
                                                    index_cantidad
                                            ]
                                        "
                                        v-text="
                                            errors[
                                                'cantidad_usar_' +
                                                    index_cantidad
                                            ][0]
                                        "
                                    ></span>
                                </div>
                                <div class="col-md-6">
                                    <label
                                        :class="{
                                            'text-danger':
                                                errors[
                                                    'presupuesto_usarse_' +
                                                        index_cantidad
                                                ],
                                        }"
                                        >Monto a utilizar*</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                errors[
                                                    'presupuesto_usarse_' +
                                                        index_cantidad
                                                ],
                                        }"
                                        step="0.01"
                                        v-model="
                                            certificacion_detalle.presupuesto_usarse
                                        "
                                        @change="
                                            actualizaSaldos(index_cantidad)
                                        "
                                        @keyup="actualizaSaldos(index_cantidad)"
                                    />
                                    <span
                                        class="error invalid-feedback"
                                        v-if="
                                            errors[
                                                'presupuesto_usarse_' +
                                                    index_cantidad
                                            ]
                                        "
                                        v-text="
                                            errors[
                                                'presupuesto_usarse_' +
                                                    index_cantidad
                                            ][0]
                                        "
                                    ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="nro_paso == 4"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <label
                        :class="{
                            'text-danger': errors.archivo,
                        }"
                        >Adjuntar archivo</label
                    >
                    <input
                        type="file"
                        ref="archivo"
                        class="form-control"
                        @change="cargaArchivo"
                    />
                    <span
                        class="error invalid-feedback"
                        v-if="errors.archivo"
                        v-text="errors.archivo[0]"
                    ></span>
                </div>
                <div
                    v-if="nro_paso == 5"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <label
                        :class="{
                            'text-danger': errors.correlativo,
                        }"
                        >Nro. correlativo*</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        v-model="oCertificacion.correlativo"
                        readonly
                    />
                    <span
                        class="error invalid-feedback"
                        v-if="errors.correlativo"
                        v-text="errors.correlativo[0]"
                    ></span>
                </div>
                <div
                    v-if="nro_paso == 6"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <label
                        :class="{
                            'text-danger': errors.solicitante_id,
                        }"
                        >Seleccionar datos del solicitante*</label
                    >

                    <el-select
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.solicitante_id,
                        }"
                        v-model="oCertificacion.solicitante_id"
                    >
                        <el-option
                            v-for="item in listPersonals"
                            :key="item.id"
                            :value="item.id"
                            :label="item.full_name"
                        >
                        </el-option>
                    </el-select>
                    <!-- <el-select
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.solicitante_id,
                        }"
                        v-model="oCertificacion.solicitante_id"
                        clearable
                    >
                        <el-option
                            v-for="item in listUsuarios"
                            :key="item.id"
                            :value="item.id"
                            :label="item.full_name"
                        >
                        </el-option>
                    </el-select> -->
                    <span
                        class="error invalid-feedback"
                        v-if="errors.solicitante_id"
                        v-text="errors.solicitante_id[0]"
                    ></span>
                </div>
                <div
                    v-if="nro_paso == 7"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <label
                        :class="{
                            'text-danger': errors.superior_id,
                        }"
                        >Seleccionar datos del inmediato superior que
                        aprueba*</label
                    >
                    <el-select
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.superior_id,
                        }"
                        v-model="oCertificacion.superior_id"
                        clearable
                    >
                        <el-option
                            v-for="item in listUsuarios"
                            :key="item.id"
                            :value="item.id"
                            :label="item.full_name"
                        >
                        </el-option>
                    </el-select>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.superior_id"
                        v-text="errors.superior_id[0]"
                    ></span>
                </div>
                <div
                    v-if="nro_paso == 8"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <div class="row">
                        <div class="col-md-6">
                            <label
                                :class="{
                                    'text-danger': errors.inicio,
                                }"
                                >Fecha inicio*</label
                            >
                            <input
                                type="date"
                                class="form-control"
                                v-model="oCertificacion.inicio"
                                :class="{
                                    'is-invalid': errors.inicio,
                                }"
                            />
                            <span
                                class="error invalid-feedback"
                                v-if="errors.inicio"
                                v-text="errors.inicio[0]"
                            ></span>
                        </div>
                        <div class="col-md-6">
                            <label
                                :class="{
                                    'text-danger': errors.final,
                                }"
                                >Fecha final*</label
                            >
                            <input
                                type="date"
                                class="form-control"
                                v-model="oCertificacion.final"
                                :class="{
                                    'is-invalid': errors.final,
                                }"
                            />
                            <span
                                class="error invalid-feedback"
                                v-if="errors.final"
                                v-text="errors.final[0]"
                            ></span>
                        </div>
                    </div>
                </div>
                <div
                    v-if="nro_paso == 9"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <label
                        :class="{
                            'text-danger': errors.personal_designado,
                        }"
                        >Personal designado*</label
                    >
                    <el-select
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.personal_designado,
                        }"
                        v-model="oCertificacion.personal_designado"
                    >
                        <el-option
                            v-for="item in listPersonals"
                            :key="item.id"
                            :value="item.id"
                            :label="item.full_name"
                        >
                        </el-option>
                    </el-select>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.personal_designado"
                        v-text="errors.personal_designado[0]"
                    ></span>
                </div>
                <div
                    v-if="nro_paso == 10"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <label
                        :class="{
                            'text-danger': errors.departamento,
                        }"
                        >Departamento</label
                    >

                    <el-select
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.departamento,
                        }"
                        v-model="oCertificacion.departamento"
                    >
                        <el-option
                            v-for="(item, index) in [
                                'LA PAZ',
                                'COCHABAMBA',
                                'SANTA CRUZ',
                                'BENI',
                                'PANDO',
                                'POTOSÍ',
                                'ORURO',
                                'CHUQUISACA',
                                'TARIJA',
                            ]"
                            :key="index"
                            :value="item"
                            :label="item"
                        >
                        </el-option>
                    </el-select>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.departamento"
                        v-text="errors.departamento[0]"
                    ></span>
                </div>
                <div
                    v-if="nro_paso == 11"
                    class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                >
                    <label
                        :class="{
                            'text-danger': errors.municipio,
                        }"
                        >Municipio</label
                    >
                    <el-input
                        filterable
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.municipio,
                        }"
                        v-model="oCertificacion.municipio"
                        clearable
                    >
                    </el-input>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.municipio"
                        v-text="errors.municipio[0]"
                    ></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 ml-auto mr-auto">
                    <el-button
                        v-if="muestraBoton"
                        class="btn btn-primary bg-primary btn-flat btn-block"
                        :loading="enviando"
                        @click="enviaRegistro()"
                        ><i class="fa fa-save"></i>
                        <template v-if="accion == 'edit'"
                            >Actualizar Registro</template
                        >
                        <template v-else>Registrar</template>
                    </el-button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["id", "accion", "certificacion"],
    data() {
        return {
            listPasos: [
                {
                    nro: 1,
                    label: "Código POA",
                    key: "formulario_id",
                    error: false,
                },
                {
                    nro: 2,
                    label: "Operación | Tarea/Actividad | Partida ",
                    key: "mo_id",
                    error: false,
                },
                {
                    nro: 3,
                    label: "Cantidad",
                    key: "cantidad_usar",
                    error: false,
                },
                { nro: 4, label: "Archivo", key: "archivo", error: false },
                {
                    nro: 5,
                    label: "Nro. Correlativo",
                    key: "correlativo",
                    error: false,
                },
                {
                    nro: 6,
                    label: "Solicitante",
                    key: "solicitante_id",
                    error: false,
                },
                {
                    nro: 7,
                    label: "Inmediato Superior",
                    key: "superior_id",
                    error: false,
                },
                {
                    nro: 8,
                    label: "Inicio y Final",
                    key: "inicio_final",
                    error: false,
                },
                {
                    nro: 9,
                    label: "Personal designado",
                    key: "personal_designado",
                    error: false,
                },
                {
                    nro: 10,
                    label: "Departamento",
                    key: "departamento",
                    error: false,
                },
                {
                    nro: 11,
                    label: "Municipio",
                    key: "municipio",
                    error: false,
                },
            ],
            nro_paso: 1,
            listFormularios: [],
            listUsuarios: [],
            listPersonals: [],
            listOperaciones: [],
            listTareas: [],
            listDetalles: [],
            saldo_edicion: 0,
            oCertificacion: this.certificacion,
            errors: [],
            enviando: false,
            eliminados: [],
            saldos_aux: {},
        };
    },
    watch: {
        certificacion(newVal, oldVal) {
            this.oCertificacion = newVal;
            if (this.oCertificacion.id && this.oCertificacion.id != 0) {
                this.getOperacionesMemoriaCalculo();
            }
            if (this.oCertificacion.certificacion_detalles.length == 0) {
                // asignar un detalle por defecto
                this.agregarDetalle();
            }
        },
    },
    computed: {
        tituloFormulario() {
            return this.accion == "edit" ? "Editar registro" : "Nuevo registro";
        },
        muestraBoton() {
            if (this.accion == "edit") {
                return true;
            } else {
                if (this.nro_paso == this.listPasos.length) return true;
            }
            return false;
        },
    },
    mounted() {
        this.getFormularios();
        this.getUsuarios();
        this.getPersonals();
        if (this.accion != "edit") {
            this.getCorrelativo();
        }
    },
    methods: {
        // MANEJAR PASOS
        cambiaPaso(paso) {
            this.nro_paso = paso;
            if (this.nro_paso < 1) {
                this.nro_paso = 1;
            }
            if (this.nro_paso > this.listPasos.length) {
                this.nro_paso = this.listPasos.length;
            }
        },
        // Obtener la lista de los formularios cuatro
        getFormularios() {
            axios
                .get("/admin/formulario_cuatro/listado_index")
                .then((response) => {
                    console.log(response.data.listado);
                    this.listFormularios = response.data.listado;
                });
        },
        getUsuarios() {
            axios.get("/admin/usuarios").then((response) => {
                this.listUsuarios = response.data.usuarios;
            });
        },
        getPersonals() {
            axios.get("/admin/personals").then((response) => {
                this.listPersonals = response.data.personals;
            });
        },
        getCorrelativo() {
            axios
                .get("/admin/certificacions/getNroCorrelativo")
                .then((response) => {
                    this.oCertificacion.correlativo = response.data;
                });
        },
        validaCantidadUsar(index) {
            if (this.accion == "edit") {
                if (
                    this.oCertificacion.certificacion_detalles[index]
                        .cantidad_usar >
                    this.oCertificacion.certificacion_detalles[index].saldo_form
                ) {
                    this.oCertificacion.certificacion_detalles[
                        index
                    ].cantidad_usar =
                        this.oCertificacion.certificacion_detalles[
                            index
                        ].saldo_form;
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        html:
                            "La cantidad maxima permitida es de " +
                            this.oCertificacion.certificacion_detalles[index]
                                .saldo_form,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                } else {
                    this.getMontoPartida(index);
                }
            } else {
                if (
                    this.oCertificacion.certificacion_detalles[index]
                        .cantidad_usar >
                    this.oCertificacion.certificacion_detalles[index]
                        .memoria_operacion_detalle.saldo_cantidad
                ) {
                    this.oCertificacion.certificacion_detalles[
                        index
                    ].cantidad_usar =
                        this.oCertificacion.certificacion_detalles[
                            index
                        ].memoria_operacion_detalle.saldo_cantidad;

                    this.getMontoPartida(index);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        html:
                            "La cantidad maxima permitida es de " +
                            this.oCertificacion.certificacion_detalles[index]
                                .memoria_operacion_detalle.saldo_cantidad,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                } else {
                    this.getMontoPartida(index);
                }
            }
            this.actualizaSaldos(index);
        },
        actualizaSaldos(index) {
            if (this.accion == "edit") {
                if (!this.saldos_aux[index]) {
                    this.saldos_aux[index] =
                        parseFloat(
                            this.oCertificacion.certificacion_detalles[index]
                                .memoria_operacion_detalle.saldo
                        ) +
                        parseFloat(
                            this.oCertificacion.certificacion_detalles[index]
                                .presupuesto_usarse_aux
                        );
                }
            } else {
                this.saldos_aux[index] =
                    this.oCertificacion.certificacion_detalles[
                        index
                    ].memoria_operacion_detalle.saldo;
            }

            this.oCertificacion.certificacion_detalles[
                index
            ].memoria_operacion_detalle.saldo =
                parseFloat(
                    this.saldos_aux[index] ? this.saldos_aux[index] : 0
                ) -
                parseFloat(
                    this.oCertificacion.certificacion_detalles[index]
                        .presupuesto_usarse
                        ? this.oCertificacion.certificacion_detalles[index]
                              .presupuesto_usarse
                        : 0
                );
        },
        // lista de operaciones deacuerdo al formulario cuatro seleccionado
        getOperacionesMemoriaCalculo() {
            let array_formulario_id =
                this.oCertificacion.poa_seleccionado.split("|");
            this.oCertificacion.formulario_id = array_formulario_id[1];
            console.log(this.oCertificacion.formulario_id);
            console.log(this.oCertificacion.poa_seleccionado);
            axios
                .get("/admin/memoria_calculos/getOperaciones", {
                    params: {
                        formulario_id: this.oCertificacion.formulario_id,
                    },
                })
                .then((response) => {
                    this.listOperaciones = response.data;
                    this.cargaDetallesCertificacion();
                });
        },
        // textos codigos
        getDetalleOperacion(index) {
            let id_mod =
                this.oCertificacion.certificacion_detalles[index].mod_id;
            if (id_mod && id_mod != 0) {
                let operacion = this.listDetalles.filter(
                    (item) => item.id == id_mod
                )[0];
                this.oCertificacion.certificacion_detalles[
                    index
                ].memoria_operacion_detalle = operacion;
                let valor_saldo_cantidad =
                    this.oCertificacion.certificacion_detalles[index]
                        .memoria_operacion_detalle.saldo_cantidad;

                if (this.accion == "edit") {
                    // this.oCertificacion.certificacion_detalles[
                    //     index
                    // ].cantidad_usar = valor_saldo_cantidad;
                } else {
                    // create
                    this.oCertificacion.certificacion_detalles[
                        index
                    ].cantidad_usar =
                        this.oCertificacion.certificacion_detalles[
                            index
                        ].memoria_operacion_detalle.saldo_cantidad;
                }
                this.getMontoPartida(index);
                this.obtieneSaldo(index);
            }
            // console.log("AA");
            // console.log(
            // "Cantidad usar: " +
            // this.oCertificacion.certificacion_detalles[index]
            // .cantidad_usar
            // );
            // console.log("BVB");
        },
        cargaDetallesCertificacion() {
            let certificacion_detalles =
                this.oCertificacion.certificacion_detalles;
            if (certificacion_detalles.length > 0) {
                certificacion_detalles.forEach((elem, index) => {
                    axios
                        .get("/admin/memoria_operacion_detalles/getDetalles", {
                            params: {
                                id: elem.mo_id,
                            },
                        })
                        .then((response) => {
                            this.listDetalles = response.data;
                            this.oCertificacion.certificacion_detalles.forEach(
                                (elem, index) => {
                                    if (elem.mod_id != "" && elem.mo_id != "") {
                                        this.getDetalleOperacion(index);
                                    }
                                }
                            );
                        });
                });
            } else {
                this.agregarDetalle();
            }
        },
        getDetalles() {
            let id_mo = this.oCertificacion.mo_id;
            axios
                .get("/admin/memoria_operacion_detalles/getDetalles", {
                    params: {
                        id: id_mo,
                    },
                })
                .then((response) => {
                    this.listDetalles = response.data;
                    this.oCertificacion.certificacion_detalles.forEach(
                        (elem, index) => {
                            if (elem.mod_id && elem.mod_id != 0) {
                                this.getDetalleOperacion(index);
                            }
                        }
                    );
                });
        },

        getMontoPartida(index) {
            let cantidad_detalle =
                this.oCertificacion.certificacion_detalles[index].cantidad_usar;
            let valor_costo =
                this.oCertificacion.certificacion_detalles[index]
                    .memoria_operacion_detalle.costo;

            let monto_usarse = parseFloat(
                parseFloat(cantidad_detalle) * parseFloat(valor_costo)
            ).toFixed(2);
            if (
                this.oCertificacion.certificacion_detalles[index]
                    .cantidad_usar !=
                    this.oCertificacion.certificacion_detalles[index]
                        .cantidad_usar_aux ||
                this.accion != "edit"
            ) {
                console.log("asd");
                this.oCertificacion.certificacion_detalles[
                    index
                ].presupuesto_usarse = monto_usarse;
            }
        },
        obtieneSaldo(index) {
            let id_mod =
                this.oCertificacion.certificacion_detalles[index]
                    .memoria_operacion_detalle.id;
            if (
                this.oCertificacion.certificacion_detalles[index].id &&
                this.oCertificacion.certificacion_detalles[index] != 0 &&
                id_mod ==
                    this.oCertificacion.certificacion_detalles[index].mod_id
            ) {
                this.oCertificacion.certificacion_detalles[index].saldo_form =
                    parseFloat(
                        this.oCertificacion.certificacion_detalles[index]
                            .memoria_operacion_detalle.saldo_cantidad
                    ) +
                    parseFloat(
                        this.oCertificacion.certificacion_detalles[index]
                            .cantidad_usar
                    );
            } else {
                this.oCertificacion.certificacion_detalles[index].saldo_form =
                    this.oCertificacion.certificacion_detalles[
                        index
                    ].memoria_operacion_detalle.saldo_cantidad;
            }
        },
        ingresarEnter(valor) {
            return valor.replace(",", " | ");
        },
        agregarDetalle() {
            this.oCertificacion.certificacion_detalles.push({
                id: 0,
                certificacion_id:
                    this.oCertificacion.id == 0 ? 0 : this.oCertificacion.id,
                mo_id: "",
                mod_id: "",
                total_cantidad: 0,
                cantidad_usar: 0,
                saldo_cantidad: 0,
                total: 0,
                presupuesto_usarse: 0,
                saldo_total: 0,
                memoria_operacion_detalle: {
                    descripcion: "",
                    cantidad: 0,
                    unidad: "",
                    costo: 0,
                    total: 0,
                    saldo: 0,
                },
                listDetalles: [],
                cantidad_usar_aux: 0,
                presupuesto_usarse_aux: 0,
                saldo_form: 0,
            });
        },
        quitarDetalle(index, id) {
            if (id != 0) {
                this.eliminados.push(id);
            }
            this.oCertificacion.certificacion_detalles.splice(index, 1);
        },
        cargaArchivo(e) {
            this.oCertificacion.archivo = e.target.files[0];
            console.log(e);
            console.log(this.oCertificacion.archivo);
        },
        // ENVIAR FORMULARIO
        enviaRegistro() {
            if (this.validaSaldos()) {
                Swal.fire({
                    icon: "error",
                    title: "ERROR",
                    html: `Existe un registro de detalle seleccionado con saldo menor a <span class="text-md font-weight-bold text-red">0.00</span>`,
                    showConfirmButton: true,
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#0069d9",
                });
                this.listPasos[1].error = true;
                return false;
            }
            if (this.accion != "edit") {
                if (this.validaDetalleOperacion()) {
                    Swal.fire({
                        icon: "error",
                        title: "ERROR",
                        html: `Existe un registro con un detalle de operación que no se seleccionó`,
                        showConfirmButton: true,
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#0069d9",
                    });
                    this.listPasos[1].error = true;
                    return false;
                }
                if (this.validaPartida()) {
                    Swal.fire({
                        icon: "error",
                        title: "ERROR",
                        html: `Debes seleccionar un archivo para la partida 22210`,
                        showConfirmButton: true,
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#0069d9",
                    });
                    this.listPasos[3].error = true;
                    return false;
                }
            }
            this.listPasos[1].error = false;
            this.enviando = true;
            try {
                let config = {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                };
                let url = "/admin/certificacions";
                let formdata = new FormData();
                if (this.accion == "edit") {
                    url = "/admin/certificacions/" + this.id;
                    formdata.append("_method", "put");
                }
                formdata.append(
                    "formulario_id",
                    this.oCertificacion.formulario_id
                );
                formdata.append(
                    "poa_seleccionado",
                    this.oCertificacion.poa_seleccionado
                );

                let elemento_seleccionado = this.listFormularios.filter(
                    (elem) =>
                        elem.poa_seleccionado ==
                        this.oCertificacion.poa_seleccionado
                )[0];

                formdata.append("codigo", elemento_seleccionado.codigo);

                formdata.append("accion", elemento_seleccionado.accion);

                // array_valores
                this.oCertificacion.certificacion_detalles.forEach((elem) => {
                    formdata.append("ids[]", elem.id);
                    formdata.append("mod_id[]", elem.mod_id);
                    formdata.append("cantidad_usar[]", elem.cantidad_usar);
                    formdata.append(
                        "presupuesto_usarse[]",
                        elem.presupuesto_usarse
                    );
                });

                if (this.accion == "edit") {
                    this.eliminados.forEach((elem) => {
                        formdata.append("eliminados[]", elem);
                    });
                }

                formdata.append("archivo", this.oCertificacion.archivo);
                formdata.append(
                    "correlativo",
                    this.oCertificacion.correlativo
                        ? this.oCertificacion.correlativo
                        : ""
                );
                formdata.append(
                    "mo_id",
                    this.oCertificacion.mo_id ? this.oCertificacion.mo_id : ""
                );
                formdata.append(
                    "solicitante_id",
                    this.oCertificacion.solicitante_id
                        ? this.oCertificacion.solicitante_id
                        : ""
                );
                formdata.append(
                    "superior_id",
                    this.oCertificacion.superior_id
                        ? this.oCertificacion.superior_id
                        : ""
                );
                formdata.append(
                    "inicio",
                    this.oCertificacion.inicio ? this.oCertificacion.inicio : ""
                );
                formdata.append(
                    "final",
                    this.oCertificacion.final ? this.oCertificacion.final : ""
                );
                formdata.append(
                    "estado",
                    this.oCertificacion.estado ? this.oCertificacion.estado : ""
                );
                formdata.append(
                    "personal_designado",
                    this.oCertificacion.personal_designado
                        ? this.oCertificacion.personal_designado
                        : ""
                );
                formdata.append(
                    "departamento",
                    this.oCertificacion.departamento
                        ? this.oCertificacion.departamento
                        : ""
                );
                formdata.append(
                    "municipio",
                    this.oCertificacion.municipio
                        ? this.oCertificacion.municipio
                        : ""
                );

                axios
                    .post(url, formdata, config)
                    .then((res) => {
                        this.enviando = false;
                        Swal.fire({
                            icon: "success",
                            title: res.data.msj,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.errors = [];
                        this.$router.push({ name: "certificacions.index" });
                    })
                    .catch((error) => {
                        this.enviando = false;
                        if (error.response) {
                            if (
                                error.response.status === 420 ||
                                error.response.status === 419 ||
                                error.response.status === 401
                            ) {
                                window.location = "/";
                            }
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors;
                                this.muestraErrores();
                            } else {
                                if (error.response.status === 500) {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        html: error.response.data.message,
                                        showConfirmButton: false,
                                        timer: 3000,
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: error,
                                        showConfirmButton: false,
                                        timer: 2000,
                                    });
                                }
                            }
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: error,
                                showConfirmButton: false,
                                timer: 2000,
                            });
                        }
                    });
            } catch (e) {
                this.enviando = false;
                console.log(e);
            }
        },
        validaSaldos() {
            let sw = false;
            this.oCertificacion.certificacion_detalles.forEach((elem) => {
                console.log(elem);
                console.log(elem.memoria_operacion_detalle.saldo);
                if (parseFloat(elem.memoria_operacion_detalle.saldo) < 0) {
                    sw = true;
                }
            });

            return sw;
        },
        validaDetalleOperacion() {
            let sw = false;
            this.oCertificacion.certificacion_detalles.forEach((elem) => {
                if (elem.memoria_operacion_detalle == null) {
                    sw = true;
                }
            });

            return sw;
        },
        validaPartida() {
            let sw = false;
            this.oCertificacion.certificacion_detalles.forEach((elem) => {
                if (elem.memoria_operacion_detalle.partida == "22210") {
                    if (
                        !this.oCertificacion.archivo ||
                        this.oCertificacion.archivo == null ||
                        this.oCertificacion.archivo == ""
                    ) {
                        sw = true;
                    }
                }
            });

            return sw;
        },
        // ARMAR ERRORES
        muestraErrores() {
            let msj_errores = "";
            this.listPasos.forEach((paso) => {
                if (this.errors[paso.key] != undefined) {
                    paso.error = true;
                    msj_errores += this.errors[paso.key][0] + "<br/>";
                } else {
                    paso.error = false;
                }
                if (paso.key == "inicio_final") {
                    if (this.errors.inicio || this.errors.final) {
                        paso.error = true;
                        msj_errores += "Tienes un error en Inicio y Final<br/>";
                    }
                }
            });
            Swal.fire({
                icon: "error",
                title: "Tienes los siguientes errores",
                html: msj_errores,
                showConfirmButton: true,
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#0069d9",
            });
        },
    },
};
</script>
<style>
.contenedor_pasos {
    width: 100%;
    overflow: auto;
    padding: 0px;
    display: grid;
    grid-template-columns: repeat(11, 1fr);
}
.paso {
    display: flex;
    flex-direction: column;
    justify-content: center;
    vertical-align: center;
    padding: 4px;
    border: solid 1px rgb(226, 226, 226);
    font-size: 0.7rem;
}
.paso.active {
    color: white;
    background: var(--principal);
}

.paso.active .nro_paso {
    background: #2e93d9;
}

.paso.error {
    color: white;
    background: #d74040;
}

.paso.error .nro_paso {
    background: #f59090;
}

.nro_paso {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 2px auto;
    height: 25px;
    width: 25px;
    font-weight: bold;
    border-radius: 50%;
    background: rgb(210, 210, 210);
}
.txt {
    font-weight: 600;
    width: 100%;
    text-align: center;
}
</style>
