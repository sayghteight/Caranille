<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalDelete
if (isset($_POST['finalDelete']))
{
    $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));

    //On supprime l'équippement de la base de donnée
    $itemDeleteQuery = $bdd->prepare("DELETE FROM car_items
    WHERE itemId = ?");
    $itemDeleteQuery->execute([$adminItemId]);
    $itemDeleteQuery->closeCursor();

    //On supprime aussi l'équippement de l'inventaire dans la base de donnée
    $inventoryDeleteQuery = $bdd->prepare("DELETE FROM car_inventory
    WHERE inventoryItemId = ?");
    $inventoryDeleteQuery->execute([$adminItemId]);
    $inventoryDeleteQuery->closeCursor();
    ?>

    L'objet a bien été supprimé

    <hr>
        
    <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton finalDelete
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");