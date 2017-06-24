<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['itemId'])
&& isset($_POST['viewItem']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if(ctype_digit($_POST['itemId'])
    && $_POST['itemId'] >= 1)
    {
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));
        
        //On fait une requête pour avoir la liste des objets du personnage
        $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
        WHERE itemId = inventoryItemId
        AND itemType = 'Item' 
        AND inventoryCharacterId = ?
        AND itemId = ?");
        $itemQuery->execute([$characterId, $itemId]);

        //On fait une boucle sur les résultats et on vérifie à chaque fois de quel type d'équipement il s'agit
        while ($item = $itemQuery->fetch())
        {
            $itemId = stripslashes($item['itemId']);
            $itemType = stripslashes($item['itemType']);
            $itemLevelRequired = stripslashes($item['itemLevelRequired']);
            $itemName = stripslashes($item['itemName']);
            $itemDescription = stripslashes($item['itemDescription']);
            $itemQuantity = stripslashes($item['inventoryQuantity']);
            $itemHpEffect = stripslashes($item['itemHpEffect']);
            $itemMpEffect = stripslashes($item['itemMpEffect']);
            $itemStrengthEffect = stripslashes($item['itemStrengthEffect']);
            $itemMagicEffect = stripslashes($item['itemMagicEffect']);
            $itemAgilityEffect = stripslashes($item['itemAgilityEffect']);
            $itemDefenseEffect = stripslashes($item['itemDefenseEffect']);
            $itemDefenseMagicEffect = stripslashes($item['itemDefenseMagicEffect']);
            $itemWisdomEffect = stripslashes($item['itemWisdomEffect']);
            $itemSalePrice = stripslashes($item['itemSalePrice']);
            ?>
            <table class="table">
                <tr>
                    <td>
                        Niveau requis
                    </td>
                    
                    <td>
                        <?php echo $itemLevelRequired; ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        Nom
                    </td>
                    
                    <td>
                        <?php echo $itemName; ?>
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        Description
                    </td>
                    
                    <td>
                        <?php echo nl2br($itemDescription); ?>
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        Quantité
                    </td>
                    
                    <td>
                        <?php echo $itemQuantity; ?>
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        Effet(s)
                    </td>
                    
                    <td>
                        <?php echo '+' .$itemHpEffect. ' HP'; ?><br />
                        <?php echo '+' .$itemMpEffect. ' MP'; ?><br />
                        <?php echo '+' .$itemStrengthEffect. ' Force'; ?><br />
                        <?php echo '+' .$itemMagicEffect. ' Magie'; ?><br />
                        <?php echo '+' .$itemAgilityEffect. ' Agilité'; ?><br />
                        <?php echo '+' .$itemDefenseEffect. ' Défense'; ?><br />
                        <?php echo '+' .$itemDefenseMagicEffect. ' Défense magique'; ?><br />
                        <?php echo '+' .$itemWisdomEffect. ' Sagesse'; ?>
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        Prix de vente
                    </td>
                    
                    <td>
                        <?php echo $itemSalePrice; ?>
                    </td>
                </tr>
                    
                <tr>
                    <td>
                        Actions
                    </td>
                    
                    <td>
                        <form method="POST" action="saleItem.php">
                            <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                            <input type="submit" class="btn btn-default form-control" name="sale" value="Vendre"><br /><br />
                        </form>
                    </td>
                </tr>
            </table>
                        
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" value="Retour">
            </form>
            <?php
        }
        $itemQuery->closeCursor();
    }
    //Si l'objet choisit n'est pas un nombre
    else
    {
         echo "L'objet choisit est invalid";
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>