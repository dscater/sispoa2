<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Formulario 5</h1>
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
                                                        variant="primary"
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
                                                responsive
                                                :current-page="currentPage"
                                                :per-page="perPage"
                                                @filtered="onFiltered"
                                                empty-text="Sin resultados"
                                                empty-filtered-text="Sin resultados"
                                                :filter="filter"
                                            >
                                                <template
                                                    #cell(fecha_registro)="row"
                                                >
                                                    {{
                                                        formatoFecha(
                                                            row.item
                                                                .fecha_registro
                                                        )
                                                    }}
                                                </template>
                                                <template #cell(accion)="row">
                                                    <div
                                                        class="row justify-content-between"
                                                    >
                                                        <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-primary"
                                                            class="btn-flat btn-block"
                                                            title="Descargar Pdf"
                                                            @click="
                                                                descargarExcel(
                                                                    row.item.id,
                                                                    'pdf'
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="fa fa-file-pdf"
                                                            ></i>
                                                        </b-button>
                                                        <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-success"
                                                            class="btn-flat btn-block"
                                                            title="Descargar Excel"
                                                            @click="
                                                                descargarExcel(
                                                                    row.item.id,
                                                                    'excel'
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="fa fa-file-excel"
                                                            ></i>
                                                        </b-button>

                                                        <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-primary"
                                                            class="btn-flat btn-block"
                                                            title="Ver detalle"
                                                            @click="
                                                                show(
                                                                    row.item.id
                                                                )
                                                            "
                                                        >
                                                            Ver detalles
                                                        </b-button>
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
            showOverlay: false,
            fields: [
                {
                    key: "memoria.formulario.codigo_pei",
                    label: "Código PEI",
                    sortable: true,
                },
                {
                    key: "memoria.formulario.unidad.nombre",
                    label: "Unidad Organizacional",
                    sortable: true,
                },
                {
                    key: "memoria.operacions.length",
                    label: "Total operaciones",
                    sortable: true,
                },
                {
                    key: "memoria.total_final",
                    label: "Total monto formulario",
                    sortable: true,
                },
                {
                    key: "estado_aprobado",
                    label: "Estado",
                    sortable: true,
                },
                {
                    key: "fecha_registro",
                    label: "Fecha de registro",
                    sortable: true,
                },
                { key: "accion", label: "Acción" },
            ],
            loading: true,
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            currentPage: 1,
            perPage: 10,
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
            estado_aprobado: false,
        };
    },
    mounted() {
        this.loadingWindow.close();
        this.getFormularioCinco();
        this.getAprobado();
    },
    methods: {
        // Listar FormularioCinco
        getFormularioCinco() {
            this.showOverlay = true;
            this.muestra_modal = false;
            let url = "/admin/formulario_cinco";
            axios.get(url).then((res) => {
                this.showOverlay = false;
                this.listRegistros = res.data.listado;
                this.totalRows = res.data.total;
            });
        },
        eliminaFormularioCinco(id, descripcion) {
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
                        .post("/admin/formulario_cinco/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            this.getFormularioCinco();
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
        show(id) {
            this.$router.push({
                name: "formulario_cinco.show",
                params: { id: id },
            });
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD/MM/YYYY");
        },
        descargarExcel(id, tipo) {
            let config = {
                responseType: "blob",
            };
            axios
                .post(
                    "/admin/reportes/formulario_cinco_excel",
                    { id: id, tipo: tipo },
                    config
                )
                .then((response) => {
                    if (tipo == "pdf") {
                        let pdfBlob = new Blob([response.data], {
                            type: "application/pdf",
                        });
                        let urlReporte = URL.createObjectURL(pdfBlob);
                        window.open(urlReporte);
                    } else {
                        var fileURL = window.URL.createObjectURL(
                            new Blob([response.data])
                        );
                        var fileLink = document.createElement("a");
                        fileLink.href = fileURL;
                        fileLink.setAttribute(
                            "download",
                            "formulario_cinco.xlsx"
                        );
                        document.body.appendChild(fileLink);

                        fileLink.click();
                    }
                })
                .catch(async (error) => {
                    console.log(error);
                    let responseObj = await error.response.data.text();
                    responseObj = JSON.parse(responseObj);
                    this.enviando = false;
                    if (error.response) {
                    }
                });
        },
        getAprobado() {
            axios.get("/admin/get_aprobados").then((response) => {
                this.estado_aprobado = response.data;
            });
        },
    },
};
</script>

<style></style>
