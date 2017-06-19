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
    $characterRaceId = stripslashes($character['characterRaceId']);
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

//On fait une recherche dans la base de donnée pour récupérer la race du personnage
$racerQuery = $bdd->prepare("SELECT * FROM car_races 
WHERE raceId = ?");
$racerQuery->execute([$characterRaceId]);

//On fait une boucle sur les résultats
while ($race = $racerQuery->fetch())
{
    $characterRaceName = stripslashes($race['raceName']);
    $raceHpBonus = stripslashes($race['raceHpBonus']);
    $raceMpBonus = stripslashes($race['raceMpBonus']);
    $raceStrengthBonus = stripslashes($race['raceStrengthBonus']);
    $raceMagicBonus = stripslashes($race['raceMagicBonus']);
    $raceAgilityBonus = stripslashes($race['raceAgilityBonus']);
    $raceDefenseBonus = stripslashes($race['raceDefenseBonus']);
    $raceDefenseMagicBonus = stripslashes($race['raceDefenseMagicBonus']);
    $raceWidsomBonus = stripslashes($race['raceWidsomBonus']);
}
$racerQuery->closeCursor();

//Valeurs des statistiques qui seront ajoutée à la monté d'un niveau
$levelBaseExperience = 500;
$hPByLevel = $raceHpBonus;
$mPByLevel = $raceMpBonus;
$strengthByLevel = $raceStrengthBonus;
$magicByLevel = $raceMagicBonus;
$agilityByLevel = $raceAgilityBonus;
$defenseByLevel = $raceDefenseBonus;
$defenseMagicByLevel = $raceDefenseMagicBonus;
$wisdomByLevel = $raceWidsomBonus;
$skillPointsByLevel = 2;
$experienceLevel = $characterLevel * $levelBaseExperience;
$experienceRemaining = $characterLevel * $levelBaseExperience - $characterExperience;

//Si le personnage à suffisament d'experience pour monter de niveau
if ($characterExperience >= $experienceLevel)
{
    $characterHpMin = $characterHpMin + $hPByLevel;
    $characterHpMax = $characterHpMax + $hPByLevel;
    $characterHpTotal = $characterHpTotal + $hPByLevel;
    $characterMpMin = $characterMpMin + $mPByLevel;
    $characterMpMax = $characterMpMax + $mPByLevel;
    $characterMpTotal = $characterMpTotal + $mPByLevel;
    $characterStrength = $characterStrength + $strengthByLevel;
    $characterStrengthTotal = $characterStrengthTotal + $strengthByLevel;
    $characterMagic = $characterMagic + $magicByLevel;
    $characterMagicTotal = $characterMagicTotal + $magicByLevel;
    $characterAgility = $characterAgility + $agilityByLevel;
    $characterAgilityTotal = $characterAgilityTotal + $agilityByLevel;
    $characterDefense = $characterDefense + $defenseByLevel;
    $characterDefenseTotal = $characterDefenseTotal + $defenseByLevel;
    $characterDefenseMagic = $characterDefenseMagic + $defenseMagicByLevel;
    $characterDefenseMagicTotal = $characterDefenseMagicTotal + $defenseMagicByLevel;
    $characterWisdom = $characterWisdom + $wisdomByLevel;
    $characterWisdomTotal = $characterWisdomTotal + $wisdomByLevel;
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
