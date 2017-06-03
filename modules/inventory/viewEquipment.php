<?php 
require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['viewEquipment']))
{
    //On vérifie si la classe choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['itemId']))
    {
        ?>

        Liste de vos équipements

        <hr>

        <?php
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));
        //On fait une requête pour avoir la liste des équipements du personnage
        /*
        SELECT * FROM car_items, car_inventory //On fait une liaison entre la table car_items et car_inventory
        WHERE itemId = inventoryItemItemId //On lie ses deux tables par l'Id de l'objet
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
            $equipmentType = stripslashes($equipment['itemType']);
            $equipmentName = stripslashes($equipment['itemName']);
            $equipmentDescription = stripslashes($equipment['itemDescription']);
            $equipmentLevelRequired = stripslashes($equipment['itemLevelRequired']);
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
            ?>
            <table class="table">
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
                        <?php echo $equipmentDescription; ?>
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
                        //Si cet objet n'est pas équipé on donne la possibilité au joueur de l'équiper ou le vendre
                        if($equipment['inventoryEquipped'] == '0')
                        {
                            ?>
                            <form method="POST" action="equip.php">
                                <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                <input type="submit" class="btn btn-default form-control" name="equip" value="Equiper">
                            </form>

                            <form method="POST" action="sale.php">
                                <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                <input type="submit" class="btn btn-default form-control" name="sale" value="Vendre"><br /><br />
                            </form>
                            <?php
                        }
                        //Si cet objet est équipé le joueur ne peux pas le vendre
                        else
                        {
                            ?>
                            Aucun actions possible
                            <?php
                        }
                        ?>  
                    </td>
                </tr>
            </table>
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
//Si tous les champs n'ont pas été rempli
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>