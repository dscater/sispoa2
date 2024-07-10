<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <router-link
            exact
            :to="{ name: 'inicio' }"
            class="brand-link bg-lightblue"
        >
            <img
                :src="configuracion.path_image"
                alt="Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: 0.8"
            />
            <span
                class="brand-text font-weight-light"
                v-text="configuracion.alias"
            ></span>
        </router-link>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        :src="user_sidebar.path_image"
                        class="img-circle elevation-2"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <router-link
                        exact
                        :to="{
                            name: 'usuarios.perfil',
                            params: { id: user_sidebar.id },
                        }"
                        class="d-block"
                        v-text="user_sidebar.full_name"
                    ></router-link>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input
                        class="form-control form-control-sidebar bg-white"
                        type="search"
                        placeholder="Buscar Modulo"
                        aria-label="Search"
                    />
                    <div class="input-group-append">
                        <button class="btn btn-sidebar bg-white">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column text-xs nav-flat"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false"
                >
                    <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <router-link
                            exact
                            :to="{ name: 'inicio' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-home"></i>
                            <p>Inicio</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-header bg-navy"
                        v-if="
                            permisos.includes('unidads.index') ||
                            permisos.includes('partidas.index') ||
                            permisos.includes('formulario_uno.index') ||
                            permisos.includes('formulario_dos.index') ||
                            permisos.includes('formulario_tres.index') ||
                            permisos.includes('formulario_cuatro.index') ||
                            permisos.includes('formulario_cinco.index') ||
                            permisos.includes('memoria_calculos.index')
                        "
                    >
                        FORMULACIÓN
                    </li>
                    <li class="nav-item" v-if="permisos.includes('pei.index')">
                        <router-link
                            exact
                            :to="{ name: 'pei.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>PEI (Misión, Visión, Objetivos Estratégico)</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('unidads.index')"
                    >
                        <router-link
                            :to="{ name: 'unidads.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Unidades Organizacionales</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('subdireccions.index')"
                    >
                        <router-link
                            :to="{ name: 'subdireccions.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Subunidades</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('partidas.index')"
                    >
                        <router-link
                            :to="{ name: 'partidas.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Partidas</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('formulario_uno.index')"
                    >
                        <router-link
                            :to="{ name: 'formulario_uno.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Formulario 1</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('formulario_dos.index')"
                    >
                        <router-link
                            :to="{ name: 'formulario_dos.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Formulario 2</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('formulario_tres.index')"
                    >
                        <router-link
                            :to="{ name: 'formulario_tres.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Formulario 3</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item menu"
                        :class="{
                            'menu-open':
                                $route.name == 'formulario_cuatro.index' ||
                                $route.name == 'detalle_formularios.index',
                        }"
                        v-if="
                            permisos.includes('formulario_cuatro.index') ||
                            permisos.includes('detalle_formularios.index')
                        "
                    >
                        <a
                            href="#"
                            class="nav-link"
                            :class="{
                                active:
                                    $route.name == 'formulario_cuatro.index' ||
                                    $route.name == 'detalle_formularios.index',
                            }"
                        >
                            <i class="nav-icon fa fa-file-contract"></i>
                            <p>
                                Formulario 4
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li
                                class="nav-item"
                                v-if="
                                    permisos.includes('formulario_cuatro.index')
                                "
                            >
                                <router-link
                                    :to="{ name: 'formulario_cuatro.index' }"
                                    v-loading.fullscreen.lock="
                                        fullscreenLoading
                                    "
                                    class="nav-link"
                                >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>Administrar registros</p>
                                </router-link>
                            </li>
                            <li
                                class="nav-item"
                                v-if="
                                    permisos.includes(
                                        'detalle_formularios.index'
                                    )
                                "
                            >
                                <router-link
                                    :to="{ name: 'detalle_formularios.index' }"
                                    v-loading.fullscreen.lock="
                                        fullscreenLoading
                                    "
                                    class="nav-link"
                                >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>Administrar detalles</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('formulario_cinco.index')"
                    >
                        <router-link
                            :to="{ name: 'formulario_cinco.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fa fa-file-contract"></i>
                            <p>Formulario 5</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('memoria_calculos.index')"
                    >
                        <router-link
                            :to="{ name: 'memoria_calculos.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Memoria de cálculo</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-header bg-navy"
                        v-if="
                            permisos.includes(
                                'verificacion_actividads.index'
                            ) ||
                            permisos.includes('certificacions.index') ||
                            permisos.includes('seguimiento_trimestral.index') ||
                            permisos.includes('actividad_realizadas.index')
                        "
                    >
                        SEGUIMIENTO
                    </li>
                    <li
                        class="nav-item"
                        v-if="
                            permisos.includes('verificacion_actividads.index')
                        "
                    >
                        <router-link
                            :to="{ name: 'verificacion_actividads.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Verificación de la actividad POA</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('certificacions.index')"
                    >
                        <router-link
                            :to="{ name: 'certificacions.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fa fa-file-alt"></i>
                            <p>Certificación POA</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('seguimiento_trimestral.index')"
                    >
                        <router-link
                            :to="{ name: 'seguimiento_trimestral.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fa fa-file-alt"></i>
                            <p>Ejecución Físico</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="
                            permisos.includes('reportes.ejecucion_presupuestos')
                        "
                    >
                        <router-link
                            :to="{ name: 'reportes.ejecucion_presupuestos' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Ejecución de financiera</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="
                            permisos.includes(
                                'reportes.ejecucion_presupuestos_g'
                            )
                        "
                    >
                        <router-link
                            :to="{ name: 'reportes.ejecucion_presupuestos_g' }"
                            class="nav-link"
                        >
                            <i class="fas fa-chart-bar nav-icon"></i>
                            <p>G. Ejecución de financiera</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('actividad_realizadas.index')"
                    >
                        <router-link
                            :to="{ name: 'actividad_realizadas.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fa fa-file-alt"></i>
                            <p>Informe de actividad realizada</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-header bg-navy"
                        v-if="
                            (user_sidebar.tipo != 'JEFES DE UNIDAD' &&
                                user_sidebar.tipo != 'DIRECTORES' &&
                                user_sidebar.tipo != 'JEFES DE ÁREAS' &&
                                permisos.includes('memoria_calculos.edit')) ||
                            permisos.includes('saldo_presupuesto.index')
                        "
                    >
                        MODIFICACIÓN POA
                    </li>
                    <li
                        class="nav-item"
                        v-if="
                            permisos.includes('memoria_calculos.edit') &&
                            user_sidebar.tipo != 'JEFES DE UNIDAD' &&
                            user_sidebar.tipo != 'DIRECTORES' &&
                            user_sidebar.tipo != 'JEFES DE ÁREAS'
                        "
                    >
                        <router-link
                            :to="{ name: 'memoria_calculos.modificaciones' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fa fa-file-alt"></i>
                            <p>Modificación de memorias de cálculo</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('saldo_presupuesto.index')"
                    >
                        <router-link
                            :to="{ name: 'saldo_presupuesto.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fa fa-file-alt"></i>
                            <p>Saldo presupuesto</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-header bg-navy"
                        v-if="
                            permisos.includes('reportes.formulario_cuatro') ||
                            permisos.includes('reportes.formulario_cinco') ||
                            permisos.includes('reportes.memoria_calculos') ||
                            permisos.includes('reportes.saldos_actividad') ||
                            permisos.includes('reportes.saldos_partida') ||
                            permisos.includes(
                                'reportes.ejecucion_presupuestos'
                            ) ||
                            permisos.includes(
                                'reportes.ejecucion_presupuestos_g'
                            ) ||
                            permisos.includes('reportes.fisicos') ||
                            permisos.includes('reportes.financieros') ||
                            permisos.includes('reportes.semaforos')
                        "
                    >
                        REPORTES
                    </li>
                    <!-- <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.usuarios')"
                    >
                        <router-link
                            :to="{ name: 'reportes.usuarios' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Lista de Usuarios</p>
                        </router-link>
                    </li> -->
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.formulario_cuatro')"
                    >
                        <router-link
                            :to="{ name: 'reportes.formulario_cuatro' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Formulario 4</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.formulario_cinco')"
                    >
                        <router-link
                            :to="{ name: 'reportes.formulario_cinco' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Formulario 5</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.memoria_calculos')"
                    >
                        <router-link
                            :to="{ name: 'reportes.memoria_calculos' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Memorias de cálculo</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.saldos_actividad')"
                    >
                        <router-link
                            :to="{ name: 'reportes.saldos_actividad' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Saldos de presupuestos por actividad</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.saldos_partida')"
                    >
                        <router-link
                            :to="{ name: 'reportes.saldos_partida' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Saldos por partida</p>
                        </router-link>
                    </li>
                    <!-- <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.fisicos')"
                    >
                        <router-link
                            :to="{ name: 'reportes.fisicos' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Físicos</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.financieros')"
                    >
                        <router-link
                            :to="{ name: 'reportes.financieros' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Financieros</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('reportes.semaforos')"
                    >
                        <router-link
                            :to="{ name: 'reportes.semaforos' }"
                            class="nav-link"
                        >
                            <i class="fas fa-file-pdf nav-icon"></i>
                            <p>Semáforos</p>
                        </router-link>
                    </li> -->
                    <li class="nav-header bg-navy">OTRAS OPCIONES</li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('fisicos.index')"
                    >
                        <router-link
                            :to="{ name: 'fisicos.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Físico</p>
                        </router-link>
                    </li>
                    <!-- <li
                        class="nav-item"
                        v-if="permisos.includes('financieras.index')"
                    >
                        <router-link
                            :to="{ name: 'financieras.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Financiero</p>
                        </router-link>
                    </li> -->
                    <li
                        class="nav-item"
                        v-if="permisos.includes('semaforos.index')"
                    >
                        <router-link
                            :to="{ name: 'semaforos.index' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Semáforo</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('configuracion.index')"
                    >
                        <router-link
                            :to="{ name: 'configuracion' }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Configuración</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('personals.index')"
                    >
                        <router-link
                            exact
                            :to="{ name: 'personals.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-users"></i>
                            <p>Personal</p>
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('usuarios.index')"
                    >
                        <router-link
                            exact
                            :to="{ name: 'usuarios.index' }"
                            class="nav-link"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            exact
                            :to="{
                                name: 'usuarios.perfil',
                                params: { id: user_sidebar.id },
                            }"
                            class="nav-link"
                        >
                            <i class="nav-icon fas fa-user"></i>
                            <p>Perfil</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <a
                            href="#"
                            class="nav-link"
                            @click.prevent="logout()"
                            v-loading.fullscreen.lock="fullscreenLoading"
                        >
                            <i class="fas fa-power-off nav-icon"></i>
                            <p>Salir</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script>
export default {
    props: ["user_sidebar", "configuracion"],
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            fullscreenLoading: false,
            permisos: localStorage.getItem("permisos"),
        };
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

<style>
.user-panel .info {
    display: flex;
    height: 100%;
    align-items: center;
}
.user-panel .info a {
    font-size: 0.8em;
}
</style>
