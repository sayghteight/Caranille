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
    //On récupères les informations du combat (Id du combat, Id du monstre, Hp et Mp restant au monstre)
    while ($foundBattle = $foundBattleQuery->fetch())
    {
        //On récupère les variables importante pour la gestion du combat
        $battleMonsterId = $foundBattle['battleMonsterId'];
        $battleMonsterMonsterId = $foundBattle['battleMonsterMonsterId'];
        $battleMonsterHpRemaining = $foundBattle['battleMonsterMonsterHpRemaining'];
        $battleMonsterMpRemaining = $foundBattle['battleMonsterMonsterMpRemaining'];
    }

    //On récupère toutes les informations du monstre que nous sommes en train de combattre
    $foundMonsterQuery = $bdd->prepare("SELECT * FROM car_monsters 
    WHERE monsterId = ?");
    $foundMonsterQuery->execute([$battleMonsterMonsterId]);

    //On boucle sur le résultat
    while ($foundMonster = $foundMonsterQuery->fetch())
    {
        $monsterName = $foundMonster['monsterName'];
        $monsterDescription = $foundMonster['monsterDescription'];
        $monsterLevel = $foundMonster['monsterLevel'];
        $monsterHp = $foundMonster['monsterHp'];
        $monsterMp = $foundMonster['monsterMp'];
        $monsterStrength = $foundMonster['monsterStrength'];
        $monsterMagic = $foundMonster['monsterMagic'];
        $monsterAgility = $foundMonster['monsterAgility'];
        $monsterDefense = $foundMonster['monsterDefense'];
        $monsterDefenseMagic = $foundMonster['monsterDefenseMagic'];
        $monsterWisdom = $foundMonster['monsterWisdom'];
        $monsterExperience = $foundMonster['monsterExperience'];
        $monsterGold = $foundMonster['monsterGold'];
    }
}
?>