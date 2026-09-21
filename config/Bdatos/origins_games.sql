-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-09-2026 a las 05:15:59
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
-- Base de datos: `origins_games`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `tipo_equipo` varchar(100) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('pendiente','confirmada','completada','cancelada') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `usuario_id`, `servicio_id`, `tipo_equipo`, `fecha_cita`, `hora_cita`, `observaciones`, `estado`, `created_at`) VALUES
(3, 6, 10, 'PC ACER  NITRO', '2026-09-30', '17:10:00', '', 'confirmada', '2026-09-20 05:03:52');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 19, 1, 34500.00),
(2, 2, 21, 1, 134990.00),
(3, 3, 16, 1, 923239.00),
(4, 4, 22, 1, 59860.00),
(5, 5, 13, 1, 34000.00),
(6, 5, 20, 1, 220620.00),
(7, 5, 16, 1, 923239.00),
(8, 5, 14, 1, 4309700.00),
(16, 13, 14, 1, 4309700.00),
(17, 14, 19, 1, 34500.00),
(18, 15, 14, 2, 4309700.00),
(19, 16, 22, 1, 59860.00),
(20, 16, 21, 1, 134990.00),
(21, 16, 20, 1, 220620.00),
(22, 16, 16, 1, 923239.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id`, `usuario_id`, `producto_id`, `creado_en`) VALUES
(33, 6, 42, '2026-09-21 02:57:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','pagado','enviado','cancelado') DEFAULT 'pendiente',
  `fecha_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `total`, `estado`, `fecha_pedido`) VALUES
(1, 6, 34500.00, 'pagado', '2026-08-31 15:18:38'),
(2, 6, 134990.00, 'pagado', '2026-08-31 16:42:25'),
(3, 7, 923239.00, 'pagado', '2026-08-31 16:49:19'),
(4, 6, 59860.00, 'pagado', '2026-09-02 15:46:06'),
(5, 7, 5487559.00, 'enviado', '2026-09-16 13:40:33'),
(13, 6, 4309700.00, 'pagado', '2026-09-16 13:54:00'),
(14, 6, 34500.00, 'pagado', '2026-09-16 14:21:19'),
(15, 6, 8619400.00, 'pagado', '2026-09-16 16:06:04'),
(16, 6, 1338709.00, 'pagado', '2026-09-20 22:59:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(255) DEFAULT 'default.jpg',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `stock`, `imagen`, `creado_en`) VALUES
(12, 3, 'Tomodachi Life', 'Encárgate de una isla llena de travesuras, drama, amor ¡y otras sorpresas! · Crea personajes Mii basados en tu familia, tus amigos o tu propia inspiración', 250000.00, 3, '1787577658_6a8c453a73b4b.jpg', '2026-08-24 13:20:25'),
(13, 3, 'Skullgirls', 'Frenético juego de lucha en 2D que pone a los jugadores al mando de un elenco (casi) exclusivamente femenino al estilo de Arcana Heart.', 34000.00, 7, '1787577829_6a8c45e5eaee0.avif', '2026-08-24 13:23:49'),
(14, 2, 'Playstation 5', 'Procesador AMD Zen 2 de 8 núcleos, gráficos basados en la arquitectura RDNA 2, disco SSD ultra rápido que reduce los tiempos de carga y el innovador mando DualSense con respuesta háptica y gatillos adaptativos.', 4309700.00, 3, '1787578354_6a8c47f2a820a.webp', '2026-08-24 13:26:12'),
(15, 1, 'Audífonos Diadema Gamer Inalámbrica Logitech G321 LIGHTSPEED', 'Auriculares ultra livianos con conectividad dual (inalámbrica LIGHTSPEED de 2.4 GHz y Bluetooth 5.2), transductores de 40 mm, micrófono abatible con función flip-to-mute.', 249900.00, 4, '1787578689_6a8c4941dbeaa.jpg', '2026-08-24 13:34:49'),
(16, 2, 'Nintendo Switch Modelo OLED', 'Consola de videojuegos híbrida que destaca por su vibrante pantalla OLED de 7 pulgadas', 923239.00, 2, '1787581503_6a8c543fc5ddd.jpg', '2026-08-24 14:25:03'),
(17, 2, 'XBOX One X', 'Disfruta de juegos envolventes en 4K con XBOX One X. Da vida a tus videojuegos y disfruta 4K Blu-ray y del streaming de video 4K en la consola XBOX One X', 1600000.00, 2, '1787581700_6a8c55048c332.png', '2026-08-24 14:28:20'),
(18, 1, 'Mouse Logitech G502 X', 'G502 X es la última adición a la legendaria gama G502. Rediseñado para ofrecer una impresionante reducción de peso', 265707.00, 4, '1787584000_6a8c5e0021bda.webp', '2026-08-24 14:30:50'),
(19, 3, 'Outlast II', 'Videojuego de terror psicológico y supervivencia en primera persona desarrollado y publicado por Red Barrels', 34500.00, 13, '1787584181_6a8c5eb521067.webp', '2026-08-24 15:09:41'),
(20, 3, 'Silent Hill F', 'Videojuego de terror psicológico desarrollado por Konami que traslada la saga por primera vez al Japón rural de la década de 1960.', 220620.00, 9, '1788192498_6a95a6f28243d.PNG', '2026-08-31 16:06:18'),
(21, 3, 'Resident Evil 4 Remake', 'Reimaginación en alta definición y con mecánicas modernas del clásico videojuego de terror y supervivencia de Capcom lanzado originalmente en 2005.', 134990.00, 1, '1788193472_6a95aac088292.jpeg', '2026-08-31 16:24:32'),
(22, 3, 'Little Nigthmares', 'Cuenta una oscura historia sobre la pérdida de la inocencia y la supervivencia. Sigue a niños atrapados en un mundo retorcido llamado Nowhere', 59860.00, 4, '1788193895_6a95ac6702eb9.jpg', '2026-08-31 16:31:35'),
(23, 1, 'Teclado LOGITECH G G413 SE TKL Gaming', 'Ofrece desempeño apto para juegos -interruptores mecánicos táctiles, seguidilla de 6 teclas con prevención de efecto fantasma y teclas de PBT- en un óptimo formato compacto.', 459900.00, 7, '1789868694_6aaf3a967c93f.webp', '2026-09-20 01:44:54'),
(25, 1, 'Gamepad Redragon Pc Ps3 G807 Saturn', 'Con 8 botones y dos sticks totalmente configurables, este mando de la familia Redragon promete la mejor experiencia sin importar el juego que le pongan enfrente.', 84900.00, 5, '1789945969_6ab068715eb72.jpg', '2026-09-20 23:12:49'),
(26, 1, 'Control Dualsense Sony Negro Ps5 Inalambrico', 'Es un mando de última generación que ofrece una experiencia de juego inmersiva y un diseño ergonómico en color negro.', 319000.00, 13, '1789947189_6ab06d35abdf8.png', '2026-09-20 23:29:57'),
(27, 1, 'Audífonos de Diadema HYPERX Alámbricos Over Ear Gaming Cloud 3', 'Auriculares gaming tipo diadema circumaurales (over-ear), diseñados para ofrecer máxima comodidad y durabilidad en largas sesiones de juego.', 199900.00, 8, '1789947510_6ab06e7687b2e.webp', '2026-09-20 23:38:30'),
(28, 1, 'Microfono LOGTECH G Alámbrico Yeti ORB USB', 'Micrófono de condensador USB diseñado para gaming y streaming que ofrece audio claro de 24 bits/48 kHz con iluminación RGB LIGHTSYNC.', 299000.00, 9, '1789952429_6ab081ad4e4ac.webp', '2026-09-21 01:00:29'),
(29, 1, 'MICROFONO RAZER SEIREN V2 X USB', 'Hazte oír con el Razer Seiren V2 X, un micrófono condensador USB perfecto para cualquier equipo de streaming, equipado con toda la tecnología necesaria para que tu voz cobre vida.', 280000.00, 6, '1789952861_6ab0835d1eb3f.jpg', '2026-09-21 01:07:41'),
(30, 1, 'Xiaomi Gaming Mouse Lite', 'Ratón ultraligero con cable e iluminación RGB diseñado para jugadores de nivel de entrada y uso diario.', 89900.00, 20, '1789953318_6ab085268d1a7.jpg', '2026-09-21 01:15:18'),
(31, 1, 'Teclado Gamer ROG Strix Scope II RX', 'Con protección IP57 contra el agua, espuma insonorizante, cubiertas de teclas PBT de doble capa, teclas de acceso directo para streaming, controles multifunción, tres ángulos de inclinación y reposamuñecas.', 453000.00, 11, '1789953504_6ab085e0d19e4.jpg', '2026-09-21 01:18:24'),
(32, 1, 'Cámara Web HP FHD 1080p 320', 'Cámara web compacta y versátil que ofrece una experiencia de alta calidad gracias a su resolución Full HD, micrófono integrado y compatibilidad con múltiples sistemas operativos.', 159900.00, 16, '1789953787_6ab086fbe0245.webp', '2026-09-21 01:23:07'),
(33, 1, 'Cámara Web Logitech C922 Pro Stream', 'Webcam profesional Full HD de 1080p, diseñada especialmente para transmisiones en vivo fluidas, videos nítidos y videollamadas con enfoque automático inteligente.', 325000.00, 10, '1789954243_6ab088c3c8963.webp', '2026-09-21 01:30:43'),
(34, 2, 'Nintendo Switch 2', 'Consola de videojuegos híbrida de novena generación que evoluciona el diseño portátil y de sobremesa de su predecesora con mayor potencia gráfica y nuevas funciones.', 2299900.00, 7, '1789954625_6ab08a4151d3d.webp', '2026-09-21 01:37:05'),
(35, 2, 'Microsoft Xbox Series X 1TB', 'Consola de sobremesa más potente de Microsoft, diseñada para ofrecer juegos en resolución nativa de hasta 4K a 120 cuadros por segundo.', 3399000.00, 4, '1789954877_6ab08b3dd3413.jpg', '2026-09-21 01:41:17'),
(36, 2, 'PlayStation 4', 'Cuenta con un procesador AMD Jaguar de 8 núcleos, una tarjeta gráfica de 1.84 TFLOPS y 8 GB de memoria RAM GDDR5.', 1135000.00, 3, '1789955229_6ab08c9d80dfc.jpg', '2026-09-21 01:47:09'),
(37, 3, 'Pragmata', 'Videojuego de acción-aventura desarrollado y publicado por Capcom. Ambientado en un futuro próximo en una estación de investigación lunar, el astronauta Hugh Williams y la androide Diana deben colaborar para luchar contra una IA hostil y así volver a La Tierra.', 269000.00, 5, '1789955435_6ab08d6b77bad.webp', '2026-09-21 01:50:35'),
(38, 3, 'Inside', 'Videojuego desarrollado y publicado por Playdead en 2016 para PlayStation 4, Xbox One y Microsoft Windows. Fue lanzado para IOS en 2017 y para Nintendo Switch en 2018. El jugador controla a un niño en un mundo postapocalíptico, resolviendo puzles y evitando la muerte.', 7800.00, 12, '1789955970_6ab08f82c827a.jpg', '2026-09-21 01:59:30'),
(39, 3, 'God of War Ragnarök', 'Novena entrega de la saga de God of War y la secuela de God of War de 2018. Basado libremente en la mitología nórdica, el juego se desarrolla en la antigua Escandinavia y presenta al protagonista de la serie Kratos y su hijo adolescente Atreus.', 280000.00, 9, '1789956873_6ab0930972101.webp', '2026-09-21 02:14:33'),
(40, 3, 'Hollow Knight', 'Videojuego de acción y exploración en 2D (género metroidvania) donde controlas a un pequeño caballero insecto que desciende a las ruinas del vasto y subterráneo reino de Hallownest.', 94000.00, 25, '1789957062_6ab093c68ae01.webp', '2026-09-21 02:16:32'),
(41, 3, 'Look Outside', 'Videojuego de rol y terror de supervivencia de 2025 desarrollado por Francis Coulombe y publicado por Devolver Digital. El juego se desarrolla en un edificio de apartamentos donde un extraño fenómeno exterior comienza a transformar a quienes lo observan en monstruos grotescos.', 45000.00, 21, '1789957696_6ab0964017b1a.jpg', '2026-09-21 02:28:16'),
(42, 3, 'Resident Evil 2 Remake', 'Es una reconstrucción total desde cero del clásico juego de terror y supervivencia (survival horror) de 1998, desarrollado y publicado por Capcom.', 100000.00, 19, '1789958419_6ab099131478d.jpg', '2026-09-21 02:35:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `precio`) VALUES
(1, 'Mantenimiento General', 80000.00),
(2, 'Cambio de Pasta Térmica', 120000.00),
(3, 'Reparación de Controles', 75000.00),
(4, 'Diagnóstico de Fallas', 15000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL DEFAULT 2,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `rol_id`, `nombre`, `email`, `password`, `telefono`, `direccion`, `creado_en`) VALUES
(2, 2, 'Alice Violeta Tellez Corredor', 'rinconvargasdaniela8@gmail.com', '$2y$10$7cypbhQ0z.6TLZM.CodXOe/lfgXl2qCMHJahC6uUS7dKvcS0XLz.O', '30012547089', '', '2026-08-19 14:18:05'),
(6, 1, 'Anahi Sánchez', 'anah@gmail.com', '$2y$10$AnOjhnq0E4M6jecYfjuyPOPAlkR92XZgRPfN7CtUsv7cefXwSUJsC', '3026092294', 'Cra 45 # 53 - 63c', '2026-08-24 13:07:23'),
(7, 2, 'Washington', 'wnieto@gmail.com', '$2y$10$sr4NffLHWhBDn2O/pBmN6.DYnpCJ40Fr7vFsNIPagDpu/CnbHzsOa', '32049675242', '', '2026-08-31 14:21:03'),
(8, 1, 'Hanna Ballen', 'ballen@gmail.com', '$2y$10$POdvPwocOH8SOZI8mQML4.f3SHOOTPKKlBz0RYGzFDyI2wbn6BuxC', '32378890900', 'av b', '2026-09-14 16:38:05');

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
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_pedidos` (`pedido_id`),
  ADD KEY `fk_detalle_productos` (`producto_id`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unico_favorito` (`usuario_id`,`producto_id`),
  ADD KEY `fk_favoritos_producto` (`producto_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

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
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `fk_detalle_pedidos` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_productos` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `fk_favoritos_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favoritos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
