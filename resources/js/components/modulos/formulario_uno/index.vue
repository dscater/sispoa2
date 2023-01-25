<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Formulario 1</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body overflow-auto">
                                <div class="row">
                                    <div
                                        class="col-md-12"
                                        v-html="formulario"
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
            permisos: localStorage.getItem("permisos"),
            formulario: "",
        };
    },
    mounted() {
        this.getFormulario();
    },
    methods: {
        getFormulario() {
            axios.get("/admin/formulario_uno").then((response) => {
                this.formulario = response.data;
            });
        },
    },
};
</script>

<style></style>
