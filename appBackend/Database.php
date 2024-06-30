<?php

class Database
{
    // les propriétés de la classe 
    private $host = "localhost";
    private $dbname = "uploaddb";
    private $user = "root";
    private $password = "";
    private $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ];
    
    // méthode de connexion à la base de donnees
    public function getConnexion()
    {
        $conn = null;

        try {
            $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;char_set=utf8", $this->user, $this->password, $this->options);
        } catch (PDOException $e) {
            die(json_encode("Erreur de connexion a la base de données : " . $e->getMessage(), JSON_PRETTY_PRINT));
        }
        // retourne la connexion 
        return $conn;
    }
}
// création d'une de la  connexion 
$db = new Database();
$con = $db->getConnexion();
