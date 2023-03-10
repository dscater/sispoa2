<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Certificación POA - <small>Nuevo</small></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 p-2">
                        <router-link
                            :to="{ name: 'certificacions.index' }"
                            class="btn btn-primary btn-block btn-flat"
                            ><i class="fa fa-arrow-left"></i>
                            Volver</router-link
                        >
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3
                                            class="card-title w-full font-weight-bold"
                                        >
                                            <i class="fas fa-edit"></i>
                                            Nuevo Registro
                                        </h3>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div
                                            class="contenedor_pasos text-center"
                                        >
                                            <button
                                                class="paso"
                                                v-for="(
                                                    paso, index
                                                ) in listPasos"
                                                :key="index"
                                                :class="{
                                                    active:
                                                        nro_paso == paso.nro,
                                                    error: paso.error,
                                                }"
                                                @click="cambiaPaso(paso.nro)"
                                            >
                                                <div
                                                    class="nro_paso"
                                                    v-text="paso.nro"
                                                ></div>
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
                                                    @click="
                                                        cambiaPaso(nro_paso - 1)
                                                    "
                                                    ><i
                                                        class="fa fa-arrow-left"
                                                    ></i>
                                                    Anterior</el-button
                                                >
                                            </div>
                                            <div class="col-md-6 p-0 mb-3">
                                                <el-button
                                                    v-if="
                                                        nro_paso <
                                                        listPasos.length
                                                    "
                                                    class="btn btn-primary bg-light btn-flat btn-block"
                                                    :loading="enviando"
                                                    @click="
                                                        cambiaPaso(nro_paso + 1)
                                                    "
                                                    >Siguiente
                                                    <i
                                                        class="fa fa-arrow-right"
                                                    ></i
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
                                                'text-danger':
                                                    errors.formulario_id,
                                            }"
                                            >Seleccionar código POA*</label
                                        >
                                        <el-select
                                            filterable
                                            class="w-100 d-block"
                                            :class="{
                                                'is-invalid':
                                                    errors.formulario_id,
                                            }"
                                            v-model="
                                                oCertificacion.formulario_id
                                            "
                                            clearable
                                            @change="
                                                getOperacionesMemoriaCalculo
                                            "
                                        >
                                            <el-option
                                                v-for="item in listFormularios"
                                                :key="item.id"
                                                :value="item.id"
                                                :label="item.codigo_poa"
                                            >
                                            </el-option>
                                        </el-select>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="errors.formulario_id"
                                            v-text="errors.formulario_id[0]"
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
                                            >Seleccionar código de
                                            operación*</label
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
                                                oCertificacion.mod_id = '';
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

                                        <label
                                            :class="{
                                                'text-danger': errors.mod_id,
                                            }"
                                            >Seleccionar detalle*</label
                                        >
                                        <el-select
                                            filterable
                                            class="w-100 d-block"
                                            :class="{
                                                'is-invalid': errors.mod_id,
                                            }"
                                            v-model="oCertificacion.mod_id"
                                            clearable
                                            @change="getDetalleOperacion"
                                        >
                                            <el-option
                                                v-for="item in listDetalles"
                                                :key="item.id"
                                                :value="item.id"
                                                :label="
                                                    item.partida +
                                                    ' - ' +
                                                    item.descripcion
                                                "
                                            >
                                            </el-option>
                                        </el-select>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="errors.mod_id"
                                            v-text="errors.mod_id[0]"
                                        ></span>

                                        <div
                                            class="row mt-1"
                                            v-if="
                                                oCertificacion.mo_id != '' &&
                                                oCertificacion.mod_id != ''
                                            "
                                        >
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4
                                                            class="w-full text-center"
                                                        >
                                                            Detalle
                                                        </h4>
                                                        <p>
                                                            <strong
                                                                >Descripción: </strong
                                                            >{{
                                                                oMOperacion.descripcion
                                                            }}
                                                        </p>
                                                        <p>
                                                            <strong
                                                                >Cantidad
                                                                requerida
                                                                actual: </strong
                                                            >{{
                                                                oMOperacion.cantidad
                                                            }}
                                                        </p>
                                                        <p>
                                                            <strong
                                                                >Unidad: </strong
                                                            >{{
                                                                oMOperacion.unidad
                                                            }}
                                                        </p>
                                                        <p>
                                                            <strong
                                                                >Costo Unitario: </strong
                                                            >{{
                                                                oMOperacion.costo
                                                            }}
                                                        </p>
                                                        <p>
                                                            <strong
                                                                >Total: </strong
                                                            >{{
                                                                oMOperacion.total
                                                            }}
                                                        </p>
                                                        <p>
                                                            <strong
                                                                >Saldo:
                                                            </strong>
                                                            <span
                                                                class="text-md"
                                                                :class="{
                                                                    'text-danger font-weight-bold':
                                                                        parseFloat(
                                                                            oMOperacion.saldo
                                                                        ) == 0,
                                                                }"
                                                            >
                                                                {{
                                                                    oMOperacion.saldo
                                                                }}</span
                                                            >
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        v-if="nro_paso == 3"
                                        class="form-group col-md-3 ml-auto border border-1 border-right-0 p-3"
                                    >
                                        <label
                                            :class="{
                                                'text-danger':
                                                    errors.cantidad_usar,
                                            }"
                                            >Ingresar cantidad a
                                            utilizar*</label
                                        >
                                        <input
                                            type="number"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    errors.cantidad_usar,
                                            }"
                                            step="0.01"
                                            v-model="
                                                oCertificacion.cantidad_usar
                                            "
                                            @change="validaCantidadUsar"
                                            @keyup="validaCantidadUsar"
                                        />
                                        <span
                                            class="error invalid-feedback"
                                            v-if="errors.cantidad_usar"
                                            v-text="errors.cantidad_usar[0]"
                                        ></span>
                                    </div>
                                    <div
                                        v-if="nro_paso == 3"
                                        class="form-group col-md-3 mr-auto border border-1 border-left-0 p-3"
                                    >
                                        <label
                                            :class="{
                                                'text-danger':
                                                    errors.presupuesto_usarse,
                                            }"
                                            >Monto a utilizar*</label
                                        >
                                        <input
                                            type="number"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    errors.presupuesto_usarse,
                                            }"
                                            step="0.01"
                                            v-model="
                                                oCertificacion.presupuesto_usarse
                                            "
                                        />
                                        <span
                                            class="error invalid-feedback"
                                            v-if="errors.presupuesto_usarse"
                                            v-text="
                                                errors.presupuesto_usarse[0]
                                            "
                                        ></span>
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
                                                'text-danger':
                                                    errors.correlativo,
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
                                                'text-danger':
                                                    errors.solicitante_id,
                                            }"
                                            >Seleccionar datos del
                                            solicitante*</label
                                        >
                                        <el-select
                                            filterable
                                            class="w-100 d-block"
                                            :class="{
                                                'is-invalid':
                                                    errors.solicitante_id,
                                            }"
                                            v-model="
                                                oCertificacion.solicitante_id
                                            "
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
                                                'text-danger':
                                                    errors.superior_id,
                                            }"
                                            >Seleccionar datos del inmediato
                                            superior que aprueba*</label
                                        >
                                        <el-select
                                            filterable
                                            class="w-100 d-block"
                                            :class="{
                                                'is-invalid':
                                                    errors.superior_id,
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
                                                        'text-danger':
                                                            errors.inicio,
                                                    }"
                                                    >Fecha inicio*</label
                                                >
                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    v-model="
                                                        oCertificacion.inicio
                                                    "
                                                    :class="{
                                                        'is-invalid':
                                                            errors.inicio,
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
                                                        'text-danger':
                                                            errors.final,
                                                    }"
                                                    >Fecha final*</label
                                                >
                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    v-model="
                                                        oCertificacion.final
                                                    "
                                                    :class="{
                                                        'is-invalid':
                                                            errors.final,
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
                                                'text-danger':
                                                    errors.personal_designado,
                                            }"
                                            >Personal designado*</label
                                        >
                                        <el-select
                                            filterable
                                            class="w-100 d-block"
                                            :class="{
                                                'is-invalid':
                                                    errors.personal_designado,
                                            }"
                                            v-model="
                                                oCertificacion.personal_designado
                                            "
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
                                            v-text="
                                                errors.personal_designado[0]
                                            "
                                        ></span>
                                    </div>
                                    <div
                                        v-if="nro_paso == 10"
                                        class="form-group col-md-6 ml-auto mr-auto border border-1 p-3"
                                    >
                                        <label
                                            :class="{
                                                'text-danger':
                                                    errors.departamento,
                                            }"
                                            >Departamento</label
                                        >
                                        <el-input
                                            filterable
                                            class="w-100 d-block"
                                            :class="{
                                                'is-invalid':
                                                    errors.departamento,
                                            }"
                                            v-model="
                                                oCertificacion.departamento
                                            "
                                            clearable
                                        >
                                        </el-input>
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
                                            v-if="nro_paso == listPasos.length"
                                            class="btn btn-primary bg-primary btn-flat btn-block"
                                            :loading="enviando"
                                            @click="enviaRegistro()"
                                            ><i class="fa fa-save"></i>
                                            Registrar</el-button
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            oCertificacion: {
                formulario_id: "",
                mo_id: "",
                mod_id: "",
                cantidad_usar: "",
                presupuesto_usarse: 0,
                archivo: null,
                correlativo: "",
                solicitante_id: "",
                superior_id: "",
                inicio: "",
                final: "",
                personal_designado: "",
                personal_designado: "",
                departamento: "",
                estado: "PENDIENTE",
            },
            errors: [],
            listFormularios: [],
            listOperaciones: [],
            listPersonals: [],
            listTareas: [],
            listDetalles: [],
            listUsuarios: [],
            enviando: false,
            nro_paso: 1,
            text_operacion: "",
            text_tarea: "",
            oMOperacion: null,
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
        };
    },
    mounted() {
        this.getFormularios();
        this.getUsuarios();
        this.getPersonals();
        this.getCorrelativo();
        this.loadingWindow.close();
    },
    methods: {
        // Obtener la lista de los formularios cuatro
        getFormularios() {
            axios.get("/admin/formulario_cuatro").then((response) => {
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

        // lista de operaciones deacuerdo al formulario cuatro seleccionado
        getOperacionesMemoriaCalculo() {
            axios
                .get("/admin/memoria_calculos/getOperaciones", {
                    params: {
                        formulario_id: this.oCertificacion.formulario_id,
                    },
                })
                .then((response) => {
                    this.text_operacion = "";
                    this.oCertificacion.mo_id = "";
                    this.listOperaciones = response.data;
                });
        },
        // ENVIAR FORMULARIO
        enviaRegistro() {
            if (this.oMOperacion) {
                if (parseFloat(this.oMOperacion.saldo) > 0) {
                    this.enviando = true;
                    try {
                        let url = "/admin/certificacions";
                        let formdata = new FormData();
                        let config = {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        };
                        formdata.append(
                            "formulario_id",
                            this.oCertificacion.formulario_id
                        );
                        formdata.append("mo_id", this.oCertificacion.mo_id);
                        formdata.append("mod_id", this.oCertificacion.mod_id);
                        formdata.append(
                            "cantidad_usar",
                            this.oCertificacion.cantidad_usar
                        );
                        formdata.append(
                            "presupuesto_usarse",
                            this.oCertificacion.presupuesto_usarse
                        );
                        formdata.append("archivo", this.oCertificacion.archivo);
                        formdata.append(
                            "correlativo",
                            this.oCertificacion.correlativo
                        );
                        formdata.append(
                            "solicitante_id",
                            this.oCertificacion.solicitante_id
                        );
                        formdata.append(
                            "superior_id",
                            this.oCertificacion.superior_id
                        );
                        formdata.append("inicio", this.oCertificacion.inicio);
                        formdata.append("final", this.oCertificacion.final);
                        formdata.append("final", this.oCertificacion.final);
                        formdata.append(
                            "personal_designado",
                            this.oCertificacion.personal_designado
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
                                this.$router.push({
                                    name: "certificacions.index",
                                });
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
                                        this.errors =
                                            error.response.data.errors;
                                        this.muestraErrores();
                                    } else {
                                        if (error.response.status === 500) {
                                            Swal.fire({
                                                icon: "error",
                                                title: "Error",
                                                html: error.response.data
                                                    .message,
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
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "ERROR",
                        html: `El saldo del detalle seleccionado es de <span class="text-md font-weight-bold text-red">${this.oMOperacion.saldo}</span>`,
                        showConfirmButton: true,
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#0069d9",
                    });
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "ERROR",
                    html: "Debes seleccionar el detalle de la operación",
                    showConfirmButton: true,
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#0069d9",
                });
            }
        },

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

        // textos codigos
        getDetalleOperacion() {
            let operacion = this.listDetalles.filter(
                (item) => item.id == this.oCertificacion.mod_id
            )[0];
            this.oMOperacion = operacion;
            this.oCertificacion.cantidad_usar = this.oMOperacion.saldo_cantidad;
            this.getMontoPartida();
        },
        cargaArchivo(e) {
            this.oCertificacion.archivo = e.target.files[0];
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
        getDetalles() {
            axios
                .get("/admin/memoria_operacion_detalles/getDetalles", {
                    params: {
                        id: this.oCertificacion.mo_id,
                    },
                })
                .then((response) => {
                    this.listDetalles = response.data;
                });
        },
        validaCantidadUsar() {
            if (
                this.oCertificacion.cantidad_usar >
                this.oMOperacion.saldo_cantidad
            ) {
                this.oCertificacion.cantidad_usar =
                    this.oMOperacion.saldo_cantidad;
                this.getMontoPartida();
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    html:
                        "La cantidad maxima permitida es de " +
                        this.oMOperacion.saldo_cantidad,
                    showConfirmButton: false,
                    timer: 1500,
                });
            } else {
                this.getMontoPartida();
            }
        },
        getMontoPartida() {
            this.oCertificacion.presupuesto_usarse = parseFloat(
                parseFloat(this.oCertificacion.cantidad_usar) *
                    parseFloat(this.oMOperacion.costo)
            ).toFixed(2);
        },
        ingresarEnter(valor) {
            return valor.replace(",", " | ");
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
