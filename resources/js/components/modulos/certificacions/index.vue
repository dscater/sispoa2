<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Certificación POA</h1>
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
                                                name: 'certificacions.create',
                                            }"
                                            v-if="
                                                permisos.includes(
                                                    'certificacions.create'
                                                )
                                            "
                                            class="btn btn-outline-primary bg-lightblue btn-flat btn-block"
                                        >
                                            <i class="fa fa-plus"></i>
                                            Nuevo
                                        </router-link>
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
                                                :fields="fields_certificacions"
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
                                                <template #cell(codigo)="row">
                                                    <span
                                                        v-html="
                                                            row.item.codigo +
                                                            '-' +
                                                            row.item.accion
                                                        "
                                                    ></span>
                                                </template>
                                                <template
                                                    #cell(formulario.codigo_pei)="row"
                                                >
                                                    <span
                                                        v-html="
                                                            row.item.formulario
                                                                .codigo_pei
                                                        "
                                                    ></span>
                                                </template>

                                                <template #cell(mo_id)="row">
                                                    <b-button
                                                        variant="primary"
                                                        size="sm"
                                                        @click="
                                                            row.toggleDetails
                                                        "
                                                    >
                                                        {{
                                                            row.detailsShowing
                                                                ? "Ocultar"
                                                                : "Mostrar"
                                                        }}
                                                        Descripción
                                                    </b-button>
                                                </template>

                                                <template #row-details="row">
                                                    <b-card>
                                                        <b-row
                                                            class="mb-2"
                                                            style="
                                                                overflow: auto;
                                                            "
                                                        >
                                                            <div
                                                                v-for="(
                                                                    cd, index_cd
                                                                ) in row.item
                                                                    .certificacion_detalles"
                                                                :key="cd.id"
                                                            >
                                                                <hr
                                                                    v-if="
                                                                        index_cd >
                                                                        0
                                                                    "
                                                                />
                                                                <strong
                                                                    >Código de
                                                                    operación: </strong
                                                                >{{
                                                                    cd
                                                                        .memoria_operacion
                                                                        .codigo_operacion
                                                                }}<br />
                                                                <strong
                                                                    >Operación: </strong
                                                                >{{
                                                                    cd
                                                                        .memoria_operacion
                                                                        .descripcion_operacion
                                                                }}<br />
                                                                <strong
                                                                    >Código de
                                                                    tarea: </strong
                                                                >{{
                                                                    cd
                                                                        .memoria_operacion
                                                                        .codigo_actividad
                                                                }}<br />
                                                                <strong
                                                                    >Actividad/Tarea: </strong
                                                                >{{
                                                                    cd
                                                                        .memoria_operacion
                                                                        .descripcion_actividad
                                                                }}<br />
                                                                <strong
                                                                    >Partida: </strong
                                                                >{{
                                                                    cd
                                                                        .memoria_operacion_detalle
                                                                        .partida
                                                                }}<br />
                                                            </div>
                                                            <template
                                                                v-if="
                                                                    row.item
                                                                        .url_archivo
                                                                "
                                                            >
                                                                <strong
                                                                    >Archivo:
                                                                </strong>
                                                                <a
                                                                    :href="
                                                                        row.item
                                                                            .url_archivo
                                                                    "
                                                                    target="_blank"
                                                                    >Descargar</a
                                                                >
                                                            </template>
                                                        </b-row>
                                                        <b-button
                                                            size="sm"
                                                            variant="primary"
                                                            @click="
                                                                row.toggleDetails
                                                            "
                                                            >Ocultar</b-button
                                                        >
                                                    </b-card>
                                                </template>

                                                <template
                                                    #cell(descargar)="row"
                                                >
                                                    <a
                                                        v-if="
                                                            row.item.url_archivo
                                                        "
                                                        :href="
                                                            row.item.url_archivo
                                                        "
                                                        class="btn btn-primary"
                                                        target="_blank"
                                                        >Descargar</a
                                                    >
                                                    <button
                                                        class="btn btn-sm btn-flat"
                                                        v-else
                                                        disabled
                                                    >
                                                        No se cargo ningún
                                                        archivo
                                                    </button>
                                                </template>

                                                <template
                                                    #cell(cantidad_usar)="row"
                                                >
                                                    <div
                                                        v-for="(
                                                            cd, index_cd
                                                        ) in row.item
                                                            .certificacion_detalles"
                                                        :key="cd.id"
                                                    >
                                                        <hr
                                                            v-if="index_cd > 0"
                                                        />
                                                        <strong
                                                            >Cantidad a usar: </strong
                                                        >{{ cd.cantidad_usar }}
                                                        <br />
                                                        <strong
                                                            >Total a usar: </strong
                                                        >{{
                                                            cd.presupuesto_usarse
                                                        }}
                                                    </div>
                                                </template>
                                                <template #cell(fechas)="row">
                                                    <strong>Inicio: </strong
                                                    >{{
                                                        formatoFecha(
                                                            row.item.inicio
                                                        )
                                                    }}<br />
                                                    <strong>Final: </strong
                                                    >{{
                                                        formatoFecha(
                                                            row.item.final
                                                        )
                                                    }}
                                                </template>
                                                <template #cell(estado)="row">
                                                    <template
                                                        v-if="
                                                            row.item.anulado ==
                                                            0
                                                        "
                                                    >
                                                        <span
                                                            :title="
                                                                row.item.estado
                                                            "
                                                            class="badge bg-danger"
                                                            v-if="
                                                                row.item
                                                                    .estado ==
                                                                'PENDIENTE'
                                                            "
                                                            >{{
                                                                row.item.estado
                                                            }}</span
                                                        >
                                                        <span
                                                            :title="
                                                                row.item.estado
                                                            "
                                                            class="badge bg-success"
                                                            v-else
                                                            >{{
                                                                row.item.estado
                                                            }}</span
                                                        >
                                                    </template>
                                                    <template v-else>
                                                        <span
                                                            :title="'ANULADO'"
                                                            class="badge bg-secondary"
                                                            >ANULADO</span
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
                                                <!-- <template #cell(detalles)="row">
                                                    <b-button
                                                        size="sm"
                                                        pill
                                                        variant="outline-warning"
                                                        class="btn-flat btn-block"
                                                        title="Editar registro"
                                                        @click="
                                                            editar(row.item.id)
                                                        "
                                                    >
                                                        <i
                                                            class="fa fa-edit"
                                                        ></i>
                                                    </b-button>
                                                </template> -->

                                                <template #cell(accion)="row">
                                                    <div
                                                        class="row justify-content-between"
                                                    >
                                                        <b-button
                                                            v-if="
                                                                row.item
                                                                    .anulado ==
                                                                0
                                                            "
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
                                                        </b-button>

                                                        <b-button
                                                            v-if="
                                                                row.item
                                                                    .anulado ==
                                                                    0 &&
                                                                row.item
                                                                    .estado !=
                                                                    'APROBADO'
                                                            "
                                                            size="sm"
                                                            pill
                                                            variant="outline-warning"
                                                            class="btn-flat btn-block"
                                                            title="Editar registro"
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
                                                            v-if="
                                                                row.item
                                                                    .anulado ==
                                                                    0 &&
                                                                row.item
                                                                    .estado ==
                                                                    'PENDIENTE' &&
                                                                user.tipo ==
                                                                    'SUPER USUARIO'
                                                            "
                                                            size="sm"
                                                            pill
                                                            variant="outline-success"
                                                            class="btn-flat btn-block"
                                                            title="Aprobar"
                                                            @click="
                                                                aprobarCertificacion(
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
                                                                class="fa fa-check"
                                                            ></i>
                                                        </b-button>
                                                        <b-button
                                                            v-else
                                                            size="sm"
                                                            pill
                                                            variant="outline-info"
                                                            class="btn-flat btn-block"
                                                            title="Desaprobar"
                                                            @click="
                                                                desaprobarCertificacion(
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
                                                                class="fa fa-times-circle"
                                                            ></i>
                                                        </b-button>

                                                        <template
                                                            v-if="
                                                                row.item
                                                                    .anulado ==
                                                                    0 &&
                                                                row.item
                                                                    .estado !=
                                                                    'APROBADO'
                                                            "
                                                        >
                                                            <b-button
                                                                v-if="
                                                                    permisos.includes(
                                                                        'certificacions.destroy'
                                                                    )
                                                                "
                                                                size="sm"
                                                                pill
                                                                variant="outline-danger"
                                                                class="btn-flat btn-block"
                                                                title="Anular"
                                                                @click="
                                                                    anularCertificacion(
                                                                        row.item
                                                                            .id,
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
                                                                    class="fa fa-times"
                                                                ></i>
                                                            </b-button>
                                                        </template>
                                                        <template v-else>
                                                            <template
                                                                v-if="
                                                                    row.item
                                                                        .estado !=
                                                                        'APROBADO' &&
                                                                    permisos.includes(
                                                                        'certificacions.destroy'
                                                                    )
                                                                "
                                                            >
                                                                <b-button
                                                                    size="sm"
                                                                    pill
                                                                    variant="outline-success"
                                                                    class="btn-flat btn-block"
                                                                    title="Activar certificación"
                                                                    @click="
                                                                        activarCertificacion(
                                                                            row
                                                                                .item
                                                                                .id,
                                                                            row
                                                                                .item
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
                                                                        class="fa fa-check"
                                                                    ></i>
                                                                </b-button>
                                                            </template>
                                                        </template>

                                                        <!-- <b-button
                                                            size="sm"
                                                            pill
                                                            variant="outline-danger"
                                                            class="btn-flat btn-block"
                                                            title="Eliminar registro"
                                                            @click="
                                                                eliminaCertificacion(
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
                                                        </b-button> -->
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
            permisos:
                typeof localStorage.getItem("permisos") == "string"
                    ? JSON.parse(localStorage.getItem("permisos"))
                    : localStorage.getItem("permisos"),
            user: JSON.parse(localStorage.getItem("user")),
            search: "",
            listRegistros: [],
            showOverlay: false,
            fields: [
                {
                    key: "codigo",
                    label: "Código POA",
                    sortable: true,
                },
                {
                    key: "formulario.unidad.nombre",
                    label: "Unidad Organizacional",
                    sortable: true,
                },
                {
                    key: "correlativo",
                    label: "Nro. Correlativo",
                    sortable: true,
                },
                {
                    key: "o_personal_designado.full_name",
                    label: "Personal designado",
                    sortable: true,
                },
                {
                    key: "departamento",
                    label: "Departamento(s)",
                    sortable: true,
                },
                {
                    key: "municipio",
                    label: "Municipio",
                    sortable: true,
                },
                {
                    key: "mo_id",
                    label: "Descripción Operación",
                    sortable: true,
                },
                {
                    key: "cantidad_usar",
                    label: "Certificación",
                    sortable: true,
                },
                {
                    key: "fechas",
                    label: "Fechas",
                    sortable: true,
                },
                {
                    key: "estado",
                    label: "Estado",
                    sortable: true,
                },
                { key: "fecha_registro", label: "Fecha de registro" },
                // { key: "detalles", label: "Ver más" },
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
        };
    },
    computed: {
        fields_certificacions() {
            if (this.user.tipo == "SUPER USUARIO") {
                return [
                    {
                        key: "codigo",
                        label: "Código POA",
                        sortable: true,
                    },
                    {
                        key: "formulario.unidad.nombre",
                        label: "Unidad Organizacional",
                        sortable: true,
                    },
                    {
                        key: "correlativo",
                        label: "Nro. Correlativo",
                        sortable: true,
                    },
                    {
                        key: "o_personal_designado.full_name",
                        label: "Personal designado",
                        sortable: true,
                    },
                    {
                        key: "departamento",
                        label: "Departamento",
                        sortable: true,
                    },
                    {
                        key: "municipio",
                        label: "Municipio",
                        sortable: true,
                    },
                    {
                        key: "mo_id",
                        label: "Descripción Operación",
                        sortable: true,
                    },
                    {
                        key: "cantidad_usar",
                        label: "Certificación",
                        sortable: true,
                    },
                    {
                        key: "descargar",
                        label: "Descargar",
                        sortable: true,
                    },
                    {
                        key: "fechas",
                        label: "Fechas",
                        sortable: true,
                    },
                    {
                        key: "estado",
                        label: "Estado",
                        sortable: true,
                    },
                    { key: "fecha_registro", label: "Fecha de registro" },
                    // { key: "detalles", label: "Ver más" },
                    { key: "accion", label: "Acción" },
                ];
            }
            return this.fields;
        },
    },
    mounted() {
        // console.log(this.user);
        this.loadingWindow.close();
        this.getCertificacions();
    },
    methods: {
        // Listar Certificacions
        getCertificacions() {
            this.showOverlay = true;
            this.muestra_modal = false;
            let url = "/admin/certificacions";
            if (this.pagina != 0) {
                url += "?page=" + this.pagina;
            }
            axios
                .get(url, {
                    params: { per_page: this.per_page },
                })
                .then((res) => {
                    this.showOverlay = false;
                    this.listRegistros = res.data.certificacions;
                    this.totalRows = res.data.total;
                });
        },
        eliminaCertificacion(id, descripcion) {
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
                        .post("/admin/certificacions/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            this.getCertificacions();
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
        aprobarCertificacion(id, descripcion) {
            Swal.fire({
                title: "¿Quierés aprobar este registro?",
                html: `<strong>${descripcion}</strong>`,
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                confirmButtonText: "Si, aprobar",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios
                        .post("/admin/certificacions/aprobar/" + id)
                        .then((res) => {
                            this.getCertificacions();
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
        desaprobarCertificacion(id, descripcion) {
            Swal.fire({
                title: "¿Quierés desaprobar este registro?",
                html: `<strong>${descripcion}</strong>`,
                showCancelButton: true,
                confirmButtonColor: "#17a2b8",
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios
                        .post("/admin/certificacions/desaprobar/" + id)
                        .then((res) => {
                            this.getCertificacions();
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
        anularCertificacion(id, descripcion) {
            Swal.fire({
                title: "¿Quierés anular este registro?",
                html: `<strong>${descripcion}</strong>`,
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Si, anular",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios
                        .post("/admin/certificacions/anular/" + id, {})
                        .then((res) => {
                            this.getCertificacions();
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
        activarCertificacion(id, descripcion) {
            Swal.fire({
                title: "¿Quierés reactivar este registro?",
                html: `<strong>${descripcion}</strong>`,
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                confirmButtonText: "Si, reactivar",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios
                        .post("/admin/certificacions/activar/" + id, {})
                        .then((res) => {
                            this.getCertificacions();
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
        editar(id) {
            this.$router.push({
                name: "certificacions.edit",
                params: { id: id },
            });
        },
        pdf(id) {
            let config = {
                responseType: "blob",
            };
            axios
                .post("/admin/certificacions/pdf/" + id, null, config)
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
    },
};
</script>

<style></style>
