<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Memoria de Cálculo - <small>Nuevo</small></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 p-2">
                        <router-link
                            :to="{ name: 'memoria_calculos.index' }"
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
                            :formulario_seleccionado="formulario_seleccionado"
                            :operacion="operacion"
                            :index="index"
                            @quitar="quitarOperacion"
                            @sw_guardar="swBotonGuardar"
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
                    .post("/admin/memoria_calculos", data)
                    .then((response) => {
                        Swal.fire({
                            icon: "success",
                            title: response.data.msj,
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        this.cambioPagina = false;
                        this.$router.push({
                            name: "memoria_calculos.index",
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
                if (item.operacion_id == null || item.operacion_id == "") {
                    array_errors.push(
                        "Debes seleccionar un <b>código de operación</b> en el elemento " +
                            (index + 1)
                    );
                }
                // if (
                //     item.detalle_operacion_id == null ||
                //     item.detalle_operacion_id == ""
                // ) {
                //     array_errors.push(
                //         "Debes seleccionar un <b>código de Actividad/Tarea</b> en el elemento " +
                //             (index + 1)
                //     );
                // }
                if (item.ue == null || item.ue == "") {
                    array_errors.push(
                        "Debes ingresar una <b>Unidad Ejecutora</b> en el elemento " +
                            (index + 1)
                    );
                }
                if (item.prog == null || item.prog == "") {
                    array_errors.push(
                        "Debes ingresar una <b>Programación</b> en el elemento " +
                            (index + 1)
                    );
                }
                if (item.act == null || item.act == "") {
                    array_errors.push(
                        "Debes ingresar una <b>Actividad</b> en el elemento " +
                            (index + 1)
                    );
                }
                if (item.lugar == null || item.lugar == "") {
                    array_errors.push(
                        "Debes ingresar un <b>Lugar de Ejecución de la Operación</b> en el elemento " +
                            (index + 1)
                    );
                }
                if (item.responsable == null || item.responsable == "") {
                    array_errors.push(
                        "Debes ingresar un <b>Responsable de Ejecución de la Operación / Tarea</b> en el elemento " +
                            (index + 1)
                    );
                }
                if (item.justificacion == null || item.justificacion == "") {
                    array_errors.push(
                        "Debes ingresar una <b>Justificación</b> en el elemento " +
                            (index + 1)
                    );
                }
                item.memoria_operacion_detalles.forEach(
                    (elem_detalle, index_detalle) => {
                        // if (elem_detalle.ue == null || elem_detalle.ue == "") {
                        //     array_errors.push(
                        //         "Debes ingresar una <b>Unidad Ejecutora</b> en el detalle " +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1)
                        //     );
                        // }
                        // if (
                        //     elem_detalle.prog == null ||
                        //     elem_detalle.prog == ""
                        // ) {
                        //     array_errors.push(
                        //         "Debes ingresar una <b>Programación</b> en el detalle " +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1)
                        //     );
                        // }
                        // if (
                        //     elem_detalle.act == null ||
                        //     elem_detalle.act == ""
                        // ) {
                        //     array_errors.push(
                        //         "Debes ingresar una <b>Actividad</b> en el detalle " +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1)
                        //     );
                        // }
                        // if (
                        //     elem_detalle.lugar == null ||
                        //     elem_detalle.lugar == ""
                        // ) {
                        //     array_errors.push(
                        //         "Debes ingresar un <b>Lugar de Ejecución de la Operación</b> en el detalle " +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1)
                        //     );
                        // }
                        // if (
                        //     elem_detalle.responsable == null ||
                        //     elem_detalle.responsable == ""
                        // ) {
                        //     array_errors.push(
                        //         "Debes ingresar un <b>Responsable de Ejecución de la Operación / Tarea</b> en el detalle " +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1)
                        //     );
                        // }
                        if (
                            elem_detalle.partida_id == null ||
                            elem_detalle.partida_id == ""
                        ) {
                            array_errors.push(
                                "Debes seleccionar una <b>Partida de gasto</b> en el detalle " +
                                    (index + 1) +
                                    "-" +
                                    (index_detalle + 1)
                            );
                        }
                        if (
                            elem_detalle.nro == null ||
                            elem_detalle.nro == ""
                        ) {
                            array_errors.push(
                                "Debes ingresar un <b>Nro</b> en el detalle " +
                                    (index + 1) +
                                    "-" +
                                    (index_detalle + 1)
                            );
                        }
                        // if (
                        //     elem_detalle.descripcion_detallada == null ||
                        //     elem_detalle.descripcion_detallada == ""
                        // ) {
                        //     array_errors.push(
                        //         "Debes ingresar una <b>Descripción detallada por item (bien o servicio)</b> en el detalle " +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1)
                        //     );
                        // }
                        if (
                            elem_detalle.cantidad == null ||
                            elem_detalle.cantidad == ""
                        ) {
                            array_errors.push(
                                "Debes ingresar una <b>Canitdad</b> en el detalle " +
                                    (index + 1) +
                                    "-" +
                                    (index_detalle + 1)
                            );
                        } else {
                            if (parseFloat(elem_detalle.cantidad) < 0) {
                                array_errors.push(
                                    "La <b>Canitdad</b> en el detalle " +
                                        (index + 1) +
                                        "-" +
                                        (index_detalle + 1) +
                                        " no puede se menor a 0"
                                );
                            }
                        }
                        if (
                            elem_detalle.unidad == null ||
                            elem_detalle.unidad == ""
                        ) {
                            array_errors.push(
                                "Debes ingresar una <b>Unidad</b> en el detalle " +
                                    (index + 1) +
                                    "-" +
                                    (index_detalle + 1)
                            );
                        }
                        if (
                            elem_detalle.costo == null ||
                            elem_detalle.costo == ""
                        ) {
                            array_errors.push(
                                "Debes ingresar un <b>Precio Unitario</b> en el detalle " +
                                    (index + 1) +
                                    "-" +
                                    (index_detalle + 1)
                            );
                        } else {
                            if (parseFloat(elem_detalle.costo) < 0) {
                                array_errors.push(
                                    "El <b>Precio Unitario</b> en el detalle " +
                                        (index + 1) +
                                        "-" +
                                        (index_detalle + 1) +
                                        " no puede ser menor a 0"
                                );
                            }
                        }
                        // if (
                        //     elem_detalle.justificacion == null ||
                        //     elem_detalle.justificacion == ""
                        // ) {
                        //     array_errors.push(
                        //         "Debes ingresar una <b>Justificación</b> en el detalle " +
                        //             (index + 1) +
                        //             "-" +
                        //             (index_detalle + 1)
                        //     );
                        // }
                    }
                );
            });

            return array_errors;
        },

        // METODOS DE LOS DETALLES
        agregarOperacion() {
            this.listOperacions.push({
                id: 0,
                memoria_id: "",
                operacion_id: "",
                detalle_operacion_id: "",
                total_operacion: 0,
                ue: "",
                prog: "",
                act: "",
                lugar: "",
                responsable: "",
                justificacion: "",
                memoria_operacion_detalles: [
                    {
                        ue: "",
                        prog: "",
                        act: "",
                        lugar: "",
                        responsable: "",
                        partida: "",
                        nro: 1,
                        descripcion: "",
                        cantidad: "",
                        unidad: "",
                        costo: "",
                        total: 0,
                        justificacion: "",
                        ene: "",
                        feb: "",
                        mar: "",
                        abr: "",
                        may: "",
                        jun: "",
                        jul: "",
                        ago: "",
                        sep: "",
                        oct: "",
                        nov: "",
                        dic: "",
                        total_actividad: 0,
                    },
                ],
            });
        },
        quitarOperacion(index, item) {
            this.listOperacions.splice(index, 1);
        },
        ingresarEnter(valor) {
            return valor.replace(",", " | ");
        },
        swBotonGuardar(val) {
            this.enviable = val;
        },
    },
    beforeRouteLeave(to, from, next) {
        if (this.cambioPagina) {
            if (this.enviable) {
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
                    } else {
                        next();
                    }
                });
            } else {
                Swal.fire({
                    icon: "info",
                    title: "Atención",
                    html: `Los cambios no se guardaran debido a que existen un error de montos en una de las operaciones<br>¿Desea salir del formulario?`,
                    showCancelButton: true,
                    confirmButtonColor: "#05568e",
                    confirmButtonText: "Si, salir",
                    cancelButtonText: "No, cancelar",
                    denyButtonText: `No, cancelar`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        next();
                    }
                });
            }
        } else {
            next();
        }
    },
};
</script>

<style></style>
