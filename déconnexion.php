<?php
    // Démarrez la session
    session_start();

    // Détruire toutes les variables de session
    $SESSION = array();

    // Détruisez la session
    session_destroy();

    // Redirigez l'utilisateur vers la page d'accueil
    header("Location: index.php");
    exit;
?>