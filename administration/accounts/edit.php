<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton edit
if (isset($_POST['edit']))
{
    $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

    //On fait une recherche dans la base de donnée du compte
    $accountListQuery = $bdd->prepare("SELECT * FROM car_accounts, car_characters
    WHERE accountId = characterAccountId
    AND accountId = ?");
    $accountListQuery->execute([$adminAccountId]);
    while ($accountList = $accountListQuery->fetch())
    {
        //On récupère les informations du compte
        $adminAccountId = stripslashes($accountList['accountId']);
        $adminAccountPseudo = stripslashes($accountList['accountPseudo']);
        $adminAccountEmail = stripslashes($accountList['accountEmail']);
        $adminAccountAccess = stripslashes($accountList['accountAccess']);

        //On récupère les informations du personnage
        $adminCharacterId = stripslashes($accountList['characterId']);
        $adminCharacterAccountId = stripslashes($accountList['characterAccountId']);
        $adminCharacterRaceId = stripslashes($accountList['characterRaceId']);
        $adminCharacterName = stripslashes($accountList['characterName']);
        $adminCharacterLevel = stripslashes($accountList['characterLevel']);
        $adminCharacterSex = stripslashes($accountList['characterSex']);
        $adminCharacterHpMin = stripslashes($accountList['characterHpMin']);
        $adminCharacterHpMax = stripslashes($accountList['characterHpMax']);
        $adminCharacterHpSkillPoints = stripslashes($accountList['characterHpSkillPoints']);
        $adminCharacterHpBonus = stripslashes($accountList['characterHpBonus']);
        $adminCharacterHpEquipments = stripslashes($accountList['characterHpEquipments']);
        $adminCharacterHpTotal = stripslashes($accountList['characterHpTotal']);
        $adminCharacterMpMin = stripslashes($accountList['characterMpMin']);
        $adminCharacterMpMax = stripslashes($accountList['characterMpMax']);
        $adminCharacterMpSkillPoints = stripslashes($accountList['characterMpSkillPoints']);
        $adminCharacterMpBonus = stripslashes($accountList['characterMpBonus']);
        $adminCharacterMpEquipments = stripslashes($accountList['characterMpEquipments']);
        $adminCharacterMpTotal = stripslashes($accountList['characterMpTotal']);
        $adminCharacterStrength = stripslashes($accountList['characterStrength']);
        $adminCharacterStrengthSkillPoints = stripslashes($accountList['characterStrengthSkillPoints']);
        $adminCharacterStrengthBonus = stripslashes($accountList['characterStrengthBonus']);
        $adminCharacterStrengthEquipments = stripslashes($accountList['characterStrengthEquipments']);
        $adminCharacterStrengthTotal = stripslashes($accountList['characterStrengthTotal']);
        $adminCharacterMagic = stripslashes($accountList['characterMagic']);
        $adminCharacterMagicSkillPoints = stripslashes($accountList['characterMagicSkillPoints']);
        $adminCharacterMagicBonus = stripslashes($accountList['characterMagicBonus']);
        $adminCharacterMagicEquipments = stripslashes($accountList['characterMagicEquipments']);
        $adminCharacterMagicTotal = stripslashes($accountList['characterMagicTotal']);
        $adminCharacterAgility = stripslashes($accountList['characterAgility']);
        $adminCharacterAgilitySkillPoints = stripslashes($accountList['characterAgilitySkillPoints']);
        $adminCharacterAgilityBonus = stripslashes($accountList['characterAgilityBonus']);
        $adminCharacterAgilityEquipments = stripslashes($accountList['characterAgilityEquipments']);
        $adminCharacterAgilityTotal = stripslashes($accountList['characterAgilityTotal']);
        $adminCharacterDefense = stripslashes($accountList['characterDefense']);
        $adminCharacterDefenseSkillPoints = stripslashes($accountList['characterDefenseSkillPoints']);
        $adminCharacterDefenseBonus = stripslashes($accountList['characterDefenseBonus']);
        $adminCharacterDefenseEquipment = stripslashes($accountList['characterDefenseEquipments']);
        $adminCharacterDefenseTotal = stripslashes($accountList['characterDefenseTotal']);
        $adminCharacterDefenseMagic = stripslashes($accountList['characterDefenseMagic']);
        $adminCharacterDefenseMagicSkillPoints = stripslashes($accountList['characterDefenseMagicSkillPoints']);
        $adminCharacterDefenseMagicBonus = stripslashes($accountList['characterDefenseMagicBonus']);
        $adminCharacterDefenseMagicEquipments = stripslashes($accountList['characterDefenseMagicEquipments']);
        $adminCharacterDefenseMagicTotal = stripslashes($accountList['characterDefenseMagicTotal']);
        $adminCharacterWisdom = stripslashes($accountList['characterWisdom']);
        $adminCharacterWisdomSkillPoints = stripslashes($accountList['characterWisdomSkillPoints']);
        $adminCharacterWisdomBonus = stripslashes($accountList['characterWisdomBonus']);
        $adminCharacterWisdomEquipments = stripslashes($accountList['characterWisdomEquipments']);
        $adminCharacterWisdomTotal = stripslashes($accountList['characterWisdomTotal']);
        $adminCharacterDefeate = stripslashes($accountList['characterDefeate']);
        $adminCharacterVictory = stripslashes($accountList['characterVictory']);
        $adminCharacterExperience = stripslashes($accountList['characterExperience']);
        $adminCharacterExperienceTotal = stripslashes($accountList['characterExperienceTotal']);
        $adminCharacterSkillPoints = stripslashes($accountList['characterSkillPoints']);
        $adminCharacterGold = stripslashes($accountList['characterGold']);
        $adminCharacterTownId = stripslashes($accountList['characterTownId']);
        $adminCharacterChapter = stripslashes($accountList['characterChapter']);
        $adminCharacterOnBattle = stripslashes($accountList['characterOnBattle']);
        $adminCharacterEnable = stripslashes($accountList['characterEnable']);
    }
    $accountListQuery->closeCursor();
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
        Race : <?php echo $adminCharacterRaceId; ?><br />
        Nom du personnage : <?php echo $adminCharacterName; ?><br />
        Niveau du personnage : <?php echo $adminCharacterLevel; ?><br />
        Sexe du personnage(0 = Femme, 1 = Homme) : <?php echo $adminCharacterSex; ?><br />
        HP actuel : <?php echo $adminCharacterHpMin; ?><br />
        HP maximum : <?php echo $adminCharacterHpMax; ?><br />
        HP points de compétences : <?php echo $adminCharacterHpSkillPoints; ?><br />
        HP bonus : <?php echo $adminCharacterHpBonus; ?><br />
        HP équipement : <?php echo $adminCharacterHpEquipments; ?><br />
        HP total : <?php echo $adminCharacterHpTotal; ?><br />
        MP actuel : <?php echo $adminCharacterMpMin; ?><br />
        MP maximum : <?php echo $adminCharacterMpMax; ?><br />
        MP points de compétences : <?php echo $adminCharacterMpSkillPoints; ?><br />
        MP bonus : <?php echo $adminCharacterMpBonus; ?><br />
        MP équipements : <?php echo $adminCharacterMpEquipments; ?><br />
        MP total : <?php echo $adminCharacterMpTotal; ?><br />
        Force actuelle: <?php echo $adminCharacterStrength; ?><br />
        Force points de compétences : <?php echo $adminCharacterStrengthSkillPoints; ?><br />
        Force bonus: <?php echo $adminCharacterStrengthBonus; ?><br />
        Force points de compétences : <?php echo $adminCharacterStrengthEquipments; ?><br />
        Force Total : <?php echo $adminCharacterStrengthTotal; ?><br />
        Magie actuelle : <?php echo $adminCharacterMagic; ?><br />
        Magie points de compétences : <?php echo $adminCharacterMagicSkillPoints; ?><br />
        Magie bonus : <?php echo $adminCharacterMagicBonus; ?><br />
        Magie équipement : <?php echo $adminCharacterMagicEquipments; ?><br />
        Magie Total : <?php echo $adminCharacterMagicTotal; ?><br />
        Agilité actuelle : <?php echo $adminCharacterAgility; ?><br />
        Agilité points de compétences : <?php echo $adminCharacterAgilitySkillPoints; ?><br />
        Agilité bonus : <?php echo $adminCharacterAgilityBonus; ?><br />
        Agilité équipement : <?php echo $adminCharacterAgilityEquipments; ?><br />
        Agilité Total : <?php echo $adminCharacterAgilityTotal; ?><br />
        Défense actuelle: <?php echo $adminCharacterDefense; ?><br />
        Défense points de compétences : <?php echo $adminCharacterDefenseSkillPoints; ?><br />
        Défense bonus : <?php echo $adminCharacterDefenseBonus; ?><br />
        Défense équipment : <?php echo $adminCharacterDefenseEquipment; ?><br />
        Défense Total : <?php echo $adminCharacterDefenseTotal; ?><br />
        Défense magique actuelle: <?php echo $adminCharacterDefenseMagic; ?><br />
        Défense magique points de compétences : <?php echo $adminCharacterDefenseMagicSkillPoints; ?><br />
        Défense magique bonus : <?php echo $adminCharacterDefenseMagicBonus; ?><br />
        Défense équipement : <?php echo $adminCharacterDefenseMagicEquipments; ?><br />
        Défense magique Total : <?php echo $adminCharacterDefenseMagicTotal; ?><br />
        Sagesse actuelle : <?php echo $adminCharacterWisdom; ?><br />
        Sagesse points de compétences : <?php echo $adminCharacterWisdomSkillPoints; ?><br />
        Sagesse bonus : <?php echo $adminCharacterWisdomBonus; ?><br />
        Sagesse équipement : <?php echo $adminCharacterWisdomEquipments; ?><br />
        Sagesse Total : <?php echo $adminCharacterWisdomTotal; ?><br />
        Défaite : <?php echo $adminCharacterDefeate; ?><br />
        Victoire : <?php echo $adminCharacterVictory; ?><br />
        Experience : <?php echo $adminCharacterExperience; ?><br />
        Experience total : <?php echo $adminCharacterExperienceTotal; ?><br />
        Points de compétence: <?php echo $adminCharacterSkillPoints; ?><br />
        Argent : <?php echo $adminCharacterGold; ?><br />
        Ville : <?php echo $adminCharacterTownId; ?><br />
        Chapitre : <?php echo $adminCharacterChapter; ?><br />
        En combat (0 = Non, 1 = Oui) : <?php echo $adminCharacterOnBattle; ?><br />
        Activé : <?php echo $adminCharacterEnable; ?><br />
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");