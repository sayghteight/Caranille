<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['tradeId'])
&& isset($_POST['declineTrade']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeId'])
    && $_POST['tradeId'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $tradeId = htmlspecialchars(addslashes($_POST['tradeId']));
        
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

            //Si la première personne de l'échange est le joueur
            if ($tradeCharacterOneId == $characterId)
            {
                //On met à jour l'échange dans la base de donnée
                $updateTrade = $bdd->prepare("UPDATE car_trades
                SET tradeCharacterOneTradeAccepted = 'Yes'
                WHERE tradeId = :tradeId");
                $updateTrade->execute([
                'tradeId' => $tradeId]);
                $updateTrade->closeCursor();
                
                //On fait une requête pour récupérer la liste des objets que le joueur à proposé
                $tradeItemQuery = $bdd->prepare("SELECT * FROM car_trades_items
                WHERE tradeItemCharacterId = ?
                AND tradeItemTradeId = ?");
                $tradeItemQuery->execute([$tradeCharacterOneId, $tradeId]);
                $tradeRow = $tradeItemQuery->rowCount();
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($tradeItem = $tradeItemQuery->fetch())
                {
                    $tradeItemId = stripslashes($tradeItem['tradeItemItemId']);
                    $tradeItemQuantity = stripslashes($tradeItem['tradeItemItemQuantity']);
                    
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
                    
                    echo "Vous avez reçu $itemName<br />";
                }
                
                //On fait une requête pour récupérer le montant de l'argent que l'autre joueur à proposé
                $tradeGoldQuery = $bdd->prepare("SELECT * FROM car_trades_golds
                WHERE tradeGoldCharacterId = ?
                AND tradeGoldTradeId = ?");
                $tradeGoldQuery->execute([$tradeCharacterOneId, $tradeId]);
                $tradeGoldRow = $tradeGoldQuery->rowCount();
                
                if ($tradeGoldRow == 1)
                {
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($tradeGold = $tradeGoldQuery->fetch())
                    {
                        $tradeGoldQuantity = stripslashes($tradeGold['tradeGoldQuantity']);
                    }
                    
                    //On ajoute l'argent au joueur
                     $updateCharacter = $bdd->prepare("UPDATE car_characters SET
                    characterGold = characterGold + :tradeGoldQuantity
                    WHERE characterId = :tradeCharacterOneId");
                    $updateCharacter->execute(array(
                    'tradeGoldQuantity' => $tradeGoldQuantity,  
                    'tradeCharacterOneId' => $tradeCharacterOneId));
                    $updateCharacter->closeCursor();
                    
                    echo "Vous avez reçu $tradeGoldQuantity PO";
                }
            }
            //Si la seconde personne de l'échange est le joueur
            else
            {
                //On met à jour le chapitre dans la base de donnée
                $updateTrade = $bdd->prepare("UPDATE car_trades
                SET tradeCharacterTwoTradeAccepted = 'Yes'
                WHERE tradeId = :tradeId");
                $updateTrade->execute([
                'tradeId' => $tradeId]);
                $updateTrade->closeCursor();
                
                //On fait une requête pour récupérer la liste des objets que l'autre joueur à proposé
                $tradeItemQuery = $bdd->prepare("SELECT * FROM car_trades_items
                WHERE tradeItemCharacterId = ?
                AND tradeItemTradeId = ?");
                $tradeItemQuery->execute([$tradeCharacterTwoId, $tradeId]);
                $tradeRow = $tradeItemQuery->rowCount();
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($tradeItem = $tradeItemQuery->fetch())
                {
                    $tradeItemId = stripslashes($tradeItem['tradeItemItemId']);
                    $tradeItemQuantity = stripslashes($tradeItem['tradeItemItemQuantity']);
                    
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
                        echo "$tradeItemQuantity";
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
                    
                    echo "Vous avez reçu $itemName<br />";
                }
                
                //On fait une requête pour récupérer le montant de l'argent que l'autre joueur à proposé
                $tradeGoldQuery = $bdd->prepare("SELECT * FROM car_trades_golds
                WHERE tradeGoldCharacterId = ?
                AND tradeGoldTradeId = ?");
                $tradeGoldQuery->execute([$tradeCharacterTwoId, $tradeId]);
                $tradeGoldRow = $tradeGoldQuery->rowCount();
                
                if ($tradeGoldRow == 1)
                {
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($tradeGold = $tradeGoldQuery->fetch())
                    {
                        $tradeGoldQuantity = stripslashes($tradeGold['tradeGoldQuantity']);
                    }
                    
                    //On ajoute l'argent au joueur
                     $updateCharacter = $bdd->prepare("UPDATE car_characters SET
                    characterGold = characterGold + :tradeGoldQuantity
                    WHERE characterId = :tradeCharacterTwoId");
                    $updateCharacter->execute(array(
                    'tradeGoldQuantity' => $tradeGoldQuantity,  
                    'tradeCharacterTwoId' => $tradeCharacterTwoId));
                    $updateCharacter->closeCursor();
                    
                    echo "Vous avez reçu $tradeGoldQuantity PO<br />";
                }
            }
            
            //On supprime l'échange
            $deleteTrade = $bdd->prepare("DELETE FROM car_trades 
            WHERE tradeId = :tradeId");
            $deleteTrade->execute(array('tradeId' => $tradeId));
            $deleteTrade->closeCursor();
            
            $deleteTradeItems = $bdd->prepare("DELETE FROM car_trades_items
            WHERE tradeItemTradeId = :tradeId");
            $deleteTradeItems->execute(array('tradeId' => $tradeId));
            $deleteTradeItems->closeCursor();
            
            $deleteTradeGolds = $bdd->prepare("DELETE FROM car_trades_golds
            WHERE tradeGoldTradeId = :tradeId");
            $deleteTradeGolds->execute(array('tradeId' => $tradeId));
            $deleteTradeGolds->closeCursor();
            ?>
            
            <hr>
            
            L'échange a été annulé, vous avez récupéré votre argent ainsi que vos objets
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
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