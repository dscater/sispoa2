<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Anteproyecto POA</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-primary">
                            <div class="card-header">
                                <button
                                    class="btn btn-primary"
                                    @click="generarAnteproyecto"
                                    v-html="textoBoton"
                                    :disabled="enviando"
                                ></button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <b-col lg="10" class="my-1">
                                        <b-form-group
                                            label="Buscar"
                                            label-for="filter-input"
                                            label-cols-sm="3"
                                            label-align-sm="right"
                                            label-size="sm"
                                            class="mb-0"
                                        >
                                            <b-input-group size="sm">
                                                <b-form-input
                                                    id="filter-input"
                                                    v-model="filter"
                                                    type="search"
                                                    placeholder="Buscar"
                                                ></b-form-input>

                                                <b-input-group-append>
                                                    <b-button
                                                        class="bg-lightblue"
                                                        :disabled="!filter"
                                                        @click="filter = ''"
                                                        >Borrar</b-button
                                                    >
                                                </b-input-group-append>
                                            </b-input-group>
                                        </b-form-group>
                                    </b-col>
                                    <div class="col-md-12">
                                        <b-overlay
                                            :show="showOverlay"
                                            rounded="sm"
                                        >
                                            <b-table
                                                :fields="fields"
                                                :items="listRegistros"
                                                show-empty
                                                stacked="md"
                                                thead-class="bg-lightblue"
                                                responsive
                                                :current-page="currentPage"
                                                :per-page="perPage"
                                                @filtered="onFiltered"
                                                empty-text="Sin resultados"
                                                empty-filtered-text="Sin resultados"
                                                :filter="filter"
                                            >
                                                <template #cell(form4)="row">
                                                    <div class="col-12 text-left">
                                                        <a :href="row.item.url_form4_pdf" target="_blank" class="btn btn-outline-primary">PDF</a>
                                                        <a :href="row.item.url_form4_excel" target="_blank" class="btn btn-outline-success">Excel</a>
                                                    </div>
                                                </template>
                                                <template #cell(form5)="row">
                                                    <div class="col-12 text-left">
                                                        <a :href="row.item.url_form5_pdf" target="_blank" class="btn btn-outline-primary">PDF</a>
                                                        <a :href="row.item.url_form5_excel" target="_blank" class="btn btn-outline-success">Excel</a>
                                                    </div>
                                                </template>
                                                <template #cell(memoria)="row">
                                                    <div class="col-12 text-left">
                                                        <a :href="row.item.url_memoria_pdf" target="_blank" class="btn btn-outline-primary">PDF</a>
                                                        <a :href="row.item.url_memoria_excel" target="_blank" class="btn btn-outline-success">Excel</a>
                                                    </div>
                                                </template>
                                                <template
                                                    #cell(certificacion)="row"
                                                >
                                                <div class="col-12 text-left">
                                                    <a :href="row.item.url_certificacion_pdf" target="_blank" class="btn btn-outline-primary">PDF</a>
                                                    <a :href="row.item.url_certificacion_excel" target="_blank" class="btn btn-outline-success">Excel</a>
                                                </div>
                                                </template>
                                            </b-table>
                                        </b-overlay>
                                        <div class="row">
                                            <b-col
                                                sm="6"
                                                md="2"
                                                class="ml-auto my-1"
                                            >
                                                <b-form-select
                                                    align="right"
                                                    id="per-page-select"
                                                    v-model="perPage"
                                                    :options="pageOptions"
                                                    size="sm"
                                                ></b-form-select>
                                            </b-col>
                                            <b-col
                                                sm="6"
                                                md="2"
                                                class="my-1 mr-auto"
                                                v-if="perPage"
                                            >
                                                <b-pagination
                                                    v-model="currentPage"
                                                    :total-rows="totalRows"
                                                    :per-page="perPage"
                                                    align="left"
                                                ></b-pagination>
                                            </b-col>
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
            permisos: localStorage.getItem("permisos"),
            search: "",
            listRegistros: [],
            errors: [],
            showOverlay: false,
            accion: "nuevo",
            enviando: false,
            fields: [
                {
                    key: "form4",
                    label: "Formulario 4",
                    sortable: true,
                },
                {
                    key: "form5",
                    label: "Formulario 5",
                    sortable: true,
                },
                {
                    key: "memoria",
                    label: "Memorias de cálculo",
                    sortable: true,
                },
                {
                    key: "certificacion",
                    label: "Certificación POA",
                    sortable: true,
                },
                { key: "fecha_cierre_t", label: "Fecha cierre" },
            ],
            loading: true,
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            oAnteproyecto: {
                id: 0,
                nombre: "",
            },
            currentPage: 1,
            perPage: 5,
            pageOptions: [
                { value: 5, text: "Mostrar 5 Registros" },
                { value: 10, text: "Mostrar 10 Registros" },
                { value: 25, text: "Mostrar 25 Registros" },
                { value: 50, text: "Mostrar 50 Registros" },
                { value: 100, text: "Mostrar 100 Registros" },
                { value: this.totalRows, text: "Mostrar Todo" },
            ],
            totalRows: 10,
            filter: null,
        };
    },
    computed: {
        textoBoton() {
            if (this.enviando) {
                return "Generando...";
            } else {
                return "Generar Anteproyecto";
            }
        },
    },
    mounted() {
        this.loadingWindow.close();
        this.getAnteproyectos();
    },
    methods: {
        // Seleccionar Opciones de Tabla
        editarRegistro(item) {
            this.accion = "edit";
            this.oAnteproyecto.id = item.id;
            this.oAnteproyecto.nombre = item.nombre ? item.nombre : "";
        },
        // Listar Anteproyectos
        getAnteproyectos() {
            this.showOverlay = true;
            let url = "/admin/anteproyectos";
            if (this.pagina != 0) {
                url += "?page=" + this.pagina;
            }
            axios
                .get(url, {
                    params: { per_page: this.per_page },
                })
                .then((res) => {
                    this.showOverlay = false;
                    this.listRegistros = res.data.anteproyectos;
                    this.totalRows = res.data.total;
                });
        },
        generarAnteproyecto() {
            this.enviando = true;
            try {
                this.textoBtn = "Enviando...";
                let url = "/admin/anteproyectos";
                axios
                    .post(url)
                    .then((res) => {
                        this.enviando = false;
                        Swal.fire({
                            icon: "success",
                            title: res.data.msj,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.getAnteproyectos();
                        this.errors = [];
                    })
                    .catch((error) => {
                        this.enviando = false;
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors;
                            }
                        }
                    });
            } catch (e) {
                this.enviando = false;
                console.log(e);
            }
        },
        eliminaAnteproyecto(id, descripcion) {
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
                        .post("/admin/anteproyectos/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            if (res.data.sw) {
                                this.getAnteproyectos();
                                this.filter = "";
                                Swal.fire({
                                    icon: "success",
                                    title: res.data.msj,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: res.data.msj,
                                    showConfirmButton: false,
                                    timer: 2500,
                                });
                            }
                        });
                }
            });
        },
        abreModal(tipo_accion = "nuevo", anteproyecto = null) {
            if (anteproyecto) {
                this.oAnteproyecto = anteproyecto;
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        limpiaAnteproyecto() {
            this.oAnteproyecto.nombre = "";
            this.accion = "nuevo";
        },
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD [de] MMMM [del] YYYY");
        },
    },
};
</script>

<style></style>
