<?php
header("Allow-Access-Control-Origin: *");
header("Allow-Access-Control-Methods: POST");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    
    // vérification de l'existence des clés
    if (isset($data->file) && isset($data->uploaderName))
    {
        // vérification de l'existence des valeurs
        if (!empty($data->file) && !empty($data->uploaderName)) {
            // appel des fichiers externe 
            require_once("Database.php");
            require_once("SaveFileInDb.php");

            // création de l'instance d'un enregistrement 
            $saveTool = new SaveFileInDb($con);
            $name = $data->uploaderName;
            $file = $data->file;

            $targetDir = dirname(__DIR__).DIRECTORY_SEPARATOR."fileDir";
            $targetFile = $targetDir.basename($file["name"]);
            // contrôle de la validité de l'extension du fichier
            $fileExtention = pathinfo($file["name"],PATHINFO_EXTENSION);
            $extentionValid = ["png", "jpg", "jpeg"];
            if (!in_array($fileExtention,$extentionValid)) {
                die(print json_encode(["message"=>"Erreur : Invalid extension"]));
            }
            // contrôle de la validité de la taille du fichier
            if ($file['size'] > 500000) {
                die(print json_encode(["message"=>"Erreur : Le fichier est trop volumineux"]));
            }

            // c'est lorsque le fichier est uploader qu'on envoi le reste dans la base de donnée
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                $saveTool ->setFileName($file["name"]);
                $saveTool ->setUploaderName($name);
                $r = $saveTool ->saveFile();

                if ($r) {
                    die(
                        print json_encode(["message" =>"Succès : Informations enregistré avec succes"],JSON_PRETTY_PRINT)
                    );
                } else {
                    die(
                        print json_encode(["message" =>"Erreur : Une erreur lors de l'enregistrement"],JSON_PRETTY_PRINT)
                    );
                }
            } else {
                die(print json_encode(["message" => "Erreur : une erreur est survenus lors de l'upload"]));
            }
        } else {
            die(
                print json_encode(["message" =>"Erreur : Les champs sont obligatoires !"],JSON_PRETTY_PRINT)
            );
        }
    }else {
        die(
            print json_encode(["message" =>"Erreur : Les données ne sont pas définies"],JSON_PRETTY_PRINT)
        );
    }
} else {
    die(
        print json_encode(["message" => "Erreur : problème de droit à la méthode"],JSON_PRETTY_PRINT)
    );
}