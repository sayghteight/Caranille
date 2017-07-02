<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemId'])
    && $_POST['adminItemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));

        //On fait une requête pour vérifier si l'objet choisi existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId= ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'objet existe
        if ($itemRow == 1) 
        {
            //On supprime l'objet de la base de donnée
            $itemDeleteQuery = $bdd->prepare("DELETE FROM car_items
            WHERE itemId = ?");
            $itemDeleteQuery->execute([$adminItemId]);
            $itemDeleteQuery->closeCursor();

            //On supprime aussi l'objet de l'inventaire dans la base de donnée
            $inventoryDeleteQuery = $bdd->prepare("DELETE FROM car_inventory
            WHERE inventoryItemId = ?");
            $inventoryDeleteQuery->execute([$adminItemId]);
            $inventoryDeleteQuery->closeCursor();
            
            //On supprime les objets et équippements qui sont lié à un monstre
            $itemDropDeleteQuery = $bdd->prepare("DELETE FROM car_monsters_drops
            WHERE monsterDropItemID = ?");
            $itemDropDeleteQuery->execute([$adminItemId]);
            $itemDropDeleteQuery->closeCursor();
            ?>

            L'objet a bien été supprimé

            <hr>
                
            <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
            <?php
        }
        //Si l'objet n'exite pas
        else
        {
            echo "Erreur: Objet indisponible";
        }
        $itemQuery->closeCursor();
    }
    //Si l'objet choisi n'est pas un nombre
    else
    {
        echo "Erreur: Objet invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");