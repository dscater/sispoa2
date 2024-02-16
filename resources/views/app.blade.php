@inject('configuracion', 'App\Models\Configuracion')
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $configuracion->first()->alias }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/plantilla.css') }}">
</head>

<body class="sidebar-mini layout-fixed control-sidebar-slide-open layout-navbar-fixed text-sm">
    <div id="app">
        @if (Auth::check())
            <App ruta="{{ route('base_path') }}" configuracion="{{ $configuracion->first() }}"
                user="{{ Auth::user() }}"></App>
        @else
            <Auth ruta="{{ route('base_path') }}" logo="{{ asset('imgs/' . $configuracion->first()->logo) }}"
                empresa="{{ $configuracion->first()->razon_social }}" configuracion="{{ $configuracion->first() }}"
                key_secret="{{ config('app.clave_captcha') }}">
            </Auth>
        @endif
    </div>

    <script>
        const listCodPei = [
            "6.1.1.3.1.1.",
            "6.3.6.2.5.1.",
            "6.7.1.7.1.1.",
            "6.11.7.1.7.1.",
        ];
        const listAmp = {
            "6.1.1.3.1.1.": "Garantizar que las asignaciones familiares lleguen al binomio madre e hijo de forma oportuna y de calidad en el cumplimiento del derecho fundamental a una alimentación saludable de las madres y los niños, mediante la fiscalización, control de la otorgación de las asignaciones familiares y el subsidio prenatal por la vida, Afiliación, Desafiliación y Reafiliación.",
            "6.3.6.2.5.1.": "Mejorar los servicios de salud de la Seguridad Social de Corto Plazo, de 12% al 60% a través de procesos de fiscalización, control, habilitación, acreditación, fortalecimiento de recursos humanos hasta la gestión 2025.",
            "6.7.1.7.1.1.": "Mejorar la eficacia y eficiencia en el manejo administrativo y financiero del 30% al 60% en los Entes Gestores de la Seguridad Social de Corto Plazo hasta 2025.",
            "6.11.7.1.7.1.": "Mejorar la ejecución física y financiera de 60% al 90% a través de la consolidación de la capacidad institucional técnica, tecnológica, financiera y normativa, de la Autoridad de Supervisión de la Seguridad Social de Corto Plazo hasta la gestión 2025.",
        };
        const listIndPei = {
            "6.1.1.3.1.1.": [
                "N° de supervisiones realizadas y a puntos de distribución, SEDEM y supermercados",
                "N° de quejas y denuncias atendidas en asignaciones familiares",
                "N° de normativas elaboradas o ajustadas en Asignaciones Familiares",
                "N° de fiscalizaciones en las asignaciones familiares",
                "N° fiscalizaciones realizadas",
                "N° fiscalizaciones realizadas a las obligaciones de los Empleadores",
                "N° de certificaciones realizadas",
                "N° de Normativas Elaboradas en afiliaciones desafiliación y reafiliacion",
            ],
            "6.3.6.2.5.1.": [
                "N° de Establecimientos Evaluados de la Seguridad Social de corto Plazo",
                "N° de establecimientos de Salud de la Seguridad Social a corto plazo habilitados por la ASUSS",
                "N° de establecimientos de Salud de la Seguridad Social a corto plazo acreditados por la ASUSS",
                "N° de seguimientos realizados a la implementación de planes de medidas correctivas",
                "N° de normativas referentes en materia de la Salud elaborados",
                "N° de Atenciones de quejas y denuncias de las prestaciones de salud",
                "N° de evaluaciones de pertinencia de proyectos",
                "N° de Establecimientos de Salud que Aplican el Modelos",
                "N° de supervisiones a la ejecución de planes de contingencia",
                "N° de evaluaciones de factibilidad",
                "N° de normativas realizadas para cobertura temporal de prestaciones",
                "N° de controles y Fiscalizaciones de cobertura temporal de prestaciones",
                "N° de informes de homologación realizadas",
                "N° de informes en salud realizadas",
                "N° de Auditorías externas realizadas",
                "N° de Auditorías de servicio realizadas",
                "Data Center Implementado",
                "N° de Sistemas Informáticos Administrativo desarrollados e implementados",
                "N° de Sistema de Salud de la SSCP desarrollados e implementados",
                "Nº Entes Gestores que interoperan con el MISAA",
                "Nº Entes Gestores con MISAA (módulos de informática medica) implementados",
                "Nº Entes Gestores implementan la Norma Tecnológica para la Gestión de Sistemas de Información en Establecimientos de Salud de la SSCP",
                "Nº Entes Gestores implementan la Norma de Indicadores en Salud",
                "N° indicadores de Salud en función a los perfiles epidemiológicos de la Seguridad Social de Corto",
                "N° de Establecimientos de Salud de la Seguridad Social de Corto Plazo que Aplican el Modelos",
                "Módulos de Sala Situacional Implementada ASUSS Oficina Nacional",
                "N° de Establecimientos de Salud que implementan la Sala Situacional de Salud de acuerdo a indicadores estandarizados",
                "Nº de boletines publicados desde la implementación del MISAA MISAA",
                "N° de Investigaciones desarrolladas",
                "N° de publicaciones desarrolladas.",
                "N° de Capacitaciones realizadas",
            ],
            "6.7.1.7.1.1.": [
                "N° de Auditorías, especiales, operacionales y financieras realizadas",
                "N° de Supervisiones y/o Relevamientos",
                "N° de Seguimientos a las Recomendaciones",
                "N° de atenciones a Consultas relacionadas a temas de Fiscalización y Control Administrativo",
                "N° de informes realizadas",
                "N° normativas realizadas",
                "N° de guías y reglamentos elaborados",
                "N° de informes de viabilidad económica",
                "N° reglamentos realizados para la inversión",
                "N° de informes técnicos administrativos realizados",
                "N° de informes de acuerdos suscritos",
                "N° de informes de registro de patrimonio",
                "N° de informes de conciliaciones",
                "N° de informes de  Homologación de PEI",
                "N° de informes de  Homologación de POA y PPTO",
                "Nº de informes de Seguimientos y Control a la ejecución del POA de los Entes Gestores elaborados",
                "Nº de informes de agrupación de los presupuestos institucionales elaborados ",
                "Nº de informes evaluación de casos elaborados ",
                "Nº de informes de agrupación de los estados financieros elaborados ",
                "Nº de informes de Análisis Financiero",
                "Nº de informes de Homologación de Estatutos elaborados",
                "Sistema de Información, (POA, planillas de sueldo, Ejecución Presupuestaria) Planificación y seguimiento para la Seguridad Social a Corto Plazo Implementado",
            ],
            "6.11.7.1.7.1.": [
                "N° de acuerdos y/o convenios suscritos",
                "N° de Planes Estratégicos Institucionales elaborados",
                "N° de Planes Estratégicos comunicacionales elaborados",
                "N° de asistencias jurídicas realizadas",
                "N° Acciones realizadas",
                "N° de programas implementadas",
                "N° de Personal Contratado",
                "N° Unidades fortalecidas",
                "N° de rendición de cuentas realizadas"
            ],
        };
        const listCodPoa = {
            "6.1.1.3.1.1.": "1.1.3.1.1.1.",
            "6.3.6.2.5.1.": "3.6.2.5.1.2",
            "6.7.1.7.1.1.": "11.7.1.7.1.3.",
            "6.11.7.1.7.1.": "11.7.1.7.1.4."
        };
        const listAccion = {
            "6.1.1.3.1.1.": "1) Fiscalizar y controlar el correcto cumplimiento de la normativa del subsidio prenatal, lactancia universal por la vida, asignaciones familiares, afiliación, desafiliación y reafiliación.",
            "6.3.6.2.5.1.": "2) Realizar el control de la correcta prestación de los servicios de salud en la Seguridad Social de Corto Plazo, mediante procesos de seguimiento, monitoreo, supervisión y evaluación de la calidad de los servicios, auditorias médicas, calidad de la información, enseñanza e investigación y fortalecimiento de Salud en sus RRHH, infraestructura y equipamiento",
            "6.7.1.7.1.1.": "3) Realizar el Control y Fiscalización Administrativo y Financiero de los Entes Gestores de la Seguridad Social de Corto Plazo, mediante Auditorias Especiales, Operacionales y Financieras, seguimiento, control y homologación los Planes Estratégicos, Programas Operativos Anuales y Presupuesto.",
            "6.11.7.1.7.1.": "Consolidar la Estructura Institucional Técnica, Administrativa, Financiera y Jurídica de la Autoridad de Supervisión de la Seguridad Social de Corto Plazo a nivel Nacional, Regional y Departamental."
        };
    </script>

    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ mix('js/plantilla.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on("click", "aside nav ul li a", function() {
                if ($("body").hasClass("sidebar-open") && !$(this).parent().hasClass("menu")) {
                    $("body").addClass("sidebar-collapse");
                    $("body").addClass("sidebar-close");
                    $("body").removeClass("sidebar-open");
                }

            });
        });
    </script>
</body>

</html>
