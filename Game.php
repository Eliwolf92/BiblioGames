<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

$game = ""; // Définition par défaut
$platform = ""; // Définition par défaut

$idgame = isset($_GET['idgame']) ? intval($_GET['idgame']) : null; // Récupération de l'identifiant du jeu depuis l'URL
$iduser = isset($_GET['id_utilisateur']) ? intval($_GET['id_utilisateur']) : null; // Récupération de l'identifiant de l'utilisateur depuis l'URL

// Vérification de la validité de l'identifiant du jeu
if ($idgame) {
    // Requête SQL pour récupérer l'id_info à partir de idgame
    $sql_idinfo = "SELECT id_info FROM jeux WHERE idgame = $idgame";

    // Exécution de la requête pour récupérer l'id_info
    $resultat_idinfo = $connexion->query($sql_idinfo);

    // Vérification s'il y a des résultats
    if ($resultat_idinfo->num_rows > 0) {
        // Récupération de l'id_info
        $row_idinfo = $resultat_idinfo->fetch_assoc();
        $id_info = $row_idinfo['id_info'];

        // Requête SQL pour récupérer les informations du jeu et de la plateforme à partir de l'identifiant unique de l'information "$id_info"
        $sql = "SELECT Nom, platform FROM jeux WHERE idgame = $idgame";
        $sql2 = "SELECT infogame1, infogame2, infogame3 FROM infogame WHERE id_info = $id_info ";
        $sql3 = "SELECT ImgInfoGame1, ImgInfoGame2, ImgInfoGame3 FROM infogame WHERE id_info = $id_info";

        // Exécution de la requête pour récupérer les informations du jeu et de la plateforme
        $resultat = $connexion->query($sql);

        // Vérification s'il y a des résultats
        if ($resultat->num_rows > 0) {
            // Récupération des données
            $row = $resultat->fetch_assoc();
            $game = $row['Nom'];
            $platform = $row['platform'];
        } else {
            // Gérer le cas où aucune information n'est trouvée pour cet ID
            $game = "Jeu introuvable";
            $platform = "Plateforme inconnue";
        }

        // Exécution de la requête pour récupérer les descriptions et les images de la table "infogame" en utilisant l'identifiant unique "$id_info"
        $resultat2 = $connexion->query($sql2);

        // Vérification s'il y a des résultats
        if ($resultat2->num_rows > 0) {
            // Récupération des données
            $row = $resultat2->fetch_assoc();
            $info1 = $row['infogame1'];
            $info2 = $row['infogame2'];
            $info3 = $row['infogame3'];
        } else {
            // Gérer le cas où aucune information n'est trouvée pour cet ID
            $info1 = "Aucune description disponible";
            $info2 = "Aucune description disponible";
            $info3 = "Aucune description disponible";
        }

        // Exécution de la requête pour récupérer les images de la table "infogame" en utilisant l'identifiant unique "$id_info"
        $resultat3 = $connexion->query($sql3);

        // Vérification s'il y a des résultats
        if ($resultat3->num_rows > 0) {
            // Récupération des données
            $row = $resultat3->fetch_assoc();
            // Chemin vers le dossier contenant les images
            $imgFolder = "img_info_game/";
            // Chemin complet des images
            $img1 = $imgFolder . $row['ImgInfoGame1'];
            $img2 = $imgFolder . $row['ImgInfoGame2'];
            $img3 = $imgFolder . $row['ImgInfoGame3'];
        } else {
            // Gérer le cas où aucune information n'est trouvée pour cet ID
            $img1 = "img_not_found.jpg";
            $img2 = "img_not_found.jpg";
            $img3 = "img_not_found.jpg";
        }
    } else {
        // Gérer le cas où aucune information n'est trouvée pour cet ID
        $game = "Jeu introuvable";
        $platform = "Plateforme inconnue";
        $info1 = "Aucune description disponible";
        $info2 = "Aucune description disponible";
        $info3 = "Aucune description disponible";
        $img1 = "img_not_found.jpg";
        $img2 = "img_not_found.jpg";
        $img3 = "img_not_found.jpg";
    }
} else {
    // Gérer le cas où l'identifiant du jeu n'est pas spécifié dans l'URL
    $game = "Jeu introuvable";
    $platform = "Plateforme inconnue";
    $info1 = "Aucune description disponible";
    $info2 = "Aucune description disponible";
    $info3 = "Aucune description disponible";
    $img1 = "img_not_found.jpg";
    $img2 = "img_not_found.jpg";
    $img3 = "img_not_found.jpg";
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
<div id="Bande_Biblio"> <button onclick="window.location.href='PageUtilisateur.php?id_utilisateur=<?php echo($iduser);?>'">Retour</button><a id="Bibliogames">Bibliogames</a> <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button></div>
<div class="backgroundmain">

<?php 
echo '<center>
    <div class="info-container">
        <h2>' . $game . ' sur ' . $platform . '</h2>
        <div class="info">
            <h3>Scénario :</h3>
            <p class="boxinfo">' . $info1 . '</p>
            <img class="boximg" src="' . $img1 . '" alt="Image 1">
        </div>
        <div class="info">
            <h3>Gameplay :</h3>
            <p class="boxinfo">' . $info2 . '</p>
            <img class="boximg" src="' . $img2 . '" alt="Image 2">
        </div>
        <div class="info">
            <h3>DLC :</h3>
            <p class="boxinfo">' . $info3 . '</p>
            <img class="boximg" src="' . $img3 . '" alt="Image 3">
        </div>
    </div>
    </center>';
?>
    
</body>
</html>
