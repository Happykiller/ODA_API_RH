<?php 

require("../../include/config.php");
require("../../API/php/fonctions.php");
require("../../API/php/header.php");

// API_RH/phpsql/mysql_editeSuiviActivite.php?milis=123450&date=2013-01-14&auth=FRO&id=289&value=1.15

//IN obligatoire
$arrayGet = array(
    "auth" => null,
    "date" => null,
    "id" => null,
    "value" => null
);

$arrayValeur = recupGet($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){
    // CONNECION SQL
    $db = mysql_connect($host, $base, $mdp)  or die('Erreur de connexion '.mysql_error());

    // CONNECION BASE
    mysql_select_db($base,$db)  or die('Erreur de selection '.mysql_error()); 

    $sql = "UPDATE tab_suivihebdo_enreg
    SET charge = ".$arrayValeur["value"]."
    WHERE 1=1
    AND id='".$arrayValeur["id"]."'
    AND date_saisi ='".$arrayValeur["date"]."'
    AND user='".$arrayValeur["auth"]."'
    ;";

    mysql_query($sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error()); 

    // DECONNECION SQL
    mysql_close();
}else{
    echo "ERROR:".$arrayValeur["error"];
}

?>