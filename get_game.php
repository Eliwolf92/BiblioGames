<?php
// Connexion à la base de données et autres initialisations nécessaires
session_start(); // Démarrage de la session

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

if(isset($_POST['platform']) && !empty($_POST['platform'])) {
    $platform = $_POST['platform'];

    // Requête SQL pour récupérer les jeux correspondant à la plateforme sélectionnée
    $selectplat = "SELECT Nom FROM Jeux WHERE platform = '$platform'";

    // Exécutez la requête SQL et construisez le HTML pour les jeux
    $result = $connexion->query($selectplat);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['Nom'] . "<br>"; // Affichez le nom du jeu (vous pouvez personnaliser le HTML ici)
        }
    } else {
        echo "Aucun jeu trouvé pour cette plateforme.";
    }
}
?>
