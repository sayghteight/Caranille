<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['itemId'])
&& isset($_POST['finalUnEquip']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['itemId'])
    && $_POST['itemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));

        //On cherche à savoir si l'équipement qui va se déséquiper appartient bien au joueur
        $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
        WHERE itemId = inventoryItemId
        AND inventoryCharacterId = ?
        AND itemId = ?");
        $itemQuery->execute([$characterId, $itemId]);
        $itemRow = $itemQuery->rowCount();

        //Si le personne possède cet équipement
        if ($itemRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($item = $itemQuery->fetch())
            {
                //On récupère les informations de l'équippement
                $inventoryId = stripslashes($item['inventoryId']);
                $itemQuantity = stripslashes($item['inventoryQuantity']);
                $itemName = stripslashes($item['itemName']);
                $itemSalePrice = stripslashes($item['itemSalePrice']);
                $inventoryEquipped = stripslashes($item['inventoryEquipped']);
            }
            $itemQuery->closeCursor();

            //Si la variable $inventoryEquipped est égale à 1 c'est qu'il s'agit d'un équipement actuellement équippé
            if ($inventoryEquipped == 1)
            {
                //On va rendre l'équipement non équippé
                $updateInventory = $bdd->prepare("UPDATE car_inventory SET
                inventoryEquipped = 0
                WHERE inventoryId = :inventoryId");
                $updateInventory->execute(array(
                'inventoryId' => $inventoryId));
                $updateInventory->closeCursor();

                //On remet les stats du joueurs à zéro pour recalculer ensuite le bonus de tous les équipements équippé
                $updateCharacter = $bdd->prepare("UPDATE car_characters SET
                characterHpEquipments = 0,
                characterMpEquipments = 0, 
                characterStrengthEquipments = 0, 
                characterMagicEquipments = 0, 
                characterAgilityEquipments = 0, 
                characterDefenseEquipments = 0, 
                characterDefenseMagicEquipments = 0, 
                characterWisdomEquipments = 0,
                characterProspectingEquipments = 0
                WHERE characterId = :characterId");
                $updateCharacter->execute(array(
                'characterId' => $characterId));
                $updateCharacter->closeCursor();

                //On va maintenant faire une requête sur tous les équipements que possède le joueurs et qui sont équippé pour rajouter les bonus
                $equipmentEquipedQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
                WHERE itemId = inventoryItemId
                AND inventoryEquipped = 1
                AND inventoryCharacterId = ?");
                $equipmentEquipedQuery->execute([$characterId]);

                //On fait une boucle sur les résultats et on additionne les bonus de tous les équipements actuellement équipé
                while ($equipment = $equipmentEquipedQuery->fetch())
                {
                    //On récupère les informations de l'équippement
                    $hpBonus = $hpBonus + stripslashes($equipment['itemHpEffect']);
                    $mpBonus = $mpBonus + stripslashes($equipment['itemMpEffect']);
                    $strengthBonus = $strengthBonus + stripslashes($equipment['itemStrengthEffect']);
                    $magicBonus = $magicBonus + stripslashes($equipment['itemMagicEffect']);
                    $agilityBonus = $agilityBonus + stripslashes($equipment['itemAgilityEffect']);
                    $defenseBonus = $defenseBonus + stripslashes($equipment['itemDefenseEffect']);
                    $defenseMagicBonus = $defenseMagicBonus + stripslashes($equipment['itemDefenseMagicEffect']);
                    $wisdomBonus = $wisdomBonus + stripslashes($equipment['itemWisdomEffect']);
                    $prospectingBonus = $wisdomBonus + stripslashes($equipment['itemProspectingEffect']);
                }
                $equipmentEquipedQuery->closeCursor();

                //On ajoute les bonus des stats au joueurs
                $updateCharacter = $bdd->prepare("UPDATE car_characters SET
                characterHpEquipments = :hpBonus,
                characterMpEquipments = :mpBonus, 
                characterStrengthEquipments = :strengthBonus, 
                characterMagicEquipments = :magicBonus, 
                characterAgilityEquipments = :agilityBonus, 
                characterDefenseEquipments = :defenseBonus, 
                characterDefenseMagicEquipments = :defenseMagicBonus, 
                characterWisdomEquipments = :wisdomBonus,
                characterProspectingEquipments = :prospectingBonus
                WHERE characterId = :characterId");
                $updateCharacter->execute(array(
                'hpBonus' => $hpBonus,
                'mpBonus' => $mpBonus,
                'strengthBonus' => $strengthBonus,
                'magicBonus' => $magicBonus,
                'agilityBonus' => $agilityBonus,
                'defenseBonus' => $defenseBonus,
                'defenseMagicBonus' => $defenseMagicBonus,
                'wisdomBonus' => $wisdomBonus,
                'prospectingBonus' => $prospectingBonus,
                'characterId' => $characterId));
                $updateCharacter->closeCursor();

                //On va maintenant finir par actualiser tous le personnage
                $updateCharacter = $bdd->prepare('UPDATE car_characters
                SET characterHpTotal = characterHpMax + characterHpSkillPoints + characterHpBonus + characterHpEquipments + characterHpGuild,
                characterMpTotal = characterMpMax + characterMpSkillPoints + characterMpBonus + characterMpEquipments + characterMpGuild,
                characterStrengthTotal = characterStrength + characterStrengthSkillPoints + characterStrengthBonus + characterStrengthEquipments + characterStrengthGuild,
                characterMagicTotal = characterMagic + characterMagicSkillPoints + characterMagicBonus + characterMagicEquipments + characterMagicGuild,
                characterAgilityTotal = characterAgility + characterAgilitySkillPoints + characterAgilityBonus + characterAgilityEquipments + characterAgilityGuild,
                characterDefenseTotal = characterDefense + characterDefenseSkillPoints + characterDefenseBonus + characterDefenseEquipments + characterDefenseGuild,
                characterDefenseMagicTotal = characterDefenseMagic + characterDefenseMagicSkillPoints + characterDefenseMagicBonus + characterDefenseMagicEquipments + characterDefenseMagicGuild,
                characterWisdomTotal = characterWisdom + characterWisdomSkillPoints + characterWisdomBonus + characterWisdomEquipments + characterWisdomGuild,
                characterProspectingTotal = characterProspecting + characterProspectingSkillPoints + characterProspectingBonus + characterProspectingEquipments + characterProspectingGuild
                WHERE characterId = :characterId');
                $updateCharacter->execute(['characterId' => $characterId]);
                $updateCharacter->closeCursor();
            }
            ?>
            
            Vous venez de déséquiper l'équipement <?php echo $itemName ?>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" value="Retour">
            </form>
            
            <?php
        }
        else
        {
            echo "Erreur: Impossible déséquiper un équipement que vous ne possédez pas.";
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
    echo "Tous les champs n'ont pas été remplie";
}

require_once("../../html/footer.php"); ?>