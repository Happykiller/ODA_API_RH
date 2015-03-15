<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_deleteJourFigerUser.php?milis=123450&code_user=FRO&date_begin=2013-02-11&date_end=2013-02-17

// IN obligatoire
$arrayGet = array(
    "code_user" => null,
    "date_begin" => null,
    "date_end" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error()); 

	$sql = "DELETE FROM `tab_suivihebdo_jour_figer_user` 
		WHERE 1=1 
		AND code_user = '".$arrayValeur["code_user"]."'
		AND date between '".$arrayValeur["date_begin"]."' and '".$arrayValeur["date_end"]."'
	;";

	mysql_query($sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error()); 

	// DECONNECION SQL
	mysql_close();
}else{
	echo "ERROR:".$arrayValeur["error"];
}

?>