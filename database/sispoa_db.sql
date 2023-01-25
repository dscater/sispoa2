-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 14-01-2023 a las 18:39:57
-- Versión del servidor: 5.7.33
-- Versión de PHP: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sispoa_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_realizadas`
--

CREATE TABLE `actividad_realizadas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `actividad_realizadas`
--

INSERT INTO `actividad_realizadas` (`id`, `descripcion`, `archivo`, `estado`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'ACTIVIDAD REALIZADA 1', '1667660327_actividad_realizada1.pdf', '', '2022-11-05', '2022-11-05 14:58:47', '2022-11-05 19:03:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificacions`
--

CREATE TABLE `certificacions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `formulario_id` bigint(20) UNSIGNED NOT NULL,
  `mo_id` bigint(20) UNSIGNED NOT NULL,
  `mod_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad_usar` double NOT NULL,
  `presupuesto_usarse` decimal(24,2) NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correlativo` bigint(20) NOT NULL,
  `solicitante_id` bigint(20) UNSIGNED NOT NULL,
  `superior_id` bigint(20) UNSIGNED NOT NULL,
  `inicio` date NOT NULL,
  `final` date NOT NULL,
  `personal_designado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departamento` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `municipio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anulado` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `certificacions`
--

INSERT INTO `certificacions` (`id`, `formulario_id`, `mo_id`, `mod_id`, `cantidad_usar`, `presupuesto_usarse`, `archivo`, `correlativo`, `solicitante_id`, `superior_id`, `inicio`, `final`, `personal_designado`, `departamento`, `municipio`, `estado`, `anulado`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 9, 2, '60.00', NULL, 1, 2, 3, '2022-02-01', '2022-12-01', 'JOSE PAREDES', 'LA PAZ', 'LA PAZ', 'PENDIENTE', 0, '2022-12-13', '2022-12-13 21:57:46', '2022-12-23 15:08:15'),
(2, 2, 11, 2, 28, '1120.00', NULL, 2, 5, 4, '2022-04-04', '2022-12-12', 'CARLOS SANCHEZ', 'LA PAZ', 'LA PAZ', 'PENDIENTE', 0, '2022-12-13', '2022-12-13 22:12:46', '2022-12-13 22:28:05'),
(3, 4, 14, 11, 3, '120.00', NULL, 3, 6, 4, '2022-04-04', '2022-12-30', 'JUAN PERES', '', '', 'PENDIENTE', 0, '2022-12-23', '2022-12-23 14:56:35', '2022-12-23 14:56:44'),
(4, 5, 15, 12, 30, '1200.00', NULL, 4, 5, 4, '2023-03-03', '2023-06-06', 'JUAN PERES', '', '', 'PENDIENTE', 0, '2023-01-06', '2023-01-06 15:46:42', '2023-01-06 15:46:42'),
(5, 1, 1, 6, 3, '60.00', NULL, 5, 10, 5, '2023-02-03', '2023-12-12', 'JOSE PAREDES', '', '', 'PENDIENTE', 0, '2023-01-14', '2023-01-14 16:53:28', '2023-01-14 16:53:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `ciudad`, `dir`, `fono`, `web`, `actividad`, `correo`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'SISTEMA DE PROGRAMACIÓN OPERATIVA ANUAL', 'SISPOA', 'RAZON SOCIAL DE PRUEBA', 'LA PAZ', 'LOS OLIVOS', '222222', '', 'ACTIVIDAD', '', '1665947357_logo.png', NULL, '2022-11-05 01:54:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_modulos`
--

CREATE TABLE `configuracion_modulos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `modulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editar` int(11) NOT NULL,
  `eliminar` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion_modulos`
--

INSERT INTO `configuracion_modulos` (`id`, `modulo`, `editar`, `eliminar`, `created_at`, `updated_at`) VALUES
(1, 'FORMULARIO 4', 1, 0, NULL, '2023-01-14 18:38:48'),
(2, 'DETALLE FORMULARIO 4', 0, 0, NULL, '2023-01-14 18:37:47'),
(3, 'MEMORIA DE CÁLCULO', 1, 1, NULL, '2023-01-14 18:38:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_formularios`
--

CREATE TABLE `detalle_formularios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `formulario_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_formularios`
--

INSERT INTO `detalle_formularios` (`id`, `formulario_id`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-10-17', '2022-10-17 19:31:22', '2022-10-17 19:31:22'),
(2, 2, '2022-10-17', '2022-10-17 22:06:22', '2022-10-17 22:06:22'),
(3, 3, '2022-10-20', '2022-10-20 14:28:57', '2022-10-20 14:28:57'),
(8, 4, '2022-12-23', '2022-12-23 14:47:53', '2022-12-23 14:47:53'),
(9, 5, '2023-01-06', '2023-01-06 15:36:58', '2023-01-06 15:36:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_operacions`
--

CREATE TABLE `detalle_operacions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operacion_id` bigint(20) UNSIGNED NOT NULL,
  `ponderacion` double(8,2) NOT NULL,
  `resultado_esperado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `medios_verificacion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_tarea` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actividad_tarea` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_e` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_f` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_m` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_a` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_m` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_j` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tt_j` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tt_a` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tt_s` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ct_o` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ct_n` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ct_d` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inicio` date NOT NULL,
  `final` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_operacions`
--

INSERT INTO `detalle_operacions` (`id`, `operacion_id`, `ponderacion`, `resultado_esperado`, `medios_verificacion`, `codigo_tarea`, `actividad_tarea`, `pt_e`, `pt_f`, `pt_m`, `st_a`, `st_m`, `st_j`, `tt_j`, `tt_a`, `tt_s`, `ct_o`, `ct_n`, `ct_d`, `inicio`, `final`, `created_at`, `updated_at`) VALUES
(1, 1, 10.00, 'PLAN ESTRATEGICO COMUNICACIONAL 2022', 'INFORME', '4.1.1', 'ELABORACIÓN DE PLAN ESTRATEGICO COMUNICACIONAL 2022', '1', '', '', '', '', '', '', '', '', '', '', '', '2022-01-03', '2022-03-31', '2022-10-17 19:31:22', '2022-10-17 19:31:22'),
(2, 1, 20.00, 'POBLACION BOLIVIANA CONOCE LAS ACCIONES Y RESULTADOSDE LA ASUSS', 'INFORMES', '4.1.2', 'COBERTURA DE PRENSA PARA LA GESTIÓN DE INFORMACIÓN', '', '', '1', '', '', '1', '', '', '1', '', '', '1', '2022-01-03', '2022-12-31', '2022-10-17 19:31:22', '2022-10-17 19:31:22'),
(3, 1, 20.00, 'LA POBLACION BOLIVIANA CONOCE LAS ACCIONES Y LOS RESULTADOS DE LA ASUSS', 'INFORME', '4.1.3.', 'REALIZAR LAS GESTIONES PARA LA DIFUSION EN MEDIOS DE COMUNICACIÓN TRADICIONALES', '', '', '', '', '', '', '', '', '', '', '', '1', '2022-03-01', '2022-12-15', '2022-10-17 19:31:22', '2022-10-17 19:31:22'),
(4, 1, 10.00, 'VISIBILIZAR LA IMAGEN INSTITUCIONAL DE LA ASUSS', 'INFORME', '4.1.4', 'ADMINISTRACION DE REDES SOCIALES DE LA ASUSS', '', '', '', '', '', '', '', '', '', '', '', '', '2022-01-03', '2022-12-31', '2022-10-17 19:31:22', '2022-10-17 19:31:22'),
(5, 1, 10.00, 'LA POBLACION BOLIVIANA CONOCE LAS ACCIONES Y LOS RESULTADOS DE LA ASUSS', 'INFORME', '4.1.5', 'PARTICIPACION EN FERIAS Y EVENTOS', '', '', '', '', '', '', '', '', '', '', '', '', '2022-01-03', '2022-12-31', '2022-10-17 19:31:22', '2022-10-17 19:31:22'),
(6, 2, 10.00, 'MEMORIA INSTITUCIONAL DE LA GESTIÓN 2021 Y MEMORIA INSTITUCIONAL DE LA GESTIÓN 2022', 'INFORME', '4.2.1', 'ELABORACIÓN  DE LA MEMORIA INSTITUCIONAL', '', '', '', '1', '', '', '', '', '', '', '', '1', '2022-03-01', '2022-12-31', '2022-10-17 19:31:22', '2022-10-17 19:31:22'),
(7, 2, 10.00, 'CONTAR CON MATERIAL PROMOCIONAL E INFORMATIVO', 'INFORME', '4.2.2', 'ELABORACION Y DIFUSION DE MATERIALES PROMOCIONALES INFORMATIVOS E INSTITUCIONALES', '', '', '', '', '', '1', '', '', '', '', '', '1', '2022-03-01', '2022-12-15', '2022-10-17 19:31:23', '2022-11-11 01:56:44'),
(9, 3, 20.00, 'RESULTAOD ESPERADO DE LA TAREA', 'MEDIO DE VERIFICACION', '1.1', 'ACTIVIDAD 1', '', '1', '', '', '1', '', '', '', '', '', '', '1', '2022-01-01', '2022-12-12', '2022-10-17 22:06:22', '2022-10-17 22:06:22'),
(10, 3, 20.00, 'RESULTADO ESPERADO DE LA ACTIVIDAD', 'MEDIO DE VERIFICACION', '1.2.', 'ACTIVIDAD 2', '', '', '', '', '', '', '1', '', '', '', '', '1', '2022-03-03', '2022-12-01', '2022-10-17 22:06:22', '2022-10-17 22:06:22'),
(11, 4, 40.00, 'RESULTADO ESPERADO', 'MEDIO', '2.1.2', 'ACTIVIDAD 2.1', '', '1', '', '', '', '', '', '', '', '', '', '', '2022-03-03', '2022-06-06', '2022-10-17 22:06:22', '2022-10-17 22:06:22'),
(12, 5, 10.00, 'DESC', 'DESC', '1.1', 'ACT 1', '', '', '', '', '', '', '', '', '1', '', '', '', '2022-10-27', '2022-11-03', '2022-10-20 14:28:57', '2022-10-20 14:28:57'),
(30, 19, 10.00, 'RESULTADO OPERACION 4.3', 'MEDIOS DE VERIFICACION OP. 4.3', '4.3.1', 'ACTIVIDAD 4.3.1', '', '', '', '1', '', '', '1', '', '', '', '', '1', '2022-04-04', '2022-12-31', '2022-12-13 14:55:00', '2022-12-13 14:55:00'),
(31, 20, 40.00, 'RESULTADO INTERMEDIO ESPERADO', 'MEDIOS VERIFICACION', '1.1.1', 'ACTIVIDAD 1.1.1', '', '', '1', '', '', '', '', '', '', '1', '1', '1', '2022-03-03', '2022-12-30', '2022-12-23 14:47:53', '2022-12-23 14:47:53'),
(32, 21, 40.00, 'RESULTADO INTERMEDIO ESPERADO 1.1.', 'MEDIOS DE VERIFICACION', '1.1.1.', 'TAREA 1.1.1.', '1', '', '', '', '', '', '1', '', '', '', '1', '1', '2023-01-01', '2023-12-12', '2023-01-06 15:36:58', '2023-01-06 15:36:58'),
(33, 22, 50.00, 'RESULTADO', 'MEDIOS', '2.1.1.', 'ACTIVIDAD 2.1.1.', '', '', '', '', '1', '', '', '', '', '', '', '1', '2023-05-04', '2023-12-12', '2023-01-06 15:36:58', '2023-01-06 15:36:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `financieras`
--

CREATE TABLE `financieras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `financieras`
--

INSERT INTO `financieras` (`id`, `descripcion`, `archivo`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'FINANCIERO 1', '1667580590_financiera1.jpg', '2022-11-04', '2022-11-04 16:49:23', '2022-11-05 17:53:17'),
(2, 'FINANCIERO 2', '1671811075_financiera2.jpg', '2022-12-23', '2022-12-23 15:57:55', '2022-12-23 15:57:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fisicos`
--

CREATE TABLE `fisicos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `fisicos`
--

INSERT INTO `fisicos` (`id`, `descripcion`, `archivo`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'DESCRIPCION FISICO 1', '1667580480_fisico1.jpg', '2022-11-04', '2022-11-04 16:24:57', '2022-11-04 16:48:00'),
(2, 'FISICO 2', '1667580485_fisico2.jpg', '2022-11-04', '2022-11-04 16:44:25', '2022-11-04 16:48:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario_cinco`
--

CREATE TABLE `formulario_cinco` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `memoria_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `formulario_cinco`
--

INSERT INTO `formulario_cinco` (`id`, `memoria_id`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-11-04', '2022-11-04 21:56:18', '2022-11-04 21:56:18'),
(2, 4, '2022-12-12', '2022-12-12 19:34:59', '2022-12-12 19:34:59'),
(4, 6, '2022-12-23', '2022-12-23 14:56:02', '2022-12-23 14:56:02'),
(5, 7, '2023-01-06', '2023-01-06 15:40:56', '2023-01-06 15:40:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario_cuatro`
--

CREATE TABLE `formulario_cuatro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo_pei` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resultado_institucional` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicador` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_poa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion_corto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicador_proceso` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `linea_base` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `presupuesto` decimal(24,2) NOT NULL,
  `ponderacion` double(8,2) NOT NULL,
  `unidad_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `formulario_cuatro`
--

INSERT INTO `formulario_cuatro` (`id`, `codigo_pei`, `resultado_institucional`, `indicador`, `codigo_poa`, `accion_corto`, `indicador_proceso`, `linea_base`, `meta`, `presupuesto`, `ponderacion`, `unidad_id`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, '11.1.298.4', 'CONSOLIDAR LA CAPACIDAD TÉCNICA TECNOLOGICA FINANCIERA Y NORMATIVA DE LA AUTORIDAD DE SUPERVISIÓN DE CORTO PLAZO', 'INCREMENTAR EL ÍNDICE DE EFICACIA DE LA ASUSS', '11.1.298.4', 'CONSOLIDAR LA ESTRUCTURA INSTITUCIONAL TÉCNICA, ADMINISTRATIVA, FINANCIERA Y JURÍDICA DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO A NIVEL NACIONAL', 'EJECUCIÓN FÍSICA 90% Y FINANCIERA 90%', '', '', '2081175.00', 20.00, 1, '2022-10-17', '2022-10-17 19:23:24', '2022-12-12 14:45:00'),
(2, '2.333.44', 'ACCION DE PRUEBA', 'INDICADOR DE PRUEBA', '2.333.44', 'ACCION DE CORTO PLAZO DE PRUEBA', 'RESULTADO ESPERADO DE PRUEBA', '', '', '350000.00', 20.00, 2, '2022-10-17', '2022-10-17 21:59:36', '2022-10-17 21:59:36'),
(3, '3.33', 'ACCION', 'INDICADOR', '3.33', 'ACCION', 'RESULTADO', '', '', '30000.00', 10.00, 3, '2022-10-20', '2022-10-20 14:28:04', '2022-10-20 14:28:04'),
(4, '3.33.11', 'ACCION INSTITCIONAL ESPECIFICA', 'INDICADOR DE PROCESO DESDE JEFE DE UNIDAD', '3.33.11', 'ACCION DE CORTO PLAZO', 'RESULTADO ESPERADO', 'PRUEBA LINEA BASE', 'PRUEBA META', '20000.00', 20.00, 3, '2022-11-05', '2022-11-05 20:36:21', '2022-12-12 14:49:53'),
(5, '11.33,4.33.33', 'RESULTADO INSTITUCIONAL', 'INDICADOR DE PROCESO', '33.333,3.11.1', 'ACCIÓN DE CORTO PLAZO', '', 'LINEA DE BASE', 'META', '30000.00', 20.00, 1, '2023-01-06', '2023-01-06 15:18:12', '2023-01-06 15:22:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `accion` varchar(155) NOT NULL,
  `modulo` varchar(155) NOT NULL,
  `detalle` text NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `accion`, `modulo`, `detalle`, `user_id`, `fecha`, `hora`) VALUES
(1, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2022-12-23', '10:23:01'),
(2, 'MODIFICACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 MODIFICÓ UNA UNIDAD ORGANIZACIONAL', 1, '2022-12-23', '10:25:12'),
(3, 'ELIMINACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 ELIMINÓ UNA UNIDAD ORGANIZACIONAL', 1, '2022-12-23', '10:25:23'),
(4, 'CREACIÓN', 'SUBDIRECCIONES', 'EL USUARIO 1 REGISTRO UNA SUBDIRECCIÓN', 1, '2022-12-23', '10:27:47'),
(5, 'MODIFICACIÓN', 'SUBDIRECCIONES', 'EL USUARIO 1 MODIFICÓ UNA SUBDIRECCIÓN', 1, '2022-12-23', '10:27:55'),
(6, 'ELIMINACIÓN', 'SUBDIRECCIONES', 'EL USUARIO 1 ELIMINÓ UNA SUBDIRECCIÓN', 1, '2022-12-23', '10:28:06'),
(7, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2022-12-23', '10:29:41'),
(8, 'MODIFICACIÓN', 'PARTIDAS', 'EL USUARIO 1 MODIFICÓ UNA PARTIDA', 1, '2022-12-23', '10:29:50'),
(9, 'ELIMINACIÓN', 'PARTIDAS', 'EL USUARIO 1 ELIMINÓ UNA PARTIDA', 1, '2022-12-23', '10:30:28'),
(10, 'CREACIÓN', 'FORMULARIO CUATRO', 'EL USUARIO 1 REGISTRO UN FORMULARIO CUATRO', 1, '2022-12-23', '10:35:16'),
(11, 'MODIFICACIÓN', 'FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN FORMULARIO CUATRO', 1, '2022-12-23', '10:39:35'),
(12, 'ELIMINACIÓN', 'FORMULARIO CUATRO', 'EL USUARIO 1 ELIMINÓ UN FORMULARIO CUATRO', 1, '2022-12-23', '10:42:52'),
(13, 'MODIFICACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN DETALLE FORMULARIO CUATRO', 1, '2022-12-23', '10:46:44'),
(14, 'ELIMINACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 1 ELIMINÓ UN DETALLE FORMULARIO CUATRO', 1, '2022-12-23', '10:47:09'),
(15, 'CREACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 1 REGISTRO UN DETALLE FORMULARIO CUATRO', 1, '2022-12-23', '10:47:53'),
(16, 'CREACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 REGISTRO UNA MEMORIA DE CÁLCULO', 1, '2022-12-23', '10:50:59'),
(17, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2022-12-23', '10:51:10'),
(18, 'ELIMINACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 ELIMINÓ UNA MEMORIA DE CÁLCULO', 1, '2022-12-23', '10:51:23'),
(19, 'MODIFICACIÓN', 'VERIFICACIÓN DE LA ACTIVDAD POA', 'EL USUARIO 1 MODIFICÓ LA VERIFICACIÓN DE LA ACTIVDAD POA', 1, '2022-12-23', '10:53:13'),
(20, 'CREACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 REGISTRO UNA MEMORIA DE CÁLCULO', 1, '2022-12-23', '10:56:02'),
(21, 'CREACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REGISTRO UNA CERTIFICACIÓN POA', 1, '2022-12-23', '10:56:35'),
(22, 'MODIFICACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 MODIFICÓ UNA CERTIFICACIÓN POA', 1, '2022-12-23', '10:56:44'),
(23, 'CREACIÓN', 'INFORME DE ACTIVIDAD REALIZADA', 'EL USUARIO 1 REGISTRO UN INFORME DE ACTIVIDAD REALIZADA', 1, '2022-12-23', '10:59:24'),
(24, 'MODIFICACIÓN', 'INFORME DE ACTIVIDAD REALIZADA', 'EL USUARIO 1 MODIFICÓ UN INFORME DE ACTIVIDAD REALIZADA', 1, '2022-12-23', '10:59:35'),
(25, 'ELIMINACIÓN', 'INFORME DE ACTIVIDAD REALIZADA', 'EL USUARIO 1 ELIMINÓ UN INFORME DE ACTIVIDAD REALIZADA', 1, '2022-12-23', '10:59:41'),
(26, 'MODIFICACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 MODIFICÓ UNA CERTIFICACIÓN POA', 1, '2022-12-23', '11:08:15'),
(27, 'CREACIÓN', 'FÍSICO', 'EL USUARIO 1 REGISTRO UN FÍSICO', 1, '2022-12-23', '11:56:21'),
(28, 'MODIFICACIÓN', 'FÍSICO', 'EL USUARIO 1 MODIFICÓ UN FÍSICO', 1, '2022-12-23', '11:56:32'),
(29, 'ELIMINACIÓN', 'FÍSICO', 'EL USUARIO 1 ELIMINÓ UN FÍSICO', 1, '2022-12-23', '11:56:39'),
(30, 'CREACIÓN', 'FINANCIERO', 'EL USUARIO 1 REGISTRO UN FINANCIERO', 1, '2022-12-23', '11:57:55'),
(31, 'MODIFICACIÓN', 'USUARIOS', 'EL USUARIO 1 MODIFICÓ UN USUARIO', 1, '2022-12-23', '12:01:18'),
(32, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2022-12-23', '12:01:47'),
(33, 'ELIMINACIÓN', 'USUARIOS', 'EL USUARIO 1 ELIMINÓ UN USUARIO', 1, '2022-12-23', '12:05:13'),
(34, 'CREACIÓN', 'FORMULARIO CUATRO', 'EL USUARIO 1 REGISTRO UN FORMULARIO CUATRO', 1, '2023-01-06', '11:18:12'),
(35, 'MODIFICACIÓN', 'FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN FORMULARIO CUATRO', 1, '2023-01-06', '11:22:59'),
(36, 'CREACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 1 REGISTRO UN DETALLE FORMULARIO CUATRO', 1, '2023-01-06', '11:36:58'),
(37, 'MODIFICACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN DETALLE FORMULARIO CUATRO', 1, '2023-01-06', '11:39:07'),
(38, 'CREACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 REGISTRO UNA MEMORIA DE CÁLCULO', 1, '2023-01-06', '11:40:56'),
(39, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-06', '11:41:05'),
(40, 'CREACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REGISTRO UNA CERTIFICACIÓN POA', 1, '2023-01-06', '11:46:42'),
(41, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-01-14', '11:23:00'),
(42, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-01-14', '11:24:16'),
(43, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-01-14', '11:24:53'),
(44, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-01-14', '11:25:36'),
(45, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '11:55:03'),
(46, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '11:57:00'),
(47, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '11:57:10'),
(48, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '11:58:14'),
(49, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '11:58:56'),
(50, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '11:59:23'),
(51, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '11:59:49'),
(52, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:00:57'),
(53, 'MODIFICACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN DETALLE FORMULARIO CUATRO', 1, '2023-01-14', '12:01:16'),
(54, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:01:33'),
(55, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:02:47'),
(56, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:02:55'),
(57, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:03:17'),
(58, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:04:03'),
(59, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:04:38'),
(60, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:04:54'),
(61, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:07:05'),
(62, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '12:07:10'),
(63, 'MODIFICACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN DETALLE FORMULARIO CUATRO', 1, '2023-01-14', '12:08:20'),
(64, 'MODIFICACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN DETALLE FORMULARIO CUATRO', 1, '2023-01-14', '12:08:30'),
(65, 'CREACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REGISTRO UNA CERTIFICACIÓN POA', 1, '2023-01-14', '12:53:28'),
(66, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:06:07'),
(67, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:06:17'),
(68, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:07:00'),
(69, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:07:01'),
(70, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:07:41'),
(71, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:08:46'),
(72, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:08:49'),
(73, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:09:35'),
(74, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:09:38'),
(75, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:09:40'),
(76, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:09:42'),
(77, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:09:44'),
(78, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:22:12'),
(79, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:29:36'),
(80, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:31:58'),
(81, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:32:00'),
(82, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:32:02'),
(83, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:32:41'),
(84, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:32:42'),
(85, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:32:43'),
(86, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:35:30'),
(87, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:36:01'),
(88, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:36:02'),
(89, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:37:40'),
(90, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:37:47'),
(91, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:37:49'),
(92, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:38:48'),
(93, 'MODIFICACIÓN', 'CONFIGURACIÓN DE MODULOS', 'EL USUARIO 1 REALIZÓ UN CAMBIO EN LA CONFIGURACIÓN DE MODULOS PARA EDITAR/ELIMINAR', 1, '2023-01-14', '14:38:48'),
(94, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '14:39:08'),
(95, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-01-14', '14:39:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `memoria_calculos`
--

CREATE TABLE `memoria_calculos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `formulario_id` bigint(20) UNSIGNED NOT NULL,
  `total_actividades` decimal(24,2) NOT NULL,
  `total_ene` decimal(24,2) NOT NULL,
  `total_feb` decimal(24,2) NOT NULL,
  `total_mar` decimal(24,2) NOT NULL,
  `total_abr` decimal(24,2) NOT NULL,
  `total_may` decimal(24,2) NOT NULL,
  `total_jun` decimal(24,2) NOT NULL,
  `total_jul` decimal(24,2) NOT NULL,
  `total_ago` decimal(24,2) NOT NULL,
  `total_sep` decimal(24,2) NOT NULL,
  `total_oct` decimal(24,2) NOT NULL,
  `total_nov` decimal(24,2) NOT NULL,
  `total_dic` decimal(24,2) NOT NULL,
  `total_final` decimal(24,2) NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `memoria_calculos`
--

INSERT INTO `memoria_calculos` (`id`, `formulario_id`, `total_actividades`, `total_ene`, `total_feb`, `total_mar`, `total_abr`, `total_may`, `total_jun`, `total_jul`, `total_ago`, `total_sep`, `total_oct`, `total_nov`, `total_dic`, `total_final`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, '2210.00', '0.00', '0.00', '0.00', '30.00', '30.00', '1060.00', '1045.00', '45.00', '0.00', '0.00', '0.00', '0.00', '2210.00', '2022-11-04', '2022-11-04 20:19:23', '2023-01-14 18:39:12'),
(4, 2, '2640.00', '0.00', '0.00', '100.00', '0.00', '1700.00', '400.00', '440.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2640.00', '2022-12-12', '2022-12-12 19:34:59', '2022-12-13 17:03:31'),
(6, 4, '120.00', '0.00', '0.00', '60.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '60.00', '120.00', '2022-12-23', '2022-12-23 14:56:02', '2022-12-23 14:56:02'),
(7, 5, '1200.00', '0.00', '400.00', '400.00', '400.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '1200.00', '2023-01-06', '2023-01-06 15:40:56', '2023-01-06 15:41:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `memoria_operacions`
--

CREATE TABLE `memoria_operacions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `memoria_id` bigint(20) UNSIGNED NOT NULL,
  `operacion_id` bigint(20) UNSIGNED NOT NULL,
  `detalle_operacion_id` bigint(20) UNSIGNED NOT NULL,
  `total_operacion` decimal(24,2) NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `memoria_operacions`
--

INSERT INTO `memoria_operacions` (`id`, `memoria_id`, `operacion_id`, `detalle_operacion_id`, `total_operacion`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '0.00', '2022-11-04', '2022-11-04 20:19:23', '2023-01-14 18:39:12'),
(7, 1, 2, 6, '0.00', '2022-11-04', '2022-11-04 21:44:45', '2023-01-14 18:39:12'),
(8, 1, 19, 30, '0.00', '2022-11-04', '2022-11-04 21:46:34', '2023-01-14 18:39:12'),
(11, 4, 3, 9, '0.00', '2022-12-12', '2022-12-12 19:34:59', '2022-12-13 17:03:31'),
(12, 4, 4, 11, '0.00', '2022-12-12', '2022-12-12 19:34:59', '2022-12-13 17:03:31'),
(14, 6, 20, 31, '0.00', '2022-12-23', '2022-12-23 14:56:02', '2022-12-23 14:56:02'),
(15, 7, 21, 32, '0.00', '2023-01-06', '2023-01-06 15:40:56', '2023-01-06 15:41:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `memoria_operacion_detalles`
--

CREATE TABLE `memoria_operacion_detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `memoria_operacion_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prog` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `act` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsable` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `partida_id` bigint(20) UNSIGNED NOT NULL,
  `partida` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_detallada` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` double(8,2) NOT NULL,
  `unidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `justificacion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ene` decimal(24,2) DEFAULT NULL,
  `feb` decimal(24,2) DEFAULT NULL,
  `mar` decimal(24,2) DEFAULT NULL,
  `abr` decimal(24,2) DEFAULT NULL,
  `may` decimal(24,2) DEFAULT NULL,
  `jun` decimal(24,2) DEFAULT NULL,
  `jul` decimal(24,2) DEFAULT NULL,
  `ago` decimal(24,2) DEFAULT NULL,
  `sep` decimal(24,2) DEFAULT NULL,
  `oct` decimal(24,2) DEFAULT NULL,
  `nov` decimal(24,2) DEFAULT NULL,
  `dic` decimal(24,2) DEFAULT NULL,
  `total_actividad` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `memoria_operacion_detalles`
--

INSERT INTO `memoria_operacion_detalles` (`id`, `memoria_operacion_id`, `ue`, `prog`, `act`, `lugar`, `responsable`, `partida_id`, `partida`, `descripcion`, `nro`, `descripcion_detallada`, `cantidad`, `unidad`, `costo`, `total`, `justificacion`, `ene`, `feb`, `mar`, `abr`, `may`, `jun`, `jul`, `ago`, `sep`, `oct`, `nov`, `dic`, `total_actividad`, `created_at`, `updated_at`) VALUES
(1, '11', '01', '01', '10', 'LA PAZ', 'TECNICO EN TELECOMUNICACIONES', 1, '25600', 'SERVICIOS DE FOTOCOPIADO Y FOTOGRAFICO', '1', 'DESCRIPCION SERVICIO 1', 10.00, 'UNIDAD', '20.00', '200.00', 'JUSTIFICACION SERVICIO 1', NULL, NULL, '100.00', NULL, '100.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', '2022-12-12 19:34:59', '2022-12-13 17:03:31'),
(2, '11', '01', '01', '10', 'LA PAZ', 'TECNICO', 2, '21002', 'SERVICIOS DE PRUEBA', '2', 'DESCRIPCION ITEM 2', 30.00, 'UNIDAD', '40.00', '1200.00', 'JUSITIFICACION DEL SEGUNDO ITEM', NULL, NULL, NULL, NULL, '400.00', '400.00', '400.00', NULL, NULL, NULL, NULL, NULL, '1200.00', '2022-12-12 19:34:59', '2022-12-13 17:03:31'),
(3, '12', '01', '01', '10', 'ORURO', 'PERIODISTAS', 1, '25600', 'SERVICIOS DE FOTOCOPIADO Y FOTOGRAFICO', '1', 'DESCRIPCION ITEM 1 OPERACION 2', 3.00, 'UNIDAD', '400.00', '1200.00', 'JUSTIFICACION DE PRUEBA', NULL, NULL, NULL, NULL, '1200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1200.00', '2022-12-12 19:34:59', '2022-12-13 17:03:31'),
(5, '12', '01', '01', '10', 'BENI', 'TECNICO RESPONSABLE', 2, '21002', 'SERVICIOS DE PRUEBA', '2', 'DESCRIPCION DE PRUEBA NUEVO POR ACTUALIZACION', 4.00, 'UNIDAD', '10.00', '40.00', 'JUSTIFICACION', NULL, NULL, NULL, NULL, NULL, NULL, '40.00', NULL, NULL, NULL, NULL, NULL, '40.00', '2022-12-12 19:50:58', '2022-12-13 17:03:31'),
(6, '1', '01', '01', '10', 'LA PAZ', 'TECNICO EN TELECOMUNICACIONES', 2, '21002', 'SERVICIOS DE PRUEBA', '1', 'DESCRIPCION PRIMER GASTO', 3.00, 'UNIDAD', '20.00', '60.00', 'JUSTIFICACION', NULL, NULL, NULL, NULL, NULL, '60.00', NULL, NULL, NULL, NULL, NULL, NULL, '60.00', '2022-12-12 20:02:21', '2022-12-13 13:56:47'),
(7, '7', '01', '01', '10', 'LA PAZ', 'PERIODISTAS', 2, '21002', 'SERVICIOS DE PRUEBA', '1', 'GASTO DE PRUEBA EN SEGUNDA OPERACION', 3.00, 'UNIDAD', '30.00', '90.00', 'JUSTIFICACION', NULL, NULL, NULL, NULL, NULL, NULL, '45.00', '45.00', NULL, NULL, NULL, NULL, '90.00', '2022-12-12 20:02:21', '2023-01-14 15:57:10'),
(8, '8', '01', '01', '10', 'LA PAZ', 'TECNICO RESPONSABLE', 2, '21002', 'SERVICIOS DE PRUEBA', '1', 'DESCRIPCION GASTO OPERACION 3', 20.00, 'UNIDAD', '100.00', '2000.00', 'JUSTIFICACION DEL GASTO DE LA OPERACION NRO. 3', NULL, NULL, NULL, NULL, NULL, '1000.00', '1000.00', NULL, NULL, NULL, NULL, NULL, '2000.00', '2022-12-12 20:02:21', '2023-01-14 15:55:03'),
(9, '1', '01', '01', '10', 'LA PAZ', 'TECNICO EN TELECOMUNICACIONES', 1, '25600', 'SERVICIOS DE FOTOCOPIADO Y FOTOGRAFICO', '2', 'PRUEBA SEGUNDO GASTO PRIMERA OPERACION ACTUALIZACION', 2.00, 'UNIDAD', '30.00', '60.00', 'JUSTIFICACION', NULL, NULL, NULL, '30.00', '30.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '60.00', '2022-12-12 20:03:04', '2022-12-13 15:42:11'),
(10, '13', '01', '01', '10', 'LA PAZ', 'TECNICO', 2, '21002', 'SERVICIOS DE PRUEBA', '1', 'DESCRIPCION DETALLADA', 3.00, 'UNIDAD', '300.00', '900.00', 'JUSTIFICACION', NULL, NULL, '300.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '300.00', '300.00', '900.00', '2022-12-23 14:50:59', '2022-12-23 14:50:59'),
(11, '14', '01', '01', '10', 'LA PAZ', 'TECNICO', 1, '25600', 'SERVICIOS DE FOTOCOPIADO Y FOTOGRAFICO', '1', 'DETALLE', 3.00, 'UNIDAD', '40.00', '120.00', 'JUSTIFICACION', NULL, NULL, '60.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '60.00', '120.00', '2022-12-23 14:56:02', '2022-12-23 14:56:02'),
(12, '15', '10', '01', '01', 'LA PAZ', 'TECNICO', 1, '25600', 'SERVICIOS DE FOTOCOPIADO Y FOTOGRAFICO', '1', 'DESCRIPCION GASTO 1', 30.00, 'UNIDAD', '40.00', '1200.00', 'JUSTIFICACION', NULL, '400.00', '400.00', '400.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1200.00', '2023-01-06 15:40:56', '2023-01-06 15:40:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2022_10_13_132625_create_configuracions_table', 1),
(3, '2022_10_13_132626_create_unidads_table', 1),
(4, '2022_10_13_132627_create_users_table', 1),
(5, '2022_10_13_134551_create_formulario_cuatro_table', 1),
(6, '2022_10_13_135025_create_detalle_formularios_table', 1),
(7, '2022_10_13_135026_create_operacions_table', 1),
(8, '2022_10_13_135709_create_detalle_operacions_table', 1),
(11, '2022_10_13_142014_create_lugar_responsables_table', 1),
(14, '2022_10_13_140145_create_f_c_operacions_table', 2),
(15, '2022_10_13_141806_create_lugar_responsables_table', 3),
(16, '2022_10_13_141807_create_actividad_tareas_table', 4),
(17, '2022_10_13_142633_create_partidas_table', 5),
(24, '2022_11_04_115914_create_fisicos_table', 7),
(25, '2022_11_04_120025_create_financieras_table', 8),
(26, '2022_11_04_120034_create_semaforos_table', 9),
(27, '2022_10_13_140142_create_memoria_calculos_table', 10),
(28, '2022_11_04_133608_create_memoria_operacions_table', 11),
(29, '2022_10_13_140144_create_formulario_cinco_table', 12),
(30, '2022_10_13_143018_create_certificacions_table', 13),
(31, '2022_11_04_221949_create_verificacion_actividads_table', 14),
(32, '2022_11_05_102322_create_actividad_realizadas_table', 15),
(33, '2022_11_19_083815_create_partidas_table', 16),
(34, '2022_12_12_112259_create_subdireccions_table', 17),
(35, '2022_12_12_133756_create_memoria_operacion_detalles_table', 18),
(36, '2023_01_14_132641_create_configuracion_modulos_table', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operacions`
--

CREATE TABLE `operacions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detalle_formulario_id` bigint(20) UNSIGNED NOT NULL,
  `subdireccion_id` bigint(20) UNSIGNED DEFAULT NULL,
  `codigo_operacion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operacion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `operacions`
--

INSERT INTO `operacions` (`id`, `detalle_formulario_id`, `subdireccion_id`, `codigo_operacion`, `operacion`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '4.1', 'VISIBILIZAR Y POSICIONAR LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO A NIVEL REGIONAL Y NACIONAL', '2022-10-17 19:31:22', '2022-12-13 17:37:51'),
(2, 1, NULL, '4.2', 'ELABORACIÓN Y PUBLICACIÓN DE CONTENIDOS DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', '2022-10-17 19:31:22', '2022-10-17 19:31:22'),
(3, 2, NULL, '1', 'OPERACION 1', '2022-10-17 22:06:22', '2022-10-17 22:06:22'),
(4, 2, NULL, '2.1', 'OPERACION 2', '2022-10-17 22:06:22', '2022-10-17 22:06:22'),
(5, 3, NULL, '1', 'OP 1', '2022-10-20 14:28:57', '2022-10-20 14:28:57'),
(19, 1, 2, '4.3', 'OPERACION 4.3', '2022-12-13 14:55:00', '2022-12-13 17:41:49'),
(20, 8, NULL, '1.1', 'OPERACION 1.1', '2022-12-23 14:47:53', '2022-12-23 14:47:53'),
(21, 9, 1, '1.1.', 'OPERACION', '2023-01-06 15:36:58', '2023-01-06 15:36:58'),
(22, 9, NULL, '2.1.', 'OPERACION 2.1.', '2023-01-06 15:36:58', '2023-01-06 15:36:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

CREATE TABLE `partidas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partida` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `partidas`
--

INSERT INTO `partidas` (`id`, `partida`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, '25600', 'SERVICIOS DE FOTOCOPIADO Y FOTOGRAFICO', '2022-11-19 12:39:02', '2022-11-19 12:39:12'),
(2, '21002', 'SERVICIOS DE PRUEBA', '2022-11-19 12:47:59', '2022-11-19 12:47:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semaforos`
--

CREATE TABLE `semaforos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `semaforos`
--

INSERT INTO `semaforos` (`id`, `descripcion`, `archivo`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'SEMAFORO 1', '1667580599_semaforo1.jpg', '2022-11-04', '2022-11-04 16:49:59', '2022-11-04 16:49:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subdireccions`
--

CREATE TABLE `subdireccions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `subdireccions`
--

INSERT INTO `subdireccions` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'SUBDIRECCION 1', '2022-12-12 15:33:24', '2022-12-12 15:33:24'),
(2, 'SUBDIRECCION 2', '2022-12-12 15:33:29', '2022-12-12 15:33:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidads`
--

CREATE TABLE `unidads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `unidads`
--

INSERT INTO `unidads` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'ÁREA DE COMUNICACIÓN SOCIAL DE LA ASUSS', '2022-10-14 20:27:10', '2022-10-14 20:27:10'),
(2, 'UNIDAD 2', '2022-10-17 21:58:59', '2022-10-17 21:58:59'),
(3, 'UNIDAD 3', '2022-10-20 14:26:12', '2022-10-20 14:26:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cargo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lugar_trabajo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_puesto` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci,
  `unidad_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acceso` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `fono`, `cargo`, `lugar_trabajo`, `descripcion_puesto`, `observacion`, `unidad_id`, `tipo`, `foto`, `password`, `acceso`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '', NULL, '', '', '', '', '', '', NULL, NULL, 'SUPER USUARIO', 'default.png', '$2y$10$cDSOdzTsMDQAfqcb6.WFtu40s.wmQ4Jl8poIwW69MSZnnedD3prKu', 1, '2022-10-13', NULL, NULL),
(2, 'JPERES', 'JUAN', 'PERES', '', '1234', 'LP', '22222', 'RESPONSABLE DE OFICINA DEPARTAMENTAL POTOSÍ', '', '', NULL, 1, 'ENLACE', 'default.png', '$2y$10$1hgmqNI9y8FEfM2ZY/scau4SFqC055G7U.NowDmBkJrCuORkfzLuu', 1, '2022-10-16', '2022-10-16 17:47:11', '2022-11-05 20:21:29'),
(3, 'RCOLQUE', 'RUBEN', 'COLQUE', '', '2222', 'LP', '22222', 'RESPONSABLE REGIONAL LA PAZ', '', '', NULL, 1, 'MAE', 'default.png', '$2y$10$BTLd1mspf9zY1UaBWWgnz.ktFLPIQtvRAuL0IfI45M6GnpQCbmu2O', 1, '2022-10-16', '2022-10-16 17:47:49', '2022-11-04 14:00:02'),
(4, 'AGONZALES', 'ALBERTO', 'GONZALES', '', '3333', 'LP', '2222', 'CARGO', '', '', NULL, 1, 'FINANCIERA', 'default.png', '$2y$10$hjd0GxLr801x/JTYDuuAZeObvjdgMs1RKMDY1YiOhELF7SXjSMGge', 1, '2022-11-04', '2022-11-04 13:56:22', '2022-11-05 20:00:56'),
(5, 'PALBES', 'PEDRO', 'ALBES', 'CONDORI', '4444', 'CB', '22222', 'CARGO 4', '', '', NULL, 3, 'JEFES DE UNIDAD', 'default.png', '$2y$10$r3IopSrAc4DPjUVhYCod1OfcykM//CP9gPRlpg4RXoAquhtA09.I6', 1, '2022-11-05', '2022-11-05 20:00:43', '2022-11-05 20:00:48'),
(6, 'CSANCHEZ', 'CARLOS', 'SANCHEZ', '', '5555', 'LP', '2222', 'CARGO 5', '', '', NULL, 1, 'DIRECTORES', 'default.png', '$2y$10$75Xh3l4YZqj5gccjdI3jcObhRQrc5sOJBIKTSM0L7P12PCT0Xr8bW', 1, '2022-11-05', '2022-11-05 20:13:26', '2022-11-05 20:13:26'),
(7, 'ALBERTO.MAMANI', 'ALBERTO', 'MAMANI', 'ROSALES', '3434', 'LP', '77777', 'DENOMINACIÓN DEL PUESTO', 'LA PAZ', 'DESCRIPCION DELPUESTO', '', 2, 'ENLACE', 'default.png', '$2y$10$oWp3CGo81bjOjZND8jceWub10nOnvIceqDDaQC7olTW.TckuF3VoG', 1, '2023-01-14', '2023-01-14 15:23:00', '2023-01-14 15:23:00'),
(8, 'JUAN.CORTEZ', 'JUAN PABLO', 'CORTEZ', 'CORTEZ', '2424', 'CB', '666666', 'RESPONSABLE DE OFICINA DEPARTAMENTAL POTOSÍ', 'POTOSI', 'DESCRIPCION', 'OBS', 1, 'JEFES DE UNIDAD', 'default.png', '$2y$10$/inv0MjLvND92V4P98pn5.cocJDweU8AfWS7ppy5mCGM56hTa39SO', 1, '2023-01-14', '2023-01-14 15:24:16', '2023-01-14 15:24:16'),
(9, 'JUAN.CANAVIRI', 'JUAN', 'CANAVIRI', 'ROSALES', '5454', 'LP', '222222', 'RESPONSABLE DE OFICINA DEPARTAMENTAL LA PAZ', 'LA PAZ', 'DESC PUESTO', '', 2, 'JEFES DE UNIDAD', 'default.png', '$2y$10$xKM532iU/JtqrXxpfz3oWOadAKnJlPywq39pn92iKPp4MY/Oafiey', 1, '2023-01-14', '2023-01-14 15:24:53', '2023-01-14 15:24:53'),
(10, 'JUAN.CORTEZ1', 'JUAN JOSE', 'CORTEZ', 'MAMANI', '1221', 'CB', '666666', 'RESPONSABLE DE OFICINA DEPARTAMENTAL COCHABAMBA', 'COCHABAMBA', 'DESC', '', 2, 'JEFES DE ÁREAS', 'default.png', '$2y$10$AcdZe1vmVwBXccJLAKxG7uFDCgVuoQsCNIGNUszLIHw2BlB2bZm9i', 1, '2023-01-14', '2023-01-14 15:25:36', '2023-01-14 15:25:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verificacion_actividads`
--

CREATE TABLE `verificacion_actividads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gestion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actividad` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `verificacion_actividads`
--

INSERT INTO `verificacion_actividads` (`id`, `gestion`, `actividad`, `created_at`, `updated_at`) VALUES
(1, '2022', 'La actividad se encuentra en el Plan Operativo Anual de la gestión 2022 de la Autoridad de Supervisión de la Seguridad Social de Corto Plazo, aprobado mediante la Resolución Administrativa N° 043 de 10 de septiembre de 2021 (Para su aprobación)', NULL, '2022-11-19 13:30:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad_realizadas`
--
ALTER TABLE `actividad_realizadas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `certificacions`
--
ALTER TABLE `certificacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificacions_formulario_id_foreign` (`formulario_id`),
  ADD KEY `certificacions_mo_id_foreign` (`mo_id`),
  ADD KEY `certificacions_solicitante_id_foreign` (`solicitante_id`),
  ADD KEY `certificacions_superior_id_foreign` (`superior_id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion_modulos`
--
ALTER TABLE `configuracion_modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_formularios`
--
ALTER TABLE `detalle_formularios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_formularios_formulario_id_foreign` (`formulario_id`);

--
-- Indices de la tabla `detalle_operacions`
--
ALTER TABLE `detalle_operacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_operacions_operacion_id_foreign` (`operacion_id`);

--
-- Indices de la tabla `financieras`
--
ALTER TABLE `financieras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fisicos`
--
ALTER TABLE `fisicos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `formulario_cinco`
--
ALTER TABLE `formulario_cinco`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formulario_cinco_memoria_id_foreign` (`memoria_id`);

--
-- Indices de la tabla `formulario_cuatro`
--
ALTER TABLE `formulario_cuatro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formulario_cuatro_unidad_id_foreign` (`unidad_id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `memoria_calculos`
--
ALTER TABLE `memoria_calculos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `memoria_operacions`
--
ALTER TABLE `memoria_operacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `memoria_operacions_memoria_id_foreign` (`memoria_id`),
  ADD KEY `memoria_operacions_operacion_id_foreign` (`operacion_id`),
  ADD KEY `memoria_operacions_detalle_operacion_id_foreign` (`detalle_operacion_id`);

--
-- Indices de la tabla `memoria_operacion_detalles`
--
ALTER TABLE `memoria_operacion_detalles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `operacions`
--
ALTER TABLE `operacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operacions_detalle_formulario_id_foreign` (`detalle_formulario_id`);

--
-- Indices de la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `semaforos`
--
ALTER TABLE `semaforos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subdireccions`
--
ALTER TABLE `subdireccions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `unidads`
--
ALTER TABLE `unidads`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`),
  ADD KEY `users_unidad_id_foreign` (`unidad_id`);

--
-- Indices de la tabla `verificacion_actividads`
--
ALTER TABLE `verificacion_actividads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividad_realizadas`
--
ALTER TABLE `actividad_realizadas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `certificacions`
--
ALTER TABLE `certificacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `configuracion_modulos`
--
ALTER TABLE `configuracion_modulos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_formularios`
--
ALTER TABLE `detalle_formularios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `detalle_operacions`
--
ALTER TABLE `detalle_operacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `financieras`
--
ALTER TABLE `financieras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `fisicos`
--
ALTER TABLE `fisicos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `formulario_cinco`
--
ALTER TABLE `formulario_cinco`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `formulario_cuatro`
--
ALTER TABLE `formulario_cuatro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `memoria_calculos`
--
ALTER TABLE `memoria_calculos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `memoria_operacions`
--
ALTER TABLE `memoria_operacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `memoria_operacion_detalles`
--
ALTER TABLE `memoria_operacion_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `operacions`
--
ALTER TABLE `operacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `partidas`
--
ALTER TABLE `partidas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `semaforos`
--
ALTER TABLE `semaforos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `subdireccions`
--
ALTER TABLE `subdireccions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `unidads`
--
ALTER TABLE `unidads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `verificacion_actividads`
--
ALTER TABLE `verificacion_actividads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `certificacions`
--
ALTER TABLE `certificacions`
  ADD CONSTRAINT `certificacions_formulario_id_foreign` FOREIGN KEY (`formulario_id`) REFERENCES `formulario_cuatro` (`id`),
  ADD CONSTRAINT `certificacions_mo_id_foreign` FOREIGN KEY (`mo_id`) REFERENCES `memoria_operacions` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `certificacions_solicitante_id_foreign` FOREIGN KEY (`solicitante_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `certificacions_superior_id_foreign` FOREIGN KEY (`superior_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_formularios`
--
ALTER TABLE `detalle_formularios`
  ADD CONSTRAINT `detalle_formularios_formulario_id_foreign` FOREIGN KEY (`formulario_id`) REFERENCES `formulario_cuatro` (`id`);

--
-- Filtros para la tabla `detalle_operacions`
--
ALTER TABLE `detalle_operacions`
  ADD CONSTRAINT `detalle_operacions_operacion_id_foreign` FOREIGN KEY (`operacion_id`) REFERENCES `operacions` (`id`);

--
-- Filtros para la tabla `formulario_cinco`
--
ALTER TABLE `formulario_cinco`
  ADD CONSTRAINT `formulario_cinco_memoria_id_foreign` FOREIGN KEY (`memoria_id`) REFERENCES `memoria_calculos` (`id`);

--
-- Filtros para la tabla `formulario_cuatro`
--
ALTER TABLE `formulario_cuatro`
  ADD CONSTRAINT `formulario_cuatro_unidad_id_foreign` FOREIGN KEY (`unidad_id`) REFERENCES `unidads` (`id`);

--
-- Filtros para la tabla `memoria_operacions`
--
ALTER TABLE `memoria_operacions`
  ADD CONSTRAINT `memoria_operacions_detalle_operacion_id_foreign` FOREIGN KEY (`detalle_operacion_id`) REFERENCES `detalle_operacions` (`id`),
  ADD CONSTRAINT `memoria_operacions_memoria_id_foreign` FOREIGN KEY (`memoria_id`) REFERENCES `memoria_calculos` (`id`),
  ADD CONSTRAINT `memoria_operacions_operacion_id_foreign` FOREIGN KEY (`operacion_id`) REFERENCES `operacions` (`id`);

--
-- Filtros para la tabla `operacions`
--
ALTER TABLE `operacions`
  ADD CONSTRAINT `operacions_detalle_formulario_id_foreign` FOREIGN KEY (`detalle_formulario_id`) REFERENCES `detalle_formularios` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_unidad_id_foreign` FOREIGN KEY (`unidad_id`) REFERENCES `unidads` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
