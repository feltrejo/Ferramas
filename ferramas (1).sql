-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2024 a las 09:36:29
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
-- Base de datos: `ferramas`
--

CREATE DATABASE IF NOT EXISTS ferramas DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE ferramas;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `precio` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_tipo_producto` int(11) NOT NULL,
  `es_oferta` tinyint(1) DEFAULT 0,
  `descuento` int(11) DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `precio`, `nombre`, `descripcion`, `stock`, `id_tipo_producto`, `es_oferta`, `descuento`, `imagen`) VALUES
(1001, 9500, 'Martillo', 'Martillo de acero con mango de madera', 100, 1, 1, 25, NULL),
(1002, 2500, 'Destornillador', 'Destornillador de punta plana', 150, 1, 0, 0, NULL),
(1003, 1800, 'Llave Inglesa', 'Llave ajustable de acero inoxidable', 120, 1, 0, 0, NULL),
(1004, 3000, 'Alicate', 'Alicate de corte con recubrimiento antideslizante', 80, 1, 0, 0, NULL),
(1005, 1200, 'Cinta Métrica', 'Cinta métrica de 5 metros', 200, 1, 0, 0, NULL),
(2001, 65000, 'Taladro Eléctrico', 'Taladro eléctrico con múltiples velocidades brr br', 60, 2, 0, 0, NULL),
(2002, 75000, 'Sierra Eléctrica', 'Sierra eléctrica de precisión', 40, 2, 0, 0, NULL),
(2003, 52000, 'Amoladora', 'Amoladora de 900W', 50, 2, 0, 0, NULL),
(2004, 50000, 'Atornillador Eléctrico', 'Atornillador eléctrico inalámbrico', 30, 2, 0, 0, NULL),
(2005, 40000, 'Lijadora', 'Lijadora orbital de mano', 70, 2, 0, 0, NULL),
(3001, 12000, 'Llave de Impacto Neumática', 'Llave de impacto con motor neumático', 25, 3, 0, 0, NULL),
(3002, 9000, 'Martillo Neumático', 'Martillo neumático para demoliciones', 35, 3, 0, 0, NULL),
(3003, 13000, 'Pulidora Neumática', 'Pulidora neumática de alta potencia', 20, 3, 0, 0, NULL),
(3004, 10500, 'Cortadora Neumática', 'Cortadora neumática de metales', 15, 3, 0, 0, NULL),
(3005, 80000, 'Compresor de Aire', 'Compresor neumático portátil', 45, 3, 0, 0, NULL),
(4001, 22000, 'Gato Hidráulico', 'Gato hidráulico de 3 toneladas', 18, 4, 0, 0, NULL),
(4002, 18500, 'Cizalla Hidráulica', 'Cizalla hidráulica para corte de metal', 12, 4, 0, 0, NULL),
(4003, 24500, 'Prensa Hidráulica', 'Prensa hidráulica de 10 toneladas', 10, 4, 0, 0, NULL),
(4004, 30000, 'Elevador Hidráulico', 'Elevador hidráulico para vehículos', 8, 4, 0, 0, NULL),
(4005, 16000, 'Bomba Hidráulica', 'Bomba hidráulica portátil', 20, 4, 0, 0, NULL),
(5001, 95000, 'Prensa Mecánica', 'Prensa mecánica de precisión', 25, 5, 0, 0, NULL),
(5002, 11000, 'Torno Mecánico', 'Torno mecánico para metal', 10, 5, 0, 0, NULL),
(5003, 130000, 'Fresadora', 'Fresadora mecánica de alta precisión', 15, 5, 0, 0, NULL),
(5004, 8000, 'Taladro de Banco', 'Taladro de banco de 500W', 35, 5, 0, 0, NULL),
(5005, 7500, 'Esmeril de Banco', 'Esmeril mecánico de banco', 40, 5, 0, 0, NULL),
(6001, 5000, 'Calibrador Vernier', 'Calibrador vernier de acero inoxidable', 120, 6, 0, 0, NULL),
(6002, 8000, 'Micrómetro', 'Micrómetro de precisión', 60, 6, 0, 0, NULL),
(6003, 3500, 'Cinta Métrica de Precisión', 'Cinta métrica de 30 metros', 90, 6, 0, 0, NULL),
(6004, 4500, 'Nivela Láser', 'Nivela láser de alta precisión', 50, 6, 0, 0, NULL),
(6005, 2500, 'Regla de Acero', 'Regla de acero inoxidable de 30 cm', 150, 6, 0, 0, NULL),
(7001, 5500, 'Casco de Seguridad', 'Casco de seguridad para no golpearse la cabeza', 550, 7, 0, 0, NULL),
(7002, 3000, 'Guantes de Protección', 'Guantes de seguridad para trabajos pesados', 400, 7, 0, 0, NULL),
(7003, 666, 'Zapatos', 'Zapatos de Seguridad Seguros', 10, 7, 1, 50, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `id_tipo_producto` int(11) NOT NULL,
  `nombre_tipo` varchar(50) NOT NULL,
  `descripcion_tipo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`id_tipo_producto`, `nombre_tipo`, `descripcion_tipo`) VALUES
(1, 'Manual', 'Herramientas Manuales'),
(2, 'Eléctrico', 'Herramientas Eléctricas'),
(3, 'Neumático', 'Herramientas Neumáticas'),
(4, 'Hidráulico', 'Herramientas Hidráulicas'),
(5, 'Mecánico', 'Herramientas Mecánicas'),
(6, 'De Medición', 'Instrumentos de Medición'),
(7, 'De Seguridad', 'Equipos de Seguridad Industrial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'Pipetua', '$2y$10$spBiaMw2/c3mJOFYZ0RC3.k1861A1ECUoN4U6PuAuOamJTH/jz8C6', 'admin'),
(2, 'Ev', '$2y$10$spBiaMw2/c3mJOFYZ0RC3.k1861A1ECUoN4U6PuAuOamJTH/jz8C6', 'user'),
(3, 'pou', '$2y$10$spBiaMw2/c3mJOFYZ0RC3.k1861A1ECUoN4U6PuAuOamJTH/jz8C6', 'user'),
(5, 'cr', '$2y$10$spBiaMw2/c3mJOFYZ0RC3.k1861A1ECUoN4U6PuAuOamJTH/jz8C6', 'user'),
(6, 'miguelito', '$2y$10$spBiaMw2/c3mJOFYZ0RC3.k1861A1ECUoN4U6PuAuOamJTH/jz8C6', 'user');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD KEY `fk_tipo_producto` (`id_tipo_producto`);

--
-- Indices de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD PRIMARY KEY (`id_tipo_producto`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  MODIFY `id_tipo_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_tipo_producto` FOREIGN KEY (`id_tipo_producto`) REFERENCES `tipo_producto` (`id_tipo_producto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
