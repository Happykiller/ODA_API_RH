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
// API_RH/phpsql/setNombreConges.php?milis=123450&ctrl=ok&annee=2014&code_user=FRO&type=CP&nombre=31

//Définition des entrants
$arrayGet = array(
    "ctrl" => null,
    "annee" => null,
    "code_user" => null,
    "type" => null,
    "nombre" => null
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
    $strSql = "UPDATE `".$prefixTable."api_rh_tab_provision_conges`
        SET `nombre` = ".$arrayValeur["nombre"]."
        WHERE 1=1
        AND `code_user` = '".$arrayValeur["code_user"]."'
        AND `annee` = ".$arrayValeur["annee"]."
        AND `type` = '".$arrayValeur["type"]."'
    ;";
    $req = $connection->prepare($strSql);
    $req->execute() or die('Erreur SQL:'.print_r($req->errorInfo(), true)." (".$strSql.")");
    $req->closeCursor();
    
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