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
    $accountListQuery = $bdd->prepare("SELECT * FROM car_accounts
    WHERE accountId = ?");
    $accountListQuery->execute([$adminAccountId]);
    while ($accountList = $accountListQuery->fetch())
    {
        $adminAccountId = stripslashes($accountList['accountId']);
        $adminAccountPseudo = stripslashes($accountList['accountPseudo']);
        $adminAccountEmail = stripslashes($accountList['accountEmail']);
        $adminAccountAccess = stripslashes($accountList['accountAccess']);
    }
    $accountListQuery->closeCursor();

    //On fait une recherche dans la base de donnée du personnage du compte
    $characterListQuery = $bdd->prepare("SELECT * FROM car_characters
    WHERE characterAccountId = ?");
    $characterListQuery->execute([$adminAccountId]);
    while ($characterList = $characterListQuery->fetch())
    {
        $adminCharacterId = stripslashes($characterList['characterId']);
        $adminCharacterAccountId = stripslashes($characterList['characterAccountId']);
        $adminCharacterRaceId = stripslashes($characterList['characterRaceId']);
        $adminCharacterName = stripslashes($characterList['characterName']);
        $adminCharacterLevel = stripslashes($characterList['characterLevel']);
        $adminCharacterSex = stripslashes($characterList['characterSex']);
        $adminCharacterHpMin = stripslashes($characterList['characterHpMin']);
        $adminCharacterHpMax = stripslashes($characterList['characterHpMax']);
        $adminCharacterHpSkillPoints = stripslashes($characterList['characterHpSkillPoints']);
        $adminCharacterHpBonus = stripslashes($characterList['characterHpBonus']);
        $adminCharacterHpEquipments = stripslashes($characterList['characterHpEquipments']);
        $adminCharacterHpTotal = stripslashes($characterList['characterHpTotal']);
        $adminCharacterMpMin = stripslashes($characterList['characterMpMin']);
        $adminCharacterMpMax = stripslashes($characterList['characterMpMax']);
        $adminCharacterMpSkillPoints = stripslashes($characterList['characterMpSkillPoints']);
        $adminCharacterMpBonus = stripslashes($characterList['characterMpBonus']);
        $adminCharacterMpEquipments = stripslashes($characterList['characterMpEquipments']);
        $adminCharacterMpTotal = stripslashes($characterList['characterMpTotal']);
        $adminCharacterStrength = stripslashes($characterList['characterStrength']);
        $adminCharacterStrengthSkillPoints = stripslashes($characterList['characterStrengthSkillPoints']);
        $adminCharacterStrengthBonus = stripslashes($characterList['characterStrengthBonus']);
        $adminCharacterStrengthEquipments = stripslashes($characterList['characterStrengthEquipments']);
        $adminCharacterStrengthTotal = stripslashes($characterList['characterStrengthTotal']);
        $adminCharacterMagic = stripslashes($characterList['characterMagic']);
        $adminCharacterMagicSkillPoints = stripslashes($characterList['characterMagicSkillPoints']);
        $adminCharacterMagicBonus = stripslashes($characterList['characterMagicBonus']);
        $adminCharacterMagicEquipments = stripslashes($characterList['characterMagicEquipments']);
        $adminCharacterMagicTotal = stripslashes($characterList['characterMagicTotal']);
        $adminCharacterAgility = stripslashes($characterList['characterAgility']);
        $adminCharacterAgilitySkillPoints = stripslashes($characterList['characterAgilitySkillPoints']);
        $adminCharacterAgilityBonus = stripslashes($characterList['characterAgilityBonus']);
        $adminCharacterAgilityEquipments = stripslashes($characterList['characterAgilityEquipments']);
        $adminCharacterAgilityTotal = stripslashes($characterList['characterAgilityTotal']);
        $adminCharacterDefense = stripslashes($characterList['characterDefense']);
        $adminCharacterDefenseSkillPoints = stripslashes($characterList['characterDefenseSkillPoints']);
        $adminCharacterDefenseBonus = stripslashes($characterList['characterDefenseBonus']);
        $adminCharacterDefenseEquipment = stripslashes($characterList['characterDefenseEquipments']);
        $adminCharacterDefenseTotal = stripslashes($characterList['characterDefenseTotal']);
        $adminCharacterDefenseMagic = stripslashes($characterList['characterDefenseMagic']);
        $adminCharacterDefenseMagicSkillPoints = stripslashes($characterList['characterDefenseMagicSkillPoints']);
        $adminCharacterDefenseMagicBonus = stripslashes($characterList['characterDefenseMagicBonus']);
        $adminCharacterDefenseMagicEquipments = stripslashes($characterList['characterDefenseMagicEquipments']);
        $adminCharacterDefenseMagicTotal = stripslashes($characterList['characterDefenseMagicTotal']);
        $adminCharacterWisdom = stripslashes($characterList['characterWisdom']);
        $adminCharacterWisdomSkillPoints = stripslashes($characterList['characterWisdomSkillPoints']);
        $adminCharacterWisdomBonus = stripslashes($characterList['characterWisdomBonus']);
        $adminCharacterWisdomEquipments = stripslashes($characterList['characterWisdomEquipments']);
        $adminCharacterWisdomTotal = stripslashes($characterList['characterWisdomTotal']);
        $adminCharacterDefeate = stripslashes($characterList['characterDefeate']);
        $adminCharacterVictory = stripslashes($characterList['characterVictory']);
        $adminCharacterExperience = stripslashes($characterList['characterExperience']);
        $adminCharacterExperienceTotal = stripslashes($characterList['characterExperienceTotal']);
        $adminCharacterSkillPoints = stripslashes($characterList['characterSkillPoints']);
        $adminCharacterGold = stripslashes($characterList['characterGold']);
        $adminCharacterTownId = stripslashes($characterList['characterTownId']);
        $adminCharacterChapter = stripslashes($characterList['characterChapter']);
        $adminCharacterOnBattle = stripslashes($characterList['characterOnBattle']);
        $adminCharacterEnable = stripslashes($characterList['characterEnable']);
    }
    $characterListQuery->closeCursor();
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