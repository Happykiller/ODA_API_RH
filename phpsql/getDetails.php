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
// API_RH/phpsql/getDetails.php?milis=123450&ctrl=ok&annee=2014&code_user=FRO

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
    $strSql = "Select a.`date`, count(*) as 'nb', IFNULL(e.`actionUser`, false) as 'actionUser', IFNULL(f.`actionRespon`, false) as 'actionRespon'
        FROM `".$prefixTable."api_rh_tab_enreg_conges` a
        LEFT OUTER JOIN (
                SELECT b.`date`, IF(COUNT(*) > 0, true, false) as 'actionUser'
                FROM `".$prefixTable."api_rh_tab_enreg_conges` b
                WHERE 1=1
                AND b.`actif` = 1
                AND b.`date` >= '".$arrayValeur["annee"]."-01-01'
                AND b.`date` < '".strval(intval($arrayValeur["annee"])+1)."-01-01'
                AND b.`code_user` = '".$arrayValeur["code_user"]."'
                AND b.`statut` = 'P'
                GROUP BY b.`date`
        ) e
        ON e.`date` = a.`date`
        LEFT OUTER JOIN (
                SELECT d.`date`, IF(COUNT(*) > 0, true, false) as 'actionRespon'
                FROM `".$prefixTable."api_rh_tab_enreg_conges` d
                WHERE 1=1
                AND d.`actif` = 1
                AND d.`date` >= '".$arrayValeur["annee"]."-01-01'
                AND d.`date` < '".strval(intval($arrayValeur["annee"])+1)."-01-01'
                AND EXISTS (
                        SELECT 1
                        FROM `".$prefixTable."tab_utilisateurs` c
                        WHERE 1=1
                        AND c.`code_user` = '".$arrayValeur["code_user"]."'
                        AND c.`profile` <= 20 
                )
                AND d.`statut` != 'V' 
                AND d.`statut` != 'R'
                GROUP BY d.`date`
        ) f
        ON f.`date` = a.`date`
        WHERE 1=1
        AND a.`actif` = 1
        AND a.`date` >= '".$arrayValeur["annee"]."-01-01'
        AND a.`date` < '".strval(intval($arrayValeur["annee"])+1)."-01-01'
        GROUP BY a.`date`
        ORDER BY a.`date`
    ";

    // On envois la requète
    $select = $connection->query($strSql) or die('Erreur SQL !'.$strSql.'<br>'.print_r($connection->errorInfo(), true));

    // On indique que nous utiliserons les résultats en tant qu'objet
    $select->setFetchMode(PDO::FETCH_OBJ);

    // On transforme les résultats en tableaux d'objet
    $resultats = new stdClass();
    $resultats->data = $select->fetchAll(PDO::FETCH_OBJ);
    $resultats->nombre = count($resultats->data);
    $object_retour->data["details"] = $resultats;
    
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