<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_insertSuiviActivite.php?milis=123450&date=2013-02-04&auth=FRO&tacheType=1&tacheActivite=blablal&tacheDuree=4&phase=4

//IN obligatoire
$arrayGet = array(
    "auth" => null,
    "date" => null,
    "tacheType" => null,
    "tacheActivite" => null,
    "tacheDuree" => null,
    "phase" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){

	// CONNECION SQL
	$db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

	// CONNECION BASE
	mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error()); 

	$sql = "INSERT INTO `tab_suivihebdo_enreg` (
	`id` ,
	`date_rec` ,
	`user` ,
	`date_saisi` ,
	`type` ,
	`phase` ,
	`commentaire` ,
	`charge` 
	)
	VALUES (
		NULL , NOW(), '".$arrayValeur["auth"]."', '".$arrayValeur["date"]."', '".$arrayValeur["tacheType"]."', '".$arrayValeur["phase"]."', '".$arrayValeur["tacheActivite"]."', '".$arrayValeur["tacheDuree"]."'
	);";

	mysql_query($sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error()); 

	// DECONNECION SQL
	mysql_close();
}else{
	echo "ERROR:".$arrayValeur["error"];
}

?>