-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2024 a las 22:53:38
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `situ_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id_area` int(11) NOT NULL,
  `nombreArea` varchar(100) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `fecha_actualizacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id_area`, `nombreArea`, `id_estado`, `fecha_registro`, `fecha_actualizacion`) VALUES
(20, 'Pecuaria', 1, '2024-07-03 07:40:27', '2024-07-03 07:41:13'),
(21, 'Agricola', 1, '2024-07-03 07:40:46', '2024-07-03 07:42:13'),
(22, 'Agroindustria', 1, '2024-07-03 07:41:04', '2024-07-03 07:42:19'),
(23, 'Gestion', 1, '2024-07-03 07:41:54', '2024-07-03 07:42:54'),
(24, 'Ambiental', 1, '2024-07-03 07:42:07', '2024-07-03 07:42:59'),
(25, 'Innovacion', 1, '2024-07-03 07:43:13', '2024-07-03 07:50:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id_cargo` int(11) NOT NULL,
  `tipo_cargo` varchar(255) NOT NULL,
  `estado` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id_cargo`, `tipo_cargo`, `estado`, `fecha_registro`) VALUES
(8, 'Instructor Tecnico ', 1, '2024-07-04 14:31:30'),
(9, 'Lider Agroindustria', 2, '2024-07-04 14:31:30'),
(10, 'Lider Sena Empresa', 1, '2024-07-04 14:32:11'),
(11, 'Lider Pecuario', 2, '2024-07-04 14:32:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_area_unidades`
--

CREATE TABLE `detalle_area_unidades` (
  `id_detalle_areauni` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_unidad` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `estado`) VALUES
(1, 'activo'),
(2, 'inactivo'),
(4, 'Suspendido'),
(5, 'Bloqueado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas`
--

CREATE TABLE `fichas` (
  `codigoFicha` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `inicio_formacion` date NOT NULL,
  `fin_formacion` date NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_estado_se` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fichas`
--

INSERT INTO `fichas` (`codigoFicha`, `id_programa`, `inicio_formacion`, `fin_formacion`, `id_estado`, `id_estado_se`) VALUES
(123456, 14, '2024-02-18', '2026-02-18', 1, 2),
(342301, 12, '2024-02-17', '2026-02-17', 1, 2),
(881201, 11, '2024-02-16', '2026-02-16', 1, 2),
(23012302, 5, '2024-02-13', '2026-02-13', 1, 2),
(23014502, 8, '2024-02-15', '2026-02-15', 1, 2),
(26013402, 4, '2024-02-12', '2026-02-12', 1, 2),
(1201561201, 7, '2024-02-14', '2026-02-14', 1, 2),
(2147483647, 11, '2024-07-13', '2024-07-20', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formatos`
--

CREATE TABLE `formatos` (
  `id_formato` int(11) NOT NULL,
  `nombreFormato` varchar(255) NOT NULL,
  `nombreFormatoMagnetico` varchar(255) NOT NULL,
  `estado` int(11) NOT NULL,
  `horario_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `formatos`
--

INSERT INTO `formatos` (`id_formato`, `nombreFormato`, `nombreFormatoMagnetico`, `estado`, `horario_registro`) VALUES
(5, 'Formato registro de area', 'formatoarea.csv', 1, '2024-06-20 08:12:33'),
(6, 'Formato registro de unidad', 'formatounidad.csv', 1, '2024-06-20 10:51:24'),
(7, 'Formato Registro de Programas de Formacion', 'formatoprogramas.csv', 1, '2024-06-20 12:25:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos_fallidos`
--

CREATE TABLE `intentos_fallidos` (
  `id_intentos` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `fecha_intento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_formacion`
--

CREATE TABLE `programas_formacion` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(200) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas_formacion`
--

INSERT INTO `programas_formacion` (`id_programa`, `nombre_programa`, `descripcion`, `id_estado`) VALUES
(4, 'Analisis y Desarrollo de Software', 'Esta es una descripción para un programa de formación relacionado con el analisis y desarrollo de software', 1),
(5, 'Gestion de Empresas Pecuarias', 'Esta es la descripcion de un programa relacionado con empresas pecuarias', 1),
(7, 'Gestion de Produccion Agricola', 'Esta es la descripcion de un programa relacionado con la produccion agricola', 1),
(8, 'Gestion Agroempresarial', 'descripcion corta del tecnologo gestion agroempresarial', 1),
(11, 'Produccion Ambiental', 'Esta es una descripcion relacionada con el programa de formacion produccion ambiental', 1),
(12, 'Gestion Ambiental', 'Esta es una descripcion relacionada con el programa de formacion Ambiental', 1),
(14, 'Gestion de Empresas Agropecuarias', 'esta es la descripcion de un programa de formacion relacionado con empresas agropecuarias', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `id` int(11) NOT NULL,
  `tipo_usuario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`id`, `tipo_usuario`) VALUES
(1, 'administrador'),
(2, 'aprendiz');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno_especial`
--

CREATE TABLE `turno_especial` (
  `id_turno_especial` int(11) NOT NULL,
  `id_ficha` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `horario_inicio` time NOT NULL,
  `horario_fin` time NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno_rutinario`
--

CREATE TABLE `turno_rutinario` (
  `id_turno_rutinario` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `horario_inicial` time NOT NULL,
  `horario_final` time NOT NULL,
  `estado` int(11) NOT NULL,
  `fallas` int(11) NOT NULL,
  `id_aprendiz` int(11) NOT NULL,
  `id_unidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad`
--

CREATE TABLE `unidad` (
  `id_unidad` int(11) NOT NULL,
  `nombre_unidad` varchar(200) NOT NULL,
  `id_area` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_finalizacion` time NOT NULL,
  `cantidad_aprendices` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `fecha_actualizacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidad`
--

INSERT INTO `unidad` (`id_unidad`, `nombre_unidad`, `id_area`, `hora_inicio`, `hora_finalizacion`, `cantidad_aprendices`, `id_estado`, `fecha_registro`, `fecha_actualizacion`) VALUES
(37, 'Avicultura', 20, '07:00:00', '16:00:00', 4, 1, '2024-07-03 10:35:44', '2024-07-03 10:40:40'),
(38, 'Mercasena 1', 22, '08:00:00', '11:00:00', 3, 1, '2024-07-03 10:35:44', '2024-07-03 10:49:12'),
(39, 'Caprinos', 20, '07:00:00', '16:00:00', 4, 1, '2024-07-03 10:49:21', '0000-00-00 00:00:00'),
(40, 'Mercasena 2', 22, '08:00:00', '11:00:00', 3, 1, '2024-07-03 10:49:21', '0000-00-00 00:00:00'),
(41, 'Lacteos', 20, '07:00:00', '16:00:00', 4, 1, '2024-07-03 11:38:35', '0000-00-00 00:00:00'),
(42, 'Panificacion', 22, '08:00:00', '11:00:00', 3, 1, '2024-07-03 11:38:35', '0000-00-00 00:00:00'),
(43, 'Fruhor', 20, '07:00:00', '16:00:00', 4, 1, '2024-07-03 11:40:02', '0000-00-00 00:00:00'),
(44, 'Carnicos', 22, '08:00:00', '11:00:00', 3, 1, '2024-07-03 11:40:02', '0000-00-00 00:00:00'),
(45, 'Zonas verdes', 20, '07:00:00', '16:00:00', 4, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'Laboratorio ambiental', 22, '08:00:00', '11:00:00', 3, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'Lote 1', 20, '07:00:00', '16:00:00', 4, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'Lote 2', 22, '08:00:00', '11:00:00', 3, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `documento` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `cargo_funcionario` varchar(150) DEFAULT NULL,
  `foto_data` varchar(255) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `sexo` varchar(100) DEFAULT NULL,
  `id_estado_se` int(11) DEFAULT NULL,
  `id_estado_trimestre` int(11) DEFAULT NULL,
  `id_ficha` int(11) DEFAULT NULL,
  `id_tipo_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`documento`, `nombres`, `apellidos`, `cargo_funcionario`, `foto_data`, `celular`, `password`, `sexo`, `id_estado_se`, `id_estado_trimestre`, `id_ficha`, `id_tipo_usuario`, `email`, `id_estado`, `fecha_registro`) VALUES
(1002340230, 'Natalia', 'Olmos', 'Lider de Talento Humano', 'logonegro.png', '3103452301', NULL, NULL, NULL, NULL, NULL, 3, 'nataliaolmos02@gmail.com', 1, NULL),
(1110460410, 'Daniel ', 'Cardenas', 'Lider Sena Empresa', 'LideresSenaEmpresa.PNG', '3112301201', NULL, NULL, NULL, NULL, NULL, 3, 'danielcardenas@gmail.com', 1, NULL),
(1140914512, 'Laura Sofia', 'Casallas Cardenas', NULL, 'logonegro.png', '3203694662', '$2y$15$Nfes2HTuFrz0tRw3S41jsekld.pLkC7bJyamVGXQUmVwt2JmvyFwK', 'Femenino', 1, 1, 2669497, 1, 'mitalentohumanose@gmail.com', 1, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `detalle_area_unidades`
--
ALTER TABLE `detalle_area_unidades`
  ADD PRIMARY KEY (`id_detalle_areauni`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD PRIMARY KEY (`codigoFicha`);

--
-- Indices de la tabla `formatos`
--
ALTER TABLE `formatos`
  ADD PRIMARY KEY (`id_formato`);

--
-- Indices de la tabla `intentos_fallidos`
--
ALTER TABLE `intentos_fallidos`
  ADD PRIMARY KEY (`id_intentos`);

--
-- Indices de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  ADD PRIMARY KEY (`id_programa`);

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `turno_especial`
--
ALTER TABLE `turno_especial`
  ADD PRIMARY KEY (`id_turno_especial`);

--
-- Indices de la tabla `turno_rutinario`
--
ALTER TABLE `turno_rutinario`
  ADD PRIMARY KEY (`id_turno_rutinario`);

--
-- Indices de la tabla `unidad`
--
ALTER TABLE `unidad`
  ADD PRIMARY KEY (`id_unidad`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`documento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `detalle_area_unidades`
--
ALTER TABLE `detalle_area_unidades`
  MODIFY `id_detalle_areauni` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `formatos`
--
ALTER TABLE `formatos`
  MODIFY `id_formato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `intentos_fallidos`
--
ALTER TABLE `intentos_fallidos`
  MODIFY `id_intentos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `turno_especial`
--
ALTER TABLE `turno_especial`
  MODIFY `id_turno_especial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `turno_rutinario`
--
ALTER TABLE `turno_rutinario`
  MODIFY `id_turno_rutinario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unidad`
--
ALTER TABLE `unidad`
  MODIFY `id_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
