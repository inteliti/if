-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-07-2017 a las 21:17:12
-- Versión del servidor: 1.0.122
-- Versión de PHP: 5.5.38-1~dotdeb+7.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `db_if`
--

DELIMITER $$
--
-- Procedimientos
--
$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_acciones`
--

CREATE TABLE IF NOT EXISTS `if_acciones` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modulo` varchar(40) DEFAULT NULL,
  `accion` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_config`
--

CREATE TABLE IF NOT EXISTS `if_config` (
  `name` varchar(50) NOT NULL,
  `value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_roles`
--

CREATE TABLE IF NOT EXISTS `if_roles` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rol` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_roles_acciones`
--

CREATE TABLE IF NOT EXISTS `if_roles_acciones` (
  `accion_id` mediumint(8) unsigned NOT NULL,
  `rol_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`accion_id`,`rol_id`),
  KEY `fk_roles_idx` (`rol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_sessions`
--

CREATE TABLE IF NOT EXISTS `if_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `if_sessions_id_ip` (`id`,`ip_address`),
  KEY `if_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_uploads`
--

CREATE TABLE IF NOT EXISTS `if_uploads` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `file1` varchar(45) NOT NULL,
  `file2` varchar(45) NOT NULL,
  `file3` varchar(45) NOT NULL,
  `file4` varchar(45) NOT NULL,
  `file5` varchar(45) NOT NULL,
  `file6` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_usuarios`
--

CREATE TABLE IF NOT EXISTS `if_usuarios` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(25) NOT NULL,
  `clave` varchar(32) NOT NULL,
  `rol_id` mediumint(8) unsigned NOT NULL,
  `acceso_invalid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bloqueado` tinyint(1) unsigned DEFAULT NULL COMMENT 'NULL=no, 1=si',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_UNIQUE` (`usuario`),
  KEY `fk_rol_idx` (`rol_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `if_roles_acciones`
--
ALTER TABLE `if_roles_acciones`
  ADD CONSTRAINT `fk_acciones` FOREIGN KEY (`accion_id`) REFERENCES `if_acciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_roles` FOREIGN KEY (`rol_id`) REFERENCES `if_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `if_usuarios`
--
ALTER TABLE `if_usuarios`
  ADD CONSTRAINT `fk_rol_usuario` FOREIGN KEY (`rol_id`) REFERENCES `if_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
