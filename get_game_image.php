<?php 
// Affichage des noms des jeux avec les plateformes correspondantes dans des divs
for ($i = 0; $i < count($games); $i++) {
    // Vérification pour s'assurer que l'index existe dans les deux tableaux
    if (isset($games[$i]) && isset($platform[$i]) && isset($idgame[$i])) {
        $encodedGame = urlencode($games[$i]);
        $encodedPlatform = urlencode($platform[$i]);
        $currentIdGame = $idgame[$i]; // ID du jeu actuel
        echo '
        <div class="game" data-idgame="' . $currentIdGame . '" onclick="loadGameImageAndRedirect(' . $currentIdGame . ', \'' . $encodedGame . '\', \'' . $encodedPlatform . '\')">'.$games[$i].' - '.$platform[$i].'</div>
        <button onclick="window.location.href = \'ajout_biblio.php?id_utilisateur='.$iduser.'&idgame='.$currentIdGame.'\'" type="button" name="ajoutbiblio" value="AddBiblio">ajouter à sa bibliothèque</button>';
    }
}
?>
