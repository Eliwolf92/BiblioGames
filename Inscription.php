<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "Chaplin3000*";
$basededonnees = "bibliogames";


$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);


if ($connexion->connect_error) {
    die("Y'a un probleme chef" . $connexion->connect_error);
}

?>

<!DOCTYPE html
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="Bibliogames.css">

</head>
<body background="Fond Bibliogames connexion.png">
    <div id="Bande_Biblio"><button onclick="window.location.href='index.php'">retour</button><a id="Bibliogames">Bibliogames</a></div>

    
    <div id="Page_Connexion">
    <form method="post">
        <div><label style="position: relative; left: 170px; top: 70px;font-family: Edo SZ; font-size: 20px;" for="username">Pseudonyme :</label></div>
        <div><input style="border-radius: 10px; border: solid black 2px; height: 40px; width: 160px;top: 70px;position: relative; left: 170px;" type="text" name="username" id="username" placeholder="123Kevin..."></div>

        <div><label style="position: relative; left: 170px; top: 120px;font-family: Edo SZ; font-size: 20px;" for="mail">Adresse mail :</label></div>
        <div><input style="border-radius: 10px; border: solid black 2px; height: 40px; width: 160px;top: 120px;position: relative; left: 170px;" type="email" name="mail" id="mail" placeholder="exemple@gmail.com"></div>
        
        <div><label style="position: relative; left: 170px; top: 170px;font-family: Edo SZ; font-size: 20px;" for="password">Mot-De-Passe :</label></div>
        <div><input style="border-radius: 10px; border: solid black 2px; height: 40px; width: 160px;top: 170px;position: relative; left: 170px;" type="password" id="password" name="password" placeholder="*****"></div>
    
        <div><button name="inscrit" type="submit" style="border-radius: 10px; border: solid black 2px; height: 40px; width: 160px;top: 290px;position: relative; left: 170px; font-family: Cyberpunk is not dead;">INSCRIT</button>
        </div>
    </form>
    </div>

</body>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['mail'];
    $mdp = $_POST['password'];
    $mdp = sha1($mdp); // Cryptage du mot de passe en utilisant SHA1

    $sql = "INSERT INTO utilisateur (pseudonyme, email, mot_de_passe) VALUES ('$username', '$email', '$mdp')";

    if ($connexion->query($sql) === TRUE) {
        // Récupérer l'ID de l'utilisateur après l'insertion
        $iduser_query = "SELECT id_utilisateur FROM utilisateur WHERE pseudonyme = '$username'";
        $result = $connexion->query($iduser_query);
        $row = $result->fetch_assoc();
        $iduser = $row['id_utilisateur'];
        
        // Redirection vers la page utilisateur avec l'ID de l'utilisateur
        header("Location: PageUtilisateur.php?iduser=$iduser");
        exit;
    } else {
        echo "Erreur: " . $sql . "<br>" . $connexion->error;
    }
}

?>


</html>

