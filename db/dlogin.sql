-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 16-03-2024 a las 00:03:37
-- Versión del servidor: 8.2.0
-- Versión de PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dlogin`
--
DROP DATABASE IF EXISTS `dlogin`;
CREATE DATABASE IF NOT EXISTS `dlogin` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `dlogin`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--
-- Creación: 14-03-2024 a las 09:59:46
-- Última actualización: 14-03-2024 a las 10:15:59
--

DROP TABLE IF EXISTS `registro`;
CREATE TABLE IF NOT EXISTS `registro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `contrasena_anterior` varchar(255) DEFAULT NULL,
  `contrasena_nueva` varchar(255) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`id`, `usuario_id`, `contrasena_anterior`, `contrasena_nueva`, `fecha_actualizacion`) VALUES
(1, 1, 'c71b6bffff694da0dec11a2ae7cbfdd92bfc0320', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2024-03-14 10:09:33'),
(2, 1, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'c71b6bffff694da0dec11a2ae7cbfdd92bfc0320', '2024-03-14 10:10:37'),
(3, 1, 'c71b6bffff694da0dec11a2ae7cbfdd92bfc0320', 'fa2a77cd6ee73c4f78a0365521632c31557d7562', '2024-03-14 10:15:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reset_password_tokens`
--
-- Creación: 14-03-2024 a las 01:26:34
-- Última actualización: 14-03-2024 a las 10:15:51
--

DROP TABLE IF EXISTS `reset_password_tokens`;
CREATE TABLE IF NOT EXISTS `reset_password_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `reset_password_tokens`
--

INSERT INTO `reset_password_tokens` (`id`, `usuario_id`, `token`, `created_at`, `expiry_at`) VALUES
(1, 1, '07660c7e6ddc230edcd133e69e7274a1ae1a50654a125f231e4eebde97e3ea6f', '2024-03-14 10:04:06', '2024-03-15 16:04:06'),
(2, 1, 'b8bb619c51411ff9ed8c7bef3697dcfcd227fa24b67d49e1eedd05c0de77d579', '2024-03-14 10:07:12', '2024-03-15 16:07:12'),
(3, 1, 'b6fcc568442b733d50c0006e79f388d4a8b1424577f8a2b9abe82d4a8d27eabe', '2024-03-14 10:07:50', '2024-03-15 16:07:50'),
(4, 1, 'a4261231d8c0211893367d43fdcc286b543ccd7ce9c5663e5a4ef1d92883aa51', '2024-03-14 10:09:29', '2024-03-15 16:09:29'),
(5, 1, 'eb8dd2c34e2f7aad2a22798a35c2729b91f5ca70388e6b210388e8e2dab5e7bf', '2024-03-14 10:10:33', '2024-03-15 16:10:33'),
(6, 1, '070f739649dc762bf584f044b6abeffee02b2d6b225cc7d260759780d4d5d970', '2024-03-14 10:15:51', '2024-03-15 16:15:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--
-- Creación: 13-03-2024 a las 08:32:04
-- Última actualización: 14-03-2024 a las 10:16:20
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `usuario` varchar(150) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(150) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `token` varchar(150) DEFAULT NULL,
  `fecha_sesion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `usuario`, `correo`, `contrasena`, `telefono`, `token`, `fecha_sesion`) VALUES
(1, 'Carlos Ernesto', 'Cih Cisnero', 'CarlosCih26', 'carloscih258@gmail.com', 'fa2a77cd6ee73c4f78a0365521632c31557d7562', '3141651293', '3e05a03f850c5890c3646eaef8c6c2b969f91e83', '2024-03-14 10:03:24');

--
-- Disparadores `usuarios`
--
DROP TRIGGER IF EXISTS `after_update_usuario`;
DELIMITER $$
CREATE TRIGGER `after_update_usuario` AFTER UPDATE ON `usuarios` FOR EACH ROW BEGIN
    IF NEW.contrasena <> OLD.contrasena THEN
        INSERT INTO registro (usuario_id, contrasena_anterior, contrasena_nueva)
        VALUES (NEW.id, OLD.contrasena, NEW.contrasena);
    END IF;
END
$$
DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
