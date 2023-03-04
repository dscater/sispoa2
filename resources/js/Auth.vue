<template>
    <div class="login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <img :src="logo" alt="Logo" />
                    <router-link :to="{ name: 'login' }" class="h1 text-primary"
                        ><b v-text="empresa"></b
                    ></router-link>
                </div>
                <div class="card-body">
                    <p class="login-box-msg text-primary font-weight-bold">
                        Ingresa tu usuario y contraseña para inicar sesión
                    </p>

                    <form action="../../index3.html" method="post">
                        <span
                            class="error invalid-feedback d-block"
                            v-if="errors.usuario"
                            v-text="errors.usuario[0]"
                        ></span>
                        <div
                            class="input-group mb-3"
                            :class="{
                                'is-invalid': errors.usuario,
                            }"
                        >
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Usuario"
                                v-model="usuario"
                                @keypress.enter="login()"
                                autofocus
                            />
                            <div class="input-group-append">
                                <div class="input-group-text bg-blue">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>

                        <span
                            class="error invalid-feedback"
                            v-if="errors.password"
                            v-text="errors.password[0]"
                        ></span>
                        <div
                            class="input-group mb-3 contenedor_contrasenia"
                            :class="{
                                'is-invalid': errors.password,
                            }"
                        >
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Contraseña"
                                v-model="password"
                                id="password"
                                @keypress.enter="login()"
                            />
                            <div class="input-group-append">
                                <div
                                    class="input-group-text bg-light ojo_contrasenia"
                                    @click="toggleContrasenia"
                                >
                                    <i
                                        class="fa"
                                        :class="{
                                            'fa-eye-slash': !muestra,
                                            'fa-eye': muestra,
                                        }"
                                    ></i>
                                </div>
                            </div>
                            <div class="input-group-append">
                                <div class="input-group-text bg-blue">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="row mb-2">
                            <div
                                class="col-12 text-center contenedor_captcha"
                                :class="{
                                    'is-invalid': errors.captcha,
                                }"
                            >
                                <vue-recaptcha
                                    :sitekey="key_secret"
                                    ref="recaptcha"
                                    @verify="verificaCaptcha"
                                    @error="errorCaptcha"
                                >
                                </vue-recaptcha>
                                <span
                                    class="error invalid-feedback d-block"
                                    v-if="errors.captcha"
                                    v-text="errors.captcha[0]"
                                ></span>
                            </div>
                        </div> -->
                        <div class="row" v-if="error">
                            <div class="col-12">
                                <div class="callout callout-danger">
                                    <h5>¡Error!</h5>
                                    <p>
                                        El usuario o contraseña son incorrectos
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-12">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-block bg-blue btn-flat font-weight-bold"
                                    @click.prevent="login()"
                                    v-loading.fullscreen.lock="
                                        fullscreenLoading
                                    "
                                >
                                    Acceder
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</template>

<script>
import { VueRecaptcha } from "vue-recaptcha";
export default {
    components: { VueRecaptcha },
    props: {
        key_secret: {
            String,
            default: "key reCaptcha Google",
        },
        empresa: { String, default: "Nombre Empresa" },
        logo: {
            String,
            default:
                "https://www.logodesign.net/logo/eye-and-house-5806ld.png?size=2&industry=All",
        },
        configuracion: {
            String,
            default: "",
        },
    },
    data() {
        return {
            usuario: "",
            password: "",
            error: false,
            fullscreenLoading: false,
            muestra: false,
            captcha: null,
            errors: [],
        };
    },
    methods: {
        errorCaptcha() {},
        verificaCaptcha(datos) {
            let config = {
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
            };
            let formdata = new FormData();
            formdata.append("secret", this.key_secret);
            formdata.append("g-recaptcha-response", datos);
            axios
                .post("/verifica_captcha", formdata, config)
                .then((response) => {
                    this.captcha = response.data.success;
                });
        },
        login(res_captcha) {
            this.fullscreenLoading = true;
            let datos = { usuario: this.usuario, password: this.password };
            if (this.captcha) {
                datos["captcha"] = this.captcha;
            }
            axios
                .post("/login", datos)
                .then((res) => {
                    let user = res.data.user;
                    setTimeout(() => {
                        this.obtienePermisos(user);
                    }, 50);
                })
                .catch((error) => {
                    this.fullscreenLoading = false;
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                            console.log("asdasd");
                        } else {
                            this.error = true;
                            this.password = "";
                        }
                    }
                });
        },
        obtienePermisos(user) {
            axios.get("/admin/usuarios/getPermisos/" + user.id).then((res) => {
                this.error = false;
                this.$router.push({ name: "inicio" });
                localStorage.setItem("configuracion", this.configuracion);
                localStorage.setItem("permisos", JSON.stringify(res.data));
                localStorage.setItem("user", JSON.stringify(user));

                localStorage.setItem("reproducir_audio", "si");
                location.reload();
            });
        },
        toggleContrasenia() {
            this.muestra = !this.muestra;
            if (this.muestra) {
                $("#password").prop("type", "text");
            } else {
                $("#password").prop("type", "password");
            }
        },
    },
};
</script>

<style>
.login-page {
    /* background: var(--principal); */
}

.card {
    border-radius: 0px;
}
.login-page .card-header {
    border-bottom: solid 1px var(--principal);
}

.contenedor_contrasenia {
    position: relative;
}

.ojo_contrasenia {
    cursor: pointer;
}

.contenedor_captcha > div > div {
    width: 100%!important;
}
</style>
