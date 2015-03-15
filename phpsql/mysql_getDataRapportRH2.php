<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_getDataRapportRH2.php?milis=123456789&dateBegin=01032013&dateEnd=31032013

//[X][0] = date
//[X][1] = responsable
//[X][2] = total

// IN obligatoire
$arrayGet = array(
    "dateBegin" => null,
    "dateEnd" => null,
    "filtreUsers" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());
	
	//------------------------------------------------------------------
	if ($arrayValeur["filtreUsers"] != ""){
		$strFiltreUsersB = "AND b.user in (".$arrayValeur["filtreUsers"].")";
		$strFiltreUsersD = "AND d.user in (".$arrayValeur["filtreUsers"].")";
	}else{
		$strFiltreUsersD = "";
		$strFiltreUsersB = "";
	}
	
	//pourcent de saisi d activitee
	$sql = "SELECT f.date, f.user responsable, IF(c.totalj IS NULL,0,c.totalj) total
		FROM (
			SELECT a.date, e.user
			FROM `affectation` a, (SELECT DISTINCT d.user
					FROM tab_suivihebdo_enreg d, effectifs g
					WHERE 1=1
					AND d.user = g.responsable
					".$strFiltreUsersD."
					AND g.date_out > STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y')
					AND d.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')) e
			WHERE 1=1
			AND a.`open` != a.`close`
			AND a.`date` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')) f
		LEFT JOIN (SELECT b.date_saisi, b.user, IF(SUM(b.charge)IS NULL,0,SUM(b.charge)) totalj 
				FROM tab_suivihebdo_enreg b 
				WHERE 1=1 
				".$strFiltreUsersB."
				AND b.date_saisi between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y') 
				GROUP BY b.date_saisi, b.user) c
			ON c.date_saisi = f.date AND c.user = f.user	
		WHERE 1=1
		AND IF(c.totalj IS NULL,0,c.totalj) < 10 
		ORDER BY f.user asc, f.date desc	
	;";
		
	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 

	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 
		$strSorti .= $data['date']."&".$data['responsable']."&".$data['total']
		."&"
		."\r\n"; 
	}
	
	// DECONNECION SQL
	mysql_close();
}else{
	$strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>