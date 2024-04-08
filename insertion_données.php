<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

if ($connexion->connect_error) {
    die("Y'a un probleme chef" . $connexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['mail'];
    $mdp = $_POST['password'];

    $sql = "INSERT INTO utilisateur (pseudonyme, email, mot_de_passe) VALUES ('$username', '$email', '$mdp')";

    if ($connexion->query($sql) === TRUE) {
        echo "<script>window.location.href = 'PageUtilisateur.php';</script>";
        exit;
    } else {
        echo "Erreur: " . $sql . "<br>" . $connexion->error;
    }
}
?>
