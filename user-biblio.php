<?php

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

session_start(); // Assurez-vous que la session est démarrée

if (isset($_SESSION["id_utilisateur"])) {
    $iduser = $_SESSION["id_utilisateur"];

    // Requête SQL pour récupérer le pseudonyme de l'utilisateur
    $requete = "SELECT pseudonyme FROM utilisateur WHERE id_utilisateur = '$iduser'";
    $resultat = $connexion->query($requete);

    if ($resultat) {
        if ($resultat->num_rows > 0) {
            $row = $resultat->fetch_assoc();
            $username = $row['pseudonyme']; // Récupérer le pseudonyme de l'utilisateur
        } else {
            // Gérer le cas où aucun utilisateur n'est trouvé pour cet identifiant
            $username = "Utilisateur"; // Défaut si aucun utilisateur n'est trouvé
        }
    } else {
        echo "Erreur de requête : " . $connexion->error;
    }

} else {
    // Gérer l'absence d'identifiant d'utilisateur dans l'URL
    $username = "Utilisateur"; // Défaut si l'identifiant d'utilisateur n'est pas fourni
}


$gamesuser = "SELECT Nom,platform FROM Jeux WHERE Jeux.idgame = bibliothèque.idgame WHERE bibliothèque.id_utilisateur = '$iduser'";
$showgame = $connexion->query($gamesuser);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque-Utilisateur</title>
    <link rel="stylesheet" href="Bibliogames.css">
</head>
<body background="Fond Bibliogames connexion.png">
<div id="Bande_Biblio"><button onclick="window.location.href='PageUtilisateur.php?id_utilisateur=<?php echo($iduser); ?>'">retour</button> <a id="Bibliogames">Bibliogames</a> <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button></div>
<div style="height: 1250px;width: 1470px;border: solid black 15px; border-radius: 20px;background-color: rgb(198, 139, 30); position: relative;">
 
<div id="list-biblio">

<p>Welcome, <?php echo $username; ?>!</p>


<?php 
$gamesuser = "SELECT Nom, platform FROM Jeux INNER JOIN bibliothèque ON Jeux.idgame = bibliothèque.idgame WHERE bibliothèque.id_utilisateur = '$iduser'";
$showgame = $connexion->query($gamesuser);

if ($showgame && $showgame->num_rows > 0) {
    // Si des jeux sont trouvés dans la bibliothèque de l'utilisateur
    while ($row = $showgame->fetch_assoc()) {
        $nom = $row['Nom'];
        $platform = $row['platform'];
        echo '<div onclick="window.location.href = \'Game.php?game=' . $nom . '&platform=' . $platform . '&id_utilisateur='. $iduser . '\'" class="game">'. $nom .' - '. $platform .' </div>' ;
    }
} else {
    // Si la bibliothèque est vide
    echo "La bibliothèque est vide.";
}
?>

</div>
    
</body>
</html>