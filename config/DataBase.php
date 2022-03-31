<?php
class Database
{
    private $host ="localhost";
    private $dbname ="api_php";
    private $user ="root";
    private $pwd = "";
    public $connexion;


    public function getConnection(){
        $this->connexion = null;

        try {
            $this->connexion = new PDO ('mysql:host=localhost;port=3306;dbname=api_5HDS_gestion_stock;','root','');

        }catch (Exception $e){
            echo "Erreur de connection".$e->getMessage();
        }

        return $this->connexion;
    }
}