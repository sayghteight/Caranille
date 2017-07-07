<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['itemId'])
&& isset($_POST['viewEquipment']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if(ctype_digit($_POST['itemId'])
    && $_POST['itemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));
        
        //On fait une requête pour vérifier si l'équipement choisi existe
        $equipmentQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId = ?');
        $equipmentQuery->execute([$itemId]);
        $equipmentRow = $equipmentQuery->rowCount();

        //Si l'équipement existe
        if ($equipmentRow == 1) 
        {
            //On fait une requête pour vérifier si le joueur possède bien cet équipement
            $equipmentInventoryQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
            WHERE itemId = inventoryItemId
            AND (itemType = 'Armor' 
            OR itemType = 'Boots' 
            OR itemType = 'Gloves' 
            OR itemType = 'Helmet' 
            OR itemType = 'Weapon')
            AND inventoryCharacterId = ?
            AND inventoryItemId = ?");
            $equipmentInventoryQuery->execute([$characterId, $itemId]);
            $equipmentInventoryRow = $equipmentInventoryQuery->rowCount();
            
            //Si le personnage possède cet objet
            if ($equipmentInventoryRow == 1) 
            {
                //On fait une boucle sur les résultats et on vérifie à chaque fois de quel type d'équipement il s'agit
                while ($equipmentInventory = $equipmentInventoryQuery->fetch())
                {
                    $equipmentId = stripslashes($equipmentInventory['itemId']);
                    $equipmentRaceId = stripslashes($equipmentInventory['itemRaceId']);
                    $equipmentPicture = stripslashes($equipmentInventory['itemPicture']);
                    $equipmentType = stripslashes($equipmentInventory['itemType']);
                    $equipmentLevel = stripslashes($equipmentInventory['itemLevel']);
                    $equipmentLevelRequired = stripslashes($equipmentInventory['itemLevelRequired']);
                    $equipmentName = stripslashes($equipmentInventory['itemName']);
                    $equipmentDescription = stripslashes($equipmentInventory['itemDescription']);
                    $equipmentQuantity = stripslashes($equipmentInventory['inventoryQuantity']);
                    $equipmentHpEffect = stripslashes($equipmentInventory['itemHpEffect']);
                    $equipmentMpEffect = stripslashes($equipmentInventory['itemMpEffect']);
                    $equipmentStrengthEffect = stripslashes($equipmentInventory['itemStrengthEffect']);
                    $equipmentMagicEffect = stripslashes($equipmentInventory['itemMagicEffect']);
                    $equipmentAgilityEffect = stripslashes($equipmentInventory['itemAgilityEffect']);
                    $equipmentDefenseEffect = stripslashes($equipmentInventory['itemDefenseEffect']);
                    $equipmentDefenseMagicEffect = stripslashes($equipmentInventory['itemDefenseMagicEffect']);
                    $equipmentWisdomEffect = stripslashes($equipmentInventory['itemWisdomEffect']);
                    $equipmentSalePrice = stripslashes($equipmentInventory['itemSalePrice']);
                    $equipmentEquipped = stripslashes($equipmentInventory['inventoryEquipped']);
        
                    //Si la race de l'équipement est supérieur à 1 c'est qu'il est attitré à une classe
                    if ($equipmentRaceId >= 1)
                    {
                        //On récupère la classe de l'équipement
                        $raceQuery = $bdd->prepare("SELECT * FROM car_races
                        WHERE raceId = ?");
                        $raceQuery->execute([$equipmentRaceId]);
                        while ($race = $raceQuery->fetch())
                        {
                            //On récupère le nom de la classe
                            $equipmentRaceName = stripslashes($race['raceName']);
                        }
                        $raceQuery->closeCursor();
                    }
                    else
                    {
                        $equipmentRaceName = "Toutes les classes";
                    }
                    ?>
                    
                    <p><img src="<?php echo $equipmentPicture; ?>" height="100" width="100"></p>
                    
                    <table class="table">
                        <tr>
                            <td>
                                Classe requise
                            </td>
                        
                            <td>
                                <?php echo $equipmentRaceName; ?>
                            </td>
                        </tr>
        
                        <tr>
                            <td>
                                Niveau de l'objet
                            </td>
                            
                            <td>
                                <?php echo $equipmentLevel; ?>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                Niveau requis
                            </td>
                            
                            <td>
                                <?php echo $equipmentLevelRequired; ?>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                Nom
                            </td>
                            
                            <td>
                                <?php echo $equipmentName; ?>
                            </td>
                        </tr>
                            
                        <tr>
                            <td>
                                Description
                            </td>
                            
                            <td>
                                <?php echo nl2br($equipmentDescription); ?>
                            </td>
                        </tr>
                            
                        <tr>
                            <td>
                                Quantité
                            </td>
                            
                            <td>
                                <?php echo $equipmentQuantity; ?>
                            </td>
                        </tr>
                            
                        <tr>
                            <td>
                                Effet(s)
                            </td>
                            
                            <td>
                                <?php echo '+' .$equipmentHpEffect. ' HP'; ?><br />
                                <?php echo '+' .$equipmentMpEffect. ' MP'; ?><br />
                                <?php echo '+' .$equipmentStrengthEffect. ' Force'; ?><br />
                                <?php echo '+' .$equipmentMagicEffect. ' Magie'; ?><br />
                                <?php echo '+' .$equipmentAgilityEffect. ' Agilité'; ?><br />
                                <?php echo '+' .$equipmentDefenseEffect. ' Défense'; ?><br />
                                <?php echo '+' .$equipmentDefenseMagicEffect. ' Défense magique'; ?><br />
                                <?php echo '+' .$equipmentWisdomEffect. ' Sagesse'; ?>
                            </td>
                        </tr>
                            
                        <tr>
                            <td>
                                Prix de vente
                            </td>
                            
                            <td>
                                <?php echo $equipmentSalePrice; ?>
                            </td>
                        </tr>
                            
                        <tr>
                            <td>
                                Actions
                            </td>
                            
                            <td>
                                
                                <?php
                                //Si l'équipement n'est pas équippé ont propose au joueur de l'équiper
                                if ($equipmentEquipped == 0)
                                {
                                    //On vérifie si la classe du joueur lui permet de s'équiper de cet équipement, ou si celui-ci est pour toutes les classes
                                    if ($characterRaceId == $equipmentRaceId || $equipmentRaceId == 0)
                                    {
                                        //Si le niveau du joueur est supérieur ou égal à celui du niveau requis
                                        if ($characterLevel >= $equipmentLevelRequired)
                                        {
                                            ?>
                                            
                                                <form method="POST" action="equipItem.php">
                                                    <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                                    <input type="submit" class="btn btn-default form-control" name="equip" value="Equiper">
                                                </form> 
                                                
                                            <?php
                                        }
                                        //Si le niveau du joueur n'est pas supérieur ou égal à celui du niveau requis
                                        else
                                        {
                                            echo "Votre niveau est trop faible pour vous équiper de cet objet";
                                        }
                                    }
                                    //Si la classe de l'objet est incompatible avec celle du joueur
                                    else
                                    {
                                         echo "Votre classe ne vous permet pas de vous équiper de cet équipement";
                                    }
                                    
                                }
                                //Si l'équipement est équippé on prévient le joueur
                                else
                                {
                                    ?>
                                    
                                        Cet équipement est actuellement équippé
                                        
                                        <form method="POST" action="unEquip.php">
                                            <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                            <input type="submit" class="btn btn-default form-control" name="unEquip" value="Déséquiper">
                                        </form>
                                        
                                    <?php
                                }
                                ?>
                                
                                <form method="POST" action="saleItem.php">
                                    <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                    <input type="submit" class="btn btn-default form-control" name="sale" value="Vendre">
                                </form>
                            </td>
                        </tr>
                    </table>
                    
                    <hr>
        
                    <form method="POST" action="equipment.php">
                        <input type="submit" class="btn btn-default form-control" value="Retour">
                    </form>
                    
                    <?php
                }
                $equipmentQuery->closeCursor();
            }
            //Si le joueur ne possède pas cet équipement
            else
            {
                echo "Erreur: Impossible de visualiser un équipement que vous ne possédez pas.";
            }
        }
        //Si l'équipement n'existe pas
        else
        {
            echo "Erreur: Equippement indisponible";
        }
        $equipmentInventoryRow->closeCursor();
    }
    //Si l'équipement choisi n'est pas un nombre
    else
    {
         echo "L'équipment choisi est invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>