-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le : Lun 04 Novembre 2013 à 15:39
-- Version du serveur: 5.5.16
-- Version de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `db_oda_dise`
--

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `tab_listes_users`
--

CREATE TABLE IF NOT EXISTS `tab_listes_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `code_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code_user` (`code_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tab_listes_users_contenus`
--

CREATE TABLE IF NOT EXISTS `tab_listes_users_contenus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_listes_users` int(11) NOT NULL,
  `code_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tab_suivihebdo_enreg`
--

CREATE TABLE IF NOT EXISTS `tab_suivihebdo_enreg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_rec` datetime NOT NULL,
  `user` varchar(20) NOT NULL,
  `date_saisi` date NOT NULL,
  `type` int(4) NOT NULL,
  `phase` int(11) NOT NULL,
  `commentaire` varchar(250) NOT NULL,
  `charge` float(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tab_suivihebdo_jour_figer_user`
--

CREATE TABLE IF NOT EXISTS `tab_suivihebdo_jour_figer_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `code_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`,`code_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tab_suivihebdo_param_ordre_rapport3`
--

CREATE TABLE IF NOT EXISTS `tab_suivihebdo_param_ordre_rapport3` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `ordre` int(11) NOT NULL,
  `group` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tab_suivihebdo_param_ordre_rapport4`
--

CREATE TABLE IF NOT EXISTS `tab_suivihebdo_param_ordre_rapport4` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `ordre` int(11) NOT NULL,
  `group` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tab_suivihebdo_type_activite`
--

CREATE TABLE IF NOT EXISTS `tab_suivihebdo_type_activite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(250) NOT NULL,
  `description_long` varchar(250) NOT NULL,
  `id_sous_domaine` int(4) NOT NULL,
  `acti_obligatoire` int(1) NOT NULL DEFAULT '1',
  `actif` int(1) NOT NULL DEFAULT '1',
  `date_desactivation` datetime NOT NULL,
  `code_imputation` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tab_suivihebdo_type_phase`
--

CREATE TABLE IF NOT EXISTS `tab_suivihebdo_type_phase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(250) NOT NULL,
  `description_long` varchar(250) NOT NULL,
  `id_sous_domaine` int(11) NOT NULL DEFAULT '1',
  `actif` int(1) NOT NULL DEFAULT '1',
  `date_desactivation` datetime NOT NULL,
  PRIMARY KEY (`id`)
) 
;


-- --------------------------------------------------------

--
-- Structure de la table 'api_rh_tab_calendrier'
--

CREATE TABLE IF NOT EXISTS `api_rh_tab_calendrier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `jour` tinyint(7) NOT NULL,
  `type` varchar(250) NOT NULL,
  `commentaires` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) 
;

-- --------------------------------------------------------

--
-- Structure de la table 'api_rh_tab_enreg_conges'
--

CREATE TABLE IF NOT EXISTS `api_rh_tab_enreg_conges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_user` varchar(5) NOT NULL,
  `date` date NOT NULL,
  `type` varchar(5) NOT NULL,
  `am_ap` varchar(2) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateSupp` datetime NOT NULL,
  `statut` varchar(5) NOT NULL,
  `actif` int(2) NOT NULL,
  PRIMARY KEY (`id`)
)
;

-- --------------------------------------------------------

--
-- Structure de la table 'api_rh_tab_provision_conges'
--

CREATE TABLE IF NOT EXISTS `api_rh_tab_provision_conges` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `code_user` varchar(5) NOT NULL,
  `annee` int(4) NOT NULL,
  `type` varchar(250) NOT NULL,
  `nombre` int(2) NOT NULL,
  PRIMARY KEY (`id`)
)
;

-- --------------------------------------------------------

--
-- Structure de la table `vue_plage_travaille`
--
CREATE VIEW `api_rh_vue_plage_travaille` AS 
select `a`.`date` AS `date`,
str_to_date(concat(date_format(`a`.`date`,'%Y-%m-%d'),' ',`a`.`open`),'%Y-%m-%d %H:%i') AS `heure_open`,
str_to_date(concat(date_format(`a`.`date`,'%Y-%m-%d'),' ',`a`.`close`),'%Y-%m-%d %H:%i') AS `heure_close` 
from `affectation` `a` 
where 1 = 1 
and (dayofweek(`a`.`date`) <> 7) 
and (dayofweek(`a`.`date`) <> 1) 
;
