<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Informe de Actividad realizada</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button
                                            v-if="
                                                permisos.includes(
                                                    'actividad_realizadas.create'
                                                )
                                            "
                                            class="btn btn-outline-primary bg-lightblue btn-flat btn-block"
                                            @click="
                                                abreModal('nuevo');
                                                limpiaActividadRealizada();
                                            "
                                        >
                                            <i class="fa fa-plus"></i>
                                            Nuevo
                                        </button>
                                    </div>
                                </div>
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
                                                <template #cell(archivo)="row">
                                                    <a
                                                        href=""
                                                        v-if="
                                                            row.item.archivo &&
                                                            row.item.archivo !=
                                                                ''
                                                        "
                                                        @click.prevent="
                                                            descargarArchivo(
                                                                row.item.id
                                                            )
                                                        "
                                                        >Descargar</a
                                                    >
                                                    <span v-else></span>
                                                </template>

                                                <template #cell(estado)="row">
                                                    <template
                                                        v-if="row.item.estado"
                                                    >
                                                        <span
                                                            class="badge badge-danger"
                                                            v-if="
                                                                row.item
                                                                    .estado ==
                                                                'RECHAZADO'
                                                            "
                                                            >RECHAZADO</span
                                                        >
                                                        <span
                                                            class="badge badge-success"
                                                            v-else
                                                            >APROBADO</span
                                                        >
                                                    </template>
                                                    <template v-else>
                                                        <span
                                                            class="badge badge-gray bg-gray">
                                                            Sin verificar</span
                                                        >
                                                    </template>
                                                </template>

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
                                                        class="row justify-content-center"
                                                    >
                                                        <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-warning"
                                                            class="btn-flat mb-1"
                                                            title="Editar registro"
                                                            @click="
                                                                editarRegistro(
                                                                    row.item
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="fa fa-edit"
                                                            ></i> </b-button
                                                        ><br />
                                                        <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-danger"
                                                            class="btn-flat"
                                                            title="Eliminar registro"
                                                            @click="
                                                                eliminaActividadRealizada(
                                                                    row.item.id,
                                                                    row.item
                                                                        .descripcion
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="fa fa-trash"
                                                            ></i>
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
        <Nuevo
            :muestra_modal="muestra_modal"
            :accion="modal_accion"
            :actividad_realizada="oActividadRealizada"
            @close="muestra_modal = false"
            @envioModal="getActividadRealizadas"
        ></Nuevo>

        <div
            class="modal fade"
            :class="{ show: modal_imagen }"
            id="modal-default"
            aria-modal="true"
            role="dialog"
        >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-lightblue">
                        <h4 class="modal-title">Ver archivo</h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="modal_imagen = false"
                        >
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <img
                                    :src="file_path"
                                    alt="Imagen"
                                    class="w-100"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button
                            type="button"
                            class="btn btn-default"
                            data-dismiss="modal"
                            @click="modal_imagen = false"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
            permisos: localStorage.getItem("permisos"),
            search: "",
            listRegistros: [],
            showOverlay: false,
            fields: [
                {
                    key: "descripcion",
                    label: "Descripción del informe",
                    sortable: true,
                },
                { key: "estado", label: "Estado" },
                { key: "archivo", label: "Archivo" },
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
            muestra_modal: false,
            modal_accion: "nuevo",
            oActividadRealizada: {
                id: 0,
                descripcion: "",
                archivo: null,
                estado: "",
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
            modal_imagen: false,
            file_path: "",
        };
    },
    mounted() {
        this.loadingWindow.close();
        this.getActividadRealizadas();
    },
    methods: {
        // Ver archivo
        verImagen(path) {
            this.file_path = path;
            this.modal_imagen = true;
        },
        // Seleccionar Opciones de Tabla
        editarRegistro(item) {
            this.oActividadRealizada.id = item.id;
            this.oActividadRealizada.descripcion = item.descripcion
                ? item.descripcion
                : "";
            this.oActividadRealizada.estado = item.estado ? item.estado : "";
            this.modal_accion = "edit";
            this.muestra_modal = true;
        },

        // Listar ActividadRealizadas
        getActividadRealizadas() {
            this.showOverlay = true;
            this.muestra_modal = false;
            let url = "/admin/actividad_realizadas";
            if (this.pagina != 0) {
                url += "?page=" + this.pagina;
            }
            axios
                .get(url, {
                    params: { per_page: this.per_page },
                })
                .then((res) => {
                    this.showOverlay = false;
                    this.listRegistros = res.data.actividad_realizadas;
                    this.totalRows = res.data.total;
                });
        },
        eliminaActividadRealizada(id, descripcion) {
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
                        .post("/admin/actividad_realizadas/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            this.getActividadRealizadas();
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
        abreModal(tipo_accion = "nuevo", actividad_realizada = null) {
            this.muestra_modal = true;
            this.modal_accion = tipo_accion;
            if (actividad_realizada) {
                this.oActividadRealizada = actividad_realizada;
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        limpiaActividadRealizada() {
            this.oActividadRealizada.descripcion = "";
            this.oActividadRealizada.foto = null;
        },
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD/MM/YYYY");
        },
        descargarArchivo(id) {
            let config = {
                responseType: "blob",
            };
            axios
                .post("/admin/actividad_realizadas/archivo/" + id, null, config)
                .then((res) => {
                    console.log(res);
                    let nom = res.headers["content-disposition"].split("=");
                    var fileURL = window.URL.createObjectURL(
                        new Blob([res.data])
                    );
                    var fileLink = document.createElement("a");

                    fileLink.href = fileURL;
                    fileLink.setAttribute("download", nom[1]);
                    document.body.appendChild(fileLink);

                    fileLink.click();
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
    },
};
</script>

<style></style>
