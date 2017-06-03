<?php
require_once("../../kernel/config.php");

//On fait une requête pour vérifier si il y a un combat en cours
$battleArenaQuery = $bdd->prepare("SELECT * FROM car_battles_arenas, car_characters 
WHERE battleArenaCharacterId = characterId
AND battleArenaCharacterId = ?");
$battleArenaQuery->execute([$characterId]);
$battleArenaRow = $battleArenaQuery->rowCount();

//Si il y a un combat de trouvé
if ($battleArenaRow == 1)
{
    //On boucle sur le résultat
    while ($battleArena = $battleArenaQuery->fetch())
    {
        $battleArenaId = $battleArena['battleArenaId'];
        $battleArenaOpponentCharacterId = $battleArena['battleArenaOpponentCharacterId'];
        $battleArenaOpponentCharacterHpRemaining = $battleArena['battleArenaOpponentCharacterHpRemaining'];
        $battleArenaOpponentCharacterMpRemaining = $battleArena['battleArenaOpponentCharacterMpRemaining'];
    }
    $battleArenaQuery->closeCursor();

    //On recherche le personnage à combattre dans la base de donnée avec son Id
    $opponentQuery = $bdd->prepare("SELECT * FROM car_characters 
    WHERE characterId = ?");
    $opponentQuery->execute([$battleArenaOpponentCharacterId]);

    //On fait une boucle pour récupérer les résultats
    while ($opponent = $opponentQuery->fetch())
    {
        $opponentCharacterId = stripslashes($opponent['characterId']);
        $opponentCharacterAccountId = stripslashes($opponent['characterAccountId']);
        $opponentCharacterRaceId = stripslashes($opponent['characterRaceId']);
        $opponentCharacterName = stripslashes($opponent['characterName']);
        $opponentCharacterLevel = stripslashes($opponent['characterLevel']);
        $opponentCharacterSex = stripslashes($opponent['characterSex']);
        $opponentCharacterHpMin = stripslashes($opponent['characterHpMin']);
        $opponentCharacterHpMax = stripslashes($opponent['characterHpMax']);
        $opponentCharacterHpEquipments = stripslashes($opponent['characterHpEquipments']);
        $opponentCharacterHpSkillPoints = stripslashes($opponent['characterHpSkillPoints']);
        $opponentCharacterHpTotal = stripslashes($opponent['characterHpTotal']);
        $opponentCharacterMpMin = stripslashes($opponent['characterMpMin']);
        $opponentCharacterMpMax = stripslashes($opponent['characterMpMax']);
        $opponentCharacterMpEquipments = stripslashes($opponent['characterMpEquipments']);
        $opponentCharacterMpSkillPoints = stripslashes($opponent['characterMpSkillPoints']);
        $opponentCharacterMpTotal = stripslashes($opponent['characterMpTotal']);
        $opponentCharacterStrength = stripslashes($opponent['characterStrength']);
        $opponentCharacterStrengthEquipments = stripslashes($opponent['characterStrengthEquipments']);
        $opponentCharacterStrengthSkillPoints = stripslashes($opponent['characterStrengthSkillPoints']);
        $opponentCharacterStrengthTotal = stripslashes($opponent['characterStrengthTotal']);
        $opponentCharacterMagic = stripslashes($opponent['characterMagic']);
        $opponentCharacterMagicEquipments = stripslashes($opponent['characterMagicEquipments']);
        $opponentCharacterMagicSkillPoints = stripslashes($opponent['characterMagicSkillPoints']);
        $opponentCharacterMagicTotal = stripslashes($opponent['characterMagicTotal']);
        $opponentCharacterAgility = stripslashes($opponent['characterAgility']);
        $opponentCharacterAgilityEquipments = stripslashes($opponent['characterAgilityEquipments']);
        $opponentCharacterAgilitySkillPoints = stripslashes($opponent['characterAgilitySkillPoints']);
        $opponentCharacterAgilityTotal = stripslashes($opponent['characterAgilityTotal']);
        $opponentCharacterDefense = stripslashes($opponent['characterDefense']);
        $opponentCharacterDefenseEquipment = stripslashes($opponent['characterDefenseEquipments']);
        $opponentCharacterDefenseSkillPoints = stripslashes($opponent['characterDefenseSkillPoints']);
        $opponentCharacterDefenseTotal = stripslashes($opponent['characterDefenseTotal']);
        $opponentCharacterDefenseMagic = stripslashes($opponent['characterDefenseMagic']);
        $opponentCharacterDefenseMagicEquipments = stripslashes($opponent['characterDefenseMagicEquipments']);
        $opponentCharacterDefenseMagicSkillPoints = stripslashes($opponent['characterDefenseMagicSkillPoints']);
        $opponentCharacterDefenseMagicTotal = stripslashes($opponent['characterDefenseMagicTotal']);
        $opponentCharacterWisdom = stripslashes($opponent['characterWisdom']);
        $opponentCharacterWisdomEquipments = stripslashes($opponent['characterWisdomEquipments']);
        $opponentCharacterWisdomSkillPoints = stripslashes($opponent['characterWisdomSkillPoints']);
        $opponentCharacterWisdomTotal = stripslashes($opponent['characterWisdomTotal']);
        $opponentCharacterDefeate = stripslashes($opponent['characterDefeate']);
        $opponentCharacterVictory = stripslashes($opponent['characterVictory']);
        $opponentCharacterExperience = stripslashes($opponent['characterExperience']);
        $opponentCharacterExperienceTotal = stripslashes($opponent['characterExperienceTotal']);
        $opponentCharacterSkillPoints = stripslashes($opponent['characterSkillPoints']);
        $opponentCharacterGold = stripslashes($opponent['characterGold']);
        $opponentCharacterTownId = stripslashes($opponent['characterTownId']);
        $opponentCharacterChapter = stripslashes($opponent['characterChapter']);
        $opponentCharacterOnBattle = stripslashes($opponent['characterOnBattle']);
        $opponentCharacterEnable = stripslashes($opponent['characterEnable']);
    }
    $opponentQuery->closeCursor();
}
?>