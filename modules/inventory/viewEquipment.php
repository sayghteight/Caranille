<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['itemId'])
&& isset($_POST['viewEquipment']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if(ctype_digit($_POST['itemId'])
    && $_POST['itemId'] >= 1)
    {
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));
        //On fait une requête pour avoir la liste des équipements du personnage
        /*
        SELECT * FROM car_items, car_inventory //On fait une liaison entre la table car_items et car_inventory
        WHERE itemId = inventoryItemItemId //On lie ses deux tables par l'id de l'objet
        AND (itemType = 'Armor' //Il faut que le type de l'objet soit soit une armure (Armor)
        OR itemType = 'Boots' //Soit des bottes (Boots)
        OR itemType = 'Gloves' //Soit des gants (Gloves)
        OR itemType = 'Helmet' //Soit un casque (Helmet)
        OR itemType = 'Weapon') //Ou soit une arme (Weapon)
        AND inventoryItemCharacterId = ? //Ou le proprietaire et le personnage du joueur
        ORDER BY itemType //Par ordre de type
        */
        $equipmentQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
        WHERE itemId = inventoryItemId
        AND (itemType = 'Armor' 
        OR itemType = 'Boots' 
        OR itemType = 'Gloves' 
        OR itemType = 'Helmet' 
        OR itemType = 'Weapon')
        AND inventoryCharacterId = ?
        AND itemId = ?");
        $equipmentQuery->execute([$characterId, $itemId]);

        //On fait une boucle sur les résultats et on vérifie à chaque fois de quel type d'équipement il s'agit
        while ($equipment = $equipmentQuery->fetch())
        {
            $equipmentId = stripslashes($equipment['itemId']);
            $equipmentRaceId = stripslashes($item['itemRaceId']);
            $equipmentType = stripslashes($equipment['itemType']);
            $equipmentLevel = stripslashes($equipment['itemLevel']);
            $equipmentLevelRequired = stripslashes($equipment['itemLevelRequired']);
            $equipmentName = stripslashes($equipment['itemName']);
            $equipmentDescription = stripslashes($equipment['itemDescription']);
            $equipmentQuantity = stripslashes($equipment['inventoryQuantity']);
            $equipmentHpEffect = stripslashes($equipment['itemHpEffect']);
            $equipmentMpEffect = stripslashes($equipment['itemMpEffect']);
            $equipmentStrengthEffect = stripslashes($equipment['itemStrengthEffect']);
            $equipmentMagicEffect = stripslashes($equipment['itemMagicEffect']);
            $equipmentAgilityEffect = stripslashes($equipment['itemAgilityEffect']);
            $equipmentDefenseEffect = stripslashes($equipment['itemDefenseEffect']);
            $equipmentDefenseMagicEffect = stripslashes($equipment['itemDefenseMagicEffect']);
            $equipmentWisdomEffect = stripslashes($equipment['itemWisdomEffect']);
            $equipmentSalePrice = stripslashes($equipment['itemSalePrice']);
            $equipmentEquipped = stripslashes($equipment['inventoryEquipped']);

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
                                ?>
                                    Votre niveau est trop faible pour vous équiper de cet objet
                                <?php
                            }
                            
                        }
                        //Si l'équipement est équippé on prévient le joueur
                        else
                        {
                            ?>
                                Cet équipement est actuellement équippé
                                <form method="POST" action="unEquip.php">
                                    <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                    <input type="submit" class="btn btn-default form-control" name="unEquip" value="Déséquiper"><br /><br />
                                </form>
                            <?php
                        }
                        ?>
                        <form method="POST" action="saleItem.php">
                            <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                            <input type="submit" class="btn btn-default form-control" name="sale" value="Vendre"><br /><br />
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
    //Si l'équipement choisit n'est pas un nombre
    else
    {
         echo "L'équipment choisit est invalid";
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>