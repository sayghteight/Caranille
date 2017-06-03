<?php 
require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['sale']))
{
    //On vérifie si la classe choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['itemId']))
    {
        //On récupère l'Id de l'objet ou équipement
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
                $itemSalePrice = stripslashes($item['itemSalePrice']);
            }
            $itemQuery->closeCursor();

            //Si le joueur possède plusieur exemplaire de cet objet/équipement
            if ($itemQuantity > 1)
            {
                //On met l'inventaire à jour
                $updateInventory = $bdd->prepare("UPDATE car_inventory SET
                inventoryQuantity = inventoryQuantity - 1
                WHERE inventoryId= :inventoryId");
                $updateInventory->execute(array(
                'inventoryId' => $inventoryId));
                $updateInventory->closeCursor();
            }
            //Si le joueur ne possède cet objet/équipement que en un seul exemplaire
            else
            {
                //On supprime l'objet de l'inventaire
                $updateInventory = $bdd->prepare("DELETE FROM car_inventory
                WHERE inventoryId= :inventoryId");
                $updateInventory->execute(array(
                'inventoryId' => $inventoryId));
                $updateInventory->closeCursor();
            }

            //On donne l'argent de la vente au personnage
            $updatecharacter = $bdd->prepare("UPDATE car_characters SET
            characterGold = characterGold + :itemSalePrice
            WHERE characterId= :characterId");

            $updatecharacter->execute(array(
            'itemSalePrice' => $itemSalePrice,  
            'characterId' => $characterId));
            $updatecharacter->closeCursor();
            ?>
            Vous venez de vendre l'objet <?php echo $itemName ?> pour <?php echo $itemSalePrice ?> Pièce(s) d'or

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" value="Retour">
            </form>
            <?php
        }
        else
        {
            echo "Erreur: Impossible de vendre un objet/équipement que vous ne possédez pas.";
        }
    }
    //Si l'objet choisit n'est pas un nombre
    else
    {
         echo "L'équipment choisit est invalid";
    }
}
//Si tous les champs n'ont pas été rempli
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>