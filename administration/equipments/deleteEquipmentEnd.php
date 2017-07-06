<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemId'])
    && $_POST['adminItemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));
        
        //On fait une requête pour vérifier si l'équipement choisi existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId = ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'équipement existe
        if ($itemRow == 1) 
        {
            //Avant de supprimer l'équipement On cherche à savoir quel joueur a cet équipement et S'il en est équippé pour appliquer la mise à jour
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
                    //On récupère l'id du personnage
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
                    WHERE characterId = :adminCharacterId");

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

                    //On va maintenant faire une requête sur tous les équipements que possède le joueurs et qui sont équippé pour rajouter les bonus en ignorant celui qu'on va supprimer
                    $equipmentEquipedQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
                    WHERE itemId = inventoryItemId
                    AND inventoryEquipped = 1
                    AND inventoryItemId != ?
                    AND inventoryCharacterId = ?");
                    $equipmentEquipedQuery->execute([$adminItemId, $adminCharacterId]);

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
                    characterWisdomTotal = characterWisdom + characterWisdomSkillPoints + characterWisdomBonus + characterWisdomEquipments + characterWisdomGuild
                    WHERE characterId = :characterId');
                    $updateCharacter->execute(['characterId' => $characterId]);
                    $updateCharacter->closeCursor();
                }
            }

            //On supprime l'équipement de la base de donnée
            $itemDeleteQuery = $bdd->prepare("DELETE FROM car_items
            WHERE itemId = ?");
            $itemDeleteQuery->execute([$adminItemId]);
            $itemDeleteQuery->closeCursor();

            //On supprime aussi l'équipement de l'inventaire dans la base de donnée
            $inventoryDeleteQuery = $bdd->prepare("DELETE FROM car_inventory
            WHERE inventoryItemId = ?");
            $inventoryDeleteQuery->execute([$adminItemId]);
            $inventoryDeleteQuery->closeCursor();
            
            //On supprime l'équippements si il est lié à un monstre
            $itemDropDeleteQuery = $bdd->prepare("DELETE FROM car_monsters_drops
            WHERE monsterDropItemID = ?");
            $itemDropDeleteQuery->execute([$adminItemId]);
            $itemDropDeleteQuery->closeCursor();
            ?>

            L'équipement a bien été supprimé

            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
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
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");