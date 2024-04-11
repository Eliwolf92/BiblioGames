<?php
session_start(); // Assurez-vous que la session est démarrée

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

// Vérifie si la connexion à la base de données est établie
if ($connexion->connect_error) {
    die("Y'a un probleme chef" . $connexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si le formulaire d'ajout de jeu est soumis

    $NameGame = $_POST['Nom'];
    $PlatformGame = $_POST['platform'];
    $ImageGame = $_POST['image']; 


    //verification Image pour le jeux à ajouté dans la liste    
    if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK){
        switch($_FILES["image"]["error"]){
            case UPLOAD_ERR_PARTIAL:
                exit("No file was uploaded");
                break;
            case UPLOAD_ERR_NO_FILE:
                exit("No file was uploaded");
                break;
            case UPLOAD_ERR_EXTENSION:
                exit("File upload stopped by a PHP extension");
                break;
            case UPLOAD_ERR_FORM_SIZE:
                exit("File exceeds MAW_FILE_SIZE in the HTML form");
                break;
            default:
                exit("Unknown upload error");
                break;
    
            }       
        }
    
    if ($_FILES["image"]["size"] > 5000000){
        exit("File too large (max 5 mb)");
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);  
    
    $mime_type = $finfo->file($_FILES["image"]["tmp_name"]);
    
    
    $mime_types = ["image/gif", "image/png", "image/jpg"];
    
    if ( ! in_array($_FILES["image"]["type"], $mime_types)) {
        exit("Invalid file type");
    }
    
    $pathinfo = pathinfo($_FILES['image']['name']);

    $base = $pathinfo["filename"];


    $filename = $_FILES["image"]["name"];
    
    $destination = __DIR__. "/Img_game/" . $filename;
    
    if (! move_uploaded_file($_FILES["image"]["tmp_name"], $destination)){
        exit("peut pas transferer l'image");
    }
    
    
    print_r($_FILES);



    // Requête SQL pour insérer le jeu dans la base de données
    $sql = "INSERT INTO jeux (Nom, platform, img_game) VALUES ('$NameGame', '$PlatformGame', '$ImageGame')";
    if ($connexion->query($sql) === TRUE) {
        echo "<script>window.location.href = 'PageAdmin.php';</script>";
    } else {
        echo "Erreur: " . $sql . "<br>" . $connexion->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Bibliogames.css">
</head>
<body background="Fond Bibliogames connexion.png">
    <div id="Bande_Biblio"><a id="Bibliogames">Bibliogames</a> <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button></div>
    <div style="height: 1250px;width: 1470px;border: solid black 15px; border-radius: 20px;background-color: rgb(198, 139, 30); position: relative;">
        <div id="NouveauJeux">
            <form method="post" enctype="multipart/form-data">
                <label class="description" for="Nom">Nom du jeu :</label>
                <input type="text" id="Nom" name="Nom" placeholder="nom du jeu" />


                <label class="description" for="platform">Plateforme :</label>
                <select id="platform" name="platform">
                    <option value="">Jouable où ?</option>
                    <optgroup label="Xbox">
                        <option value="Xbox serie x">Xbox série X</option>
                        <option value="Xbox one">Xbox One</option>  
                        <option value="Xbox 360">Xbox 360</option>
                        <option value="Xbox legacy">Xbox legacy (ancienne version)</option>
                    </optgroup>
                    <optgroup label="PS">
                        <option value="PS5">PlayStation 5</option>
                        <option value="PS4">PlayStation 4</option>
                        <option value="PS3">PlayStation 3</option>
                        <option value="PS2">PlayStation 2</option>
                    </optgroup>        
                    <optgroup label="Nintendo">
                        <option value="Switch">Nintendo Switch</option>
                        <option value="Wii U">Wii U</option>
                        <option value="Wii">Wii</option>
                        <option value="Game Boy Advance">Game Boy Advance</option>
                    </optgroup>
                    <option value="Steam">Steam</option>
                    <option value="Epic">Epic</option>
                </select>


                <label class="description" for="image">Image du jeu :</label>
                <input type="file" id="image" name="image" /><br>
                <button type="submit">Valider !</button>


            </form>
        </div>
    </div>
</body>
</html>





