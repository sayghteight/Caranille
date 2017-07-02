<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemId']) 
&& isset($_POST['adminItemRaceId'])
&& isset($_POST['adminItemPicture'])
&& isset($_POST['adminItemLevel'])
&& isset($_POST['adminItemLevelRequired'])
&& isset($_POST['adminItemName'])
&& isset($_POST['adminItemDescription'])
&& isset($_POST['adminItemHpEffects'])
&& isset($_POST['adminItemMpEffect'])
&& isset($_POST['adminItemStrengthEffect'])
&& isset($_POST['adminItemMagicEffect'])
&& isset($_POST['adminItemAgilityEffect'])
&& isset($_POST['adminItemDefenseEffect'])
&& isset($_POST['adminItemDefenseMagicEffect'])
&& isset($_POST['adminItemWisdomEffect'])
&& isset($_POST['adminItemPurchasePrice'])
&& isset($_POST['adminItemSalePrice'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemId']) 
    && ctype_digit($_POST['adminItemRaceId'])
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
    && ctype_digit($_POST['adminItemPurchasePrice'])
    && ctype_digit($_POST['adminItemSalePrice'])
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
    && $_POST['adminItemPurchasePrice'] >= 0
    && $_POST['adminItemSalePrice'] >= 0)
    {
        //On récupère l'Id du formulaire précédent
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));
        
        //On fait une requête pour vérifier si l'équipement choisi existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId= ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'équipement existe
        if ($itemRow == 1) 
        {
            $adminItemRaceId = htmlspecialchars(addslashes($_POST['adminItemRaceId']));
            //Si la classe choisi est supérieur à zéro c'est que l'équipement est dedié à une classe
            if ($adminItemRaceId > 0)
            {
                //On fait une requête pour vérifier si la classe choisie existe
                $raceQuery = $bdd->prepare('SELECT * FROM car_races 
                WHERE raceId= ?');
                $raceQuery->execute([$adminItemRaceId]);
                $raceRow = $raceQuery->rowCount();
            }
            //Si la classe choisi est égal à zéro c'est qu'il s'agit d'un équipement pour toutes les classes
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
                $adminItemRaceId = htmlspecialchars(addslashes($_POST['adminItemRaceId']));
                $adminItemPicture = htmlspecialchars(addslashes($_POST['adminItemPicture']));
                $adminItemType = htmlspecialchars(addslashes($_POST['adminItemType']));
                $adminItemLevel = htmlspecialchars(addslashes($_POST['adminItemLevel']));
                $adminItemLevelRequired = htmlspecialchars(addslashes($_POST['adminItemLevelRequired']));
                $adminItemName = htmlspecialchars(addslashes($_POST['adminItemName']));
                $adminItemDescription = htmlspecialchars(addslashes($_POST['adminItemDescription']));
                $adminItemHpEffects = htmlspecialchars(addslashes($_POST['adminItemHpEffects']));
                $adminItemMpEffect = htmlspecialchars(addslashes($_POST['adminItemMpEffect']));
                $adminItemStrengthEffect = htmlspecialchars(addslashes($_POST['adminItemStrengthEffect']));
                $adminItemMagicEffect = htmlspecialchars(addslashes($_POST['adminItemMagicEffect']));
                $adminItemAgilityEffect = htmlspecialchars(addslashes($_POST['adminItemAgilityEffect']));
                $adminItemDefenseEffect = htmlspecialchars(addslashes($_POST['adminItemDefenseEffect']));
                $adminItemDefenseMagicEffect = htmlspecialchars(addslashes($_POST['adminItemDefenseMagicEffect']));
                $adminItemWisdomEffect = htmlspecialchars(addslashes($_POST['adminItemWisdomEffect']));
                $adminItemPurchasePrice = htmlspecialchars(addslashes($_POST['adminItemPurchasePrice']));
                $adminItemSalePrice = htmlspecialchars(addslashes($_POST['adminItemSalePrice']));

                //On met à jour l'équipement dans la base de donnée
                $updateItems = $bdd->prepare('UPDATE car_items 
                SET itemRaceId = :adminItemRaceId, 
                itemPicture = :adminItemPicture, 
                itemType = :adminItemType,
                itemLevel = :adminItemLevel,
                itemLevelRequired = :adminItemLevelRequired,
                itemName = :adminItemName,
                itemDescription = :adminItemDescription,
                itemHpEffect = :adminItemHpEffects,
                itemMpEffect = :adminItemMpEffect,
                itemStrengthEffect = :adminItemStrengthEffect,
                itemMagicEffect = :adminItemMagicEffect,
                itemAgilityEffect = :adminItemAgilityEffect,
                itemDefenseEffect = :adminItemDefenseEffect,
                itemDefenseMagicEffect = :adminItemDefenseMagicEffect,
                itemWisdomEffect = :adminItemWisdomEffect,
                itemPurchasePrice = :adminItemPurchasePrice,
                itemSalePrice = :adminItemSalePrice
                WHERE itemId = :adminItemId');

                $updateItems->execute([
                'adminItemRaceId' => $adminItemRaceId,
                'adminItemPicture' => $adminItemPicture,
                'adminItemType' => $adminItemType,
                'adminItemLevel' => $adminItemLevel,
                'adminItemLevelRequired' => $adminItemLevelRequired,
                'adminItemName' => $adminItemName,
                'adminItemDescription' => $adminItemDescription,
                'adminItemHpEffects' => $adminItemHpEffects,
                'adminItemMpEffect' => $adminItemMpEffect,
                'adminItemStrengthEffect' => $adminItemStrengthEffect,
                'adminItemMagicEffect' => $adminItemMagicEffect,
                'adminItemAgilityEffect' => $adminItemAgilityEffect,
                'adminItemDefenseEffect' => $adminItemDefenseEffect,
                'adminItemDefenseMagicEffect' => $adminItemDefenseMagicEffect,
                'adminItemWisdomEffect' => $adminItemWisdomEffect,
                'adminItemPurchasePrice' => $adminItemPurchasePrice,
                'adminItemSalePrice' => $adminItemSalePrice,
                'adminItemId' => $adminItemId]);
                $updateItems->closeCursor();

                //On cherche à savoir quel joueur à cet équipement et S'il en est équippé pour appliquer la mise à jour
                $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
                WHERE itemId = inventoryItemId
                AND inventoryEquipped = 1
                AND itemId = ?");
                $itemQuery->execute([$adminItemId]);
                $itemRow = $itemQuery->rowCount();

                //Si des joueurs en sont équippé
                if ($itemRow > 0) 
                {
                    //On va mettre leur compte à jour
                    while ($item = $itemQuery->fetch())
                    {   
                        //On récupère l'Id du personnage
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
                        characterWisdomEquipments = 0
                        WHERE characterId= :adminCharacterId");

                        $updateCharacter->execute(array(
                        'adminCharacterId' => $adminCharacterId));
                        $updateCharacter->closeCursor();

                        //Initialisation des variables qui vont contenir les bonus de tous les équipements actuellement équippé
                        $hpBonus = 0;
                        $mpBonus = 0;
                        $strengthBonus = 0;
                        $magicBonus = 0;
                        $agilityBonus = 0;
                        $defenseBonus = 0;
                        $defenseMagicBonus = 0;
                        $wisdomBonus = 0;

                        //On va maintenant faire une requête sur tous les équipements que possède le joueurs et qui sont équippé pour rajouter les bonus
                        $equipmentEquipedQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
                        WHERE itemId = inventoryItemId
                        AND inventoryEquipped = 1
                        AND inventoryCharacterId = ?");
                        $equipmentEquipedQuery->execute([$adminCharacterId]);

                        //On fait une boucle sur les résultats et on additionne les bonus de tous les équipements actuellement équipé
                        while ($equipment = $equipmentEquipedQuery->fetch())
                        {
                            $hpBonus = $hpBonus + stripslashes($equipment['itemHpEffect']);
                            $mpBonus = $mpBonus + stripslashes($equipment['itemMpEffect']);
                            $strengthBonus = $strengthBonus + stripslashes($equipment['itemStrengthEffect']);
                            $magicBonus = $magicBonus + stripslashes($equipment['itemMagicEffect']);
                            $agilityBonus = $agilityBonus + stripslashes($equipment['itemAgilityEffect']);
                            $defenseBonus = $defenseBonus + stripslashes($equipment['itemDefenseEffect']);
                            $defenseMagicBonus = $defenseMagicBonus + stripslashes($equipment['itemDefenseMagicEffect']);
                            $wisdomBonus = $wisdomBonus + stripslashes($equipment['itemWisdomEffect']);
                        }

                        //On ajoute les bonus des stats au joueurs
                        $updateCharacter = $bdd->prepare("UPDATE car_characters SET
                        characterHpEquipments = :hpBonus,
                        characterMpEquipments = :mpBonus, 
                        characterStrengthEquipments = :strengthBonus, 
                        characterMagicEquipments = :magicBonus, 
                        characterAgilityEquipments = :agilityBonus, 
                        characterDefenseEquipments = :defenseBonus, 
                        characterDefenseMagicEquipments = :defenseMagicBonus, 
                        characterWisdomEquipments = :wisdomBonus
                        WHERE characterId= :adminCharacterId");

                        $updateCharacter->execute(array(
                        'hpBonus' => $hpBonus,
                        'mpBonus' => $mpBonus,
                        'strengthBonus' => $strengthBonus,
                        'magicBonus' => $magicBonus,
                        'agilityBonus' => $agilityBonus,
                        'defenseBonus' => $defenseBonus,
                        'defenseMagicBonus' => $defenseMagicBonus,
                        'wisdomBonus' => $wisdomBonus,
                        'adminCharacterId' => $adminCharacterId));
                        $updateCharacter->closeCursor();

                        //On va maintenant finir par actualiser tous le personnage
                        $updateCharacter = $bdd->prepare('UPDATE car_characters
                        SET characterHpTotal = characterHpMax + characterHpSkillPoints + characterHpBonus + characterHpEquipments,
                        characterMpTotal = characterMpMax + characterMpSkillPoints + characterMpBonus + characterMpEquipments,
                        characterStrengthTotal = characterStrength + characterStrengthSkillPoints + characterStrengthBonus + characterStrengthEquipments,
                        characterMagicTotal = characterMagic + characterMagicSkillPoints + characterMagicBonus + characterMagicEquipments,
                        characterAgilityTotal = characterAgility + characterAgilitySkillPoints + characterAgilityBonus + characterAgilityEquipments,
                        characterDefenseTotal = characterDefense + characterDefenseSkillPoints + characterDefenseBonus + characterDefenseEquipments,
                        characterDefenseMagicTotal = characterDefenseMagic + characterDefenseMagicSkillPoints + characterDefenseMagicBonus + characterDefenseMagicEquipments,
                        characterWisdomTotal = characterWisdom + characterWisdomSkillPoints + characterWisdomBonus + characterWisdomEquipments
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