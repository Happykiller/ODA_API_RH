<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_insertJourFigerUser.php?milis=123450&code_user=FRO&date=2013-02-11

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

	$sql = "INSERT INTO `tab_suivihebdo_jour_figer_user` (
		`id` ,
		`date` ,
		`code_user` 
	)
	VALUES (
		NULL , '".$arrayValeur["date"]."', '".$arrayValeur["code_user"]."'
	)
	;";

	mysql_query($sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error()); 

	// DECONNECION SQL
	mysql_close();
}else{
	echo "ERROR:".$arrayValeur["error"];
}

?>