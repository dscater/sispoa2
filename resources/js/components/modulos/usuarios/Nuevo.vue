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
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.nombre,
                                    }"
                                    >Nombre*</label
                                >
                                <el-input
                                    placeholder="Nombre"
                                    :class="{ 'is-invalid': errors.nombre }"
                                    v-model="usuario.nombre"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.nombre"
                                    v-text="errors.nombre[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.paterno,
                                    }"
                                    >Ap. Paterno*</label
                                >

                                <el-input
                                    placeholder="Ap. Paterno"
                                    :class="{ 'is-invalid': errors.paterno }"
                                    v-model="usuario.paterno"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.paterno"
                                    v-text="errors.paterno[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.materno,
                                    }"
                                    >Ap. Materno</label
                                >
                                <el-input
                                    placeholder="Ap. Materno"
                                    :class="{ 'is-invalid': errors.materno }"
                                    v-model="usuario.materno"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.materno"
                                    v-text="errors.materno[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.ci,
                                    }"
                                    >C.I.*</label
                                >
                                <el-input
                                    placeholder="Número de C.I."
                                    :class="{ 'is-invalid': errors.ci }"
                                    v-model="usuario.ci"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.ci"
                                    v-text="errors.ci[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.ci_exp,
                                    }"
                                    >Expedido*</label
                                >
                                <el-select
                                    class="w-100 d-block"
                                    :class="{
                                        'is-invalid': errors.ci_exp,
                                    }"
                                    v-model="usuario.ci_exp"
                                    clearable
                                >
                                    <el-option
                                        v-for="(item, index) in listExpedido"
                                        :key="index"
                                        :value="item.value"
                                        :label="item.label"
                                    >
                                    </el-option>
                                </el-select>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.ci_exp"
                                    v-text="errors.ci_exp[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.fono,
                                    }"
                                    >Teléfono*</label
                                >
                                <el-input
                                    placeholder="Teléfono"
                                    :class="{ 'is-invalid': errors.fono }"
                                    v-model="usuario.fono"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.fono"
                                    v-text="errors.fono[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.cargo,
                                    }"
                                    >Denominación del puesto*</label
                                >
                                <el-input
                                    placeholder="Denominación del puesto"
                                    :class="{ 'is-invalid': errors.cargo }"
                                    v-model="usuario.cargo"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.cargo"
                                    v-text="errors.cargo[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.lugar_trabajo,
                                    }"
                                    >Lugar de trabajo*</label
                                >
                                <el-input
                                    placeholder="Lugar de trabajo"
                                    :class="{
                                        'is-invalid': errors.lugar_trabajo,
                                    }"
                                    v-model="usuario.lugar_trabajo"
                                    clearable
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.lugar_trabajo"
                                    v-text="errors.lugar_trabajo[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger':
                                            errors.descripcion_puesto,
                                    }"
                                    >Descripción del puesto*</label
                                >
                                <el-input
                                    type="textarea"
                                    autosize
                                    placeholder="Descripción del puesto"
                                    :class="{
                                        'is-invalid': errors.descripcion_puesto,
                                    }"
                                    v-model="usuario.descripcion_puesto"
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.descripcion_puesto"
                                    v-text="errors.descripcion_puesto[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.observacion,
                                    }"
                                    >Observaciones</label
                                >
                                <el-input
                                    type="textarea"
                                    autosize
                                    placeholder="Observaciones"
                                    :class="{
                                        'is-invalid': errors.observacion,
                                    }"
                                    v-model="usuario.observacion"
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.observacion"
                                    v-text="errors.observacion[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
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
                                    v-model="usuario.unidad_id"
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
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.tipo,
                                    }"
                                    >Tipo de Usuario*</label
                                >
                                <el-select
                                    class="w-100 d-block"
                                    :class="{
                                        'is-invalid': errors.tipo,
                                    }"
                                    v-model="usuario.tipo"
                                    clearable
                                >
                                    <el-option
                                        v-for="(item, index) in listRoles"
                                        :key="index"
                                        :value="item"
                                        :label="item"
                                    >
                                    </el-option>
                                </el-select>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.tipo"
                                    v-text="errors.tipo[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.foto,
                                    }"
                                    >Foto</label
                                >
                                <input
                                    type="file"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.foto,
                                    }"
                                    ref="input_file"
                                    @change="cargaImagen"
                                />
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.foto"
                                    v-text="errors.foto[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.acceso,
                                    }"
                                    >Acceso*</label
                                >
                                <el-switch
                                    :class="{
                                        'is-invalid': errors.acceso,
                                    }"
                                    style="display: block"
                                    v-model="usuario.acceso"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949"
                                    active-text="HABILITADO"
                                    inactive-text="DESHABILITADO"
                                    active-value="1"
                                    inactive-value="0"
                                >
                                    >
                                </el-switch>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.acceso"
                                    v-text="errors.acceso[0]"
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
        usuario: {
            type: Object,
            default: {
                id: 0,
                nombre: "",
                paterno: "",
                materno: "",
                ci: "",
                ci_exp: "",
                fono: "",
                cargo: "",
                lugar_trabajo: "",
                descripcion_puesto: "",
                observacion: "",
                unidad_id: "",
                tipo: "",
                foto: null,
                acceso: "0",
            },
        },
    },
    watch: {
        muestra_modal: function (newVal, oldVal) {
            this.errors = [];
            if (newVal) {
                this.$refs.input_file.value = null;
                this.bModal = true;
            } else {
                this.bModal = false;
            }
        },
    },
    computed: {
        tituloModal() {
            if (this.accion == "nuevo") {
                return "AGREGAR USUARIO";
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
    },
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            bModal: this.muestra_modal,
            enviando: false,
            listExpedido: [
                { value: "LP", label: "La Paz" },
                { value: "CB", label: "Cochabamba" },
                { value: "SC", label: "Santa Cruz" },
                { value: "CH", label: "Chuquisaca" },
                { value: "OR", label: "Oruro" },
                { value: "PT", label: "Potosi" },
                { value: "TJ", label: "Tarija" },
                { value: "PD", label: "Pando" },
                { value: "BN", label: "Beni" },
            ],
            listRoles: [
                "SUPER USUARIO",
                "MAE",
                "FINANCIERA",
                "JEFES DE ÁREAS",
                "DIRECTORES",
                "JEFES DE UNIDAD",
                "ENLACE",
            ],
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
                let url = "/admin/usuarios";
                let config = {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                };
                let formdata = new FormData();
                formdata.append(
                    "nombre",
                    this.usuario.nombre ? this.usuario.nombre : ""
                );
                formdata.append(
                    "paterno",
                    this.usuario.paterno ? this.usuario.paterno : ""
                );
                formdata.append(
                    "materno",
                    this.usuario.materno ? this.usuario.materno : ""
                );
                formdata.append("ci", this.usuario.ci ? this.usuario.ci : "");
                formdata.append(
                    "ci_exp",
                    this.usuario.ci_exp ? this.usuario.ci_exp : ""
                );
                formdata.append(
                    "fono",
                    this.usuario.fono ? this.usuario.fono : ""
                );
                formdata.append(
                    "cargo",
                    this.usuario.cargo ? this.usuario.cargo : ""
                );

                formdata.append(
                    "lugar_trabajo",
                    this.usuario.lugar_trabajo ? this.usuario.lugar_trabajo : ""
                );
                formdata.append(
                    "descripcion_puesto",
                    this.usuario.descripcion_puesto
                        ? this.usuario.descripcion_puesto
                        : ""
                );
                formdata.append(
                    "observacion",
                    this.usuario.observacion ? this.usuario.observacion : ""
                );

                formdata.append(
                    "unidad_id",
                    this.usuario.unidad_id ? this.usuario.unidad_id : ""
                );
                formdata.append(
                    "tipo",
                    this.usuario.tipo ? this.usuario.tipo : ""
                );
                formdata.append(
                    "foto",
                    this.usuario.foto ? this.usuario.foto : ""
                );

                formdata.append(
                    "acceso",
                    this.usuario.acceso ? this.usuario.acceso : "0"
                );

                if (this.accion == "edit") {
                    url = "/admin/usuarios/" + this.usuario.id;
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
                        this.limpiaUsuario();
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
        cargaImagen(e) {
            this.usuario.foto = e.target.files[0];
        },
        // Dialog/modal
        cierraModal() {
            this.bModal = false;
            this.$emit("close");
        },
        limpiaUsuario() {
            this.errors = [];
            this.usuario.nombre = "";
            this.usuario.paterno = "";
            this.usuario.materno = "";
            this.usuario.ci = "";
            this.usuario.ci_exp = "";
            this.usuario.fono = "";
            this.usuario.cargo = "";
            this.usuario.lugar_trabajo = "";
            this.usuario.descripcion_puesto = "";
            this.usuario.observacion = "";
            this.usuario.unidad_id = "";
            this.usuario.tipo = "";
            this.usuario.foto = null;
            this.usuario.acceso = "0";
            this.$refs.input_file.value = null;
        },
    },
};
</script>

<style></style>
