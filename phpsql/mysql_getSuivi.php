<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_getSuivi.php?milis=123450&date=2014-02-06&auth=EMQ

//[x][0] = cejour
//[x][2] = jour
//[x][3] = date
//[x][4] = ouvre
//[x][5] = id
//[x][6] = type
//[x][7] = commentaire
//[x][8] = charge
//[x][9] = phase

//IN obligatoire
$arrayGet = array(
    "auth" => null,
    "date" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());

	$sql = "SELECT IF(a.`date`=CURDATE(),1,0) cejour,  WEEKDAY(STR_TO_DATE(a.`date`,'%Y-%m-%d')) as jour, a.`date`, IF(a.open = a.close,1,0) ouvre
	FROM `affectation` a
	where 1=1
	and a.`date` between DATE_SUB(CONCAT(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'), ' 00:00:00'), INTERVAL WEEKDAY(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d')) DAY) 
	and DATE_ADD(CONCAT(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'), ' 00:00:00'), INTERVAL (6 - WEEKDAY(STR_TO_DATE('".$arrayValeur["date"]."','%Y-%m-%d'))) DAY)
	order by a.`date` asc
	;";

	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 
		$strOut = $data['cejour'].'&'.$data['jour'].'&'.$data['date'].'&'.$data['ouvre'];
		
		$sql2 = "SELECT IF(SUM(charge)IS NULL,0,SUM(charge)) as total FROM tab_suivihebdo_enreg
		WHERE 1=1
		AND date_saisi = '".$data['date']."'
		AND user = '".$arrayValeur["auth"]."'
		";
		$req2 = mysql_query($sql2) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
		// on fait une boucle qui va faire un tour pour chaque enregistrement 
		while($data2 = mysql_fetch_assoc($req2)) 
		{ 
			$strOut .= '&'.$data2['total'];
		}
		
		$sql2 = "SELECT * FROM tab_suivihebdo_enreg
		WHERE 1=1
		AND date_saisi = '".$data['date']."'
		AND user = '".$arrayValeur["auth"]."'
		ORDER BY id DESC
		";
		$req2 = mysql_query($sql2) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
		// on fait une boucle qui va faire un tour pour chaque enregistrement 
		while($data2 = mysql_fetch_assoc($req2)) 
		{ 
			$strOut .= '&'.$data2['id'].'&'.$data2['type'].'&'.$data2['commentaire'].'&'.$data2['charge'].'&'.$data2['phase'];
		}
		
		$strOut .= '&'."ZZZ"
		.'&'
		."\r\n";
		
		$strSorti .= $strOut;
	}

	// DECONNECION SQL
	mysql_close();
}else{
	$strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>