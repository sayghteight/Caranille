<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à choisit d'ajouter une news
if (isset($_POST['add']))
{
    ?>
    <p>Informations de la news</p>
    <form method="POST" action="addItemEnd.php">
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
//Si l'utilisateur n'a pas cliqué sur le bouton add
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");