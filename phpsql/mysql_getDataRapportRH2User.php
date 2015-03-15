<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_getDataRapportRH2User.php?milis=123456789&user=FRO

//[X][0] = date
//[X][1] = responsable
//[X][2] = total

// IN obligatoire
$arrayGet = array(
    "user" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());
	
	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());
	
	//------------------------------------------------------------------
	//pourcent de saisi d activitee
	$sql = "SELECT f.date, f.user responsable, IF(c.totalj IS NULL,0,c.totalj) total
		FROM (
			SELECT a.date, e.user
			FROM `affectation` a, (SELECT DISTINCT d.user
					FROM tab_suivihebdo_enreg d, effectifs g
					WHERE 1=1
					AND d.user = g.responsable
					AND g.date_out > now()
					AND d.user = '".$arrayValeur["user"]."'
					AND d.`date_saisi` between (now() - interval 1 MONTH) AND DATE_FORMAT(NOW() - interval 1 DAY, '%Y-%m-%d')) e
			WHERE 1=1
			AND a.`open` != a.`close`
			AND a.`date` between (now() - interval 1 MONTH) AND DATE_FORMAT(NOW() - interval 1 DAY, '%Y-%m-%d')) f
		LEFT JOIN (SELECT b.date_saisi, b.user, IF(SUM(b.charge)IS NULL,0,SUM(b.charge)) totalj 
				FROM tab_suivihebdo_enreg b 
				WHERE 1=1 
				AND b.user = '".$arrayValeur["user"]."'
				AND b.date_saisi between (now() - interval 1 MONTH) AND DATE_FORMAT(NOW() - interval 1 DAY, '%Y-%m-%d') 
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