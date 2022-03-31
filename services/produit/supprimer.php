<?php
include_once '../../config/Database.php';
include_once '../../config/header.php';
header("Access-Control-Allow-Methods: DELETE");

if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
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
        if(intval($token) > 0){


            $querySqlVerf = $db->prepare("SELECT token FROM produits where token = :token");
            $querySqlVerf->bindParam(':token',$token);
            $querySqlVerf->execute();
            $results = $querySqlVerf->fetchAll();

            //Vérification si le produit existe bien vat de le supprimer
            if($results){
                $querySql = $db->prepare("DELETE FROM produits WHERE token = :token");
                $querySql->bindParam(':token',$token);
                $querySql->execute();
                $retour["Success"] = true;
                $retour["Message"] = "Le produit à bien été supprimé"; //Faire une fonction success true

            }else{
                $retour['Success']= false;
                $retour['Message'] = "Ce produit n'existe pas";

            }

        }else{
            $retour['Success']= false;
            $retour['Message'] = $token." n'est pas valide";
        }


    }else{

        $retour["Success"] = false;
        $retour["Message"] = "Ne peut etre utilisé"; //Faire une fonction success true
        $retour["Utilisateur"] = "Il manque des infos";
    }

    echo json_encode($retour);
}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
