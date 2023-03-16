-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 16-03-2023 a las 16:08:33
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aprobacions`
--

CREATE TABLE `aprobacions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unidad_id` bigint(20) UNSIGNED NOT NULL,
  `estado` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `aprobacions`
--

INSERT INTO `aprobacions` (`id`, `unidad_id`, `estado`, `created_at`, `updated_at`) VALUES
(1, 4, 0, '2023-03-03 18:14:22', '2023-03-03 18:14:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificacions`
--

CREATE TABLE `certificacions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `formulario_id` bigint(20) UNSIGNED NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correlativo` bigint(20) NOT NULL,
  `solicitante_id` bigint(20) UNSIGNED NOT NULL,
  `superior_id` bigint(20) UNSIGNED NOT NULL,
  `inicio` date NOT NULL,
  `final` date NOT NULL,
  `personal_designado` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `certificacions` (`id`, `formulario_id`, `archivo`, `correlativo`, `solicitante_id`, `superior_id`, `inicio`, `final`, `personal_designado`, `departamento`, `municipio`, `estado`, `anulado`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(5, 1, NULL, 5, 13, 12, '2023-03-13', '2023-03-31', 9, 'COCHABAMBA', 'CHAPARE', 'APROBADO', 0, '2023-03-13', '2023-03-13 21:59:39', '2023-03-16 14:22:34'),
(6, 1, NULL, 6, 15, 16, '2023-01-01', '2023-12-12', 9, '', '', 'PENDIENTE', 0, '2023-03-16', '2023-03-16 13:56:12', '2023-03-16 14:26:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificacion_detalles`
--

CREATE TABLE `certificacion_detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificacion_id` bigint(20) UNSIGNED NOT NULL,
  `mo_id` bigint(20) UNSIGNED NOT NULL,
  `mod_id` bigint(20) UNSIGNED NOT NULL,
  `total_cantidad` double NOT NULL,
  `cantidad_usar` double NOT NULL,
  `saldo_cantidad` double NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `presupuesto_usarse` decimal(24,2) NOT NULL,
  `saldo_total` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `certificacion_detalles`
--

INSERT INTO `certificacion_detalles` (`id`, `certificacion_id`, `mo_id`, `mod_id`, `total_cantidad`, `cantidad_usar`, `saldo_cantidad`, `total`, `presupuesto_usarse`, `saldo_total`, `created_at`, `updated_at`) VALUES
(13, 5, 1, 1, 2, 2, 0, '2484.00', '2484.00', '0.00', '2023-03-15 23:20:24', '2023-03-15 23:56:29'),
(14, 5, 1, 2, 3, 3, 0, '1113.00', '1113.00', '0.00', '2023-03-15 23:20:24', '2023-03-15 23:56:29'),
(15, 6, 3, 5, 4, 2, 2, '4882.00', '2441.00', '2441.00', '2023-03-16 13:56:12', '2023-03-16 13:56:12'),
(16, 6, 3, 6, 7, 3, 4, '2597.00', '1113.00', '1484.00', '2023-03-16 13:56:12', '2023-03-16 13:56:12');

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
  `logo2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `ciudad`, `dir`, `fono`, `web`, `actividad`, `correo`, `logo`, `logo2`, `created_at`, `updated_at`) VALUES
(1, 'INTEGRAL DE PLANIFICACIÓN Y PRESUPUESTO', 'SIPLAP', 'SIPLAP', 'LA PAZ', 'AV. 6 DE AGOSTO EDIF. DOS TORRES', '2152400', 'WWW.ASUSS.GOB.BO', 'AUTORIDAD DE SUPERVISION DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', '', '1678731747_logo.png', '1678977745_logo2.jpg', NULL, '2023-03-16 14:42:25');

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
(1, 'FORMULARIO 4', 1, 1, NULL, '2023-01-29 15:23:51'),
(2, 'DETALLE FORMULARIO 4', 1, 1, NULL, '2023-01-25 02:51:47'),
(3, 'MEMORIA DE CÁLCULO', 1, 1, NULL, '2023-01-25 02:51:48'),
(4, 'APROBAR FORMULARIOS', 0, 1, NULL, '2023-01-29 15:23:16');

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
(1, 1, '2023-03-03', '2023-03-03 18:26:17', '2023-03-03 18:26:17');

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
(1, 1, 8.00, '\'PLAN ESTRATÉGICO INSTITUCIONAL APROBADO MEDIANTE LA RESOLUCIÓN ADMINISTRATIVA Y RESOLUCIÓN MINISTERIAL Y SOCIALIZADO', '\'INFORME TÉCNICO', '4.1.1', 'SOCIALIZAR EL PLAN  ESTRATÉGICO INSTITUCIONAL 2021- 2025 DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', '', '1', '', '', '', '', '', '', '', '', '', '', '2023-01-02', '2023-02-28', '2023-03-03 18:26:18', '2023-03-03 18:26:18'),
(2, 2, 11.00, 'UN INFORME DE GESTIÓN 2022', 'INFORME DE GESTIÓN', '4.2.1', 'ELABORAR EL INFORME DE GESTIÓN 2022 DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', '', '1', '', '', '', '', '', '', '', '', '', '', '2023-01-01', '2023-02-10', '2023-03-03 18:35:40', '2023-03-03 18:35:40'),
(3, 2, 19.00, 'UN INFORME DE SEGUIMIENTO Y EVALUACIÓN DEL PLAN OPERATIVO 2022 Y 3 INFORMES TRIMESTRALES AL POA 2023', 'INFORMES DE SEGUIMIENTO TRIMESTRALES AL POA 2022 Y 2023', '4.2.2', 'REALIZAR EL SEGUIMIENTO AL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2022 (CUARTO TRIMESTRE) Y 2023 (PRIMER, SEGUNDO Y TERCER TRIMESTRE) DE LA ASUSS', '1', '', '', '1', '', '', '1', '', '', '1', '', '1', '2023-01-01', '2023-10-20', '2023-03-03 18:35:40', '2023-03-03 18:35:40'),
(4, 2, 17.00, '3 DOCUMENTO DEL PLAN OPERATIVO APROBADO CON RESOLUCIÓN ADMINISTRATIVA', '3 INFORMES TÉCNICOS DE MODIFICACIÓN DEL POA 2023', '4.2.3', 'REALIZAR LA MODIFICACIÓN AL PLAN OPERATIVO ANUAL  DE LA GESTIÓN 2023 DE LA ASUSS', '', '', '1', '', '', '', '', '1', '', '', '1', '', '2023-02-01', '2023-10-25', '2023-03-03 18:35:40', '2023-03-03 18:35:40'),
(5, 2, 18.00, 'UN DOCUMENTO DEL PLAN OPERATIVO ANUAL 2024 APROBADO CON RESOLUCIÓN ADMINISTRATIVA', 'INFORME TÉCNICO', '4.2.4', 'ELABORAR EL ANTEPROYECTO DEL POA 2024 DE LA  AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', '', '', '1', '', '', '', '', '', '', '', '', '', '2023-07-01', '2023-09-15', '2023-03-03 18:35:40', '2023-03-03 18:35:40'),
(6, 2, 6.00, 'SISTEMA IMPLEMENTADO', 'INFORME TÉCNICO', '4.2.5', 'IMPLEMENTAR EL SISTEMA DE FORMULACIÓN, SEGUIMIENTO Y MODIFICACIÓN DEL PLAN OPERATIVO ANUAL DE LA ASUSS', '', '', '1', '', '', '', '', '', '', '', '', '', '2023-03-02', '2023-06-30', '2023-03-03 18:35:40', '2023-03-03 18:35:40'),
(7, 3, 11.00, 'MANUAL DE ORGANIZACIÓN Y FUNCIONES APROBADO MEDIANTE LA RESOLUCIÓN ADMINISTRATIVA', 'INFORME TÉCNICO', '4.3.1', 'ACTUALIZAR EL MANUAL DE ORGANIZACIÓN Y FUNCIONES DE LA ASUSS EN COORDINACIÓN CON LAS UNIDADES ORGANIZACIONALES', '', '', '', '', '', '', '1', '', '', '', '', '', '2023-04-01', '2023-08-30', '2023-03-03 18:38:42', '2023-03-03 18:38:42'),
(8, 3, 10.00, 'MANUAL DE PROCESOS Y PROCEDIMIENTOS APROBADO MEDIANTE LA RESOLUCIÓN ADMINISTRATIVA', 'INFORME TÉCNICO', '4.3.2', 'ACTUALIZAR EL MANUAL DE PROCESOS Y PROCEDIMIENTOS DE LA ASUSS EN COORDINACIÓN CON LAS UNIDADES ORGANIZACIONALES', '', '', '', '', '', '', '1', '', '', '', '', '', '2023-04-01', '2023-08-30', '2023-03-03 18:38:42', '2023-03-03 18:38:42');

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
(1, 1, '2023-03-03', '2023-03-03 18:45:53', '2023-03-03 18:45:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario_cuatro`
--

CREATE TABLE `formulario_cuatro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo_pei` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `resultado_institucional` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicador` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_poa` text COLLATE utf8mb4_unicode_ci NOT NULL,
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
(1, '6.11.7.1.7.1.-CONSOLIDAR LA CAPACIDAD INSTITUCIONAL, TÉCNICA, \r\nTECNOLÓGICA, FINANCIERA Y NORMATIVA DE LA AUTORIDAD DE \r\nSUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO.', 'CONSOLIDAR LA CAPACIDAD INSTITUCIONAL TÉCNICA TECNOLÓGICA, FINANCIERA Y NORMATIVA, DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', 'INCREMENTAR LA EJECUCIÓN FÍSICA Y FINANCIERA MAYOR AL 90%', '11.7.1.7.1.4.-CONSOLIDAR LA ESTRUCTURA INSTITUCIONAL TÉCNICA, ADMINISTRATIVA, FINANCIERA Y JURÍDICA DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO A NIVEL NACIONAL, REGIONAL Y DEPARTAMENTAL.', 'CONSOLIDAR LA ESTRUCTURA INSTITUCIONAL TÉCNICA, ADMINISTRATIVA, FINANCIERA Y JURÍDICA DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO A NIVEL NACIONAL, REGIONAL Y DEPARTAMENTAL.', '', 'MAYOR A 90%', 'MAYOR A 90%', '91800.00', 20.00, 4, '2023-03-03', '2023-03-03 18:14:22', '2023-03-13 19:00:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalle` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `accion`, `modulo`, `detalle`, `user_id`, `fecha`, `hora`) VALUES
(1, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:42:37'),
(2, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:43:00'),
(3, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:44:00'),
(4, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:44:27'),
(5, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:44:49'),
(6, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:45:02'),
(7, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:45:37'),
(8, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:45:59'),
(9, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:46:42'),
(10, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:47:08'),
(11, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:47:40'),
(12, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:48:12'),
(13, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:51:45'),
(14, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:52:31'),
(15, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:53:33'),
(16, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:53:57'),
(17, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:54:26'),
(18, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:54:50'),
(19, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:56:23'),
(20, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:59:03'),
(21, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '10:59:33'),
(22, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:00:24'),
(23, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:02:09'),
(24, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:03:22'),
(25, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:28:04'),
(26, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:28:28'),
(27, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:29:25'),
(28, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:29:50'),
(29, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:30:21'),
(30, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:31:46'),
(31, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:32:10'),
(32, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:32:26'),
(33, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:32:57'),
(34, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:33:17'),
(35, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:33:41'),
(36, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:40:21'),
(37, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:40:41'),
(38, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:41:07'),
(39, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:41:34'),
(40, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:42:02'),
(41, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:42:24'),
(42, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:43:03'),
(43, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:44:13'),
(44, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:44:47'),
(45, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:45:07'),
(46, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:45:31'),
(47, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:45:55'),
(48, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:46:23'),
(49, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:46:44'),
(50, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:47:06'),
(51, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:47:40'),
(52, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:48:16'),
(53, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:48:41'),
(54, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:48:53'),
(55, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:49:23'),
(56, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:49:52'),
(57, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:50:08'),
(58, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:50:36'),
(59, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:50:58'),
(60, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:53:43'),
(61, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:53:58'),
(62, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:54:10'),
(63, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '11:54:25'),
(64, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:00:09'),
(65, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:17:20'),
(66, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:17:41'),
(67, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:18:06'),
(68, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:18:33'),
(69, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:18:59'),
(70, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:19:25'),
(71, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:19:45'),
(72, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:20:13'),
(73, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:20:39'),
(74, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:22:10'),
(75, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:22:24'),
(76, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:22:44'),
(77, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:23:34'),
(78, 'MODIFICACIÓN', 'PARTIDAS', 'EL USUARIO 1 MODIFICÓ UNA PARTIDA', 1, '2023-03-03', '12:24:23'),
(79, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:24:40'),
(80, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:24:55'),
(81, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:25:17'),
(82, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:25:34'),
(83, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:25:45'),
(84, 'CREACIÓN', 'PARTIDAS', 'EL USUARIO 1 REGISTRO UNA PARTIDA', 1, '2023-03-03', '12:25:58'),
(85, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-03', '12:27:59'),
(86, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-03', '12:29:08'),
(87, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-03', '12:29:40'),
(88, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-03', '12:32:14'),
(89, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-03', '13:19:28'),
(90, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-03', '13:44:20'),
(91, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-03', '13:44:55'),
(92, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-03', '13:45:44'),
(93, 'CREACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 REGISTRO UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-03', '13:46:24'),
(94, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-03-03', '13:48:36'),
(95, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-03-03', '13:50:09'),
(96, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-03-03', '13:51:22'),
(97, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-03-03', '13:53:00'),
(98, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-03-03', '13:55:39'),
(99, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-03-03', '13:58:11'),
(100, 'CREACIÓN', 'USUARIOS', 'EL USUARIO 1 REGISTRO UN USUARIO', 1, '2023-03-03', '13:59:59'),
(101, 'CREACIÓN', 'FORMULARIO CUATRO', 'EL USUARIO 14 REGISTRO UN FORMULARIO CUATRO', 14, '2023-03-03', '14:14:22'),
(102, 'CREACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 14 REGISTRO UN DETALLE FORMULARIO CUATRO', 14, '2023-03-03', '14:26:18'),
(103, 'MODIFICACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 14 MODIFICÓ UN DETALLE FORMULARIO CUATRO', 14, '2023-03-03', '14:35:40'),
(104, 'MODIFICACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 14 MODIFICÓ UN DETALLE FORMULARIO CUATRO', 14, '2023-03-03', '14:38:42'),
(105, 'CREACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 14 REGISTRO UNA MEMORIA DE CÁLCULO', 14, '2023-03-03', '14:45:53'),
(106, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 14 MODIFICÓ UNA MEMORIA DE CÁLCULO', 14, '2023-03-03', '14:50:03'),
(107, 'CREACIÓN', 'PERSONAL', 'EL USUARIO 1 REGISTRO UN NUEVO PERSONAL', 1, '2023-03-03', '14:54:20'),
(108, 'CREACIÓN', 'PERSONAL', 'EL USUARIO 1 REGISTRO UN NUEVO PERSONAL', 1, '2023-03-03', '14:55:20'),
(109, 'CREACIÓN', 'PERSONAL', 'EL USUARIO 1 REGISTRO UN NUEVO PERSONAL', 1, '2023-03-03', '14:55:51'),
(110, 'CREACIÓN', 'PERSONAL', 'EL USUARIO 1 REGISTRO UN NUEVO PERSONAL', 1, '2023-03-03', '14:57:31'),
(111, 'CREACIÓN', 'SUBDIRECCIONES', 'EL USUARIO 1 REGISTRO UNA SUBDIRECCIÓN', 1, '2023-03-03', '15:00:07'),
(112, 'CREACIÓN', 'SUBDIRECCIONES', 'EL USUARIO 1 REGISTRO UNA SUBDIRECCIÓN', 1, '2023-03-03', '15:00:16'),
(113, 'CREACIÓN', 'SUBDIRECCIONES', 'EL USUARIO 1 REGISTRO UNA SUBDIRECCIÓN', 1, '2023-03-03', '15:00:28'),
(114, 'MODIFICACIÓN', 'VERIFICACIÓN DE LA ACTIVDAD POA', 'EL USUARIO 1 MODIFICÓ LA VERIFICACIÓN DE LA ACTIVDAD POA', 1, '2023-03-06', '09:14:41'),
(115, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '09:44:15'),
(116, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '09:44:50'),
(117, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '11:05:25'),
(118, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '12:32:16'),
(119, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '12:42:15'),
(120, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '12:46:05'),
(121, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '12:46:49'),
(122, 'MODIFICACIÓN', 'DETALLE FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN DETALLE FORMULARIO CUATRO', 1, '2023-03-06', '12:47:37'),
(123, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '15:04:48'),
(124, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '15:05:24'),
(125, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '15:07:06'),
(126, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '15:11:31'),
(127, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '15:13:06'),
(128, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '15:14:49'),
(129, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-06', '15:15:25'),
(130, 'CREACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REGISTRO UNA CERTIFICACIÓN POA', 1, '2023-03-06', '15:37:34'),
(131, 'MODIFICACIÓN', 'CONFIGURACIÓN', 'EL USUARIO 1 MODIFICÓ LA CONFIGURACIÓN DEL SISTEMA', 1, '2023-03-10', '12:40:05'),
(132, 'CREACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REGISTRO UNA CERTIFICACIÓN POA', 1, '2023-03-13', '11:28:25'),
(133, 'CREACIÓN', 'PERSONAL', 'EL USUARIO 1 REGISTRO UN NUEVO PERSONAL', 1, '2023-03-13', '11:49:42'),
(134, 'CREACIÓN', 'PERSONAL', 'EL USUARIO 1 REGISTRO UN NUEVO PERSONAL', 1, '2023-03-13', '11:50:54'),
(135, 'CREACIÓN', 'PERSONAL', 'EL USUARIO 1 REGISTRO UN NUEVO PERSONAL', 1, '2023-03-13', '11:51:38'),
(136, 'CREACIÓN', 'PERSONAL', 'EL USUARIO 1 REGISTRO UN NUEVO PERSONAL', 1, '2023-03-13', '11:52:46'),
(137, 'CREACIÓN', 'PERSONAL', 'EL USUARIO 1 REGISTRO UN NUEVO PERSONAL', 1, '2023-03-13', '11:53:33'),
(138, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-13', '12:26:25'),
(139, 'MODIFICACIÓN', 'MEMORIA DE CÁLCULO', 'EL USUARIO 1 MODIFICÓ UNA MEMORIA DE CÁLCULO', 1, '2023-03-13', '12:40:52'),
(140, 'MODIFICACIÓN', 'CONFIGURACIÓN', 'EL USUARIO 1 MODIFICÓ LA CONFIGURACIÓN DEL SISTEMA', 1, '2023-03-13', '14:21:43'),
(141, 'MODIFICACIÓN', 'CONFIGURACIÓN', 'EL USUARIO 1 MODIFICÓ LA CONFIGURACIÓN DEL SISTEMA', 1, '2023-03-13', '14:22:27'),
(142, 'MODIFICACIÓN', 'FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN FORMULARIO CUATRO', 1, '2023-03-13', '15:00:12'),
(143, 'MODIFICACIÓN', 'FORMULARIO CUATRO', 'EL USUARIO 1 MODIFICÓ UN FORMULARIO CUATRO', 1, '2023-03-13', '15:00:24'),
(144, 'CREACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REGISTRO UNA CERTIFICACIÓN POA', 1, '2023-03-13', '15:06:16'),
(145, 'MODIFICACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 MODIFICÓ EL ESTADO DE UNA CERTIFICACIÓN POA A APROBADO', 1, '2023-03-13', '15:06:59'),
(146, 'CREACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REGISTRO UNA CERTIFICACIÓN POA', 1, '2023-03-13', '15:16:26'),
(147, 'MODIFICACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 MODIFICÓ EL ESTADO DE UNA CERTIFICACIÓN POA A APROBADO', 1, '2023-03-13', '15:16:31'),
(148, 'MODIFICACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 MODIFICÓ UNA CERTIFICACIÓN POA', 1, '2023-03-13', '15:18:25'),
(149, 'ANULACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 ANULÓ UNA CERTIFICACIÓN POA', 1, '2023-03-13', '15:20:33'),
(150, 'ACTIVACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REACTIVÓ UNA CERTIFICACIÓN POA', 1, '2023-03-13', '15:20:55'),
(151, 'ANULACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 ANULÓ UNA CERTIFICACIÓN POA', 1, '2023-03-13', '15:21:51'),
(152, 'CREACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REGISTRO UNA CERTIFICACIÓN POA', 1, '2023-03-13', '17:59:39'),
(153, 'MODIFICACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 MODIFICÓ UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-14', '09:01:23'),
(154, 'MODIFICACIÓN', 'UNIDADES ORGANIZACIONALES', 'EL USUARIO 1 MODIFICÓ UNA UNIDAD ORGANIZACIONAL', 1, '2023-03-14', '09:02:34'),
(155, 'MODIFICACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 MODIFICÓ UNA CERTIFICACIÓN POA', 1, '2023-03-15', '19:20:24'),
(156, 'MODIFICACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 MODIFICÓ UNA CERTIFICACIÓN POA', 1, '2023-03-15', '19:54:38'),
(157, 'MODIFICACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 MODIFICÓ UNA CERTIFICACIÓN POA', 1, '2023-03-15', '19:56:29'),
(158, 'CREACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REGISTRO UNA CERTIFICACIÓN POA', 1, '2023-03-16', '09:56:12'),
(159, 'MODIFICACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 MODIFICÓ EL ESTADO DE UNA CERTIFICACIÓN POA A APROBADO', 1, '2023-03-16', '10:22:34'),
(160, 'ANULACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 ANULÓ UNA CERTIFICACIÓN POA', 1, '2023-03-16', '10:23:35'),
(161, 'ACTIVACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REACTIVÓ UNA CERTIFICACIÓN POA', 1, '2023-03-16', '10:23:38'),
(162, 'ANULACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 ANULÓ UNA CERTIFICACIÓN POA', 1, '2023-03-16', '10:26:03'),
(163, 'ACTIVACIÓN', 'CERTIFICACIÓN POA', 'EL USUARIO 1 REACTIVÓ UNA CERTIFICACIÓN POA', 1, '2023-03-16', '10:26:06'),
(164, 'MODIFICACIÓN', 'CONFIGURACIÓN', 'EL USUARIO 1 MODIFICÓ LA CONFIGURACIÓN DEL SISTEMA', 1, '2023-03-16', '10:42:25');

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
(1, 1, '79842.00', '2040.50', '9566.00', '1613.00', '4230.00', '4882.00', '1298.50', '4230.00', '7850.00', '28392.00', '2597.00', '5273.00', '7870.00', '79842.00', '2023-03-03', '2023-03-03 18:45:53', '2023-03-13 16:40:52');

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
(1, 1, 1, 1, '0.00', '2023-03-03', '2023-03-03 18:45:53', '2023-03-13 16:40:52'),
(2, 1, 2, 2, '0.00', '2023-03-03', '2023-03-03 18:50:03', '2023-03-13 16:40:52'),
(3, 1, 2, 3, '0.00', '2023-03-06', '2023-03-06 13:44:15', '2023-03-13 16:40:52'),
(4, 1, 2, 4, '0.00', '2023-03-06', '2023-03-06 16:32:16', '2023-03-13 16:40:52'),
(7, 1, 2, 5, '0.00', '2023-03-13', '2023-03-13 16:40:52', '2023-03-13 16:40:52');

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
(1, '1', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 18, '22110', 'PASAJES AL INTERIOR DEL PAÍS', '1', 'PASAJES', 2.00, 'PASAJE', '1242.00', '2484.00', 'PASAJES PARA LOS VIAJES PARA SOCIALIZACIÓN DEL PLAN ESTRATÉGICO INSTITUCIONAL 2021 -2025 DE LA ASUSS', NULL, NULL, '1242.00', '1242.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2484.00', '2023-03-03 18:45:53', '2023-03-03 18:45:53'),
(2, '1', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '2', 'PASAJES', 3.00, 'DÍA', '371.00', '1113.00', 'VIÁTICOS PARA LOS VIAJES PARA SOCIALIZACIÓN DEL PLAN ESTRATÉGICO INSTITUCIONAL 2021 -2025 DE LA ASUSS', NULL, '742.00', '371.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1113.00', '2023-03-03 18:45:53', '2023-03-03 18:45:53'),
(3, '2', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 18, '22110', 'PASAJES AL INTERIOR DEL PAÍS', '3', 'PASAJE', 1.00, 'PASAJE', '772.00', '772.00', 'PAGO DE PASAJES PARA LA REALIZACIÓN DEL INFORME DE GESTIÓN DE 2022', NULL, '772.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '772.00', '2023-03-03 18:50:03', '2023-03-03 18:50:03'),
(4, '2', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '4', 'VIATICOS', 2.00, 'DÍA', '371.00', '742.00', 'PAGO DE VIÁTICOS POR VIAJES PARA LA REALIZACIÓN DEL INFORME DE GESTIÓN DE 2022', '742.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '742.00', '2023-03-03 18:50:03', '2023-03-03 18:50:03'),
(5, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 18, '22110', 'PASAJES AL INTERIOR DEL PAÍS', '5', 'PASAJE', 4.00, 'PASAJE', '1220.50', '4882.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN AL PRIMER TRIMESTRE DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, '4882.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4882.00', '2023-03-06 13:44:15', '2023-03-06 13:44:15'),
(6, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '6', 'VIATICOS', 7.00, 'DÍA', '371.00', '2597.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN AL PRIMER TRIMESTRE DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, '2597.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2597.00', '2023-03-06 13:44:15', '2023-03-06 13:44:15'),
(7, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '7', 'VIATICOS', 1.00, 'DIA', '391.00', '391.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN AL PRIMER TRIMESTRE DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, '391.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '391.00', '2023-03-06 13:44:15', '2023-03-06 13:44:15'),
(8, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 18, '22110', 'PASAJES AL INTERIOR DEL PAÍS', '8', 'PASAJES', 4.00, 'PASAJE', '1220.50', '4882.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN AL SEGUNDO TRIMESTRE DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4882.00', NULL, NULL, NULL, NULL, '4882.00', '2023-03-06 15:05:25', '2023-03-06 15:05:25'),
(9, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '9', 'VIATICOS', 7.00, 'DÍA', '371.00', '2597.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN AL SEGUNDO TRIMESTRE DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, NULL, NULL, '2597.00', NULL, NULL, NULL, NULL, NULL, '2597.00', '2023-03-06 15:05:25', '2023-03-06 15:05:25'),
(10, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '10', 'VIATICOS', 1.00, 'DÍA', '391.00', '391.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN AL SEGUNDO TRIMESTRE DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, NULL, NULL, '391.00', NULL, NULL, NULL, NULL, NULL, '391.00', '2023-03-06 15:05:25', '2023-03-06 15:05:25'),
(11, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 18, '22110', 'PASAJES AL INTERIOR DEL PAÍS', '11', 'PASAJE', 4.00, 'PASAJE', '1220.50', '4882.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN DEL TERCER TRIMESTRE DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4882.00', NULL, '4882.00', '2023-03-06 15:05:25', '2023-03-06 15:05:25'),
(12, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '12', 'VIATICOS', 7.00, 'DÍA', '371.00', '2597.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN DEL TERCER TRIMESTRE DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2597.00', NULL, NULL, '2597.00', '2023-03-06 15:05:25', '2023-03-06 15:05:25'),
(13, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '13', 'VIATICO', 1.00, 'DÍA', '391.00', '391.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN DEL TERCER TRIMESTRE DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '391.00', NULL, '391.00', '2023-03-06 15:05:25', '2023-03-06 15:05:25'),
(14, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 18, '22110', 'PASAJES AL INTERIOR DEL PAÍS', '14', 'PASAJE', 4.00, 'PASAJE', '1220.50', '4882.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN FINAL DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4882.00', '4882.00', '2023-03-06 15:05:25', '2023-03-06 15:05:25'),
(15, '3', '01', '01', '007', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '15', 'VIATICO', 7.00, 'DÍA', '371.00', '2597.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN FINAL DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2597.00', '2597.00', '2023-03-06 15:05:25', '2023-03-06 15:05:25'),
(16, '3', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '16', 'VIATICO', 1.00, 'DÍA', '391.00', '391.00', 'PASAJES Y VIÁTICOS PARA VIAJES DE SEGUIMIENTO Y EVALUACIÓN FINAL DEL PLAN OPERATIVO ANUAL DE LA GESTIÓN 2023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '391.00', '391.00', '2023-03-06 15:05:25', '2023-03-06 15:05:25'),
(17, '4', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 18, '22110', 'PASAJES AL INTERIOR DEL PAÍS', '17', 'PASAJE', 2.00, 'PASAJE', '1242.00', '2484.00', 'PASAJES Y VIÁTICOS PARA LAS GESTIONES DE LAS MODIFICACIONES DEL PLAN OPERATIVO ANUAL 2023', NULL, '1242.00', NULL, NULL, NULL, NULL, '1242.00', NULL, NULL, NULL, NULL, NULL, '2484.00', '2023-03-06 16:32:16', '2023-03-06 16:32:16'),
(18, '5', '01', '01', '07', 'NACIONAL', 'TECNICO', 4, '11600', 'ASIGNACIONES FAMILIARES', '18', 'ASIGANCIONES', 4.00, 'UNIDADES', '6.00', '24.00', 'ASDAD', NULL, NULL, NULL, NULL, '24.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '24.00', '2023-03-06 19:04:48', '2023-03-06 19:04:48'),
(20, '6', '01', '01', '07', 'NACIONAL', 'ASDASSDADSASD', 1, '11220', 'BONO DE ANTIGÛEDAD', '19', 'ASDASDADA', 4.00, 'UNIDAD', '10.00', '40.00', 'ASDASD', NULL, NULL, NULL, NULL, '40.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '40.00', '2023-03-06 19:14:49', '2023-03-06 19:14:49'),
(21, '4', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '18', 'VIATICOS', 7.00, 'DÍA', '371.00', '2597.00', 'PASAJES Y VIÁTICOS PARA LAS GESTIONES DE LAS MODIFICACIONES DEL PLAN OPERATIVO ANUAL 2023', '1298.50', NULL, NULL, NULL, NULL, '1298.50', NULL, NULL, NULL, NULL, NULL, NULL, '2597.00', '2023-03-13 16:26:25', '2023-03-13 16:26:25'),
(22, '7', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 18, '22110', 'PASAJES AL INTERIOR DEL PAÍS', '19', 'PASAJE', 4.00, 'PASAJE', '1269.00', '5076.00', 'PASAJES Y VIÁTICOS POR VIAJES PARA LAS GESTIONES DE LA FORMULACIÓN DEL PLAN OPERATIVO ANUAL 2024 DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5076.00', NULL, NULL, NULL, '5076.00', '2023-03-13 16:40:52', '2023-03-13 16:40:52'),
(23, '7', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '20', 'VIATICOS', 8.00, 'DÍA', '371.00', '2968.00', 'PASAJES Y VIÁTICOS POR VIAJES PARA LAS GESTIÓNES DE LA FORMULACIÓN DEL PLAN OPERATIVO ANUAL 2024 DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2968.00', NULL, NULL, NULL, NULL, '2968.00', '2023-03-13 16:40:52', '2023-03-13 16:40:52'),
(24, '7', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 48, '31120', 'GASTOS POR ALIMENTACIÓN Y OTROS SIMILARES', '21', 'GASTOS', 820.00, 'REFRIGERIO', '25.00', '20500.00', 'GASTOS DE LOGÍSTICA PARA LA ELABORACIÓN DEL PLAN OPERATIVO ANUAL 2024 DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20500.00', NULL, NULL, NULL, '20500.00', '2023-03-13 16:40:52', '2023-03-13 16:40:52'),
(25, '7', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 38, '25600', 'SERVICIOS DE IMPRENTA, FOTOCOPIADO Y FOTOGRÁFICOS', '22', 'IMPRENTA', 2000.00, 'COPIA', '0.20', '400.00', 'GASTOS DE LOGÍSTICA PARA LA ELABORACIÓN DEL PLAN OPERATIVO ANUAL 2024 DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '400.00', NULL, NULL, NULL, '400.00', '2023-03-13 16:40:52', '2023-03-13 16:40:52'),
(26, '7', '01', '01', '07', 'NACIONAL', 'JEFE DE PLANIFICACIÓN ESTRATÉGICA, PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA Y EL AUXILIAR ADMINISTRATIVO', 27, '23400', 'OTROS ALQUILERES', '23', 'ALQUILERES', 2.00, 'SERVICIO', '4613.00', '9226.00', 'GASTOS DE LOGÍSTICA PARA LA ELABORACIÓN DEL PLAN OPERATIVO ANUAL 2024 DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', NULL, '6810.00', NULL, NULL, NULL, NULL, NULL, NULL, '2416.00', NULL, NULL, NULL, '9226.00', '2023-03-13 16:40:52', '2023-03-13 16:40:52');

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
(1, '2023_03_15_162255_create_certificacion_detalles_table', 1);

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
(1, 1, NULL, '4.1', 'SOCIALIZACIÓN  DE PLANES DE MEDIANO PLAZO DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO.', '2023-03-03 18:26:17', '2023-03-03 18:26:17'),
(2, 1, NULL, '4.2', 'FORMULACIÓN, SEGUIMIENTO, EVALUACIÓN Y MODIFICACIÓN DEL PLAN OPERATIVO ANUAL DE LA AUTORIDAD DE SUPERVISIÓN DE LA SEGURIDAD SOCIAL DE CORTO PLAZO.', '2023-03-03 18:35:40', '2023-03-03 18:35:40'),
(3, 1, NULL, '4.3', 'ACTUALIZACIÓN DEL MANUAL DE  ORGANIZACIÓN Y FUNCIONES Y REVISIÓN, VALIDACIÓN DEL MANUAL DE PROCESOS Y PROCEDIMIENTOS EN COORDINACIÓN CON LAS UNIDADES ORGANIZACIONALES DE LA AUTORIDAD DE SUPERVISIÓN DELA SEGURIDAD SOCIAL DE CORTO PLAZO', '2023-03-03 18:38:42', '2023-03-03 18:38:42');

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
(1, '11220', 'BONO DE ANTIGÛEDAD', '2023-03-03 14:42:37', '2023-03-03 14:42:37'),
(2, '11310', 'BONO DE FRONTERA', '2023-03-03 14:43:00', '2023-03-03 14:43:00'),
(3, '11400', 'AGUINALDOS', '2023-03-03 14:44:00', '2023-03-03 14:44:00'),
(4, '11600', 'ASIGNACIONES FAMILIARES', '2023-03-03 14:44:27', '2023-03-03 14:44:27'),
(5, '11700', 'SUELDOS', '2023-03-03 14:44:48', '2023-03-03 14:44:48'),
(6, '11920', 'VACACIONES NO UTILIZADAS', '2023-03-03 14:45:02', '2023-03-03 14:45:02'),
(7, '11940', 'SUPLENCIAS', '2023-03-03 14:45:37', '2023-03-03 14:45:37'),
(8, '12100', 'PERSONAL EVENTUAL', '2023-03-03 14:45:59', '2023-03-03 14:45:59'),
(9, '13110', 'RÉGIMEN DE CORTO PLAZO (SALUD)', '2023-03-03 14:46:42', '2023-03-03 14:46:42'),
(10, '13120', 'PRIMA DE RIESGO PROFESIONAL RÉGIMEN DE LARGO PLAZO', '2023-03-03 14:47:08', '2023-03-03 14:47:08'),
(11, '13131', 'APORTE PATRONAL SOLIDARIO 3%', '2023-03-03 14:47:40', '2023-03-03 14:47:40'),
(12, '13200', 'APORTE PATRONAL PARA VIVIENDA', '2023-03-03 14:48:12', '2023-03-03 14:48:12'),
(13, '21100', 'COMUNICACIONES', '2023-03-03 14:51:45', '2023-03-03 14:51:45'),
(14, '21200', 'ENERGÍA ELÉCTRICA', '2023-03-03 14:52:31', '2023-03-03 14:52:31'),
(15, '21300', 'AGUA', '2023-03-03 14:53:33', '2023-03-03 14:53:33'),
(16, '21400', 'TELEFONÍA', '2023-03-03 14:53:57', '2023-03-03 14:53:57'),
(17, '21600', 'INTERNET', '2023-03-03 14:54:26', '2023-03-03 14:54:26'),
(18, '22110', 'PASAJES AL INTERIOR DEL PAÍS', '2023-03-03 14:54:50', '2023-03-03 14:54:50'),
(19, '22120', 'PASAJES AL EXTERIOR DEL PAÍS', '2023-03-03 14:56:23', '2023-03-03 14:56:23'),
(20, '22210', 'VIÁTICOS POR VIAJES AL INTERIOR DEL PAÍS', '2023-03-03 14:59:03', '2023-03-03 14:59:03'),
(21, '22220', 'VIÁTICOS POR VIAJES AL EXTERIOR DEL PAÍS', '2023-03-03 14:59:33', '2023-03-03 14:59:33'),
(22, '22300', 'FLETES Y ALMACENAMIENTO', '2023-03-03 15:00:24', '2023-03-03 15:00:24'),
(23, '22500', 'SEGUROS', '2023-03-03 15:02:09', '2023-03-03 15:02:09'),
(24, '22600', 'TRANSPORTE DE PERSONAL', '2023-03-03 15:03:22', '2023-03-03 15:03:22'),
(25, '23100', 'ALQUILER DE INMUEBLES', '2023-03-03 15:28:04', '2023-03-03 15:28:04'),
(26, '23200', 'ALQUILER DE EQUIPOS Y MAQUINARIAS', '2023-03-03 15:28:28', '2023-03-03 15:28:28'),
(27, '23400', 'OTROS ALQUILERES', '2023-03-03 15:29:25', '2023-03-03 15:29:25'),
(28, '24110', 'MANTENIMIENTO Y REPARACIÓN DE INMUEBLES', '2023-03-03 15:29:50', '2023-03-03 15:29:50'),
(29, '24120', 'MANTENIMIENTO Y REPARACIÓN DE VEHÍCULOS, MAQUINARIA Y EQUIPOS', '2023-03-03 15:30:21', '2023-03-03 15:30:21'),
(30, '24130', 'MANTENIMIENTO Y REPARACIÓN DE MUEBLES Y ENSERES', '2023-03-03 15:31:46', '2023-03-03 15:31:46'),
(31, '24300', 'OTROS GASTOS POR CONCEPTO DE INSTALACIÓN, MANTENIMIENTO Y REPARACIÓN', '2023-03-03 15:32:10', '2023-03-03 15:32:10'),
(32, '25120', 'GASTOS ESPECIALIZADOS POR ATENCIÓN MÉDICA Y OTROS', '2023-03-03 15:32:26', '2023-03-03 15:32:26'),
(33, '25210', 'CONSULTORÍAS POR PRODUCTO', '2023-03-03 15:32:57', '2023-03-03 15:32:57'),
(34, '25220', 'CONSULTORES INDIVIDUALES DE LÍNEA', '2023-03-03 15:33:17', '2023-03-03 15:33:17'),
(35, '25300', 'COMISIONES Y GASTOS BANCARIOS', '2023-03-03 15:33:41', '2023-03-03 15:33:41'),
(36, '25400', 'LAVANDERÍA, LIMPIEZA E HIGIENE', '2023-03-03 15:40:21', '2023-03-03 15:40:21'),
(37, '25500', 'PUBLICIDAD', '2023-03-03 15:40:40', '2023-03-03 15:40:40'),
(38, '25600', 'SERVICIOS DE IMPRENTA, FOTOCOPIADO Y FOTOGRÁFICOS', '2023-03-03 15:41:07', '2023-03-03 15:41:07'),
(39, '25700', 'CAPACITACIÓN DEL PERSONAL', '2023-03-03 15:41:34', '2023-03-03 15:41:34'),
(40, '25900', 'SERVICIOS MANUALES', '2023-03-03 15:42:02', '2023-03-03 15:42:02'),
(41, '26200', 'GASTOS JUDICIALES', '2023-03-03 15:42:24', '2023-03-03 15:42:24'),
(42, '26300', 'DERECHOS SOBRE BIENES INTANGIBLES', '2023-03-03 15:43:03', '2023-03-03 15:43:03'),
(43, '26610', 'SERVICIOS PÚBLICOS', '2023-03-03 15:44:13', '2023-03-03 15:44:13'),
(44, '26910', 'GASTOS DE REPRESENTACIÓN', '2023-03-03 15:44:47', '2023-03-03 15:44:47'),
(45, '26930', 'PAGO POR TRABAJOS DIRIGIDOS Y PASANTÍAS', '2023-03-03 15:45:07', '2023-03-03 15:45:07'),
(46, '26990', 'OTROS', '2023-03-03 15:45:31', '2023-03-03 15:45:31'),
(47, '31110', 'GASTOS POR REFRIGERIOS AL PERSONAL PERMANENTE, EVENTUAL Y CONSULTORES INDIVIDUALES DE LÍNEA DE LAS INSTITUCIONES PÚBLICAS', '2023-03-03 15:45:55', '2023-03-03 15:45:55'),
(48, '31120', 'GASTOS POR ALIMENTACIÓN Y OTROS SIMILARES', '2023-03-03 15:46:23', '2023-03-03 15:46:23'),
(49, '32100', 'PAPEL', '2023-03-03 15:46:44', '2023-03-03 15:46:44'),
(50, '32200', 'PRODUCTOS DE ARTES GRÁFICAS', '2023-03-03 15:47:06', '2023-03-03 15:47:06'),
(51, '32300', 'LIBROS, MANUALES Y REVISTAS', '2023-03-03 15:47:39', '2023-03-03 15:47:39'),
(52, '32500', 'PERIÓDICOS Y BOLETINES', '2023-03-03 15:48:16', '2023-03-03 15:48:16'),
(53, '33100', 'HILADOS, TELAS, FIBRAS Y ALGODÓN', '2023-03-03 15:48:41', '2023-03-03 15:48:41'),
(54, '33200', 'CONFECCIONES TEXTILES', '2023-03-03 15:48:53', '2023-03-03 15:48:53'),
(55, '33300', 'PRENDAS DE VESTIR', '2023-03-03 15:49:22', '2023-03-03 15:49:22'),
(56, '34110', 'COMBUSTIBLES, LUBRICANTES Y DERIVADOS PARA CONSUMO', '2023-03-03 15:49:52', '2023-03-03 15:49:52'),
(57, '34200', 'PRODUCTOS QUÍMICOS Y FARMACÉUTICOS', '2023-03-03 15:50:07', '2023-03-03 15:50:07'),
(58, '34300', 'LLANTAS Y NEUMÁTICOS', '2023-03-03 15:50:36', '2023-03-03 15:50:36'),
(59, '34400', 'PRODUCTOS DE CUERO Y CAUCHO', '2023-03-03 15:50:58', '2023-03-03 15:50:58'),
(60, '34500', 'PRODUCTOS DE MINERALES NO METÁLICOS Y PLÁSTICOS', '2023-03-03 15:53:43', '2023-03-03 15:53:43'),
(61, '34600', 'PRODUCTOS METÁLICOS', '2023-03-03 15:53:58', '2023-03-03 15:53:58'),
(62, '34800', 'HERRAMIENTAS MENORES', '2023-03-03 15:54:10', '2023-03-03 15:54:10'),
(63, '39100', 'MATERIAL DE LIMPIEZA E HIGIENE', '2023-03-03 15:54:25', '2023-03-03 15:54:25'),
(64, '39300', 'UTENSILIOS DE COCINA Y COMEDOR', '2023-03-03 16:00:09', '2023-03-03 16:00:09'),
(65, '39400', 'INSTRUMENTAL MENOR MÉDICO-QUIRÚRGICO', '2023-03-03 16:17:20', '2023-03-03 16:17:20'),
(66, '36500', 'UTILES DE ESCRITORIO Y OFICINA', '2023-03-03 16:17:41', '2023-03-03 16:17:41'),
(67, '39600', 'UTILES EDUCACIONALES, CULTURALES Y DE CAPACITACIÓN', '2023-03-03 16:18:06', '2023-03-03 16:18:06'),
(68, '39700', 'UTILES Y MATERIALES ELÉCTRICOS', '2023-03-03 16:18:32', '2023-03-03 16:18:32'),
(69, '39800', 'OTROS REPUESTOS Y ACCESORIOS', '2023-03-03 16:18:59', '2023-03-03 16:18:59'),
(70, '43110', 'EQUIPO DE OFICINA Y MUEBLES', '2023-03-03 16:19:25', '2023-03-03 16:19:25'),
(71, '43120', 'EQUIPO DE COMPUTACIÓN', '2023-03-03 16:19:45', '2023-03-03 16:19:45'),
(72, '43500', 'EQUIPO DE COMUNICACIÓN', '2023-03-03 16:20:13', '2023-03-03 16:20:13'),
(73, '43600', 'EQUIPO EDUCACIONAL Y RECREATIVO', '2023-03-03 16:20:39', '2023-03-03 16:20:39'),
(74, '43700', 'OTRA MAQUINARIA Y EQUIPO', '2023-03-03 16:22:10', '2023-03-03 16:22:10'),
(75, '57100', 'INCREMENTO DE CAJA Y BANCOS', '2023-03-03 16:22:24', '2023-03-03 16:22:24'),
(76, '66100', 'GASTOS DEVENGADOS NO PAGADOS POR SERVICIOS PERSONALES', '2023-03-03 16:22:44', '2023-03-03 16:22:44'),
(77, '66210', 'GASTOS DEVENGADOS NO PAGADOS POR SERVICIOS NO PERSONALES', '2023-03-03 16:23:34', '2023-03-03 16:24:23'),
(78, '66220', 'GASTOS DEVENGADOS NO PAGADOS POR MATERIALES Y SUMINISTROS', '2023-03-03 16:24:40', '2023-03-03 16:24:40'),
(79, '66400', 'GASTOS DEVENGADOS NO PAGADOS POR RETENCIONES', '2023-03-03 16:24:55', '2023-03-03 16:24:55'),
(80, '79100', 'TRANSFERENCIAS CORRIENTES A GOBIERNOS EXTRANJEROS Y ORGANISMOS INTERNACIONALES POR CUOTAS REGULARES', '2023-03-03 16:25:17', '2023-03-03 16:25:17'),
(81, '85100', 'TASAS', '2023-03-03 16:25:34', '2023-03-03 16:25:34'),
(82, '85400', 'MULTAS', '2023-03-03 16:25:45', '2023-03-03 16:25:45'),
(83, '85900', 'OTROS', '2023-03-03 16:25:58', '2023-03-03 16:25:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personals`
--

CREATE TABLE `personals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personals`
--

INSERT INTO `personals` (`id`, `nombre`, `paterno`, `materno`, `cargo`, `created_at`, `updated_at`) VALUES
(1, 'ALVARO ARIEL', 'QUINO', 'CONDE', 'AUXILIAR ADMINISTRATIVO', '2023-03-03 18:54:20', '2023-03-03 18:54:20'),
(2, 'ANA PAOLA', 'PEREZ', 'RAQUELA', 'SECRETARIA DE LA DIRECCION GENERAL EJECUTIVA', '2023-03-03 18:55:20', '2023-03-03 18:55:20'),
(3, 'JOSE LUIS', 'FABIO', 'CONDORI', 'MENSAJERO', '2023-03-03 18:55:51', '2023-03-03 18:55:51'),
(4, 'RUFINA', 'APAZA', 'CHOQUE', 'PROFESIONAL EN REAFILIACION Y DESAFILIACIÓN', '2023-03-03 18:57:31', '2023-03-03 18:57:31'),
(5, 'JOSE VICTOR', 'PATIÑO', 'DURAN', 'DIRECTOR GENERAL EJECUTIVO', '2023-03-13 15:49:42', '2023-03-13 15:49:42'),
(6, 'JAVIER', 'CALLE', 'QUISPE', 'PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA', '2023-03-13 15:50:54', '2023-03-13 15:50:54'),
(7, 'JUAN', 'KASAI', 'JANKKO', 'JEFE DE UNIDAD DE PLANIFICACIÓN ESTRATÉGICA', '2023-03-13 15:51:38', '2023-03-13 15:51:38'),
(8, 'AIDA XIMENA', 'BUSTILLOS', 'LIRA', 'TÉCNICO EN COMUNICACIÓN SOCIAL', '2023-03-13 15:52:46', '2023-03-13 15:52:46'),
(9, 'MARIA ALEJANDRA', 'DUARTE', 'VERA', 'PROFESIONAL EN TRANPARENCIA Y LUCHA CONTRA LA CORRUPCIÓN', '2023-03-13 15:53:33', '2023-03-13 15:53:33');

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
(1, 'UNIDAD DE GESTIÓN Y ANALISIS JURIDICO', '2023-03-03 19:00:07', '2023-03-03 19:00:07'),
(2, 'UNIDAD DE ASIGNACIONES FAMILIARES', '2023-03-03 19:00:16', '2023-03-03 19:00:16'),
(3, 'UNIDAD DE AFILIACIÓN, REAFILIACIÓN Y DESAFILIACIÓN', '2023-03-03 19:00:28', '2023-03-03 19:00:28');

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
(1, 'DIRECCIÓN JURÍDICA', '2023-03-03 16:27:59', '2023-03-03 16:27:59'),
(2, 'ÁREA DE COMUNICACIÓN SOCIAL', '2023-03-03 16:29:08', '2023-03-03 16:29:08'),
(3, 'ÁREA DE TRANSPARENCIA INSTITUCIONAL Y LUCHA CONTRA LA CORRUPCIÓN', '2023-03-03 16:29:40', '2023-03-14 13:02:34'),
(4, 'UNIDAD DE PLANIFICACIÓN ESTRATÉGICA', '2023-03-03 16:32:14', '2023-03-14 13:01:23'),
(5, 'UNIDAD  DE AUDITORIA INTERNA', '2023-03-03 17:19:28', '2023-03-03 17:19:28'),
(6, 'DIRECCIÓN ADMINISTRATIVA FINANCIERA', '2023-03-03 17:44:20', '2023-03-03 17:44:20'),
(7, 'DIRECCIÓN GENERAL EJECUTIVA', '2023-03-03 17:44:55', '2023-03-03 17:44:55'),
(8, 'DIRECCION TECNICA DE FISCALIZACIÓN Y CONTROL ADMINISTRATIVO FINANCIERO DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', '2023-03-03 17:45:44', '2023-03-03 17:45:44'),
(9, 'DIRECCION TECNICA DE FISCALIZACION Y CONTROL DE SERVICIOS DE SALUD', '2023-03-03 17:46:24', '2023-03-03 17:46:24');

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
(11, 'JOSE.PATIÑO', 'JOSE VICTOR', 'PATIÑO', 'DURAN', '8309601', 'CB', '2481201', 'DIRECTOR GENERAL EJECUTIVO', 'NACIONAL', 'DIRECTOR GENERAL EJECUTIVO', '', 7, 'MAE', 'default.png', '$2y$10$9rlL3im8pcDn7.W03vwed.nFnKgHWfsrvzVrhtx45GQP1RhB/PDoq', 1, '2023-03-03', '2023-03-03 17:48:36', '2023-03-03 17:48:36'),
(12, 'JUAN.KASAI', 'JUAN', 'KASAI', 'JANKO', '8309602', 'CH', '2481202', 'JEFE DE UNIDAD DE PLANIFICACIÓN ESTRATÉGICA', 'NACIONAL', 'JEFE DE UNIDAD', '', 4, 'JEFES DE UNIDAD', 'default.png', '$2y$10$uMVSG4SmjcP1xUmVpvvSK.f.Isdj5KQftJXMN2LH7aEAJsT0X0Bqe', 1, '2023-03-03', '2023-03-03 17:50:08', '2023-03-03 17:50:08'),
(13, 'JAVIER.CALLE', 'JAVIER', 'CALLE', 'QUISPE', '8309603', 'LP', '2481203', 'PROFESIONAL EN PLANIFICACIÓN ESTRATÉGICA', 'NACIONAL', 'PROFESIONAL III', '', 4, 'ENLACE', 'default.png', '$2y$10$ozPeroyxw4EOn0wDJYkyr.YT1Iu4EoQiB5tzbacmrurYrVCWFbdJe', 1, '2023-03-03', '2023-03-03 17:51:22', '2023-03-03 17:51:22'),
(14, 'ALVARO.QUINO', 'ALVARO ARIEL', 'QUINO', 'CONDE', '8309604', 'LP', '2481204', 'AUXILIAR ADMINISTRATIVO', 'NACIONAL', 'AUXILIAR I', '', 4, 'ENLACE', 'default.png', '$2y$10$IHcMYhd1/uyYDnj7NAyWx.HOHy.Fz4XPsZvu7va3Lwr9keNO1lsli', 1, '2023-03-03', '2023-03-03 17:53:00', '2023-03-03 17:53:00'),
(15, 'AIDA.BUSTILLOS', 'AIDA XIMENA', 'BUSTILLOS', 'LIRA', '8309605', 'LP', '8309605', 'TÉCNICO EN COMUNICACIÓN SOCIAL', 'NACIONAL', 'TECNICO II', '', 2, 'ENLACE', 'default.png', '$2y$10$2wGvET8lDS6ho0EjsOQILOrUfqqRuKq0f48mEH5/PvLSv9JzvTNJG', 1, '2023-03-03', '2023-03-03 17:55:39', '2023-03-03 17:55:39'),
(16, 'HENRY.ANGULO', 'HENRY ANIBAL', 'ANGULO', 'SERRANO', '8309606', 'LP', '8309606', 'RESPONSABLE DE ÁREA DE COMUNICACIÓN SOCIAL', 'NACIONAL', 'RESPONSABLE DE AREA', '', 2, 'JEFES DE ÁREAS', 'default.png', '$2y$10$4YntTSmbVkn.iabCVPE/N.jgi6TSGTmysyGZ72Dj3WOax1t7G8HeO', 1, '2023-03-03', '2023-03-03 17:58:11', '2023-03-03 17:58:11'),
(17, 'MARIA.DUARTE', 'MARIA ALEJANDRA', 'DUARTE', 'VERA', '8309607', 'LP', '2481207', 'PROFESIONAL EN TRANSPARENCIA INSTITUCIONAL Y LUCHA CONTRA LA CORRUPCIÓN', 'NACIONAL', 'PROFESIONAL III', '', 3, 'ENLACE', 'default.png', '$2y$10$y6mS/MkyZ.zCyHqhBq173u2BzTlAGlq/UNFShNO5/oirzjvJ/6oqu', 1, '2023-03-03', '2023-03-03 17:59:59', '2023-03-03 17:59:59');

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
(1, '2023', 'La actividad se encuentra en el Plan Operativo Anual de la gestión 2023 de la Autoridad de Supervisión de la Seguridad Social a Corto Plazo, aprobado mediante la Resolución Administrativa  N° 11 de 6 de Febrero de 2023.', NULL, '2023-03-06 13:14:41');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad_realizadas`
--
ALTER TABLE `actividad_realizadas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aprobacions`
--
ALTER TABLE `aprobacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aprobacions_unidad_id_foreign` (`unidad_id`);

--
-- Indices de la tabla `certificacions`
--
ALTER TABLE `certificacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificacions_formulario_id_foreign` (`formulario_id`),
  ADD KEY `certificacions_solicitante_id_foreign` (`solicitante_id`),
  ADD KEY `certificacions_superior_id_foreign` (`superior_id`);

--
-- Indices de la tabla `certificacion_detalles`
--
ALTER TABLE `certificacion_detalles`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `personals`
--
ALTER TABLE `personals`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `aprobacions`
--
ALTER TABLE `aprobacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `certificacions`
--
ALTER TABLE `certificacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `certificacion_detalles`
--
ALTER TABLE `certificacion_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `configuracion_modulos`
--
ALTER TABLE `configuracion_modulos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_formularios`
--
ALTER TABLE `detalle_formularios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_operacions`
--
ALTER TABLE `detalle_operacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `financieras`
--
ALTER TABLE `financieras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fisicos`
--
ALTER TABLE `fisicos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formulario_cinco`
--
ALTER TABLE `formulario_cinco`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `formulario_cuatro`
--
ALTER TABLE `formulario_cuatro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT de la tabla `memoria_calculos`
--
ALTER TABLE `memoria_calculos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `memoria_operacions`
--
ALTER TABLE `memoria_operacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `memoria_operacion_detalles`
--
ALTER TABLE `memoria_operacion_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `operacions`
--
ALTER TABLE `operacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `partidas`
--
ALTER TABLE `partidas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de la tabla `personals`
--
ALTER TABLE `personals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `semaforos`
--
ALTER TABLE `semaforos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subdireccions`
--
ALTER TABLE `subdireccions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `unidads`
--
ALTER TABLE `unidads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `verificacion_actividads`
--
ALTER TABLE `verificacion_actividads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aprobacions`
--
ALTER TABLE `aprobacions`
  ADD CONSTRAINT `aprobacions_unidad_id_foreign` FOREIGN KEY (`unidad_id`) REFERENCES `unidads` (`id`);

--
-- Filtros para la tabla `certificacions`
--
ALTER TABLE `certificacions`
  ADD CONSTRAINT `certificacions_formulario_id_foreign` FOREIGN KEY (`formulario_id`) REFERENCES `formulario_cuatro` (`id`),
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
