<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['tradeId'])
&& isset($_POST['addTradeItem']))
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
            //On fait une requête pour vérifier tous les objets et équippement qui sont dans l'inventaire du joueur
            $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
            WHERE itemId = inventoryItemId
            AND inventoryCharacterId = ?
            ORDER BY itemName");
            $itemQuery->execute([$characterId]);
            $itemRow = $itemQuery->rowCount();
            
            //S'il existe un ou plusieurs objets on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($itemRow > 0) 
            {
                ?>
        
                <form method="POST" action="addOffer.php">
                    Liste de vos objets/équipements : <select name="tradeItemQuantity" class="form-control">
                            
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($item = $itemQuery->fetch())
                        {
                            //On récupère les informations des objets
                            $tradeItemId = stripslashes($item['itemId']);
                            $tradeItemName = stripslashes($item['itemName']);
                            $tradeItemQuantity = stripslashes($item['inventoryQuantity']);
                            ?>
                            <option value="<?php echo $tradeItemId ?>"><?php echo "$tradeItemName (Quantité: $tradeItemQuantity)"; ?></option>
                            <?php
                        }
                        ?>
                        
                    </select>
                    
                    Quantité:  <input type="number" name="tradeItemQuantity" class="form-control" placeholder="Quantité" value="1" required>
                    <input type="submit" name="addItem" class="btn btn-default form-control" value="Ajouter cet objet/équipement">
                </form>
                
                <?php
            }
            else
            {
                echo "Vous n'avez actuellement aucun objet pouvant être ajouté à l'échange";
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