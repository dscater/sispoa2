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
                                        'text-danger': errors.descripcion,
                                    }"
                                    >Descripción*</label
                                >
                                <el-input
                                    type="textarea"
                                    autosize
                                    placeholder="Nombre"
                                    :class="{
                                        'is-invalid': errors.descripcion,
                                    }"
                                    v-model="actividad_realizada.descripcion"
                                >
                                </el-input>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.descripcion"
                                    v-text="errors.descripcion[0]"
                                ></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    :class="{
                                        'text-danger': errors.archivo,
                                    }"
                                    >Cargar archivo*</label
                                >
                                <input
                                    type="file"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.archivo,
                                    }"
                                    accept="application/pdf"
                                    ref="input_file"
                                    @change="cargaImagen"
                                />
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.archivo"
                                    v-text="errors.archivo[0]"
                                ></span>
                            </div>
                            <div
                                class="form-group col-md-6"
                                v-if="user.tipo == 'SUPER USUARIO'"
                            >
                                <label
                                    :class="{
                                        'text-danger': errors.estado,
                                    }"
                                    >Estado*</label
                                >
                                <el-select
                                    class="w-full"
                                    :class="{ 'is-invalid': errors.estado }"
                                    v-model="actividad_realizada.estado"
                                    clearable
                                >
                                    <el-option
                                        v-for="(item, index) in [
                                            'APROBADO',
                                            'RECHAZADO',
                                        ]"
                                        :key="index"
                                        :value="item"
                                        :label="item"
                                    >
                                    </el-option>
                                </el-select>
                                <span
                                    class="error invalid-feedback"
                                    v-if="errors.estado"
                                    v-text="errors.estado[0]"
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
        actividad_realizada: {
            type: Object,
            default: {
                id: 0,
                descripcion: "",
                archivo: null,
                estado: "",
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
    },
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            bModal: this.muestra_modal,
            enviando: false,
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
                let url = "/admin/actividad_realizadas";
                let config = {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                };
                let formdata = new FormData();
                formdata.append(
                    "descripcion",
                    this.actividad_realizada.descripcion
                        ? this.actividad_realizada.descripcion
                        : ""
                );
                formdata.append(
                    "archivo",
                    this.actividad_realizada.archivo
                        ? this.actividad_realizada.archivo
                        : ""
                );
                formdata.append(
                    "estado",
                    this.actividad_realizada.estado
                        ? this.actividad_realizada.estado
                        : ""
                );

                if (this.accion == "edit") {
                    url =
                        "/admin/actividad_realizadas/" +
                        this.actividad_realizada.id;
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
                        this.limpiaActividadRealizada();
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
            this.actividad_realizada.archivo = e.target.files[0];
        },
        // Dialog/modal
        cierraModal() {
            this.bModal = false;
            this.$emit("close");
        },
        limpiaActividadRealizada() {
            this.errors = [];
            this.actividad_realizada.descripcion = "";
            this.actividad_realizada.archivo = null;
            this.actividad_realizada.estado = "";
            this.$refs.input_file.value = null;
        },
    },
};
</script>

<style></style>
