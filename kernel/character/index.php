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

$racerQuery = $bdd->prepare("SELECT * FROM car_races 
WHERE raceId = ?");
$racerQuery->execute([$characterRaceId]);

//On fait une boucle sur les résultats
while ($race = $racerQuery->fetch())
{
    $characterRaceName = stripslashes($race['raceName']);
}

//Valeurs des statistiques qui seront ajoutée à la monté d'un niveau
$levelBaseExperience = 5000;
$hPByLevel = 10;
$mPByLevel = 1;
$strengthByLevel = 1;
$magicByLevel = 1;
$agilityByLevel = 1;
$defenseByLevel = 1;
$defenseMagicByLevel = 1;
$wisdomByLevel = 0;
$skillPointsByLevel = 4;
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
}

//On met le personnage à jour
$updatecharacter = $bdd->prepare("UPDATE car_characters SET
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

$updatecharacter->execute(array(
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

?>