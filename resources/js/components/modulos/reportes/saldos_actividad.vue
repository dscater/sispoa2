<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Reportes - Saldos de presupuestos por actividad</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="ml-auto mr-auto col-md-5">
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label
                                                    :class="{
                                                        'text-danger':
                                                            errors.filtro,
                                                    }"
                                                    >Cóidgo PEI*</label
                                                >
                                                <el-select
                                                    v-model="
                                                        oReporte.formulario_id
                                                    "
                                                    filterable
                                                    placeholder="Seleccione"
                                                    class="d-block"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.formulario_id,
                                                    }"
                                                    @change="getOperaciones"
                                                >
                                                    <el-option
                                                        v-for="item in listFormularios"
                                                        :key="item.id"
                                                        :label="
                                                            ingresarEnter(
                                                                item.codigo_pei
                                                            )
                                                        "
                                                        :value="item.id"
                                                    >
                                                    </el-option>
                                                </el-select>
                                                <span
                                                    class="error invalid-feedback"
                                                    v-if="errors.formulario_id"
                                                    v-text="
                                                        errors.formulario_id[0]
                                                    "
                                                ></span>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label
                                                    :class="{
                                                        'text-danger':
                                                            errors.filtro,
                                                    }"
                                                    >Código de operación*</label
                                                >
                                                <el-select
                                                    v-model="
                                                        oReporte.operacion_id
                                                    "
                                                    filterable
                                                    placeholder="Seleccione"
                                                    class="d-block"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.operacion_id,
                                                    }"
                                                    @change="getActividades"
                                                >
                                                    <el-option
                                                        v-for="item in listaOperaciones"
                                                        :key="item.id"
                                                        :label="
                                                            item.codigo_operacion
                                                        "
                                                        :value="item.id"
                                                    >
                                                    </el-option>
                                                </el-select>
                                                <span
                                                    class="error invalid-feedback"
                                                    v-if="errors.operacion_id"
                                                    v-text="
                                                        errors.operacion_id[0]
                                                    "
                                                ></span>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label
                                                    :class="{
                                                        'text-danger':
                                                            errors.filtro,
                                                    }"
                                                    >Código
                                                    Actividad/Tarea*</label
                                                >
                                                <el-select
                                                    v-model="
                                                        oReporte.actividad_id
                                                    "
                                                    filterable
                                                    placeholder="Seleccione"
                                                    class="d-block"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.actividad_id,
                                                    }"
                                                >
                                                    <el-option
                                                        v-for="item in listActividades"
                                                        :key="item.id"
                                                        :label="
                                                            item.codigo_tarea
                                                        "
                                                        :value="item.id"
                                                    >
                                                    </el-option>
                                                </el-select>
                                                <span
                                                    class="error invalid-feedback"
                                                    v-if="errors.actividad_id"
                                                    v-text="
                                                        errors.actividad_id[0]
                                                    "
                                                ></span>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <el-button
                                                type="primary"
                                                class="bg-lightblue w-full"
                                                :loading="enviando"
                                                @click="generaReporte()"
                                                >{{ textoBtn }}</el-button
                                            >
                                        </div>
                                        <div class="col-md-12">
                                            <el-button
                                                type="default"
                                                class="w-full mt-1"
                                                @click="limpiarFormulario()"
                                                >Reiniciar</el-button
                                            >
                                        </div>
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
            errors: [],
            oReporte: {
                formulario_id: "",
                operacion_id: "",
                actividad_id: "",
            },
            aFechas: [],
            enviando: false,
            textoBtn: "Generar Reporte",
            listaOperaciones: [],
            listActividades: [],
            listFormularios: [],
            errors: [],
        };
    },
    mounted() {
        this.getFormularios();
    },
    methods: {
        // OBTENER LA LISTA DE FORMULARIO
        getFormularios() {
            axios.get("/admin/formulario_cuatro").then((response) => {
                this.listFormularios = response.data.listado;
            });
        },
        // OBTENER LA LISTA DE OPERACIONES
        getOperaciones() {
            axios
                .get("/admin/formulario_cuatro/getOperaciones", {
                    params: { id: this.oReporte.formulario_id },
                })
                .then((response) => {
                    this.listaOperaciones = response.data;
                });
        },
        // OBTENER LA LISTA DE ACTIVIDADES
        getActividades() {
            axios
                .get("/admin/operacions/getTareas", {
                    params: { id: this.oReporte.operacion_id },
                })
                .then((response) => {
                    this.listActividades = response.data;
                });
        },
        limpiarFormulario() {
            this.oReporte.formulario_id = "";
            this.oReporte.operacion_id = "";
            this.oReporte.actividad_id = "";
        },
        generaReporte() {
            this.enviando = true;
            let config = {
                responseType: "blob",
            };
            axios
                .post("/admin/reportes/saldos_actividad", this.oReporte, config)
                .then((res) => {
                    this.errors = [];
                    this.enviando = false;
                    let pdfBlob = new Blob([res.data], {
                        type: "application/pdf",
                    });
                    let urlReporte = URL.createObjectURL(pdfBlob);
                    window.open(urlReporte);
                })
                .catch(async (error) => {
                    let responseObj = await error.response.data.text();
                    responseObj = JSON.parse(responseObj);
                    this.enviando = false;
                    if (error.response) {
                        if (error.response.status == 422)
                            this.errors = responseObj.errors;
                    }
                });
        },
        ingresarEnter(valor) {
            return valor.replace(",", " | ");
        },
    },
};
</script>

<style></style>
