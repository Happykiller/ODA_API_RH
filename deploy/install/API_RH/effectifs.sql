-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le : Lun 04 Novembre 2013 à 21:27
-- Version du serveur: 5.5.16
-- Version de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `effectifs`
--

CREATE TABLE IF NOT EXISTS `effectifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_user` varchar(50) NOT NULL,
  `date_in` date NOT NULL,
  `date_out` date NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;