<?php
include_once '../../config/Database.php';
include_once '../../config/header.php';
header("Access-Control-Allow-Methods: GET");
if($_SERVER['REQUEST_METHOD'] == 'GET'){

    try{
        $database = new Database();
        $db = $database->getConnection();
        $retour['Success']= true;
        $retour['Message'] = "La base est bien connecté";

    }catch (Exception $e){
        $retour['Success']= false;
        $retour['Message'] = "Echec à la connexion de la base";
        $retour['Error'] = $e->getMessage();
    }

    if(isset($_GET['token'])){
        $token = $_GET['token'];
        $querySql = $db->prepare("SELECT *  FROM produits  WHERE token= :token");
        $querySql->bindParam(':token',$token);
        $querySql->execute();
        $results = $querySql->fetchAll();
        if($results == null){
            $retour["Porduit"] = "Ce produit n'existe pas ";
        }else{
            $retour["Nombre_resultat"] =count($results);
            $retour["Produit"] = $results;
        }


    }


    echo json_encode($retour);
}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}

