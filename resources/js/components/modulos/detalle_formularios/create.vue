<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Detalle Formulario 4 - <small>Nuevo</small></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 p-2">
                        <router-link
                            :to="{ name: 'detalle_formularios.index' }"
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
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label
                                            :class="{
                                                'text-danger':
                                                    errors.formulario_seleccionado,
                                            }"
                                            >Seleccionar código PEI*</label
                                        >
                                        <el-select
                                            filterable
                                            class="w-100 d-block"
                                            :class="{
                                                'is-invalid':
                                                    errors.formulario_seleccionado,
                                            }"
                                            v-model="formulario_seleccionado"
                                            clearable
                                        >
                                            <el-option
                                                v-for="(
                                                    item, index_form
                                                ) in listFormularios"
                                                :key="index_form"
                                                :value="item.pei_seleccionado"
                                                :label="item.codigo_pei"
                                            >
                                            </el-option>
                                        </el-select>
                                        <span
                                            class="error invalid-feedback"
                                            v-if="
                                                errors.formulario_seleccionado
                                            "
                                            v-text="
                                                errors
                                                    .formulario_seleccionado[0]
                                            "
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Operacion
                            v-for="(operacion, index) in listOperacions"
                            :operacion="operacion"
                            :index="index"
                            @quitar="quitarOperacion"
                            :key="index"
                        ></Operacion>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button
                                            class="btn btn-primary btn-flat btn-block"
                                            @click="agregarOperacion"
                                            :disabled="!agregaOperacion"
                                        >
                                            <i class="fa fa-plus"></i>
                                            Agregar Operación
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-3">
                            <div class="col-md-3">
                                <el-button
                                    class="btn btn-primary bg-primary btn-flat btn-block"
                                    :loading="enviando"
                                    @click="enviarRegistro"
                                    :disabled="!enviable"
                                    ><i class="fa fa-save"></i>
                                    Registrar</el-button
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import Operacion from "./Operacion.vue";
export default {
    components: {
        Operacion,
    },
    data() {
        return {
            oUser:
                typeof localStorage.getItem("user") == "string"
                    ? JSON.parse(localStorage.getItem("user"))
                    : localStorage.getItem("user"),
            permisos: localStorage.getItem("permisos"),
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            enviando: false,
            enviable: false,
            agregaOperacion: false,
            listFormularios: [],
            listOperacions: [],
            cantidad_registrados: 0,
            errors: [],
            formulario_seleccionado: "",
            cambioPagina: true,
        };
    },
    watch: {
        listOperacions(newVal, oldVal) {
            if (newVal.length > 0) {
                this.enviable = true;
            } else {
                this.enviable = false;
            }
        },
        formulario_seleccionado(newVal, oldVal) {
            if (newVal != "") {
                this.agregaOperacion = true;
            } else {
                this.agregaOperacion = false;
            }
        },
    },
    mounted() {
        this.loadingWindow.close();
        this.getFormularios();
        // this.validarCierre();
        this.cambioPagina = true;
    },
    methods: {
        // OBTENER LA LISTA DE FORMULARIO
        getFormularios() {
            axios
                .get("/admin/formulario_cuatro/listado_pei_index")
                .then((response) => {
                    this.listFormularios = response.data.listado;
                });
        },

        // ENVIAR OPERACIONES
        enviarRegistro() {
            let a_errores = this.validaData();
            if (a_errores.length == 0) {
                let data = {
                    formulario_seleccionado: this.formulario_seleccionado,
                    data: this.listOperacions,
                };
                axios
                    .post("/admin/detalle_formularios", data)
                    .then((response) => {
                        Swal.fire({
                            icon: "success",
                            title: response.data.msj,
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        this.cambioPagina = false;
                        this.$router.push({
                            name: "detalle_formularios.index",
                        });
                    })
                    .catch((error) => {
                        this.enviando = false;
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors;
                                this.$refs.codigo_pei.focus();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: error,
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
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
            } else {
                let mensaje = "";
                a_errores.forEach((e) => {
                    mensaje += e + "<br>";
                });
                Swal.fire({
                    icon: "error",
                    title: "Tienes los siguientes errores",
                    html: mensaje,
                    showConfirmButton: true,
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#0069d9",
                });
            }
        },

        // VALIDACION DE DATA
        validaData() {
            let array_errors = [];
            this.listOperacions.forEach((item, index) => {
                if (
                    item.codigo_operacion == null ||
                    item.codigo_operacion == ""
                ) {
                    array_errors.push(
                        'Debes ingresar el <b>código de operación</b> en el elemento <span class="font-weight-bold text-lg text-primary">' +
                            (index + 1) +
                            "</span>"
                    );
                }
                if (item.operacion == null || item.operacion == "") {
                    array_errors.push(
                        'Debes ingresar la <b>descripción de operación</b> en el elemento <span class="text-primary font-weight-bold text-lg">' +
                            (index + 1) +
                            "</span>"
                    );
                }
                if (item.ponderacion == null || item.ponderacion == "") {
                    array_errors.push(
                        'Debes ingresar la <b>ponderación</b> en el elemento <span class="text-primary font-weight-bold text-lg">' +
                            (index + 1) +
                            "</span>"
                    );
                }
                if (
                    item.resultado_esperado == null ||
                    item.resultado_esperado == ""
                ) {
                    array_errors.push(
                        'Debes ingresar el <b>Resultado esperado</b> en el elemento <span class="text-primary font-weight-bold text-lg">' +
                            (index + 1) +
                            "</span>"
                    );
                }
                if (
                    item.medios_verificacion == null ||
                    item.medios_verificacion == ""
                ) {
                    array_errors.push(
                        'Debes ingresar los <b>Medios de verificación</b> en el elemento <span class="text-primary font-weight-bold text-lg">' +
                            (index + 1) +
                            "</span>"
                    );
                }
                item.detalle_operaciones.forEach(
                    (item_detalle, index_detalle) => {
                        // if (
                        //     item_detalle.ponderacion == null ||
                        //     item_detalle.ponderacion == ""
                        // ) {
                        //     array_errors.push(
                        //         'Debes ingresar la <b>ponderación</b> en el elemento  <span class="text-secondary font-weight-bold text-lg">' +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1) +
                        //             "</span>"
                        //     );
                        // }
                        // if (
                        //     item_detalle.resultado_esperado == null ||
                        //     item_detalle.resultado_esperado == ""
                        // ) {
                        //     array_errors.push(
                        //         'Debes ingresar el <b>resultado intermedio esperado</b> en el elemento  <span class="text-secondary font-weight-bold text-lg">' +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1) +
                        //             "</span>"
                        //     );
                        // }
                        // if (
                        //     item_detalle.medios_verificacion == null ||
                        //     item_detalle.medios_verificacion == ""
                        // ) {
                        //     array_errors.push(
                        //         'Debes ingresar los <b>medios de verificación</b> en el elemento  <span class="text-secondary font-weight-bold text-lg">' +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1) +
                        //             "</span>"
                        //     );
                        // }
                        if (
                            item_detalle.codigo_tarea == null ||
                            item_detalle.codigo_tarea == ""
                        ) {
                            array_errors.push(
                                'Debes ingresar el <b>código de tarea</b> en el elemento  <span class="text-secondary font-weight-bold text-lg">' +
                                    (index + 1) +
                                    "-" +
                                    (index_detalle + 1) +
                                    "</span>"
                            );
                        }
                        if (
                            item_detalle.actividad_tarea == null ||
                            item_detalle.actividad_tarea == ""
                        ) {
                            array_errors.push(
                                'Debes ingresar la <b>actividad/tarea</b> en el elemento  <span class="text-secondary font-weight-bold text-lg">' +
                                    (index + 1) +
                                    "-" +
                                    (index_detalle + 1) +
                                    "</span>"
                            );
                        }
                        if (
                            item_detalle.inicio == null ||
                            item_detalle.inicio == ""
                        ) {
                            array_errors.push(
                                'Debes ingresar el <b>inicio</b> en el elemento  <span class="text-secondary font-weight-bold text-lg">' +
                                    (index + 1) +
                                    "-" +
                                    (index_detalle + 1) +
                                    "</span>"
                            );
                        }
                        if (
                            item_detalle.final == null ||
                            item_detalle.final == ""
                        ) {
                            array_errors.push(
                                'Debes ingresar el <b>final</b> en el elemento  <span class="text-secondary font-weight-bold text-lg">' +
                                    (index + 1) +
                                    "-" +
                                    (index_detalle + 1) +
                                    "</span>"
                            );
                        }
                    }
                );
            });

            return array_errors;
        },

        // METODOS DE LOS DETALLES
        agregarOperacion() {
            this.listOperacions.push({
                id: 0,
                detalle_formulario_id: this.detalle_formulario_id,
                subdireccion_id: "",
                codigo_operacion: "",
                operacion: "",
                fecha_registro: "",
                detalle_operaciones: [],
            });
        },
        quitarOperacion(index, item) {
            this.listOperacions.splice(index, 1);
        },
        validarCierre() {
            window.addEventListener("beforeunload", (evento) => {
                if (true) {
                    evento.preventDefault();
                    evento.returnValue = "";
                    return "";
                }
            });
        },
        removerValidacion() {
            // window.removeEventListener("beforeunload");
        },
        ingresarEnter(valor) {
            return valor.replace(",", " | ");
        },
    },
    beforeRouteLeave(to, from, next) {
        if (this.cambioPagina) {
            Swal.fire({
                title: "¿Desea guardar el formulario antes de salir?",
                html: ``,
                showCancelButton: true,
                confirmButtonColor: "#05568e",
                confirmButtonText: "Si, guardar",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    this.enviarRegistro();
                    this.removerValidacion();
                } else {
                    next();
                    this.removerValidacion();
                }
            });
        } else {
            next();
        }
    },
};
</script>

<style></style>
