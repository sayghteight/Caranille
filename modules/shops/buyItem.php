<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['shopId'])
&& isset($_POST['itemId'])
&& isset($_POST['buy']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['shopId'])
    && ctype_digit($_POST['itemId'])
    && $_POST['shopId'] >= 1
    && $_POST['itemId'] >= 1)
    {
        //On récupère l'Id de l'objet ou équipement
        $shopId = htmlspecialchars(addslashes($_POST['shopId']));
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));

        //On fait une requête pour vérifier si le magasin choisit existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId= ?');
        $shopQuery->execute([$shopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin est disponible
        if ($shopRow == 1) 
        {
            //On fait une requête pour vérifier si l'objet choisit existe
            $itemQuery = $bdd->prepare('SELECT * FROM car_items 
            WHERE itemId= ?');
            $itemQuery->execute([$itemId]);
            $itemRow = $itemQuery->rowCount();

            //Si l'objet est disponible
            if ($itemRow == 1) 
            {
                //On récupère les informations de l'objet
                while ($item = $itemQuery->fetch())
                {
                    $itemName = stripslashes($item['itemName']);
                    $itemSalePrice = stripslashes($item['itemPurchasePrice']);
                }
                
                //On fait une requête pour récupérer les informations de l'objet du magasin
                $shopItemQuery = $bdd->prepare('SELECT * FROM car_shops_items
                WHERE shopItemShopId = ?
                AND shopItemItemId = ?');
                $shopItemQuery->execute([$shopId, $itemId]);
                $shopItemRow = $shopItemQuery->rowCount();

                //On récupère le taux de réduction de l'objet/équipement
                while ($shopItem = $shopItemQuery->fetch())
                {
                    $itemDiscount = stripslashes($shopItem['shopItemDiscount']);
                }

                $discount = $itemSalePrice * $itemDiscount / 100;
                $itemSalePrice = $itemSalePrice - $discount;

                //Si le joueur à suffisament d'argent
                if ($characterGold >= $itemSalePrice)
                {
                    //On cherche à savoir si l'objet que le joueur va acheter appartient déjà au joueur
                    $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
                    WHERE itemId = inventoryItemId
                    AND inventoryCharacterId = ?
                    AND itemId = ?");
                    $itemQuery->execute([$characterId, $itemId]);
                    $itemRow = $itemQuery->rowCount();

                    //Si le personne possède cet objet
                    if ($itemRow == 1) 
                    {
                        //On récupère les informations de l'inventaire
                        while ($item = $itemQuery->fetch())
                        {
                            $inventoryId = stripslashes($item['inventoryId']);
                            $itemQuantity = stripslashes($item['inventoryQuantity']);
                            $inventoryEquipped = stripslashes($item['inventoryEquipped']);
                        }
                        $itemQuery->closeCursor();

                        //On met l'inventaire à jour
                        $updateInventory = $bdd->prepare("UPDATE car_inventory SET
                        inventoryQuantity = inventoryQuantity + 1
                        WHERE inventoryId= :inventoryId");
                        $updateInventory->execute(array(
                        'inventoryId' => $inventoryId));
                        $updateInventory->closeCursor();

                        //On retire l'argent de la vente au personnage
                        $updatecharacter = $bdd->prepare("UPDATE car_characters SET
                        characterGold = characterGold - :itemSalePrice
                        WHERE characterId= :characterId");

                        $updatecharacter->execute(array(
                        'itemSalePrice' => $itemSalePrice,  
                        'characterId' => $characterId));
                        $updatecharacter->closeCursor();
                    }
                    //Si le joueur ne possède pas encore cet objet/équipement
                    else
                    {
                        $addItem = $bdd->prepare("INSERT INTO car_inventory VALUES(
                        '',
                        :characterId,
                        :itemId,
                        '1',
                        '0')");
                        $addItem->execute([
                        'characterId' => $characterId,
                        'itemId' => $itemId]);
                        $addItem->closeCursor();
                    }
                    ?>
                    Vous venez d'acheter l'objet <?php echo $itemName ?> pour <?php echo $itemSalePrice ?> Pièce(s) d'or

                    <hr>

                    <form method="POST" action="index.php">
                        <input type="submit" class="btn btn-default form-control" value="Retour">
                    </form>
                    <?php
                }
                else
                {
                    ?>
                    Vous n'avez pas assez d'argent

                    <hr>

                    <form method="POST" action="index.php">
                        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                    </form>
                    <?php
                }
            }
            //Si l'objet n'est pas disponible
            else
            {
                echo "Erreur: Objet indisponible";
            }
            $itemQuery->closeCursor();
        }
        //Si le magasin n'est pas disponible
        else
        {
            echo "Erreur: Magasin indisponible";
        }
        $shopQuery->closeCursor();
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