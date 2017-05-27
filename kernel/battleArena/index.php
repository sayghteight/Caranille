<?php
require_once("../../kernel/config.php");

//On fait une requête pour vérifier si il y a un combat en cours
$foundBattleQuery = $bdd->prepare("SELECT * FROM car_battles_arenas 
WHERE battleCharacterOneId = ?
OR battleCharacterTwoId = ?");
$foundBattleQuery->execute([$characterId, $characterId]);
$foundBattleArena = $foundBattleQuery->rowCount();

//Si il y a un combat de trouvé
if ($foundBattleArena == 1)
{
    //On boucle sur le résultat
    while ($foundBattle = $foundBattleQuery->fetch())
    {
        //On récupère les variables importante pour la gestion du combat
        $battleId = $foundBattle['battleId'];
        $damagesPlayerOne = $foundBattle['battleCharacterOneDamage'];
        $damagesPlayerTwo = $foundBattle['battleCharacterTwoDamage'];
        $playerOneStep = $foundBattle['battleCharacterOneStep'];
        $playerTwoStep = $foundBattle['battleCharacterTwoStep'];

        //Si l'Id du dresseur est égale à battleCharacterOneId, nous sommes le joueurs 1
        if ($foundBattle['battleCharacterOneId'] == $CharacterId)
        {
            $battlePlayer = 1;
            $battleOpponent = $foundBattle['battleCharacterTwoId'];
        }
        //Si l'Id du dresseur est égale à battleCharacterTwoId, nous sommes le joueurs 2
        elseif ($foundBattle['battleCharacterTwoId'] == $CharacterId)
        {
            $battlePlayer = 2;
            $battleOpponent = $foundBattle['battleCharacterOneId'];
        }
    }

    //On recherche le personnage à combattre dans la base de donnée avec son Id
    $opponentQuery = $bdd->prepare("SELECT * FROM car_characters 
    WHERE characterId = ?");
    $opponentQuery->execute([$battleOpponent]);

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
}
?>