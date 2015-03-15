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
// API_RH/phpsql/getSoldesCongees.php?milis=123450&ctrl=ok&code_user=FRO&annee=2014

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
    $strSql = "Select a.`id`, a.`code_user`, a.`annee`, a.`type`, a.`nombre`, IFNULL(ROUND(c.pris, 1),0) as 'pris', IFNULL(ROUND((a.`nombre` - c.`pris`), 1),a.`nombre`) as 'reste'
        FROM `".$prefixTable."api_rh_tab_provision_conges` a
        LEFT OUTER JOIN (
                SELECT COUNT(*)/2 as 'pris', b.`type`
                FROM `".$prefixTable."api_rh_tab_enreg_conges` b
                WHERE 1=1
                AND b.`code_user` = '".$arrayValeur["code_user"]."'
                AND b.`date` >= '".$arrayValeur["annee"]."-01-01'
                AND b.`date` < '".strval(intval($arrayValeur["annee"])+1)."-01-01'
                AND b.`actif` = 1
                GROUP BY b.`type`
        ) c
        ON a.`type` = c.`type`
        WHERE 1=1
        AND a.`code_user` = '".$arrayValeur["code_user"]."'
        AND a.`annee` = ".$arrayValeur["annee"]."
    ";

    // On envois la requète
    $select = $connection->query($strSql) or die('Erreur SQL !'.$strSql.'<br>'.print_r($connection->errorInfo(), true));

    // On indique que nous utiliserons les résultats en tant qu'objet
    $select->setFetchMode(PDO::FETCH_OBJ);

    // On transforme les résultats en tableaux d'objet
    $resultats = new stdClass();
    $resultats->data = $select->fetchAll(PDO::FETCH_OBJ);
    $resultats->nombre = count($resultats->data);
    $object_retour->data["soldesCongees"] = $resultats;
    
    if($resultats->nombre == 0){
        $strSql = "INSERT INTO `".$prefixTable."api_rh_tab_provision_conges` (
                `code_user` ,
                `annee`,
                `type`,
                `nombre`
            )
            VALUES (
                '".$arrayValeur["code_user"]."' ,  ".$arrayValeur["annee"].", 'CP', 27
            ),(
                '".$arrayValeur["code_user"]."' ,  ".$arrayValeur["annee"].", 'Q1a', 6
            ),(
                '".$arrayValeur["code_user"]."' ,  ".$arrayValeur["annee"].", 'Q1b', 6
            ),(
                '".$arrayValeur["code_user"]."' ,  ".$arrayValeur["annee"].", 'Q2', 0
            )
        ;";
        $req = $connection->prepare($strSql);

        $resultat = new stdClass();
        if($req->execute()){
            $resultat->statut = "ok"; 
        }else{
            die('Erreur SQL:'.print_r($req->errorInfo(), true)." (".$strSql.")");
        }
        $req->closeCursor();
        
        $strSql = "Select a.`id`, a.`code_user`, a.`annee`, a.`type`, a.`nombre`, IFNULL(ROUND(c.pris, 1),0) as 'pris', IFNULL(ROUND((a.`nombre` - c.`pris`), 1),a.`nombre`) as 'reste'
            FROM `".$prefixTable."api_rh_tab_provision_conges` a
            LEFT OUTER JOIN (
                    SELECT COUNT(*)/2 as 'pris', b.`type`
                    FROM `".$prefixTable."api_rh_tab_enreg_conges` b
                    WHERE 1=1
                    AND b.`code_user` = '".$arrayValeur["code_user"]."'
                    AND b.`date` >= '".$arrayValeur["annee"]."-01-01'
                    AND b.`date` < '".strval(intval($arrayValeur["annee"])+1)."-01-01'
                    AND b.`actif` = 1
                    GROUP BY b.`type`
            ) c
            ON a.`type` = c.`type`
            WHERE 1=1
            AND a.`code_user` = '".$arrayValeur["code_user"]."'
            AND a.`annee` = ".$arrayValeur["annee"]."
        ";

        // On envois la requète
        $select = $connection->query($strSql) or die('Erreur SQL !'.$strSql.'<br>'.print_r($connection->errorInfo(), true));

        // On indique que nous utiliserons les résultats en tant qu'objet
        $select->setFetchMode(PDO::FETCH_OBJ);

        // On transforme les résultats en tableaux d'objet
        $resultats = new stdClass();
        $resultats->data = $select->fetchAll(PDO::FETCH_OBJ);
        $resultats->nombre = count($resultats->data);
        $object_retour->data["soldesCongees"] = $resultats;
    }

    
    //--------------------------------------------------------------------------
    //Pour exemple d'ajout d'information

    
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