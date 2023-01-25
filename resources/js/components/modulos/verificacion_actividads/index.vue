<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Verificación de la actividad POA</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button
                                            v-if="
                                                permisos.includes(
                                                    'verificacion_actividads.edit'
                                                )
                                            "
                                            class="btn btn-outline-primary bg-lightblue btn-flat btn-block"
                                            @click="
                                                muestra_modal = true;
                                                limpiarVerificacionActividad();
                                            "
                                        >
                                            <i class="el-icon-edit-outline"></i>
                                            Actualizar información
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <el-descriptions
                                        class="mt-3"
                                        title="Información"
                                        :column="1"
                                        border
                                        :labelStyle="{
                                            background: 'rgb(0, 123, 255)',
                                            width: '180px',
                                            color: 'white',
                                        }"
                                    >
                                        <el-descriptions-item>
                                            <template slot="label">
                                                <i class="el-icon-s-order"></i>
                                                Gestión
                                            </template>
                                            {{ oVerificacionActividad.gestion }}
                                        </el-descriptions-item>
                                        <el-descriptions-item>
                                            <template slot="label">
                                                <i class="el-icon-s-order"></i>
                                                Actividad
                                            </template>
                                            {{
                                                oVerificacionActividad.actividad
                                            }}
                                        </el-descriptions-item>
                                    </el-descriptions>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div
            class="modal fade"
            :class="{ show: muestra_modal }"
            id="modal-default"
            aria-modal="true"
            role="dialog"
            @click.self="cierraModal"
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
                                            'text-danger': errors.gestion,
                                        }"
                                        >Gestión*</label
                                    >

                                    <input
                                        type="text"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.gestion,
                                        }"
                                        placeholder="Gestión"
                                        v-model="oVerificacionActividad.gestion"
                                    />
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.gestion"
                                        v-text="errors.gestion[0]"
                                    ></span>
                                </div>
                                <div class="form-group col-md-12">
                                    <label
                                        :class="{
                                            'text-danger': errors.actividad,
                                        }"
                                        >Actividad*</label
                                    >

                                    <el-input
                                        type="textarea"
                                        :autosize="{ minRows: 5 }"
                                        placeholder="Actividad"
                                        :class="{
                                            'is-invalid': errors.actividad,
                                        }"
                                        v-model="
                                            oVerificacionActividad.actividad
                                        "
                                        clearable
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors.actividad"
                                        v-text="errors.actividad[0]"
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
                            >{{ textoBtn }}</el-button
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            muestra_modal: false,
            // config table
            loading: false,
            // Fin config table

            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            // config modal
            tituloModal: "ACTUALIZAR INFORMACIÓN",
            accion: "edit",
            textoBtn: "Actualizar",
            enviando: false,
            oVerificacionActividad: {
                id: 0,
                gestion: "",
                actividad: "",
            },
            errors: [],
        };
    },
    mounted() {
        this.loadingWindow.close();
        this.getVerificacionActividad();
    },
    methods: {
        getFile(e) {
            this.oVerificacionActividad.logo = e.target.files[0];
        },
        getVerificacionActividad() {
            this.loading = true;
            let url = "/admin/verificacion_actividads/getVerificacionActividad";
            axios.get(url).then((res) => {
                this.oVerificacionActividad = res.data.verificacion_actividad;
            });
        },
        // Dialog/modal
        cierraModal() {
            this.muestra_modal = false;
            this.getVerificacionActividad();
        },
        setRegistroModal() {
            this.enviando = true;
            try {
                this.textoBtn = "Enviando...";
                let url = "/admin/verificacion_actividads/update";
                let formdata = new FormData();
                formdata.append("_method", "put");
                formdata.append("gestion", this.oVerificacionActividad.gestion);
                formdata.append(
                    "actividad",
                    this.oVerificacionActividad.actividad
                );

                let config = {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                };

                axios
                    .post(url, formdata, config)
                    .then((res) => {
                        this.getVerificacionActividad();
                        this.enviando = false;
                        this.muestra_modal = false;
                        Swal.fire({
                            icon: "success",
                            title: res.data.msj,
                            showConfirmButton: false,
                            timer: 1500,
                        });

                        this.limpiarVerificacionActividad();
                        this.errors = [];
                        this.$refs.input_file.value = null;
                        if (this.accion == "edit") {
                            this.textoBtn = "Actualizar";
                        } else {
                            this.textoBtn = "Actualizar";
                        }
                    })
                    .catch((error) => {
                        this.enviando = false;
                        if (this.accion == "edit") {
                            this.textoBtn = "Actualizar";
                        } else {
                            this.textoBtn = "Actualizar";
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
        limpiarVerificacionActividad() {
            this.accion = "create";
            this.textoBtn = "Actualizar";
        },
    },
};
</script>

<style>
.el-descriptions-item__cell.el-descriptions-item__label.is-bordered-label {
    background: var(--principal) !important;
}
</style>
