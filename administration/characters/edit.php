<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton edit
if (isset($_POST['edit']))
{
    $adminCharacterId = htmlspecialchars(addslashes($_POST['adminCharacterId']));

    //On fait une recherche dans la base de donnée de tous les comptes
    $characterListQuery = $bdd->prepare("SELECT * FROM car_characters
    WHERE characterId = ?");
    $characterListQuery->execute([$adminCharacterId]);
    while ($accountList = $accountListQuery->fetch())
    {
        $adminCharacterId = stripslashes($accountList['characterId']);
    }
    $characterListQuery->closeCursor();
    ?>
    <form method="POST" action="finalEdit.php">
        </select><br /><br />
        <input type="hidden" name="adminCharacterId" value="<?= $adminCharacterId ?>">
        <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
    </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");