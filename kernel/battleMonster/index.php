<?php
require_once("../../kernel/config.php");

//On fait une requête pour vérifier si il y a un combat en cours
$foundBattleQuery = $bdd->prepare("SELECT * FROM car_battles_monsters, car_monsters 
WHERE battleMonsterMonsterId = monsterId
AND battleMonsterCharacterId = ?");
$foundBattleQuery->execute([$characterId]);
$foundBattleMonster = $foundBattleQuery->rowCount();

//Si il y a un combat de trouvé
if ($foundBattleMonster == 1)
{
    //On boucle sur le résultat
    while ($foundBattle = $foundBattleQuery->fetch())
    {
        //On récupère les variables importante pour la gestion du combat
        $battleMonsterId = $foundBattle['battleMonsterId'];
        $battleMonsterName = $foundBattle['monsterName'];
        $battleMonsterDescription = $foundBattle['monsterDescription'];
        $battleMonsterLevel = $foundBattle['monsterLevel'];
        $battleMonsterHpRemaining = $foundBattle['battleMonsterMonsterHpRemaining'];
        $battleMonsterHpTotal = $foundBattle['monsterHp'];
        $battleMonsterMpRemaining = $foundBattle['battleMonsterMonsterMpRemaining'];
        $battleMonsterMpTotal = $foundBattle['monsterMp'];
        $battleMonsterExperience = $foundBattle['monsterExperience'];
        $battleMonsterGold = $foundBattle['monsterGold'];
    }
}
?>