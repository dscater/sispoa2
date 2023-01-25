<template>
    <div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-lightblue">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a
                        href="#"
                        class="nav-link text-white"
                        data-widget="pushmenu"
                        role="button"
                        ><i class="fas fa-bars"></i
                    ></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <router-link :to="{ name: 'inicio' }" class="nav-link text-white"
                        >Inicio</router-link
                    >
                </li>
                <li
                    class="nav-item d-none d-sm-inline-block"
                    v-if="permisos.includes('tickets.create')"
                >
                    <router-link
                        exact
                        :to="{ name: 'tickets.create' }"
                        class="nav-link text-white"
                        >Nuevo ticket</router-link
                    >
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a
                        class="nav-link text-white"
                        href="#"
                        role="button"
                        @click.prevent="logout()"
                        v-loading.fullscreen.lock="fullscreenLoading"
                    >
                        <i class="fas fa-power-off"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link text-white"
                        data-widget="fullscreen"
                        href="#"
                        role="button"
                    >
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a
                        class="nav-link"
                        data-widget="control-sidebar"
                        data-slide="true"
                        href="#"
                        role="button"
                    >
                        <i class="fas fa-th-large"></i>
                    </a>
                </li> -->
            </ul>
        </nav>
        <!-- /.navbar -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            fullscreenLoading: false,
            permisos: localStorage.getItem("permisos"),
        };
    },
    mounted() {
        if (!this.permisos) {
            this.$router.push({ name: "login" });
        }
    },
    methods: {
        logout() {
            this.fullscreenLoading = true;
            axios.post("/logout").then((res) => {
                setTimeout(function () {
                    localStorage.clear();
                    // this.$router.push({ name: "login" });
                    window.location = "/";
                }, 500);
            });
        },
    },
};
</script>

<style></style>
