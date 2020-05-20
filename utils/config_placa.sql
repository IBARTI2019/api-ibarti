-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 04-05-2020 a las 20:59:25
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `config_placa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_clientes`
--

CREATE TABLE `tbl_clientes` (
  `id` int(11) NOT NULL,
  `cod_cliente` varchar(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `identidad` varchar(50) NOT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `cuidad` varchar(255) DEFAULT NULL,
  `addr_principal` varchar(255) DEFAULT NULL,
  `represenate` varchar(255) DEFAULT NULL,
  `ci_representante` varchar(255) DEFAULT NULL,
  `tlf_representante` varchar(255) DEFAULT NULL,
  `status` int(2) NOT NULL,
  `datecreated` datetime DEFAULT NULL,
  `datevence` datetime DEFAULT NULL,
  `database` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `tbl_clientes`
--
DELIMITER $$
CREATE TRIGGER `insert_client_user` AFTER INSERT ON `tbl_clientes` FOR EACH ROW BEGIN
   INSERT INTO tbl_usuario (email,pass,db,cod_cliente) VALUES (new.email,'',new.database,new.cod_cliente);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_dispositivos`
--

CREATE TABLE `tbl_dispositivos` (
  `id` int(11) NOT NULL,
  `cod_dispositivo` varchar(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `addr_dispositvo` varchar(255) DEFAULT NULL,
  `coordenadas` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `port` varchar(255) DEFAULT NULL,
  `campo1` varchar(255) DEFAULT NULL,
  `campo2` varchar(255) DEFAULT NULL,
  `campo3` varchar(255) DEFAULT NULL,
  `cod_cliente` varchar(11) NOT NULL,
  `email` varchar(100) DEFAULT '',
  `url_arduino` varchar(100) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuario`
--

CREATE TABLE `tbl_usuario` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `db` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cod_cliente` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_clientes`
--
ALTER TABLE `tbl_clientes`
  ADD PRIMARY KEY (`id`,`cod_cliente`,`email`),
  ADD KEY `cod_cliente` (`cod_cliente`);

--
-- Indices de la tabla `tbl_dispositivos`
--
ALTER TABLE `tbl_dispositivos`
  ADD PRIMARY KEY (`id`,`cod_dispositivo`,`url_arduino`);

--
-- Indices de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_clientes`
--
ALTER TABLE `tbl_clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_dispositivos`
--
ALTER TABLE `tbl_dispositivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
