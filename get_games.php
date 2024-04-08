<?php

session_start(); // Démarrage de la session

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

// Vérifie si la plateforme est envoyée via la requête POST
if(isset($_POST['platform']) && !empty($_POST['platform'])) {
    // Récupère la plateforme sélectionnée
    $platform = $_POST['platform'];

    // Connectez-vous à votre base de données
    $connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

    // Assurez-vous que la connexion à la base de données s'est bien déroulée
    if ($connexion->connect_error) {
        die("Erreur de connexion à la base de données: " . $connexion->connect_error);
    }

    // Requête SQL pour sélectionner les jeux pour la plateforme sélectionnée
    $selectplat = "SELECT Nom FROM Jeux WHERE platform = '$platform'";

    // Exécutez la requête SQL
    $result = $connexion->query($selectplat);

    // Vérifiez s'il y a des résultats
    if ($result->num_rows > 0) {
        // Parcourez les résultats et affichez les noms des jeux
        while ($row = $result->fetch_assoc()) {
            echo $row['Nom'] . "<br>";
        }
    } else {
        // Aucun jeu trouvé pour cette plateforme
        echo "Aucun jeu trouvé pour cette plateforme.";
    }

    // Fermez la connexion à la base de données
    $connexion->close();
} else {
    // Si aucune plateforme n'est fournie, renvoyez un message d'erreur
    echo "Erreur: Aucune plateforme sélectionnée.";
}
?>
