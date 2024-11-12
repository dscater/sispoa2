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
                                            <div
                                                class="form-group col-md-12"
                                                v-if="
                                                    user &&
                                                    user.tipo == 'SUPER USUARIO'
                                                "
                                            >
                                                <label
                                                    :class="{
                                                        'text-danger':
                                                            errors.unidad_id,
                                                    }"
                                                    >Unidad
                                                    Organizacional*</label
                                                >
                                                <el-select
                                                    class="w-100 d-block"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.unidad_id,
                                                    }"
                                                    v-model="oReporte.unidad_id"
                                                    clearable
                                                    @change="getFormularios"
                                                >
                                                    <el-option
                                                        v-for="item in listaUnidades"
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
                                            <div class="form-group col-md-12">
                                                <label
                                                    :class="{
                                                        'text-danger':
                                                            errors.filtro,
                                                    }"
                                                    >Cóidgo POA*</label
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
                                                        v-for="(
                                                            item, index_form
                                                        ) in listFormularios"
                                                        :key="index_form"
                                                        :value="
                                                            item.poa_seleccionado
                                                        "
                                                        :label="item.codigo_poa"
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
                                                        :label="
                                                            item.partida +
                                                            ': ' +
                                                            item.descripcion
                                                        "
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
            user: JSON.parse(localStorage.getItem("user")),
            errors: [],
            oReporte: {
                formulario_id: "",
                partida_id: "",
            },
            aFechas: [],
            enviando: false,
            textoBtn: "Generar Reporte",
            listaUnidades: [],
            listPartidas: [],
            listFormularios: [],
            errors: [],
        };
    },
    mounted() {
        if (
            this.user.tipo == "JEFES DE UNIDAD" ||
            this.user.tipo == "DIRECTORES" ||
            this.user.tipo == "JEFES DE ÁREAS" ||
            this.user.tipo == "MAE" ||
            this.user.tipo == "ENLACE" ||
            this.user.tipo == "FINANCIERA"
        ) {
            this.oReporte.unidad_id = this.user.unidad_id;
            this.getFormularios();
        } else {
            this.getUnidades();
        }
        this.getFormularios();
        this.getPartidas();
    },
    methods: {
        getUnidades() {
            axios.get("/admin/unidads").then((response) => {
                this.listaUnidades = response.data.unidads;
            });
        },
        // OBTENER LA LISTA DE FORMULARIO
        getFormularios() {
            if (this.oReporte.unidad_id != "") {
                axios
                    .get("/admin/formulario_cuatro/getPoaPorUnidad", {
                        params: {
                            id: this.oReporte.unidad_id,
                        },
                    })
                    .then((response) => {
                        this.listFormularios = response.data.listado;
                    });
            } else {
                this.listFormularios = [];
            }
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
