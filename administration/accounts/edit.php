<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à choisit un id de compte
if (isset($_POST['adminAccountId']))
{
    //On vérifie si l'id du compte choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['adminAccountId']))
    {
        //On récupère l'Id du formulaire précédent
        $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

        //On fait une requête pour vérifier si le compte choisit existe
        $accountQuery = $bdd->prepare('SELECT * FROM car_accounts 
        WHERE accountId= ?');
        $accountQuery->execute([$adminAccountId]);
        $account = $accountQuery->rowCount();
        $accountQuery->closeCursor();

        //Si le compte est disponible
        if ($account == 1) 
        {
            //On récupères les informations du compte pour le formulaire ci-dessous
            $accountQuery = $bdd->prepare("SELECT * FROM car_accounts, car_characters, car_races
            WHERE accountId = characterAccountId
            AND characterRaceId = raceId
            AND accountId = ?");
            $accountQuery->execute([$adminAccountId]);
            while ($account = $accountQuery->fetch())
            {
                //On récupère les informations du compte
                $adminAccountId = stripslashes($account['accountId']);
                $adminAccountPseudo = stripslashes($account['accountPseudo']);
                $adminAccountEmail = stripslashes($account['accountEmail']);
                $adminAccountAccess = stripslashes($account['accountAccess']);

                //On récupère les informations du personnage
                $adminCharacterId = stripslashes($account['characterId']);
                $adminCharacterAccountId = stripslashes($account['characterAccountId']);
                $adminCharacterRaceId = stripslashes($account['characterRaceId']);
                $adminCharacterName = stripslashes($account['characterName']);
                $adminCharacterLevel = stripslashes($account['characterLevel']);
                $adminCharacterSex = stripslashes($account['characterSex']);
                $adminCharacterHpMin = stripslashes($account['characterHpMin']);
                $adminCharacterHpMax = stripslashes($account['characterHpMax']);
                $adminCharacterHpSkillPoints = stripslashes($account['characterHpSkillPoints']);
                $adminCharacterHpBonus = stripslashes($account['characterHpBonus']);
                $adminCharacterHpEquipments = stripslashes($account['characterHpEquipments']);
                $adminCharacterHpTotal = stripslashes($account['characterHpTotal']);
                $adminCharacterMpMin = stripslashes($account['characterMpMin']);
                $adminCharacterMpMax = stripslashes($account['characterMpMax']);
                $adminCharacterMpSkillPoints = stripslashes($account['characterMpSkillPoints']);
                $adminCharacterMpBonus = stripslashes($account['characterMpBonus']);
                $adminCharacterMpEquipments = stripslashes($account['characterMpEquipments']);
                $adminCharacterMpTotal = stripslashes($account['characterMpTotal']);
                $adminCharacterStrength = stripslashes($account['characterStrength']);
                $adminCharacterStrengthSkillPoints = stripslashes($account['characterStrengthSkillPoints']);
                $adminCharacterStrengthBonus = stripslashes($account['characterStrengthBonus']);
                $adminCharacterStrengthEquipments = stripslashes($account['characterStrengthEquipments']);
                $adminCharacterStrengthTotal = stripslashes($account['characterStrengthTotal']);
                $adminCharacterMagic = stripslashes($account['characterMagic']);
                $adminCharacterMagicSkillPoints = stripslashes($account['characterMagicSkillPoints']);
                $adminCharacterMagicBonus = stripslashes($account['characterMagicBonus']);
                $adminCharacterMagicEquipments = stripslashes($account['characterMagicEquipments']);
                $adminCharacterMagicTotal = stripslashes($account['characterMagicTotal']);
                $adminCharacterAgility = stripslashes($account['characterAgility']);
                $adminCharacterAgilitySkillPoints = stripslashes($account['characterAgilitySkillPoints']);
                $adminCharacterAgilityBonus = stripslashes($account['characterAgilityBonus']);
                $adminCharacterAgilityEquipments = stripslashes($account['characterAgilityEquipments']);
                $adminCharacterAgilityTotal = stripslashes($account['characterAgilityTotal']);
                $adminCharacterDefense = stripslashes($account['characterDefense']);
                $adminCharacterDefenseSkillPoints = stripslashes($account['characterDefenseSkillPoints']);
                $adminCharacterDefenseBonus = stripslashes($account['characterDefenseBonus']);
                $adminCharacterDefenseEquipment = stripslashes($account['characterDefenseEquipments']);
                $adminCharacterDefenseTotal = stripslashes($account['characterDefenseTotal']);
                $adminCharacterDefenseMagic = stripslashes($account['characterDefenseMagic']);
                $adminCharacterDefenseMagicSkillPoints = stripslashes($account['characterDefenseMagicSkillPoints']);
                $adminCharacterDefenseMagicBonus = stripslashes($account['characterDefenseMagicBonus']);
                $adminCharacterDefenseMagicEquipments = stripslashes($account['characterDefenseMagicEquipments']);
                $adminCharacterDefenseMagicTotal = stripslashes($account['characterDefenseMagicTotal']);
                $adminCharacterWisdom = stripslashes($account['characterWisdom']);
                $adminCharacterWisdomSkillPoints = stripslashes($account['characterWisdomSkillPoints']);
                $adminCharacterWisdomBonus = stripslashes($account['characterWisdomBonus']);
                $adminCharacterWisdomEquipments = stripslashes($account['characterWisdomEquipments']);
                $adminCharacterWisdomTotal = stripslashes($account['characterWisdomTotal']);
                $adminCharacterDefeate = stripslashes($account['characterDefeate']);
                $adminCharacterVictory = stripslashes($account['characterVictory']);
                $adminCharacterExperience = stripslashes($account['characterExperience']);
                $adminCharacterExperienceTotal = stripslashes($account['characterExperienceTotal']);
                $adminCharacterSkillPoints = stripslashes($account['characterSkillPoints']);
                $adminCharacterGold = stripslashes($account['characterGold']);
                $adminCharacterTownId = stripslashes($account['characterTownId']);
                $adminCharacterChapter = stripslashes($account['characterChapter']);
                $adminCharacterOnBattle = stripslashes($account['characterOnBattle']);
                $adminCharacterEnable = stripslashes($account['characterEnable']);

                //On récupère le nom de la classe
                $adminRaceName = stripslashes($account['raceName']);
            }
            $accountQuery->closeCursor();

            //On va déterminer le sexe du personnage
            switch ($adminCharacterSex)
            {
                //Si le sexe du personnage est 0 c'est sexe féminin
                case 0:
                    $adminCharacterSexName = "Féminin";
                break;

                //Si le sexe du personnage est 1 c'est sexe masculin
                case 1:
                    $adminCharacterSexName = "Masculin";
                break;
            }

            //On va déterminer si le personnage est en combat ou pas
            switch ($adminCharacterOnBattle)
            {
                //Si OnBattle du personnage est 0 il est hors combat
                case 0:
                    $adminCharacterOnBattleName = "Non";
                break;

                //Si OnBattle du personnage est 1 il est en combat
                case 1:
                    $adminCharacterOnBattleName = "Oui";
                break;
            }

            //Si adminCharacterTownId à un Id supérieur à zéro c'est que le joueur est dans une ville
            if ($adminCharacterTownId > 0)
            {
                //On récupère la ville du personnage pour l'afficher dans le menu d'information du personnage
                $townQuery = $bdd->prepare("SELECT * FROM car_towns
                WHERE townId = ?");
                $townQuery->execute([$adminCharacterTownId]);
                while ($town = $townQuery->fetch())
                {
                    //On récupère le nom de la ville
                    $adminTownName = stripslashes($town['townName']);
                }
                $townQuery->closeCursor();
            }
            else
            {
                //On affiche Carte du monde
                $adminTownName = "Carte du monde";
            }

            //On récupère les équipement équippé du personnage pour l'afficher dans le menu d'information du personnage
            $equipmentEquipedQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
            WHERE itemId = inventoryItemItemId
            AND inventoryItemEquipped = 1
            AND inventoryItemCharacterId = ?");
            $equipmentEquipedQuery->execute([$adminCharacterId]);

            //On fait une boucle sur les résultats et on vérifie à chaque fois de quel type d'équipement il s'agit
            while ($equipment = $equipmentEquipedQuery->fetch())
            {
                switch ($equipment['itemType'])
                {
                    //Si il s'agit d'une armure
                    case "Armor":
                        $adminEquipmentArmorId = stripslashes($equipment['itemId']);
                        $adminEquipmentArmorName = stripslashes($equipment['itemName']);
                        $adminEquipmentArmorDescription = stripslashes($equipment['itemDescription']);
                    break;

                    //Si il s'agit de bottes
                    case "Boots":
                        $adminEquipmentBootsId = stripslashes($equipment['itemId']);
                        $adminEquipmentBootsName = stripslashes($equipment['itemName']);
                        $adminEquipmentBootsDescription = stripslashes($equipment['itemDescription']);
                    break;

                    //Si il s'agit de gants
                    case "Gloves":
                        $adminEquipmentGlovesId = stripslashes($equipment['itemId']);
                        $adminEquipmentGlovesName = stripslashes($equipment['itemName']);
                        $adminEquipmentGlovesDescription = stripslashes($equipment['itemDescription']);
                    break;

                    //Si il s'agit d'un casque
                    case "Helmet":
                        $adminEquipmentHelmetId = stripslashes($equipment['itemId']);
                        $adminEquipmentHelmetName = stripslashes($equipment['itemName']);
                        $adminEquipmentHelmetDescription = stripslashes($equipment['itemDescription']);
                    break;

                    //Si il s'agit d'une arme
                    case "Weapon":
                        $adminEquipmentWeaponId = stripslashes($equipment['itemId']);
                        $adminEquipmentWeaponName = stripslashes($equipment['itemName']);
                        $adminEquipmentWeaponDescription = stripslashes($equipment['itemDescription']);
                    break;
                }
            }

            //On cherche maintenant à voir quel équipement le personnage n'a pas d'équipé

            //Si la variable $equipmentArmorId existe pas c'est que le personnage n'en est pas équipé
            if (!isset($adminEquipmentArmorId))
            {
                $adminEquipmentArmorId = 0;
                $adminEquipmentArmorName = "Vide";
                $adminEquipmentArmorDescription = "";
            }

            //Si la variable $equipmentBootsId existe pas c'est que le personnage n'en est pas équipé
            if (!isset($adminEquipmentBootsId))
            {
                $adminEquipmentBootsId = 0;
                $adminEquipmentBootsName = "Vide";
                $adminEquipmentBootsDescription = "";
            }

            //Si la variable $equipmentGlovesId existe pas c'est que le personnage n'en est pas équipé
            if (!isset($adminEquipmentGlovesId))
            {
                $adminEquipmentGlovesId = 0;
                $adminEquipmentGlovesName = "Vide";
                $adminEquipmentGlovesDescription = "";
            }

            //Si la variable $equipmentHelmetId existe pas c'est que le personnage n'en est pas équipé
            if (!isset($adminEquipmentHelmetId))
            {
                $adminEquipmentHelmetId = 0;
                $adminEquipmentHelmetName = "Vide";
                $adminEquipmentHelmetDescription = "";
            }

            //Si la variable $equipmentWeaponId existe pas c'est que le personnage n'en est pas équipé
            if (!isset($adminEquipmentWeaponId))
            {
                $adminEquipmentWeaponId = 0;
                $adminEquipmentWeaponName = "Vide";
                $adminEquipmentWeaponDescription = "";
            }
            ?>

            <p>Informations du compte (Modifiable)</p>
            <form method="POST" action="finalEdit.php">
                Pseudo : <br> <input type="text" name="adminAccountPseudo" class="form-control" placeholder="Pseudo" value="<?php echo $adminAccountPseudo; ?>" required autofocus><br /><br />
                Email : <br> <input type="mail" name="adminAccountEmail" class="form-control" placeholder="Email" value="<?php echo $adminAccountEmail; ?>" required><br /><br />
                Accès : <br> <select name="adminAccountAccess" class="form-control">
                    
                <?php
                switch ($accountAccess)
                {
                    case 0:
                    ?>
                    <option selected="selected" value="0">Joueur</option>
                    <option value="1">Modérateur</option>
                    <option value="2">Administrateur</option>
                    <?php
                    break;

                    case 1:
                    ?>
                    <option selected="selected" value="1">Modérateur</option>
                    <option value="0">Joueur</option>
                    <option value="2">Administrateur</option>
                    <?php
                    break;

                    case 2:
                    ?>
                    <option selected="selected" value="2">Administrateur</option>
                    <option value="0">Joueur</option>
                    <option value="1">Modérateur</option>";
                    <?php
                    break;
                }
                ?>
                </select><br /><br />
                <input type="hidden" name="adminAccountId" value="<?= $adminAccountId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>

            <hr>

                <p>Informations du personnage (Non modifiable)</p>
                Race : <?php echo $adminRaceName; ?><br />
                Nom du personnage : <?php echo $adminCharacterName; ?><br />
                Niveau du personnage : <?php echo $adminCharacterLevel; ?><br />
                Armure: <?php echo $adminEquipmentArmorName; ?><br />
                Bottes: <?php echo $adminEquipmentBootsName; ?><br />
                Gants: <?php echo $adminEquipmentGlovesName; ?><br />
                Casque: <?php echo $adminEquipmentHelmetName; ?><br />
                Arme: <?php echo $adminEquipmentWeaponName; ?><br />
                Sexe du personnage: <?php echo $adminCharacterSexName; ?><br />
                HP actuel: <?php echo $adminCharacterHpMin; ?><br />
                HP maximum: <?php echo $adminCharacterHpMax; ?><br />
                HP points de compétences: <?php echo $adminCharacterHpSkillPoints; ?><br />
                HP bonus: <?php echo $adminCharacterHpBonus; ?><br />
                HP équipement: <?php echo $adminCharacterHpEquipments; ?><br />
                HP total: <?php echo $adminCharacterHpTotal; ?><br />
                MP actuel: <?php echo $adminCharacterMpMin; ?><br />
                MP maximum: <?php echo $adminCharacterMpMax; ?><br />
                MP points de compétences: <?php echo $adminCharacterMpSkillPoints; ?><br />
                MP bonus: <?php echo $adminCharacterMpBonus; ?><br />
                MP équipements: <?php echo $adminCharacterMpEquipments; ?><br />
                MP total: <?php echo $adminCharacterMpTotal; ?><br />
                Force actuelle: <?php echo $adminCharacterStrength; ?><br />
                Force points de compétences: <?php echo $adminCharacterStrengthSkillPoints; ?><br />
                Force bonus: <?php echo $adminCharacterStrengthBonus; ?><br />
                Force points de compétences: <?php echo $adminCharacterStrengthEquipments; ?><br />
                Force Total: <?php echo $adminCharacterStrengthTotal; ?><br />
                Magie actuelle: <?php echo $adminCharacterMagic; ?><br />
                Magie points de compétences: <?php echo $adminCharacterMagicSkillPoints; ?><br />
                Magie bonus: <?php echo $adminCharacterMagicBonus; ?><br />
                Magie équipement: <?php echo $adminCharacterMagicEquipments; ?><br />
                Magie Total: <?php echo $adminCharacterMagicTotal; ?><br />
                Agilité actuelle: <?php echo $adminCharacterAgility; ?><br />
                Agilité points de compétences: <?php echo $adminCharacterAgilitySkillPoints; ?><br />
                Agilité bonus: <?php echo $adminCharacterAgilityBonus; ?><br />
                Agilité équipement: <?php echo $adminCharacterAgilityEquipments; ?><br />
                Agilité Total: <?php echo $adminCharacterAgilityTotal; ?><br />
                Défense actuelle: <?php echo $adminCharacterDefense; ?><br />
                Défense points de compétences: <?php echo $adminCharacterDefenseSkillPoints; ?><br />
                Défense bonus: <?php echo $adminCharacterDefenseBonus; ?><br />
                Défense équipment: <?php echo $adminCharacterDefenseEquipment; ?><br />
                Défense Total: <?php echo $adminCharacterDefenseTotal; ?><br />
                Défense magique actuelle: <?php echo $adminCharacterDefenseMagic; ?><br />
                Défense magique points de compétences: <?php echo $adminCharacterDefenseMagicSkillPoints; ?><br />
                Défense magique bonus: <?php echo $adminCharacterDefenseMagicBonus; ?><br />
                Défense équipement: <?php echo $adminCharacterDefenseMagicEquipments; ?><br />
                Défense magique Total: <?php echo $adminCharacterDefenseMagicTotal; ?><br />
                Sagesse actuelle: <?php echo $adminCharacterWisdom; ?><br />
                Sagesse points de compétences: <?php echo $adminCharacterWisdomSkillPoints; ?><br />
                Sagesse bonus: <?php echo $adminCharacterWisdomBonus; ?><br />
                Sagesse équipement: <?php echo $adminCharacterWisdomEquipments; ?><br />
                Sagesse Total: <?php echo $adminCharacterWisdomTotal; ?><br />
                Défaite: <?php echo $adminCharacterDefeate; ?><br />
                Victoire: <?php echo $adminCharacterVictory; ?><br />
                Experience: <?php echo $adminCharacterExperience; ?><br />
                Experience total: <?php echo $adminCharacterExperienceTotal; ?><br />
                Points de compétence: <?php echo $adminCharacterSkillPoints; ?><br />
                Argent: <?php echo $adminCharacterGold; ?><br />
                Ville: <?php echo $adminTownName; ?><br />
                Chapitre: <?php echo $adminCharacterChapter; ?><br />
                En combat: <?php echo $adminCharacterOnBattleName; ?><br />
                Activé: <?php echo $adminCharacterEnable; ?><br />

                <hr>
                
                Autre options
            <form method="POST" action="delete.php">
                <input type="hidden" class="btn btn-default form-control" name="adminAccountId" value="<?= $adminAccountId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer le compte">
            </form>
            <?php
        }
        //Si le compte n'est pas disponible
        else
        {
            echo "Erreur: Compte indisponible";
        }
    }
    //Si le compte choisit n'est pas un nombre
    else
    {
        echo "Erreur: compte invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");