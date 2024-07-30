-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-07-2024 a las 22:54:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
(31, 'Mecanización', 1, '2024-07-26 15:28:34', '0000-00-00 00:00:00');

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

--
-- Volcado de datos para la tabla `detalle_area_unidades`
--

INSERT INTO `detalle_area_unidades` (`id_detalle_areauni`, `id_area`, `id_unidad`, `fecha_registro`) VALUES
(84, 20, 40, '2024-07-17 15:31:29'),
(85, 20, 41, '2024-07-17 15:31:29'),
(86, 20, 39, '2024-07-17 15:31:29'),
(87, 23, 47, '2024-07-29 12:36:44'),
(88, 23, 48, '2024-07-29 12:36:44'),
(89, 23, 38, '2024-07-29 12:36:44'),
(90, 23, 40, '2024-07-29 12:36:45'),
(91, 21, 37, '2024-07-29 12:36:45'),
(92, 21, 45, '2024-07-29 12:36:45'),
(93, 21, 48, '2024-07-29 12:36:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id_empresa` int(11) NOT NULL,
  `nombre_empresa` varchar(255) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id_empresa`, `nombre_empresa`, `id_estado`, `fecha_registro`) VALUES
(9518833, 'RAFAÉL HERNÁN LARA OJEDA', 1, '2024-07-30 10:44:51'),
(14234175, 'SARMIENTO LINDO ANGEL GUSTAVO', 1, '2024-07-30 10:44:51'),
(16763313, 'GARCIA GOMEZ LUIS FERNANDO', 1, '2024-07-30 10:44:51'),
(75062708, 'JULIAN DIAZ DUQUE', 1, '2024-07-30 10:44:51'),
(79000674, 'JORGE ENRIQUE PRIMO-PANADERIA Y PASTELERIA EL NECTAR', 1, '2024-07-30 10:44:51'),
(79234386, 'LORENZO HIDALGO ZAMORA', 1, '2024-07-30 10:44:51'),
(79762603, 'FAUNIER ROLANDO LOPEZ AVILA', 1, '2024-07-30 10:44:51'),
(800018488, 'AGROZ S.A.', 1, '2024-07-30 10:44:51'),
(800020220, 'AGROINDUSTRIAL MOLINO SONORA A.P. S A S', 1, '2024-07-30 10:44:51'),
(800027738, 'AGROPECUARIA LA MARQUEZA S.A.', 1, '2024-07-30 10:44:51'),
(800033345, 'INVAGRAS S.A.S', 1, '2024-07-30 10:44:51'),
(800045797, 'INDUSTRIAS ALIADAS S.A.S.', 1, '2024-07-30 10:44:51'),
(800050714, 'COMERCIALIZADORA INTERNACIONAL AGRÍCOLA CARDENAL S.A.S', 1, '2024-07-30 10:44:51'),
(800065684, 'FLORES DE LA HACIENDA SAS', 1, '2024-07-30 10:44:51'),
(800095036, 'DLK S A S', 1, '2024-07-30 10:44:51'),
(800095068, 'AGROINDUSTRIAL DON EUSEBIO S.A.S', 1, '2024-07-30 10:44:51'),
(800108333, 'INVERSIONES STHONIA S.A.S', 1, '2024-07-30 10:44:51'),
(800109387, 'UNIVERSIDAD CESMAG', 1, '2024-07-30 10:44:51'),
(800125859, 'FLORES MILONGA S.A', 1, '2024-07-30 10:44:51'),
(800126875, 'FLORES DE BOJACA SAS', 1, '2024-07-30 10:44:51'),
(800141506, 'THE ELITE FLOWER S A S C I', 1, '2024-07-30 10:44:51'),
(800142580, 'TEUCALI FLOWERS SA', 1, '2024-07-30 10:44:51'),
(800158149, 'FLORES DE BRITANIA S.A.S.', 1, '2024-07-30 10:44:51'),
(800173004, 'FRUTAS COMERCIALES S.A.', 1, '2024-07-30 10:44:51'),
(800192056, 'CONSORCIO AGRICOLA BUENOS AIRES S A S', 1, '2024-07-30 10:44:51'),
(800194600, 'LA CORPORACION COLOMBIANA DE INVESTIGACION AGROPECUARIA - AGROSAVIA', 1, '2024-07-30 10:44:51'),
(800214937, 'WAYUU FLOWERS S A S', 1, '2024-07-30 10:44:51'),
(800222689, 'INVERSIONES SOGA S.A.', 1, '2024-07-30 10:44:51'),
(800249444, 'GONAREZ & CIA S.A.S.', 1, '2024-07-30 10:44:51'),
(801000713, 'ONCÓLOGOS DEL OCCIDENTE S.A.S', 1, '2024-07-30 10:44:51'),
(804005066, 'GANADERIA CANDILEJAS S.A.S.', 1, '2024-07-30 10:44:51'),
(808002473, 'AVICOLA DEL MAGDALENA S.A - AVIMA S.A.', 1, '2024-07-30 10:44:51'),
(809005667, 'JESUS MARIA SANCHEZ R. Y CIA. S. EN C.', 1, '2024-07-30 10:44:51'),
(809008051, 'GM LEGAZY S.A.S.', 1, '2024-07-30 10:44:51'),
(809009050, 'EMBOTELLADORA DE BEBIDAS DEL TOLIMA S.A.', 1, '2024-07-30 10:44:51'),
(809012806, 'AGROVAR S A S', 1, '2024-07-30 10:44:51'),
(811004057, 'PRAGMA S.A.', 1, '2024-07-30 10:44:51'),
(811032272, 'VIVERO SOL ROJO S.A.S.', 1, '2024-07-30 10:44:51'),
(820000142, 'INSTITUTO DE INVESTIGACION DE RECURSOS BIOLOGICOS \"ALEXANDER VON HUMBOLDT\"', 1, '2024-07-30 10:44:51'),
(830005674, 'MOUNTAIN ROSES S A S', 1, '2024-07-30 10:44:51'),
(830010738, 'COMERCIALIZADORA INTERNACIONAL SUNSHINE BOUQUET COLOMBIA S.A.S - C.I. SUNSHINE BOUQUET S.A.S', 1, '2024-07-30 10:44:51'),
(830016868, 'AGROAVICOLA SAN MARINO S.A', 1, '2024-07-30 10:44:51'),
(830031070, 'TRINITY FARMS S.A.', 1, '2024-07-30 10:44:51'),
(830051928, 'DON MAIZ S.A.S.', 1, '2024-07-30 10:44:51'),
(830055791, 'AXITY COLOMBIA SOCIEDAD POR ACCIONES SIMPLIFICADA -AXITY COLOMBIA SAS', 1, '2024-07-30 10:44:51'),
(830070021, 'INVERSIONES PENIEL SAS', 1, '2024-07-30 10:44:51'),
(830091683, 'SNF SAS', 1, '2024-07-30 10:44:51'),
(830093741, 'FANTASY FLOWERS S.A.S.', 1, '2024-07-30 10:44:51'),
(830109054, 'FRUSERVICE S.A.S', 1, '2024-07-30 10:44:51'),
(830111367, 'SUDESPENSA BARRAGAN S.A.', 1, '2024-07-30 10:44:51'),
(830117002, 'FLORES EL PANDERO S.A.S', 1, '2024-07-30 10:44:51'),
(830140122, 'MATINA FLOWERS SAS', 1, '2024-07-30 10:44:51'),
(830141144, 'ECOFILLERS EU', 1, '2024-07-30 10:44:51'),
(830144337, 'VIDRIOS Y ACCESORIOS DE BOGOTA  S.A.S.', 1, '2024-07-30 10:44:51'),
(830506392, 'MORANGO S.A.S.', 1, '2024-07-30 10:44:51'),
(830511773, 'BRM S.A.S.', 1, '2024-07-30 10:44:51'),
(860000006, 'TEAM FOODS COLOMBIA S A - ACEGRASAS S A TECNOLOGIA EMPRESARIAL DE ALIMENTOS TEAM S A Y FAGRAVE S A', 1, '2024-07-30 10:44:51'),
(860000698, 'JAIME URIBE Y HERMANAS LTDA', 1, '2024-07-30 10:44:51'),
(860003563, 'ABB POWER GRIDS COLOMBIA LTDA', 1, '2024-07-30 10:44:51'),
(860006333, 'YARA COLOMBIA S.A.', 1, '2024-07-30 10:44:51'),
(860007386, 'UNIVERSIDAD DE LOS ANDES', 1, '2024-07-30 10:44:51'),
(860007538, 'FEDERACION NACIONAL DE CAFETEROS DE COLOMBIA', 1, '2024-07-30 10:44:51'),
(860010457, 'HERMANAS DEL NIÑO JESUS POBRE', 1, '2024-07-30 10:44:51'),
(860010522, 'FEDERACION NACIONAL DE ARROCEROS FEDEARROZ', 1, '2024-07-30 10:44:51'),
(860014918, 'FUNDACION UNIVERSIDAD EXTERNADO DE COLOMBIA', 1, '2024-07-30 10:44:51'),
(860021475, 'INDUSTRIA COLOMBIANA DE CARNE S A - INCOLCAR S A', 1, '2024-07-30 10:44:51'),
(860025900, 'ALPINA PRODUCTOS ALIMENTICIOS S.A.', 1, '2024-07-30 10:44:51'),
(860026895, 'ITALCOL S.A.', 1, '2024-07-30 10:44:51'),
(860027812, 'CULTIVOS SAN JOSE LIMITADA', 1, '2024-07-30 10:44:51'),
(860028238, 'FABRICA DE ESPECIAS Y PRODUCTOS EL REY SA', 1, '2024-07-30 10:44:51'),
(860029445, 'SANTA REYES S.A.S - SRY S.A.S', 1, '2024-07-30 10:44:51'),
(860029997, 'LABORATORIOS DE COSMETICOS VOGUE SAS', 1, '2024-07-30 10:44:51'),
(860031606, 'DIANA CORPORACION S.A.S - DICORP S.A.S.', 1, '2024-07-30 10:44:51'),
(860032436, 'FLORES DEL RIO Y CIA S.A.S.', 1, '2024-07-30 10:44:51'),
(860054546, 'MG CONSULTORES SAS', 1, '2024-07-30 10:44:51'),
(860058831, 'AVICOLA LOS CAMBULOS S.A.', 1, '2024-07-30 10:44:51'),
(860063039, 'HACIENDA EL RINCON LTDA.', 1, '2024-07-30 10:44:51'),
(860075498, 'MORENOS S.A.S', 1, '2024-07-30 10:44:51'),
(860075966, 'INVERSIONES CLIMACUNA S.A.S. - CLIMACUNA S.A.S.', 1, '2024-07-30 10:44:51'),
(860350564, 'INVERPALMAS S.A.S. - INVERPALMAS S.A.S.', 1, '2024-07-30 10:44:51'),
(860351680, 'C.I. AGROMONTE S.A.S.', 1, '2024-07-30 10:44:51'),
(860450234, 'INCODEPF S.A.S', 1, '2024-07-30 10:44:51'),
(860507669, 'JARDINEROS LIMITADA', 1, '2024-07-30 10:44:51'),
(860508791, 'DONUCOL S.A.', 1, '2024-07-30 10:44:51'),
(860524163, 'FLORES DE SERREZUELA S.A.S.', 1, '2024-07-30 10:44:51'),
(860526418, 'FUNDACION LABORATORIO DE FARMACOLOGIA VEGETAL - LABFARVE', 1, '2024-07-30 10:44:51'),
(860532081, 'HALCON AGROINDUSTRIAL S.A.S', 1, '2024-07-30 10:44:51'),
(890700056, 'INVERSIONES AGROPECUARIAS DOIMA S.A.', 1, '2024-07-30 10:44:51'),
(890700058, 'UNION DE ARROCEROS S.A.S. - UNIARROZ S.A.S.', 1, '2024-07-30 10:44:51'),
(890700148, 'CAJA DE COMPENSACION FAMILIAR DE FENALCO DEL TOLIMA - COMFENALCO', 1, '2024-07-30 10:44:51'),
(890700359, 'LILIA GALINDO DE POSADA & CIA. LTDA.', 1, '2024-07-30 10:44:51'),
(890700965, 'CULTIVOS CASA SAS', 1, '2024-07-30 10:44:51'),
(890701327, 'ARROCERA POTRERITO S A S', 1, '2024-07-30 10:44:51'),
(890701655, 'ZORROZA Y SUAREZ S.A.S', 1, '2024-07-30 10:44:51'),
(890701732, 'COOPERATIVA DE CAFICULTORES DEL SUR DEL TOLIMA LTDA. - CAFISUR', 1, '2024-07-30 10:44:51'),
(890702335, 'CENTRAL PECUARIA S.A.', 1, '2024-07-30 10:44:51'),
(890702406, 'ERNESTO NAVARRO Y CIA S EN C', 1, '2024-07-30 10:44:51'),
(890702700, 'MELENDEZ Y MELENDEZ LTDA.', 1, '2024-07-30 10:44:51'),
(890702731, 'USOCOELLO', 1, '2024-07-30 10:44:51'),
(890702902, 'ELIAS ACOSTA Y CIA SAS', 1, '2024-07-30 10:44:51'),
(890703064, 'CORPORACIÓN JARDÍN DE LOS ABUELOS', 1, '2024-07-30 10:44:51'),
(890703094, 'CORPORACIÓN CLUB CAMPESTRE IBAGUÉ', 1, '2024-07-30 10:44:51'),
(890704021, 'ORGANIZACION PAJONALES S.A.S', 1, '2024-07-30 10:44:51'),
(890704409, 'USOSALDAÑA', 1, '2024-07-30 10:44:51'),
(890706833, 'HOSPITAL FEDERICO LLERAS ACOSTA DE IBAGUE TOLIMA - EMPRESA SOCIAL DEL ESTADO', 1, '2024-07-30 10:44:51'),
(890706999, 'PISCIFACTORIA REMAR S.A.S.', 1, '2024-07-30 10:44:51'),
(890900135, 'AVIAGEN COLOMBIA SA', 1, '2024-07-30 10:44:51'),
(890904478, 'COOPERATIVA  COLANTA', 1, '2024-07-30 10:44:51'),
(890938750, 'SOCIEDAD DE COMERCIALIZACION INTERNACIONAL AGRICOLAS UNIDAS S.A.', 1, '2024-07-30 10:44:51'),
(891100445, 'ORF S.A - O R F', 1, '2024-07-30 10:44:51'),
(891301549, 'NUTRIENTES AVICOLAS S.A.S', 1, '2024-07-30 10:44:51'),
(891855200, 'ALCALDIA MUNICIPAL DE AGUAZUL', 1, '2024-07-30 10:44:51'),
(891903333, 'CARCAFE LTDA', 1, '2024-07-30 10:44:51'),
(899999034, 'SERVICIO NACIONAL DE APRENDIZAJE, SENA', 1, '2024-07-30 10:44:51'),
(900016521, 'FRUTIVER 1A S.A.S', 1, '2024-07-30 10:44:51'),
(900031737, 'PORCICOLA EL RECUERDO LTDA.', 1, '2024-07-30 10:44:51'),
(900036947, 'TRESPALACIOS BOLIVAR S EN C SIMPLE', 1, '2024-07-30 10:44:51'),
(900087145, 'MINA EL GRAN PORVENIR DEL LIBANO S.A.', 1, '2024-07-30 10:44:51'),
(900119429, 'JARDINES URBANOS S.A.S', 1, '2024-07-30 10:44:51'),
(900139215, 'AGROPECUARIA LEMAYA LTDA', 1, '2024-07-30 10:44:51'),
(900155107, 'CENCOSUD COLOMBIA S.A.', 1, '2024-07-30 10:44:51'),
(900209374, 'GANADOS Y FORRAJES S.A.S', 1, '2024-07-30 10:44:51'),
(900215071, 'BANCO DE LAS MICROFINANZAS BANCAMIA S.A.', 1, '2024-07-30 10:44:51'),
(900249144, 'INVERSIONES Y OPERACIONES COMERCIALES DEL SUR S.A.', 1, '2024-07-30 10:44:51'),
(900254110, 'AGRÍCOLA TERRA S.A.S.', 1, '2024-07-30 10:44:51'),
(900264802, 'SINEFI S.A.S', 1, '2024-07-30 10:44:51'),
(900267392, 'GARCIA DEVIA AGROPECUARIA RANCHO LUNA SAS', 1, '2024-07-30 10:44:51'),
(900272161, 'MTJA S.A.S.', 1, '2024-07-30 10:44:51'),
(900294923, 'AVICOLA TRIPLE A S.A.S.', 1, '2024-07-30 10:44:51'),
(900315287, 'INGENIO DEL OCCIDENTE SAS', 1, '2024-07-30 10:44:51'),
(900317030, 'PROCESOS TECNICOS DE SEGURIDAD Y VALORES S. A. S. -  TESEVAL S. A. S.', 1, '2024-07-30 10:44:51'),
(900353879, 'DATTEC S A S', 1, '2024-07-30 10:44:51'),
(900381993, 'AGROQUIMICOS PARA LA AGRICULTURA COLOMBIANA S.A.S.', 1, '2024-07-30 10:44:51'),
(900384449, 'FRESH & NATURAL S.A.S.', 1, '2024-07-30 10:44:51'),
(900403334, 'CHARRY TRADING S A S', 1, '2024-07-30 10:44:51'),
(900437091, 'COMERCIALIZADORA JOSACAR SAS', 1, '2024-07-30 10:44:51'),
(900462245, 'NATIVA PRODUCE S.A.S.', 1, '2024-07-30 10:44:51'),
(900465446, 'PRODUCTORA COLOMBIANA DE CITRICOS SAS - PROCOLCITRICOS SAS', 1, '2024-07-30 10:44:51'),
(900496469, 'COLOMBIANA DE SERVICIOS LOGISTICOS S.A.S - COLSERLOG S.A.S', 1, '2024-07-30 10:44:51'),
(900496606, 'AGROLIMON S.A.S', 1, '2024-07-30 10:44:51'),
(900514910, 'ESHKOL PREMIUM FOODS S.A.S', 1, '2024-07-30 10:44:51'),
(900526797, 'INDUSTRIAS ALIMENTICIAS SAN NICOLAS S A S', 1, '2024-07-30 10:44:51'),
(900529276, 'PRODUCTOS LACTEOS PACO´S SAS', 1, '2024-07-30 10:44:51'),
(900552241, 'GEOFLORA SAS', 1, '2024-07-30 10:44:51'),
(900561500, 'LA ARTESA S A S', 1, '2024-07-30 10:44:51'),
(900563861, 'PIANTE S.A.S', 1, '2024-07-30 10:44:51'),
(900581287, 'SERVIPOLLOS GERMAN S.A.S', 1, '2024-07-30 10:44:51'),
(900602495, 'OUTSOURCING SUPPORT SAS', 1, '2024-07-30 10:44:51'),
(900605834, 'AGROINDUSTRIAS SANTA MONICA S.A.S', 1, '2024-07-30 10:44:51'),
(900630758, 'QUALITY FRESH FOOD  S.A.S', 1, '2024-07-30 10:44:51'),
(900631030, 'AGRICOLA LA CAROLINA SAS', 1, '2024-07-30 10:44:51'),
(900768933, 'BANCO MUNDO MUJER SA O MUNDO MUJER EL BANCO DE LA COMUNIDAD O MUNDO MUJER', 1, '2024-07-30 10:44:51'),
(900769717, 'JUGANI S.A.S.', 1, '2024-07-30 10:44:51'),
(900817795, 'LA CATLEYA AC SAS', 1, '2024-07-30 10:44:51'),
(900819727, 'PES COLOMBIA S.A.S', 1, '2024-07-30 10:44:51'),
(900829016, 'ALMA - G S.A.S.', 1, '2024-07-30 10:44:51'),
(900893683, 'SEMILLAS ELITE DE PALMA PARA LAS AMERICAS S.A.S - SEPALM', 1, '2024-07-30 10:44:51'),
(900964443, 'ONELINK S.A.S.', 1, '2024-07-30 10:44:51'),
(901026695, 'SAMBA PRODUCE S.A.S.', 1, '2024-07-30 10:44:51'),
(901043903, 'GOWAN COLOMBIA S.A.S.', 1, '2024-07-30 10:44:51'),
(901106407, 'INVERSIONES AGROPECUARIAS WAC SAS', 1, '2024-07-30 10:44:51'),
(901148480, 'BIONATURE SAS', 1, '2024-07-30 10:44:51'),
(901236507, 'ELITE BLU S.A.S.', 1, '2024-07-30 10:44:51'),
(901237772, 'PROGRESA ZOMAC S.A.S.', 1, '2024-07-30 10:44:51'),
(901242351, 'EDOSPINA TECHNOLOGY', 1, '2024-07-30 10:44:51'),
(901245456, 'CULTIVOS BELLAVISTA SAS', 1, '2024-07-30 10:44:51'),
(901260012, 'HORNEADOS LA 5TA ESTRELLA SAS', 1, '2024-07-30 10:44:51'),
(901343476, 'CIFRUTOL S.A.S', 1, '2024-07-30 10:44:51'),
(901388276, 'SAKANA SUSHI FUSION COL S.A.S', 1, '2024-07-30 10:44:51'),
(901390735, 'CI MANAR FRUIT SAS', 1, '2024-07-30 10:44:51'),
(901392009, 'AGROINDUSTRIAL PECUARIA EL JUGUETE', 1, '2024-07-30 10:44:51'),
(901400067, 'COSECHAR SERVICIOS AGRICOLAS SAS', 1, '2024-07-30 10:44:51'),
(901430941, 'SAN ALEJO Y ASOCIADOS SAS', 1, '2024-07-30 10:44:51'),
(901449886, 'G.D AGRO LOGISTICA SOLUCIONES AGROPECUARIAS SAS', 1, '2024-07-30 10:44:51'),
(901506325, 'HY-LINE COLOMBIA S.A.S.', 1, '2024-07-30 10:44:51'),
(901610780, 'XALTECH TELECOMUNICACIONES S.A.S.', 1, '2024-07-30 10:44:51'),
(1110454490, 'SANTANILLA HERNANDEZ ANDRES MAURICIO / NUEVO MILENIO PARTELERIA', 1, '2024-07-30 10:44:51');

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
(5, 'Bloqueado'),
(8, 'Etapa Productiva'),
(9, 'Retirado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas`
--

CREATE TABLE `fichas` (
  `codigoFicha` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `inicio_formacion` date NOT NULL,
  `fin_formacion` date NOT NULL,
  `fecha_productiva` date DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `id_estado_se` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Volcado de datos para la tabla `intentos_fallidos`
--

INSERT INTO `intentos_fallidos` (`id_intentos`, `email`, `fecha_intento`) VALUES
(1, 'mitalentohumanose@gmail.com', '2024-07-11'),
(2, 'mitalentohumanose@gmail.com', '2024-07-11'),
(3, 'mitalentohumanose@gmail.com', '2024-07-11'),
(4, 'mitalentohumanose@gmail.com', '2024-07-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_formacion`
--

CREATE TABLE `programas_formacion` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(200) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas_formacion`
--

INSERT INTO `programas_formacion` (`id_programa`, `nombre_programa`, `descripcion`, `id_estado`) VALUES
(22, 'Tecnologo en procesamiento de alimentos', 'El tecnólogo en Procesamiento de Alimentos tendrá la capacidad de elaborar y procesar higiénicamente los alimentos, aplicando las normas y sistemas de calidad vigentes. Además, estará en la capacidad de manejar, clasiticar, caracterizar la composición de los alimentos, mediante análisis fisicoquímicos, biológicos y organolépticos de las materias primas, insumos y alimentos.', 1),
(24, 'Tecnologo en agricultura de precision ', 'Este programa contempla el manejo de las tecnologías agrícolas como sistemas de información geográfica, manejo de GPS, nivelación láser, riego y drenaje automatizado, maquinaria agrícola de precisión y software para fertilización.', 1),
(25, 'Tecnologo en control y calidad en la industria de los alimentos', 'El tecnólogo en Control de Calidad en la Industria de Alimentos es competente para realizar, en el sector agroindustrial y especialmente en los subsectores de alimentos y bebidas, la verificación de producción de alimentos, la coordinación de las actividades generales de laboratorio, etc.', 1),
(26, 'Tecnólogo en producción de especies menores', 'El tecnólogo en Producción de Especies Menores maneja los aspectos de planeación, producción, comercialización, aprovechamiento de los recursos técnicos y físicos presentes en las fincas, el procesamiento de los productos y subproductos originados en los procesos productivos de las especies\r\nmenores.', 1),
(27, 'Mecanización agrícola', 'El egresado del programa de formación en Mecanización\r\nAgrícola, administra y opera tractores e implementos para la adecuada realización de labores agricolas, prepara terrenos para la siembra, tipo convencional, labranza reducida o mínima y labranza cero, nivel a lotes, aplica productos con equipos mecánicos para el control de plagas, prepara la cosecha.', 1),
(28, 'Tecnólogo en Gestión de la Producción Agrícola', 'El egresado del programa tecnólogo en Gestión de la Producción Agrícola, está en capacidad de apoyar las actividades que promuevan nuevos modelos tecnológicos productivos, con base en las buenas prácticas agricolas y sus referentes normativos, de tal forma que contribuya en la documentación de procesos necesarios para el mantenimiento y mejora continua.', 1),
(29, 'Tecnologo en gestion de recursos naturales', 'Intervienen en los procesos de diagnóstico como en ordenación, administración y conservación de los recursos agua, suelo, flora y fauna nivel local como regional y nacional, para satisfacer las demandas del sector de acuerdo con las tecnologías y los sistemas productivos actuales, participa en el proceso de gestión y administración de los recursos naturales, el cual tiene diversos niveles tecnológicos.', 1),
(30, 'Tecnologo en gestion de empresas pecuarias', 'El egresado del programa de formación gestión de empresas pecuarias, posee las competencias necesarias para desempeñarse en el contexto laboral, social y cultural, como persona idónea en el desarrollo de actividades relacionadas con la supervicion y control de procesos productivos pecuarios, coordinación de proyectos productivos pecuarios, administración de información y recursos de la producción pecuaria, implementación de sistemas agrícolas para la alimentación animal.', 1);

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
(76, 'Porcinos', 20, '07:00:00', '09:00:00', 2, 1, '2024-07-29 14:45:53', '0000-00-00 00:00:00'),
(77, 'Caprinos / Cunicultura', 20, '07:00:00', '09:00:00', 2, 1, '2024-07-29 14:47:13', '0000-00-00 00:00:00'),
(78, 'Ganaderia', 20, '07:00:00', '09:00:00', 2, 1, '2024-07-29 14:47:43', '0000-00-00 00:00:00'),
(79, 'Planta de concentrados', 20, '07:00:00', '09:00:00', 1, 1, '2024-07-29 14:48:25', '0000-00-00 00:00:00'),
(80, 'Piscicultura', 20, '07:00:00', '09:00:00', 2, 1, '2024-07-29 14:48:53', '0000-00-00 00:00:00'),
(81, 'Apicultura', 20, '07:00:00', '09:00:00', 1, 1, '2024-07-29 14:49:33', '0000-00-00 00:00:00'),
(82, 'Avicultura', 20, '07:00:00', '09:00:00', 1, 1, '2024-07-29 14:50:00', '0000-00-00 00:00:00'),
(83, 'Ovinos', 20, '07:00:00', '09:00:00', 2, 1, '2024-07-29 14:50:37', '0000-00-00 00:00:00'),
(84, 'Laboratorio de reproduccion bovina', 20, '07:00:00', '09:00:00', 1, 2, '2024-07-29 14:51:34', '0000-00-00 00:00:00'),
(85, 'Lote 1', 21, '07:00:00', '09:00:00', 2, 1, '2024-07-29 14:52:18', '0000-00-00 00:00:00'),
(86, 'Lote 2', 21, '07:00:00', '09:00:00', 2, 1, '2024-07-29 14:52:45', '0000-00-00 00:00:00'),
(87, 'Semestrales', 21, '07:00:00', '09:00:00', 1, 1, '2024-07-29 14:53:32', '0000-00-00 00:00:00'),
(88, 'Lote 9', 21, '07:00:00', '09:00:00', 2, 1, '2024-07-29 15:04:55', '0000-00-00 00:00:00'),
(89, 'Lote 20', 21, '07:00:00', '09:00:00', 2, 1, '2024-07-29 15:05:35', '0000-00-00 00:00:00'),
(90, 'Lote 5', 24, '07:00:00', '09:00:00', 2, 1, '2024-07-29 15:06:06', '0000-00-00 00:00:00'),
(91, 'Vivero', 24, '07:00:00', '09:00:00', 2, 1, '2024-07-29 15:06:27', '0000-00-00 00:00:00'),
(92, 'Bioinsumos', 24, '07:00:00', '09:00:00', 2, 1, '2024-07-29 15:07:03', '0000-00-00 00:00:00'),
(93, 'Gestion de centro', 24, '07:00:00', '09:00:00', 2, 1, '2024-07-29 15:07:30', '0000-00-00 00:00:00'),
(94, 'Laboratorio ambiental', 24, '07:00:00', '09:00:00', 1, 2, '2024-07-29 15:08:18', '2024-07-29 15:09:59'),
(95, 'Laboratorio de biotecnologia', 24, '07:00:00', '09:00:00', 1, 2, '2024-07-29 15:08:51', '2024-07-29 15:09:44'),
(96, 'Mecanizacion', 31, '07:00:00', '09:00:00', 1, 1, '2024-07-29 15:09:20', '0000-00-00 00:00:00'),
(97, 'Laboratorio de suelos', 31, '07:00:00', '09:00:00', 1, 2, '2024-07-29 15:10:30', '0000-00-00 00:00:00'),
(98, 'Invernadero', 31, '07:00:00', '09:00:00', 1, 2, '2024-07-29 15:10:52', '0000-00-00 00:00:00'),
(99, 'Parque de riego', 31, '07:00:00', '09:00:00', 1, 2, '2024-07-29 15:11:17', '0000-00-00 00:00:00'),
(100, 'Casa de herramientas', 23, '07:00:00', '09:00:00', 1, 1, '2024-07-29 15:11:45', '0000-00-00 00:00:00'),
(101, 'Oficina de Sena Empresa', 23, '07:00:00', '09:00:00', 2, 1, '2024-07-29 15:12:31', '0000-00-00 00:00:00'),
(102, 'Mercasena', 23, '07:00:00', '16:00:00', 1, 1, '2024-07-29 15:12:55', '0000-00-00 00:00:00'),
(103, 'Mercasena pan y cafe', 23, '07:00:00', '16:00:00', 1, 1, '2024-07-29 15:13:27', '0000-00-00 00:00:00'),
(104, 'Fruhor', 22, '07:00:00', '16:00:00', 1, 1, '2024-07-29 15:14:01', '0000-00-00 00:00:00'),
(105, 'Carnicos', 22, '07:00:00', '16:00:00', 1, 1, '2024-07-29 15:14:27', '0000-00-00 00:00:00'),
(106, 'Lacteos', 22, '07:00:00', '16:00:00', 1, 1, '2024-07-29 15:14:51', '0000-00-00 00:00:00'),
(107, 'Panficacion', 22, '07:00:00', '04:00:00', 1, 1, '2024-07-29 15:15:17', '0000-00-00 00:00:00'),
(108, 'Postcosecha', 22, '07:00:00', '16:00:00', 1, 1, '2024-07-29 15:15:50', '0000-00-00 00:00:00'),
(109, 'Chocolateria', 22, '07:00:00', '16:00:00', 1, 2, '2024-07-29 15:16:18', '2024-07-29 15:19:59'),
(110, 'Laboratorio de cafe', 22, '07:00:00', '16:00:00', 1, 2, '2024-07-29 15:16:55', '0000-00-00 00:00:00'),
(111, 'Laboratorio de calidad ', 22, '07:00:00', '16:00:00', 1, 2, '2024-07-29 15:17:41', '0000-00-00 00:00:00'),
(112, 'Planta de aguas', 22, '07:00:00', '16:00:00', 1, 2, '2024-07-29 15:18:15', '0000-00-00 00:00:00'),
(113, 'Zonas verdes', 24, '07:00:00', '09:00:00', 1, 2, '2024-07-30 08:28:39', '0000-00-00 00:00:00'),
(114, 'Agricultura de precision', 31, '07:00:00', '09:00:00', 1, 2, '2024-07-30 08:29:47', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `documento` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `foto_data` varchar(255) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `sexo` varchar(100) DEFAULT NULL,
  `id_estado_se` int(11) DEFAULT NULL,
  `id_ficha` int(11) DEFAULT NULL,
  `id_tipo_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cargo_funcionario` int(11) DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `tipo_convivencia` varchar(255) DEFAULT NULL,
  `patrocinio` varchar(50) DEFAULT NULL,
  `empresa_patrocinadora` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`documento`, `nombres`, `apellidos`, `foto_data`, `celular`, `password`, `sexo`, `id_estado_se`, `id_ficha`, `id_tipo_usuario`, `email`, `cargo_funcionario`, `id_estado`, `fecha_registro`, `fecha_actualizacion`, `fecha_nacimiento`, `tipo_convivencia`, `patrocinio`, `empresa_patrocinadora`) VALUES
(99464482, 'Albeiro', 'Mejia', 'Albeiro_Mejia_23911216.jpg', '3132001000', NULL, 'masculino', 2, 23911216, 2, 'albeiro@gmail.com', NULL, 9, '2024-07-29 08:43:36', '2024-07-29 09:21:45', '2009-07-17', 'interno', 'si', '5'),
(1005717700, 'Natalia', 'Olmos Villarraga', 'Natalia_Olmos Villarraga_23911202.jpeg', '3043254508', NULL, 'femenino', 1, 23911202, 2, 'nataliaolmos02@gmail.com', NULL, 1, '2024-07-26 15:18:22', '2024-07-29 09:16:23', '2003-08-06', 'externo', 'si', '4'),
(1009200120, 'Gloria Amparo', 'Garcia', 'Gloria Amparo_Garcia_28230188.png', '3222309999', NULL, 'masculino', 9, 28230188, 2, 'amparo123@gmail.com', NULL, 8, '2024-07-29 08:45:24', '2024-07-29 09:20:35', '2009-06-11', 'interno', 'no', ''),
(1110460410, 'Alejandro', 'Munoz', 'Alejandro_Munoz_23911202.jpg', '3201201122', NULL, NULL, 1, 23911202, 2, 'alejandro@gmail.com', NULL, 1, '2024-07-26 15:36:13', NULL, '2009-07-11', 'externo', 'si', '4'),
(1140914512, 'Laura Sofia', 'Casallas Cardenas', 'logonegro.png', '3203694662', '$2y$15$Nfes2HTuFrz0tRw3S41jsekld.pLkC7bJyamVGXQUmVwt2JmvyFwK', 'Femenino', 1, 2669497, 1, 'mitalentohumanose@gmail.com', NULL, 1, '2024-07-24 15:12:02', NULL, '2015-07-17', NULL, 'si', '5');

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
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id_empresa`);

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
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `detalle_area_unidades`
--
ALTER TABLE `detalle_area_unidades`
  MODIFY `id_detalle_areauni` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `formatos`
--
ALTER TABLE `formatos`
  MODIFY `id_formato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `intentos_fallidos`
--
ALTER TABLE `intentos_fallidos`
  MODIFY `id_intentos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `id_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
