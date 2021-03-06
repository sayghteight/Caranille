<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['itemId'])
&& isset($_POST['viewItem']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if(ctype_digit($_POST['itemId'])
    && $_POST['itemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));
                
        //On fait une requête pour vérifier si l'objet choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId = ?');
        $itemQuery->execute([$itemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'objet existe
        if ($itemRow == 1) 
        {
            //On fait une requête pour avoir la liste des objets du personnage
            $itemInventoryQuery = $bdd->prepare("SELECT * FROM  car_items, car_items_types, car_inventory 
            WHERE itemItemTypeId = itemTypeId
            AND itemId = inventoryItemId
            AND itemTypeName = 'Item' 
            AND inventoryCharacterId = ?
            AND inventoryItemId = ?");
            $itemInventoryQuery->execute([$characterId, $itemId]);
            $itemInventoryRow = $itemInventoryQuery->rowCount();
            
            //Si le personnage possède cet objet
            if ($itemInventoryRow == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($itemInventory = $itemInventoryQuery->fetch())
                {
                    //On récupère les informations de l'objet
                    $itemId = stripslashes($itemInventory['itemId']);
                    $itemTypeName = stripslashes($equipmentInventory['itemTypeName']);
                    $itemTypeNameShow = stripslashes($equipmentInventory['itemTypeNameShow']);
                    $itemPicture = stripslashes($itemInventory['itemPicture']);
                    $itemName = stripslashes($itemInventory['itemName']);
                    $itemDescription = stripslashes($itemInventory['itemDescription']);
                    $itemQuantity = stripslashes($itemInventory['inventoryQuantity']);
                    $itemHpEffect = stripslashes($itemInventory['itemHpEffect']);
                    $itemMpEffect = stripslashes($itemInventory['itemMpEffect']);
                    $itemStrengthEffect = stripslashes($itemInventory['itemStrengthEffect']);
                    $itemMagicEffect = stripslashes($itemInventory['itemMagicEffect']);
                    $itemAgilityEffect = stripslashes($itemInventory['itemAgilityEffect']);
                    $itemDefenseEffect = stripslashes($itemInventory['itemDefenseEffect']);
                    $itemDefenseMagicEffect = stripslashes($itemInventory['itemDefenseMagicEffect']);
                    $itemWisdomEffect = stripslashes($itemInventory['itemWisdomEffect']);
                    $itemSalePrice = stripslashes($itemInventory['itemSalePrice']);
                    ?>

                    <p><img src="<?php echo $itemPicture ?>" height="100" width="100"></p>
                    
                    <table class="table">
                        
                        <tr>
                            <td>
                                Type
                            </td>
                            
                            <td>
                                <?php echo $itemTypeNameShow; ?>
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
                                <?php
                                //Si l'objet augmente les HP on l'affiche
                                if ($itemHpEffect > 0)
                                {
                                    echo "+ $itemHpEffect HP<br />";
                                }
                                
                                //Si l'objet augmente les MP on l'affiche
                                if ($itemMpEffect > 0)
                                {
                                    echo "+ $itemMpEffect MP<br />";
                                }
                                ?>
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
                                <form method="POST" action="useItem.php">
                                    <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                    <input type="submit" class="btn btn-default form-control" name="use" value="Utiliser">
                                </form>
                                <form method="POST" action="saleItem.php">
                                    <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                    <input type="submit" class="btn btn-default form-control" name="sale" value="Vendre">
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
            }
            //Si le joueur ne possède pas cet objet
            else
            {
                echo "Erreur: Impossible de visualiser un objet que vous ne possédez pas.";
            }
            $itemInventoryQuery->closeCursor();
        }
        //Si l'objet n'exite pas
        else
        {
            echo "Erreur: Cet objet n'existe pas";
        }
        $itemQuery->closeCursor();
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
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>