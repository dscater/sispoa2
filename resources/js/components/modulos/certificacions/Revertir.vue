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
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>OPERACIÓN</th>
                                        <th>
                                            {{
                                                oMemoriaOperacion?.operacion
                                                    .codigo_operacion
                                            }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template
                                        v-for="item in oCertificacion.certificacion_detalles"
                                    >
                                        <tr>
                                            <td>PARTIDA</td>
                                            <td>
                                                {{
                                                    item
                                                        .memoria_operacion_detalle
                                                        .partida
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>MONTO CERTIFICADO</td>
                                            <td>
                                                {{ item.total }}
                                            </td>
                                        </tr>
                                        <tr class="bg-gray-light">
                                            <td>MONTO A REVERTIR</td>
                                            <td>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    class="form-control"
                                                    v-model="item.revertir"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SALDO</td>
                                            <td>
                                                {{ item.saldo_total }}
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal"
                        @click="cierraModal"
                    >
                        Cerrar
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-dismiss="modal"
                        @click="guardarCambios"
                    >
                        Guardar cambios
                    </button>
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
        certificacion: {
            type: Object,
            default: {
                id: 0,
                formulario_id: "",
                poa_seleccionado: "",
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
                departamento: "",
                municipio: "",
                estado: "PENDIENTE",
                certificacion_detalles: [],
                array_dptos: [],
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
        certificacion: function (newVal, oldVal) {
            this.oCertificacion = newVal;
            this.getMemoriaOperacion();
        },
    },
    computed: {
        tituloModal() {
            if (this.accion == "nuevo") {
                return "SALDO CON REVERSIÓN";
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
            oCertificacion: {
                formulario_id: "",
                poa_seleccionado: "",
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
                departamento: "",
                municipio: "",
                estado: "PENDIENTE",
                certificacion_detalles: [],
                array_dptos: [],
            },
            oMemoriaOperacion: null,
        };
    },
    mounted() {
        this.bModal = this.muestra_modal;
    },
    methods: {
        guardarCambios() {
            axios
                .post(
                    "/admin/certificacions/update_revertir/" +
                        this.oCertificacion.id,
                    {
                        certificacion_detalles:
                            this.oCertificacion.certificacion_detalles,
                    }
                )
                .then((res) => {
                    this.bModal = false;
                    Swal.fire({
                        icon: "success",
                        title: res.data.msj,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    this.$emit("close");
                });
        },
        getMemoriaOperacion() {
            if (this.oCertificacion.mo_id) {
                axios
                    .get(
                        "admin/memoria_operacions/" + this.oCertificacion.mo_id
                    )
                    .then((response) => {
                        this.oMemoriaOperacion = response.data;
                    });
            }
        },
        async getPartida(mod_id) {
            let respuesta = await axios.get(
                "/admin/memoria_operacion_detalles/getRegistro/" + mod_id
            );

            if (respuesta) {
                return respuesta.data.partida;
            }

            return "-";
        },
        // Dialog/modal
        cierraModal() {
            this.bModal = false;
            this.$emit("close");
        },
        limpiaUsuario() {
            this.errors = [];
        },
    },
};
</script>

<style></style>
