<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Detalle Formulario 4 - <small>Detalles</small></h1>
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
                                            <i class="fas fa-list"></i>
                                            Detalle Registro
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
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
                                                <td>
                                                    {{ detalle_operacion.pt_e }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.pt_f }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.pt_m }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.st_a }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.st_m }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.st_j }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.tt_j }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.tt_a }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.tt_s }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.ct_o }}
                                                </td>

                                                <td>
                                                    {{ detalle_operacion.ct_n }}
                                                </td>

                                                <td>
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
                                    </tbody>
                                </table>
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
                });
        },
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD/MM/YYYY");
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
