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
    $characterHpSkillPoints = stripslashes($character['characterHpSkillPoints']);
    $characterHpBonus = stripslashes($character['characterHpBonus']);
    $characterHpEquipments = stripslashes($character['characterHpEquipments']);
    $characterHpTotal = stripslashes($character['characterHpTotal']);
    $characterMpMin = stripslashes($character['characterMpMin']);
    $characterMpMax = stripslashes($character['characterMpMax']);
    $characterMpSkillPoints = stripslashes($character['characterMpSkillPoints']);
    $characterMpBonus = stripslashes($character['characterMpBonus']);
    $characterMpEquipments = stripslashes($character['characterMpEquipments']);
    $characterMpTotal = stripslashes($character['characterMpTotal']);
    $characterStrength = stripslashes($character['characterStrength']);
    $characterStrengthSkillPoints = stripslashes($character['characterStrengthSkillPoints']);
    $characterStrengthBonus = stripslashes($character['characterStrengthBonus']);
    $characterStrengthEquipments = stripslashes($character['characterStrengthEquipments']);
    $characterStrengthTotal = stripslashes($character['characterStrengthTotal']);
    $characterMagic = stripslashes($character['characterMagic']);
    $characterMagicSkillPoints = stripslashes($character['characterMagicSkillPoints']);
    $characterMagicBonus = stripslashes($character['characterMagicBonus']);
    $characterMagicEquipments = stripslashes($character['characterMagicEquipments']);
    $characterMagicTotal = stripslashes($character['characterMagicTotal']);
    $characterAgility = stripslashes($character['characterAgility']);
    $characterAgilitySkillPoints = stripslashes($character['characterAgilitySkillPoints']);
    $characterAgilityBonus = stripslashes($character['characterAgilityBonus']);
    $characterAgilityEquipments = stripslashes($character['characterAgilityEquipments']);
    $characterAgilityTotal = stripslashes($character['characterAgilityTotal']);
    $characterDefense = stripslashes($character['characterDefense']);
    $characterDefenseSkillPoints = stripslashes($character['characterDefenseSkillPoints']);
    $characterDefenseBonus = stripslashes($character['characterDefenseBonus']);
    $characterDefenseEquipment = stripslashes($character['characterDefenseEquipments']);
    $characterDefenseTotal = stripslashes($character['characterDefenseTotal']);
    $characterDefenseMagic = stripslashes($character['characterDefenseMagic']);
    $characterDefenseMagicSkillPoints = stripslashes($character['characterDefenseMagicSkillPoints']);
    $characterDefenseMagicBonus = stripslashes($character['characterDefenseMagicBonus']);
    $characterDefenseMagicEquipments = stripslashes($character['characterDefenseMagicEquipments']);
    $characterDefenseMagicTotal = stripslashes($character['characterDefenseMagicTotal']);
    $characterWisdom = stripslashes($character['characterWisdom']);
    $characterWisdomSkillPoints = stripslashes($character['characterWisdomSkillPoints']);
    $characterWisdomBonus = stripslashes($character['characterWisdomBonus']);
    $characterWisdomEquipments = stripslashes($character['characterWisdomEquipments']);
    $characterWisdomTotal = stripslashes($character['characterWisdomTotal']);
    $characterDefeate = stripslashes($character['characterDefeate']);
    $characterVictory = stripslashes($character['characterVictory']);
    $characterExperience = stripslashes($character['characterExperience']);
    $characterExperienceTotal = stripslashes($character['characterExperienceTotal']);
    $characterSkillPoints = stripslashes($character['characterSkillPoints']);
    $characterGold = stripslashes($character['characterGold']);
    $characterTownId = stripslashes($character['characterTownId']);
    $characterChapter = stripslashes($character['characterChapter']);
    $characterOnBattle = stripslashes($character['characterOnBattle']);
    $characterEnable = stripslashes($character['characterEnable']);
}
$characterQuery->closeCursor();

//Valeurs des statistiques qui seront ajoutée à la monté d'un niveau
$levelBaseExperience = 500;
$skillPointsByLevel = 2;
$experienceLevel = $characterLevel * $levelBaseExperience;
$experienceRemaining = $characterLevel * $levelBaseExperience - $characterExperience;

//Si le personnage à suffisament d'experience pour monter de niveau
if ($characterExperience >= $experienceLevel)
{
    $characterHpMin = $characterHpMin + 10;
    $characterHpMax = $characterHpMax + 1;
    $characterHpTotal = $characterHpTotal + 1;
    $characterMpMin = $characterMpMin + 1;
    $characterMpMax = $characterMpMax + 1;
    $characterMpTotal = $characterMpTotal + 1;
    $characterStrength = $characterStrength + 1;
    $characterStrengthTotal = $characterStrengthTotal + 1;
    $characterMagic = $characterMagic + 1;
    $characterMagicTotal = $characterMagicTotal + 1;
    $characterAgility = $characterAgility + 1;
    $characterAgilityTotal = $characterAgilityTotal + 1;
    $characterDefense = $characterDefense + 1;
    $characterDefenseTotal = $characterDefenseTotal + 1;
    $characterDefenseMagic = $characterDefenseMagic + 1;
    $characterDefenseMagicTotal = $characterDefenseMagicTotal + 1;
    $characterWisdom = $characterWisdom + 1;
    $characterWisdomTotal = $characterWisdomTotal + 1;
    $characterExperience = $characterExperience - $experienceLevel;
    $characterSkillPoints = $characterSkillPoints + $skillPointsByLevel;
    $characterLevel = $characterLevel + 1;
    echo "<script>alert(\"Votre personnage vient de gagner un niveau !\");</script>";

    //On met le personnage à jour si il gagne un niveau
    $updateCharacter = $bdd->prepare("UPDATE car_characters SET
    characterLevel = :characterLevel,
    characterHpMin = :characterHpMin, 
    characterHpMax = :characterHpMax, 
    characterHpTotal = :characterHpTotal, 
    characterMpMin = :characterMpMin, 
    characterMpMax = :characterMpMax, 
    characterMpTotal = :characterMpTotal, 
    characterStrength = :characterStrength, 
    characterStrengthTotal = :characterStrengthTotal, 
    characterMagic = :characterMagic, 
    characterMagicTotal = :characterMagicTotal, 
    characterAgility = :characterAgility, 
    characterAgilityTotal = :characterAgilityTotal, 
    characterDefense = :characterDefense, 
    characterDefenseTotal = :characterDefenseTotal, 
    characterDefenseMagic = :characterDefenseMagic, 
    characterDefenseMagicTotal = :characterDefenseMagicTotal, 
    characterWisdom = :characterWisdom, 
    characterWisdomTotal = :characterWisdomTotal, 
    characterExperience = :characterExperience, 
    characterExperienceTotal = :characterExperienceTotal,
    characterSkillPoints = :characterSkillPoints
    WHERE characterId= :characterId");

    $updateCharacter->execute(array(
    'characterLevel' => $characterLevel,  
    'characterHpMin' => $characterHpMin, 
    'characterHpMax' => $characterHpMax, 
    'characterHpTotal' => $characterHpTotal, 
    'characterMpMin' => $characterMpMin, 
    'characterMpMax' => $characterMpMax, 
    'characterMpTotal' => $characterMpTotal, 
    'characterStrength' => $characterStrength, 
    'characterStrengthTotal' => $characterStrengthTotal, 
    'characterMagic' => $characterMagic, 
    'characterMagicTotal' => $characterMagicTotal, 
    'characterAgility' => $characterAgility, 
    'characterAgilityTotal' => $characterAgilityTotal, 
    'characterDefense' => $characterDefense, 
    'characterDefenseTotal' => $characterDefenseTotal, 
    'characterDefenseMagic' => $characterDefenseMagic, 
    'characterDefenseMagicTotal' => $characterDefenseMagicTotal, 
    'characterWisdom' => $characterWisdom, 
    'characterWisdomTotal' => $characterWisdomTotal, 
    'characterExperience' => $characterExperience, 
    'characterExperienceTotal' => $characterExperienceTotal, 
    'characterSkillPoints' => $characterSkillPoints, 
    'characterId' => $characterId));
    $updateCharacter->closeCursor();
}
?>
