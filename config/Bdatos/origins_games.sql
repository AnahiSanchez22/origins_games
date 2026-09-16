-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-09-2026 a las 18:14:16
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `origins_games`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Juegos', 'Juegos físicos y digitales para consolas y PC'),
(2, 'Accesorios', 'Controles, audífonos, teclados y periféricos gamer'),
(3, 'Consolas', 'Sistemas de juego de última generación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `tipo_equipo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `estado` enum('pendiente','confirmada','completada','cancelada') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `usuario_id`, `servicio_id`, `tipo_equipo`, `fecha_cita`, `hora_cita`, `observaciones`, `estado`, `created_at`) VALUES
(1, 6, 2, 'dsc', '2026-08-26', '23:56:00', 'dvfx', 'cancelada', '2026-08-24 13:53:12'),
(2, 6, 1, 'xbox', '2026-09-04', '02:00:00', 'Le salio humo', 'cancelada', '2026-08-24 14:01:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_mantenimiento`
--

CREATE TABLE `citas_mantenimiento` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `tipo_equipo` varchar(100) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `observaciones` text,
  `estado` enum('pendiente','confirmada','completada','cancelada') DEFAULT 'pendiente',
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 19, 1, '34500.00'),
(2, 2, 21, 1, '134990.00'),
(3, 3, 16, 1, '923239.00'),
(4, 4, 22, 1, '59860.00'),
(5, 5, 13, 1, '34000.00'),
(6, 5, 20, 1, '220620.00'),
(7, 5, 16, 1, '923239.00'),
(8, 5, 14, 1, '4309700.00'),
(16, 13, 14, 1, '4309700.00'),
(17, 14, 19, 1, '34500.00'),
(18, 15, 14, 2, '4309700.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','pagado','enviado','cancelado') DEFAULT 'pendiente',
  `fecha_pedido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `total`, `estado`, `fecha_pedido`) VALUES
(1, 6, '34500.00', 'pagado', '2026-08-31 15:18:38'),
(2, 6, '134990.00', 'pagado', '2026-08-31 16:42:25'),
(3, 7, '923239.00', 'pagado', '2026-08-31 16:49:19'),
(4, 6, '59860.00', 'pagado', '2026-09-02 15:46:06'),
(5, 7, '5487559.00', 'enviado', '2026-09-16 13:40:33'),
(13, 6, '4309700.00', 'pagado', '2026-09-16 13:54:00'),
(14, 6, '34500.00', 'pagado', '2026-09-16 14:21:19'),
(15, 6, '8619400.00', 'pagado', '2026-09-16 16:06:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `imagen` varchar(255) DEFAULT 'default.jpg',
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categoria` varchar(50) NOT NULL DEFAULT 'accesorios'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `stock`, `imagen`, `creado_en`, `categoria`) VALUES
(12, 3, 'Tomodachi Life', 'Encárgate de una isla llena de travesuras, drama, amor ¡y otras sorpresas! · Crea personajes Mii basados en tu familia, tus amigos o tu propia inspiración', '250000.00', 3, '1787577658_6a8c453a73b4b.jpg', '2026-08-24 13:20:25', 'accesorios'),
(13, 3, 'Skullgirls', 'Frenético juego de lucha en 2D que pone a los jugadores al mando de un elenco (casi) exclusivamente femenino al estilo de Arcana Heart.', '34000.00', 7, '1787577829_6a8c45e5eaee0.avif', '2026-08-24 13:23:49', 'accesorios'),
(14, 2, 'Playstation 5', 'Procesador AMD Zen 2 de 8 núcleos, gráficos basados en la arquitectura RDNA 2, disco SSD ultra rápido que reduce los tiempos de carga y el innovador mando DualSense con respuesta háptica y gatillos adaptativos.', '4309700.00', 3, '1787578354_6a8c47f2a820a.webp', '2026-08-24 13:26:12', 'accesorios'),
(15, 1, 'Audífonos Diadema Gamer Inalámbrica Logitech G321 LIGHTSPEED', 'Auriculares ultra livianos con conectividad dual (inalámbrica LIGHTSPEED de 2.4 GHz y Bluetooth 5.2), transductores de 40 mm, micrófono abatible con función flip-to-mute.', '249900.00', 4, '1787578689_6a8c4941dbeaa.jpg', '2026-08-24 13:34:49', 'accesorios'),
(16, 2, 'Nintendo Switch Modelo OLED', 'Consola de videojuegos híbrida que destaca por su vibrante pantalla OLED de 7 pulgadas', '923239.00', 3, '1787581503_6a8c543fc5ddd.jpg', '2026-08-24 14:25:03', 'accesorios'),
(17, 2, 'XBOX One X', 'Disfruta de juegos envolventes en 4K con XBOX One X. Da vida a tus videojuegos y disfruta 4K Blu-ray y del streaming de video 4K en la consola XBOX One X', '1600000.00', 2, '1787581700_6a8c55048c332.png', '2026-08-24 14:28:20', 'accesorios'),
(18, 1, 'Mouse Logitech G502 X', 'G502 X es la última adición a la legendaria gama G502. Rediseñado para ofrecer una impresionante reducción de peso', '265707.00', 4, '1787584000_6a8c5e0021bda.webp', '2026-08-24 14:30:50', 'accesorios'),
(19, 3, 'Outlast II', 'Videojuego de terror psicológico y supervivencia en primera persona desarrollado y publicado por Red Barrels', '34500.00', 13, '1787584181_6a8c5eb521067.webp', '2026-08-24 15:09:41', 'accesorios'),
(20, 3, 'Silent Hill F', 'Videojuego de terror psicológico desarrollado por Konami que traslada la saga por primera vez al Japón rural de la década de 1960.', '220620.00', 10, '1788192498_6a95a6f28243d.PNG', '2026-08-31 16:06:18', 'accesorios'),
(21, 3, 'Resident Evil 4 Remake', 'Es una reimaginación en alta definición y con mecánicas modernas del clásico videojuego de terror y supervivencia de Capcom lanzado originalmente en 2005.', '134990.00', 2, '1788193472_6a95aac088292.jpeg', '2026-08-31 16:24:32', 'accesorios'),
(22, 3, 'Little Nigthmares', 'Cuenta una oscura historia sobre la pérdida de la inocencia y la supervivencia. Sigue a niños atrapados en un mundo retorcido llamado Nowhere', '59860.00', 5, '1788193895_6a95ac6702eb9.jpg', '2026-08-31 16:31:35', 'accesorios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `precio`) VALUES
(1, 'Mantenimiento General', '25.00'),
(2, 'Cambio de Pasta Térmica', '20.00'),
(3, 'Reparación de Controles', '15.00'),
(4, 'Diagnóstico de Fallas', '10.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_mantenimiento`
--

CREATE TABLE `servicios_mantenimiento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `descripcion` text,
  `precio_estimado` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL DEFAULT '2',
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rol` varchar(20) NOT NULL DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `rol_id`, `nombre`, `email`, `password`, `telefono`, `direccion`, `creado_en`, `rol`) VALUES
(2, 2, 'Alice Violeta Tellez Corredor', 'rinconvargasdaniela8@gmail.com', '$2y$10$7cypbhQ0z.6TLZM.CodXOe/lfgXl2qCMHJahC6uUS7dKvcS0XLz.O', '', '', '2026-08-19 14:18:05', 'cliente'),
(6, 1, 'Anahi Sanchez', 'anah@gmail.com', '$2y$10$AnOjhnq0E4M6jecYfjuyPOPAlkR92XZgRPfN7CtUsv7cefXwSUJsC', NULL, NULL, '2026-08-24 13:07:23', 'admin'),
(7, 2, 'Washington', 'wnieto@gmail.com', '$2y$10$sr4NffLHWhBDn2O/pBmN6.DYnpCJ40Fr7vFsNIPagDpu/CnbHzsOa', '', '', '2026-08-31 14:21:03', 'cliente'),
(8, 1, 'Hanna Ballen', 'ballen@gmail.com', '$2y$10$POdvPwocOH8SOZI8mQML4.f3SHOOTPKKlBz0RYGzFDyI2wbn6BuxC', '32378890900', 'av b', '2026-09-14 16:38:05', 'cliente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `citas_mantenimiento`
--
ALTER TABLE `citas_mantenimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_citas_usuarios` (`usuario_id`),
  ADD KEY `fk_citas_servicios` (`servicio_id`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_pedidos` (`pedido_id`),
  ADD KEY `fk_detalle_productos` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedidos_usuarios` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_productos_categorias` (`categoria_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios_mantenimiento`
--
ALTER TABLE `servicios_mantenimiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuarios_roles` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `citas_mantenimiento`
--
ALTER TABLE `citas_mantenimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `servicios_mantenimiento`
--
ALTER TABLE `servicios_mantenimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas_mantenimiento`
--
ALTER TABLE `citas_mantenimiento`
  ADD CONSTRAINT `fk_citas_servicios` FOREIGN KEY (`servicio_id`) REFERENCES `servicios_mantenimiento` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `fk_detalle_pedidos` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_productos` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
