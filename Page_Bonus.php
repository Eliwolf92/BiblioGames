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
    <title>Document</title>
    <link rel="stylesheet" href="Bibliogames.css">
    <script>
        // Fonction pour récupérer les combinaisons depuis la base de données
        function chargerCombinaisons() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'charger_combinaisons.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var combinaisons = JSON.parse(xhr.responseText);
                    afficherCombinaisons(combinaisons);
                }
            };
            xhr.send();
        }

        // Fonction pour afficher les combinaisons sur la page
        function afficherCombinaisons(combinaisons) {
            var listeCombinaisons = document.getElementById('liste-combinaisons');
            listeCombinaisons.innerHTML = ''; // Efface le contenu précédent

            combinaisons.forEach(function(combinaison) {
                var item = document.createElement('li');
                item.textContent = combinaison.nom_combinaison + ': ' + combinaison.description;
                listeCombinaisons.appendChild(item);
            });
        }

        // Appel de la fonction pour charger et afficher les combinaisons au chargement de la page
        window.onload = function() {
            chargerCombinaisons();
        };
    </script>
</head>
<body background="Fond Bibliogames connexion.png">
<div id="Bande_Biblio"> <button onclick="window.location.href='PageUtilisateur.php?id_utilisateur=<?php echo($iduser);?>'">Retour</button><a id="Bibliogames">Bibliogames</a> <button id="Deconnect" onclick="window.location.href = 'déconnexion.php'">Déconnexion</button></div>
<div class="backgroundmain">
    <h1>Bienvenue, <?php echo $username; ?>!</h1>
    <h2>Crédits</h2>
    <ul id="liste-combinaisons">
        <!-- Les combinaisons seront ajoutées ici dynamiquement -->
    </ul>
</div>
</body>
</html>
