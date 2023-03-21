-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-03-2023 a las 06:04:35
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
-- Base de datos: `website`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caracteristicas`
--

CREATE TABLE `caracteristicas` (
  `id` int(11) NOT NULL,
  `caracteristicas` varchar(30) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `caracteristicas`
--

INSERT INTO `caracteristicas` (`id`, `caracteristicas`, `estado`) VALUES
(1, 'talla', 1),
(2, 'color', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caract_productos`
--

CREATE TABLE `caract_productos` (
  `id` int(11) NOT NULL,
  `id_productos` int(11) NOT NULL,
  `id_caracteristicas` int(11) NOT NULL,
  `valor` varchar(30) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `caract_productos`
--

INSERT INTO `caract_productos` (`id`, `id_productos`, `id_caracteristicas`, `valor`, `stock`) VALUES
(9, 1, 1, '22', 5),
(10, 1, 1, '24', 3),
(11, 1, 1, '25', 4),
(12, 1, 1, '26', 6),
(13, 1, 2, 'azul', 5),
(14, 1, 2, 'verde', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `id_transaccion` varchar(20) NOT NULL,
  `fecha` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_cliente` varchar(20) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `id_transaccion`, `fecha`, `status`, `email`, `id_cliente`, `total`) VALUES
(1, '37K95227MN247452W', '2023-03-10 04:57:39', 'COMPLETED', 'sb-sgmr316476136@personal.example.com', 'FNB4Y2L7LB6ZJ', '3625.50'),
(2, '2NS24580GG583114K', '2023-03-11 03:15:00', 'COMPLETED', 'sb-sgmr316476136@personal.example.com', 'FNB4Y2L7LB6ZJ', '3083.50'),
(3, '21S503293B5400119', '2023-03-11 07:37:41', 'COMPLETED', 'sb-sgmr316476136@personal.example.com', 'FNB4Y2L7LB6ZJ', '725.50'),
(4, '6B541449HC381661P', '2023-03-16 06:24:44', 'COMPLETED', 'sb-sgmr316476136@personal.example.com', 'FNB4Y2L7LB6ZJ', '4932.00'),
(5, '5PB388598R1153918', '2023-03-17 20:01:25', 'COMPLETED', 'sb-sgmr316476136@personal.example.com', 'FNB4Y2L7LB6ZJ', '617.50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_productos` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id`, `id_compra`, `id_productos`, `nombre`, `precio`, `cantidad`) VALUES
(1, 1, 1, 'polera azul', '108.00', 1),
(2, 1, 2, 'zapatillas blancas', '617.50', 1),
(3, 1, 4, 'polo balenciaga', '650.00', 1),
(4, 1, 3, 'pc gamer intel core i10 primera generacion', '2250.00', 1),
(5, 2, 3, 'pc gamer intel core i10 primera generacion', '2250.00', 1),
(6, 2, 2, 'zapatillas blancas', '617.50', 1),
(7, 2, 1, 'polera azul', '108.00', 2),
(8, 3, 1, 'polera azul', '108.00', 1),
(9, 3, 2, 'zapatillas blancas', '617.50', 1),
(10, 4, 1, 'polera azul', '108.00', 4),
(11, 4, 3, 'pc gamer intel core i10 primera generacion', '2250.00', 2),
(12, 5, 2, 'zapatillas blancas', '617.50', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `nombres` varchar(80) NOT NULL,
  `apellidos` varchar(80) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` char(9) NOT NULL,
  `dni` char(8) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `fechaCreacion` date NOT NULL,
  `horaCreacion` time NOT NULL,
  `fechaModificacion` date DEFAULT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `nombres`, `apellidos`, `email`, `telefono`, `dni`, `estado`, `fechaCreacion`, `horaCreacion`, `fechaModificacion`, `fechaBaja`) VALUES
(1, 'malvi', 'firulais', 'fabriziso2@gmail.com', '987654365', '98752132', 1, '2023-03-21', '00:01:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descuento` tinyint(3) NOT NULL DEFAULT 0,
  `id_categoria` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `descuento`, `id_categoria`, `estado`) VALUES
(1, 'polera azul', 'polera de color azul pe on', '120.00', 10, 1, 1),
(2, 'zapatillas blancas', 'zapatillas para damas', '650.00', 5, 1, 1),
(3, 'pc gamer intel core i10 primera generacion', 'pc gamer para todo tipo de video juegos', '4500.00', 50, 1, 1),
(4, 'polo balenciaga', 'polo de colores', '650.00', 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `claveAcceso` varchar(50) NOT NULL,
  `activacion` int(11) NOT NULL DEFAULT 0,
  `token` varchar(40) NOT NULL,
  `tokenRecuperar` varchar(40) DEFAULT NULL,
  `claveAcceso_Request` int(11) NOT NULL DEFAULT 0,
  `id_personas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `claveAcceso`, `activacion`, `token`, `tokenRecuperar`, `claveAcceso_Request`, `id_personas`) VALUES
(1, 'fabrizio12', '$2y$10$7udDkp3DLvYNe01g6r5Y3.OIwvBsQr5w9rEkIBzsOM.', 0, 'b2a120342c33b85f772b84d25f1ee6c3', NULL, 0, 1),
(2, 'juan loco', '$2y$10$sy5IORzFZbwtHcdpswRWL.DpdNS8KNPbjJ4cZobzljw', 0, 'c181161aaa37a5ed7c2ab004ab78c810', NULL, 0, 2),
(3, 'fusti', '$2y$10$XTv/BrOfQJMyIY3C6iBYHeE/M1shcA6X9gVA2M5jcCv', 0, '74d7667e62c10417fcfddc90c1b25cf6', NULL, 0, 3),
(4, 'fabricu', '$2y$10$de1MKxDpx68koMMtrdGlW.ngSwp4Bg8qmqLewJtAmZS', 0, 'bc12266c7cf372d6efd5c6e176381827', NULL, 0, 4),
(5, 'fabrizio124', '$2y$10$VTFyQ0cxadErDL9fBRSNgOWA4m6jBECRBPlvixKmvHJ', 0, '84ec73cb625467b6273e72f49b87d643', NULL, 0, 5),
(6, 'malvin', '$2y$10$O6JO6EEdo8O3HmuJQkHYSessk24AKek5iHCfdN9U14o', 0, 'a465363ddb55e4d4b53517355414f722', NULL, 0, 6);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `caract_productos`
--
ALTER TABLE `caract_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_caract_prod` (`id_productos`),
  ADD KEY `fk_detcaracter` (`id_caracteristicas`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `caract_productos`
--
ALTER TABLE `caract_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `caract_productos`
--
ALTER TABLE `caract_productos`
  ADD CONSTRAINT `fk_caract_prod` FOREIGN KEY (`id_productos`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `fk_detcaracter` FOREIGN KEY (`id_caracteristicas`) REFERENCES `caracteristicas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
