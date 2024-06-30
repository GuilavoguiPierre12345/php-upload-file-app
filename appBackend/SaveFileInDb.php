<?php
class SaveFileInDb {
    // les variables clés
    private $table = "files";
    private $connexion = null;

    // les attributs
    private $uploaderName;
    private $fileName;

    // le constructeur 
    public function  __construct($db)
    {
        if ($this->connexion === null) {
            $this ->connexion = $db;
        }
    }

    // la méthode de transfer dans la base donnée
    public function saveFile()
    {
        $sql = "INSERT INTO $this->table(uploaderName,fileName) VALUES(:up,:f)";
        $values = ["up" =>$this->uploaderName,"f" =>$this->fileName];
        try {
            $stmt = $this->connexion->prepare($sql);
            $response = $stmt ->execute($values);
            return $response;
        } catch (PDOException $e) {
            die(print json_encode(["message" =>"Erreur aucours de l'envoi dans la base de donnée ".$e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    // la méthode pour l'affichage des images
    public function readFile()
    {
        $sql = "SELECT * FROM $this ->table";
        try {
            $stmt = $this->connexion->prepare($sql);
            return $stmt->execute();
        } catch (PDOException $e) {
            die(print json_encode(["message" =>"Erreur d'affichage des données  ".$e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /**
     * Set the value of uploaderName
     *
     * @return  self
     */ 
    public function setUploaderName($uploaderName)
    {
        $this->uploaderName = $uploaderName;
        return $this;
    }

    /**
     * Set the value of fileName
     *
     * @return  self
     */ 
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
}