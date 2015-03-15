DROP FUNCTION `getDuree`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `getDuree`( p_dateDebut TEXT, p_dateFin TEXT) RETURNS int(11)
    DETERMINISTIC
BEGIN
	DECLARE intDuree INT DEFAULT 0;
	
	SELECT SUM(TIME_TO_SEC(TIMEDIFF(LEAST(STR_TO_DATE(p_dateFin,'%Y-%m-%d %H:%i:%s'),a.`heure_close`),GREATEST(STR_TO_DATE(p_dateDebut,'%Y-%m-%d %H:%i:%s'),a.`heure_open`)))) as 'duree'
	INTO intDuree
	FROM `api_rh_vue_plage_travaille` a
	WHERE 1=1
	AND a.`date` >= DATE_FORMAT(STR_TO_DATE(p_dateDebut,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d')
	AND a.`date` <= DATE_FORMAT(STR_TO_DATE(p_dateFin,'%Y-%m-%d %H:%i:%s'),'%Y-%m-%d')
	;

	RETURN intDuree; 
END$$