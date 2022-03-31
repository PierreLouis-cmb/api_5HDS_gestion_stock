<?php
include_once '../../config/Database.php';
include_once '../../config/header.php';
header("Access-Control-Allow-Methods: GET");

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    try {
        $database = new Database();
        $db = $database->getConnection();
        $retour["Success"] = true;
        $retour["Success_BDD"] = true;
    }catch(Exception $e) {
        echo json_encode('Il y à eu une erreur de connexion à la base'.$e->getMessage()." ".$e->getCode());
    }

    if(isset($_GET['mode']) && $_GET['mode'] =="tout" ){
        $querySql = $db->prepare("SELECT * FROM users");
        $querySql->execute();
        $results = $querySql->fetchAll();
        $retour["Nombre_utilisateur"] = count($results);
        $retour["Utilisateur"] = $results;
    }

    //MODE DE CONSULTATION BONUS



    echo json_encode($retour);
}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}


