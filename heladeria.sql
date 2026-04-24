-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-12-2025 a las 02:00:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `heladeria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `ID_AREA` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`ID_AREA`, `NOMBRE`) VALUES
(1, 'Heladería'),
(2, 'Tíenda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto`
--

CREATE TABLE `gasto` (
  `ID_GASTO` int(11) NOT NULL,
  `DESCRIPCION` text NOT NULL,
  `FECHA` date NOT NULL,
  `VALOR` decimal(10,2) NOT NULL,
  `ID_AREA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `gasto`
--

INSERT INTO `gasto` (`ID_GASTO`, `DESCRIPCION`, `FECHA`, `VALOR`, `ID_AREA`) VALUES
(1, 'Compra de lacteos', '2025-11-26', 50050.00, 1),
(2, 'Compra de café', '2025-07-08', 80000.00, 2),
(3, 'Compra de frutas tropicales', '2025-09-24', 55000.00, 2),
(5, 'Mantenimiento refrigerador', '2025-07-05', 90000.00, 1),
(6, 'Compra de vasos desechables', '2025-07-04', 25000.00, 1),
(7, 'Compra de pan', '2025-07-03', 35000.00, 2),
(8, 'Pago recibo de gas', '2025-07-02', 40000.00, 2),
(17, 'pedido de cream helado', '2025-11-21', 500000.99, 1),
(19, 'Luz', '2025-11-19', 213434.00, 2),
(21, 'Pedido ramo', '2025-12-12', 3000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `ID_PERSONA` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `TELEFONO` varchar(30) NOT NULL,
  `CORREO` text NOT NULL,
  `CONTRASENA` varchar(255) NOT NULL,
  `ID_ROL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`ID_PERSONA`, `NOMBRE`, `TELEFONO`, `CORREO`, `CONTRASENA`, `ID_ROL`) VALUES
(1, 'Carlos Pérez Becerra', '3123310345', 'carlos33@gmail.com', '1243254532', 2),
(2, 'Laura Gómez', '3017654321', 'lauragom21@gmail.com', '', 1),
(3, 'María López', '3221112233', 'mariahd23@gmail.com', '', 1),
(5, 'Pedro Ramírez', '3113335566', 'Rami33@gmail.com', '$2y$10$BGe2eR6lQR5ftVrHxbhwQ.2HtSKy/SdNenD6KZpekR13R6qtjsqbm', 1),
(8, 'Distribuidora Láctea', '3205551111', 'ditributionlac@gmail.com', '', 2),
(9, 'Café sello rojo.', '3102223344', 'cafesello22@gmail.com', '$2y$10$BGe2eR6lQR5ftVrHxbhwQ.2HtSKy/SdNenD6KZpekR13R6qtjsqbm', 2),
(10, 'Helados Clombina', '3128889999', 'heladitoscom22@gmail.com', '$2y$10$BGe2eR6lQR5ftVrHxbhwQ.2HtSKy/SdNenD6KZpekR13R6qtjsqbm', 2),
(13, 'Nicolas Alba Perez', '3112840059', 'nikolasalba3302@gmail.com', '$2y$10$GKtr035GqvfIGmw6Wdm9Xu6Wjx4PxYvb2DBD7SPzNMuncLzpjBsgy', 3),
(16, 'Ramo', '3142746509', 'ramoofcial21@gmail.com', '', 2),
(17, 'samuel alba', '32132434345', 'samuelgamerhd7@gmail.com', '', 1),
(18, 'Ana Marta Maldonado', '3204303792', 'heladeriaycafeterialosabuelos@gmail.com', '', 3),
(19, 'Ruby esperanza perez maldonado', '3123310344', 'rubyjiji@gmail.com', '', 1),
(20, 'Omar alba burgos', '3123935760', 'omaralba22@gmail.com', '$2y$10$sQVIHhGU1ISfPLtooce0a.STNTQghqtRj5EulXuR9mpewl8HnGHhS', 6),
(21, 'Santiago Alba perez', '3212323245', 'santiago23@gmail.com', '$2y$10$E233K0sx38UmemBqNXukge4oj9Y.znBQ9U8TNHS7n0CASm0W8Atea', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ID_PRODUCTO` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `PRECIO_ACTUAL` decimal(10,2) NOT NULL,
  `STOCK` int(11) DEFAULT 0,
  `ID_AREA` int(11) NOT NULL,
  `ID_PROVEEDOR` int(11) NOT NULL,
  `IMAGEN` varchar(255) DEFAULT NULL,
  `DESTACADO` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ID_PRODUCTO`, `NOMBRE`, `PRECIO_ACTUAL`, `STOCK`, `ID_AREA`, `ID_PROVEEDOR`, `IMAGEN`, `DESTACADO`) VALUES
(18, 'Banan split', 6000.00, 33, 1, 10, 'Gemini_Generated_Image_i9mchki9mchki9mc.png', 1),
(19, 'Capuchino', 3000.00, 43, 2, 9, 'capuccino.jpg', 1),
(20, 'Milo', 3000.00, 23, 2, 9, 'milo.avif', 1),
(21, 'Chocolate', 3400.00, 27, 2, 9, 'chocolate.jpg', 1),
(22, 'Cafe', 2500.00, 35, 2, 9, 'cafe.jpg', 1),
(23, 'Gelatina con helado', 4500.00, 39, 1, 10, 'Gemini_Generated_Image_hnz295hnz295hnz2.png', 1),
(24, 'Oblea', 3000.00, 24, 1, 10, 'Gemini_Generated_Image_jsgd6mjsgd6mjsgd.png', 1),
(25, 'Ensalada de frutas con helado', 4500.00, 24, 1, 10, 'Gemini_Generated_Image_rkp7ldrkp7ldrkp7.png', 1),
(26, 'Chocorramo', 2700.00, 3, 2, 16, 'chocorramo.webp', 1),
(27, 'Leche de 1L', 2000.00, 9, 2, 8, 'Chocolate.webp', 0),
(28, 'Leche de 2L', 4500.00, 13, 2, 8, 'Fresa.webp', 0),
(29, 'Doritos clasicos pequeños', 2500.00, 5, 2, 1, '1646340869505_1646340866701.jpg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `ID_ROL` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`ID_ROL`, `NOMBRE`) VALUES
(1, 'Cliente'),
(2, 'Proveedor'),
(3, 'Administrador'),
(6, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `ID_VENTA` int(11) NOT NULL,
  `FECHA` date NOT NULL,
  `METODO_PAGO` varchar(40) NOT NULL,
  `TOTAL` decimal(10,2) NOT NULL,
  `ID_CLIENTE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`ID_VENTA`, `FECHA`, `METODO_PAGO`, `TOTAL`, `ID_CLIENTE`) VALUES
(11, '2025-12-06', 'Efectivo', 11000.00, 13),
(12, '2025-12-07', 'Efectivo', 13500.00, 17),
(16, '2025-12-07', 'Efectivo', 11300.00, 2),
(17, '2025-12-09', 'Nequi', 17900.00, 19),
(18, '2025-12-09', 'Efectivo', 7700.00, 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_detalle`
--

CREATE TABLE `venta_detalle` (
  `ID_DETALLE` int(11) NOT NULL,
  `ID_VENTA` int(11) NOT NULL,
  `ID_PRODUCTO` int(11) NOT NULL,
  `CANTIDAD` int(11) NOT NULL,
  `PRECIO_UNITARIO` decimal(10,2) NOT NULL,
  `SUBTOTAL` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `venta_detalle`
--

INSERT INTO `venta_detalle` (`ID_DETALLE`, `ID_VENTA`, `ID_PRODUCTO`, `CANTIDAD`, `PRECIO_UNITARIO`, `SUBTOTAL`) VALUES
(1, 11, 18, 1, 6000.00, 6000.00),
(2, 11, 22, 1, 2500.00, 2500.00),
(3, 11, 29, 1, 2500.00, 2500.00),
(4, 12, 26, 5, 2700.00, 13500.00),
(10, 16, 21, 2, 3400.00, 6800.00),
(11, 16, 23, 1, 4500.00, 4500.00),
(12, 17, 18, 2, 6000.00, 12000.00),
(13, 17, 21, 1, 3400.00, 3400.00),
(14, 17, 29, 1, 2500.00, 2500.00),
(15, 18, 24, 1, 3000.00, 3000.00),
(16, 18, 26, 1, 2700.00, 2700.00),
(17, 18, 27, 1, 2000.00, 2000.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`ID_AREA`);

--
-- Indices de la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD PRIMARY KEY (`ID_GASTO`),
  ADD KEY `ID_AREA` (`ID_AREA`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`ID_PERSONA`),
  ADD KEY `ID_ROL` (`ID_ROL`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`ID_PRODUCTO`),
  ADD KEY `ID_AREA` (`ID_AREA`),
  ADD KEY `ID_PROVEEDOR` (`ID_PROVEEDOR`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`ID_ROL`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`ID_VENTA`),
  ADD KEY `fk_venta_cliente` (`ID_CLIENTE`);

--
-- Indices de la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD PRIMARY KEY (`ID_DETALLE`),
  ADD KEY `ID_VENTA` (`ID_VENTA`),
  ADD KEY `ID_PRODUCTO` (`ID_PRODUCTO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `ID_AREA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `gasto`
--
ALTER TABLE `gasto`
  MODIFY `ID_GASTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `ID_PERSONA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ID_PRODUCTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `ID_ROL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `ID_VENTA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  MODIFY `ID_DETALLE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD CONSTRAINT `gasto_ibfk_1` FOREIGN KEY (`ID_AREA`) REFERENCES `area` (`ID_AREA`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`ID_ROL`) REFERENCES `rol` (`ID_ROL`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`ID_AREA`) REFERENCES `area` (`ID_AREA`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`ID_PROVEEDOR`) REFERENCES `persona` (`ID_PERSONA`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_cliente` FOREIGN KEY (`ID_CLIENTE`) REFERENCES `persona` (`ID_PERSONA`);

--
-- Filtros para la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD CONSTRAINT `venta_detalle_ibfk_1` FOREIGN KEY (`ID_VENTA`) REFERENCES `venta` (`ID_VENTA`),
  ADD CONSTRAINT `venta_detalle_ibfk_2` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `producto` (`ID_PRODUCTO`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
