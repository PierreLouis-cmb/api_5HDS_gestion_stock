<?php
include_once '../../config/Database.php';
include_once '../../config/header.php';
header("Access-Control-Allow-Methods: POST");


if($_SERVER['REQUEST_METHOD'] == 'POST'){

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
    if( !empty($_GET['nom']) && !empty($_GET['prenom']) && !empty($_GET['role'])){

        $nom = $_GET['nom'];
        $prenom = $_GET['prenom'];
        $role= $_GET['role'];

            $querySql = $db->prepare("SELECT nom, prenom, role FROM users where nom = :nom and prenom = :prenom and role = :role");
            $querySql->bindParam(':nom',$nom);
            $querySql->bindParam(':prenom',$prenom);
            $querySql->bindParam(':role',$role);
            $querySql->execute();
            $results = $querySql->fetchAll();

            if($results){
                $retour['Success']= false;
                $retour['Message'] = "Cette utilisateur ".$nom." existe deja";
            }
            else{

                $querySql = $db->prepare("INSERT INTO `users` (nom, prenom, role, created_at, update_at) VALUES (:nom,:prenom,:role, current_timestamp(), current_timestamp())");
                $querySql->bindParam(':nom',$nom);
                $querySql->bindParam(':prenom',$prenom);
                $querySql->bindParam(':role',$role);
                $querySql->execute();
                $retour["Success"] = true;
                $retour["Message"] = "L'utilisateur à bien été créé";
            }

    }else{
        $retour["Success"] = false;
        $retour["Message"] = "Ne peut etre utilisé";
        $retour["Produit"] = "Il manque des infos";
    }


echo json_encode($retour);
}else{
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}

