<template>
    <div
        class="modal fade"
        :class="{ show: bModal }"
        id="modal-default"
        aria-modal="true"
        role="dialog"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                        @click="cierraModal"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label
                                    :class="{
                                        'text-danger': errors.codigo_pei,
                                    }"
                                    >Código PEI - Objetivos estrategicos*</label
                                >
                                <div class="input-group">
                                    <input
                                        type="text"
                                        :class="{
                                            'is-invalid': errors.codigo_pei,
                                        }"
                                        v-model="formulario_cuatro.codigo_pei"
                                        placeholder="Código PEI"
                                        class="form-control"
                                    />
                                    <div class="input-group-prepend texto">
                                        <el-input
                                            type="textarea"
                                            autosize
                                            placeholder="Objetivos estrategicos"
                                            :class="{
                                                'is-invalid':
                                                    errors.objetivo_estrategico,
                                            }"
                                            v-model="
                                                formulario_cuatro.objetivo_estrategico
                                            "
                                            clearable
                                        >
                                        </el-input>
                                    </div>
                                </div>
                                <span
                                    class="error invalid-feedback d-block"
                                    v-if="errors.codigo_pei"
                                    v-text="errors.codigo_pei[0]"
                                ></span>
                                <span
                                    class="error invalid-feedback d-block"
                                    v-if="errors.objetivo_estrategico"
                                    v-text="errors.objetivo_estrategico[0]"
                                ></span>
                                <div class="input-group mt-1">
                                    <input
                                        type="text"
                                        :class="{
                                            'is-invalid': errors.codigo_pei2,
                                        }"
                                        v-model="formulario_cuatro.codigo_pei2"
                                        placeholder="Código PEI"
                                        class="form-control"
                                    />
                                    <div class="input-group-prepend texto">
                                        <el-input
                                            type="textarea"
                                            autosize
                                            placeholder="Objetivos estrategicos"
                                            :class="{
                                                'is-invalid':
                                                    errors.objetivo_estrategico2,
                                            }"
                                            v-model="
                                                formulario_cuatro.objetivo_estrategico2
                                            "
                                            clearable
                                        >
                                        </el-input>
                                    </div>
                                </div>
                                <div class="input-group mt-1">
                                    <input
                                        type="text"
                                        :class="{
                                            'is-invalid': errors.codigo_pei3,
                                        }"
                                        v-model="formulario_cuatro.codigo_pei3"
                                        placeholder="Código PEI"
                                        class="form-control"
                                    />
                                    <div class="input-group-prepend texto">
                                        <el-input
                                            type="textarea"
                                            autosize
                                            placeholder="Objetivos estrategicos"
                                            :class="{
                                                'is-invalid':
                                                    errors.objetivo_estrategico3,
                                            }"
                                            v-model="
                                                formulario_cuatro.objetivo_estrategico3
                                            "
                                            clearable
                                        >
                                        </el-input>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger':
                                            errors.resultado_institucional,
                                    }"
                                    >Resultado institucional*</label
                                >
                                <el-input
                                    type="textarea"
                                    autosize
                                    placeholder="Resultado institucional"
                                    :class="{
                                        'is-invalid':
                                            errors.resultado_institucional,
                                    }"
                                    v-model="
                                        formulario_cuatro.resultado_institucional
                                    "
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.resultado_institucional"
                                    v-text="errors.resultado_institucional[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.indicador,
                                    }"
                                    >Indicador de Proceso*</label
                                >
                                <el-input
                                    type="textarea"
                                    autosize
                                    placeholder="Indicador de Proceso"
                                    :class="{ 'is-invalid': errors.indicador }"
                                    v-model="formulario_cuatro.indicador"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.indicador"
                                    v-text="errors.indicador[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-12">
                                <label
                                    :class="{
                                        'text-danger': errors.codigo_poa,
                                    }"
                                    >Código POA - Acción de Corto Plazo de
                                    Gestión*</label
                                >
                                <div class="input-group">
                                    <input
                                        type="text"
                                        :class="{
                                            'is-invalid': errors.codigo_poa,
                                        }"
                                        v-model="formulario_cuatro.codigo_poa"
                                        placeholder="Código POA"
                                        class="form-control"
                                    />
                                    <div class="input-group-prepend texto">
                                        <el-input
                                            type="textarea"
                                            autosize
                                            placeholder="Acción de Corto Plazo de Gestión"
                                            :class="{
                                                'is-invalid':
                                                    errors.accion_corto,
                                            }"
                                            v-model="
                                                formulario_cuatro.accion_corto
                                            "
                                            clearable
                                        >
                                        </el-input>
                                    </div>
                                </div>
                                <span
                                    class="error invalid-feedback d-block"
                                    v-if="errors.codigo_poa"
                                    v-text="errors.codigo_poa[0]"
                                ></span>
                                <span
                                    class="error invalid-feedback d-block"
                                    v-if="errors.accion_corto"
                                    v-text="errors.accion_corto[0]"
                                ></span>
                                <div class="input-group mt-1">
                                    <input
                                        type="text"
                                        :class="{
                                            'is-invalid': errors.codigo_poa2,
                                        }"
                                        v-model="formulario_cuatro.codigo_poa2"
                                        placeholder="Código POA"
                                        class="form-control"
                                    />
                                    <div class="input-group-prepend texto">
                                        <el-input
                                            type="textarea"
                                            autosize
                                            placeholder="Acción de Corto Plazo de Gestión"
                                            :class="{
                                                'is-invalid':
                                                    errors.accion_corto2,
                                            }"
                                            v-model="
                                                formulario_cuatro.accion_corto2
                                            "
                                            clearable
                                        >
                                        </el-input>
                                    </div>
                                </div>
                                <div class="input-group mt-1">
                                    <input
                                        type="text"
                                        :class="{
                                            'is-invalid': errors.codigo_poa3,
                                        }"
                                        v-model="formulario_cuatro.codigo_poa3"
                                        placeholder="Código POA"
                                        class="form-control"
                                    />
                                    <div class="input-group-prepend texto">
                                        <el-input
                                            type="textarea"
                                            autosize
                                            placeholder="Acción de Corto Plazo de Gestión"
                                            :class="{
                                                'is-invalid':
                                                    errors.accion_corto3,
                                            }"
                                            v-model="
                                                formulario_cuatro.accion_corto3
                                            "
                                            clearable
                                        >
                                        </el-input>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.indicador_proceso,
                                    }"
                                    >Indicador de proceso POA*</label
                                >
                                <el-input
                                    type="textarea"
                                    autosize
                                    placeholder="Indicador de proceso POA"
                                    :class="{
                                        'is-invalid': errors.indicador_proceso,
                                    }"
                                    v-model="formulario_cuatro.indicador_proceso"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.indicador_proceso"
                                    v-text="errors.indicador_proceso[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.linea_base,
                                    }"
                                    >Línea de base*</label
                                >
                                <el-input
                                    type="textarea"
                                    autosize
                                    placeholder="Línea de base"
                                    :class="{
                                        'is-invalid': errors.linea_base,
                                    }"
                                    v-model="formulario_cuatro.linea_base"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.linea_base"
                                    v-text="errors.linea_base[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.meta,
                                    }"
                                    >Meta*</label
                                >
                                <el-input
                                    type="textarea"
                                    autosize
                                    placeholder="Meta"
                                    :class="{
                                        'is-invalid': errors.meta,
                                    }"
                                    v-model="formulario_cuatro.meta"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.meta"
                                    v-text="errors.meta[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.presupuesto,
                                    }"
                                    >Presupuesto Programado Gestión*</label
                                >
                                <input
                                    type="number"
                                    placeholder="Presupuesto Programado Gestión"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.presupuesto,
                                    }"
                                    v-model="formulario_cuatro.presupuesto"
                                />
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.presupuesto"
                                    v-text="errors.presupuesto[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.ponderacion,
                                    }"
                                    >Ponderación %*</label
                                >
                                <input
                                    type="number"
                                    placeholder="Ponderación %"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.presupuesto,
                                    }"
                                    v-model="formulario_cuatro.ponderacion"
                                />
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.ponderacion"
                                    v-text="errors.ponderacion[0]"
                                ></span>
                            </div>
                            <div
                                class="form-group col-md-6"
                                v-if="user.tipo=='SUPER USUARIO'"
                            >
                                <label
                                    :class="{
                                        'text-danger': errors.unidad_id,
                                    }"
                                    >Unidad Organizacional*</label
                                >
                                <el-select
                                    class="w-100 d-block"
                                    :class="{
                                        'is-invalid': errors.unidad_id,
                                    }"
                                    v-model="formulario_cuatro.unidad_id"
                                    clearable
                                >
                                    <el-option
                                        v-for="item in listUnidades"
                                        :key="item.id"
                                        :value="item.id"
                                        :label="item.nombre"
                                    >
                                    </el-option>
                                </el-select>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.unidad_id"
                                    v-text="errors.unidad_id[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6" v-else>
                                <label
                                    :class="{
                                        'text-danger': errors.unidad_id,
                                    }"
                                    >Unidad Organizacional*</label
                                >
                                <el-input
                                    class="w-100"
                                    :value="unidadUsuario"
                                    readonly
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.unidad_id"
                                    v-text="errors.unidad_id[0]"
                                ></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal"
                        @click="cierraModal"
                    >
                        Cerrar
                    </button>
                    <el-button
                        type="primary"
                        class="bg-lightblue"
                        :loading="enviando"
                        @click="setRegistroModal()"
                        >{{ textoBoton }}</el-button
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        muestra_modal: {
            type: Boolean,
            default: false,
        },
        accion: {
            type: String,
            default: "nuevo",
        },
        formulario_cuatro: {
            type: Object,
            default: {
                id: 0,
                codigo_pei: "",
                objetivo_estrategico: "",
                codigo_pei2: "",
                objetivo_estrategico2: "",
                codigo_pei3: "",
                objetivo_estrategico3: "",
                resultado_institucional: "",
                indicador: "",
                codigo_poa: "",
                accion_corto: "",
                indicador_proceso: "",
                linea_base: "",
                meta: "",
                presupuesto: "",
                ponderacion: "",
                unidad_id: "",
            },
        },
    },
    watch: {
        muestra_modal: function (newVal, oldVal) {
            this.errors = [];
            if (newVal) {
                this.bModal = true;
            } else {
                this.bModal = false;
            }
        },
    },
    computed: {
        tituloModal() {
            if (this.accion == "nuevo") {
                return "AGREGAR REGISTRO";
            } else {
                return "MODIFICAR REGISTRO";
            }
        },
        textoBoton() {
            if (this.accion == "nuevo") {
                return "Registrar";
            } else {
                return "Actualizar";
            }
        },
        unidadUsuario() {
            return this.user ? (this.user.unidad ? this.user.unidad.nombre : "") : "";
        },
    },
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            bModal: this.muestra_modal,
            enviando: false,
            listUnidades: [],
            errors: [],
        };
    },
    mounted() {
        this.bModal = this.muestra_modal;
        this.getUnidades();
    },
    methods: {
        getUnidades() {
            axios.get("/admin/unidads").then((response) => {
                this.listUnidades = response.data.unidads;
            });
        },
        setRegistroModal() {
            this.enviando = true;
            try {
                this.textoBtn = "Enviando...";
                let url = "/admin/formulario_cuatro";
                let config = {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                };
                let formdata = new FormData();

                let codigo_pei_completo =
                    this.formulario_cuatro.codigo_pei +
                    `-${this.formulario_cuatro.objetivo_estrategico}`;
                // this.formulario_cuatro.codigo_pei += `-${this.formulario_cuatro.objetivo_estrategico}`;
                if (
                    this.formulario_cuatro.codigo_pei2 &&
                    this.formulario_cuatro.codigo_pei2 != ""
                ) {
                    codigo_pei_completo += ` | ${this.formulario_cuatro.codigo_pei2}-${this.formulario_cuatro.objetivo_estrategico2}`;
                }
                if (
                    this.formulario_cuatro.codigo_pei3 &&
                    this.formulario_cuatro.codigo_pei3 != ""
                ) {
                    codigo_pei_completo += ` | ${this.formulario_cuatro.codigo_pei3}-${this.formulario_cuatro.objetivo_estrategico3}`;
                }

                let codigo_poa_completo =
                    this.formulario_cuatro.codigo_poa +
                    `-${this.formulario_cuatro.accion_corto}`;
                if (
                    this.formulario_cuatro.codigo_poa2 &&
                    this.formulario_cuatro.codigo_poa2 != ""
                ) {
                    codigo_poa_completo += ` | ${this.formulario_cuatro.codigo_poa2}-${this.formulario_cuatro.accion_corto2}`;
                }
                if (
                    this.formulario_cuatro.codigo_poa3 &&
                    this.formulario_cuatro.codigo_poa3 != ""
                ) {
                    codigo_poa_completo += ` | ${this.formulario_cuatro.codigo_poa3}-${this.formulario_cuatro.accion_corto3}`;
                }

                formdata.append(
                    "codigo_pei",
                    codigo_pei_completo ? codigo_pei_completo : ""
                );
                formdata.append(
                    "objetivo_estrategico",
                    this.formulario_cuatro.objetivo_estrategico
                        ? this.formulario_cuatro.objetivo_estrategico
                        : ""
                );
                formdata.append(
                    "resultado_institucional",
                    this.formulario_cuatro.resultado_institucional
                        ? this.formulario_cuatro.resultado_institucional
                        : ""
                );
                formdata.append(
                    "indicador",
                    this.formulario_cuatro.indicador
                        ? this.formulario_cuatro.indicador
                        : ""
                );
                formdata.append(
                    "codigo_poa",
                    codigo_poa_completo ? codigo_poa_completo : ""
                );
                formdata.append(
                    "accion_corto",
                    this.formulario_cuatro.accion_corto
                        ? this.formulario_cuatro.accion_corto
                        : ""
                );
                formdata.append(
                    "indicador_proceso",
                    this.formulario_cuatro.indicador_proceso
                        ? this.formulario_cuatro.indicador_proceso
                        : ""
                );
                formdata.append(
                    "linea_base",
                    this.formulario_cuatro.linea_base
                        ? this.formulario_cuatro.linea_base
                        : ""
                );
                formdata.append(
                    "meta",
                    this.formulario_cuatro.meta
                        ? this.formulario_cuatro.meta
                        : ""
                );
                formdata.append(
                    "presupuesto",
                    this.formulario_cuatro.presupuesto
                        ? this.formulario_cuatro.presupuesto
                        : ""
                );
                formdata.append(
                    "ponderacion",
                    this.formulario_cuatro.ponderacion
                        ? this.formulario_cuatro.ponderacion
                        : ""
                );

                // if (
                //     this.user.tipo == "JEFES DE UNIDAD" ||
                //     this.user.tipo == "DIRECTORES" ||
                //     this.user.tipo == "JEFES DE ÁREAS"
                // ) {
                //     formdata.append(
                //         "unidad_id",
                //         this.user.unidad_id ? this.user.unidad_id : ""
                //     );
                // } else {
                //     formdata.append(
                //         "unidad_id",
                //         this.formulario_cuatro.unidad_id
                //             ? this.formulario_cuatro.unidad_id
                //             : ""
                //     );
                // }

                if (this.user.unidad_id && this.user.unidad_id != "") {
                    formdata.append(
                        "unidad_id",
                        this.user.unidad_id ? this.user.unidad_id : ""
                    );
                } else {
                    formdata.append(
                        "unidad_id",
                        this.formulario_cuatro.unidad_id
                            ? this.formulario_cuatro.unidad_id
                            : ""
                    );
                }
                if (this.accion == "edit") {
                    url =
                        "/admin/formulario_cuatro/" + this.formulario_cuatro.id;
                    formdata.append("_method", "PUT");
                }
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
                        this.limpiaFormularioCuatro();
                        this.$emit("envioModal");
                        this.errors = [];
                        if (this.accion == "edit") {
                            this.textoBtn = "Actualizar";
                        } else {
                            this.textoBtn = "Registrar";
                        }
                    })
                    .catch((error) => {
                        this.enviando = false;
                        if (this.accion == "edit") {
                            this.textoBtn = "Actualizar";
                        } else {
                            this.textoBtn = "Registrar";
                        }
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors;
                            }
                        }
                    });
            } catch (e) {
                this.enviando = false;
                console.log(e);
            }
        },
        // Dialog/modal
        cierraModal() {
            this.bModal = false;
            this.$emit("close");
        },
        limpiaFormularioCuatro() {
            this.errors = [];
            this.formulario_cuatro.codigo_pei = "";
            this.formulario_cuatro.objetivo_estrategico = "";
            this.formulario_cuatro.codigo_pei2 = "";
            this.formulario_cuatro.objetivo_estrategico2 = "";
            this.formulario_cuatro.codigo_pei3 = "";
            this.formulario_cuatro.objetivo_estrategico3 = "";
            this.formulario_cuatro.resultado_institucional = "";
            this.formulario_cuatro.indicador = "";
            this.formulario_cuatro.codigo_poa = "";
            this.formulario_cuatro.accion_corto = "";
            this.formulario_cuatro.codigo_poa2 = "";
            this.formulario_cuatro.accion_corto2 = "";
            this.formulario_cuatro.codigo_poa3 = "";
            this.formulario_cuatro.accion_corto3 = "";
            this.formulario_cuatro.indicador_proceso = "";
            this.formulario_cuatro.linea_base = "";
            this.formulario_cuatro.meta = "";
            this.formulario_cuatro.presupuesto = "";
            this.formulario_cuatro.ponderacion = "";
            this.formulario_cuatro.unidad_id = "";
        },
    },
};
</script>

<style></style>
