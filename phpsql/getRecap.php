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
// API_RH/phpsql/getRecap.php?milis=123450&ctrl=ok&annee=2014&code_user=FRO

//Définition des entrants
$arrayGet = array(
    "ctrl" => null,
    "annee" => null,
    "code_user" => null
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
    $strSql = "SELECT * 
        FROM (
            Select a.`id`, a.`date`, a.`code_user`, a.`statut`, a.`am_ap`
            , (IF((a.`code_user` = '".$arrayValeur["code_user"]."' AND a.`statut` = 'P'), true, false)) 
            as 'displayA'
            , (IF((b.`respon` = true AND (a.`statut` = 'P' OR a.`statut` = 'A')), true, false))  as 'displayV'
            , (IF((b.`respon` = true AND (a.`statut` = 'P' OR a.`statut` = 'A')), true, false)) as 'displayR'
            FROM `api_rh_tab_enreg_conges` a
            LEFT OUTER JOIN (
                SELECT true as 'respon'
                FROM `tab_utilisateurs` c
                WHERE 1=1
                AND c.`code_user` = '".$arrayValeur["code_user"]."'
                AND c.`profile` <= 20 
            ) b
            ON 1=1
            WHERE 1=1
            AND a.`date` >= '".$arrayValeur["annee"]."-01-01'
            AND a.`date` < '".strval(intval($arrayValeur["annee"])+1)."-01-01'
            AND a.`actif` = 1
        ) c
        WHERE 1=1
        AND (
            c.`displayA` = 1
            OR
            c.`displayV` = 1
            OR
            c.`displayR` = 1
        )
        ORDER BY c.`date`, c.`code_user`, c.`am_ap`
    ";

    // On envois la requète
    $select = $connection->query($strSql) or die('Erreur SQL !'.$strSql.'<br>'.print_r($connection->errorInfo(), true));

    // On indique que nous utiliserons les résultats en tant qu'objet
    $select->setFetchMode(PDO::FETCH_OBJ);

    // On transforme les résultats en tableaux d'objet
    $resultats = new stdClass();
    $resultats->data = $select->fetchAll(PDO::FETCH_OBJ);
    $resultats->nombre = count($resultats->data);
    $object_retour->data["recap"] = $resultats;
    
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