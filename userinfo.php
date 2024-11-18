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

if (isset($_GET['id_utilisateur']) && isset($_GET['id_admin'])) {
    $idUtilisateur = $_GET['id_utilisateur'];
    $idAdmin = $_GET['id_admin'];

    // Requête SQL pour récupérer les jeux possédés par l'utilisateur
    $sql = "SELECT jeux.Nom, jeux.platform, jeux.idgame, jeux.img_game, jeux.id_info 
            FROM jeux
            INNER JOIN bibliothèque ON jeux.idgame = bibliothèque.idgame
            WHERE bibliothèque.id_utilisateur = $idUtilisateur";

    $resultatJeux = $connexion->query($sql);

    if (!$resultatJeux) {
        die("Erreur de requête : " . $connexion->error);
    }

    // Requête SQL pour récupérer le pseudonyme de l'utilisateur
    $requeteUtilisateur = "SELECT pseudonyme FROM utilisateur WHERE id_utilisateur = '$idUtilisateur'";
    $resultatUtilisateur = $connexion->query($requeteUtilisateur);

    if ($resultatUtilisateur && $resultatUtilisateur->num_rows > 0) {
        $row = $resultatUtilisateur->fetch_assoc();
        $username = $row['pseudonyme'];
    } else {
        $username = "Utilisateur";
    }
} else {
    echo "ID utilisateur ou ID admin non spécifié.";
    exit();
}

$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="Bibliogames.css">
</head>
<body background="Fond Bibliogames connexion.png">
<div id="Bande_Biblio">
    <button class="Retour" onclick="window.location.href='ListeUser.php?id_utilisateur=<?php echo($idAdmin); ?>'">Retour</button>
    <a id="Bibliogames">Bibliogames</a>
    <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button>
</div>
<div class="backgroundmain">
    <div class="Liste">
        <h2>Jeux possédés par <?php echo htmlspecialchars($username); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Nom du jeu</th>
                    <th>Plateforme</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($resultatJeux) && $resultatJeux->num_rows > 0) {
                    while ($row = $resultatJeux->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['Nom']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['platform']) . '</td>';
                        echo '<td><img src="' . htmlspecialchars($row['img_game']) . '" alt="' . htmlspecialchars($row['Nom']) . '"></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">Aucun jeu trouvé.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
