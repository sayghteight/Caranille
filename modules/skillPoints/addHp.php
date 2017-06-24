<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['addHp']))
{
    if ($characterSkillPoints > 0)
    {
        //On met la stats à jour
        $updateCharacter = $bdd->prepare('UPDATE car_characters 
        SET characterHpSkillPoints = characterHpSkillPoints + 1,
        characterSkillPoints = characterSkillPoints -1
        WHERE characterId = :characterId');
        $updateCharacter->execute(['characterId' => $characterId]);
        $updateCharacter->closeCursor();

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

        header("Location: index.php");
    }
    else
    {
        echo "Vous n'avez pas assez de points de compétences";
        ?>
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" value="Retour">
        </form>
        <?php
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>