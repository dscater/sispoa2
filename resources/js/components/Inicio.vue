<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row" v-if="configuracion">
                    <div class="col-md-12">
                        <div class="card border border-primary">
                            <div class="card-body">
                                <h2
                                    style="
                                        font-weight: bold;
                                        text-align: center;
                                    "
                                >
                                    SISTEMA {{ configuracion.nombre_sistema }}
                                </h2>
                                <h3 style="text-align: center">
                                    ¡BIENVENIDO {{ user.full_name }}!
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                    <div
                        class="col-12 col-sm-6 col-md-3"
                        v-for="(item, index) in listInfoBox"
                        :key="index"
                    >
                        <div class="info-box">
                            <span
                                class="info-box-icon elevation-1"
                                :class="item.color"
                                ><i :class="item.icon"></i
                            ></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{
                                    item.label
                                }}</span>
                                <span class="info-box-number">{{
                                    item.cantidad
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border border-primary">
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
                        <div class="card border border-primary">
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
                        <div class="card border border-primary">
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
            <!-- /.container-fluid -->
        </section>
        <audio id="audio" controls style="display: none">
            <source type="audio/wav" src="/audio/bienvenido.mp3" />
        </audio>
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
            usuarios: 10,
            configuracion: JSON.parse(localStorage.getItem("configuracion")),
            user: JSON.parse(localStorage.getItem("user")),
            listInfoBox: [],
            htmlMision: "",
            htmlVision: "",
            htmlObjetivos: "",
        };
    },
    mounted() {
        this.loadingWindow.close();
        this.mensajeBienvenida();
        this.getInfoBox();
        this.getMision();
        this.getVision();
        this.getObjetivos();
    },
    methods: {
        getInfoBox() {
            axios.get("/admin/usuarios/getInfoBox").then((res) => {
                this.listInfoBox = res.data;
            });
        },
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
        mensajeBienvenida() {
            let reproduce = localStorage.getItem("reproducir_audio");
            var audio = document.getElementById("audio");
            if (reproduce == "si") {
                audio.play();
                localStorage.setItem("reproducir_audio", "no");
            }
        },
    },
};
</script>

<style></style>
