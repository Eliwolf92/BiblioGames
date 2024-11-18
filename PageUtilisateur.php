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
} else {
    // Gérer l'absence d'identifiant d'utilisateur dans l'URL
    $username = "Utilisateur"; // Défaut si l'identifiant d'utilisateur n'est pas fourni
}

// Récupération de l'ordre de tri
$order = isset($_GET['order']) && ($_GET['order'] == 'DESC') ? 'DESC' : 'ASC';

// Requête SQL pour récupérer les noms des jeux triés par nombre total de téléchargements
$sql = "SELECT jeux.Nom, jeux.platform, jeux.idgame, COUNT(bibliothèque.idgame) AS total_downloads
        FROM jeux
        LEFT JOIN bibliothèque ON jeux.idgame = bibliothèque.idgame
        GROUP BY jeux.idgame
        ORDER BY total_downloads $order";

// Exécution de la requête
$resultat = $connexion->query($sql);

// Vérification s'il y a des résultats
if (!$resultat) {
    die("Erreur de requête : " . $connexion->error);
}

// Création des tableaux pour stocker les jeux et leurs informations
$games = array();
$platform = array();
$idgame = array();
$total_downloads = array();

// Récupération des données de chaque ligne de résultat
while ($row = $resultat->fetch_assoc()) {
    // Ajout des données aux tableaux correspondants
    $games[] = $row['Nom'];
    $platform[] = $row['platform'];
    $idgame[] = $row['idgame'];
    $total_downloads[] = $row['total_downloads'];
}

$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="Bibliogames.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .tri {
            margin-bottom: 10px;
        }
        .jeu {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .jeu img {
            max-width: 100px;
            height: auto;
        }
        .game-details {
            margin-top: 5px;
        }
        .gamebutton {
            margin-top: 5px;
        }
        .Boutton {
            margin-top: 10px;
        }
    </style>
</head>
<body background="Fond Bibliogames connexion.png">
<div id="Bande_Biblio">
    <a id="Bibliogames">Bibliogames</a>
    <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button>
</div>
<div class="backgroundmain">
    <div class="Liste">
        <h2>Liste des jeux de <?php echo htmlspecialchars($username); ?></h2>
        
        <!-- Boutons de tri -->
        <div class="tri">
            <a href="PageUtilisateur.php?order=ASC">Tri croissant</a> |
            <a href="PageUtilisateur.php?order=DESC">Tri décroissant</a>
        </div>

        <!-- Affichage des jeux -->
        <?php 
        if (isset($games) && is_array($games) && count($games) > 0) {
            for ($i = 0; $i < count($games); $i++) {
                // Chemin de l'image du jeu
                $imagePath = 'Img_game/' . $games[$i] . '.png';
                
                // Vérifier si le fichier existe avant de l'afficher
                if (file_exists($imagePath)) {
                    echo '
                    <div class="jeu">
                        <div class="game-container" data-idgame="' . $idgame[$i] . '">
                            <a href="Game.php?idgame=' . $idgame[$i] . '&id_utilisateur=' . $iduser . '">
                                <img class="img_game" src="' . $imagePath . '" alt="' . $games[$i] . '">
                            </a>
                            <div class="game-details">
                                <div>' . $games[$i] . ' - <span class="platform">' . $platform[$i] . '</span></div>
                                <div>Nombre total de téléchargements : ' . $total_downloads[$i] . '</div>
                                <button class="gamebutton" onclick="window.location.href = \'ajout_biblio.php?id_utilisateur=' . $iduser . '&idgame=' . $idgame[$i] . '\'" type="button" name="ajoutbiblio" value="AddBiblio">Ajouter à la bibliothèque</button>
                            </div>
                        </div>
                    </div>';
                } else {
                    echo '
                    <div class="jeu">
                        <div class="game-container" data-idgame="' . $idgame[$i] . '">
                            <a href="Game.php?idgame=' . $idgame[$i] . '&id_utilisateur=' . $iduser . '">
                                <div>Image non disponible</div>
                            </a>
                            <div class="game-details">
                                <div>' . $games[$i] . ' - <span class="platform">' . $platform[$i] . '</span></div>
                                <div>Nombre total de téléchargements : ' . $total_downloads[$i] . '</div>
                                <button class="gamebutton" onclick="window.location.href = \'ajout_biblio.php?id_utilisateur=' . $iduser . '&idgame=' . $idgame[$i] . '\'" type="button" name="ajoutbiblio" value="AddBiblio">Ajouter à la bibliothèque</button>
                            </div>
                        </div>
                    </div>';
                }
            }
        } else {
            echo "Aucun jeu trouvé.";
        }
        ?>
    </div>
</div>

<div style="height: 1250px;width: 250px;border: solid black 10px; position: absolute;background-color: rgb(201, 201, 201); top: 100px;left: 1520px;">
    <div>
        <center>
            <h2 style="color: black;font-size: 150%;">Bienvenue <?php echo "$username"; ?></h2>
        </center>
    </div>
    <button class="Boutton" onclick="window.location.href = 'user-biblio.php?id_utilisateur=<?php echo($iduser); ?>'">
        Biblio
    </button>
    <button class="Boutton" onclick="window.location.href = 'user-profil.php?id_utilisateur=<?php echo($iduser); ?>'">
        Profil
    </button>
</div>

</body>
</html>
