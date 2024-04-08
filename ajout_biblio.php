<?php
session_start(); // Assurez-vous que la session est démarrée

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

// Assurez-vous que les ID de l'utilisateur et du jeu sont définis dans l'URL
if (isset($_GET['idgame'])) {
    $id_user = $_SESSION["id_utilisateur"];
    $id_jeu = $_GET['idgame'];
} else {
    // Gérer l'absence d'identifiants dans l'URL
    echo "Identifiant d'utilisateur ou ID de jeu non spécifié.";
    exit();
}

// Vérifiez si l'utilisateur est connecté
if (!isset($id_user)) {
    // Redirigez l'utilisateur vers la page de connexion si nécessaire
    header("Location: PageUtilisateur.php?id_utilisateur=$id_user");
    exit();
}

// érifiez si le formulaire est soumis
// Requête SQL pour insérer l'ID de l'utilisateur et l'ID du jeu dans la table bibliotèque
$sql = "INSERT INTO bibliothèque (id_utilisateur, idgame) VALUES ('$id_user', '$id_jeu')";
if ($connexion->query($sql) === TRUE) {
    // Rediriger l'utilisateur vers la page "PageUtilisateur.php" après l'ajout du jeu
    header("Location: PageUtilisateur.php?id_utilisateur=$id_user");
    exit();
} else {
    echo "Erreur lors de l'ajout du jeu à la bibliothèque : " . $connexion->error;
}
?>
