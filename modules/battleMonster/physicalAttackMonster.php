<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un monstre on redirige le joueur vers le module dungeon
if ($battleMonsterRow == 0) { exit(header("Location: ../../modules/battleArena/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['attack']))
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
    $positiveDamagesCharacter = mt_rand($characterMinStrength, $characterMaxStrength);
    $negativeDamagesCharacter = mt_rand($monsterMinDefense, $monsterMaxDefense);
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

    //Si le joueur a fait des dégats négatif ont bloque à zéro pour ne pas soigner le monstre (Car moins et moins fait plus)
    if ($totalDamagesCharacter < 0)
    {
        $totalDamagesCharacter = 0;
    }

    //Si le monstre a fait des dégats négatif ont bloque à zéro pour ne pas soigner le personnage (Car moins et moins fait plus)
    if ($totalDamagesMonster < 0)
    {
        $totalDamagesMonster = 0;
    }

    //On vérifie si le monstre esquive l'attaque du joueur
    if ($monsterAgility >= $characterAgilityTotal)
    {
        $totalDifference = $monsterAgility - $characterAgilityTotal;
        $percentage = $totalDifference/$monsterAgility * 100;

        //Si la différence est de plus de 50% on bloque pour ne pas rendre le monstre intouchable
        if ($percentage > 50)
        {
            $percentage = 50;
        }

        //On génère un nombre entre 0 et 100 (inclus)
        $result = mt_rand(0, 101);

        //Si le nombre généré est inférieur ou égal le monstre esquive l'attaque, on met donc $totalDamagesCharacter à 0
        if ($result <= $percentage)
        {
            $totalDamagesCharacter = 0;
            echo "$monsterName a esquivé l'attaque de $characterName<br />";
        }
        else
        {
            echo "$characterName a fait $totalDamagesCharacter point(s) de dégat à $monsterName<br />";
        }
    }
    //Si le monstre a moins d'agilité que le joueur il subit l'attaque
    else
    {
        echo "$characterName a fait $totalDamagesCharacter point(s) de dégat à $monsterName<br />";
    }

    //On vérifie si le joueur esquive l'attaque du monstre
    if ($characterAgilityTotal >= $monsterAgility)
    {
        $totalDifference = $characterAgilityTotal - $monsterAgility;
        $percentage = $totalDifference/$characterAgilityTotal * 100;

        //Si la différence est de plus de 50% on bloque pour ne pas rendre le joueur intouchable
        if ($percentage > 50)
        {
            $percentage = 50;
        }

        //On génère un nombre entre 0 et 100 (inclus)
        $result = mt_rand(0, 101);

        //Si le nombre généré est inférieur ou égal le monstre esquive l'attaque, on met donc $totalDamagesMonster à 0
        if ($result <= $percentage)
        {
            $totalDamagesMonster = 0;
            echo "$characterName a esquivé l'attaque de $monsterName<br />";
        }
        //Sinon le monstre subit l'attaque
        else
        {
            echo "$monsterName a fait $totalDamagesMonster point(s) de dégat à $characterName<br />";
        }
    }
    //Si le joueur a moins d'agilité que le monstre il subit l'attaque
    else
    {
        echo "$monsterName a fait $totalDamagesMonster point(s) de dégat à $characterName<br />";
    }

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
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucune attaque de lancée";
}

require_once("../../html/footer.php"); ?>