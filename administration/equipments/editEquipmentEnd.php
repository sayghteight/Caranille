<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemId']) 
&& isset($_POST['adminItemItemTypeId'])
&& isset($_POST['adminItemRaceId'])
&& isset($_POST['adminItemPicture'])
&& isset($_POST['adminItemName'])
&& isset($_POST['adminItemDescription'])
&& isset($_POST['adminItemLevel'])
&& isset($_POST['adminItemLevelRequired'])
&& isset($_POST['adminItemHpEffects'])
&& isset($_POST['adminItemMpEffect'])
&& isset($_POST['adminItemStrengthEffect'])
&& isset($_POST['adminItemMagicEffect'])
&& isset($_POST['adminItemAgilityEffect'])
&& isset($_POST['adminItemDefenseEffect'])
&& isset($_POST['adminItemDefenseMagicEffect'])
&& isset($_POST['adminItemWisdomEffect'])
&& isset($_POST['adminItemProspectingEffect'])
&& isset($_POST['adminItemPurchasePrice'])
&& isset($_POST['adminItemSalePrice'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemId']) 
    && ctype_digit($_POST['adminItemItemTypeId'])
    && ctype_digit($_POST['adminItemRaceId'])
    && ctype_digit($_POST['adminItemLevel'])
    && ctype_digit($_POST['adminItemLevelRequired'])
    && ctype_digit($_POST['adminItemHpEffects'])
    && ctype_digit($_POST['adminItemMpEffect'])
    && ctype_digit($_POST['adminItemStrengthEffect'])
    && ctype_digit($_POST['adminItemMagicEffect'])
    && ctype_digit($_POST['adminItemAgilityEffect'])
    && ctype_digit($_POST['adminItemDefenseEffect'])
    && ctype_digit($_POST['adminItemDefenseMagicEffect'])
    && ctype_digit($_POST['adminItemWisdomEffect'])
    && ctype_digit($_POST['adminItemProspectingEffect'])
    && ctype_digit($_POST['adminItemPurchasePrice'])
    && ctype_digit($_POST['adminItemSalePrice'])
    && $_POST['adminItemId'] >= 0
    && $_POST['adminItemItemTypeId'] >= 0
    && $_POST['adminItemRaceId'] >= 0
    && $_POST['adminItemLevel'] >= 0
    && $_POST['adminItemLevelRequired'] >= 0
    && $_POST['adminItemHpEffects'] >= 0
    && $_POST['adminItemMpEffect'] >= 0
    && $_POST['adminItemStrengthEffect'] >= 0
    && $_POST['adminItemMagicEffect'] >= 0
    && $_POST['adminItemAgilityEffect'] >= 0
    && $_POST['adminItemDefenseEffect'] >= 0
    && $_POST['adminItemDefenseMagicEffect'] >= 0
    && $_POST['adminItemWisdomEffect'] >= 0
    && $_POST['adminItemProspectingEffect'] >= 0
    && $_POST['adminItemPurchasePrice'] >= 0
    && $_POST['adminItemSalePrice'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));
        $adminItemItemTypeId = htmlspecialchars(addslashes($_POST['adminItemItemTypeId']));
        $adminItemRaceId = htmlspecialchars(addslashes($_POST['adminItemRaceId']));
        
        //On fait une requête pour vérifier si l'équipement choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId = ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'équipement existe
        if ($itemRow == 1) 
        {
            //On fait une requête pour vérifier si le type d'équipement choisit existe
            $itemTypeQuery = $bdd->prepare('SELECT * FROM car_items_types
            WHERE itemTypeId = ?');
            $itemTypeQuery->execute([$adminItemItemTypeId]);
            $itemTypeRow = $itemTypeQuery->rowCount();
    
            //Si le type d'équipement existe
            if ($itemTypeRow == 1) 
            {
                //Si la classe choisit est supérieur à zéro c'est que l'équipement est dedié à une classe
                if ($adminItemRaceId > 0)
                {
                    //On fait une requête pour vérifier si la classe choisie existe
                    $raceQuery = $bdd->prepare('SELECT * FROM car_races 
                    WHERE raceId = ?');
                    $raceQuery->execute([$adminItemRaceId]);
                    $raceRow = $raceQuery->rowCount();
                }
                //Si la classe choisit est égal à zéro c'est qu'il s'agit d'un équipement pour toutes les classes
                else
                {
                    //On met $raceRow à 1 pour passer à la suite
                    $raceRow = 1;
                }
    
                //Si la classe est disponible ou que la classe est à zéro
                if ($raceRow == 1) 
                {
                    //On récupère les informations du formulaire
                    $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));
                    $adminItemItemTypeId = htmlspecialchars(addslashes($_POST['adminItemItemTypeId']));
                    $adminItemRaceId = htmlspecialchars(addslashes($_POST['adminItemRaceId']));
                    $adminItemPicture = htmlspecialchars(addslashes($_POST['adminItemPicture']));
                    $adminItemName = htmlspecialchars(addslashes($_POST['adminItemName']));
                    $adminItemDescription = htmlspecialchars(addslashes($_POST['adminItemDescription']));
                    $adminItemLevel = htmlspecialchars(addslashes($_POST['adminItemLevel']));
                    $adminItemLevelRequired = htmlspecialchars(addslashes($_POST['adminItemLevelRequired']));
                    $adminItemHpEffects = htmlspecialchars(addslashes($_POST['adminItemHpEffects']));
                    $adminItemMpEffect = htmlspecialchars(addslashes($_POST['adminItemMpEffect']));
                    $adminItemStrengthEffect = htmlspecialchars(addslashes($_POST['adminItemStrengthEffect']));
                    $adminItemMagicEffect = htmlspecialchars(addslashes($_POST['adminItemMagicEffect']));
                    $adminItemAgilityEffect = htmlspecialchars(addslashes($_POST['adminItemAgilityEffect']));
                    $adminItemDefenseEffect = htmlspecialchars(addslashes($_POST['adminItemDefenseEffect']));
                    $adminItemDefenseMagicEffect = htmlspecialchars(addslashes($_POST['adminItemDefenseMagicEffect']));
                    $adminItemWisdomEffect = htmlspecialchars(addslashes($_POST['adminItemWisdomEffect']));
                    $adminItemProspectingEffect = htmlspecialchars(addslashes($_POST['adminItemProspectingEffect']));
                    $adminItemPurchasePrice = htmlspecialchars(addslashes($_POST['adminItemPurchasePrice']));
                    $adminItemSalePrice = htmlspecialchars(addslashes($_POST['adminItemSalePrice']));
    
                    //On met à jour l'équipement dans la base de donnée
                    $updateItems = $bdd->prepare('UPDATE car_items 
                    SET itemItemTypeId = :adminItemItemTypeId, 
                    itemRaceId = :adminItemRaceId, 
                    itemPicture = :adminItemPicture,
                    itemName = :adminItemName,
                    itemDescription = :adminItemDescription,
                    itemLevel = :adminItemLevel,
                    itemLevelRequired = :adminItemLevelRequired,
                    itemHpEffect = :adminItemHpEffects,
                    itemMpEffect = :adminItemMpEffect,
                    itemStrengthEffect = :adminItemStrengthEffect,
                    itemMagicEffect = :adminItemMagicEffect,
                    itemAgilityEffect = :adminItemAgilityEffect,
                    itemDefenseEffect = :adminItemDefenseEffect,
                    itemDefenseMagicEffect = :adminItemDefenseMagicEffect,
                    itemWisdomEffect = :adminItemWisdomEffect,
                    itemProspectingEffect = :adminItemProspectingEffect,
                    itemPurchasePrice = :adminItemPurchasePrice,
                    itemSalePrice = :adminItemSalePrice
                    WHERE itemId = :adminItemId');
                    $updateItems->execute([
                    'adminItemItemTypeId' => $adminItemItemTypeId,
                    'adminItemRaceId' => $adminItemRaceId,
                    'adminItemPicture' => $adminItemPicture,
                    'adminItemName' => $adminItemName,
                    'adminItemDescription' => $adminItemDescription,
                    'adminItemLevel' => $adminItemLevel,
                    'adminItemLevelRequired' => $adminItemLevelRequired,
                    'adminItemHpEffects' => $adminItemHpEffects,
                    'adminItemMpEffect' => $adminItemMpEffect,
                    'adminItemStrengthEffect' => $adminItemStrengthEffect,
                    'adminItemMagicEffect' => $adminItemMagicEffect,
                    'adminItemAgilityEffect' => $adminItemAgilityEffect,
                    'adminItemDefenseEffect' => $adminItemDefenseEffect,
                    'adminItemDefenseMagicEffect' => $adminItemDefenseMagicEffect,
                    'adminItemWisdomEffect' => $adminItemWisdomEffect,
                    'adminItemProspectingEffect' => $adminItemProspectingEffect,
                    'adminItemPurchasePrice' => $adminItemPurchasePrice,
                    'adminItemSalePrice' => $adminItemSalePrice,
                    'adminItemId' => $adminItemId]);
                    $updateItems->closeCursor();
    
                    //On cherche à savoir quel sont les joueurs qui ont cet équipement pour leur modifier les bonus de cet équippement
                    $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
                    WHERE itemId = inventoryItemId
                    AND inventoryEquipped = 1
                    AND itemId = ?");
                    $itemQuery->execute([$adminItemId]);
                    $itemRow = $itemQuery->rowCount();
    
                    //Si des joueurs en sont équippé
                    if ($itemRow > 0) 
                    {
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($item = $itemQuery->fetch())
                        {   
                            //On récupère les informations du personnage
                            $adminCharacterId = stripslashes($item['inventoryCharacterId']);
                            
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
                            WHERE characterId = :adminCharacterId");
                            $updateCharacter->execute(array(
                            'adminCharacterId' => $adminCharacterId));
                            $updateCharacter->closeCursor();
            
                            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations et on additionne les bonus de tous les équipements actuellement équipé
                            $equipmentEquipedQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
                            WHERE itemId = inventoryItemId
                            AND inventoryEquipped = 1
                            AND inventoryCharacterId = ?");
                            $equipmentEquipedQuery->execute([$adminCharacterId]);
            
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
                            WHERE characterId = :adminCharacterId");
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
                            'adminCharacterId' => $adminCharacterId));
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
                            WHERE characterId = :adminCharacterId');
                            $updateCharacter->execute(['adminCharacterId' => $adminCharacterId]);
                            $updateCharacter->closeCursor();
                        }
                    }
                    ?>
    
                    L'équipement a bien été mit à jour
    
                    <hr>
                        
                    <form method="POST" action="index.php">
                        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                    </form>
                    
                    <?php
                }
                //Si la classe choisie n'existe pas
                else
                {
                    echo "Erreur: La classe choisie n'existe pas";
                }
            }
            else 
            {
                echo "Erreur: Ce type d'objet n'existe pas";
            }
        }
        //Si l'équipement n'exite pas
        else
        {
            echo "Erreur: Equipement indisponible";
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
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");




































