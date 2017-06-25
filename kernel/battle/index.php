<?php
require_once("../../kernel/config.php");

//On fait une requête pour vérifier si il y a un combat en cours
$battleQuery = $bdd->prepare("SELECT * FROM car_battles
WHERE battleCharacterId = ?");
$battleQuery->execute([$characterId]);
$battleRow = $battleQuery->rowCount();

//Si il y a un combat de trouvé
if ($battleRow == 1)
{
    //On récupères les informations du combat (Id du combat, Id du monstre, Hp et Mp restant au monstre)
    while ($battle = $battleQuery->fetch())
    {
        //On récupère les variables importante pour la gestion du combat
        $battleId = $battle['battleId'];
        $battleOpponentId = $battle['battleOpponentId'];
        $battleType = $battle['battleType'];
        $battleOpponentHpRemaining = $battle['battleOpponentHpRemaining'];
        $battleOpponentMpRemaining = $battle['battleOpponentMpRemaining'];
    }
    $battleQuery->closeCursor();
    
    //S'il s'agit d'un combat de Donjon, de mission ou d'histoire
    if ($battleType == "Dungeon"
    || $battleType == "Mission"
    || $battleType == "Story")
    {
        //On récupère toutes les informations du monstre que nous sommes en train de combattre
        $opponentQuery = $bdd->prepare("SELECT * FROM car_monsters 
        WHERE monsterId = ?");
        $opponentQuery->execute([$battleOpponentId]);
    
        //On fait une boucle pour récupérer les résultats
        while ($opponent = $opponentQuery->fetch())
        {
            $opponentId = stripslashes($opponent['monsterId']);
            $opponentName = stripslashes($opponent['monsterName']);
            $opponentLevel = stripslashes($opponent['monsterLevel']);
            $opponentHp = stripslashes($opponent['monsterHp']);
            $opponentMp = stripslashes($opponent['monsterMp']);
            $opponentStrength = stripslashes($opponent['monsterStrength']);
            $opponentMagic = stripslashes($opponent['monsterMagic']);
            $opponentAgility = stripslashes($opponent['monsterAgility']);
            $opponentDefense = stripslashes($opponent['monsterDefense']);
            $opponentDefenseMagic = stripslashes($opponent['monsterDefenseMagic']);
            $opponentWisdomTotal = stripslashes($opponent['monsterWisdom']);
            $opponentGold = stripslashes($opponent['monsterGold']);
            $opponentExperience = stripslashes($opponent['monsterExperience']);
        }
        $opponentQuery->closeCursor();
    }
    //Si il s'agit d'un combat contre un joueur
    else if ($battleType == "Arena")
    {
        //On récupère toutes les informations du personnage que nous sommes en train de combattre
        $opponentQuery = $bdd->prepare("SELECT * FROM car_characters 
        WHERE characterId = ?");
        $opponentQuery->execute([$battleOpponentId]);
    
        //On fait une boucle pour récupérer les résultats
        while ($opponent = $opponentQuery->fetch())
        {
            $opponentId = stripslashes($opponent['characterId']);
            $opponentName = stripslashes($opponent['characterName']);
            $opponentLevel = stripslashes($opponent['characterLevel']);
            $opponentHp = stripslashes($opponent['characterHpTotal']);
            $opponentMp = stripslashes($opponent['characterMpTotal']);
            $opponentStrength = stripslashes($opponent['characterStrengthTotal']);
            $opponentMagic = stripslashes($opponent['characterMagicTotal']);
            $opponentAgility = stripslashes($opponent['characterAgilityTotal']);
            $opponentDefense = stripslashes($opponent['characterDefenseTotal']);
            $opponentDefenseMagic = stripslashes($opponent['characterDefenseMagicTotal']);
            $opponentWisdomT = stripslashes($opponent['characterWisdomTotal']);
        }
        $opponentQuery->closeCursor();
    }
}
?>