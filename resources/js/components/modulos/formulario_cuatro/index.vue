<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Formulario Cuatro</h1>
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
                                                    'formulario_cuatro.create'
                                                )
                                            "
                                            class="btn btn-outline-primary bg-lightblue btn-flat btn-block"
                                            @click="
                                                abreModal('nuevo');
                                                limpiaFormularioCuatro();
                                            "
                                        >
                                            <i class="fa fa-plus"></i>
                                            Nuevo
                                        </button>
                                    </div>
                                    <!-- CONFIGURACION MODULOS -->
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
                                                <!-- <template
                                                    #cell(codigo_pei)="row"
                                                >
                                                    <span
                                                        v-html="
                                                            ingresarEnter(
                                                                row.item
                                                                    .codigo_pei
                                                            )
                                                        "
                                                    ></span>
                                                </template> -->
                                                <!-- <template
                                                    #cell(codigo_poa)="row"
                                                >
                                                    <span
                                                        v-html="
                                                            row.item.codigo_poa
                                                        "
                                                    ></span>
                                                </template> -->
                                                <template #cell(foto)="row">
                                                    <b-avatar
                                                        :src="
                                                            row.item.path_image
                                                        "
                                                        size="3rem"
                                                    ></b-avatar>
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
                                                            variant="outline-warning"
                                                            class="btn-flat btn-block"
                                                            title="Editar registro"
                                                            v-if="
                                                                permisos.includes(
                                                                    'formulario_cuatro.edit'
                                                                ) &&
                                                                row.item
                                                                    .sw_aprobado ==
                                                                    0
                                                            "
                                                            @click="
                                                                editarRegistro(
                                                                    row.item
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
                                                                    'formulario_cuatro.destroy'
                                                                ) &&
                                                                row.item
                                                                    .sw_aprobado ==
                                                                    0
                                                            "
                                                            @click="
                                                                eliminaFormularioCuatro(
                                                                    row.item.id,
                                                                    'Código PEI: ' +
                                                                        row.item
                                                                            .codigo_pei
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
            :formulario_cuatro="oFormularioCuatro"
            @close="muestra_modal = false"
            @envioModal="getFormularioCuatros"
        ></Nuevo>
    </div>
</template>

<script>
import ConfiguracionModulo from "../configuracion_modulos/ConfiguracionModulo.vue";
import AprobarFormularios from "../aprobar_formularios/AprobarFormularios.vue";
import Nuevo from "./Nuevo.vue";
export default {
    components: {
        Nuevo,
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
                    key: "codigo_pei",
                    label: "Código PEI",
                    sortable: true,
                },
                {
                    key: "resultado_institucional",
                    label: "Resultado institucional",
                    sortable: true,
                },
                {
                    key: "indicador",
                    label: "Indicador de Proceso",
                    sortable: true,
                },
                {
                    key: "codigo_poa",
                    label: "Código POA",
                    sortable: true,
                },
                {
                    key: "accion_corto",
                    label: "Acción de Corto Plazo",
                    sortable: true,
                },
                {
                    key: "indicador_proceso",
                    label: "Indicador de proceso",
                    sortable: true,
                },
                {
                    key: "linea_base",
                    label: "Línea de base",
                    sortable: true,
                },
                {
                    key: "meta",
                    label: "Meta",
                    sortable: true,
                },
                {
                    key: "presupuesto",
                    label: "Presupuesto Programado Gestión",
                    sortable: true,
                },
                { key: "ponderacion", label: "Ponderación %", sortable: true },
                { key: "unidad.nombre", label: "Unidad Organizacional" },
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
            muestra_modal: false,
            modal_accion: "nuevo",
            oFormularioCuatro: {
                id: 0,
                codigo_pei: "",
                objetivo_estrategico: "",
                codigo_pei2: "",
                objetivo_estrategico2: "",
                codigo_pei3: "",
                objetivo_estrategico3: "",
                resultado_institucional: "",
                indicador: "",
                codigo_poa: "",
                accion_corto: "",
                codigo_poa2: "",
                accion_corto2: "",
                codigo_poa3: "",
                accion_corto3: "",
                indicador_proceso: "",
                linea_base: "",
                meta: "",
                presupuesto: "",
                ponderacion: "",
                unidad_id: "",
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
            muestra_configuracion_modulo: false,
            muestra_aprobar_formularios: false,
        };
    },
    mounted() {
        this.loadingWindow.close();
        this.getFormularioCuatros();
        this.obtienePermisos();
    },
    methods: {
        // Seleccionar Opciones de Tabla
        editarRegistro(item) {
            this.oFormularioCuatro.id = item.id;
            this.oFormularioCuatro.codigo_pei = item.codigo_pei1
                ? item.codigo_pei1
                : "";
            this.oFormularioCuatro.objetivo_estrategico =
                item.objetivo_estrategico1 ? item.objetivo_estrategico1 : "";
            this.oFormularioCuatro.codigo_pei2 = item.codigo_pei2
                ? item.codigo_pei2
                : "";
            this.oFormularioCuatro.objetivo_estrategico2 =
                item.objetivo_estrategico2 ? item.objetivo_estrategico2 : "";
            this.oFormularioCuatro.codigo_pei3 = item.codigo_pei3
                ? item.codigo_pei3
                : "";
            this.oFormularioCuatro.objetivo_estrategico3 =
                item.objetivo_estrategico3 ? item.objetivo_estrategico3 : "";

            this.oFormularioCuatro.resultado_institucional =
                item.resultado_institucional
                    ? item.resultado_institucional
                    : "";
            this.oFormularioCuatro.indicador = item.indicador
                ? item.indicador
                : "";

            this.oFormularioCuatro.codigo_poa = item.codigo_poa1
                ? item.codigo_poa1
                : "";
            this.oFormularioCuatro.accion_corto = item.accion_corto1
                ? item.accion_corto1
                : "";
            this.oFormularioCuatro.codigo_poa2 = item.codigo_poa2
                ? item.codigo_poa2
                : "";
            this.oFormularioCuatro.accion_corto2 = item.accion_corto2
                ? item.accion_corto2
                : "";
            this.oFormularioCuatro.codigo_poa3 = item.codigo_poa3
                ? item.codigo_poa3
                : "";
            this.oFormularioCuatro.accion_corto3 = item.accion_corto3
                ? item.accion_corto3
                : "";

            this.oFormularioCuatro.indicador_proceso = item.indicador_proceso
                ? item.indicador_proceso
                : "";
            this.oFormularioCuatro.linea_base = item.linea_base
                ? item.linea_base
                : "";
            this.oFormularioCuatro.meta = item.meta ? item.meta : "";
            this.oFormularioCuatro.presupuesto = item.presupuesto
                ? item.presupuesto
                : "";
            this.oFormularioCuatro.ponderacion = item.ponderacion
                ? item.ponderacion
                : "";
            this.oFormularioCuatro.unidad_id = item.unidad_id
                ? item.unidad_id
                : "";
            this.modal_accion = "edit";
            this.muestra_modal = true;
        },

        // Listar FormularioCuatros
        getFormularioCuatros() {
            this.showOverlay = true;
            this.muestra_modal = false;
            let url = "/admin/formulario_cuatro";
            if (this.pagina != 0) {
                url += "?page=" + this.pagina;
            }
            axios
                .get(url, {
                    params: { per_page: this.per_page },
                })
                .then((res) => {
                    this.showOverlay = false;
                    this.listRegistros = res.data.listado;
                    this.totalRows = res.data.total;
                });
        },
        eliminaFormularioCuatro(id, descripcion) {
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
                        .post("/admin/formulario_cuatro/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            if (res.data.sw) {
                                this.getFormularioCuatros();
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
        abreModal(tipo_accion = "nuevo", formulario_cuatro = null) {
            this.muestra_modal = true;
            this.modal_accion = tipo_accion;
            if (formulario_cuatro) {
                this.oFormularioCuatro = formulario_cuatro;
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        limpiaFormularioCuatro() {
            this.oFormularioCuatro.codigo_pei = "";
            this.oFormularioCuatro.objetivo_estrategico = "";
            this.oFormularioCuatro.codigo_pei2 = "";
            this.oFormularioCuatro.objetivo_estrategico2 = "";
            this.oFormularioCuatro.codigo_pei3 = "";
            this.oFormularioCuatro.objetivo_estrategico3 = "";
            this.oFormularioCuatro.resultado_institucional = "";
            this.oFormularioCuatro.indicador = "";
            this.oFormularioCuatro.codigo_poa = "";
            this.oFormularioCuatro.accion_corto = "";
            this.oFormularioCuatro.codigo_poa2 = "";
            this.oFormularioCuatro.accion_corto2 = "";
            this.oFormularioCuatro.codigo_poa3 = "";
            this.oFormularioCuatro.accion_corto3 = "";
            this.oFormularioCuatro.indicador_proceso = "";
            this.oFormularioCuatro.linea_base = "";
            this.oFormularioCuatro.meta = "";
            this.oFormularioCuatro.presupuesto = "";
            this.oFormularioCuatro.ponderacion = "";
            this.oFormularioCuatro.unidad_id = "";
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
                    "/admin/reportes/formulario_cuatro_excel",
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
                        // excel
                        var fileURL = window.URL.createObjectURL(
                            new Blob([response.data])
                        );
                        var fileLink = document.createElement("a");
                        fileLink.href = fileURL;
                        fileLink.setAttribute(
                            "download",
                            "formulario_cuatro.xlsx"
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
        ingresarEnter(valor) {
            return valor.replace(",", "<br/>");
        },
        obtienePermisos() {
            axios
                .get("/admin/usuarios/getPermisos/" + this.user.id)
                .then((res) => {
                    localStorage.setItem("permisos", JSON.stringify(res.data));
                    this.permisos = localStorage.getItem("permisos");
                });
        },
        muestraConfiguracionModulos() {
            this.muestra_configuracion_modulo = true;
        },
        muestraAprobarFormularios() {
            this.muestra_aprobar_formularios = true;
        },
    },
};
</script>

<style></style>
