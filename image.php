<?php
// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

// Vérifie si l'identifiant de l'image est fourni dans l'URL
if (isset($_GET['id_game'])) {
    $idgame = $_GET['id_game'];

    // Requête SQL pour récupérer l'image à partir de son identifiant
    $requete = "SELECT img_game FROM Jeux WHERE idgame = $idgame";
    $resultat = $connexion->query($requete);

    // Récupère le chemin de l'image depuis la base de données
    $image_path = $row['img_game'];

    // Vérifie si le fichier existe
    if (file_exists($image_path)) {
        // Renvoie le contenu du fichier
        header("Content-type: image/jpeg"); // Assurez-vous que le type de contenu est correct pour l'image
        readfile($image_path);
    } else {
        // Gère le cas où le fichier d'image n'existe pas
        echo 'Image not found';
    }


    if ($resultat && $resultat->num_rows > 0) {
        // Récupère les données de l'image depuis la base de données
        $row = $resultat->fetch_assoc();
        $image_data = $row['img_game'];

        // Affiche l'image
        header("Content-type: image/jpeg/png"); // Assurez-vous que le type de contenu est correct pour l'image
        echo $image_data;
    } else {
        // Gère le cas où l'image n'est pas trouvée dans la base de données
        echo 'Image not found';
    }
} else {
    // Gère le cas où l'identifiant de l'image n'est pas fourni dans l'URL
    echo 'Image ID not provided';
}

$connexion->close();
?>
