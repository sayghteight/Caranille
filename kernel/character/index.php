<?php
require_once("../../kernel/config.php");

//On fait une recherche dans la base de donnée pour récupérer le personnage du joueur
$characterQuery = $bdd->prepare("SELECT * FROM car_characters 
WHERE characterAccountId = ?");
$characterQuery->execute([$accountId]);

//On fait une boucle sur les résultats
while ($character = $characterQuery->fetch())
{
    $characterId = stripslashes($character['characterId']);
    $characterAccountId = stripslashes($character['characterAccountId']);
    $characterName = stripslashes($character['characterName']);
    $characterLevel = stripslashes($character['characterLevel']);
    $characterSex = stripslashes($character['characterSex']);
    $characterHpMin = stripslashes($character['characterHpMin']);
    $characterHpMax = stripslashes($character['characterHpMax']);
    $characterHpEquipments = stripslashes($character['characterHpEquipments']);
    $characterHpSkillPoints = stripslashes($character['characterHpSkillPoints']);
    $characterHpTotal = stripslashes($character['characterHpTotal']);
    $characterMpMin = stripslashes($character['characterMpMin']);
    $characterMpMax = stripslashes($character['characterMpMax']);
    $characterMpEquipments = stripslashes($character['characterMpEquipments']);
    $characterMpSkillPoints = stripslashes($character['characterMpSkillPoints']);
    $characterMpTotal = stripslashes($character['characterMpTotal']);
    $characterStrength = stripslashes($character['characterStrength']);
    $characterStrengthEquipments = stripslashes($character['characterStrengthEquipments']);
    $characterStrengthSkillPoints = stripslashes($character['characterStrengthSkillPoints']);
    $characterStrengthTotal = stripslashes($character['characterStrengthTotal']);
    $characterMagic = stripslashes($character['characterMagic']);
    $characterMagicEquipments = stripslashes($character['characterMagicEquipments']);
    $characterMagicSkillPoints = stripslashes($character['characterMagicSkillPoints']);
    $characterMagicTotal = stripslashes($character['characterMagicTotal']);
    $characterAgility = stripslashes($character['characterAgility']);
    $characterAgilityEquipments = stripslashes($character['characterAgilityEquipments']);
    $characterAgilitySkillPoints = stripslashes($character['characterAgilitySkillPoints']);
    $characterAgilityTotal = stripslashes($character['characterAgilityTotal']);
    $characterDefense = stripslashes($character['characterDefense']);
    $characterDefenseEquipment = stripslashes($character['characterDefenseEquipments']);
    $characterDefenseSkillPoints = stripslashes($character['characterDefenseSkillPoints']);
    $characterDefenseTotal = stripslashes($character['characterDefenseTotal']);
    $characterDefenseMagic = stripslashes($character['characterDefenseMagic']);
    $characterDefenseMagicEquipments = stripslashes($character['characterDefenseMagicEquipments']);
    $characterDefenseMagicSkillPoints = stripslashes($character['characterDefenseMagicSkillPoints']);
    $characterDefenseMagicTotal = stripslashes($character['characterDefenseMagicTotal']);
    $characterWisdom = stripslashes($character['characterWisdom']);
    $characterWisdomEquipments = stripslashes($character['characterWisdomEquipments']);
    $characterWisdomSkillPoints = stripslashes($character['characterWisdomSkillPoints']);
    $characterWisdomTotal = stripslashes($character['characterWisdomTotal']);
    $characterDefeate = stripslashes($character['characterDefeate']);
    $characterVictory = stripslashes($character['characterVictory']);
    $characterExperience = stripslashes($character['characterExperience']);
    $characterExperienceTotal = stripslashes($character['characterExperienceTotal']);
    $characterSkillPoints = stripslashes($character['characterSkillPoints']);
    $characterGold = stripslashes($character['characterGold']);
    $characterOnBattle = stripslashes($character['characterOnBattle']);
    $characterEnable = stripslashes($character['characterEnable']);
}
?>