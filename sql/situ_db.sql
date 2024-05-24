-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-05-2024 a las 22:44:29
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
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas`
--

CREATE TABLE `fichas` (
  `codigoFicha` bigint(20) NOT NULL,
  `id_programa_formacion` bigint(20) NOT NULL,
  `cantidad_aprendices` int(10) NOT NULL,
  `inicio_formacion` date NOT NULL,
  `fin_formacion` date NOT NULL,
  `estado` int(11) NOT NULL,
  `estadoSE` int(11) NOT NULL,
  `estadoTrimestre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos_fallidos`
--

CREATE TABLE `intentos_fallidos` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `fecha_intento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `intentos_fallidos`
--

INSERT INTO `intentos_fallidos` (`id`, `email`, `fecha_intento`) VALUES
(1, 'mitalentohumanose@gmail.com', '2024-05-16'),
(2, 'mitalentohumanose@gmail.com', '2024-05-16'),
(3, 'mitalentohumanose@gmail.com', '2024-05-16'),
(4, 'mitalentohumanose@gmail.com', '2024-05-16'),
(6, 'mitalentohumanose@gmail.com', '2024-05-16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_formacion`
--

CREATE TABLE `programas_formacion` (
  `id-programa` int(11) NOT NULL,
  `nombre_programa` int(11) NOT NULL,
  `descripcion` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sugerencias`
--

CREATE TABLE `sugerencias` (
  `id_sugerencia` int(11) NOT NULL,
  `tipo_sugerencia` varchar(100) NOT NULL,
  `descripcion_sugerencia` varchar(300) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estructura de tabla para la tabla `unidad`
--

CREATE TABLE `unidad` (
  `id_unidad` bigint(20) NOT NULL,
  `nombre_unidad` varchar(200) NOT NULL,
  `id_area` int(11) NOT NULL,
  `hora_inicio` date NOT NULL,
  `hora_finalizacion` date NOT NULL,
  `cantidad_aprendices` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `estado_trimestre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `documento` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `sexo` varchar(100) NOT NULL,
  `estadoSenaEmpresa` int(10) DEFAULT NULL,
  `estadoTrimestre` int(11) DEFAULT NULL,
  `id_ficha` int(11) DEFAULT NULL,
  `id_tipo_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `estado_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`documento`, `nombres`, `apellidos`, `celular`, `password`, `sexo`, `estadoSenaEmpresa`, `estadoTrimestre`, `id_ficha`, `id_tipo_usuario`, `email`, `estado_usuario`) VALUES
(1140914512, 'Laura Sofia', 'Casallas Cardenas', '3203694662', '$2y$15$Nfes2HTuFrz0tRw3S41jsekld.pLkC7bJyamVGXQUmVwt2JmvyFwK', 'Femenino', 1, 1, 2669497, 1, 'mitalentohumanose@gmail.com', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD PRIMARY KEY (`codigoFicha`);

--
-- Indices de la tabla `intentos_fallidos`
--
ALTER TABLE `intentos_fallidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sugerencias`
--
ALTER TABLE `sugerencias`
  ADD PRIMARY KEY (`id_sugerencia`);

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de la tabla `intentos_fallidos`
--
ALTER TABLE `intentos_fallidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sugerencias`
--
ALTER TABLE `sugerencias`
  MODIFY `id_sugerencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `unidad`
--
ALTER TABLE `unidad`
  MODIFY `id_unidad` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
