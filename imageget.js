function loadGameImage(gameId) {
    // Envoyer une requête AJAX pour récupérer le nom de l'image du jeu
    $.ajax({
        url: 'get_game_image.php',
        type: 'POST',
        data: { gameId: gameId },
        dataType: 'json',
        success: function(response) {
            // Mettre à jour l'élément d'image avec le chemin de l'image renvoyé par le script PHP
            $('#gameImage').attr('src', 'Image_game/' + response.imageName); // Assurez-vous que le chemin d'accès est correct
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}
