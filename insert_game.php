<?php
session_start(); // Assurez-vous que la session est démarrée

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

// Vérifie si la connexion à la base de données est établie
if ($connexion->connect_error) {
    die("Y'a un problème chef" . $connexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si le formulaire d'ajout de jeu est soumis

    // Récupérer les données du formulaire
    $NameGame = $connexion->real_escape_string($_POST['Nom']);
    $PlatformGame = $connexion->real_escape_string($_POST['platform']);
    $Info1 = $connexion->real_escape_string($_POST['description1']);
    $Info2 = $connexion->real_escape_string($_POST['description2']);
    $Info3 = $connexion->real_escape_string($_POST['description3']);

    // Vérifier si un fichier d'image principale du jeu a été téléchargé
    if (!empty($_FILES['image']['name'])) {
        // Chemin de destination pour l'image principale du jeu téléchargée
        $uploadDir = __DIR__ . "/Img_game/";
        // Nouveau nom de fichier basé sur le nom du jeu et de la plateforme
        $newFileName = $connexion->real_escape_string($NameGame . '_' . $PlatformGame . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        // Chemin complet du fichier de destination
        $destination = $uploadDir . $newFileName;

        // Déplacer le fichier téléchargé vers le dossier de destination
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            // Succès du téléchargement de l'image principale du jeu
            // Maintenant, vous pouvez utiliser $newFileName pour stocker dans la base de données
            $ImageGame = $newFileName;
        } else {
            // Échec du téléchargement de l'image principale du jeu
            echo "Erreur lors du téléchargement de l'image principale du jeu.";
            exit();
        }
    } else {
        // Aucun fichier d'image principale du jeu téléchargé
        echo "Aucune image principale du jeu sélectionnée.";
        exit();
    }

    // Vérifier si des fichiers d'image de description ont été téléchargés
    if (
        !empty($_FILES['image_info1']['name']) &&
        !empty($_FILES['image_info2']['name']) &&
        !empty($_FILES['image_info3']['name'])
    ) {
        // Chemin de destination pour les images de description
        $uploadDirInfo = __DIR__ . "/img_info_game/";

        // Vérifier et télécharger l'image de description 1
        $newFileName1 = $connexion->real_escape_string($NameGame . '_info1.' . pathinfo($_FILES['image_info1']['name'], PATHINFO_EXTENSION));
        $destination1 = $uploadDirInfo . $newFileName1;

        if (move_uploaded_file($_FILES['image_info1']['tmp_name'], $destination1)) {
            // Succès du téléchargement de l'image de description 1
            $imginfo1 = $newFileName1;
        } else {
            // Échec du téléchargement de l'image de description 1
            echo "Erreur lors du téléchargement de l'image de description 1.";
            exit();
        }

        // Vérifier et télécharger l'image de description 2
        $newFileName2 = $connexion->real_escape_string($NameGame . '_info2.' . pathinfo($_FILES['image_info2']['name'], PATHINFO_EXTENSION));
        $destination2 = $uploadDirInfo . $newFileName2;

        if (move_uploaded_file($_FILES['image_info2']['tmp_name'], $destination2)) {
            // Succès du téléchargement de l'image de description 2
            $imginfo2 = $newFileName2;
        } else {
            // Échec du téléchargement de l'image de description 2
            echo "Erreur lors du téléchargement de l'image de description 2.";
            exit();
        }

        // Vérifier et télécharger l'image de description 3
        $newFileName3 = $connexion->real_escape_string($NameGame . '_info3.' . pathinfo($_FILES['image_info3']['name'], PATHINFO_EXTENSION));
        $destination3 = $uploadDirInfo . $newFileName3;

        if (move_uploaded_file($_FILES['image_info3']['tmp_name'], $destination3)) {
            // Succès du téléchargement de l'image de description 3
            $imginfo3 = $newFileName3;
        } else {
            // Échec du téléchargement de l'image de description 3
            echo "Erreur lors du téléchargement de l'image de description 3.";
            exit();
        }
    } else {
        // Certains fichiers d'image de description sont manquants
        echo "Veuillez sélectionner toutes les images de description.";
        exit();
    }

    // Requête SQL pour insérer le jeu et ses informations dans la base de données
    $sql2 = "INSERT INTO infogame (infogame1, ImgInfoGame1, infogame2, ImgInfoGame2, infogame3, ImgInfoGame3) VALUES ('$Info1', '$imginfo1', '$Info2', '$imginfo2', '$Info3', '$imginfo3')";
    
    // Exécuter la requête pour insérer les informations de jeu
    if ($connexion->query($sql2) === TRUE) {
        // Récupérer l'ID de l'information de jeu insérée
        $result = $connexion->query("SELECT LAST_INSERT_ID() AS id");
        $row = $result->fetch_assoc();
        $id_info = $row['id'];

        // Requête SQL pour insérer le jeu avec son ID d'information de jeu
        $sql = "INSERT INTO jeux (Nom, platform, img_game, id_info) VALUES ('$NameGame', '$PlatformGame', '$ImageGame', '$id_info')";
        
        // Exécuter la requête pour insérer le jeu
        if ($connexion->query($sql) === TRUE) {
            echo "<script>window.location.href = 'PageAdmin.php';</script>";
        } else {
            echo "Erreur lors de l'insertion du jeu: " . $connexion->error;
        }
    } else {
        echo "Erreur lors de l'insertion des informations du jeu: " . $connexion->error;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Bibliogames.css">
</head>
<body background="Fond Bibliogames connexion.png">
<div id="Bande_Biblio"><a id="Bibliogames">Bibliogames</a> <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button></div>
<div class="backgroundmain">
    <div id="NouveauJeux">
        <form method="post" enctype="multipart/form-data">
            <label class="description" for="Nom">Nom du jeu :</label><br>
            <input type="text" name="Nom" placeholder="Nom du jeu" /><br><br>

            <label class="description" for="platform">Plateforme :</label><br>
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
            </select><br><br>

            <label class="description" for="image">Image principale du jeu :</label>
            <input type="file" id="image" name="image" /><br><br>

            <!-- Première groupe de zone de texte pour la description et l'image de description -->
            <div class="aline">
                <label class="description" for="description1">Description principale :</label><br>
                <textarea maxlength="3000" class="Texte" name="description1" placeholder="Donner la description principale (scenario)..."></textarea><br>
                <label class="description" for="image_info1">Image description 1 :</label>
                <input type="file" id="image_info1" name="image_info1" /><br><br>
            </div>

            <!-- Deuxième groupe de zone de texte pour la description et l'image de description -->
            <div class="aline">
                <label class="description" for="description2">Description 2 :</label><br>
                <textarea maxlength="3000" class="Texte" name="description2" placeholder="Donner une 2eme description (gameplay)...." ></textarea><br>
                <label class="description" for="image_info2">Image description 2 :</label>
                <input type="file" id="image_info2" name="image_info2" /><br><br>
            </div>

            <!-- Troisième groupe de zone de texte pour la description et l'image de description -->
            <div class="aline">
                <label class="description" for="description3">Description 3 :</label><br>
                <textarea maxlength="3000" class="Texte" name="description3" placeholder="Donner une 3eme description si possible..." ></textarea><br>
                <label class="description" for="image_info3">Image description 3 :</label>
                <input type="file" id="image_info3" name="image_info3" /><br><br>
            </div>

            <button type="submit">Valider !</button>
        </form>
    </div>
</div>
</body>
</html>
