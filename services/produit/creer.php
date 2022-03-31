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
    if( !empty($_GET['nom']) && !empty($_GET['description']) && !empty($_GET['prix']) && !empty($_GET['stock']) && !empty($_GET['reference'])){

        $nom = $_GET['nom'];
        $description = $_GET['description'];
        $prix = $_GET['prix'];
        $stock = $_GET['stock'];
        $reference = $_GET['reference'];

//        if(is_float($prix) > 0 && intval($stock) >=0)
//        {
            //Vérification si le produit n'existe pas dans la base
            $querySql = $db->prepare("SELECT nom,prix,reference FROM produits WHERE nom = :nom and prix = :prix");
            $querySql->bindParam(':nom',$nom);
            $querySql->bindParam(':prix',$prix);
            $querySql->execute();
            $results = $querySql->fetchAll();

            if($results){
                $retour['Success']= false;
                $retour['Message'] = "Ce produit ".$nom." existe deja";
            }
            else{

                $querySql = $db->prepare("INSERT INTO produits (nom, description, prix, stock, reference, created_at, update_at) VALUES (:nom, :description, :prix, :stock, :reference, current_timestamp(), current_timestamp())");
                $querySql->bindParam(':nom',$nom);
                $querySql->bindParam(':description',$description);
                $querySql->bindParam(':prix',$prix);
                $querySql->bindParam(':stock',$stock);
                $querySql->bindParam(':reference',$reference);
                $querySql->execute();
                $retour["Success"] = true;
                $retour["Message"] = "Le produit à bien été créé";
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

