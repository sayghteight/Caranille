<?php 
require_once("../../html/header.php");
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }

if ($characterSkillPoints > 0)
{
    //On met la stats à jour
    $updateCharacter = $bdd->prepare('UPDATE car_characters 
    SET characterStrengthSkillPoints = characterStrengthSkillPoints + 1,
    characterSkillPoints = characterSkillPoints -1
    WHERE characterId = :characterId');
    $updateCharacter->execute(['characterId' => $characterId]);
    $updateCharacter->closeCursor();

    $updateCharacter = $bdd->prepare('UPDATE car_characters
    SET characterHpTotal = characterHpMax + characterHpSkillPoints + characterHpParchment + characterHpEquipments,
    characterMpTotal = characterMpMax + characterMpSkillPoints + characterMpParchment + characterMpEquipments,
    characterStrengthTotal = characterStrength + characterStrengthSkillPoints + characterStrengthParchment + characterStrengthEquipments,
    characterMagicTotal = characterMagic + characterMagicSkillPoints + characterMagicParchment + characterMagicEquipments,
    characterAgilityTotal = characterAgility + characterAgilitySkillPoints + characterAgilityParchment + characterAgilityEquipments,
    characterDefenseTotal = characterDefense + characterDefenseSkillPoints + characterDefenseParchment + characterDefenseEquipments,
    characterDefenseMagicTotal = characterDefenseMagic + characterDefenseMagicSkillPoints + characterDefenseMagicParchment + characterDefenseMagicEquipments,
    characterWisdomTotal = characterWisdom + characterWisdomSkillPoints + characterWisdomParchment + characterWisdomEquipments
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

require_once("../../html/footer.php"); ?>