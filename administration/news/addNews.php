<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['add']))
{
    ?>
    <p>Informations de la news</p>
    <form method="POST" action="addNewsEnd.php">
        Image : <br> <input type="text" name="adminNewsPicture" class="form-control" placeholder="Image" required><br /><br />
        Titre : <br> <input type="text" name="adminNewsTitle" class="form-control" placeholder="Titre" required><br /><br />
        Message : <br> <textarea class="form-control" name="adminNewsMessage" id="adminNewsMessage" rows="3" required></textarea><br /><br />
        <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
    </form>
    
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");