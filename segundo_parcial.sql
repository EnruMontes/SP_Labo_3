-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-07-2024 a las 06:01:54
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
-- Base de datos: `segundo_parcial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tienda`
--

CREATE TABLE `tienda` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `precio` int(11) NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `talla` varchar(2) NOT NULL,
  `color` varchar(15) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tienda`
--

INSERT INTO `tienda` (`id`, `nombre`, `precio`, `tipo`, `talla`, `color`, `stock`) VALUES
(1, 'Levis', 2000, 'Camiseta', 'M', 'negra', 156),
(3, 'Nike', 4000, 'Pantalon', 'S', 'rojo', 20),
(5, 'Adidas', 4000, 'Pantalon', 'L', 'azul', 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `usuario` varchar(25) NOT NULL,
  `contrasenia` varchar(20) NOT NULL,
  `perfil` varchar(25) NOT NULL,
  `foto` varchar(70) DEFAULT NULL,
  `fecha_de_alta` date DEFAULT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `mail`, `usuario`, `contrasenia`, `perfil`, `foto`, `fecha_de_alta`, `fecha_de_baja`) VALUES
(2, 'enru@gmail.com', 'enrico', 'Enru123', 'admin', NULL, '2024-07-01', '0000-00-00'),
(3, 'jose@gmail.com', 'jose', 'Jose123', 'empleado', NULL, '2024-07-01', NULL),
(5, 'agustin@gmail.com', 'agustin', 'Agustin123', 'admin', NULL, '2024-07-01', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `talla` varchar(2) NOT NULL,
  `stock` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `nroPedido` int(11) NOT NULL,
  `precio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `email`, `nombre`, `tipo`, `talla`, `stock`, `fecha`, `nroPedido`, `precio`) VALUES
(1, 'enrico@gmail.com', 'Levis', 'Camiseta', 'S', 3, '2024-07-01', 1, 2000),
(2, 'jose@gmail.com', 'Adidas', 'Pantalon', 'L', 3, '2024-06-30', 2, 3000),
(3, 'ff@gmail.com', 'Levis', 'Camiseta', 'M', 6, '2024-07-01', 3, 4000),
(5, 'asdasd@gmail.com', 'Adidas', 'Pantalon', 'L', 2, '2024-07-01', 4, 7000);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tienda`
--
ALTER TABLE `tienda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tienda`
--
ALTER TABLE `tienda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
