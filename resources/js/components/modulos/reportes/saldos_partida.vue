<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Reportes - Saldos de presupuestos por partida</h1>
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
                                                    @change="getPartidas"
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
                                                    >Seleccionar partida*</label
                                                >
                                                <el-select
                                                    v-model="
                                                        oReporte.partida_id
                                                    "
                                                    filterable
                                                    placeholder="Seleccione"
                                                    class="d-block"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.partida_id,
                                                    }"
                                                >
                                                    <el-option
                                                        v-for="(
                                                            item, index
                                                        ) in listPartidas"
                                                        :key="index"
                                                        :value="item.id"
                                                        :label="item.partida"
                                                    >
                                                    </el-option>
                                                </el-select>
                                                <span
                                                    class="error invalid-feedback"
                                                    v-if="errors.partida_id"
                                                    v-text="
                                                        errors.partida_id[0]
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
                partida_id: "",
            },
            aFechas: [],
            enviando: false,
            textoBtn: "Generar Reporte",
            listPartidas: [],
            listFormularios: [],
            errors: [],
        };
    },
    mounted() {
        this.getFormularios();
        this.getPartidas();
    },
    methods: {
        // OBTENER LA LISTA DE FORMULARIO
        getFormularios() {
            axios.get("/admin/formulario_cuatro").then((response) => {
                this.listFormularios = response.data.listado;
            });
        },
        // GET PARTIDAS
        getPartidas() {
            axios.get("/admin/partidas").then((response) => {
                this.listPartidas = response.data.partidas;
            });
        },
        // OBTENER LA LISTA DE ACTIVIDADES
        // getPartidas() {
        //     axios
        //         .get("/admin/memoria_calculos/getOperaciones", {
        //             params: { formulario_id: this.oReporte.formulario_id },
        //         })
        //         .then((response) => {
        //             this.listPartidas = response.data;
        //         });
        // },
        limpiarFormulario() {
            this.oReporte.formulario_id = "";
            this.oReporte.partida_id = "";
        },
        generaReporte() {
            this.enviando = true;
            let config = {
                responseType: "blob",
            };
            axios
                .post("/admin/reportes/saldos_partida", this.oReporte, config)
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
