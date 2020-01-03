-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-01-2020 a las 17:08:36
-- Versión del servidor: 5.7.27-0ubuntu0.18.04.1
-- Versión de PHP: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `movilPeru`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `registerPasajes` (IN `idVenta` INT(10), IN `nombres` VARCHAR(256), IN `apellidos` VARCHAR(256), IN `tipoDocumento` VARCHAR(56), IN `numDocumento` INT(10), IN `idTipoPasaje` INT(10))  BEGIN

INSERT INTO PersonalData (nombres, apellidos, tipoDocumento, numDocumento)
VALUES(nombres, apellidos, tipoDocumento, numDocumento);

SET @idPersonalData := LAST_INSERT_ID();

INSERT INTO clientes(idPersonalData) VALUES (@idPersonalData);

SET @idCliente := LAST_INSERT_ID();

INSERT INTO pasajes(idVenta, idCliente, idTipoPasaje) values
(idVenta, @idCliente, idTipoPasaje);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registerUser` (IN `idEstado` INT(10), IN `idRol` INT(10), IN `username` VARCHAR(256), IN `password` VARCHAR(256), IN `nombres` VARCHAR(256), IN `apellidos` VARCHAR(256), IN `genero` VARCHAR(56), IN `fecNac` DATE, IN `tipoDocumento` VARCHAR(256), IN `numDocumento` INT(10), IN `correoElectronico` VARCHAR(256), IN `telefono` INT(10))  BEGIN

INSERT INTO usuarios (idEstado,idRol,username,password) VALUES
(idEstado,idRol,username,password);

INSERT INTO PersonalData (idUsuario, nombres, apellidos,genero, tipoDocumento, numDocumento,correoElectronico, telefono, fecNac) 
VALUES(
LAST_INSERT_ID(),nombres,apellidos,genero,tipoDocumento,numDocumento,correoElectronico,telefono,fecNac
);

INSERT INTO clientes(idPersonalData) values(LAST_INSERT_ID());

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registerVenta` (IN `idUsuarioIN` INT(10), IN `idViajeIda` INT(10), IN `idViajeVuelta` INT(10), IN `cantAdultos` INT(10), IN `cantNinos` INT(10), IN `cantBebes` INT(10))  BEGIN

IF idViajeVuelta!=0 THEN 
set @precioVuelta := (SELECT precio FROM viajes WHERE idViaje= idViajeVuelta);
ELSE 
set @precioVuelta := 0;
END IF;

set @precioIda := (SELECT precio FROM viajes WHERE idViaje= idViajeIda);
set @total := (@precioIda * cantAdultos * 1)
+(@precioIda * cantNinos * 0.5)
+(@precioVuelta * cantAdultos * 1)
+(@precioVuelta * cantNinos * 0.5);

set @capacidadIda := (SELECT capacidad FROM viajes WHERE idViaje= idViajeIda);
set @newCapacidadIda := @capacidadIda - (cantAdultos+ cantNinos);

set @capacidadVuleta := (SELECT capacidad FROM viajes WHERE idViaje= idViajeVuelta);
set @newCapacidadVuelta := @capacidadVuleta - (cantAdultos+ cantNinos);

UPDATE viajes SET capacidad = @newCapacidadIda WHERE idViaje = idViajeIda;
UPDATE viajes SET capacidad = @newCapacidadVuelta WHERE idViaje = idViajeVuelta;

INSERT INTO venta (idViajeIda, cantAdultos, cantNinos, cantBebes, total) 
values(idViajeIda, cantAdultos, cantNinos, cantBebes, @total);

set @idVenta = LAST_INSERT_ID();

IF idViajeVuelta!=0 THEN 
UPDATE venta SET idViajeVuelta = idViajeVuelta WHERE idVenta = @idVenta;
END IF;

IF idUsuarioIN!=0 THEN 
set @idCliente = (SELECT idCliente FROM usuariosInfo WHERE idUsuario= idUsuarioIN);

UPDATE venta SET idCliente = @idCliente WHERE idVenta = @idVenta;
END IF;

select @idVenta as idVenta;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarCliente` (IN `nombres` VARCHAR(256), IN `apellidos` VARCHAR(256), IN `genero` VARCHAR(45), IN `fecNac` DATE, IN `tipoDocumento` VARCHAR(256), IN `numDocumento` INT(10), IN `correoElectronico` VARCHAR(256), IN `telefono` INT(10))  BEGIN

INSERT INTO PersonalData (nombres,apellidos,tipoDocumento,numDocumento,fecNac,telefono,correoElectronico)
VALUES(nombres,apellidos,tipoDocumento,numDocumento,fecNac,telefono,correoElectronico);

INSERT INTO clientes (idPersonalData)
VALUES(LAST_INSERT_ID());

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarConductor` (IN `idEstado` INT(10), IN `idRol` INT(10), IN `username` VARCHAR(256), IN `password` VARCHAR(256), IN `nombres` VARCHAR(256), IN `apellidos` VARCHAR(256), IN `genero` VARCHAR(56), IN `fecNac` DATE, IN `tipoDocumento` VARCHAR(256), IN `numDocumento` INT(10), IN `correoElectronico` VARCHAR(256), IN `telefono` INT(10), IN `categoriaLicencia` VARCHAR(20), IN `numLicencia` VARCHAR(20))  BEGIN

INSERT INTO usuarios (idEstado,idRol,username,password) VALUES
(idEstado,idRol,username,password);

INSERT INTO PersonalData (idUsuario, nombres, apellidos,genero, tipoDocumento, numDocumento,correoElectronico, telefono, fecNac) 
VALUES(
LAST_INSERT_ID(),nombres,apellidos,genero,tipoDocumento,numDocumento,correoElectronico,telefono,fecNac
);

INSERT INTO conductores(idPersonalData, categoriaLicencia, numLicencia) values(LAST_INSERT_ID(),categoriaLicencia,numLicencia);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarVehiculo` (IN `idEstado` INT(10), IN `capacidad` INT(3), IN `placa` VARCHAR(10), IN `marca` VARCHAR(56), IN `modelo` VARCHAR(56), IN `seguro` VARCHAR(56), IN `numSeguro` VARCHAR(56), IN `SOAT` VARCHAR(56))  BEGIN

INSERT INTO vehiculos (idEstado, capacidad, placa, marca, modelo, seguro, numSeguro, SOAT)
VALUES (@idEstado, @capacidad, @placa, @marca, @modelo, @seguro, @numSeguro, @SOAT);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarViaje` (IN `idConductor` INT(10), IN `idVehiculo` INT(10), IN `idOrigen` INT(10), IN `idDestino` INT(10), IN `precio` DOUBLE, IN `departureDate` TIMESTAMP, IN `arriveDate` TIMESTAMP)  BEGIN

set @capacidad := (SELECT capacidad FROM vehiculos WHERE idVehiculo= idVehiculo LIMIT 1);

INSERT INTO viajes (idConductor, idVehiculo, idOrigen, idDestino, precio, capacidad, departureDate, arriveDate)
VALUES(idConductor, idVehiculo, idOrigen, idDestino, precio, @capacidad, departureDate, arriveDate);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateUser` (IN `id` INT(10), IN `idEstado` INT(10), IN `idRol` INT(10), IN `username` VARCHAR(256), IN `password` VARCHAR(2048), IN `nombres` VARCHAR(256), IN `apellidos` VARCHAR(256), IN `genero` VARCHAR(56), IN `fecNac` DATE, IN `tipoDocumento` VARCHAR(256), IN `numDocumento` INT(10), IN `correoElectronico` VARCHAR(256), IN `direccion` VARCHAR(256), IN `telefono` INT(10), IN `imagen` VARCHAR(256), IN `estadoCivil` VARCHAR(256))  BEGIN

IF password!="" THEN 
UPDATE usuarios SET password = password 
WHERE idUsuario = id;
END IF;

UPDATE usuarios SET 
idEstado=idEstado, idRol=idRol, username=username, imagen=imagen
WHERE idUsuario = id;

UPDATE PersonalData SET 
nombres=nombres, apellidos=apellidos, tipoDocumento=tipoDocumento, numDocumento=numDocumento, 
correoElectronico=correoElectronico, direccion=direccion, telefono=telefono,genero=genero, fecNac=fecNac, estadoCivil=estadoCivil
WHERE idUsuario = id;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idCliente` int(10) NOT NULL,
  `idPersonalData` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idCliente`, `idPersonalData`) VALUES
(40, 42),
(41, 43),
(42, 44),
(43, 45),
(44, 46),
(45, 47),
(46, 48),
(47, 49),
(48, 50),
(49, 51),
(50, 52),
(51, 53),
(52, 54),
(53, 55),
(54, 56),
(55, 57),
(56, 58),
(57, 59),
(58, 60),
(59, 61),
(60, 62),
(61, 63),
(62, 64),
(63, 65),
(64, 66),
(65, 67),
(66, 68),
(67, 69),
(68, 70),
(69, 71),
(70, 72),
(71, 73),
(72, 74),
(73, 75),
(74, 76),
(75, 77),
(76, 78),
(77, 79),
(78, 80),
(79, 81),
(80, 82),
(81, 83),
(82, 84),
(83, 85),
(84, 86),
(85, 87),
(86, 88),
(87, 89),
(88, 90),
(89, 91),
(90, 92),
(91, 93),
(92, 94),
(93, 95),
(94, 96),
(95, 97),
(96, 98),
(97, 99),
(98, 100),
(99, 101),
(100, 102),
(101, 103),
(102, 104),
(103, 105),
(104, 106),
(105, 107),
(106, 108),
(107, 109),
(108, 110),
(109, 111),
(110, 112),
(111, 113),
(112, 114),
(113, 115),
(114, 116),
(115, 117),
(116, 118),
(117, 119),
(118, 120),
(119, 121),
(120, 122),
(121, 123),
(122, 124),
(123, 125),
(124, 126),
(125, 127),
(126, 128),
(127, 129),
(128, 130),
(129, 131),
(130, 132),
(131, 133),
(132, 134),
(133, 135);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `clientesInfo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `clientesInfo` (
`idCliente` int(10)
,`tipoDocumento` varchar(45)
,`numDocumento` int(11)
,`apellidos` varchar(45)
,`nombres` varchar(45)
,`fecNac` varchar(45)
,`genero` varchar(45)
,`correoELectronico` varchar(256)
,`direccion` varchar(45)
,`telefono` int(11)
,`estadoCivil` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductores`
--

CREATE TABLE `conductores` (
  `idConductor` int(10) NOT NULL,
  `idPersonalData` int(10) NOT NULL,
  `categoriaLicencia` varchar(20) DEFAULT NULL,
  `numLicencia` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `conductores`
--

INSERT INTO `conductores` (`idConductor`, `idPersonalData`, `categoriaLicencia`, `numLicencia`) VALUES
(1, 9, 'AIII', 'q70334345'),
(2, 42, 'AIII', '961215');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `conductoresinfo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `conductoresinfo` (
`idConductor` int(10)
,`idPersonalData` int(10)
,`idUsuario` int(10)
,`categoriaLicencia` varchar(20)
,`numLicencia` varchar(20)
,`nombres` varchar(45)
,`apellidos` varchar(45)
,`tipoDocumento` varchar(45)
,`numDocumento` int(11)
,`correoElectronico` varchar(256)
,`direccion` varchar(45)
,`telefono` int(11)
,`fecNac` varchar(45)
,`genero` varchar(45)
,`estadoCivil` varchar(45)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `idEstado` int(10) NOT NULL,
  `descripcion` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`idEstado`, `descripcion`) VALUES
(1, 'habilitado'),
(2, 'deshabilitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expotokens`
--

CREATE TABLE `expotokens` (
  `idExpoToken` int(10) NOT NULL,
  `idUsuario` int(10) DEFAULT NULL,
  `expoToken` varchar(256) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `expotokens`
--

INSERT INTO `expotokens` (`idExpoToken`, `idUsuario`, `expoToken`, `created`, `updated`) VALUES
(40, 16, 'ExponentPushToken[YwnJdvI8df4vAgZijkApmM]', '2019-10-28 14:49:53', NULL),
(41, 16, 'ExponentPushToken[oGnjXnK3-FtaXzPQNqtvUo]', '2019-10-28 15:24:18', NULL),
(42, 16, 'ExponentPushToken[bpmIa9KGGj4vGeOidVlJ3s]', '2019-11-10 18:26:26', NULL),
(43, 24, 'ExponentPushToken[Oj5RQSEZm9HVgG1Jd3glyd]', '2019-12-09 05:05:49', NULL),
(46, 18, 'ExponentPushToken[tJMAK5NcIGidjVZR24Dwts]', '2019-12-09 16:03:38', NULL),
(47, 25, 'ExponentPushToken[LaU3aeH48eUmsH1cbjmoA8]', '2019-12-09 18:47:40', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

CREATE TABLE `news` (
  `idNews` int(11) NOT NULL,
  `titulo` varchar(1000) DEFAULT NULL,
  `subtitulo` varchar(1000) DEFAULT NULL,
  `contenido` varchar(21845) DEFAULT NULL,
  `imagen` varchar(256) DEFAULT NULL,
  `createDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasajes`
--

CREATE TABLE `pasajes` (
  `idpasaje` int(10) NOT NULL,
  `idVenta` int(10) DEFAULT NULL,
  `idCliente` int(10) DEFAULT NULL,
  `idTipoPasaje` int(10) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pasajes`
--

INSERT INTO `pasajes` (`idpasaje`, `idVenta`, `idCliente`, `idTipoPasaje`, `created`, `updated`) VALUES
(98, 143, 121, 1, '2019-10-28 01:35:24', NULL),
(99, 145, 122, 1, '2019-12-02 16:29:47', NULL),
(100, 146, 123, 1, '2019-12-08 17:47:41', NULL),
(101, 147, 124, 1, '2019-12-08 17:50:17', NULL),
(102, 148, 125, 1, '2019-12-08 18:21:19', NULL),
(103, 149, 126, 1, '2019-12-08 18:41:27', NULL),
(104, 150, 128, 1, '2019-12-09 13:36:02', NULL),
(105, 150, 129, 2, '2019-12-09 13:36:02', NULL),
(106, 151, 130, 1, '2019-12-09 14:09:50', NULL),
(107, 152, 131, 1, '2019-12-09 14:45:41', NULL),
(108, 153, 132, 1, '2019-12-09 14:51:15', NULL);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `pasajesInfo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `pasajesInfo` (
`idPasaje` int(10)
,`idVenta` int(10)
,`idUsuario` int(10)
,`comprador` varchar(92)
,`idCliente` int(10)
,`tipoDocumento` varchar(45)
,`numDocumento` int(11)
,`apellidos` varchar(45)
,`nombres` varchar(45)
,`idaOrigen` varchar(350)
,`idaDestino` varchar(350)
,`vueltaOrigen` varchar(350)
,`vueltaDestino` varchar(350)
,`fechaCompra` timestamp
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PersonalData`
--

CREATE TABLE `PersonalData` (
  `idPersonalData` int(10) NOT NULL,
  `idUsuario` int(10) DEFAULT NULL,
  `nombres` varchar(45) NOT NULL,
  `apellidos` varchar(45) NOT NULL,
  `tipoDocumento` varchar(45) NOT NULL,
  `numDocumento` int(11) NOT NULL,
  `correoElectronico` varchar(256) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL,
  `fecNac` varchar(45) DEFAULT NULL,
  `genero` varchar(45) DEFAULT NULL,
  `estadoCivil` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `PersonalData`
--

INSERT INTO `PersonalData` (`idPersonalData`, `idUsuario`, `nombres`, `apellidos`, `tipoDocumento`, `numDocumento`, `correoElectronico`, `direccion`, `telefono`, `fecNac`, `genero`, `estadoCivil`) VALUES
(9, 7, 'Nilton Cristian', 'Pizarro Espinoza', 'DNI', 70334345, 'test@test.com', '', 987654321, '1999-12-12', 'masculino', ''),
(42, 16, 'Percy Jesus', 'Avalos Urcia', 'DNI', 70334345, 'Percyavaur@hotmail.com', '', 996007268, '1996-12-15', 'Masculino', ''),
(43, NULL, 'percy', 'avalos', 'DNI', 70334345, NULL, NULL, NULL, NULL, NULL, NULL),
(44, NULL, 'wefere', 'cajas', 'DNI', 123456789, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 17, 'Wefere', 'Cajas', 'DNI', 78945613, 'Wefere@hotmail.com', NULL, 987654321, '2019-06-26', 'Indefinido', NULL),
(46, NULL, 'oscar', 'bravo', 'DNI', 789456123, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 18, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, 'oscarmbravoc@gmail.com', '', 956727151, '1999-02-25', 'Masculino', ''),
(48, 19, 'Rafael', 'Lopez Cordova', 'DNI', 12345678, 'rafaelukf@gmail.com', '', 963852741, '1999-03-15', 'Masculino', ''),
(49, NULL, 'lusi', 'comu', 'Pasaporte', 123466799, NULL, NULL, NULL, NULL, NULL, NULL),
(50, NULL, 'sandra', 'lizhet', 'DNI', 70334345, NULL, NULL, NULL, NULL, NULL, NULL),
(51, NULL, 'veronica', 'margarita', 'Pasaporte', 793434569, NULL, NULL, NULL, NULL, NULL, NULL),
(52, NULL, 'Rafael', 'López Cordova', 'DNI', 76958728, NULL, NULL, NULL, NULL, NULL, NULL),
(53, NULL, 'Rafael ', 'López', 'DNI', 76958728, NULL, NULL, NULL, NULL, NULL, NULL),
(54, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(55, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(56, NULL, 'Adriano Valente', 'Bravo Carbajal', 'DNI', 78945612, NULL, NULL, NULL, NULL, NULL, NULL),
(57, NULL, 'Rafael', 'Lopez Cordova', 'DNI', 78945612, NULL, NULL, NULL, NULL, NULL, NULL),
(58, NULL, 'Cristian', 'Pizarro', 'Pasaporte', 78945612, NULL, NULL, NULL, NULL, NULL, NULL),
(59, NULL, 'Max', 'Aqquepucho Huisa', 'Pasaporte', 12345678, NULL, NULL, NULL, NULL, NULL, NULL),
(60, NULL, 'Sergio', 'Muguruza Sanchez', 'Pasaporte', 45678912, NULL, NULL, NULL, NULL, NULL, NULL),
(61, NULL, 'Sergio', 'Muguruza Sanchez', 'Pasaporte', 45678912, NULL, NULL, NULL, NULL, NULL, NULL),
(62, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(63, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(64, NULL, 'asda', 'asdasd', 'DNI', 123456, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 20, 'Sergio', 'Muguruza', 'DNI', 72854112, 'sergiom2535@gmail.com', '', 989705470, '1998-12-25', 'Masculino', ''),
(66, NULL, 'Sergio', 'Muguruza Sanchez', 'DNI', 1273260, NULL, NULL, NULL, NULL, NULL, NULL),
(67, NULL, 'Roy', 'Saavedra Jimenez', 'Pasaporte', 45678912, NULL, NULL, NULL, NULL, NULL, NULL),
(68, NULL, 'Elisa', 'Olsson', 'Pasaporte', 45612345, NULL, NULL, NULL, NULL, NULL, NULL),
(69, NULL, 'Diego', 'Herrera Gomez', 'DNI', 7568956, NULL, NULL, NULL, NULL, NULL, NULL),
(70, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(71, NULL, 'Adriano Valente', 'Bravo Carbajal', 'DNI', 73265689, NULL, NULL, NULL, NULL, NULL, NULL),
(72, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(73, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(74, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(75, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(76, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(77, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(78, NULL, 'Percy', 'avalos', 'DNI', 70334345, NULL, NULL, NULL, NULL, NULL, NULL),
(79, NULL, 'oscar', 'bravo', 'Pasaporte', 12345678, NULL, NULL, NULL, NULL, NULL, NULL),
(80, NULL, 'cristian', 'Pizarro', 'DNI', 70334345, NULL, NULL, NULL, NULL, NULL, NULL),
(81, NULL, 'sergio', 'muguruza', 'DNI', 13345824, NULL, NULL, NULL, NULL, NULL, NULL),
(82, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(83, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(84, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(85, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(86, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(87, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(88, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(89, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(90, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(91, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(92, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(93, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(94, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(95, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(96, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(97, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(98, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(99, NULL, 'Sergio', 'Muguruza', 'DNI', 72854112, NULL, NULL, NULL, NULL, NULL, NULL),
(100, NULL, 'percy', 'avavalos', 'DNI', 70354345, NULL, NULL, NULL, NULL, NULL, NULL),
(101, NULL, 'larry', 'v', 'DNI', 789654311, NULL, NULL, NULL, NULL, NULL, NULL),
(102, NULL, 'test', 'test', 'DNI', 654158347, NULL, NULL, NULL, NULL, NULL, NULL),
(103, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(104, NULL, 'Juan Diego', 'Herrera Gomez', 'DNI', 75343327, NULL, NULL, NULL, NULL, NULL, NULL),
(105, NULL, 'Justin Erick', 'Herrera Gomez', 'DNI', 74405578, NULL, NULL, NULL, NULL, NULL, NULL),
(106, NULL, 'Rafael', 'Lopez Cordova', 'DNI', 76958728, NULL, NULL, NULL, NULL, NULL, NULL),
(107, NULL, 'Rafael', 'Lopez Cordova', 'DNI', 76958728, NULL, NULL, NULL, NULL, NULL, NULL),
(108, NULL, 'Rafael', 'Lopez Cordova', 'DNI', 76958728, NULL, NULL, NULL, NULL, NULL, NULL),
(109, NULL, 'Rafael', 'Lopez Cordova', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(110, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(111, NULL, 'Adriano Valente', 'Bravo Carbajal', 'DNI', 72500215, NULL, NULL, NULL, NULL, NULL, NULL),
(112, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(113, NULL, 'Percy Jesus', 'Avalos Urcia', 'DNI', 78945612, NULL, NULL, NULL, NULL, NULL, NULL),
(114, NULL, 'Cristian ', 'Pizarro Espinoza', 'DNI', 74589612, NULL, NULL, NULL, NULL, NULL, NULL),
(115, NULL, 'Oscar Manuel', 'Bravo Carbajala', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(116, NULL, 'Percy', 'Avalos Urcia', 'DNI', 70334345, NULL, NULL, NULL, NULL, NULL, NULL),
(117, NULL, 'Sergio', 'Muguruza Sanchez', 'DNI', 72854112, NULL, NULL, NULL, NULL, NULL, NULL),
(118, NULL, 'Percy Avalos Urcia', 'Avalos Urcia', 'DNI', 70334345, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 21, 'Cain', 'Cain', 'DNI', 6025414, 'rafaelukf@gmail.com', NULL, 954152952, '1999-03-17', 'Indefinido', NULL),
(120, NULL, 'cain', 'cain', 'DNI', 6025414, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 22, 'Percy', 'Avalos', 'DNI', 36, 'Bdjx', NULL, 656, '2019-10-31', 'Masculino', NULL),
(122, 23, 'Admin', 'Admin', 'DNI', 789456123, 'Admin@admin.com', NULL, 987654321, '1999-10-30', 'Masculino', NULL),
(123, NULL, 'Percy Avalos Urcia', 'Urcia', 'Pasaporte', 656, NULL, NULL, NULL, NULL, NULL, NULL),
(124, NULL, 'Sergio', 'Muguruza', 'DNI', 72854112, NULL, NULL, NULL, NULL, NULL, NULL),
(125, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(126, NULL, 'Oscar Manuel', 'Bravo Carbajal', 'DNI', 73260012, NULL, NULL, NULL, NULL, NULL, NULL),
(127, NULL, 'Sergio ', 'Muguruza', 'DNI', 78956421, NULL, NULL, NULL, NULL, NULL, NULL),
(128, NULL, 'Sergi Roberto', 'Muguruza Sanchez', 'DNI', 78945625, NULL, NULL, NULL, NULL, NULL, NULL),
(129, 24, 'Cristian', 'Pizarro Espinoza ', 'DNI', 72215079, 'cris2008981234@gmail.com', NULL, 991990982, '1998-08-20', 'Masculino', NULL),
(130, NULL, 'Percy', 'Avalos Urcia', 'DNI', 70334345, NULL, NULL, NULL, NULL, NULL, NULL),
(131, NULL, 'Percy', 'Avalos Urcia', 'DNI', 70334345, NULL, NULL, NULL, NULL, NULL, NULL),
(132, NULL, 'percy', 'avalos', 'DNI', 70334345, NULL, NULL, NULL, NULL, NULL, NULL),
(133, NULL, 'CARLOS', 'CRUZADO', 'DNI', 789456123, NULL, NULL, NULL, NULL, NULL, NULL),
(134, NULL, 'Carlos Francisco', 'Cruzado Puente de la Vega', 'DNI', 78945623, NULL, NULL, NULL, NULL, NULL, NULL),
(135, 25, 'Edwin enrique', 'Salinas soto', 'DNI', 72315408, 'edwinsalinassoto20@gmail.com', '', 977310207, '1998-08-20', 'Masculino', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `places`
--

CREATE TABLE `places` (
  `idPlace` int(10) NOT NULL,
  `departamento` varchar(45) DEFAULT NULL,
  `distrito` varchar(45) DEFAULT NULL,
  `direccion` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `places`
--

INSERT INTO `places` (`idPlace`, `departamento`, `distrito`, `direccion`) VALUES
(1, 'Lima', 'Los Olivos', 'Terminal los olivos'),
(2, 'Piura', 'Mancora', 'Terminal Mancora'),
(3, 'Ica', 'Nazca', 'Lineas de Nazca'),
(4, 'Lima', 'Pachangara', 'Plaza de Armas Churin'),
(5, 'La Libertad', 'Trujillo', 'Plaza de Armas Trujillo'),
(6, 'Arequipa', 'Arequipa', 'Plaza de armas Arequipa'),
(7, 'Lambayeque', 'Saña', 'Entierro Real Señor de Sipan'),
(8, 'Ica', 'Ica', 'Huacachina'),
(9, 'Lima', 'Lima', 'Plaza de Armas de Lima');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idRol` int(10) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idRol`, `descripcion`) VALUES
(1, 'administrador'),
(2, 'master'),
(3, 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoPasaje`
--

CREATE TABLE `tipoPasaje` (
  `idTipoPasaje` int(10) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipoPasaje`
--

INSERT INTO `tipoPasaje` (`idTipoPasaje`, `descripcion`) VALUES
(1, 'adulto'),
(2, 'nino'),
(3, 'bebe');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `tripInfoPassenger`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `tripInfoPassenger` (
`idPasaje` int(10)
,`idVenta` int(10)
,`idUsuario` int(10)
,`comprador` varchar(92)
,`idCliente` int(10)
,`tipoDocumento` varchar(45)
,`numDocumento` int(11)
,`apellidos` varchar(45)
,`nombres` varchar(45)
,`idTipoPasaje` int(10)
,`departureDateIda` timestamp
,`idaOrigen` varchar(350)
,`idaDestino` varchar(350)
,`departureDateVuelta` timestamp
,`vueltaOrigen` varchar(350)
,`vueltaDestino` varchar(350)
,`fechaCompra` timestamp
,`conductor` varchar(92)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(10) NOT NULL,
  `idEstado` int(10) NOT NULL,
  `idRol` int(10) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(2048) NOT NULL,
  `imagen` varchar(256) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expoToken` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `idEstado`, `idRol`, `username`, `password`, `imagen`, `created`, `updated`, `expoToken`) VALUES
(7, 1, 1, 'cristianp', '$2y$10$Hj79zEqhlBiTs0A8m8WGC.24X5UX9nBCcL38O5hlXb6/JzS2Tq5ja', 'https://firebasestorage.googleapis.com/v0/b/movilperu.appspot.com/o/fbb7ae9c-d6c9-4de5-8d4d-3c15582d3309?alt=media&amp;token=719f9895-0260-473a-9f65-4d19fd8b76f8', '2019-06-14 02:24:56', '2019-06-26 07:10:24', NULL),
(16, 1, 1, 'Percyavaur', '$2y$10$d0s43.YCZXzRQLluKCN8HubNCMrAKTsv8nh9Xjn6Giw2evor9tUPe', 'https://firebasestorage.googleapis.com/v0/b/movilperu.appspot.com/o/users%2F2e33d409-0b6d-4307-9974-e857587479f9?alt=media&amp;token=068347b6-aed0-4116-9f0d-55eac0783616', '2019-06-26 06:41:34', '2019-10-19 22:08:27', ''),
(17, 1, 3, 'Wefere', '$2y$10$bYUtacGXoiNzJKlyzjP8iO6/1upise8vI6UYOoiHVLqP1GAK.eKqO', NULL, '2019-06-26 06:47:54', '2019-06-27 10:33:24', ''),
(18, 1, 1, 'Iamoscarbc', '$2y$10$77kEXr1uXTVL64PunyB1fuVWnziRfX9daAVHq93pH30cfjRS2IpDm', 'https://firebasestorage.googleapis.com/v0/b/movilperu.appspot.com/o/afe86fa5-c54f-42ac-99ef-12de9d9af90e?alt=media&amp;token=4a725a32-e3bc-4e35-a0cf-705ab309adef', '2019-06-27 01:44:53', '2019-12-08 17:58:25', ''),
(19, 1, 3, 'rafo1599', '$2y$10$JfVcATGQMbqYrioOR9dlou3Czw./aqXzoGJQH.JvgnrKP1UbfxW3G', 'https://firebasestorage.googleapis.com/v0/b/movilperu.appspot.com/o/1382af8e-5725-4f1e-b8ac-228a864ea8b1?alt=media&amp;token=f6fe9ab0-250a-4e58-b8c1-12d8147c5d7d', '2019-06-27 01:47:14', '2019-10-19 22:08:39', ''),
(20, 1, 1, 'sergio2535', '$2y$10$77kEXr1uXTVL64PunyB1fuVWnziRfX9daAVHq93pH30cfjRS2IpDm', 'https://firebasestorage.googleapis.com/v0/b/movilperu.appspot.com/o/users%2F0e956a39-244f-4127-9905-6c675fe131b0?alt=media&amp;token=899e53ab-c3fd-4b69-bc31-eea1bbca7fac', '2019-06-27 07:18:42', '2019-10-22 04:47:12', ''),
(21, 1, 3, 'Cain', '$2y$10$7s04Ul32AS8HKDcy3IHjeuUYT3OIawwgYA9eGCgKYHtsCh0OpwS2a', NULL, '2019-07-14 02:35:25', '2019-10-19 22:08:36', ''),
(22, 1, 3, 'Percyavaur2', '$2y$10$9KlZU8qV2Ajgqvyg.ctpC.2n8aCuNthfYo0ujypBuB4n/ngIeuULC', NULL, '2019-10-19 18:20:42', '2019-10-19 18:20:42', NULL),
(23, 1, 1, 'Admin', '$2y$10$2o9qEdge7VmEr9PgCkalGeOKn5YBszO5y.HNl1sOudsAF2riLPpKW', NULL, '2019-10-21 13:05:16', '2019-10-21 13:05:36', NULL),
(24, 1, 3, 'Cristian', '$2y$10$gn51EVCfPsmVbnwxbmho5O.thVLV68Bl.ID4GdyXyBAsTXxdJppVO', NULL, '2019-12-09 05:05:26', '2019-12-09 05:05:26', NULL),
(25, 1, 3, 'Eesalinass', '$2y$10$Hu.VIXZiXh16qX7L5tAUoe5mRbJpXNhq2gr7kak1okEeihLen7smO', 'https://firebasestorage.googleapis.com/v0/b/movilperu.appspot.com/o/users%2F1410dd4c-f284-4e90-804b-eb3a9d478d33?alt=media&amp;token=d067e5be-64bc-4781-a56d-7d0b52494c55', '2019-12-09 18:47:24', '2019-12-09 18:51:42', NULL);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `usuariosInfo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `usuariosInfo` (
`idUsuario` int(10)
,`idCliente` int(10)
,`username` varchar(25)
,`password` varchar(2048)
,`idRol` int(10)
,`rol` varchar(100)
,`tipoDocumento` varchar(45)
,`numDocumento` int(11)
,`apellidos` varchar(45)
,`nombres` varchar(45)
,`fecNac` varchar(45)
,`genero` varchar(45)
,`correoElectronico` varchar(256)
,`direccion` varchar(45)
,`telefono` int(11)
,`estadoCivil` varchar(45)
,`imagen` varchar(256)
,`idEstado` int(10)
,`estado` varchar(256)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `idVehiculo` int(10) NOT NULL,
  `idEstado` int(10) DEFAULT NULL,
  `capacidad` int(3) DEFAULT NULL,
  `placa` varchar(10) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `seguro` varchar(45) DEFAULT NULL,
  `numSeguro` varchar(45) DEFAULT NULL,
  `SOAT` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`idVehiculo`, `idEstado`, `capacidad`, `placa`, `marca`, `modelo`, `seguro`, `numSeguro`, `SOAT`) VALUES
(1, 1, 15, 'MP987', 'Kia', 'GRAND CARNIVAL 2017', 'pacifico', '78945612', '987654321'),
(2, 1, 10, 'MP654', 'Chevrolet', 'N-300 2018', 'rimac', '987654321', '321654987');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idVenta` int(10) NOT NULL,
  `idCliente` int(10) DEFAULT NULL,
  `idViajeIda` int(10) DEFAULT NULL,
  `idViajeVuelta` int(10) DEFAULT NULL,
  `cantAdultos` int(3) DEFAULT NULL,
  `cantNinos` int(3) DEFAULT NULL,
  `cantBebes` int(3) DEFAULT NULL,
  `createDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`idVenta`, `idCliente`, `idViajeIda`, `idViajeVuelta`, `cantAdultos`, `cantNinos`, `cantBebes`, `createDate`, `total`) VALUES
(143, 40, 7024, NULL, 1, 0, 0, '2019-10-28 01:35:24', 30),
(144, 40, 7025, NULL, 1, 0, 0, '2019-10-28 16:12:47', 25),
(145, 63, 7026, NULL, 1, 0, 0, '2019-12-02 16:29:47', 500),
(146, NULL, 7026, NULL, 1, 0, 0, '2019-12-08 17:47:41', 500),
(147, NULL, 7026, NULL, 1, 0, 0, '2019-12-08 17:50:17', 500),
(148, 45, 7026, NULL, 1, 0, 0, '2019-12-08 18:21:19', 500),
(149, 45, 7026, NULL, 1, 0, 0, '2019-12-08 18:41:27', 500),
(150, 40, 7039, NULL, 1, 1, 0, '2019-12-09 13:36:02', 37.5),
(151, NULL, 7039, NULL, 1, 0, 0, '2019-12-09 14:09:50', 25),
(152, 40, 7039, NULL, 1, 0, 0, '2019-12-09 14:45:41', 25),
(153, 63, 7040, NULL, 1, 0, 0, '2019-12-09 14:51:15', 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viajes`
--

CREATE TABLE `viajes` (
  `idViaje` int(10) NOT NULL,
  `idOrigen` int(10) DEFAULT NULL,
  `idDestino` int(10) DEFAULT NULL,
  `idConductor` int(10) DEFAULT NULL,
  `idVehiculo` int(10) DEFAULT NULL,
  `precio` double DEFAULT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `departureDate` timestamp NULL DEFAULT NULL,
  `departure` timestamp NULL DEFAULT NULL,
  `arrive` timestamp NULL DEFAULT NULL,
  `arriveDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `viajes`
--

INSERT INTO `viajes` (`idViaje`, `idOrigen`, `idDestino`, `idConductor`, `idVehiculo`, `precio`, `capacidad`, `created`, `updated`, `departureDate`, `departure`, `arrive`, `arriveDate`) VALUES
(7024, 2, 4, 2, 2, 30, 14, '2019-10-28 01:10:20', '2019-10-28 01:35:24', '2019-11-01 03:09:50', NULL, NULL, '2019-11-04 03:09:50'),
(7025, 2, 1, 1, 1, 25, 14, '2019-10-28 15:28:06', '2019-10-28 16:12:47', '2019-10-29 16:54:21', NULL, NULL, '2019-10-31 16:54:21'),
(7026, 4, 5, 1, 1, 20, 10, '2019-12-02 16:24:38', '2019-12-09 03:12:01', '2019-12-25 19:21:30', '2019-12-31 00:28:31', '2019-12-31 03:11:59', '2019-12-28 03:11:47'),
(7038, 1, 8, 2, 1, 20, 15, '2019-12-09 05:16:41', '2019-12-09 05:17:45', '2019-12-13 04:59:21', '2019-12-13 05:17:36', '2019-12-25 05:17:41', '2019-12-17 05:17:23'),
(7039, 1, 6, 2, 2, 25, 11, '2019-12-09 13:34:48', '2019-12-09 14:45:41', '2019-12-10 15:23:07', NULL, NULL, '2019-12-11 14:41:57'),
(7040, 1, 3, 1, 1, 50, 14, '2019-12-09 14:50:30', '2019-12-09 14:51:15', '2019-12-10 19:17:34', NULL, NULL, '2019-12-12 17:17:34'),
(7041, 1, 5, 2, 2, 500, 15, '2019-12-09 18:38:02', NULL, '2019-12-10 02:36:03', NULL, NULL, '2019-12-10 19:00:00');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `viajesInfo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `viajesInfo` (
`idViaje` int(10)
,`idDestino` int(10)
,`Destino` varchar(350)
,`depDestino` varchar(45)
,`disDestino` varchar(45)
,`dirDestino` varchar(256)
,`idOrigen` int(10)
,`Origen` varchar(350)
,`depOrigen` varchar(45)
,`disOrigen` varchar(45)
,`dirOrigen` varchar(256)
,`idVehiculo` int(10)
,`precio` double
,`capacidad` int(11)
,`idConductor` int(10)
,`Conductor` varchar(92)
,`placa` varchar(10)
,`marca` varchar(50)
,`modelo` varchar(50)
,`seguro` varchar(45)
,`numSeguro` varchar(45)
,`SOAT` varchar(45)
,`created` timestamp
,`updated` timestamp
,`departureDate` timestamp
,`arriveDate` timestamp
,`departure` timestamp
,`arrive` timestamp
);

-- --------------------------------------------------------

--
-- Estructura para la vista `clientesInfo`
--
DROP TABLE IF EXISTS `clientesInfo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `clientesInfo`  AS  select `clientes`.`idCliente` AS `idCliente`,`PersonalData`.`tipoDocumento` AS `tipoDocumento`,`PersonalData`.`numDocumento` AS `numDocumento`,`PersonalData`.`apellidos` AS `apellidos`,`PersonalData`.`nombres` AS `nombres`,`PersonalData`.`fecNac` AS `fecNac`,`PersonalData`.`genero` AS `genero`,`PersonalData`.`correoElectronico` AS `correoELectronico`,`PersonalData`.`direccion` AS `direccion`,`PersonalData`.`telefono` AS `telefono`,`PersonalData`.`estadoCivil` AS `estadoCivil` from (`clientes` join `PersonalData` on((`clientes`.`idPersonalData` = `PersonalData`.`idPersonalData`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `conductoresinfo`
--
DROP TABLE IF EXISTS `conductoresinfo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `conductoresinfo`  AS  select `conductores`.`idConductor` AS `idConductor`,`PersonalData`.`idPersonalData` AS `idPersonalData`,`PersonalData`.`idUsuario` AS `idUsuario`,`conductores`.`categoriaLicencia` AS `categoriaLicencia`,`conductores`.`numLicencia` AS `numLicencia`,`PersonalData`.`nombres` AS `nombres`,`PersonalData`.`apellidos` AS `apellidos`,`PersonalData`.`tipoDocumento` AS `tipoDocumento`,`PersonalData`.`numDocumento` AS `numDocumento`,`PersonalData`.`correoElectronico` AS `correoElectronico`,`PersonalData`.`direccion` AS `direccion`,`PersonalData`.`telefono` AS `telefono`,`PersonalData`.`fecNac` AS `fecNac`,`PersonalData`.`genero` AS `genero`,`PersonalData`.`estadoCivil` AS `estadoCivil` from (`conductores` join `PersonalData` on((`conductores`.`idPersonalData` = `PersonalData`.`idPersonalData`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `pasajesInfo`
--
DROP TABLE IF EXISTS `pasajesInfo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pasajesInfo`  AS  select `pasajes`.`idpasaje` AS `idPasaje`,`venta`.`idVenta` AS `idVenta`,`usuarios`.`idUsuario` AS `idUsuario`,concat(`buyerData`.`apellidos`,', ',`buyerData`.`nombres`) AS `comprador`,`clientes`.`idCliente` AS `idCliente`,`PersonalData`.`tipoDocumento` AS `tipoDocumento`,`PersonalData`.`numDocumento` AS `numDocumento`,`PersonalData`.`apellidos` AS `apellidos`,`PersonalData`.`nombres` AS `nombres`,concat(`placeidaorigen`.`departamento`,', ',`placeidaorigen`.`distrito`,', ',`placeidaorigen`.`direccion`) AS `idaOrigen`,concat(`placeidaDestino`.`departamento`,', ',`placeidaDestino`.`distrito`,', ',`placeidaDestino`.`direccion`) AS `idaDestino`,concat(`placevueltaorigen`.`departamento`,', ',`placevueltaorigen`.`distrito`,', ',`placevueltaorigen`.`direccion`) AS `vueltaOrigen`,concat(`placevueltaDestino`.`departamento`,', ',`placevueltaDestino`.`distrito`,', ',`placevueltaDestino`.`direccion`) AS `vueltaDestino`,`venta`.`createDate` AS `fechaCompra` from ((((((((((((`pasajes` left join `clientes` on((`pasajes`.`idCliente` = `clientes`.`idCliente`))) left join `PersonalData` on((`clientes`.`idPersonalData` = `PersonalData`.`idPersonalData`))) left join `venta` on((`venta`.`idVenta` = `pasajes`.`idVenta`))) left join `viajes` `viajeIda` on((`viajeIda`.`idViaje` = `venta`.`idViajeIda`))) left join `places` `placeidaorigen` on((`placeidaorigen`.`idPlace` = `viajeIda`.`idOrigen`))) left join `places` `placeidaDestino` on((`placeidaDestino`.`idPlace` = `viajeIda`.`idDestino`))) left join `viajes` `viajeVuelta` on((`viajeVuelta`.`idViaje` = `venta`.`idViajeVuelta`))) left join `places` `placevueltaorigen` on((`placevueltaorigen`.`idPlace` = `viajeVuelta`.`idOrigen`))) left join `places` `placevueltaDestino` on((`placevueltaDestino`.`idPlace` = `viajeVuelta`.`idDestino`))) left join `clientes` `buyer` on((`venta`.`idCliente` = `buyer`.`idCliente`))) left join `PersonalData` `buyerData` on((`buyer`.`idPersonalData` = `buyerData`.`idPersonalData`))) left join `usuarios` on((`usuarios`.`idUsuario` = `buyerData`.`idUsuario`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `tripInfoPassenger`
--
DROP TABLE IF EXISTS `tripInfoPassenger`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tripInfoPassenger`  AS  select `pasajes`.`idpasaje` AS `idPasaje`,`venta`.`idVenta` AS `idVenta`,`usuarios`.`idUsuario` AS `idUsuario`,concat(`buyerData`.`apellidos`,', ',`buyerData`.`nombres`) AS `comprador`,`clientes`.`idCliente` AS `idCliente`,`PersonalData`.`tipoDocumento` AS `tipoDocumento`,`PersonalData`.`numDocumento` AS `numDocumento`,`PersonalData`.`apellidos` AS `apellidos`,`PersonalData`.`nombres` AS `nombres`,`pasajes`.`idTipoPasaje` AS `idTipoPasaje`,`viajeIda`.`departureDate` AS `departureDateIda`,concat(`placeidaorigen`.`departamento`,', ',`placeidaorigen`.`distrito`,', ',`placeidaorigen`.`direccion`) AS `idaOrigen`,concat(`placeidaDestino`.`departamento`,', ',`placeidaDestino`.`distrito`,', ',`placeidaDestino`.`direccion`) AS `idaDestino`,`viajeVuelta`.`departureDate` AS `departureDateVuelta`,concat(`placevueltaorigen`.`departamento`,', ',`placevueltaorigen`.`distrito`,', ',`placevueltaorigen`.`direccion`) AS `vueltaOrigen`,concat(`placevueltaDestino`.`departamento`,', ',`placevueltaDestino`.`distrito`,', ',`placevueltaDestino`.`direccion`) AS `vueltaDestino`,`venta`.`createDate` AS `fechaCompra`,concat(`conductorData`.`nombres`,', ',`conductorData`.`apellidos`) AS `conductor` from ((((((((((((((`pasajes` left join `clientes` on((`pasajes`.`idCliente` = `clientes`.`idCliente`))) left join `PersonalData` on((`clientes`.`idPersonalData` = `PersonalData`.`idPersonalData`))) left join `venta` on((`venta`.`idVenta` = `pasajes`.`idVenta`))) left join `viajes` `viajeIda` on((`viajeIda`.`idViaje` = `venta`.`idViajeIda`))) left join `places` `placeidaorigen` on((`placeidaorigen`.`idPlace` = `viajeIda`.`idOrigen`))) left join `places` `placeidaDestino` on((`placeidaDestino`.`idPlace` = `viajeIda`.`idDestino`))) left join `viajes` `viajeVuelta` on((`viajeVuelta`.`idViaje` = `venta`.`idViajeVuelta`))) left join `places` `placevueltaorigen` on((`placevueltaorigen`.`idPlace` = `viajeVuelta`.`idOrigen`))) left join `places` `placevueltaDestino` on((`placevueltaDestino`.`idPlace` = `viajeVuelta`.`idDestino`))) left join `clientes` `buyer` on((`venta`.`idCliente` = `buyer`.`idCliente`))) left join `PersonalData` `buyerData` on((`buyer`.`idPersonalData` = `buyerData`.`idPersonalData`))) left join `usuarios` on((`usuarios`.`idUsuario` = `buyerData`.`idUsuario`))) left join `conductores` on((`conductores`.`idConductor` = `conductores`.`idConductor`))) left join `PersonalData` `conductorData` on((`conductorData`.`idPersonalData` = `conductores`.`idPersonalData`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `usuariosInfo`
--
DROP TABLE IF EXISTS `usuariosInfo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usuariosInfo`  AS  select `usuarios`.`idUsuario` AS `idUsuario`,`clientes`.`idCliente` AS `idCliente`,`usuarios`.`username` AS `username`,`usuarios`.`password` AS `password`,`rol`.`idRol` AS `idRol`,`rol`.`descripcion` AS `rol`,`PersonalData`.`tipoDocumento` AS `tipoDocumento`,`PersonalData`.`numDocumento` AS `numDocumento`,`PersonalData`.`apellidos` AS `apellidos`,`PersonalData`.`nombres` AS `nombres`,`PersonalData`.`fecNac` AS `fecNac`,`PersonalData`.`genero` AS `genero`,`PersonalData`.`correoElectronico` AS `correoElectronico`,`PersonalData`.`direccion` AS `direccion`,`PersonalData`.`telefono` AS `telefono`,`PersonalData`.`estadoCivil` AS `estadoCivil`,`usuarios`.`imagen` AS `imagen`,`estado`.`idEstado` AS `idEstado`,`estado`.`descripcion` AS `estado` from ((((`usuarios` join `PersonalData` on((`PersonalData`.`idUsuario` = `usuarios`.`idUsuario`))) join `rol` on((`rol`.`idRol` = `usuarios`.`idRol`))) join `estado` on((`estado`.`idEstado` = `usuarios`.`idEstado`))) join `clientes` on((`clientes`.`idPersonalData` = `PersonalData`.`idPersonalData`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `viajesInfo`
--
DROP TABLE IF EXISTS `viajesInfo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viajesInfo`  AS  select `viajes`.`idViaje` AS `idViaje`,`Destino`.`idPlace` AS `idDestino`,concat(`Destino`.`departamento`,', ',`Destino`.`distrito`,', ',`Destino`.`direccion`) AS `Destino`,`Destino`.`departamento` AS `depDestino`,`Destino`.`distrito` AS `disDestino`,`Destino`.`direccion` AS `dirDestino`,`Origen`.`idPlace` AS `idOrigen`,concat(`Origen`.`departamento`,', ',`Origen`.`distrito`,', ',`Origen`.`direccion`) AS `Origen`,`Origen`.`departamento` AS `depOrigen`,`Origen`.`distrito` AS `disOrigen`,`Origen`.`direccion` AS `dirOrigen`,`viajes`.`idVehiculo` AS `idVehiculo`,`viajes`.`precio` AS `precio`,`viajes`.`capacidad` AS `capacidad`,`conductores`.`idConductor` AS `idConductor`,concat(`PersonalData`.`apellidos`,', ',`PersonalData`.`nombres`) AS `Conductor`,`vehiculos`.`placa` AS `placa`,`vehiculos`.`marca` AS `marca`,`vehiculos`.`modelo` AS `modelo`,`vehiculos`.`seguro` AS `seguro`,`vehiculos`.`numSeguro` AS `numSeguro`,`vehiculos`.`SOAT` AS `SOAT`,`viajes`.`created` AS `created`,`viajes`.`updated` AS `updated`,`viajes`.`departureDate` AS `departureDate`,`viajes`.`arriveDate` AS `arriveDate`,`viajes`.`departure` AS `departure`,`viajes`.`arrive` AS `arrive` from (((((`viajes` join `conductores` on((`conductores`.`idConductor` = `viajes`.`idConductor`))) join `PersonalData` on((`PersonalData`.`idPersonalData` = `conductores`.`idPersonalData`))) join `vehiculos` on((`vehiculos`.`idVehiculo` = `viajes`.`idVehiculo`))) join `places` `Destino` on((`Destino`.`idPlace` = `viajes`.`idDestino`))) join `places` `Origen` on((`Origen`.`idPlace` = `viajes`.`idOrigen`))) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCliente`),
  ADD KEY `fk_clientes_PersonalData1_idx` (`idPersonalData`);

--
-- Indices de la tabla `conductores`
--
ALTER TABLE `conductores`
  ADD PRIMARY KEY (`idConductor`),
  ADD KEY `fk_conductores_PersonalData1_idx` (`idPersonalData`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`idEstado`);

--
-- Indices de la tabla `expotokens`
--
ALTER TABLE `expotokens`
  ADD PRIMARY KEY (`idExpoToken`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`idNews`);

--
-- Indices de la tabla `pasajes`
--
ALTER TABLE `pasajes`
  ADD PRIMARY KEY (`idpasaje`),
  ADD KEY `fk_pasajes_venta1_idx` (`idVenta`),
  ADD KEY `fk_pasajes_tipoPasaje1_idx` (`idTipoPasaje`),
  ADD KEY `fk_pasajes_clientes1_idx` (`idCliente`);

--
-- Indices de la tabla `PersonalData`
--
ALTER TABLE `PersonalData`
  ADD PRIMARY KEY (`idPersonalData`),
  ADD KEY `fk_PersonalData_usuarios1_idx` (`idUsuario`);

--
-- Indices de la tabla `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`idPlace`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idRol`);

--
-- Indices de la tabla `tipoPasaje`
--
ALTER TABLE `tipoPasaje`
  ADD PRIMARY KEY (`idTipoPasaje`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD KEY `fk_usuarios_rol1_idx` (`idRol`),
  ADD KEY `fk_usuarios_estado1_idx` (`idEstado`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`idVehiculo`),
  ADD KEY `fk_vehiculos_estado1_idx` (`idEstado`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idVenta`),
  ADD KEY `fk_venta_clientes1_idx` (`idCliente`),
  ADD KEY `idViajeIda` (`idViajeIda`),
  ADD KEY `idViajeVuelta` (`idViajeVuelta`);

--
-- Indices de la tabla `viajes`
--
ALTER TABLE `viajes`
  ADD PRIMARY KEY (`idViaje`),
  ADD KEY `fk_Viajes_Conductor1_idx` (`idConductor`),
  ADD KEY `fk_Viajes_Transporte1_idx` (`idVehiculo`),
  ADD KEY `idDestino` (`idDestino`),
  ADD KEY `idOrigen` (`idOrigen`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT de la tabla `conductores`
--
ALTER TABLE `conductores`
  MODIFY `idConductor` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `idEstado` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `expotokens`
--
ALTER TABLE `expotokens`
  MODIFY `idExpoToken` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT de la tabla `news`
--
ALTER TABLE `news`
  MODIFY `idNews` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pasajes`
--
ALTER TABLE `pasajes`
  MODIFY `idpasaje` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
--
-- AUTO_INCREMENT de la tabla `PersonalData`
--
ALTER TABLE `PersonalData`
  MODIFY `idPersonalData` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;
--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idRol` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tipoPasaje`
--
ALTER TABLE `tipoPasaje`
  MODIFY `idTipoPasaje` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `idVehiculo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idVenta` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;
--
-- AUTO_INCREMENT de la tabla `viajes`
--
ALTER TABLE `viajes`
  MODIFY `idViaje` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7042;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_clientes_PersonalData1` FOREIGN KEY (`idPersonalData`) REFERENCES `PersonalData` (`idPersonalData`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `conductores`
--
ALTER TABLE `conductores`
  ADD CONSTRAINT `fk_conductores_PersonalData1` FOREIGN KEY (`idPersonalData`) REFERENCES `PersonalData` (`idPersonalData`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `expotokens`
--
ALTER TABLE `expotokens`
  ADD CONSTRAINT `expotokens_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `pasajes`
--
ALTER TABLE `pasajes`
  ADD CONSTRAINT `fk_pasajes_clientes1` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pasajes_tipoPasaje1` FOREIGN KEY (`idTipoPasaje`) REFERENCES `tipoPasaje` (`idTipoPasaje`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pasajes_venta1` FOREIGN KEY (`idVenta`) REFERENCES `venta` (`idVenta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `PersonalData`
--
ALTER TABLE `PersonalData`
  ADD CONSTRAINT `fk_PersonalData_usuarios1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_estado1` FOREIGN KEY (`idEstado`) REFERENCES `estado` (`idEstado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuarios_rol1` FOREIGN KEY (`idRol`) REFERENCES `rol` (`idRol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `fk_vehiculos_estado1` FOREIGN KEY (`idEstado`) REFERENCES `estado` (`idEstado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_clientes1` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`idViajeIda`) REFERENCES `viajes` (`idViaje`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`idViajeVuelta`) REFERENCES `viajes` (`idViaje`);

--
-- Filtros para la tabla `viajes`
--
ALTER TABLE `viajes`
  ADD CONSTRAINT `fk_Viajes_Conductor1` FOREIGN KEY (`idConductor`) REFERENCES `conductores` (`idConductor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Viajes_Transporte1` FOREIGN KEY (`idVehiculo`) REFERENCES `vehiculos` (`idVehiculo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `viajes_ibfk_1` FOREIGN KEY (`idDestino`) REFERENCES `places` (`idPlace`),
  ADD CONSTRAINT `viajes_ibfk_2` FOREIGN KEY (`idOrigen`) REFERENCES `places` (`idPlace`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
