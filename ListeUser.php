<?php
session_start();

$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

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

    // Requête SQL pour récupérer tous les utilisateurs
    $requeteUtilisateurs = "SELECT id_utilisateur, pseudonyme FROM utilisateur";
    $resultatUtilisateurs = $connexion->query($requeteUtilisateurs);

    if (!$resultatUtilisateurs) {
        echo "Erreur de requête : " . $connexion->error;
    }

} else {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: connexion.php");
    exit();
}

// Supprimer un utilisateur si la requête est reçue
if (isset($_GET['supprimer'])) {
    $idUtilisateurASupprimer = $_GET['supprimer'];

    // Requête SQL pour supprimer l'utilisateur
    $requeteSuppression = "DELETE FROM utilisateur WHERE id_utilisateur = '$idUtilisateurASupprimer'";

    if ($connexion->query($requeteSuppression) === TRUE) {
        echo "<script>alert('Utilisateur supprimé avec succès.');</script>";
        // Rafraîchir la page après suppression
        echo "<script>window.location.href = 'ListeUser.php';</script>";
    } else {
        echo "Erreur de suppression : " . $connexion->error;
    }
}

$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link rel="stylesheet" href="Bibliogames.css">
</head>
<body background="Fond Bibliogames connexion.png">
<div id="Bande_Biblio">
    <button class="Retour" onclick="window.location.href='PageUtilisateur.php?id_utilisateur=<?php echo($iduser); ?>'">Retour</button>
    <a id="Bibliogames">Bibliogames</a>
    <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button>
</div>
<div class="backgroundmain">
    <div class="Liste">
        <h2>Liste des utilisateurs</h2>
        <table>
            <thead>
                <tr>
                    <th>Pseudonyme</th>
                    <th>Profil</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($resultatUtilisateurs) && $resultatUtilisateurs->num_rows > 0) {
                    while ($row = $resultatUtilisateurs->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['pseudonyme'] . '</td>';
                        echo '<td><a href="userinfo.php?id_utilisateur=' . $row['id_utilisateur'] . '&id_admin=' . $iduser . '">Voir Profil</a></td>';
                        echo '<td><button onclick="supprimerUtilisateur(' . $row['id_utilisateur'] . ')">Supprimer</button></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">Aucun utilisateur trouvé.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function supprimerUtilisateur(idUtilisateur) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
        window.location.href = 'ListeUser.php?supprimer=' + idUtilisateur;
    }
}
</script>

</body>
</html>
