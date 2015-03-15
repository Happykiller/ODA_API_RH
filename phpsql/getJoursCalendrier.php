<?php
//Config : Les informations personnels de l'instance (log, pass, etc)
require("../../include/config.php");

//API Fonctions : les fonctions fournis de base par l'API
require("../../API/php/fonctions.php");

//Header établie la connection à la base $connection
require("../../API/php/header.php");

$modeDebug = false;
$modePublic = true;

// API_RH/phpsql/getJoursCalendrier.php?milis=123450&ctrl=ok&annee=2014

$arrayGet = array(
    "ctrl" => null,
    "annee" => null
);

$arrayValeur = recupInput($arrayGet, $bolDecode);

$retourCheckKey = checkKey($connectionAuth, $prefixTable, $arrayValeur, $modePublic);

if($arrayValeur["error"] == null){
    $object_retour = new stdClass();
    $object_retour->strErreur = "";
    $object_retour->data = "";
    
    //--------------------------------------------------------------------------
    //Pour test on récupère les paramètres de l'appli
    $strSql = "Select a.`id`, a.`date`, a.`jour`, a.`type`, a.`commentaires`
        , if(a.`type` like '%F%', 'oui', 'non') as 'ferier', if(a.`type` like '%VZA%', 'oui', 'non') as 'vacances'
        , DATE_FORMAT(a.`date`,'%u') as 'week'
            from `".$prefixTable."api_rh_tab_calendrier` a
            WHERE 1=1
            AND a.`date` >= '".$arrayValeur["annee"]."-01-01'
            AND a.`date` < '".strval(intval($arrayValeur["annee"])+1)."-01-01'
    ";

    // On envois la requète
    $select = $connection->query($strSql) or die('Erreur SQL !'.$strSql.'<br>'.print_r($connection->errorInfo(), true));

    // On indique que nous utiliserons les résultats en tant qu'objet
    $select->setFetchMode(PDO::FETCH_OBJ);

    // On transforme les résultats en tableaux d'objet
    $jours = new stdClass();
    $jours->data = $select->fetchAll(PDO::FETCH_OBJ);
    $jours->nombre = count($jours->data);
	
    $object_retour->data["jours"] = $jours;
    
    //--------------------------------------------------------------------------
    $resultats_json = json_encode($object_retour);
    
    $strSorti = $resultats_json;

    if($modeDebug){
        $strSorti .= ($strSql);
    }
}else{
    $strSorti = "ERROR:".$arrayValeur["error"];
}

require("../../API/php/footer.php");
?>