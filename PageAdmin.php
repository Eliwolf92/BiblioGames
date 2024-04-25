
<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);


// Requête SQL pour récupérer les noms des jeux
$sql = "SELECT Nom FROM Jeux";

// Exécution de la requête
$resultat = $connexion->query($sql);

// Vérification s'il y a des résultats
if ($resultat->num_rows > 0) {
    // Création d'un tableau pour stocker les noms des jeux
    $games = array();

    // Récupération des données de chaque ligne de résultat
    while ($row = $resultat->fetch_assoc()) {
        // Ajout du nom du jeu au tableau
        $games[] = $row['Nom'];
    }
} else {
    $games = array(); // Si aucun résultat, initialisation du tableau vide
}


// Requête SQL pour récupérer lse platforms de tes jeux
$sql = "SELECT platform  FROM Jeux";

// Exécution de la requête
$resultat = $connexion->query($sql);

// Vérification s'il y a des résultats
if ($resultat->num_rows > 0) {
    // Création d'un tableau pour stocker les differentes platforms
    $platform = array();

    // Récupération des données de chaque ligne de résultat
    while ($row = $resultat->fetch_assoc()) {
        // Ajout de la platform
        $platform[] = $row['platform'];
    }
} else {
    $platform = array(); // Si aucun résultat, initialisation du tableau vide
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

    $connexion->close();
} else {
    // Gérer l'absence d'identifiant d'utilisateur dans l'URL
    $username = "Utilisateur"; // Défaut si l'identifiant d'utilisateur n'est pas fourni
}


$sql = "SELECT idgame  FROM Jeux";

// Exécution de la requête
$resultat = $connexion->query($sql);

// Vérification s'il y a des résultats
if ($resultat->num_rows > 0) {
    // Création d'un tableau pour stocker les noms des jeux
    $idgame = array();

    // Récupération des données de chaque ligne de résultat
    while ($row = $resultat->fetch_assoc()) {
        // Ajout du nom du jeu au tableau
        $idgame[] = $row['idgame'];
    }
} else {
    $idgame = array(); // Si aucun résultat, initialisation du tableau vide
}



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="Bibliogames.css">
</head>
<body background="Fond Bibliogames connexion.png">
<div id="Bande_Biblio"><a id="Bibliogames">Bibliogames</a> <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button></div>
<div class="backgroundmain">
    <div>
            <button id="addGames" onclick="window.location.href='insert_game.php'">
            </button>
    </div>

    <?php 
// Affichage des noms des jeux avec les plateformes correspondantes dans des divs
for ($i = 0; $i < count($games); $i++) {
    // Vérification pour s'assurer que l'index existe dans les deux tableaux
    if (isset($games[$i]) && isset($platform[$i]) && isset($idgame[$i])) {
        echo '<div class="game">'.$games[$i].' - '.$platform[$i].'</div>';
        echo '<button onclick=>AddBiblio</button>';
        echo '<a href="supprgamedb.php?id_jeu='.$idgame[$i].'"><button>suppr Game</button></a>';
    }
}
?>


    
</div>
<?php
?>

<div style="height: 1250px;width: 250px;border: solid black 10px; position: absolute;background-color: rgb(201, 201, 201); top: 130px;left: 1520px;">
    <div>
        <center>
            <h2 style="color: black;font-size: 150%;">Bienvenue <?php echo "$username"; ?></h2>
        </center>
    </div>
    <button class="Boutton" onclick="window.location.href = 'user-biblio.php?username=<?php echo urlencode($username); ?>'">
    Biblio
</button>
<button class="Boutton" onclick="window.location.href = 'user-profil.php?username=<?php echo urlencode($username); ?>'">
    Profil
</button>
<br><br>

<form id="gameForm">
    <label class="description" for="platform">Sélectionnez la plateforme :</label>
    <select id="platform" name="platform">
        <option value="">jouable où ?</option>
        <optgroup label="Xbox">
            <option value="Xbox serie x">Xbox série X</option>
            <option value="Xbox one">Xbox One</option>  
            <option value="Xbox 360">Xbox 360</option>
            <option value="Xbox legacy">Xbox legacy (ancienne version)</option>
        </optgroup>
        <optgroup label="PS">
            <option value="PS5">PlayStation 5</option>
            <option value="PS4">PlayStation 4</option>
            <option value="PS3">PlayStation 3</option>
            <option value="PS2">PlayStation 2</option>
        </optgroup>        
        <optgroup label="Nintendo">
            <option value="Switch">Nintendo Switch</option>
            <option value="Wii U">Wii U</option>
            <option value="Wii">Wii</option>
            <option value="Game Boy Advance">Game Boy Advance</option>
        </optgroup>
        <option value="Steam">Steam</option>
        <option value="Epic">Epic</option>
    </select>
</form>

<div id="gamesList"></div>

<script>
$(document).ready(function() {
    $('#platform').change(function() {
        $('#gamesList').empty();
        $.ajax({
            url: 'get_games.php',
            type: 'POST',
            data: $('#gameForm').serialize(),
            success: function(response) {
                $('#gamesList').html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
</script>





</body>
</html>


    
