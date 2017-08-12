<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['tradeId'])
&& isset($_POST['tradeItemId'])
&& isset($_POST['removeItem']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeId'])
    && ctype_digit($_POST['tradeItemId'])
    && $_POST['tradeId'] >= 0
    && $_POST['tradeItemId'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $tradeId = htmlspecialchars(addslashes($_POST['tradeId']));
        $tradeItemId = htmlspecialchars(addslashes($_POST['tradeItemId']));
        
        //On fait une requête pour vérifier si cette demande existe et est bien attribué au joueur
        $tradeQuery = $bdd->prepare("SELECT * FROM car_trades
        WHERE (tradeCharacterOneId = ?
        OR tradeCharacterTwoId = ?)
        AND tradeId = ?");
        $tradeQuery->execute([$characterId, $characterId, $tradeId]);
        $tradeRow = $tradeQuery->rowCount();
        
        //Si cette échange existe et est attribuée au joueur
        if ($tradeRow > 0) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($trade = $tradeQuery->fetch())
            {
                //On récupère les valeurs de la demande d'échange
                $tradeId = stripslashes($trade['tradeId']);
                $tradeCharacterOneId = stripslashes($trade['tradeCharacterOneId']);
                $tradeCharacterTwoId = stripslashes($trade['tradeCharacterTwoId']);
                $tradeMessage = stripslashes($trade['tradeMessage']);
                $tradeLastUpdate = stripslashes($trade['tradeLastUpdate']);
                $tradeCharacterOneTradeAccepted = stripslashes($trade['tradeCharacterOneTradeAccepted']);
                $tradeCharacterTwoTradeAccepted = stripslashes($trade['tradeCharacterTwoTradeAccepted']);
            }
            
            //On fait une requête pour vérifier si l'objet ou équippement choisit existe
            $itemQuery = $bdd->prepare('SELECT * FROM car_items 
            WHERE itemId = ?');
            $itemQuery->execute([$tradeItemId]);
            $itemRow = $itemQuery->rowCount();
    
            //Si l'objet ou l'équippement existe
            if ($itemRow == 1) 
            {
                //On fait une requête pour récupérer la liste des objets que le joueur à proposé
                $tradeItemQuery = $bdd->prepare("SELECT * FROM car_trades_items
                WHERE tradeItemTradeId = ?
                AND tradeItemItemId = ?
                AND tradeItemCharacterId = ?");
                $tradeItemQuery->execute([$tradeId, $tradeItemId, $characterId]);
                $tradeItemRow = $tradeItemQuery->rowCount();
                
                if ($tradeItemRow == 1)
                {
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($tradeItem = $tradeItemQuery->fetch())
                    {
                        $tradeItemId = stripslashes($tradeItem['tradeItemItemId']);
                        $tradeItemQuantity = stripslashes($tradeItem['tradeItemItemQuantity']);
                    }
                    
                    //On cherche à savoir si l'objet que le joueur va recevoir appartient déjà au joueur
                    $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
                    WHERE itemId = inventoryItemId
                    AND inventoryCharacterId = ?
                    AND itemId = ?");
                    $itemQuery->execute([$characterId, $tradeItemId]);
                    $itemRow = $itemQuery->rowCount();
    
                    //Si le personne possède cet objet
                    if ($itemRow == 1) 
                    {
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($item = $itemQuery->fetch())
                        {
                            //On récupère les informations de l'inventaire
                            $itemId = stripslashes($item['itemId']);
                            $itemName = stripslashes($item['itemName']);
                            $inventoryId = stripslashes($item['inventoryId']);
                            $itemQuantity = stripslashes($item['inventoryQuantity']);
                            $inventoryEquipped = stripslashes($item['inventoryEquipped']);
                        }
                        $itemQuery->closeCursor();
    
                        //On met l'inventaire à jour
                        $updateInventory = $bdd->prepare("UPDATE car_inventory SET
                        inventoryQuantity = inventoryQuantity + :tradeItemQuantity
                        WHERE inventoryId = :inventoryId");
                        $updateInventory->execute(array(
                        'tradeItemQuantity' => $tradeItemQuantity,
                        'inventoryId' => $inventoryId));
                        $updateInventory->closeCursor();
                    }
                    else
                    {
                        $addItem = $bdd->prepare("INSERT INTO car_inventory VALUES(
                        '',
                        :characterId,
                        :tradeItemId,
                        :tradeItemQuantity,
                        '0')");
                        $addItem->execute([
                        'characterId' => $characterId,
                        'tradeItemId' => $tradeItemId,
                        'tradeItemQuantity' => $tradeItemQuantity]);
                        $addItem->closeCursor();
                    }
                    
                    //On supprime l'objet de l'échange en cours
                    $deleteTradeItems = $bdd->prepare("DELETE FROM car_trades_items
                    WHERE tradeItemItemId = :tradeItemId
                    AND tradeItemTradeId = :tradeItemTradeId");
                    $deleteTradeItems->execute(array(
                    'tradeItemId' => $tradeItemId,
                    'tradeItemTradeId' => $tradeId));
                    $deleteTradeItems->closeCursor();
                    
                    //On met l'échange à jour
                    $updateTrade = $bdd->prepare("UPDATE car_trades SET
                    tradeCharacterOneTradeAccepted = 'No',
                    tradeCharacterTwoTradeAccepted = 'No'
                    WHERE tradeId = :tradeId");
                    $updateTrade->execute(array(
                    'tradeId' => $tradeId));
                    $updateTrade->closeCursor(); 
                    ?>
                    
                    L'objet a bien été retiré et remit dans votre inventaire
                    
                    <hr>
                    
                    <form method="POST" action="index.php">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
                    </form>
                    
                    <?php
                }
                else
                {
                    echo "Erreur: Cette objet n'est plus dans l'échange";
                }
                
               
            }
            //Si l'objet ou équippement n'existe pas
            else
            {
                echo "Erreur: Cette objet ou équippement n'existe pas";
            }
            $itemQuery->closeCursor();
        }
        //Si la demande d'échange n'existe pas ou n'est pas attribué au joueur
        else
        {
            echo "Erreur: Cette demande d'échange n'existe pas où ne vous est pas attribuée";
        }
        $tradeQuery->closeCursor(); 
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>