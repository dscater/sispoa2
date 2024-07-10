<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Ejecución físico</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body overflow-auto">
                                <div class="row">
                                    <div class="col-12">
                                        <h4>Por Detalle de Formulario</h4>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Seleccionar código PEI</label>
                                        <el-select
                                            class="w-full"
                                            v-model="detalle_formulario_id"
                                            filterable
                                            @change="
                                                cargaTabla();
                                                getGrafico1();
                                            "
                                        >
                                            <el-option
                                                v-for="item in listDetalles"
                                                :key="item.pei_seleccionado"
                                                :value="item.pei_seleccionado"
                                                :label="item.codigo_pei"
                                            >
                                            </el-option>
                                        </el-select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mes Inicio</label>
                                        <el-select
                                            class="w-full"
                                            v-model="mes1"
                                            @change="
                                                cargaTabla();
                                                getGrafico1();
                                            "
                                        >
                                            <el-option
                                                v-for="item in listMeses"
                                                :key="item.value"
                                                :value="item.value"
                                                :label="item.label"
                                            ></el-option>
                                        </el-select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mes Inicio</label>
                                        <el-select
                                            class="w-full"
                                            v-model="mes2"
                                            @change="
                                                cargaTabla();
                                                getGrafico1();
                                            "
                                        >
                                            <el-option
                                                v-for="item in listMeses"
                                                :key="item.value"
                                                :value="item.value"
                                                :label="item.label"
                                            ></el-option>
                                        </el-select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div
                                        class="col-12 p-4"
                                        id="container"
                                    ></div>
                                    <div class="col-md-12">
                                        <table
                                            class="table table-bordered text-xs"
                                            v-html="htmlTabla"
                                        ></table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h4>Por Unidad Organizacional</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mes Inicio</label>
                                        <el-select
                                            class="w-full"
                                            v-model="mes3"
                                            @change="cargaTabla2()"
                                        >
                                            <el-option
                                                v-for="item in listMeses"
                                                :key="item.value"
                                                :value="item.value"
                                                :label="item.label"
                                            ></el-option>
                                        </el-select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mes Inicio</label>
                                        <el-select
                                            class="w-full"
                                            v-model="mes4"
                                            @change="cargaTabla2()"
                                        >
                                            <el-option
                                                v-for="item in listMeses"
                                                :key="item.value"
                                                :value="item.value"
                                                :label="item.label"
                                            ></el-option>
                                        </el-select>
                                    </div>
                                    <div class="col-12">
                                        <table
                                            class="table table-bordered"
                                            v-html="htmlTabla2"
                                        ></table>
                                    </div>
                                    <div
                                        class="col-12 p-4"
                                        id="container2"
                                    ></div>
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
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            detalle_formulario_id: "",
            listDetalles: [],
            htmlTabla: "",
            listMeses: [
                {
                    value: "01",
                    label: "Enero",
                },
                {
                    value: "02",
                    label: "Febrero",
                },
                {
                    value: "03",
                    label: "Marzo",
                },
                {
                    value: "04",
                    label: "Abril",
                },
                {
                    value: "05",
                    label: "Mayo",
                },
                {
                    value: "06",
                    label: "Junio",
                },
                {
                    value: "07",
                    label: "Julio",
                },
                {
                    value: "08",
                    label: "Agosto",
                },
                {
                    value: "09",
                    label: "Septiembre",
                },
                {
                    value: "10",
                    label: "Octubre",
                },
                {
                    value: "11",
                    label: "Noviembre",
                },
                {
                    value: "12",
                    label: "Diciembre",
                },
            ],
            mes1: "01",
            mes2: "02",
            mes3: "01",
            mes4: "02",
            htmlTabla2: "",
        };
    },
    mounted() {
        this.loadingWindow.close();
        this.getDetalleFormularios();
        this.cargaTabla2();
    },
    methods: {
        // Listar DetalleFormularios
        getDetalleFormularios() {
            this.showOverlay = true;
            this.muestra_modal = false;
            let url = "/admin/formulario_cuatro/listado_pei_index";
            axios.get(url).then((res) => {
                this.listDetalles = res.data.listado;
            });
        },
        cargaTabla() {
            if (this.detalle_formulario_id != "") {
                axios
                    .get("/admin/detalle_formularios/getEjecucionFisico", {
                        params: {
                            formulario_id: this.detalle_formulario_id,
                            mes1: this.mes1,
                            mes2: this.mes2,
                        },
                    })
                    .then((response) => {
                        this.htmlTabla = response.data.html;
                    });
            }
        },
        getGrafico1() {
            axios
                .get("/admin/detalle_formularios/getEjecucionFisicoGrafico", {
                    params: {
                        formulario_id: this.detalle_formulario_id,
                        mes1: this.mes1,
                        mes2: this.mes2,
                    },
                })
                .then((response) => {
                    Highcharts.chart("container", {
                        chart: {
                            type: "line",
                            backgroundColor: "#f0f0f0", // Fondo del gráfico
                            borderColor: "#000000", // Color del borde
                            borderWidth: 2, // Ancho del borde
                        },
                        title: {
                            text: "Ejecutados/Programados",
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
        cargaTabla2() {
            axios
                .get("/admin/detalle_formularios/getEjecucionFisicoUnidades", {
                    params: {
                        mes1: this.mes3,
                        mes2: this.mes4,
                    },
                })
                .then((response) => {
                    this.htmlTabla2 = response.data.html;
                    Highcharts.chart("container2", {
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
        ingresarEnter(valor) {
            return valor.replace(",", " | ");
        },
    },
};
</script>
<style></style>
