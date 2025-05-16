-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2025 a las 23:05:51
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
-- Base de datos: `mesa_ayuda`
--
CREATE DATABASE IF NOT EXISTS `mesa_ayuda` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mesa_ayuda`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

DROP TABLE IF EXISTS `administradores`;
CREATE TABLE `administradores` (
  `id_administrador` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `administradores`
--

TRUNCATE TABLE `administradores`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos_adjuntos`
--

DROP TABLE IF EXISTS `archivos_adjuntos`;
CREATE TABLE `archivos_adjuntos` (
  `id_archivo` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `tipo_archivo` varchar(100) NOT NULL,
  `tamano` int(11) NOT NULL,
  `fecha_subida` datetime NOT NULL DEFAULT current_timestamp(),
  `id_ticket` int(11) DEFAULT NULL,
  `id_intervencion` int(11) DEFAULT NULL,
  `id_articulo` int(11) DEFAULT NULL,
  `id_equipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `archivos_adjuntos`
--

TRUNCATE TABLE `archivos_adjuntos`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_tickets`
--

DROP TABLE IF EXISTS `articulos_tickets`;
CREATE TABLE `articulos_tickets` (
  `id` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `fecha_relacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `articulos_tickets`
--

TRUNCATE TABLE `articulos_tickets`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_pc`
--

DROP TABLE IF EXISTS `asignacion_pc`;
CREATE TABLE `asignacion_pc` (
  `id_asignacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `fecha_asignacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_devolucion` datetime DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `asignacion_pc`
--

TRUNCATE TABLE `asignacion_pc`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `base_conocimiento`
--

DROP TABLE IF EXISTS `base_conocimiento`;
CREATE TABLE `base_conocimiento` (
  `id_articulo` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_autor` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `palabras_clave` varchar(255) DEFAULT NULL,
  `publico` tinyint(1) NOT NULL DEFAULT 1,
  `vistas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `base_conocimiento`
--

TRUNCATE TABLE `base_conocimiento`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tiempo_respuesta_estimado` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `categoria_padre` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `categorias`
--

TRUNCATE TABLE `categorias`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
CREATE TABLE `departamentos` (
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `responsable` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `departamentos`
--

TRUNCATE TABLE `departamentos`;
--
-- Volcado de datos para la tabla `departamentos`
--

INSERT DELAYED IGNORE INTO `departamentos` (`id_departamento`, `nombre`, `descripcion`, `responsable`, `telefono`, `correo`, `activo`) VALUES
(1, 'Tecnologías de la Información (TI)', 'Departamento encargado de la infraestructura tecnológica y soporte técnico.', 'Juan Pérez', '555-1234', 'j.perez@empresa.com', 1),
(2, 'Recursos Humanos (RRHH)', 'Departamento de gestión de personal y contratación.', 'Ana Gómez', '555-5678', 'a.gomez@empresa.com', 1),
(3, 'Finanzas', 'Departamento encargado de la contabilidad y finanzas de la empresa.', 'Carlos López', '555-8765', 'c.lopez@empresa.com', 1),
(4, 'Ventas', 'Departamento comercial y de ventas.', 'Sofía Torres', '555-4321', 's.torres@empresa.com', 1),
(5, 'Marketing', 'Departamento de publicidad y estrategias de mercadeo.', 'Luis Rivas', '555-1122', 'l.rivas@empresa.com', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

DROP TABLE IF EXISTS `estados`;
CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `es_final` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `estados`
--

TRUNCATE TABLE `estados`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoja_vida_pc`
--

DROP TABLE IF EXISTS `hoja_vida_pc`;
CREATE TABLE `hoja_vida_pc` (
  `id_equipo` int(11) NOT NULL,
  `codigo_inventario` varchar(50) NOT NULL,
  `tipo_equipo` varchar(50) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `numero_serie` varchar(50) DEFAULT NULL,
  `procesador` varchar(100) DEFAULT NULL,
  `memoria_ram` varchar(50) DEFAULT NULL,
  `disco_duro` varchar(50) DEFAULT NULL,
  `sistema_operativo` varchar(100) DEFAULT NULL,
  `fecha_compra` date DEFAULT NULL,
  `fecha_garantia` date DEFAULT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `id_ubicacion` int(11) DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `hoja_vida_pc`
--

TRUNCATE TABLE `hoja_vida_pc`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intervenciones`
--

DROP TABLE IF EXISTS `intervenciones`;
CREATE TABLE `intervenciones` (
  `id_intervencion` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `id_tecnico` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp(),
  `descripcion` text NOT NULL,
  `solucion` text DEFAULT NULL,
  `tiempo_dedicado` int(11) DEFAULT NULL,
  `estado_anterior` int(11) DEFAULT NULL,
  `estado_nuevo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `intervenciones`
--

TRUNCATE TABLE `intervenciones`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

DROP TABLE IF EXISTS `prestamos`;
CREATE TABLE `prestamos` (
  `id_prestamo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tecnico` int(11) NOT NULL,
  `tipo_item` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `numero_serie` varchar(50) DEFAULT NULL,
  `fecha_prestamo` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_devolucion_estimada` datetime NOT NULL,
  `fecha_devolucion_real` datetime DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `prestamos`
--

TRUNCATE TABLE `prestamos`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridades`
--

DROP TABLE IF EXISTS `prioridades`;
CREATE TABLE `prioridades` (
  `id_prioridad` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `nivel` int(11) NOT NULL,
  `color` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `prioridades`
--

TRUNCATE TABLE `prioridades`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supervisores`
--

DROP TABLE IF EXISTS `supervisores`;
CREATE TABLE `supervisores` (
  `id_supervisor` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `id_departamento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `supervisores`
--

TRUNCATE TABLE `supervisores`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

DROP TABLE IF EXISTS `tecnicos`;
CREATE TABLE `tecnicos` (
  `id_tecnico` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `especialidad` varchar(100) DEFAULT NULL,
  `id_supervisor` int(11) DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `tecnicos`
--

TRUNCATE TABLE `tecnicos`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id_ticket` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_cierre` datetime DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tecnico` int(11) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_prioridad` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `tiempo_resolucion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `tickets`
--

TRUNCATE TABLE `tickets`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

DROP TABLE IF EXISTS `ubicaciones`;
CREATE TABLE `ubicaciones` (
  `id_ubicacion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `edificio` varchar(100) DEFAULT NULL,
  `piso` varchar(20) DEFAULT NULL,
  `sala` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `ubicaciones`
--

TRUNCATE TABLE `ubicaciones`;
--
-- Volcado de datos para la tabla `ubicaciones`
--

INSERT DELAYED IGNORE INTO `ubicaciones` (`id_ubicacion`, `nombre`, `edificio`, `piso`, `sala`, `descripcion`, `activo`) VALUES
(1, 'Oficina Principal - Piso 1', 'Edificio Central', '1', 'Recepción', 'Recepción y área de espera del edificio principal.', 1),
(2, 'Sede Norte - Piso 3 - Ala A', 'Edificio Norte', '3', 'A-305', 'Oficinas del departamento de TI en la Sede Norte.', 1),
(3, 'Centro de Datos', 'Edificio Central', 'Sótano 1', 'Rack 05', 'Ubicación de servidores principales.', 1),
(4, 'Sala de Juntas Principal', 'Edificio Central', '2', 'Sala Magna', 'Sala principal para reuniones corporativas.', 1),
(5, 'Almacén Sur (Inactivo)', 'Bodega Sur', '1', 'N/A', 'Antiguo almacén, actualmente no en uso.', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `id_departamento` int(11) DEFAULT NULL,
  `id_ubicacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncar tablas antes de insertar `usuarios`
--

TRUNCATE TABLE `usuarios`;
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_administrador`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `archivos_adjuntos`
--
ALTER TABLE `archivos_adjuntos`
  ADD PRIMARY KEY (`id_archivo`),
  ADD KEY `id_ticket` (`id_ticket`),
  ADD KEY `id_intervencion` (`id_intervencion`),
  ADD KEY `id_articulo` (`id_articulo`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `articulos_tickets`
--
ALTER TABLE `articulos_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_articulo_ticket` (`id_articulo`,`id_ticket`),
  ADD KEY `id_ticket` (`id_ticket`);

--
-- Indices de la tabla `asignacion_pc`
--
ALTER TABLE `asignacion_pc`
  ADD PRIMARY KEY (`id_asignacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `base_conocimiento`
--
ALTER TABLE `base_conocimiento`
  ADD PRIMARY KEY (`id_articulo`),
  ADD KEY `id_autor` (`id_autor`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `categoria_padre` (`categoria_padre`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id_departamento`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `hoja_vida_pc`
--
ALTER TABLE `hoja_vida_pc`
  ADD PRIMARY KEY (`id_equipo`),
  ADD UNIQUE KEY `codigo_inventario` (`codigo_inventario`),
  ADD UNIQUE KEY `numero_serie` (`numero_serie`),
  ADD KEY `id_ubicacion` (`id_ubicacion`);

--
-- Indices de la tabla `intervenciones`
--
ALTER TABLE `intervenciones`
  ADD PRIMARY KEY (`id_intervencion`),
  ADD KEY `id_ticket` (`id_ticket`),
  ADD KEY `id_tecnico` (`id_tecnico`),
  ADD KEY `estado_anterior` (`estado_anterior`),
  ADD KEY `estado_nuevo` (`estado_nuevo`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_tecnico` (`id_tecnico`);

--
-- Indices de la tabla `prioridades`
--
ALTER TABLE `prioridades`
  ADD PRIMARY KEY (`id_prioridad`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `supervisores`
--
ALTER TABLE `supervisores`
  ADD PRIMARY KEY (`id_supervisor`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `id_departamento` (`id_departamento`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id_tecnico`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `id_supervisor` (`id_supervisor`),
  ADD KEY `id_departamento` (`id_departamento`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_tecnico` (`id_tecnico`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_prioridad` (`id_prioridad`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id_ubicacion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `id_departamento` (`id_departamento`),
  ADD KEY `id_ubicacion` (`id_ubicacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `archivos_adjuntos`
--
ALTER TABLE `archivos_adjuntos`
  MODIFY `id_archivo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `articulos_tickets`
--
ALTER TABLE `articulos_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignacion_pc`
--
ALTER TABLE `asignacion_pc`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `base_conocimiento`
--
ALTER TABLE `base_conocimiento`
  MODIFY `id_articulo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hoja_vida_pc`
--
ALTER TABLE `hoja_vida_pc`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `intervenciones`
--
ALTER TABLE `intervenciones`
  MODIFY `id_intervencion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prioridades`
--
ALTER TABLE `prioridades`
  MODIFY `id_prioridad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `supervisores`
--
ALTER TABLE `supervisores`
  MODIFY `id_supervisor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id_tecnico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivos_adjuntos`
--
ALTER TABLE `archivos_adjuntos`
  ADD CONSTRAINT `archivos_adjuntos_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`),
  ADD CONSTRAINT `archivos_adjuntos_ibfk_2` FOREIGN KEY (`id_intervencion`) REFERENCES `intervenciones` (`id_intervencion`),
  ADD CONSTRAINT `archivos_adjuntos_ibfk_3` FOREIGN KEY (`id_articulo`) REFERENCES `base_conocimiento` (`id_articulo`),
  ADD CONSTRAINT `archivos_adjuntos_ibfk_4` FOREIGN KEY (`id_equipo`) REFERENCES `hoja_vida_pc` (`id_equipo`);

--
-- Filtros para la tabla `articulos_tickets`
--
ALTER TABLE `articulos_tickets`
  ADD CONSTRAINT `articulos_tickets_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `base_conocimiento` (`id_articulo`),
  ADD CONSTRAINT `articulos_tickets_ibfk_2` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`);

--
-- Filtros para la tabla `asignacion_pc`
--
ALTER TABLE `asignacion_pc`
  ADD CONSTRAINT `asignacion_pc_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `asignacion_pc_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `hoja_vida_pc` (`id_equipo`);

--
-- Filtros para la tabla `base_conocimiento`
--
ALTER TABLE `base_conocimiento`
  ADD CONSTRAINT `base_conocimiento_ibfk_1` FOREIGN KEY (`id_autor`) REFERENCES `tecnicos` (`id_tecnico`),
  ADD CONSTRAINT `base_conocimiento_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`categoria_padre`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `hoja_vida_pc`
--
ALTER TABLE `hoja_vida_pc`
  ADD CONSTRAINT `hoja_vida_pc_ibfk_1` FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicaciones` (`id_ubicacion`);

--
-- Filtros para la tabla `intervenciones`
--
ALTER TABLE `intervenciones`
  ADD CONSTRAINT `intervenciones_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`),
  ADD CONSTRAINT `intervenciones_ibfk_2` FOREIGN KEY (`id_tecnico`) REFERENCES `tecnicos` (`id_tecnico`),
  ADD CONSTRAINT `intervenciones_ibfk_3` FOREIGN KEY (`estado_anterior`) REFERENCES `estados` (`id_estado`),
  ADD CONSTRAINT `intervenciones_ibfk_4` FOREIGN KEY (`estado_nuevo`) REFERENCES `estados` (`id_estado`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`id_tecnico`) REFERENCES `tecnicos` (`id_tecnico`);

--
-- Filtros para la tabla `supervisores`
--
ALTER TABLE `supervisores`
  ADD CONSTRAINT `supervisores_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `departamentos` (`id_departamento`);

--
-- Filtros para la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD CONSTRAINT `tecnicos_ibfk_1` FOREIGN KEY (`id_supervisor`) REFERENCES `supervisores` (`id_supervisor`),
  ADD CONSTRAINT `tecnicos_ibfk_2` FOREIGN KEY (`id_departamento`) REFERENCES `departamentos` (`id_departamento`);

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`id_tecnico`) REFERENCES `tecnicos` (`id_tecnico`),
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `tickets_ibfk_4` FOREIGN KEY (`id_prioridad`) REFERENCES `prioridades` (`id_prioridad`),
  ADD CONSTRAINT `tickets_ibfk_5` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`),
  ADD CONSTRAINT `tickets_ibfk_6` FOREIGN KEY (`id_equipo`) REFERENCES `hoja_vida_pc` (`id_equipo`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `departamentos` (`id_departamento`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicaciones` (`id_ubicacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
