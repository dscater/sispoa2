<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Semáforo</h1>
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
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Fecha inicio</label>
                                                        <input
                                                            class="form-control"
                                                            type="date"
                                                            v-model="
                                                                filtro.fecha_ini
                                                            "
                                                            @change="
                                                                getSemaforos
                                                            "
                                                            @keyup="
                                                                getSemaforos
                                                            "
                                                        />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Fecha final</label>
                                                        <input
                                                            class="form-control"
                                                            type="date"
                                                            v-model="
                                                                filtro.fecha_fin
                                                            "
                                                            @change="
                                                                getSemaforos
                                                            "
                                                            @keyup="
                                                                getSemaforos
                                                            "
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
                                                            @change="
                                                                getSemaforos
                                                            "
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
                                                            @change="
                                                                getSemaforos
                                                            "
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
                                    <div class="col-md-12">
                                        <b-overlay
                                            :show="showOverlay"
                                            rounded="sm"
                                        >
                                            <div v-html="html"></div>
                                        </b-overlay>
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
import Nuevo from "./Nuevo.vue";
export default {
    components: {
        Nuevo,
    },
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            permisos: localStorage.getItem("permisos"),
            search: "",
            loading: true,
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            showOverlay: false,
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
        this.loadingWindow.close();
        this.getSemaforos();
    },
    methods: {
        // Ver archivo
        verImagen(path) {
            this.file_path = path;
            this.modal_imagen = true;
        },
        // Seleccionar Opciones de Tabla
        editar(id) {
            this.$router.push({
                name: "semaforos.edit",
                params: { id: id },
            });
        },

        // Listar Semaforos
        getSemaforos() {
            this.showOverlay = true;
            this.muestra_modal = false;
            let url = "/admin/semaforos";
            axios
                .get(url, {
                    params: this.filtro,
                })
                .then((res) => {
                    this.listAnios = res.data.arr_anios;
                    this.showOverlay = false;
                    this.html = res.data.html;
                });
        },
        eliminaSemaforo(id, descripcion) {
            Swal.fire({
                title: "¿Quierés eliminar este registro?",
                html: `<strong>${descripcion}</strong>`,
                showCancelButton: true,
                confirmButtonColor: "#05568e",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios
                        .post("/admin/detalle_formularios/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            this.getSemaforos();
                            this.filter = "";
                            Swal.fire({
                                icon: "success",
                                title: res.data.msj,
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        });
                }
            });
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        irResumen() {
            this.$router.push({ name: "semaforos.resumen" });
        },
        limpiaSemaforo() {
            this.oSemaforo.descripcion = "";
            this.oSemaforo.foto = null;
        },
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD/MM/YYYY");
        },
    },
};
</script>

<style></style>
