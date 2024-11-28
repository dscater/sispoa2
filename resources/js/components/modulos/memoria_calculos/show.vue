<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Memorias de cálculo - <small>Detalles</small></h1>
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
                                            <i class="fas fa-list"></i>
                                            Detalle Registro
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row" id="contenedor_tabla">
                                    <table class="table table-bordered">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Unidad Ejecutora
                                                </th>
                                                <th
                                                    class="text-center"
                                                    colspan="2"
                                                >
                                                    Presupuestos
                                                </th>
                                                <th
                                                    class="text-center"
                                                    colspan="2"
                                                >
                                                    POA
                                                </th>
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Partida de gasto
                                                </th>
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Descripción
                                                </th>
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    N°
                                                </th>
                                                <!-- <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Descripción detallada por
                                                    item(bien o servicio)
                                                </th> -->
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Cantidad Requerida
                                                </th>
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Unidad
                                                </th>
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Precio Unitario
                                                </th>
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Total
                                                </th>
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Justificación
                                                </th>
                                                <th
                                                    class="text-center"
                                                    colspan="12"
                                                >
                                                    PROGRAMACIÓN MENSUAL
                                                </th>
                                                <th
                                                    class="text-center"
                                                    rowspan="2"
                                                >
                                                    Total
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">
                                                    Programa
                                                </th>
                                                <th class="text-center">
                                                    Actividad
                                                </th>
                                                <th class="text-center">
                                                    Cod. Operación
                                                </th>
                                                <th class="text-center">
                                                    Cod. Act./Tarea
                                                </th>
                                                <th class="text-center">
                                                    Enero
                                                </th>
                                                <th class="text-center">
                                                    Febrero
                                                </th>
                                                <th class="text-center">
                                                    Marzo
                                                </th>
                                                <th class="text-center">
                                                    Abril
                                                </th>
                                                <th class="text-center">
                                                    Mayo
                                                </th>
                                                <th class="text-center">
                                                    Junio
                                                </th>
                                                <th class="text-center">
                                                    Julio
                                                </th>
                                                <th class="text-center">
                                                    Agosto
                                                </th>
                                                <th class="text-center">
                                                    Septiembre
                                                </th>
                                                <th class="text-center">
                                                    Octubre
                                                </th>
                                                <th class="text-center">
                                                    Noviembre
                                                </th>
                                                <th class="text-center">
                                                    Diciembre
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template
                                                v-for="(
                                                    item, index_item
                                                ) in oMemoriaCalculo.operacions"
                                            >
                                                <template
                                                    v-for="(
                                                        item_mod, index_mod
                                                    ) in item.memoria_operacion_detalles"
                                                >
                                                    <tr
                                                        v-if="
                                                            item.operacion
                                                                .subdireccion &&
                                                            index_mod == 0
                                                        "
                                                    >
                                                        <td
                                                            colspan="26"
                                                            class="bg-primary text-center"
                                                        >
                                                            {{
                                                                item.operacion
                                                                    .subdireccion
                                                                    .nombre
                                                            }}
                                                        </td>
                                                    </tr>

                                                    <!-- con rowspan -->
                                                    <tr v-if="index_mod == 0">
                                                        <td
                                                            :rowspan="
                                                                item
                                                                    .memoria_operacion_detalles
                                                                    .length
                                                            "
                                                        >
                                                            {{ item_mod.ue }}
                                                        </td>
                                                        <td
                                                            :rowspan="
                                                                item
                                                                    .memoria_operacion_detalles
                                                                    .length
                                                            "
                                                        >
                                                            {{ item_mod.prog }}
                                                        </td>
                                                        <td
                                                            :rowspan="
                                                                item
                                                                    .memoria_operacion_detalles
                                                                    .length
                                                            "
                                                        >
                                                            {{ item_mod.act }}
                                                        </td>
                                                        <td
                                                            :rowspan="
                                                                item
                                                                    .memoria_operacion_detalles
                                                                    .length
                                                            "
                                                        >
                                                            {{
                                                                item.codigo_operacion
                                                            }}
                                                        </td>
                                                        <td
                                                            :rowspan="
                                                                item
                                                                    .memoria_operacion_detalles
                                                                    .length
                                                            "
                                                        >
                                                            {{
                                                                item.codigo_actividad
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                item_mod.partida
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                item_mod.descripcion
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.nro }}
                                                        </td>
                                                        <!-- <td>
                                                            {{
                                                                item_mod.descripcion_detallada
                                                            }}
                                                        </td> -->
                                                        <td>
                                                            {{
                                                                item_mod.cantidad
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                item_mod.unidad
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.costo }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.total }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                item_mod.justificacion
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.ene }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.feb }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.mar }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.abr }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.may }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.jun }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.jul }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.ago }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.sep }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.oct }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.nov }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.dic }}
                                                        </td>
                                                        <td
                                                            :class="{
                                                                'bg-red':
                                                                    parseFloat(
                                                                        item_mod.saldo
                                                                    ) == 0,
                                                            }"
                                                        >
                                                            {{
                                                                item_mod.total_actividad
                                                            }}
                                                        </td>
                                                    </tr>
                                                    <!-- sin rowspan -->
                                                    <tr v-else>
                                                        <!-- <td>
                                                            {{ item_mod.ue }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.prog }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.act }}
                                                        </td> -->
                                                        <td>
                                                            {{
                                                                item_mod.partida
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                item_mod.descripcion
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.nro }}
                                                        </td>
                                                        <!-- <td>
                                                            {{
                                                                item_mod.descripcion_detallada
                                                            }}
                                                        </td> -->
                                                        <td>
                                                            {{
                                                                item_mod.cantidad
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                item_mod.unidad
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.costo }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.total }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                item_mod.justificacion
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.ene }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.feb }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.mar }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.abr }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.may }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.jun }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.jul }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.ago }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.sep }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.oct }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.nov }}
                                                        </td>
                                                        <td>
                                                            {{ item_mod.dic }}
                                                        </td>
                                                        <td
                                                            :class="{
                                                                'bg-red':
                                                                    parseFloat(
                                                                        item_mod.saldo
                                                                    ) == 0,
                                                            }"
                                                        >
                                                            {{
                                                                item_mod.total_actividad
                                                            }}
                                                        </td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </tbody>
                                        <tfoot class="bg-primary">
                                            <tr>
                                                <th
                                                    colspan="9"
                                                    class="text-center"
                                                >
                                                    TOTAL PARTIDA
                                                </th>
                                                <th colspan="2"></th>
                                                <th>
                                                    {{
                                                        oMemoriaCalculo.total_actividades
                                                    }}
                                                </th>
                                                <th></th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_ene
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_feb
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_mar
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_abr
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_may
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_jun
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_jul
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_ago
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_sep
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_oct
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_nov
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_dic
                                                    }}
                                                </th>
                                                <th class="text-center">
                                                    {{
                                                        oMemoriaCalculo.total_final
                                                    }}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
            oMemoriaCalculo: {
                operacions: [],
            },
            tabla: "",
        };
    },
    mounted() {
        this.getMemoriaCalculo();
        this.loadingWindow.close();
    },
    methods: {
        // OBTENER EL REGISTRO DETALLE FORMULARIO
        getMemoriaCalculo() {
            axios.get("/admin/memoria_calculos/" + this.id).then((response) => {
                this.oMemoriaCalculo = response.data.memoria_calculo;
            });
        },
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD/MM/YYYY");
        },
    },
};
</script>

<style>
#contenedor_tabla {
    width: 100%;
    overflow: auto;
}

#contenedor_tabla thead tr th,
#contenedor_tabla tfoot tr th,
#contenedor_tabla tbody tr td {
    font-size: 0.7em;
    vertical-align: middle;
}
</style>
