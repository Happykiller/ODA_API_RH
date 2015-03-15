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
// phpsql/setStatutConge.php?milis=123450&ctrl=ok&id=47&satut=A

//Définition des entrants
$arrayGet = array(
    "ctrl" => null,
    "id" => null,
    "satut" => null
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
    $strSql = "UPDATE `".$prefixTable."api_rh_tab_enreg_conges`
        SET `statut` = '".$arrayValeur["satut"]."'
        WHERE 1=1
        AND `id` = ".$arrayValeur["id"]."
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