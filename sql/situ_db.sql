-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para situ_db
CREATE DATABASE IF NOT EXISTS `situ_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `situ_db`;

-- Volcando estructura para tabla situ_db.areas
CREATE TABLE IF NOT EXISTS `areas` (
  `id_area` int NOT NULL  AUTO_INCREMENT,
  `nombreArea` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id_area`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla situ_db.areas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla situ_db.fichas
CREATE TABLE IF NOT EXISTS `fichas` (
  `codigoFicha` int NOT NULL,
  `id_programa` int NOT NULL,
  `cantidad_aprendices` int NOT NULL,
  `inicio_formacion` date NOT NULL,
  `fin_formacion` date NOT NULL,
  `id_estado` int NOT NULL,
  `id_estado_se` int NOT NULL,
  `id_estado_trimestre` int NOT NULL,
  PRIMARY KEY (`codigoFicha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla situ_db.fichas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla situ_db.intentos_fallidos
CREATE TABLE IF NOT EXISTS `intentos_fallidos` (
  `id_intentos` int NOT NULL AUTO_INCREMENT,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_intento` date NOT NULL,
  PRIMARY KEY (`id_intentos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla situ_db.intentos_fallidos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla situ_db.programas_formacion
CREATE TABLE IF NOT EXISTS `programas_formacion` (
  `id_programa` int NOT NULL AUTO_INCREMENT,
  `nombre_programa` int NOT NULL,
  `descripcion` int NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id_programa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla situ_db.programas_formacion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla situ_db.tipo_usuario
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_usuario` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla situ_db.tipo_usuario: ~2 rows (aproximadamente)
INSERT INTO `tipo_usuario` (`id`, `tipo_usuario`) VALUES
	(1, 'administrador'),
	(2, 'aprendiz');

-- Volcando estructura para tabla situ_db.unidad
CREATE TABLE IF NOT EXISTS `unidad` (
  `id_unidad` int NOT NULL AUTO_INCREMENT,
  `nombre_unidad` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `id_area` int NOT NULL,
  `hora_inicio` date NOT NULL,
  `hora_finalizacion` date NOT NULL,
  `cantidad_aprendices` int NOT NULL,
  `id_estado` int NOT NULL,
  `id_estado_trimestre` int NOT NULL,
  PRIMARY KEY (`id_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla situ_db.unidad: ~0 rows (aproximadamente)

-- Volcando estructura para tabla situ_db.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `documento` int NOT NULL ,
  `nombres` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `celular` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sexo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_estado_se` int DEFAULT NULL,
  `id_estado_trimestre` int DEFAULT NULL,
  `id_ficha` int DEFAULT NULL,
  `id_tipo_usuario` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla situ_db.usuarios: ~1 rows (aproximadamente)
INSERT INTO `usuarios` (`documento`, `nombres`, `apellidos`, `celular`, `password`, `sexo`, `id_estado_se`, `id_estado_trimestre`, `id_ficha`, `id_tipo_usuario`, `email`, `id_estado`) VALUES
	(1140914512, 'Laura Sofia', 'Casallas Cardenas', '3203694662', '$2y$15$Nfes2HTuFrz0tRw3S41jsekld.pLkC7bJyamVGXQUmVwt2JmvyFwK', 'Femenino', 1, 1, 2669497, 1, 'mitalentohumanose@gmail.com', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
