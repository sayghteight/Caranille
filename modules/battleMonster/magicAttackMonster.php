<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un monstre on redirige le joueur vers le module dungeon
if ($battleMonsterRow == 0) { exit(header("Location: ../../modules/battleArena/index.php")); }

//Si le joueur a cliqué sur le bouton magic
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
    $positiveDamagesCharacter = mt_rand($characterMinMagic, $characterMaxMagic);
    $negativeDamagesCharacter = mt_rand($monsterMinDefenseMagic, $monsterMaxDefenseMagic);
    $totalDamagesCharacter = $positiveDamagesCharacter - $negativeDamagesCharacter;

    //Si le monstre à plus de puissance physique ou autant que de magique il fera une attaque physique
    if ($monsterStrength >= $monsterMagic)
    {
        //On calcule les dégats du monstre
        $positiveDamagesMonster = mt_rand($monsterMinStrength, $monsterMaxStrength);
        $negativeDamagesMonster = mt_rand($characterMinDefense, $characterMaxDefense);
        $totalDamagesMonster = $positiveDamagesMonster - $negativeDamagesMonster;
        
    }
    //Sinon il fera une attaque magique
    else
    {
        //On calcule les dégats du monstre
        $positiveDamagesMonster = mt_rand($monsterMinMagic, $monsterMaxMagic);
        $negativeDamagesMonster = mt_rand($characterMinDefenseMagic, $characterMaxDefenseMagic);
        $totalDamagesMonster = $positiveDamagesMonster - $negativeDamagesMonster;
    }

    //Si le joueur à fait des dégats négatif ont bloque à zéro pour ne pas soigner le monstre (Car moins et moins fait plus)
    if ($totalDamagesCharacter < 0)
    {
        $totalDamagesCharacter = 0;
    }

    //Si le monstre à fait des dégats négatif ont bloque à zéro pour ne pas soigner le personnage (Car moins et moins fait plus)
    if ($totalDamagesMonster < 1)
    {
        $totalDamagesMonster = 0;
    }

    //On affiche les résultats du tour
    echo "$characterName à fait $totalDamagesCharacter point(s) de dégat à $monsterName<br />";
    echo "$monsterName à fait $totalDamagesMonster point(s) de dégat à $characterName<br />";

    //On met à jour la vie du joueur et du monstre
    $battleMonsterHpRemaining = $battleMonsterHpRemaining - $totalDamagesCharacter;
    $characterHpMin = $characterHpMin - $totalDamagesMonster;

    //On met le personnage à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = :characterHpMin
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterHpMin' => $characterHpMin,
    'characterId' => $characterId]);
    $updateCharacter->closeCursor();

    //On met le monstre à jour dans la base de donnée
    $updateMonsterBattle = $bdd->prepare("UPDATE car_battles_monsters
    SET battleMonsterMonsterHpRemaining = :battleMonsterHpRemaining
    WHERE battleMonsterId = :battleMonsterId");
    $updateMonsterBattle->execute([
    'battleMonsterHpRemaining' => $battleMonsterHpRemaining,
    'battleMonsterId' => $battleMonsterId]);
    $updateMonsterBattle->closeCursor();

    //Si le joueur ou le monstre adverse a moins ou a zéro HP on redirige le joueur vers la page des récompenses
    if ($characterHpMin <= 0 || $battleMonsterHpRemaining <= 0)
    {
        ?>
            
        <hr>
        
        <form method="POST" action="rewardsMonster.php">
            <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
        </form>
        <?php
    }

    //Si le joueur et le monstre adverse ont plus de zéro HP on continue le combat
    if ($characterHpMin > 0 && $battleMonsterHpRemaining > 0)
    {
        ?>
                
        <hr>

        <form method="POST" action="index.php">
            <input type="submit" name="magic" class="btn btn-default form-control" value="Continuer"><br>
        </form>
        <?php
    }
}
//Si le joueur n'a pas cliqué sur le bouton magic
else
{
    echo "Erreur: Aucune attaque magique de lancée";
}

require_once("../../html/footer.php"); ?>