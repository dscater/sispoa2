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
                    <h4 class="modal-title">CONFIGURACIÓN DE MODULOS</h4>
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
                        <div
                            class="row"
                            v-for="(item, index) in listConfiguracionModulos"
                        >
                            <div class="col-md-12">
                                <Acceso :modulo="item"></Acceso>
                            </div>
                        </div>
                    </form>
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Acceso from "./Acceso.vue";
export default {
    components: {
        Acceso,
    },
    props: {
        muestra_modal: {
            type: Boolean,
            default: false,
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
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            bModal: this.muestra_modal,
            listConfiguracionModulos: [],
        };
    },
    mounted() {
        this.bModal = this.muestra_modal;
        this.getConfiguracionModulos();
    },
    methods: {
        getConfiguracionModulos() {
            axios.get("/admin/configuracion_modulos").then((response) => {
                this.listConfiguracionModulos =
                    response.data.configuracion_modulos;
            });
        },
        // Dialog/modal
        cierraModal() {
            this.bModal = false;
            this.$emit("close");
        },
    },
};
</script>

<style></style>
