-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 04-05-2020 a las 20:58:44
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
-- Base de datos: `placa_client`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accion`
--

CREATE TABLE `accion` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `metodo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `status` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `accion`
--

INSERT INTO `accion` (`id`, `descripcion`, `metodo`, `status`) VALUES
(1, 'Agregar', 'POST', 'T'),
(2, 'Modificar', 'PUT', 'T'),
(3, 'Eliminar', 'DELETE', 'T'),
(4, 'Consultar', 'GET', 'T');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autoH`
--

CREATE TABLE `autoH` (
  `id` int(11) NOT NULL,
  `cod_placa` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` datetime NOT NULL,
  `e_s` char(255) COLLATE utf8_spanish_ci NOT NULL,
  `cod_ubicacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autos`
--

CREATE TABLE `autos` (
  `id` int(11) NOT NULL,
  `marca` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `modelo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cod_placa` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `last_action` varchar(1) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cod_ubicacion` int(11) NOT NULL,
  `e_s` char(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `status` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auto_persona`
--

CREATE TABLE `auto_persona` (
  `id` int(11) NOT NULL,
  `cod_auto` int(11) NOT NULL,
  `cod_persona` int(11) NOT NULL,
  `status` char(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auto_ubicacion`
--

CREATE TABLE `auto_ubicacion` (
  `id` int(11) NOT NULL,
  `cod_auto` int(11) NOT NULL,
  `cod_ubicacion` int(11) NOT NULL,
  `status` char(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispositivos`
--

CREATE TABLE `dispositivos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cod_ubicacion` int(11) NOT NULL,
  `cod_serial` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresoT`
--

CREATE TABLE `ingresoT` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `razon` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cod_persona` int(11) NOT NULL,
  `cod_placa` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `marca` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `modelo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fec_on` date NOT NULL,
  `fec_off` date NOT NULL,
  `cod_ubicacion` int(11) NOT NULL,
  `e_s` char(255) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `status` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id`, `descripcion`, `direccion`, `status`) VALUES
(1, 'Autos', '/autos', 'T'),
(2, 'Ubicaciones Autos', '/autosUbic', 'T'),
(3, 'Autos Historial', '/autosH', 'T'),
(4, 'Personas', '/personas', 'T'),
(5, 'Ubicaciones Personas', '/personasAuto', 'T'),
(6, 'Ingreso Temporal', '/ingresoT', 'T'),
(7, 'Ubicaciones', '/ubicacion', 'T'),
(8, 'Tipo Ubicaciones', '/ubicacionTipo', 'T'),
(9, 'Usuario', '/usuario', 'T'),
(10, 'Ubicaciones Usuario', '/usuarioUbic', 'T'),
(11, 'Permisos', '/permiso', 'T'),
(12, 'Permisos Roll', '/permisoRoll', 'T'),
(13, 'Permisos Usuario', '/permisoUsuario', 'T');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso_roll`
--

CREATE TABLE `permiso_roll` (
  `id` int(11) NOT NULL,
  `cod_permiso` int(11) NOT NULL,
  `cod_roll` int(11) NOT NULL,
  `cod_accion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso_usuario`
--

CREATE TABLE `permiso_usuario` (
  `id` int(11) NOT NULL,
  `cod_permiso` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `cod_accion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `nombres` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `documento` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `status` char(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roll`
--

CREATE TABLE `roll` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `SU` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'F',
  `status` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` int(11) NOT NULL,
  `status` char(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion_tipo`
--

CREATE TABLE `ubicacion_tipo` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `status` char(1) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `codigo_acceso` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `roll` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `logIn` char(255) COLLATE utf8_spanish_ci NOT NULL,
  `status` char(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_ubicacion`
--

CREATE TABLE `usuario_ubicacion` (
  `cod_usuario` int(11) NOT NULL,
  `cod_ubicacion` int(11) NOT NULL,
  `status` char(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accion`
--
ALTER TABLE `accion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `autoH`
--
ALTER TABLE `autoH`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_AH_ubic` (`cod_ubicacion`);

--
-- Indices de la tabla `autos`
--
ALTER TABLE `autos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_auto_ubic` (`cod_ubicacion`);

--
-- Indices de la tabla `auto_persona`
--
ALTER TABLE `auto_persona`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PA_per` (`cod_persona`),
  ADD KEY `fk_PA_auto` (`cod_auto`);

--
-- Indices de la tabla `auto_ubicacion`
--
ALTER TABLE `auto_ubicacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_AU_ubic` (`cod_ubicacion`) USING BTREE,
  ADD KEY `fk_AU_auto` (`cod_auto`) USING BTREE;

--
-- Indices de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_disp_ubic` (`cod_ubicacion`);

--
-- Indices de la tabla `ingresoT`
--
ALTER TABLE `ingresoT`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_IT_per` (`cod_persona`),
  ADD KEY `fk_IT_ubic` (`cod_ubicacion`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permiso_roll`
--
ALTER TABLE `permiso_roll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PR_per` (`cod_permiso`),
  ADD KEY `fk_PR_roll` (`cod_roll`),
  ADD KEY `fk_PR_ac` (`cod_accion`);

--
-- Indices de la tabla `permiso_usuario`
--
ALTER TABLE `permiso_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PU_per` (`cod_permiso`) USING BTREE,
  ADD KEY `fk_PU_us` (`cod_usuario`) USING BTREE,
  ADD KEY `fk_PU_ac` (`cod_accion`) USING BTREE;

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roll`
--
ALTER TABLE `roll`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipo_ubicacion` (`tipo`);

--
-- Indices de la tabla `ubicacion_tipo`
--
ALTER TABLE `ubicacion_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_U_roll` (`roll`);

--
-- Indices de la tabla `usuario_ubicacion`
--
ALTER TABLE `usuario_ubicacion`
  ADD KEY `fk_UU_ubic` (`cod_ubicacion`) USING BTREE,
  ADD KEY `fk_UU_us` (`cod_usuario`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accion`
--
ALTER TABLE `accion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `autoH`
--
ALTER TABLE `autoH`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `autos`
--
ALTER TABLE `autos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auto_persona`
--
ALTER TABLE `auto_persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auto_ubicacion`
--
ALTER TABLE `auto_ubicacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresoT`
--
ALTER TABLE `ingresoT`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `permiso_roll`
--
ALTER TABLE `permiso_roll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso_usuario`
--
ALTER TABLE `permiso_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roll`
--
ALTER TABLE `roll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubicacion_tipo`
--
ALTER TABLE `ubicacion_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `autoH`
--
ALTER TABLE `autoH`
  ADD CONSTRAINT `fk_AH_ubic` FOREIGN KEY (`cod_ubicacion`) REFERENCES `ubicaciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `autos`
--
ALTER TABLE `autos`
  ADD CONSTRAINT `fk_auto_ubic` FOREIGN KEY (`cod_ubicacion`) REFERENCES `ubicaciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `auto_persona`
--
ALTER TABLE `auto_persona`
  ADD CONSTRAINT `fk_PA_auto` FOREIGN KEY (`cod_auto`) REFERENCES `autos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PA_per` FOREIGN KEY (`cod_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `auto_ubicacion`
--
ALTER TABLE `auto_ubicacion`
  ADD CONSTRAINT `fk_AU_auto` FOREIGN KEY (`cod_auto`) REFERENCES `autos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_AU_ubic` FOREIGN KEY (`cod_ubicacion`) REFERENCES `ubicaciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD CONSTRAINT `fk_disp_ubic` FOREIGN KEY (`cod_ubicacion`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ingresoT`
--
ALTER TABLE `ingresoT`
  ADD CONSTRAINT `fk_IT_per` FOREIGN KEY (`cod_persona`) REFERENCES `personas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_IT_ubic` FOREIGN KEY (`cod_ubicacion`) REFERENCES `ubicaciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permiso_roll`
--
ALTER TABLE `permiso_roll`
  ADD CONSTRAINT `fk_PR_ac` FOREIGN KEY (`cod_accion`) REFERENCES `accion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_PR_per` FOREIGN KEY (`cod_permiso`) REFERENCES `permiso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_PR_roll` FOREIGN KEY (`cod_roll`) REFERENCES `roll` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permiso_usuario`
--
ALTER TABLE `permiso_usuario`
  ADD CONSTRAINT `fk_PU_ac` FOREIGN KEY (`cod_accion`) REFERENCES `accion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_PU_per` FOREIGN KEY (`cod_permiso`) REFERENCES `permiso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_PU_us` FOREIGN KEY (`cod_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD CONSTRAINT `fk_tipo_ubicacion` FOREIGN KEY (`tipo`) REFERENCES `ubicacion_tipo` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_U_roll` FOREIGN KEY (`roll`) REFERENCES `roll` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario_ubicacion`
--
ALTER TABLE `usuario_ubicacion`
  ADD CONSTRAINT `fk_UU_ubic` FOREIGN KEY (`cod_ubicacion`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_UU_us` FOREIGN KEY (`cod_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
