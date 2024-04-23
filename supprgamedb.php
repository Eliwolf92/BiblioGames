<?php

$server = "localhost";
$username = "root";
$password = "Chaplin3000*";
$dbname = "bibliogames";

// Création de la connexion
$conn = new mysqli($server, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupération de l'ID du jeu à supprimer
$id_jeu = $_GET['id_jeu']; // Supposons que vous récupérez l'ID du jeu depuis un formulaire GET

// Requête de suppression
$sql = "DELETE FROM jeux WHERE idgame = $id_jeu";

// Exécution de la requête
if ($conn->query($sql) === TRUE) {
    // Redirection vers la page "PageAdmin.php" une fois la ligne supprimée
    header("Location: PageAdmin.php");
    exit(); // Assurez-vous d'utiliser exit() après la redirection pour arrêter l'exécution du script
} else {
    echo "Erreur lors de la suppression de la ligne : " . $conn->error;
}

// Fermeture de la connexion
$conn->close();
?>
