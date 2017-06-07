<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }
//Si tous les champs ont bien été rempli

if (isset($_POST['viewItem']))
{
    //On vérifie si la classe choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['itemId']))
    {
        ?>
        Information complète de l'objet

        <hr>

        <?php
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));
        //On fait une requête pour avoir la liste des objets du personnage
        /*
        SELECT * FROM car_items, car_inventory //On fait une liaison entre la table car_items et car_inventory
        WHERE itemId = inventoryItemItemId //On lie ses deux tables par l'Id de l'objet
        AND itemType = 'Item' //Il faut que le type de l'objet soit un objet (Item)
        AND inventoryItemCharacterId = ? //Ou le proprietaire et le personnage du joueur
        ORDER BY itemType //Par ordre de type
        */
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
            $itemName = stripslashes($item['itemName']);
            $itemDescription = stripslashes($item['itemDescription']);
            $itemLevelRequired = stripslashes($item['itemLevelRequired']);
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
                        Niveau requis
                    </td>
                    
                    <td>
                        <?php echo $itemLevelRequired; ?>
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
                        <form method="POST" action="sale.php">
                            <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                            <input type="submit" class="btn btn-default form-control" name="sale" value="Vendre"><br /><br />
                        </form>
                    </td>
                </tr>
            </table>
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
//Si tous les champs n'ont pas été rempli
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>