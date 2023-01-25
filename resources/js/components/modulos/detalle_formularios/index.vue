<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Detalle Formulario 4</h1>
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
                                        <router-link
                                            :to="{
                                                name: 'detalle_formularios.create',
                                            }"
                                            v-if="
                                                permisos.includes(
                                                    'detalle_formularios.create'
                                                )
                                            "
                                            class="btn btn-outline-primary bg-lightblue btn-flat btn-block"
                                        >
                                            <i class="fa fa-plus"></i>
                                            Nuevo
                                        </router-link>
                                    </div>
                                    <div
                                        class="col-md-3"
                                        v-if="
                                            permisos.includes(
                                                'configuracion.modulos'
                                            )
                                        "
                                    >
                                        <button
                                            class="btn btn-outline-primary bg-lightblue btn-flat btn-block"
                                            @click="muestraConfiguracionModulos"
                                        >
                                            <i class="fa fa-cog"></i>
                                            Configuración de modulos
                                        </button>
                                        <ConfiguracionModulo
                                            :muestra_modal="
                                                muestra_configuracion_modulo
                                            "
                                            @close="
                                                muestra_configuracion_modulo = false
                                            "
                                        ></ConfiguracionModulo>
                                    </div>

                                    <!-- APROBAR FORMULARIOS -->
                                    <div
                                        class="col-md-3"
                                        v-if="
                                            permisos.includes('aprobar.modulos')
                                        "
                                    >
                                        <button
                                            class="btn btn-outline-primary bg-lightblue btn-flat btn-block"
                                            @click="muestraAprobarFormularios"
                                        >
                                            <i class="fa fa-check-square"></i>
                                            Aprobar formulario
                                        </button>
                                        <AprobarFormularios
                                            :muestra_modal="
                                                muestra_aprobar_formularios
                                            "
                                            @close="
                                                muestra_aprobar_formularios = false
                                            "
                                        ></AprobarFormularios>
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
                                                <template
                                                    #cell(formulario.codigo_pei)="row"
                                                >
                                                    <span
                                                        v-html="
                                                            ingresarEnter(
                                                                row.item
                                                                    .formulario
                                                                    .codigo_pei
                                                            )
                                                        "
                                                    ></span>
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

                                                <template #cell(detalles)="row">
                                                    <div
                                                        class="row justify-content-between"
                                                    >
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
                                                <template #cell(accion)="row">
                                                    <div
                                                        class="row justify-content-between"
                                                    >
                                                        <!-- <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-primary"
                                                            class="btn-flat btn-block"
                                                            title="Archivo PDF"
                                                            @click="
                                                                pdf(row.item.id)
                                                            "
                                                        >
                                                            <i
                                                                class="fa fa-file-pdf"
                                                            ></i>
                                                        </b-button> -->

                                                        <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-warning"
                                                            class="btn-flat btn-block"
                                                            title="Editar registro"
                                                            v-if="
                                                                permisos.includes(
                                                                    'detalle_formularios.edit'
                                                                ) &&
                                                                !estado_aprobado
                                                            "
                                                            @click="
                                                                editar(
                                                                    row.item.id
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="fa fa-edit"
                                                            ></i>
                                                        </b-button>
                                                        <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-danger"
                                                            class="btn-flat btn-block"
                                                            title="Eliminar registro"
                                                            v-if="
                                                                permisos.includes(
                                                                    'detalle_formularios.destroy'
                                                                ) &&
                                                                !estado_aprobado
                                                            "
                                                            @click="
                                                                eliminaDetalleFormulario(
                                                                    row.item.id,
                                                                    row.item
                                                                        .formulario
                                                                        .codigo_pei +
                                                                        ' con fecha de registro ' +
                                                                        formatoFecha(
                                                                            row
                                                                                .item
                                                                                .fecha_registro
                                                                        )
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
    </div>
</template>

<script>
import ConfiguracionModulo from "../configuracion_modulos/ConfiguracionModulo.vue";
import AprobarFormularios from "../aprobar_formularios/AprobarFormularios.vue";
export default {
    components: {
        ConfiguracionModulo,
        AprobarFormularios,
    },
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            permisos: localStorage.getItem("permisos"),
            search: "",
            listRegistros: [],
            showOverlay: false,
            fields: [
                {
                    key: "formulario.codigo_pei",
                    label: "Código PEI",
                    sortable: true,
                },
                {
                    key: "operacions.length",
                    label: "Total operaciones",
                    sortable: true,
                },
                {
                    key: "fecha_registro",
                    label: "Fecha de registro",
                    sortable: true,
                },
                {
                    key: "estado_aprobado",
                    label: "Estado",
                    sortable: true,
                },
                { key: "detalles", label: "Ver más" },
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
            muestra_configuracion_modulo: false,
            muestra_aprobar_formularios: false,
            estado_aprobado: false,
        };
    },
    mounted() {
        this.loadingWindow.close();
        this.getDetalleFormularios();
        this.obtienePermisos();
        this.getAprobado();
    },
    methods: {
        // Listar DetalleFormularios
        getDetalleFormularios() {
            this.showOverlay = true;
            this.muestra_modal = false;
            let url = "/admin/detalle_formularios";
            if (this.pagina != 0) {
                url += "?page=" + this.pagina;
            }
            axios
                .get(url, {
                    params: { per_page: this.per_page },
                })
                .then((res) => {
                    this.showOverlay = false;
                    this.listRegistros = res.data.detalle_formularios;
                    this.totalRows = res.data.total;
                });
        },
        eliminaDetalleFormulario(id, descripcion) {
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
                            if (res.data.sw) {
                                this.getDetalleFormularios();
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
        editar(id) {
            this.$router.push({
                name: "detalle_formularios.edit",
                params: { id: id },
            });
        },
        show(id) {
            this.$router.push({
                name: "detalle_formularios.show",
                params: { id: id },
            });
        },
        pdf(id) {
            let config = {
                responseType: "blob",
            };
            axios
                .post("/admin/detalle_formularios/pdf/" + id, null, config)
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
                    console.log(error);
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
        ingresarEnter(valor) {
            return valor.replace(",", "<br/>");
        },
        muestraConfiguracionModulos() {
            this.muestra_configuracion_modulo = true;
        },
        obtienePermisos() {
            axios
                .get("/admin/usuarios/getPermisos/" + this.user.id)
                .then((res) => {
                    localStorage.setItem("permisos", JSON.stringify(res.data));
                    this.permisos = localStorage.getItem("permisos");
                });
        },
        muestraAprobarFormularios() {
            this.muestra_aprobar_formularios = true;
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
