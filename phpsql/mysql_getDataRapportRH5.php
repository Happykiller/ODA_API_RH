<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_getDataRapportRH5.php?milis=123456789&dateBegin=01032013&dateEnd=31032013

//[X][0] = type
//[X][1] = description
//[X][2] = user
//[X][3] = commentaire
//[X][4] = nb

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
		$strFiltreUsers = "AND a.user in (".$arrayValeur["filtreUsers"].")";
	}else{
		$strFiltreUsers = "";
	}
	
	$sql = "
		SELECT type, description, user, commentaire, SUM(charge) as nb
		FROM tab_suivihebdo_enreg a, tab_suivihebdo_type_activite b
		WHERE 1=1
		AND a.type = b.id
		AND a.type not in (24, 1, 2, 3, 35, 36)
		".$strFiltreUsers."
		AND a.`date_saisi` between STR_TO_DATE('".$arrayValeur["dateBegin"]."','%d%m%Y') AND STR_TO_DATE('".$arrayValeur["dateEnd"]."','%d%m%Y')
		GROUP BY type, user, commentaire
		ORDER BY type, user, commentaire
	;";
		
	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 		
		$strSorti .= $data['type']."&".$data['description']."&".$data['user']."&".$data['commentaire']."&".$data['nb']."&"
		."\r\n";
	}

	// DECONNECION SQL
	mysql_close();
}else{
	$strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>