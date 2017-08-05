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
        
        //On fait une requête pour vérifier si l'équipement choisit existe
        $equipmentQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId = ?');
        $equipmentQuery->execute([$itemId]);
        $equipmentRow = $equipmentQuery->rowCount();

        //Si l'équipement existe
        if ($equipmentRow == 1) 
        {
            //On fait une requête pour vérifier si le joueur possède bien cet équipement
            $equipmentInventoryQuery = $bdd->prepare("SELECT * FROM  car_items, car_items_types, car_inventory 
            WHERE itemItemTypeId = itemTypeId
            AND itemId = inventoryItemId
            AND (itemTypeName = 'Armor' 
            OR itemTypeName = 'Boots' 
            OR itemTypeName = 'Gloves' 
            OR itemTypeName = 'Helmet' 
            OR itemTypeName = 'Weapon')
            AND inventoryCharacterId = ?
            AND inventoryItemId = ?");
            $equipmentInventoryQuery->execute([$characterId, $itemId]);
            $equipmentInventoryRow = $equipmentInventoryQuery->rowCount();
            
            //Si le personnage possède cet objet
            if ($equipmentInventoryRow == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($equipmentInventory = $equipmentInventoryQuery->fetch())
                {
                    //On récupère les informations de l'équippement
                    $equipmentId = stripslashes($equipmentInventory['itemId']);
                    $equipmentTypeName = stripslashes($equipmentInventory['itemTypeName']);
                    $equipmentTypeNameShow = stripslashes($equipmentInventory['itemTypeNameShow']);
                    $equipmentRaceId = stripslashes($equipmentInventory['itemRaceId']);
                    $equipmentPicture = stripslashes($equipmentInventory['itemPicture']);
                    $equipmentName = stripslashes($equipmentInventory['itemName']);
                    $equipmentDescription = stripslashes($equipmentInventory['itemDescription']);
                    $equipmentLevel = stripslashes($equipmentInventory['itemLevel']);
                    $equipmentLevelRequired = stripslashes($equipmentInventory['itemLevelRequired']);
                    $equipmentQuantity = stripslashes($equipmentInventory['inventoryQuantity']);
                    $equipmentHpEffect = stripslashes($equipmentInventory['itemHpEffect']);
                    $equipmentMpEffect = stripslashes($equipmentInventory['itemMpEffect']);
                    $equipmentStrengthEffect = stripslashes($equipmentInventory['itemStrengthEffect']);
                    $equipmentMagicEffect = stripslashes($equipmentInventory['itemMagicEffect']);
                    $equipmentAgilityEffect = stripslashes($equipmentInventory['itemAgilityEffect']);
                    $equipmentDefenseEffect = stripslashes($equipmentInventory['itemDefenseEffect']);
                    $equipmentDefenseMagicEffect = stripslashes($equipmentInventory['itemDefenseMagicEffect']);
                    $equipmentWisdomEffect = stripslashes($equipmentInventory['itemWisdomEffect']);
                    $equipmentProspectingEffect = stripslashes($equipmentInventory['itemProspectingEffect']);
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
                            //On récupère les informations de la classe
                            $equipmentRaceName = stripslashes($race['raceName']);
                        }
                        $raceQuery->closeCursor();
                    }
                    //Si la race de l'équipement est égal à 0 c'est qu'il est disponible pour toutes les classes
                    else
                    {
                        $equipmentRaceName = "Toutes les classes";
                    }
                    ?>
                    
                    <p><img src="<?php echo $equipmentPicture ?>" height="100" width="100"></p>
                    
                    <table class="table">
                        
                        <tr>
                            <td>
                                Type
                            </td>
                            
                            <td>
                                <?php echo $equipmentTypeNameShow; ?>
                        </td>

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
                                <?php
                                //Si l'équipement augmente les HP on l'affiche
                                if ($equipmentHpEffect > 0)
                                {
                                    echo "+ $equipmentHpEffect HP<br />";
                                }
                                
                                //Si l'équipement augmente les MP on l'affiche
                                if ($equipmentMpEffect > 0)
                                {
                                    echo "+ $equipmentMpEffect MP<br />";
                                }
                                
                                //Si l'équipement augmente la force on l'affiche
                                if ($equipmentStrengthEffect > 0)
                                {
                                    echo "+ $equipmentStrengthEffect Force<br />";
                                }
                                
                                //Si l'équipement augmente la magie on l'affiche
                                if ($equipmentMagicEffect > 0)
                                {
                                    echo "+ $equipmentMagicEffect Magie<br />";
                                }
                                
                                //Si l'équipement augmente l'agilité on l'affiche
                                if ($equipmentAgilityEffect > 0)
                                {
                                    echo "+ $equipmentAgilityEffect Agilité<br />";
                                }
                                
                                //Si l'équipement augmente la défense on l'affiche
                                if ($equipmentDefenseEffect > 0)
                                {
                                    echo "+ $equipmentDefenseEffect Défense<br />";
                                }
                                
                                //Si l'équipement augmente la défense magique on l'affiche
                                if ($equipmentDefenseMagicEffect > 0)
                                {
                                    echo "+ $equipmentDefenseMagicEffect Défense Magic<br />";
                                }
                                
                                //Si l'équipement augmente la sagesse on l'affiche
                                if ($equipmentWisdomEffect > 0)
                                {
                                    echo "+ $equipmentWisdomEffect Sagesse<br />";
                                }
                                
                                //Si l'équipement augmente la prospection on l'affiche
                                if ($equipmentProspectingEffect > 0)
                                {
                                    echo "+ $equipmentProspectingEffect Prospection<br />";
                                }
                                ?>
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
                                            
                                            <form method="POST" action="equip.php">
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
            }
            //Si le joueur ne possède pas cet équipement
            else
            {
                echo "Erreur: Impossible de visualiser un équipement que vous ne possédez pas.";
            }
            $equipmentInventoryQuery->closeCursor();
        }
        //Si l'équipement n'existe pas
        else
        {
            echo "Erreur: Cet équippement n'existe pas";
        }
        $equipmentQuery->closeCursor();
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