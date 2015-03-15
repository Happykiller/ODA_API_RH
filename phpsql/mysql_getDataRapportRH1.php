<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_getDataRapportRH1.php?milis=123456789&dateBegin=01012013&dateEnd=15012013

//[0][0] = completion
//[1][0] = nb_ouvre

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
		$strFiltreUsersA = "AND a.user in (".$arrayValeur["filtreUsers"].")";
		$strFiltreUsersB = "AND b.user in (".$arrayValeur["filtreUsers"].")";
	}else{
		$strFiltreUsersA = "";
		$strFiltreUsersB = "";
	}
	
	//pourcent de saisi d activitee
	$sql = "
		SELECT ROUND(
		IF(SUM(charge)IS NULL,0,SUM(charge))
		/
		(
		(SELECT COUNT(DISTINCT user) as nbuser
		FROM tab_suivihebdo_enreg b
		WHERE 1=1
		".$strFiltreUsersA."
		AND b.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y'))*10
		*
		(SELECT count(*) as nbjour
			FROM affectation c
			WHERE 1=1
			AND c.`open` != c.`close`
			AND c.`date` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y'))
		)
		*
		100
		,2) as completion
		FROM tab_suivihebdo_enreg a
		WHERE 1=1
		".$strFiltreUsersB."
		AND a.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')
	;";
		
	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 
		$strSorti .= $data['completion']
		."&"
		."\r\n"; 
	}
	
//------------------------------------------------------------------
	//nombre de jour ouvré
	$sql = "SELECT count(*) nb_ouvre
		FROM affectation a
		WHERE 1=1
		AND a.`open` != a.`close`
		AND a.`date` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')
	;";
		
	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 
		$strSorti .= $data['nb_ouvre']
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