<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un monstre on redirige le joueur vers le module dungeon
if ($battleMonsterRow == 0) { exit(header("Location: ../../modules/battleArena/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['magic']))
{
    /*
    VARIABLES GLOBALES
    */
    $characterMinStrength = $characterStrengthTotal / 2;
    $characterMaxStrength = $characterStrengthTotal * 2;    
    $characterMinMagic = $characterMagicTotal / 2;
    $characterMaxMagic = $characterMagicTotal * 4;

    $monsterMinStrength = $monsterStrength / 2;
    $monsterMaxStrength = $monsterStrength * 2;    
    $monsterMinMagic = $monsterMagic / 2;
    $monsterMaxMagic = $monsterMagic * 4;

    $monsterMinDefense = $monsterDefense / 2;
    $monsterMaxDefense = $monsterDefense * 2;
    $monsterMinDefenseMagic = $monsterDefenseMagic / 2;
    $monsterMaxDefenseMagic = $monsterDefenseMagic * 2;

    $characterMinDefense = $characterDefenseTotal / 2;
    $characterMaxDefense = $characterDefenseTotal * 2;
    $characterMinDefenseMagic = $characterDefenseMagicTotal / 2;
    $characterMaxDefenseMagic = $characterDefenseMagicTotal * 2;
    
    //L'utilisation d'un sort est de deux mp par niveau du joueur, exemple un joueur de niveau 10 aura besoins de 20 mp par attaque
    $mpNeed = $characterLevel * 2;
    
    //Si le joueur à suffisament de MP pour lancer cette attaque
    if ($characterMpMin >= $mpNeed)
    {
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
        
        //On met à jour les Mp du joueur
        $characterMpMin = $characterMpMin - $mpNeed;
    
        //On met le personnage à jour dans la base de donnée
        $updateCharacter = $bdd->prepare("UPDATE car_characters
        SET characterHpMin = :characterHpMin,
        characterMpMin = :characterMpMin
        WHERE characterId = :characterId");
        $updateCharacter->execute([
        'characterHpMin' => $characterHpMin,
        'characterMpMin' => $characterMpMin,
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
    //Si le joueur n'a pas assez de MP pour lancer une attaque magique
    else
    {
        ?>
        Vous n'avez pas assez de MP pour lancer cette attaque
                    
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
    echo "Erreur: Aucune attaque magique de lancée";
}

require_once("../../html/footer.php"); ?>