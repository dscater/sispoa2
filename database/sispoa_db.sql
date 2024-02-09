-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-02-2024 a las 01:31:06
-- Versión del servidor: 8.0.30
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
  `id` bigint UNSIGNED NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aprobacions`
--

CREATE TABLE `aprobacions` (
  `id` bigint UNSIGNED NOT NULL,
  `unidad_id` bigint UNSIGNED NOT NULL,
  `estado` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificacions`
--

CREATE TABLE `certificacions` (
  `id` bigint UNSIGNED NOT NULL,
  `formulario_id` bigint UNSIGNED NOT NULL,
  `poa_seleccionado` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `accion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mo_id` bigint UNSIGNED NOT NULL,
  `archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correlativo` bigint NOT NULL,
  `solicitante_id` bigint UNSIGNED NOT NULL,
  `superior_id` bigint UNSIGNED NOT NULL,
  `inicio` date NOT NULL,
  `final` date NOT NULL,
  `personal_designado` bigint UNSIGNED DEFAULT NULL,
  `departamento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `municipio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `anulado` int NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificacion_detalles`
--

CREATE TABLE `certificacion_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `certificacion_id` bigint UNSIGNED NOT NULL,
  `mo_id` bigint UNSIGNED NOT NULL,
  `mod_id` bigint UNSIGNED NOT NULL,
  `total_cantidad` double NOT NULL,
  `cantidad_usar` double NOT NULL,
  `saldo_cantidad` double NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `presupuesto_usarse` decimal(24,2) NOT NULL,
  `saldo_total` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `web` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `ciudad`, `dir`, `fono`, `web`, `actividad`, `correo`, `logo`, `logo2`, `created_at`, `updated_at`) VALUES
(1, 'INTEGRAL DE PLANIFICACIÓN Y PRESUPUESTO', 'SIPLAP', 'SIPLAP', 'LA PAZ', 'AV. 6 DE AGOSTO EDIF. DOS TORRES', '2152400', 'WWW.ASUSS.GOB.BO', 'AUTORIDAD DE SUPERVISION DE LA SEGURIDAD SOCIAL DE CORTO PLAZO', '', '1680305398_logo.png', '1680305628_logo2.jpg', NULL, '2023-03-31 23:33:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_modulos`
--

CREATE TABLE `configuracion_modulos` (
  `id` bigint UNSIGNED NOT NULL,
  `modulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `crear` int NOT NULL DEFAULT '1',
  `editar` int NOT NULL,
  `eliminar` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion_modulos`
--

INSERT INTO `configuracion_modulos` (`id`, `modulo`, `crear`, `editar`, `eliminar`, `created_at`, `updated_at`) VALUES
(1, 'FORMULARIO 4', 1, 1, 1, NULL, '2024-01-19 14:06:39'),
(2, 'DETALLE FORMULARIO 4', 1, 1, 1, NULL, '2023-04-12 20:35:51'),
(3, 'MEMORIA DE CÁLCULO', 1, 1, 1, NULL, '2023-01-25 02:51:48'),
(4, 'APROBAR FORMULARIOS', 1, 0, 1, NULL, '2023-01-29 15:23:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_formularios`
--

CREATE TABLE `detalle_formularios` (
  `id` bigint UNSIGNED NOT NULL,
  `formulario_id` bigint UNSIGNED NOT NULL,
  `formulario_seleccionado` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_operacions`
--

CREATE TABLE `detalle_operacions` (
  `id` bigint UNSIGNED NOT NULL,
  `operacion_id` bigint UNSIGNED NOT NULL,
  `ponderacion` double(8,2) NOT NULL,
  `resultado_esperado` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `medios_verificacion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_tarea` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `actividad_tarea` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_e` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_f` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_m` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_a` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_m` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_j` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tt_j` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tt_a` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tt_s` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ct_o` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ct_n` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ct_d` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inicio` date NOT NULL,
  `final` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `financieras`
--

CREATE TABLE `financieras` (
  `id` bigint UNSIGNED NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fisicos`
--

CREATE TABLE `fisicos` (
  `id` bigint UNSIGNED NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario_cinco`
--

CREATE TABLE `formulario_cinco` (
  `id` bigint UNSIGNED NOT NULL,
  `memoria_id` bigint UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario_cuatro`
--

CREATE TABLE `formulario_cuatro` (
  `id` bigint UNSIGNED NOT NULL,
  `codigo_pei` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resultado_institucional` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicador` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_poa` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion_corto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicador_proceso` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `linea_base` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `presupuesto` decimal(24,2) NOT NULL,
  `ponderacion` double(8,2) NOT NULL,
  `unidad_id` bigint UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `memoria_calculos`
--

CREATE TABLE `memoria_calculos` (
  `id` bigint UNSIGNED NOT NULL,
  `formulario_id` bigint UNSIGNED NOT NULL,
  `formulario_seleccionado` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `memoria_operacions`
--

CREATE TABLE `memoria_operacions` (
  `id` bigint UNSIGNED NOT NULL,
  `memoria_id` bigint UNSIGNED NOT NULL,
  `operacion_id` bigint UNSIGNED NOT NULL,
  `detalle_operacion_id` bigint UNSIGNED DEFAULT NULL,
  `ue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prog` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `act` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsable` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `justificacion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_operacion` decimal(24,2) NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `memoria_operacion_detalles`
--

CREATE TABLE `memoria_operacion_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `memoria_operacion_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prog` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `act` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lugar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsable` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `partida_id` bigint UNSIGNED NOT NULL,
  `partida` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_detallada` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cantidad` double(8,2) NOT NULL,
  `unidad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `justificacion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operacions`
--

CREATE TABLE `operacions` (
  `id` bigint UNSIGNED NOT NULL,
  `detalle_formulario_id` bigint UNSIGNED NOT NULL,
  `subdireccion_id` bigint UNSIGNED DEFAULT NULL,
  `codigo_operacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `operacion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ponderacion` double(8,2) NOT NULL,
  `resultado_esperado` text COLLATE utf8mb4_unicode_ci,
  `medios_verificacion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

CREATE TABLE `partidas` (
  `id` bigint UNSIGNED NOT NULL,
  `partida` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personals`
--

CREATE TABLE `personals` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semaforos`
--

CREATE TABLE `semaforos` (
  `id` bigint UNSIGNED NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subdireccions`
--

CREATE TABLE `subdireccions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidads`
--

CREATE TABLE `unidads` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cargo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lugar_trabajo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_puesto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unidad_id` bigint UNSIGNED DEFAULT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `acceso` int NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `fono`, `cargo`, `lugar_trabajo`, `descripcion_puesto`, `observacion`, `unidad_id`, `tipo`, `foto`, `password`, `acceso`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '', NULL, '', '', '', '', '', '', NULL, NULL, 'SUPER USUARIO', 'default.png', '$2y$10$cDSOdzTsMDQAfqcb6.WFtu40s.wmQ4Jl8poIwW69MSZnnedD3prKu', 1, '2022-10-13', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verificacion_actividads`
--

CREATE TABLE `verificacion_actividads` (
  `id` bigint UNSIGNED NOT NULL,
  `gestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `actividad` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `aprobacions`
--
ALTER TABLE `aprobacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `certificacions`
--
ALTER TABLE `certificacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `certificacion_detalles`
--
ALTER TABLE `certificacion_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `configuracion_modulos`
--
ALTER TABLE `configuracion_modulos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_formularios`
--
ALTER TABLE `detalle_formularios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_operacions`
--
ALTER TABLE `detalle_operacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `financieras`
--
ALTER TABLE `financieras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fisicos`
--
ALTER TABLE `fisicos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formulario_cinco`
--
ALTER TABLE `formulario_cinco`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formulario_cuatro`
--
ALTER TABLE `formulario_cuatro`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `memoria_calculos`
--
ALTER TABLE `memoria_calculos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `memoria_operacions`
--
ALTER TABLE `memoria_operacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `memoria_operacion_detalles`
--
ALTER TABLE `memoria_operacion_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `operacions`
--
ALTER TABLE `operacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partidas`
--
ALTER TABLE `partidas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personals`
--
ALTER TABLE `personals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `semaforos`
--
ALTER TABLE `semaforos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subdireccions`
--
ALTER TABLE `subdireccions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unidads`
--
ALTER TABLE `unidads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `verificacion_actividads`
--
ALTER TABLE `verificacion_actividads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
