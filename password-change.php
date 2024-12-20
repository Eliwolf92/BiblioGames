<?php
session_start();

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

// Connexion à la base de données
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

    // Traitement du changement de mot de passe
    if (isset($_POST["change_password"])) {
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        // Vérification que les mots de passe correspondent
        if ($new_password === $confirm_password) {
            // Vérification que l'ancien mot de passe correspond à celui stocké dans la base de données
            $requete_old_password = "SELECT mot_de_passe FROM utilisateur WHERE id_utilisateur = '$iduser'";
            $resultat_old_password = $connexion->query($requete_old_password);

            if ($resultat_old_password) {
                $row = $resultat_old_password->fetch_assoc();
                $stored_old_password = $row['mot_de_passe'];

                if (sha1($old_password) === $stored_old_password) {
                    // Hashage du nouveau mot de passe
                    $hashed_password = sha1($new_password);

                    // Requête SQL pour mettre à jour le mot de passe de l'utilisateur
                    $requete_update_password = "UPDATE utilisateur SET mot_de_passe = '$hashed_password' WHERE id_utilisateur = '$iduser'";
                    $resultat_update_password = $connexion->query($requete_update_password);

                    if ($resultat_update_password) {
                        echo "<script>alert('Mot de passe changé avec succès.');</script>";
                    } else {
                        echo "<script>alert('Une erreur s'est produite lors du changement de mot de passe. Veuillez réessayer.');</script>";
                    }
                } else {
                    echo "<script>alert('L'ancien mot de passe est incorrect. Veuillez réessayer.');</script>";
                }
            } else {
                echo "Erreur de requête : " . $connexion->error;
            }
        } else {
            echo "<script>alert('Les mots de passe ne correspondent pas. Veuillez réessayer.');</script>";
        }
    }

    // Fermeture de la connexion à la base de données
    $connexion->close();
} else {
    // Gérer l'absence d'identifiant d'utilisateur dans l'URL
    $username = "Utilisateur"; // Défaut si l'identifiant d'utilisateur n'est pas fourni
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mot de passe</title>
</head>
<body>

<h2>Changer le mot de passe de <?php echo $username; ?></h2>

<form method="post">
    <label for="old_password">Ancien mot de passe :</label>
    <input type="password" name="old_password" id="old_password" required><br><br>

    <label for="new_password">Nouveau mot de passe :</label>
    <input type="password" name="new_password" id="new_password" required><br><br>
    
    <label for="confirm_password">Confirmer le mot de passe :</label>
    <input type="password" name="confirm_password" id="confirm_password" required><br><br>

    <input type="submit" name="change_password" value="Changer le mot de passe">
</form>

</body>
</html>
