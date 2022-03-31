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


    switch($_GET['mode']){

        case "tout";
            $querySql = $db->prepare("SELECT * FROM produits");
            $querySql->execute();
            $results = $querySql->fetchAll();
            $retour["Success"] = true;
            $retour["Nombre_produit"] = count($results);
            $retour["Produits"] = $results;
        break;
        case "limit";
        if(isset($_GET['limit'])){
            $mode = $_GET['mode'];
            $limit = intval($_GET['limit']);
            $querySql = $db->prepare("SELECT * FROM produits LIMIT :limit");
            $querySql->bindParam(':limit',$limit,PDO::PARAM_INT);
            $querySql->execute();
            $results = $querySql->fetchAll();
            $retour["Success"] = true;
            $retour["Nombre_produit"] = count($results);
            $retour["Produits"] = $results;
        }

        break;
        default:
            $retour["Success"] = false;
            $retour["Message"] = "Un probléme est survenu";
    }

    echo json_encode($retour);
}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}


