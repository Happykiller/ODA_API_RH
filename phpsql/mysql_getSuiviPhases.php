<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_getSuiviDomaines.php?milis=123450&date=2012-10-22&ctrl=ok

//[X][0] = id
//[X][1] = description
//[X][2] = description_long
//[X][3] = acti_obligatoire

//IN obligatoire
$arrayGet = array(
    "ctrl" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error());

	$sql = "SELECT *
	FROM `tab_suivihebdo_type_phase` a
	where 1=1
	AND a.actif = 1
	order by a.`description` asc
	;";

	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	
	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = mysql_fetch_assoc($req)) 
	{ 
		$strSorti .= $data['id'].'&'.$data['description'].'&'.$data['description_long'].'&'.$data['acti_obligatoire']
		.'&'
		."\r\n";
	}

	// DECONNECION SQL
	mysql_close();
}else{
	$strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>