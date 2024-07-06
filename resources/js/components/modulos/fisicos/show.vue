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
                                <table class="tabla_detalle" border="1">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th rowspan="3" width="5%">
                                                Código Operación(1)
                                            </th>
                                            <th rowspan="3">Operación(2)</th>
                                            <th rowspan="3">Ponderación</th>
                                            <th rowspan="3">
                                                Resultado intermedio Esperado(3)
                                            </th>
                                            <th rowspan="3">
                                                Medios de verificación(4)
                                            </th>
                                            <th rowspan="3">Código tarea(5)</th>
                                            <th rowspan="3">
                                                Actividad/Tarea(6)
                                            </th>
                                            <th colspan="12">
                                                Programación de ejecución de
                                                operaciones y actividades(7)
                                            </th>
                                            <th colspan="2">
                                                Fecha prevista de ejecución(8)
                                            </th>
                                        </tr>
                                        <tr class="bg-primary">
                                            <th colspan="3">1er Trim.</th>
                                            <th colspan="3">2do Trim.</th>
                                            <th colspan="3">3er Trim.</th>
                                            <th colspan="3">4to Trim.</th>
                                            <th rowspan="2">Inicio</th>
                                            <th rowspan="2">Final</th>
                                        </tr>
                                        <tr class="bg-primary">
                                            <th>E</th>
                                            <th>F</th>
                                            <th>M</th>
                                            <th>A</th>
                                            <th>M</th>
                                            <th>J</th>
                                            <th>J</th>
                                            <th>A</th>
                                            <th>S</th>
                                            <th>O</th>
                                            <th>N</th>
                                            <th>D</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template
                                            v-for="(
                                                operacion, index_operacion
                                            ) in oDetalleFormulario.operacions"
                                        >
                                            <tr
                                                v-if="operacion.subdireccion"
                                                class="bg-primary"
                                            >
                                                <td colspan="21">
                                                    {{
                                                        operacion.subdireccion
                                                            .nombre
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    :rowspan="
                                                        operacion
                                                            .detalle_operaciones
                                                            .length + 1
                                                    "
                                                >
                                                    {{
                                                        operacion.codigo_operacion
                                                    }}
                                                </td>
                                                <td
                                                    :rowspan="
                                                        operacion
                                                            .detalle_operaciones
                                                            .length + 1
                                                    "
                                                >
                                                    {{ operacion.operacion }}
                                                </td>
                                                <td
                                                    :rowspan="
                                                        operacion
                                                            .detalle_operaciones
                                                            .length + 1
                                                    "
                                                >
                                                    {{ operacion.ponderacion }}
                                                    %
                                                </td>
                                                <td
                                                    :rowspan="
                                                        operacion
                                                            .detalle_operaciones
                                                            .length + 1
                                                    "
                                                >
                                                    {{
                                                        operacion.resultado_esperado
                                                    }}
                                                </td>
                                                <td
                                                    :rowspan="
                                                        operacion
                                                            .detalle_operaciones
                                                            .length + 1
                                                    "
                                                >
                                                    {{
                                                        operacion.medios_verificacion
                                                    }}
                                                </td>
                                            </tr>
                                            <tr
                                                v-for="(
                                                    detalle_operacion,
                                                    index_detalle
                                                ) in operacion.detalle_operaciones"
                                            >
                                                <!-- <td>
                                                    {{
                                                        detalle_operacion.ponderacion
                                                    }}%
                                                </td> -->
                                                <!-- <td>
                                                    {{
                                                        detalle_operacion.resultado_esperado
                                                    }}
                                                </td> -->
                                                <!-- <td>
                                                    {{
                                                        detalle_operacion.medios_verificacion
                                                    }}
                                                </td> -->
                                                <td>
                                                    {{
                                                        detalle_operacion.codigo_tarea
                                                    }}
                                                </td>
                                                <td>
                                                    {{
                                                        detalle_operacion.actividad_tarea
                                                    }}
                                                </td>
                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.pt_e
                                                        ) > 0 &&
                                                        detalle_operacion.pt_e ==
                                                            detalle_operacion.pt_e_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.pt_e
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.pt_e }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.pt_f
                                                        ) > 0 &&
                                                        detalle_operacion.pt_f ==
                                                            detalle_operacion.pt_f_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.pt_f
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.pt_f }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.pt_m
                                                        ) > 0 &&
                                                        detalle_operacion.pt_m ==
                                                            detalle_operacion.pt_m_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.pt_m
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.pt_m }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.st_a
                                                        ) > 0 &&
                                                        detalle_operacion.st_a ==
                                                            detalle_operacion.st_a_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.st_a
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.st_a }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.st_m
                                                        ) > 0 &&
                                                        detalle_operacion.st_m ==
                                                            detalle_operacion.st_m_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.st_m
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.st_m }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.st_j
                                                        ) > 0 &&
                                                        detalle_operacion.st_j ==
                                                            detalle_operacion.st_j_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.st_j
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.st_j }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.tt_j
                                                        ) > 0 &&
                                                        detalle_operacion.tt_j ==
                                                            detalle_operacion.tt_j_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.tt_j
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.tt_j }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.tt_a
                                                        ) > 0 &&
                                                        detalle_operacion.tt_a ==
                                                            detalle_operacion.tt_a_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.tt_a
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.tt_a }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.tt_s
                                                        ) > 0 &&
                                                        detalle_operacion.tt_s ==
                                                            detalle_operacion.tt_s_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.tt_s
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.tt_s }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.ct_o
                                                        ) > 0 &&
                                                        detalle_operacion.ct_o ==
                                                            detalle_operacion.ct_o_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.ct_o
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.ct_o }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.ct_n
                                                        ) > 0 &&
                                                        detalle_operacion.ct_n ==
                                                            detalle_operacion.ct_n_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.ct_n
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.ct_n }}
                                                </td>

                                                <td
                                                    :class="[
                                                        parseFloat(
                                                            detalle_operacion.ct_d
                                                        ) > 0 &&
                                                        detalle_operacion.ct_d ==
                                                            detalle_operacion.ct_d_eje
                                                            ? 'bg-success'
                                                            : detalle_operacion.ct_d
                                                            ? 'bg-warning'
                                                            : '',
                                                    ]"
                                                >
                                                    {{ detalle_operacion.ct_d }}
                                                </td>

                                                <td>
                                                    {{
                                                        formatoFecha(
                                                            detalle_operacion.inicio
                                                        )
                                                    }}
                                                </td>
                                                <td>
                                                    {{
                                                        formatoFecha(
                                                            detalle_operacion.final
                                                        )
                                                    }}
                                                </td>
                                            </tr>
                                        </template>

                                        <tr>
                                            <td colspan="7"></td>
                                            <td v-for="mes in meses">
                                                {{ array_programados[mes] }}
                                            </td>
                                            <td colspan="2">
                                                {{ total_programados }}
                                                Programados
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td v-for="mes in meses">
                                                {{ array_programados_p[mes] }}
                                            </td>
                                            <td colspan="2">100%</td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td v-for="mes in meses">
                                                {{ array_ejecutados[mes] }}
                                            </td>
                                            <td colspan="2">
                                                {{ total_ejecutados }}
                                                Ejecutados
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td v-for="mes in meses">
                                                {{ array_ejecutados_p[mes] }}
                                            </td>
                                            <td colspan="2">
                                                {{ p_ejecutados }}% Acumulados
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="19"></td>
                                            <td colspan="2">
                                                {{ a_la_fecha }}% A la fecha
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-12">
                                        <div id="container"></div>
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
        };
    },
    mounted() {
        this.getDetalleFormulario();
        this.loadingWindow.close();
    },
    methods: {
        // OBTENER EL REGISTRO DETALLE FORMULARIO
        getDetalleFormulario() {
            axios
                .get("/admin/detalle_formularios/" + this.id)
                .then((response) => {
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
                    Highcharts.chart("container", {
                        chart: {
                            type: "column",
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
                                    format: "{point.y:.0f}",
                                },
                            },
                        },
                        tooltip: {
                            headerFormat:
                                '<span style="font-size:10px"><b>{point.key}</b></span><table>',
                            pointFormat:
                                '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y}</b></td></tr>',
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
