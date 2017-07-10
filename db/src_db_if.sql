-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-07-2017 a las 21:15:53
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

--
-- Volcado de datos para la tabla `if_acciones`
--

INSERT INTO `if_acciones` (`id`, `modulo`, `accion`) VALUES
(1, 'Y', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_config`
--

CREATE TABLE IF NOT EXISTS `if_config` (
  `name` varchar(50) NOT NULL,
  `value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `if_config`
--

INSERT INTO `if_config` (`name`, `value`) VALUES
('if_login_max_acceso_invalid', '3'),
('if_login_max_acceso_invalid_captcha', '5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_notes`
--

CREATE TABLE IF NOT EXISTS `if_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note` varchar(45) DEFAULT NULL,
  `usuario_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`,`usuario_id`),
  KEY `fk_db_notes_if_usuarios1_idx` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Volcado de datos para la tabla `if_notes`
--

INSERT INTO `if_notes` (`id`, `note`, `usuario_id`) VALUES
(1, 'test 0', 15),
(2, 'test 0', 16),
(3, 'test 1', 16),
(4, 'test 2', 16),
(5, 'test 3', 16),
(6, 'test 4', 16),
(7, 'test 5', 16),
(8, 'test 6', 16),
(9, 'test 7', 16),
(10, 'test 8', 16),
(11, 'test 9', 16),
(12, 'test 10', 16),
(13, 'test 0', 17),
(14, 'test 1', 17),
(15, 'test 2', 17),
(16, 'test 3', 17),
(17, 'test 4', 17),
(18, 'test 5', 17),
(19, 'test 6', 17),
(20, 'test 7', 17),
(21, 'test 8', 17),
(22, 'test 9', 17),
(23, 'test 10', 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_roles`
--

CREATE TABLE IF NOT EXISTS `if_roles` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rol` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `if_roles`
--

INSERT INTO `if_roles` (`id`, `rol`) VALUES
(1, 'X');

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

--
-- Volcado de datos para la tabla `if_roles_acciones`
--

INSERT INTO `if_roles_acciones` (`accion_id`, `rol_id`) VALUES
(1, 1);

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

--
-- Volcado de datos para la tabla `if_sessions`
--

INSERT INTO `if_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('02330927b6aa78afae411dfd49eff476c1819614', '::1', 1498236530, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233363236323b),
('074c828bd48d8f2702762516d736ba678395033e', '::1', 1498224724, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383232343339313b),
('0ee401704a885a3edd551b1aba6ea9406d6cdf48', '::1', 1499619764, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439393631393736343b),
('14f9cc5ee37acc61bcf862f606dc8620daad5f23', '::1', 1498231815, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233313536343b),
('15a75c6f3a80c4a9188b414ec8bafacf6df3f4ae', '::1', 1498237005, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233363734313b),
('1f178a5c177cd780f98ab48ba3408d5644684399', '::1', 1499624691, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439393632343639313b),
('23aa00c48cd3232324dcbfe83940662fb65c5e8d', '::1', 1498236215, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233353931343b),
('24c41bd3b6971b1271d4321d1ba571bab4da0676', '::1', 1489874737, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438393837343339343b),
('36a1611b695bb210a602f87302e6da51603faf13', '::1', 1496249190, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439363234393138393b),
('37af6db8406eb5d71537f8d5bb63b75ae68c61b7', '::1', 1489875057, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438393837343734313b),
('37cf37d1875770484de60ebf09149efec8217daf', '::1', 1488383169, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438383338333136393b),
('4cdbc073a43ebe6d6dcebed821934a64afa90814', '::1', 1498234728, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233343530333b),
('5a531e913dc5a0d660a01681c74f8c6af9274440', '::1', 1491352772, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439313335323737323b),
('5ad8834aa650390724b1cb15f29ed2efa170bf9f', '::1', 1489876256, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438393837353932343b),
('5bfa74eb56ebdce433de4fecb1544ebcc8f41008', '::1', 1488380891, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438383338303839313b),
('64589b81a7394c4ed292072ac4cf1bf520c23dc6', '::1', 1488381622, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438383338313337343b),
('6b7d7e3aab3232aaafa4de65815e3197edc6fcf6', '::1', 1498237822, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233373634363b),
('712a3014b2184546734656b10f08b70993934546', '::1', 1499623116, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439393632333131363b),
('73e51fa100a7c5cf97937fd0379db300cc512b17', '::1', 1498225912, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383232353839353b),
('7f0763f1660f0bf04b215559df84c5c18f581df6', '::1', 1489877236, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438393837373130323b),
('836b82781aa793411ccb43f382c3ad6e023326a1', '::1', 1498238513, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233383231363b),
('86a6ed0a097eb5383930f310cf822b036629726e', '::1', 1488382887, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438383338323731363b),
('887f2b17ea21d6e857f1fad33079272a771c4495', '::1', 1489875404, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438393837353038363b),
('8983f5763e74946235e594d582582a3c4ce3d6c7', '::1', 1490894768, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439303839343736383b),
('8cf259062d27986ae7e972de1e9e82a4adc7d616', '::1', 1498230921, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233303634393b),
('8e4277117fed4ae68d44f9d33689a2ad60432dec', '::1', 1489877090, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438393837363730333b),
('8ed6b94fc9758186a35510d939c7160b9de7c7bc', '::1', 1498225123, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383232343734353b),
('9322be27aae8a7c59e82d13d807e774a7a7ae44d', '::1', 1491353164, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439313335333136333b),
('97c0cb52813ad3036975e28decfda6992a00f1c5', '::1', 1488382546, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438383338323239393b),
('9facf8e324cfdfdf1f585af03a18714705f81474', '::1', 1499620120, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439393632303131393b),
('a3c002afc8505a1bfc29162133818873bd6070ab', '::1', 1489875699, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438393837353430393b),
('ab3bd590cc3f7dc02b59ad4654d6948c8c45b5be', '::1', 1488382173, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438383338313939313b),
('acb74f24d72181432a3af80f669e071f646f9e72', '::1', 1498225541, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383232353435383b),
('bb1f0acb2794126b91b24474a0bb3645a953125f', '::1', 1491832664, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439313833323634343b),
('c2777940512377ca23e413f3d5d28d322f1e3719', '::1', 1498238585, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233383538333b),
('c9b277c6780e68f9be2a778bbdeb7abd11381308', '::1', 1498482974, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383438323936313b),
('cbc97c215fa70b58693b7972e011ce21bc42efa6', '::1', 1498235101, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233343831343b),
('d1caaf616cce49f9572ae109147c4ee2da5321a6', '::1', 1488381969, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438383338313638383b),
('d5714b4886253b81597bc191c9dfac1638c0e372', '::1', 1498231271, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233303937383b),
('dd5338d58805b7122b42dd480d76375e27d58b01', '::1', 1498225450, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383232353133313b),
('df6ad2242795fc49265e28326d4eb93914aa8ae5', '::1', 1489876693, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438393837363236313b),
('e24fe6b5438f5f02054f75ebc97c1b2a8664df33', '::1', 1491831582, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439313833313132373b),
('e7dde28533ab5d8c554aba22fd208473bc40d6fb', '::1', 1488908356, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438383930383335363b),
('eba4da4fc90216f6d695e4f1142f9e6fdda5f792', '::1', 1490892160, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439303839323136303b),
('f8076e0704c791eec8626ed3e59fcb3fbd14bdac', '::1', 1498232088, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439383233313836383b);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `if_test`
--

CREATE TABLE IF NOT EXISTS `if_test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(100) NOT NULL,
  `token` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `if_test`
--

INSERT INTO `if_test` (`id`, `text`, `token`) VALUES
(1, 'texto1', NULL),
(2, 'texto2', NULL),
(3, 'texto3', NULL);

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

--
-- Volcado de datos para la tabla `if_uploads`
--

INSERT INTO `if_uploads` (`id`, `file1`, `file2`, `file3`, `file4`, `file5`, `file6`) VALUES
(1, 'file1-2667970.jpeg', 'file2-1212463468.jpeg', '', '', '', ''),
(2, 'file1-771947123.jpeg', '', '', '', '', ''),
(3, 'file1-884733422.jpeg', 'file2-1005050282.jpeg', '', '', '', ''),
(4, 'file1-432985782.jpeg', 'file2-533551055.jpeg', '', '', '', ''),
(5, 'file1-287538362.jpeg', 'file2-631147135.jpeg', '', '', '', ''),
(6, 'file1-1171583276.jpeg', 'file2-681881605.jpeg', '', '', '', ''),
(7, 'file1-513584309.jpeg', 'file2-919718260.jpeg', '', '', '', ''),
(8, 'file1-710669867.jpeg', 'file2-1357738762.jpeg', '', '', '', ''),
(9, 'file1-1107595017.jpeg', 'file2-838560321.jpeg', '', '', '', ''),
(10, 'file1-1291168596.jpeg', 'file2-1250374467.jpeg', '', '', '', ''),
(11, 'file1-198290448.jpeg', 'file2-397441531.jpeg', '', '', '', ''),
(12, 'file1-1327358324.jpeg', 'file2-1078161279.jpeg', '', '', '', ''),
(13, 'file1-149191185.jpeg', 'file2-336422465.jpeg', '', '', '', ''),
(14, 'file1-1365140228.jpeg', 'file2-258233719.jpeg', '', '', '', ''),
(15, 'file1-680676715.jpeg', 'file2-603305573.jpeg', 'file3-123716371.jpeg', '', '', ''),
(16, 'file1-1175370073.jpeg', 'file3-886239534.jpeg', 'file3-1010257128.pdf', 'file4-1082765679.jpeg', '', ''),
(17, '', '', '', '', '', ''),
(18, 'file1-1367291817.png', 'file2-495683088.png', '', '', '', ''),
(19, 'file1-1246071289.jpeg', '', '', '', '', ''),
(20, 'file1-1205018970.jpeg', 'file2-1204889875.jpeg', '', '', '', ''),
(21, 'file1-1059528517.jpeg', '', '', '', '', ''),
(22, 'file1-987364220.jpeg', '', '', '', '', '');

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
-- Volcado de datos para la tabla `if_usuarios`
--

INSERT INTO `if_usuarios` (`id`, `usuario`, `clave`, `rol_id`, `acceso_invalid`, `bloqueado`) VALUES
(1, 'a', 'c4ca4238a0b923820dcc509a6f75849b', 1, 0, NULL),
(2, 'test', '1111', 1, 0, NULL),
(3, 'test22', '1111', 1, 0, NULL),
(4, 'test33', '1111', 1, 0, NULL),
(5, 'test55', '1111', 1, 0, NULL),
(6, 'test66', '1111', 1, 0, NULL),
(7, 'test77', '1111', 1, 0, NULL),
(8, 'test88', '1111', 1, 0, NULL),
(9, 'test99', '1111', 1, 0, NULL),
(10, 'test111', '1111', 1, 0, NULL),
(11, 'test222', '1111', 1, 0, NULL),
(12, 'test333', '1111', 1, 0, NULL),
(13, 'test444', '1111', 1, 0, NULL),
(14, 'test555', '1111', 1, 0, NULL),
(15, 'test666', '1111', 1, 0, NULL),
(16, 'test777', '1111', 1, 0, NULL),
(17, 'test888', '1111', 1, 0, NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `if_notes`
--
ALTER TABLE `if_notes`
  ADD CONSTRAINT `fk_db_notes_if_usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `if_usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
