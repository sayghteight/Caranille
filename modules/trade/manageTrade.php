<?php require_once("../../html/header.php");
 
//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si l'utilisateur à cliqué sur le bouton gérer l'échange
if (isset($_POST['tradeId'])
&& isset($_POST['manageTrade']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeId'])
    && $_POST['tradeId'] >= 1)
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
            }
            
            //Si la première personne de l'échange est le joueur on cherche à savoir qui est l'autre personnage
            if ($tradeCharacterOneId == $characterId)
            {
                //On fait une requête pour vérifier la liste des conversations dans la base de données
                $characterQuery = $bdd->prepare("SELECT * FROM car_characters
                WHERE characterId = ?");

                $characterQuery->execute([$tradeCharacterTwoId]);
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($character = $characterQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $tradeCharacterId = stripslashes($character['characterId']);
                    $tradeCharacterName = stripslashes($character['characterName']);
                }
                $characterQuery->closeCursor(); 
            }
            //Si la seconde personne de l'échange est le joueur on cherche à savoir qui est l'autre personne
            else
            {
                //On fait une requête pour vérifier la liste des conversations dans la base de données
                $characterQuery = $bdd->prepare("SELECT * FROM car_characters
                WHERE characterId = ?");

                $characterQuery->execute([$tradeCharacterOneId]);
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($character = $characterQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $tradeCharacterId = stripslashes($character['characterId']);
                    $tradeCharacterName = stripslashes($character['characterName']);
                }
                $characterQuery->closeCursor();
            }
            ?>
            
            <p><?php echo $tradeCharacterName ?></p>
            
            <?php
            $tradeItemQuery = $bdd->prepare("SELECT * FROM car_trades_items, car_items
            WHERE tradeItemItemId = itemId
            AND tradeItemCharacterId = ?
            AND tradeItemTradeId = ?");
            $tradeItemQuery->execute([$tradeCharacterId, $tradeId]);
            $tradeItemRow = $tradeItemQuery->rowCount();
    
            //Si plusieurs objets ont été trouvée
            if ($tradeItemRow > 0)
            {
                ?>
            
                <form>
                    Liste des objet de <?php echo $tradeCharacterName ?> : <select name="itemId" class="form-control">
    
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($tradeItem = $tradeItemQuery->fetch())
                        {
                            //on récupère les valeurs de chaque objets qu'on va ensuite mettre dans le menu déroulant
                            $tradeItemId = stripslashes($tradeItem['marketId']);
                            $tradeItemName = stripslashes($tradeItem['itemName']);
                            $tradeItemQuantity = stripslashes($tradeItem['tradeItemItemQuantity']);
                            ?>
                            <option value="<?php echo $tradeItemId ?>"><?php echo "$tradeItemName ($tradeItemQuantity)" ?></option>
                            <?php
                        }
                        ?>
    
                    </select>
                </form>
                
                <?php
            }
            else
            {
               echo "Il n'y a actuellement aucun objet<br />"; 
            }
            $tradeItemQuery->closeCursor();
            
            //On fait une requête pour vérifier si le joueur a mit des pièces d'or dans l'échange
            $tradeGoldQuery = $bdd->prepare("SELECT * FROM car_trades_golds
            WHERE tradeGoldCharacterId = ?
            AND tradeGoldTradeId = ?");
            $tradeGoldQuery->execute([$tradeCharacterId, $tradeId]);
            $tradeGoldRow = $tradeGoldQuery->rowCount();
    
            //Si l'utilisateur a mit des pièces d'or dans l'échange on récupère combien il a mit
            if ($tradeGoldRow == 1)
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($tradeGold = $tradeGoldQuery->fetch())
                {
                    //on récupère les valeurs de chaque magasins qu'on va ensuite mettre dans le menu déroulant
                    $tradeGoldQuantity = stripslashes($tradeGold['tradeGoldQuantity']);
                }
            }
            ?>
            
            Pièces d'or: <?php echo $tradeGoldQuantity ?>
            
            <hr>
            
            <p><?php echo $characterName ?></p>
            
            <?php
            $tradeItemQuery = $bdd->prepare("SELECT * FROM car_trades_items, car_items
            WHERE tradeItemItemId = itemId
            AND tradeItemCharacterId = ?
            AND tradeItemTradeId = ?");
            $tradeItemQuery->execute([$characterId, $tradeId]);
            $tradeItemRow = $tradeItemQuery->rowCount();
    
            //Si plusieurs objets ont été trouvée
            if ($tradeItemRow > 0)
            {
                ?>
            
                <form>
                    Liste des objet de <?php echo $characterName ?> : <select name="itemId" class="form-control">
    
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($tradeItem = $tradeItemQuery->fetch())
                        {
                            //on récupère les valeurs de chaque objets qu'on va ensuite mettre dans le menu déroulant
                            $tradeItemId = stripslashes($tradeItem['marketId']);
                            $tradeItemName = stripslashes($tradeItem['itemName']);
                            $tradeItemQuantity = stripslashes($tradeItem['tradeItemItemQuantity']);
                            ?>
                            <option value="<?php echo $tradeItemId ?>"><?php echo "$tradeItemName ($tradeItemQuantity)" ?></option>
                            <?php
                        }
                        ?>
    
                    </select>
                    <input type="submit" name="enter" class="btn btn-default form-control" value="Retirer l'objet">
                </form>
                
                <?php
            }
            else
            {
               echo "Il n'y a actuellement aucun objet<br />"; 
            }
            $tradeItemQuery->closeCursor();
            
            //On fait une requête pour vérifier si le joueur a mit des pièces d'or dans l'échange
            $tradeGoldQuery = $bdd->prepare("SELECT * FROM car_trades_golds
            WHERE tradeGoldCharacterId = ?
            AND tradeGoldTradeId = ?");
            $tradeGoldQuery->execute([$characterId, $tradeId]);
            $tradeGoldRow = $tradeGoldQuery->rowCount();
    
            //Si l'utilisateur a mit des pièces d'or dans l'échange on récupère combien il a mit
            if ($tradeItemRow == 1)
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($tradeGold = $tradeGoldQuery->fetch())
                {
                    //on récupère les valeurs de chaque magasins qu'on va ensuite mettre dans le menu déroulant
                    $tradeGoldQuantity = stripslashes($tradeGold['tradeGoldQuantity']);
                }
            }
            ?>
            
            Pièces d'or: <?php echo $tradeGoldQuantity ?>
            
            <hr>
            
            <form method="POST" action="addTradeItem.php">
                <input type="hidden" name="tradeId" value="<?php echo $tradeId ?>">
                <input type="submit" class="btn btn-default form-control" name="addTradeItem" value="Ajouter un objet">
            </form>
            
            <form method="POST" action="addGold.php">
                <input type="submit" class="btn btn-default form-control" name="acceptTrade" value="Modifier le nombre de pièces d'or">
            </form>
            
            <hr>
            
            <form method="POST" action="acceptTrade.php">
                <input type="submit" class="btn btn-default form-control" name="acceptTrade" value="Accepter l'échange">
            </form>
            
            <form method="POST" action="declineTrade.php">
                <input type="submit" class="btn btn-default form-control" name="declineTrade" value="Refuser l'échange">
            </form>
            
            <hr>
            
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
            </form>
            
            <?php
        }
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}
?>

<?php require_once("../../html/footer.php"); ?>