-- MySQL dump 10.13  Distrib 5.5.50, for debian-linux-gnu (armv7l)
--
-- Host: localhost    Database: dragondb
-- ------------------------------------------------------
-- Server version	5.5.50-0+deb8u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `access_log`
--

DROP TABLE IF EXISTS `access_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `access_log` (
  `ID` int(99) NOT NULL AUTO_INCREMENT COMMENT 'ID de entrada',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha del registro',
  `user` varchar(120) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Usuario que saltó el trigger',
  `IP` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Dirección IP (v4/v6) del usuario',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripción del registro',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2383 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tabla de registros de eventos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `device`
--

DROP TABLE IF EXISTS `device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mac_address` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `person_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Dispositivos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de usuario',
  `peopleID` int(11) NOT NULL COMMENT 'PeopleID referente a tabla people',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre de usuario',
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Contraseña cifrada SHA1',
  `creatorID` int(11) NOT NULL COMMENT 'ID del creador del usuario relación tabla PEOPLE',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha creación del usuario',
  `lastseen` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Está activo?',
  `email` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'dirección de email',
  `revocationKey` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Clave para inhabilitar usuario',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Usuarios de la DB';
/*!40101 SET character_set_client = @saved_cs_client */;


-- Dump completed on 2017-01-28  1:34:27
