<?php
session_start();

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

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

    // Modification du pseudonyme si le formulaire est soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérifier si le champ "nouveauPseudo" est défini et non vide
        if (isset($_POST["nouveauPseudo"]) && !empty($_POST["nouveauPseudo"])) {
            $nouveauPseudo = $_POST["nouveauPseudo"];

            // Requête SQL pour mettre à jour le pseudonyme de l'utilisateur
            $requeteUpdate = "UPDATE utilisateur SET pseudonyme = '$nouveauPseudo' WHERE id_utilisateur = '$iduser'";
            if ($connexion->query($requeteUpdate) === TRUE) {
                // Afficher un message de succès si la mise à jour a réussi
                echo "Pseudonyme mis à jour avec succès.";
            } else {
                // Afficher un message d'erreur si la mise à jour a échoué
                echo "Erreur lors de la mise à jour du pseudonyme : " . $connexion->error;
            }
        } else {
            // Afficher un message d'erreur si le champ "nouveauPseudo" est vide
            echo "Veuillez saisir un nouveau pseudonyme.";
        }
    }

    $connexion->close();
} else {
    // Gérer l'absence d'identifiant d'utilisateur dans l'URL
    $username = "Utilisateur"; // Défaut si l'identifiant d'utilisateur n'est pas fourni
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer de Pseudo</title>
    <link rel="stylesheet" href="Bibliogames.css">
</head>
<body background="Fond Bibliogames connexion.png">
<div id="Bande_Biblio">
    <button class="Retour" onclick="window.location.href='PageUtilisateur.php?id_utilisateur=<?php echo urlencode($iduser);?>'">Retour</button>
    <a id="Bibliogames">Bibliogames</a>
    <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button>
</div>
<div class="backgroundmain">
    <form method="post">
        Pseudo actuel : <?php echo "$username"; ?> <br><br>
        Nouveau pseudonyme : <input type="text" name="nouveauPseudo"><br><br>
        <input type="submit" value="Changer">
    </form>
</div>
</body>
</html>
