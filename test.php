<?php

$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "Chaplin3000*";
$nomBaseDeDonnees = "bibliogames";


$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $nomBaseDeDonnees);


if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

echo "Connexion réussie à la base de données";


?>
