<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Formulario 2</h1>
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
            axios.get("/admin/formulario_dos").then((response) => {
                this.formulario = response.data;
            });
        },
    },
};
</script>

<style>
.formulario {
    font-size: 0.8em;
}
.subtitulo {
    font-weight: bold;
}

.alineado {
    vertical-align: middle !important;
}
.bg_turqueza {
    background: #33cccc;
}
</style>
