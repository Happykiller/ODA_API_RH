<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

$modeDebug = false;

// API_RH/phpsql/mysql_getImputTimeSheet.php?milis=123456789&code_user=FRO&date=2013-04-29

//[0][0] = date
//[0][1] = code_imputation
//[0][2] = description
//[0][3] = charge

// IN obligatoire
$arrayGet = array(
    "code_user" => null,
    "date" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());
	
//------------------------------------------------------------------
	//pourcent de saisi d activitee
	$sql = "SELECT g.date, g.code_imputation, null as description, IFNULL(h.total,0) as charge
		FROM (
		SELECT i.code_imputation, j.date
		FROM (
			SELECT DISTINCT f.code_imputation
			FROM tab_suivihebdo_enreg e, tab_suivihebdo_type_activite f 
			WHERE 1=1 
			AND e.type = f.id 
			AND e.user = '".$arrayValeur["code_user"]."' 
			AND e.date_saisi between DATE_SUB(CONCAT(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'), ' 00:00:00'), INTERVAL WEEKDAY(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d')) DAY) 
				and DATE_ADD(CONCAT(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'), ' 00:00:00'), INTERVAL (6 - WEEKDAY(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'))) DAY)
		) i, `affectation` j
		where 1=1
		AND j.date between DATE_SUB(CONCAT(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'), ' 00:00:00'), INTERVAL WEEKDAY(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d')) DAY) 
			and DATE_ADD(CONCAT(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'), ' 00:00:00'), INTERVAL (6 - WEEKDAY(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'))) DAY)
	) g
	LEFT JOIN (
		SELECT a.date_saisi, b.code_imputation, SUM(a.charge) as total
		FROM tab_suivihebdo_enreg a, tab_suivihebdo_type_activite b 
		WHERE 1=1 
		AND a.type = b.id 
		AND a.user = '".$arrayValeur["code_user"]."' 
		AND a.date_saisi between DATE_SUB(CONCAT(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'), ' 00:00:00'), INTERVAL WEEKDAY(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d')) DAY) 
			and DATE_ADD(CONCAT(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'), ' 00:00:00'), INTERVAL (6 - WEEKDAY(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'))) DAY)
		GROUP BY date_saisi, b.code_imputation
	) h
	ON h.code_imputation = g.code_imputation AND h.date_saisi = g.date
	ORDER BY g.date asc, g.code_imputation desc
	;";
		
	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 

	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 
		$strSorti .= $data['date']."&".$data['code_imputation']."&".$data['description']."&".$data['charge']
		."&"
		."\r\n"; 
	}

	// DECONNECION SQL
	mysql_close();
	
	if($modeDebug){
		$strSorti .= ('<br><br><br><br>'.$sql);
	}
}else{
	$strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>