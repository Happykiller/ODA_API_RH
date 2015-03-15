-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le : Lun 04 Novembre 2013 à 15:46
-- Version du serveur: 5.5.16
-- Version de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `db_oda_dise`
--

--
-- Contenu de la table `tab_suivihebdo_param_ordre_rapport3`
--

INSERT INTO `tab_suivihebdo_param_ordre_rapport3` (`id`, `type`, `ordre`, `group`) VALUES
(1, 1, 10, 1),
(2, 2, 20, 1),
(3, 3, 30, 1),
(4, 24, 40, 2),
(5, 27, 50, 3),
(6, 28, 60, 3),
(7, 30, 70, 3),
(8, 31, 80, 3),
(9, 32, 90, 3),
(10, 29, 100, 3),
(11, 34, 110, 3),
(12, 35, 120, 3),
(13, 36, 130, 3);

--
-- Contenu de la table `tab_suivihebdo_param_ordre_rapport4`
--

INSERT INTO `tab_suivihebdo_param_ordre_rapport4` (`id`, `type`, `ordre`, `group`) VALUES
(1, 1, 10, 1),
(2, 2, 20, 1),
(3, 3, 30, 1),
(4, 24, 40, 2),
(5, 27, 50, 2),
(6, 28, 60, 2),
(7, 30, 70, 2),
(8, 31, 80, 2),
(9, 32, 90, 2),
(10, 29, 100, 2),
(11, 34, 110, 3),
(12, 35, 120, 3),
(13, 36, 130, 3);

--
-- Contenu de la table `tab_suivihebdo_type_activite`
--

INSERT INTO `tab_suivihebdo_type_activite` (`id`, `description`, `description_long`, `id_sous_domaine`, `acti_obligatoire`, `actif`, `date_desactivation`, `code_imputation`) VALUES
(1, 'Congé payé', 'Congé payé', 0, 0, 1, '0000-00-00 00:00:00', '6200100000'),
(2, 'Férié', 'Férié', 0, 0, 1, '0000-00-00 00:00:00', 'DEFAUT2'),
(3, 'CE-DP-CHSCT', 'CE-DP-CHSCT', 0, 0, 1, '0000-00-00 00:00:00', 'DEFAUT3'),
(24, 'Incident', 'Incident (Tickets)', 0, 0, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(27, 'Crises', 'Crises', 0, 1, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(28, 'OPS', 'OPS', 0, 1, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(29, 'Autres', 'Autres', 0, 1, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(30, 'Defect', 'Defect', 0, 1, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(31, 'HBM', 'HBM', 0, 1, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(32, 'PMA', 'PMA', 0, 1, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(34, 'Astreintes', 'Astreintes', 0, 1, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(35, 'Pilotage', 'Pilotage', 0, 0, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(36, 'Point Equipe', 'Point Equipe', 0, 0, 1, '0000-00-00 00:00:00', 'FREC11858600'),
(37, 'RTT payée (Q1)', 'RTT payée (Q1)', 0, 0, 1, '0000-00-00 00:00:00', '6200200000'),
(38, 'Practice technologique', 'Practice technologique', 0, 1, 1, '0000-00-00 00:00:00', '6800000000'),
(39, 'Formation DIF durant le temps de travail (DTT)', 'Formation DIF durant le temps de travail (DTT)', 0, 1, 1, '0000-00-00 00:00:00', '7200120000'),
(40, 'ADV', 'ADV', 0, 1, 0, '0000-00-00 00:00:00', 'FREC11663200'),
(41, 'Complément temps partiel', 'Complément temps partiel', 0, 0, 1, '0000-00-00 00:00:00', '7000900000'),
(42, 'RTT non payée (Q2)', 'RTT non payée (Q2)', 0, 0, 1, '0000-00-00 00:00:00', '6200210000'),
(43, 'Aménagement ... maternité', 'Aménagement du temps de travail maternité', 0, 0, 1, '0000-00-00 00:00:00', '6200310000'),
(44, 'Maladie', 'Maladie', 0, 0, 1, '0000-00-00 00:00:00', '6400100000'),
(45, 'Maternité', 'Maternité', 0, 0, 1, '0000-00-00 00:00:00', '6400200000'),
(46, 'Reporting', 'Reporting', 0, 1, 1, '0000-00-00 00:00:00', ''),
(47, 'Formation externe', 'Formation externe', 0, 1, 1, '0000-00-00 00:00:00', ''),
(48, 'Indicent(Entrant)', 'Indicent(Entrant)', 0, 1, 1, '0000-00-00 00:00:00', ''),
(49, 'Capitalisation', 'Capitalisation', 0, 1, 1, '0000-00-00 00:00:00', ''),
(50, 'Transfert compétence', 'Transfert compétence', 0, 1, 1, '0000-00-00 00:00:00', ''),
(51, 'Inactivité forcée', 'Inactivité forcée', 0, 1, 1, '0000-00-00 00:00:00', ''),
(52, 'Activité externe projet', 'Activité externe projet', 0, 1, 1, '0000-00-00 00:00:00', '');

--
-- Contenu de la table `tab_suivihebdo_type_phase`
--

INSERT INTO `tab_suivihebdo_type_phase` (`id`, `description`, `description_long`, `id_sous_domaine`, `actif`, `date_desactivation`) VALUES
(0, 'N/A', 'N/A', 0, 1, '0000-00-00 00:00:00');

--
-- Contenu de la table `@dbLog@`.`@prefixeDb@tab_menu`
-- Réservé 21-40 API_RH
--
INSERT INTO `tab_menu` (`id`, `Description`, `Description_courte`, `id_categorie`, `Lien`) VALUES
(21, 'Le calendrier des congés', 'Le calendrier des congés', 3, 'page_api_rh_calendrier.html'),
(21, 'La saisie d''activité', 'La saisie d''activité', 3, 'page_suivihebdo.html'),
(22, 'Les rapports RH', 'Les rapports RH', 4, 'page_rapports_rh.html'),
(23, 'La supervision RH', 'La supervision RH', 3, 'page_admin_rh.html');

UPDATE `tab_menu_rangs_droit` SET `id_menu` =  concat(`id_menu`,'21;22;23;') WHERE `id` in (1,2,3);
UPDATE `tab_menu_rangs_droit` SET `id_menu` =  concat(`id_menu`,'21;') WHERE `id` in (4);

--
-- Contenu de la table `tab_parametres`
-- Réservé 21-40 API_RH
--
INSERT INTO `tab_parametres` (`id`, `param_name`, `param_type`, `param_value`) VALUES
(null, 'debray_listes_users', 'int', '0'),
(null, 'debray_figer', 'int', '0'),
(null, 'debray_resume_timesheet', 'int', '1');