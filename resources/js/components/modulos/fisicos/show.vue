<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Físico - <small>Detalles</small></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 p-2">
                        <router-link
                            :to="{ name: 'fisicos.index' }"
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
                                            <i class="fas fa-list"></i>
                                            Detalle Registro
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Fecha Inicio</label>
                                                <input
                                                    type="date"
                                                    class="form-control rounded-0"
                                                    @change="generaGrafico()"
                                                    @keyup="generaGrafico()"
                                                    v-model="fecha_ini"
                                                />
                                            </div>
                                            <div class="col-md-3">
                                                <label>Fecha Fin</label>
                                                <input
                                                    type="date"
                                                    class="form-control rounded-0"
                                                    @change="generaGrafico()"
                                                    @keyup="generaGrafico()"
                                                    v-model="fecha_fin"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-12 p-4"
                                        id="container"
                                    ></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button
                                            class="btn btn-success btn-block"
                                            @click="exportar()"
                                        >
                                            Exportar
                                        </button>
                                    </div>
                                </div>
                                <table
                                    class="tabla_detalle"
                                    border="1"
                                    v-html="html"
                                ></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: ["id"],
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            oDetalleFormulario: {
                operacions: [],
            },
            total_programados: [],
            total_ejecutados: [],
            meses: [],
            array_programados: [],
            array_programados_p: [],
            array_ejecutados: [],
            array_ejecutados_p: [],
            p_ejecutados: 0,
            a_la_fecha: 0,
            fecha_ini: "",
            fecha_fin: "",
            interval_grafico: null,
            html: "",
        };
    },
    mounted() {
        this.getDetalleFormulario();
        this.generaGrafico();
        this.loadingWindow.close();
    },
    methods: {
        // OBTENER EL REGISTRO DETALLE FORMULARIO
        getDetalleFormulario() {
            axios
                .get("/admin/detalle_formularios/getTablaSubunidad/" + this.id)
                .then((response) => {
                    this.html = response.data.html;
                    this.oDetalleFormulario = response.data.detalle_formulario;
                    this.total_programados = response.data.total_programados;
                    this.total_ejecutados = response.data.total_ejecutados;
                    this.meses = response.data.meses;
                    this.array_programados = response.data.array_programados;
                    this.array_programados_p =
                        response.data.array_programados_p;
                    this.array_ejecutados = response.data.array_ejecutados;
                    this.array_ejecutados_p = response.data.array_ejecutados_p;
                    this.p_ejecutados = response.data.p_ejecutados;
                    this.a_la_fecha = response.data.a_la_fecha;
                });
        },
        generaGrafico() {
            clearInterval(this.interval_grafico);
            this.interval_grafico = setTimeout(this.getGrafico(), 700);
        },
        getGrafico() {
            axios
                .get("/admin/detalle_formularios/getGraficoFechas/" + this.id, {
                    params: {
                        fecha_ini: this.fecha_ini,
                        fecha_fin: this.fecha_fin,
                    },
                })
                .then((response) => {
                    Highcharts.chart("container", {
                        chart: {
                            type: "column",
                            backgroundColor: "#f0f0f0", // Fondo del gráfico
                            borderColor: "#000000", // Color del borde
                            borderWidth: 2, // Ancho del borde
                        },
                        title: {
                            text: "Programados/Ejecutados",
                        },
                        subtitle: {
                            text: "",
                        },
                        xAxis: {
                            labels: {
                                rotation: -45,
                                style: {
                                    fontSize: "13px",
                                    fontFamily: "Verdana, sans-serif",
                                },
                            },
                            categories: response.data.categories,
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "Total",
                            },
                        },
                        legend: {
                            enabled: true,
                        },
                        plotOptions: {
                            series: {
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true,
                                    format: "{point.y:.2f}%",
                                },
                            },
                        },
                        tooltip: {
                            headerFormat:
                                '<span style="font-size:10px"><b>{point.key}</b></span><table>',
                            pointFormat:
                                '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y}</b>%</td></tr>',
                            footerFormat: "</table>",
                            shared: true,
                            useHTML: true,
                        },
                        series: response.data.series,
                    });
                });
        },
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD/MM/YYYY");
        },
        exportar() {
            let config = {
                responseType: "blob",
            };
            axios
                .post("/admin/fisicos/exportar/" + this.id, {}, config)
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
    },
};
</script>

<style>
.tabla_detalle {
    width: 100%;
    border-collapse: collapse;
}

.tabla_detalle thead tr th,
.tabla_detalle tbody tr td {
    padding: 3px;
    font-size: 0.7rem !important;
    text-align: center;
    border: solid 1px;
}
</style>
