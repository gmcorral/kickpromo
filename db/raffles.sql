-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 22-03-2014 a las 20:06:49
-- Versión del servidor: 5.5.29
-- Versión de PHP: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `raffles`
--

DROP SCHEMA raffles;
CREATE SCHEMA raffles;
USE raffles;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrant`
--

DROP TABLE IF EXISTS `entrant`;
CREATE TABLE IF NOT EXISTS `entrant` (
  `ett_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ett_gawid` int(10) unsigned NOT NULL COMMENT 'Giveaway ID',
  `ett_email` varchar(64) NOT NULL COMMENT 'Email',
  `ett_fullname` varchar(64) NOT NULL COMMENT 'Full name',
  `ett_fbid` bigint(20) unsigned DEFAULT NULL COMMENT 'Facebook ID',
  PRIMARY KEY (`ett_id`),
  KEY `ett_gawid` (`ett_gawid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Giveaway entrant information' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entry`
--

DROP TABLE IF EXISTS `entry`;
CREATE TABLE IF NOT EXISTS `entry` (
  `ety_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ety_ettid` int(10) unsigned NOT NULL COMMENT 'Entrant ID',
  `ety_optid` int(11) unsigned NOT NULL COMMENT 'Option ID',
  `ety_time` date NOT NULL COMMENT 'Entry submission time',
  `ety_answer` varchar(256) DEFAULT NULL COMMENT 'Entry answer',
  `ety_ip` varchar(45) NOT NULL COMMENT 'Entry submission IP',
  PRIMARY KEY (`ety_id`),
  KEY `ety_ettid` (`ety_ettid`,`ety_optid`),
  KEY `ety_ettid_2` (`ety_ettid`),
  KEY `ety_optid` (`ety_optid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Giveaway single entry information' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extpricing`
--

DROP TABLE IF EXISTS `extpricing`;
CREATE TABLE IF NOT EXISTS `extpricing` (
  `epr_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `epr_extid` int(11) unsigned NOT NULL COMMENT 'Extra ID',
  `epr_locale` varchar(4) NOT NULL COMMENT 'Price locale',
  `epr_amount` int(11) unsigned NOT NULL COMMENT 'Price amount',
  `epr_currency` varchar(3) NOT NULL COMMENT 'Currency',
  PRIMARY KEY (`epr_id`),
  KEY `epr_extid` (`epr_extid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Giveaway extra pricing' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extra`
--

DROP TABLE IF EXISTS `extra`;
CREATE TABLE IF NOT EXISTS `extra` (
  `ext_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ext_name` varchar(64) NOT NULL COMMENT 'Name',
  `ext_description` varchar(256) NOT NULL COMMENT 'Description',
  PRIMARY KEY (`ext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Available extras for giveaways' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gawext`
--

DROP TABLE IF EXISTS `gawext`;
CREATE TABLE IF NOT EXISTS `gawext` (
  `gex_gawid` int(10) unsigned NOT NULL COMMENT 'Giveaway ID',
  `gex_extid` int(10) unsigned NOT NULL COMMENT 'Extra ID',
  PRIMARY KEY (`gex_gawid`,`gex_extid`),
  KEY `gex_extid` (`gex_extid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Extras added to a giveaway';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `giveaway`
--

DROP TABLE IF EXISTS `giveaway`;
CREATE TABLE IF NOT EXISTS `giveaway` (
  `gaw_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gaw_usrid` int(11) unsigned NOT NULL COMMENT 'Owner ID',
  `gaw_name` varchar(32) NOT NULL COMMENT 'Internal name',
  `gaw_owner` varchar(64) NOT NULL COMMENT 'Public title',
  `gaw_starttime` date NOT NULL COMMENT 'Start time',
  `gaw_endtime` date NOT NULL COMMENT 'End time',
  `gaw_description` varchar(256) DEFAULT NULL COMMENT 'Giveaway description',
  `gaw_terms` varchar(256) NOT NULL COMMENT 'Terms and description',
  `gaw_widgetkey` varchar(64) NOT NULL COMMENT 'Widget key',
  `gaw_creationdate` date NOT NULL COMMENT 'Creation date',
  `gaw_url` varchar(128) DEFAULT NULL COMMENT 'Giveaway URL',
  `gaw_closed` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicates if GAW is closed',
  PRIMARY KEY (`gaw_id`),
  KEY `gaw_usrid` (`gaw_usrid`),
  KEY `gaw_widgetkey` (`gaw_widgetkey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='General giveaway information' AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `language`
--

DROP TABLE IF EXISTS `language`;
CREATE TABLE IF NOT EXISTS `language` (
  `lng_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `lng_tag` varchar(64) NOT NULL COMMENT 'Text tag',
  `lng_locale` varchar(4) NOT NULL COMMENT 'Translation locale',
  `lng_text` varchar(256) NOT NULL COMMENT 'Translation text',
  PRIMARY KEY (`lng_id`),
  KEY `lng_locale` (`lng_locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Text translations' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `option`
--

DROP TABLE IF EXISTS `option`;
CREATE TABLE IF NOT EXISTS `option` (
  `opt_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `opt_gawid` int(11) unsigned NOT NULL COMMENT 'Giveaway ID',
  `opt_otyid` varchar(3) NOT NULL COMMENT 'Option type id',
  `opt_points` tinyint(3) unsigned NOT NULL COMMENT 'Points',
  `opt_daily` tinyint(3) unsigned NOT NULL COMMENT 'Is daily',
  `opt_mandatory` tinyint(3) unsigned NOT NULL COMMENT 'Is mandatory',
  PRIMARY KEY (`opt_id`),
  KEY `opt_gawid` (`opt_gawid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Giveaway entry options' AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `optpricing`
--

DROP TABLE IF EXISTS `optpricing`;
CREATE TABLE IF NOT EXISTS `optpricing` (
  `opr_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `opr_otyid` int(11) unsigned NOT NULL COMMENT 'Option type ID',
  `opr_locale` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Price locale',
  `opr_amount` int(10) unsigned NOT NULL COMMENT 'Price amount',
  `opr_currency` varchar(3) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Currency',
  PRIMARY KEY (`opr_id`),
  KEY `opr_otyid` (`opr_otyid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Giveaway option types pricing' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opttype`
--

DROP TABLE IF EXISTS `opttype`;
CREATE TABLE IF NOT EXISTS `opttype` (
  `oty_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `oty_name` varchar(64) NOT NULL COMMENT 'Name',
  `oty_description` varchar(256) NOT NULL COMMENT 'Description',
  `oty_canbedaily` tinyint(3) unsigned NOT NULL COMMENT 'Can be daily',
  `oty_canbemandatory` tinyint(3) unsigned NOT NULL COMMENT 'Can be mandatory',
  PRIMARY KEY (`oty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Giveaway option predefined types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `optvalue`
--

DROP TABLE IF EXISTS `optvalue`;
CREATE TABLE IF NOT EXISTS `optvalue` (
  `ovl_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ovl_optid` int(11) unsigned NOT NULL COMMENT 'Option ID',
  `ovl_tag` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Tag',
  `ovl_value` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT 'Value',
  PRIMARY KEY (`ovl_id`),
  KEY `ovl_optid` (`ovl_optid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Values configured for giveaway options' AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prize`
--

DROP TABLE IF EXISTS `prize`;
CREATE TABLE IF NOT EXISTS `prize` (
  `pri_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pri_gawid` int(10) unsigned NOT NULL COMMENT 'Giveaway ID',
  `pri_name` varchar(128) NOT NULL COMMENT 'Prize name',
  `pri_quantity` tinyint(3) unsigned NOT NULL COMMENT 'Prize quantity',
  `pri_image` varchar(128) DEFAULT NULL COMMENT 'Prize image path',
  `pri_order` tinyint(3) unsigned NOT NULL COMMENT 'Prize order amongst giveaway prizes',
  PRIMARY KEY (`pri_id`),
  KEY `pri_gawid` (`pri_gawid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Giveaway prize information' AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `usr_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `usr_email` varchar(64) NOT NULL COMMENT 'Email',
  `usr_password` varchar(24) NOT NULL COMMENT 'Password',
  `usr_fullname` varchar(64) NOT NULL COMMENT 'Full name',
  `usr_birthdate` date DEFAULT NULL COMMENT 'Birth date',
  `usr_timezone` int(11) NOT NULL COMMENT 'Timezone',
  `usr_regtime` date NOT NULL COMMENT 'Register time',
  `usr_regip` varchar(45) NOT NULL COMMENT 'Register IP',
  `usr_lastvisit` date DEFAULT NULL COMMENT 'Last visit time',
  `usr_fbid` bigint(20) unsigned DEFAULT NULL COMMENT 'Facebook ID',
  `usr_sessionid` varchar(40) DEFAULT NULL COMMENT 'User active session ID',
  PRIMARY KEY (`usr_id`),
  UNIQUE KEY `usr_sessionid` (`usr_sessionid`),
  KEY `usr_email` (`usr_email`),
  KEY `usr_fbid` (`usr_fbid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Registered users' AUTO_INCREMENT=59 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `winner`
--

DROP TABLE IF EXISTS `winner`;
CREATE TABLE IF NOT EXISTS `winner` (
  `wnn_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `wnn_priid` int(10) unsigned NOT NULL COMMENT 'Prize ID',
  `wnn_published` tinyint(3) unsigned NOT NULL COMMENT 'Is winner published',
  `wnn_discarded` tinyint(3) unsigned NOT NULL COMMENT 'Is winner discarded',
  `wnn_date` date NOT NULL COMMENT 'Winner selection time',
  PRIMARY KEY (`wnn_id`),
  KEY `wnn_priid` (`wnn_priid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Prize winner information' AUTO_INCREMENT=1 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entrant`
--
ALTER TABLE `entrant`
  ADD CONSTRAINT `entrant_ibfk_1` FOREIGN KEY (`ett_gawid`) REFERENCES `giveaway` (`gaw_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entry`
--
ALTER TABLE `entry`
  ADD CONSTRAINT `entry_ibfk_1` FOREIGN KEY (`ety_ettid`) REFERENCES `entrant` (`ett_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entry_ibfk_2` FOREIGN KEY (`ety_optid`) REFERENCES `option` (`opt_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `extpricing`
--
ALTER TABLE `extpricing`
  ADD CONSTRAINT `extpricing_ibfk_1` FOREIGN KEY (`epr_extid`) REFERENCES `extra` (`ext_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `gawext`
--
ALTER TABLE `gawext`
  ADD CONSTRAINT `gawext_ibfk_1` FOREIGN KEY (`gex_gawid`) REFERENCES `giveaway` (`gaw_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gawext_ibfk_2` FOREIGN KEY (`gex_extid`) REFERENCES `extra` (`ext_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `giveaway`
--
ALTER TABLE `giveaway`
  ADD CONSTRAINT `giveaway_ibfk_1` FOREIGN KEY (`gaw_usrid`) REFERENCES `user` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `option_ibfk_1` FOREIGN KEY (`opt_gawid`) REFERENCES `giveaway` (`gaw_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `optpricing`
--
ALTER TABLE `optpricing`
  ADD CONSTRAINT `optpricing_ibfk_1` FOREIGN KEY (`opr_otyid`) REFERENCES `opttype` (`oty_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `optvalue`
--
ALTER TABLE `optvalue`
  ADD CONSTRAINT `optvalue_ibfk_1` FOREIGN KEY (`ovl_optid`) REFERENCES `option` (`opt_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prize`
--
ALTER TABLE `prize`
  ADD CONSTRAINT `prize_ibfk_1` FOREIGN KEY (`pri_gawid`) REFERENCES `giveaway` (`gaw_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `winner`
--
ALTER TABLE `winner`
  ADD CONSTRAINT `winner_ibfk_1` FOREIGN KEY (`wnn_priid`) REFERENCES `prize` (`pri_id`) ON DELETE CASCADE ON UPDATE CASCADE;
