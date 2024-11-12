import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

export default new Router({
    routes: [
        // INICIO
        {
            path: "/",
            name: "inicio",
            component: require("./components/Inicio.vue").default,
        },

        // LOGIN
        {
            path: "/login",
            name: "login",
            component: require("./Auth.vue").default,
        },

        // USUARIOS
        {
            path: "/usuarios/perfil/:id",
            name: "usuarios.perfil",
            component: require("./components/modulos/usuarios/perfil.vue")
                .default,
            props: true,
        },
        {
            path: "/usuarios",
            name: "usuarios.index",
            component: require("./components/modulos/usuarios/index.vue")
                .default,
        },

        // Personal
        {
            path: "/personals",
            name: "personals.index",
            component: require("./components/modulos/personals/index.vue")
                .default,
        },

        // PARTIDAS
        {
            path: "/partidas",
            name: "partidas.index",
            component: require("./components/modulos/partidas/index.vue")
                .default,
        },

        // UNIDADES
        {
            path: "/unidads",
            name: "unidads.index",
            component: require("./components/modulos/unidads/index.vue")
                .default,
        },

        // SUBDIRECCIONES
        {
            path: "/subdireccions",
            name: "subdireccions.index",
            component: require("./components/modulos/subdireccions/index.vue")
                .default,
        },

        // FORMULARIO UNO
        {
            path: "/formulario_uno",
            name: "formulario_uno.index",
            component: require("./components/modulos/formulario_uno/index.vue")
                .default,
        },

        // FORMULARIO DOS
        {
            path: "/formulario_dos",
            name: "formulario_dos.index",
            component: require("./components/modulos/formulario_dos/index.vue")
                .default,
        },

        // FORMULARIO TRES
        {
            path: "/formulario_tres",
            name: "formulario_tres.index",
            component: require("./components/modulos/formulario_tres/index.vue")
                .default,
        },

        // FORMULARIO CUATRO
        {
            path: "/formulario_cuatro",
            name: "formulario_cuatro.index",
            component:
                require("./components/modulos/formulario_cuatro/index.vue")
                    .default,
        },

        // DETALLE FORMULARIO CUATRO
        {
            path: "/detalle_formularios",
            name: "detalle_formularios.index",
            component:
                require("./components/modulos/detalle_formularios/index.vue")
                    .default,
        },

        {
            path: "/detalle_formularios/create",
            name: "detalle_formularios.create",
            component:
                require("./components/modulos/detalle_formularios/create.vue")
                    .default,
        },

        {
            path: "/detalle_formularios/edit/:id",
            name: "detalle_formularios.edit",
            props: true,
            component:
                require("./components/modulos/detalle_formularios/edit.vue")
                    .default,
        },
        {
            path: "/detalle_formularios/show/:id",
            name: "detalle_formularios.show",
            props: true,
            component:
                require("./components/modulos/detalle_formularios/show.vue")
                    .default,
        },

        // FORMULARIO CINCO
        {
            path: "/formulario_cinco",
            name: "formulario_cinco.index",
            component:
                require("./components/modulos/formulario_cinco/index.vue")
                    .default,
        },

        {
            path: "/formulario_cinco/show/:id",
            name: "formulario_cinco.show",
            props: true,
            component: require("./components/modulos/formulario_cinco/show.vue")
                .default,
        },
        // MEMORIA DE CALCULOS
        {
            path: "/memoria_calculos",
            name: "memoria_calculos.index",
            component:
                require("./components/modulos/memoria_calculos/index.vue")
                    .default,
        },

        {
            path: "/memoria_calculos/create",
            name: "memoria_calculos.create",
            component:
                require("./components/modulos/memoria_calculos/create.vue")
                    .default,
        },

        {
            path: "/memoria_calculos/edit/:id",
            name: "memoria_calculos.edit",
            props: true,
            component: require("./components/modulos/memoria_calculos/edit.vue")
                .default,
        },

        {
            path: "/memoria_calculos/show/:id",
            name: "memoria_calculos.show",
            props: true,
            component: require("./components/modulos/memoria_calculos/show.vue")
                .default,
        },
        {
            path: "/modificaciones/memoria_calculos",
            name: "memoria_calculos.modificaciones",
            component:
                require("./components/modulos/memoria_calculos/modificaciones.vue")
                    .default,
        },

        // SALDO PRESUPUESTO
        {
            path: "/saldo_presupuesto",
            name: "saldo_presupuesto.index",
            component:
                require("./components/modulos/saldo_presupuesto/index.vue")
                    .default,
        },

        // CERTIFICACIONS
        {
            path: "/certificacions",
            name: "certificacions.index",
            component: require("./components/modulos/certificacions/index.vue")
                .default,
        },

        {
            path: "/certificacions/create",
            name: "certificacions.create",
            component: require("./components/modulos/certificacions/create.vue")
                .default,
        },

        {
            path: "/certificacions/edit/:id",
            name: "certificacions.edit",
            props: true,
            component: require("./components/modulos/certificacions/edit.vue")
                .default,
        },

        // PEI
        {
            path: "/pei",
            name: "pei.index",
            component: require("./components/modulos/pei/index.vue").default,
        },

        // SEGUIMIENTO TRIMESTRAL
        {
            path: "/seguimiento_trimestral",
            name: "seguimiento_trimestral.index",
            component:
                require("./components/modulos/seguimiento_trimestral/index.vue")
                    .default,
        },

        // ACTIVIDAD REALIZADA
        {
            path: "/actividad_realizadas",
            name: "actividad_realizadas.index",
            component:
                require("./components/modulos/actividad_realizadas/index.vue")
                    .default,
        },

        // FISICOS
        {
            path: "/fisicos",
            name: "fisicos.index",
            component: require("./components/modulos/fisicos/index.vue")
                .default,
        },
        {
            path: "/fisicos/:id",
            name: "fisicos.show",
            props: true,
            component: require("./components/modulos/fisicos/show.vue").default,
        },

        // FINANCIERAS
        {
            path: "/financieras",
            name: "financieras.index",
            component: require("./components/modulos/financieras/index.vue")
                .default,
        },

        // SEMAFOROS
        {
            path: "/semaforos",
            name: "semaforos.index",
            component: require("./components/modulos/semaforos/index.vue")
                .default,
        },
        {
            path: "/semaforos/:id",
            name: "semaforos.edit",
            props: true,
            component: require("./components/modulos/semaforos/edit.vue")
                .default,
        },
        {
            path: "/semaforos/ejecucion_fisica/actividades",
            name: "semaforos.resumen",
            component: require("./components/modulos/semaforos/Resumen.vue")
                .default,
        },
        {
            path: "/semaforos/ejecucion_fisica/actividades/:id",
            name: "semaforos.resumen_tabla",
            component: require("./components/modulos/semaforos/Fisico.vue")
                .default,
            props: true,
        },

        // VERIFICACION ACTIVIDAD
        {
            path: "/verificacion_actividads",
            name: "verificacion_actividads.index",
            component:
                require("./components/modulos/verificacion_actividads/index.vue")
                    .default,
            props: true,
        },

        // INFORME ACTIVIDAD
        {
            path: "/informe_actividad",
            name: "informe_actividad.index",
            component:
                require("./components/modulos/informe_actividad/index.vue")
                    .default,
            props: true,
        },

        // CONFIGURACIÓN
        {
            path: "/configuracion",
            name: "configuracion",
            component: require("./components/modulos/configuracion/index.vue")
                .default,
            props: true,
        },

        // REPORTES
        {
            path: "/reportes/usuarios",
            name: "reportes.usuarios",
            component: require("./components/modulos/reportes/usuarios.vue")
                .default,
            props: true,
        },
        {
            path: "/reportes/ejecucion_presupuestos",
            name: "reportes.ejecucion_presupuestos",
            component:
                require("./components/modulos/reportes/ejecucion_presupuestos.vue")
                    .default,
            props: true,
        },
        {
            path: "/reportes/ejecucion_presupuestos_g",
            name: "reportes.ejecucion_presupuestos_g",
            component:
                require("./components/modulos/reportes/ejecucion_presupuestos_g.vue")
                    .default,
            props: true,
        },
        {
            path: "/reportes/lista_certificacion",
            name: "reportes.lista_certificacion",
            component:
                require("./components/modulos/reportes/lista_certificacion.vue")
                    .default,
            props: true,
        },
        {
            path: "/reportes/formulario_cuatro",
            name: "reportes.formulario_cuatro",
            component:
                require("./components/modulos/reportes/formulario_cuatro.vue")
                    .default,
            props: true,
        },
        {
            path: "/reportes/formulario_cinco",
            name: "reportes.formulario_cinco",
            component:
                require("./components/modulos/reportes/formulario_cinco.vue")
                    .default,
            props: true,
        },
        {
            path: "/reportes/memoria_calculos",
            name: "reportes.memoria_calculos",
            component:
                require("./components/modulos/reportes/memoria_calculos.vue")
                    .default,
            props: true,
        },
        {
            path: "/reportes/saldos_actividad",
            name: "reportes.saldos_actividad",
            component:
                require("./components/modulos/reportes/saldos_actividad.vue")
                    .default,
            props: true,
        },
        {
            path: "/reportes/saldos_partida",
            name: "reportes.saldos_partida",
            component:
                require("./components/modulos/reportes/saldos_partida.vue")
                    .default,
            props: true,
        },
        {
            path: "/reportes/fisicos",
            name: "reportes.fisicos",
            component: require("./components/modulos/reportes/fisicos.vue")
                .default,
            props: true,
        },
        {
            path: "/reportes/financieros",
            name: "reportes.financieros",
            component: require("./components/modulos/reportes/financieros.vue")
                .default,
            props: true,
        },
        {
            path: "/reportes/semaforos",
            name: "reportes.semaforos",
            component: require("./components/modulos/reportes/semaforos.vue")
                .default,
            props: true,
        },

        // PÁGINA NO ENCONTRADA
        {
            path: "*",
            component: require("./components/modulos/errors/404.vue").default,
        },
    ],
    mode: "history",
    linkActiveClass: "active",
});
