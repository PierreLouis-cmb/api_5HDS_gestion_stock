<?php
include_once '../../config/Database.php';
include_once '../../config/header.php';
header("Access-Control-Allow-Methods: PUT");

if($_SERVER['REQUEST_METHOD'] == 'PUT'){
    try {
        $database = new Database();
        $db = $database->getConnection();
        $retour["Success"] = true;
        $retour["Success_BDD"] = true;
    }catch(Exception $e) {
        echo json_encode('Il y à eu une erreur de connexion à la base'.$e->getMessage()." ".$e->getCode());
    }


    if(!empty($_GET['nom']) && !empty($_GET['prenom']) && !empty($_GET['role']) && !empty($_GET['token'])){
        $nom = $_GET['nom'];
        $prenom = $_GET['prenom'];
        $role= $_GET['role'];
        $token = $_GET['token'];



        //Vérification si le produit n'existe pas dans la base
        $querySql = $db->prepare("SELECT nom, prenom, role FROM users where token = :token ");
        $querySql->bindParam(':token',$token);
        $querySql->execute();
        $results = $querySql->fetchAll();
        if($results){
            //Si il existe alors je peux le modifier
            $querySql = $db->prepare("UPDATE users SET nom=:nom,prenom=:prenom,role=:role,update_at = current_timestamp() WHERE  token = :token");
            $querySql->bindParam(':nom',$nom);
            $querySql->bindParam(':prenom',$prenom);
            $querySql->bindParam(':role',$role);
            $querySql->bindParam(':token',$token);
            $querySql->execute();
            $retour["Success"] = true;
            $retour["Message"] = "L'utilisateur ". $nom. " ".$prenom." à bien été mis a jour ";
        }
        else{
            $retour['Success']= false;
            $retour['Message'] = "Cette utilisateur n'exsite pas ";


        }

    }else{
        $retour["Success"] = false;
        $retour["Message"] = "Ne peut etre utilisé"; //Faire une fonction success true
        $retour["produit"] = "Il manque des infos";
    }





    if (array_key_exists("produit",$retour)){
        $retour["Success"] = false;
        $retour["Message"] = 'Il y à une erreur dans votre requete';
    }

    //MODE DE CONSULTATION BONUS



    echo json_encode($retour);
}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}


