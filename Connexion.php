<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $connexion = new mysqli("localhost", "root", "Chaplin3000*", "bibliogames");

    if ($connexion->connect_error) {
        die("Échec de la connexion : " . $connexion->connect_error);
    }

    // Requête SQL pour vérifier les informations de connexion
    $requete = "SELECT * FROM utilisateur WHERE pseudonyme = '$username' AND mot_de_passe = '$password'";
    $resultat = $connexion->query($requete);

    if ($resultat) {
        if ($resultat->num_rows > 0) {
            $row = $resultat->fetch_assoc();
            $iduser = $row['id_utilisateur']; // Récupérer l'ID de l'utilisateur

            if ($row['AdminUser'] == 1) {
                $_SESSION["id_utilisateur"] = $iduser;
                header("Location: PageAdmin.php?id_utilisateur=$iduser");
                exit;
            } else {
                $_SESSION["id_utilisateur"] = $iduser;
                header("Location: PageUtilisateur.php?id_utilisateur=$iduser");
                exit;
            }
        } else {
            echo "Identifiant invalides. Veuillez réessayer.";
        }
    } else {
        echo "Erreur de requête : " . $connexion->error;
    }

    $connexion->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Bibliogames.css">
</head>

<body background="Fond Bibliogames connexion.png">

    <div id="Bande_Biblio"><button onclick="window.location.href='index.php'">retour</button><a id="Bibliogames">Bibliogames</a></div>


    <div id="Page_Connexion">
        <form method="post">
            <div><label style="position: relative; left: 170px; top: 70px;font-family: Edo SZ; font-size: 20px;" for="iduser">Pseudonyme :</label></div>
            <div><input style="border-radius: 10px; border: solid black 2px; height: 40px; width: 160px;top: 70px;position: relative; left: 170px;" type="text" name="username" placeholder="123Kevin..."></div>

            <div><label style="position: relative; left: 170px; top: 170px;font-family: Edo SZ; font-size: 20px;" for="idpassword">Mot-De-Passe :</label></div>
            <div><input style="border-radius: 10px; border: solid black 2px; height: 40px; width: 160px;top: 170px;position: relative; left: 170px;" type="password" name="password" placeholder="*****"></div>
        
            <div><button type="submit" style="border-radius: 10px; border: solid black 2px; height: 40px; width: 160px;top: 290px;position: relative; left: 170px; font-family: Cyberpunk is not dead;">Connexion</button>
            </div>
        </form>
    </div>

</body>
</html>
