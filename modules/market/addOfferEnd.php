<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['marketItemId'])
&& isset($_POST['marketSalePrice'])
&& isset($_POST['addOfferEnd']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['marketItemId'])
    && ctype_digit($_POST['marketSalePrice'])
    && $_POST['marketItemId'] >= 1
    && $_POST['marketSalePrice'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $marketItemId = htmlspecialchars(addslashes($_POST['marketItemId']));
        $marketSalePrice = htmlspecialchars(addslashes($_POST['marketSalePrice']));

        //On fait une requête pour vérifier si l'objet ou équippement choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId = ?');
        $itemQuery->execute([$marketItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'objet ou l'équippement existe
        if ($itemRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($item = $itemQuery->fetch())
            {
                //On récupère les informations de l'équipement
                $marketItemName = stripslashes($item['itemName']);
            }

            //On vérifit si le joueur possède bien cet objet ou équipement
            $inventoryQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
            WHERE itemId = inventoryItemId
            AND inventoryCharacterId = ?
            AND itemId = ?");
            $inventoryQuery->execute([$characterId, $marketItemId]);
            $inventoryRow = $inventoryQuery->rowCount();

            //Si l'objet ou équipement est bien dans l'inventaire du joueur
            if ($inventoryRow == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($inventory = $inventoryQuery->fetch())
                {
                    //On récupère les informations de l'objet
                    $inventoryId = stripslashes($inventory['inventoryId']);
                    $itemQuantity = stripslashes($inventory['inventoryQuantity']);
                    $itemName = stripslashes($inventory['itemName']);
                    $itemSalePrice = stripslashes($inventory['itemSalePrice']);
                    $inventoryEquipped = stripslashes($inventory['inventoryEquipped']);
                }

                //Si le joueur possède plusieurs exemplaire de cet objet/équipement
                if ($itemQuantity > 1)
                {
                    //On met l'inventaire à jour
                    $updateInventory = $bdd->prepare("UPDATE car_inventory SET
                    inventoryQuantity = inventoryQuantity - 1
                    WHERE inventoryId = :inventoryId");
                    $updateInventory->execute(array(
                    'inventoryId' => $inventoryId));
                    $updateInventory->closeCursor();                
                }
                //Si le joueur ne possède cet objet/équipement que en un seul exemplaire
                else
                {
                    //Si l'équippement est équippé et que c'est le seul exemplaire du joueur
                    if ($inventoryEquipped == 1)
                    {
                        //On va donc rendre l'objet non équippé afin de le mettre en vente
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
                        characterWisdomEquipments = 0
                        WHERE characterId = :characterId");
                        $updateCharacter->execute(array(
                        'characterId' => $characterId));
                        $updateCharacter->closeCursor();

                        //Initialisation des variables qui vont contenir les bonus de tous les équipements équippé
                        $hpBonus = 0;
                        $mpBonus = 0;
                        $strengthBonus = 0;
                        $magicBonus = 0;
                        $agilityBonus = 0;
                        $defenseBonus = 0;
                        $defenseMagicBonus = 0;
                        $wisdomBonus = 0;

                        //On va maintenant faire une requête sur tous les équipements que possède le joueurs pour rajouter les bonus
                        $equipmentEquipedQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
                        WHERE itemId = inventoryItemId
                        AND inventoryEquipped = 1
                        AND inventoryCharacterId = ?");
                        $equipmentEquipedQuery->execute([$characterId]);

                        //On fait une boucle sur les résultats et on additionne les bonus de tous les équipements équipé
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
                        characterWisdomEquipments = :wisdomBonus
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
                        'characterId' => $characterId));
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
                        WHERE characterId = :characterId');
                        $updateCharacter->execute(['characterId' => $characterId]);
                        $updateCharacter->closeCursor();
                    }

                    //On supprime l'objet de l'inventaire
                    $updateInventory = $bdd->prepare("DELETE FROM car_inventory
                    WHERE inventoryId = :inventoryId");
                    $updateInventory->execute(array(
                    'inventoryId' => $inventoryId));
                    $updateInventory->closeCursor();
                }

                //On crée l'offre dans la base de donnée
                $addOffer = $bdd->prepare("INSERT INTO car_market VALUES(
                '',
                :characterId,
                :marketItemId,
                :marketSalePrice)");
                $addOffer->execute(array(
                'characterId' => $characterId,  
                'marketItemId' => $marketItemId,
                'marketSalePrice' => $marketSalePrice));
                $addOffer->closeCursor();
                ?>
                
                Vous venez de mettre en ligne l'offre concernant l'article <?php echo $marketItemName ?> pour <?php echo $marketSalePrice ?> Pièce(s) d'or

                <hr>

                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" value="Retour">
                </form>
                
                <?php
            }
            //Si le joueur ne possède pas cet objet ou équippement
            else
            {
                echo "Erreur: Vous ne pouvez pas mettre en vente un objet ou équippement que vous ne possedez pas";
            }
            $inventoryQuery->closeCursor();
        }
        //Si l'objet ou équippement n'exite pas
        else
        {
            echo "Erreur: Cette objet ou équippement n'existe pas";
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