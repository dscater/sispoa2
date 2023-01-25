<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>PEI (Misión, Visión, Objetivos Estratégico)</h1>
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
                                <h4>Misión</h4>
                            </div>
                            <div class="card-body overflow-auto">
                                <div class="row">
                                    <div
                                        class="col-md-12"
                                        v-html="htmlMision"
                                    ></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Visión</h4>
                            </div>
                            <div class="card-body overflow-auto">
                                <div class="row">
                                    <div
                                        class="col-md-12"
                                        v-html="htmlVision"
                                    ></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Objetivos estratégicos</h4>
                            </div>
                            <div class="card-body overflow-auto">
                                <div class="row">
                                    <div
                                        class="col-md-12"
                                        v-html="htmlObjetivos"
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
            htmlMision: "",
            htmlVision: "",
            htmlObjetivos: "",
        };
    },
    mounted() {
        this.getMision();
        this.getVision();
        this.getObjetivos();
    },
    methods: {
        getMision() {
            axios.get("/pei/mision").then((response) => {
                this.htmlMision = response.data;
            });
        },
        getVision() {
            axios.get("/pei/vision").then((response) => {
                this.htmlVision = response.data;
            });
        },
        getObjetivos() {
            axios.get("/pei/objetivos").then((response) => {
                this.htmlObjetivos = response.data;
            });
        },
    },
};
</script>
<style></style>
