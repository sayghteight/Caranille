<?php
require_once("../../kernel/config.php");

//On fait une requête pour vérifier si il y a un combat en cours
$battleMonsterQuery = $bdd->prepare("SELECT * FROM car_battles_monsters, car_monsters 
WHERE battleMonsterMonsterId = monsterId
AND battleMonsterCharacterId = ?");
$battleMonsterQuery->execute([$characterId]);
$battleMonsterRow = $battleMonsterQuery->rowCount();

//Si il y a un combat de trouvé
if ($battleMonsterRow == 1)
{
    //On récupères les informations du combat (Id du combat, Id du monstre, Hp et Mp restant au monstre)
    while ($battleMonster = $battleMonsterQuery->fetch())
    {
        //On récupère les variables importante pour la gestion du combat
        $battleMonsterId = $battleMonster['battleMonsterId'];
        $battleMonsterMonsterId = $battleMonster['battleMonsterMonsterId'];
        $battleMonsterHpRemaining = $battleMonster['battleMonsterMonsterHpRemaining'];
        $battleMonsterMpRemaining = $battleMonster['battleMonsterMonsterMpRemaining'];
    }
    $battleMonsterQuery->closeCursor();

    //On récupère toutes les informations du monstre que nous sommes en train de combattre
    $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters 
    WHERE monsterId = ?");
    $monsterQuery->execute([$battleMonsterMonsterId]);

    //On boucle sur le résultat
    while ($monster = $monsterQuery->fetch())
    {
        $monsterName = $monster['monsterName'];
        $monsterDescription = $monster['monsterDescription'];
        $monsterLevel = $monster['monsterLevel'];
        $monsterHp = $monster['monsterHp'];
        $monsterMp = $monster['monsterMp'];
        $monsterStrength = $monster['monsterStrength'];
        $monsterMagic = $monster['monsterMagic'];
        $monsterAgility = $monster['monsterAgility'];
        $monsterDefense = $monster['monsterDefense'];
        $monsterDefenseMagic = $monster['monsterDefenseMagic'];
        $monsterWisdom = $monster['monsterWisdom'];
        $monsterExperience = $monster['monsterExperience'];
        $monsterGold = $monster['monsterGold'];
    }
    $monsterQuery->closeCursor();
}
?>