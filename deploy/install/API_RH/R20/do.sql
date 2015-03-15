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

CREATE TABLE `api_rh_tab_enreg_conges` (
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

CREATE TABLE `api_rh_tab_provision_conges` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `code_user` varchar(5) NOT NULL,
  `annee` int(4) NOT NULL,
  `type` varchar(250) NOT NULL,
  `nombre` int(2) NOT NULL,
  PRIMARY KEY (`id`)
)
;

INSERT INTO  `indigo_dise_supp`.`tab_menu` (
`id` ,
`Description` ,
`Description_courte` ,
`id_categorie` ,
`Lien`
)
VALUES (
20 ,  'Le calendrier des congés',  'Le calendrier des congés',  '3',  'page_api_rh_calendrier.html'
);