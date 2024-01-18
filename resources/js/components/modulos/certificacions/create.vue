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
                        <Formulario
                            :accion="'nuevo'"
                            :id="oCertificacion.id"
                            :certificacion="oCertificacion"
                        ></Formulario>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import Formulario from "./Formulario.vue";
export default {
    components: {
        Formulario,
    },
    props: ["id"],
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            oCertificacion: {
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
            errors: [],
            enviando: false,
        };
    },
    mounted() {
        this.loadingWindow.close();
    },
    methods: {},
};
</script>

<style></style>
