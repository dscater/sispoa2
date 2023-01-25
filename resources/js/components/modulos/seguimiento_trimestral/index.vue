<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Seguimiento Trimestral</h1>
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
                                    <div class="form-group col-md-12">
                                        <label>Seleccionar código PEI</label>
                                        <el-select
                                            class="w-full"
                                            v-model="detalle_formulario_id"
                                            filterable
                                            @change="cargaTabla"
                                        >
                                            <el-option
                                                v-for="item in listDetalles"
                                                :key="item.id"
                                                :value="item.id"
                                                :label="
                                                    ingresarEnter(
                                                        item.formulario.codigo_pei
                                                    )
                                                "
                                            >
                                            </el-option>
                                        </el-select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body overflow-auto">
                                <div class="row">
                                    <div
                                        class="col-md-12"
                                        v-html="htmlTabla"
                                    ></div>
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
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            detalle_formulario_id: "",
            listDetalles: [],
            htmlTabla: "",
        };
    },
    mounted() {
        this.loadingWindow.close();
        this.getDetalleFormularios();
    },
    methods: {
        // Listar DetalleFormularios
        getDetalleFormularios() {
            this.showOverlay = true;
            this.muestra_modal = false;
            let url = "/admin/detalle_formularios";
            axios.get(url).then((res) => {
                this.listDetalles = res.data.detalle_formularios;
            });
        },
        cargaTabla() {
            if (this.detalle_formulario_id != "") {
                axios
                    .get(
                        "/admin/detalle_formularios/seguimiento_trimestral/" +
                            this.detalle_formulario_id
                    )
                    .then((response) => {
                        this.htmlTabla = response.data;
                    });
            }
        },
        ingresarEnter(valor) {
            return valor.replace(",", " | ");
        },
    },
};
</script>
<style></style>
