<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['itemId'])
&& isset($_POST['useFinal']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['itemId'])
    && $_POST['itemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));

        //On cherche à savoir si l'objet qui va se vendre appartient bien au joueur
        $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
        WHERE itemId = inventoryItemId
        AND inventoryCharacterId = ?
        AND itemId = ?");
        $itemQuery->execute([$characterId, $itemId]);
        $itemRow = $itemQuery->rowCount();

        //Si le personne possède cet objet
        if ($itemRow == 1) 
        {
            //On récupère les informations de l'objet
            while ($item = $itemQuery->fetch())
            {
                $inventoryId = stripslashes($item['inventoryId']);
                $itemQuantity = stripslashes($item['inventoryQuantity']);
                $itemName = stripslashes($item['itemName']);
                $itemHpEffect = stripslashes($item['itemHpEffect']);
                $itemMpEffect = stripslashes($item['itemMpEffect']);
            }
            $itemQuery->closeCursor();
            
            //On applique les ajouts de l'objet sur les stats du joueur
            $characterHpMin = $characterHpMin + $itemHpEffect;
            $characterMpMin = $characterMpMin + $itemMpEffect;
            
            //Si les HP Minimum sont supérieur au HP Maximum
            if ($characterHpMin > $characterHpMax)
            {
                //Si c'est le cas $characterHpMin = $characterHpMax
                $characterHpMin = $characterHpMax;
            }
            
            //Si les MP Minimum sont supérieur au MP Maximum
            if ($characterMpMin > $characterMpMax)
            {
                //Si c'est le cas $characterMpMin = $characterMpMax
                $characterMpMin = $characterMpMax;
            }

            //Si le joueur possède plusieurs exemplaire de cet objet/équipement
            if ($itemQuantity > 1)
            {
                //On met l'inventaire à jour
                $updateInventory = $bdd->prepare("UPDATE car_inventory SET
                inventoryQuantity = inventoryQuantity - 1
                WHERE inventoryId = :inventoryId");
                $updateInventory->execute(array(
                'inventoryId' => $inventoryId));
                $updateInventory->closeCursor();
            }
            //Si le joueur ne possède cet objet/équipement que en un seul exemplaire
            else
            {
                //On supprime l'objet de l'inventaire
                $updateInventory = $bdd->prepare("DELETE FROM car_inventory
                WHERE inventoryId = :inventoryId");
                $updateInventory->execute(array(
                'inventoryId' => $inventoryId));
                $updateInventory->closeCursor();
            }

            //On met le personnage à jour
            $updatecharacter = $bdd->prepare("UPDATE car_characters SET
            characterHpMin = :characterHpMin,
            characterMpMin = :characterMpMin
            WHERE characterId = :characterId");

            $updatecharacter->execute(array(
            'characterHpMin' => $characterHpMin,
            'characterMpMin' => $characterMpMin,  
            'characterId' => $characterId));
            $updatecharacter->closeCursor();
            ?>
            Vous venez d'utiliser l'objet <?php echo $itemName ?>.

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" value="Retour">
            </form>
            <?php
        }
        else
        {
            echo "Erreur: Impossible d'utiliser un objet que vous ne possédez pas.";
        }
    }
    //Si l'objet choisi n'est pas un nombre
    else
    {
         echo "L'objet choisi est invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>