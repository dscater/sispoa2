<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            Semaforos -
                            <small
                                >Ejecución fisica por número de
                                actividades</small
                            >
                        </h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 p-2">
                        <router-link
                            :to="{ name: 'semaforos.index' }"
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
                                            Ejecución fisica por número de
                                            actividades
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label
                                                            >Fecha inicio</label
                                                        >
                                                        <input
                                                            class="form-control"
                                                            type="date"
                                                            v-model="
                                                                filtro.fecha_ini
                                                            "
                                                            @change="getResumen"
                                                            @keyup="getResumen"
                                                        />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label
                                                            >Fecha final</label
                                                        >
                                                        <input
                                                            class="form-control"
                                                            type="date"
                                                            v-model="
                                                                filtro.fecha_fin
                                                            "
                                                            @change="getResumen"
                                                            @keyup="getResumen"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Mes</label>
                                                        <select
                                                            class="form-control"
                                                            v-model="filtro.mes"
                                                            @change="getResumen"
                                                        >
                                                            <option
                                                                value="todos"
                                                            >
                                                                Todos
                                                            </option>
                                                            <option
                                                                v-for="item in listMeses"
                                                                :value="
                                                                    item.value
                                                                "
                                                            >
                                                                {{ item.label }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Año</label>
                                                        <select
                                                            class="form-control"
                                                            v-model="
                                                                filtro.anio
                                                            "
                                                            @change="getResumen"
                                                        >
                                                            <option
                                                                v-for="item in listAnios"
                                                                :value="item"
                                                            >
                                                                {{ item }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
            html: "",
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
                    label: "Novimebre",
                },
                {
                    value: "12",
                    label: "Diciembre",
                },
            ],
            listAnios: [],
            filtro: {
                fecha_ini: "",
                fecha_fin: "",
                mes: "todos",
                anio: new Date().getFullYear(),
            },
        };
    },
    mounted() {
        this.getResumen();
        this.loadingWindow.close();
    },
    methods: {
        // OBTENER EL REGISTRO DETALLE FORMULARIO
        getResumen() {
            axios
                .get("/admin/semaforos/getResumenSemaforo", {
                    params: this.filtro,
                })
                .then((response) => {
                    this.listAnios = response.data.arr_anios;
                    this.html = response.data.html;
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
