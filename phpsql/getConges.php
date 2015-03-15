<?php
//Config : Les informations personnels de l'instance (log, pass, etc)
require("../../include/config.php");

//API Fonctions : les fonctions fournis de base par l'API
require("../../API/php/fonctions.php");

//Header établie la connection à la base $connection
require("../../API/php/header.php");

//Mode debug
$modeDebug = false;

//Public ou privé (clé obligatoire)
$modePublic = true;

//Liens de test
// API_RH/phpsql/getConges.php?milis=123450&ctrl=ok&code_user=FRO&annee=2014

//Définition des entrants
$arrayGet = array(
    "ctrl" => null,
    "code_user" => null,
    "annee" => null
);

//Récupération des entrants
$arrayValeur = recupInput($arrayGet, $bolDecode);

//Vérification sécurité
$retourCheckKey = checkKey($connectionAuth, $prefixTable, $arrayValeur, $modePublic);

if($arrayValeur["error"] == null){
    //Si pas d'erreur
    $object_retour = new stdClass();
    $object_retour->strErreur = "";
    $object_retour->data = "";
    
    //--------------------------------------------------------------------------
    $strSql = "Select *
        FROM `".$prefixTable."api_rh_tab_enreg_conges` a
        WHERE 1=1
        AND a.`actif` = 1
        AND a.`code_user` = '".$arrayValeur["code_user"]."'
        AND a.`date` >= '".$arrayValeur["annee"]."-01-01'
        AND a.`date` < '".strval(intval($arrayValeur["annee"])+1)."-01-01'
    ";

    // On envois la requète
    $select = $connection->query($strSql) or die('Erreur SQL !'.$strSql.'<br>'.print_r($connection->errorInfo(), true));

    // On indique que nous utiliserons les résultats en tant qu'objet
    $select->setFetchMode(PDO::FETCH_OBJ);

    // On transforme les résultats en tableaux d'objet
    $resultats = new stdClass();
    $resultats->data = $select->fetchAll(PDO::FETCH_OBJ);
    $resultats->nombre = count($resultats->data);
    $object_retour->data["conges"] = $resultats;
    
    //--------------------------------------------------------------------------
    $resultats_json = json_encode($object_retour);
    
    $strSorti = $resultats_json;

    if($modeDebug){
        $strSorti .= ($strSql);
    }
}else{
    //Problème sur les entrants
    $strSorti = "ERROR:".$arrayValeur["error"];
}

//Cloture de l'interface
require("../../API/php/footer.php");
?>