<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton manage
if (isset($_POST['manage']))
{
    //On fait une recherche dans la base de donnée de tous les comptes
    $characterListQuery = $bdd->query("SELECT * FROM car_characters");
    while ($characterList = $characterListQuery->fetch())
    {
        $adminCharacterId = stripslashes($characterList['characterId']); ?>
        <b>Nom du personnage</b>: <?= stripslashes($characterList['characterName']) ?> 
        <form method="POST" action="edit.php">
            <input type="hidden" class="btn btn-default form-control" name="adminCharacterId" value="<?= $adminCharacterId ?>">
            <input type="submit" class="btn btn-default form-control" name="edit" value="Modifier">
        </form>
 
        <hr>
            
        <?php
    }
    $characterListQuery->closeCursor();
}
//Si l'utilisateur n'a pas cliqué sur le bouton manage
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");