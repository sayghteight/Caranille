<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un monstre on redirige le joueur vers le module dungeon
if ($foundBattleMonster == 0) { exit(header("Location: ../../modules/dungeon/index.php")); }

if (isset($_POST['magic']))
{
    /*
    VARIABLES GLOBALES
    */
    $characterMinStrength = $characterStrengthTotal / 1.1;
    $characterMaxStrength = $characterStrengthTotal * 1.1;    
    $characterMinMagic = $characterMagicTotal / 1.1;
    $characterMaxMagic = $characterMagicTotal * 1.1;

    $monsterMinStrength = $monsterStrength / 1.1;
    $monsterMaxStrength = $monsterStrength * 1.1;    
    $monsterMinMagic = $monsterMagic / 1.1;
    $monsterMaxMagic = $monsterMagic * 1.1;

    $monsterMinDefense = $monsterDefense / 1.1;
    $monsterMaxDefense = $monsterDefense * 1.1;
    $monsterMinDefenseMagic = $monsterDefenseMagic / 1.1;
    $monsterMaxDefenseMagic = $monsterDefenseMagic * 1.1;

    $characterMinDefense = $characterDefenseTotal / 1.1;
    $characterMaxDefense = $characterDefenseTotal * 1.1;
    $characterMinDefenseMagic = $characterDefenseMagicTotal / 1.1;
    $characterMaxDefenseMagic = $characterDefenseMagicTotal * 1.1;

    //On calcule les dégats du joueur
    $positiveDamagePlayer = mt_rand($characterMinMagic, $characterMaxMagic);
    $negativeDamagePlayer = mt_rand($monsterMinDefenseMagic, $monsterMaxDefenseMagic);
    $totalDamagePlayer = $positiveDamagePlayer - $negativeDamagePlayer;

    //On calcule les dégats du monstre
    $positiveDamageMonster = mt_rand($monsterMinStrength, $monsterMaxStrength);
    $negativeDamageMonster = mt_rand($characterMinDefense, $characterMaxDefense);
    $totalDamageMonster = $positiveDamageMonster - $negativeDamageMonster;

    //Si le joueur à fait des dégats négatif ont bloque à zéro pour ne pas soigner le monstre (Car moins et moins fait plus)
    if ($totalDamagePlayer < 0)
    {
        $totalDamagePlayer = 0;
    }

    //Si le monstre à fait des dégats négatif ont bloque à zéro pour ne pas soigner le personnage (Car moins et moins fait plus)
    if ($totalDamageMonster < 1)
    {
        $totalDamageMonster = 0;
    }

    //On affiche les résultats du tour
    echo "$characterName à fait $totalDamagePlayer point(s) de dégat à $monsterName<br />";
    echo "$monsterName à fait $totalDamageMonster point(s) de dégat à $characterName<br />";

    //On met à jour la vie du joueur et du monstre
    $battleMonsterHpRemaining = $battleMonsterHpRemaining - $totalDamagePlayer;
    $characterHpMin = $characterHpMin - $totalDamageMonster;

    //On met le personnage à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = :characterHpMin
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterHpMin' => $characterHpMin,
    'characterId' => $characterId]);

    //On met le monstre à jour dans la base de donnée
    $updateMonsterBattle = $bdd->prepare("UPDATE car_battles_monsters
    SET battleMonsterMonsterHpRemaining = :battleMonsterHpRemaining
    WHERE battleMonsterId = :battleMonsterId");
    $updateMonsterBattle->execute([
    'battleMonsterHpRemaining' => $battleMonsterHpRemaining,
    'battleMonsterId' => $battleMonsterId]);

    ?>
        <form method="POST" action="index.php">
            <input type="submit" name="magic" class="btn btn-default form-control" value="Continuer"><br>
        </form>
    <?php
}
else
{
    echo "Erreur: Aucune attaque magique de lancée";
}

require_once("../../html/footer.php"); ?>