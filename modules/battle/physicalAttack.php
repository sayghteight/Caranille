<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow == 0) { exit(header("Location: ../../modules/main/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['attack']))
{
    /*
    VARIABLES GLOBALES
    */
    $characterMinStrength = $characterStrengthTotal / 2;
    $characterMaxStrength = $characterStrengthTotal * 2;    
    $characterMinMagic = $characterMagicTotal / 2;
    $characterMaxMagic = $characterMagicTotal * 4;

    $opponentMinStrength = $opponentStrength / 2;
    $opponentMaxStrength = $opponentStrength * 2;    
    $opponentMinMagic = $opponentMagic / 2;
    $opponentMaxMagic = $opponentMagic * 4;

    $opponentMinDefense = $opponentDefense / 2;
    $opponentMaxDefense = $opponentDefense * 2;
    $opponentMinDefenseMagic = $opponentDefenseMagic / 2;
    $opponentMaxDefenseMagic = $opponentDefenseMagic * 2;

    $characterMinDefense = $characterDefenseTotal / 2;
    $characterMaxDefense = $characterDefenseTotal * 2;
    $characterMinDefenseMagic = $characterDefenseMagicTotal / 2;
    $characterMaxDefenseMagic = $characterDefenseMagicTotal * 2;

    //On calcule les dégats du joueur
    $positiveDamagesCharacter = mt_rand($characterMinStrength, $characterMaxStrength);
    $negativeDamagesCharacter = mt_rand($opponentMinDefense, $opponentMaxDefense);
    $totalDamagesCharacter = $positiveDamagesCharacter - $negativeDamagesCharacter;

    //Si l'adversaire à plus de puissance physique ou autant que de magique il fera une attaque physique
    if ($opponentStrength >= $opponentMagic)
    {
        //On calcule les dégats de l'adversaire
        $positiveDamagesOpponent = mt_rand($opponentMinStrength, $opponentMaxStrength);
        $negativeDamagesOpponent = mt_rand($characterMinDefense, $characterMaxDefense);
        $totalDamagesOpponent = $positiveDamagesOpponent - $negativeDamagesOpponent;
    }
    //Sinon il fera une attaque magique
    else
    {
        //On calcule les dégats de l'adversaire
        $positiveDamagesOpponent = mt_rand($opponentMinMagic, $opponentMaxMagic);
        $negativeDamagesOpponent = mt_rand($characterMinDefenseMagic, $characterMaxDefenseMagic);
        $totalDamagesOpponent = $positiveDamagesOpponent - $negativeDamagesOpponent;
    }

    //Si le joueur a fait des dégats négatif ont bloque à zéro pour ne pas soigner l'adversaire (Car moins et moins fait plus)
    if ($totalDamagesCharacter < 0)
    {
        $totalDamagesCharacter = 0;
    }

    //Si l'adversaire a fait des dégats négatif ont bloque à zéro pour ne pas soigner le personnage (Car moins et moins fait plus)
    if ($totalDamagesOpponent < 0)
    {
        $totalDamagesOpponent = 0;
    }

    //On vérifie si l'adversaire esquive l'attaque du joueur
    if ($opponentAgility >= $characterAgilityTotal)
    {
        $totalDifference = $opponentAgility - $characterAgilityTotal;
        $percentage = $totalDifference/$opponentAgility * 100;

        //Si la différence est de plus de 50% on bloque pour ne pas rendre l'adversaire intouchable
        if ($percentage > 50)
        {
            $percentage = 50;
        }

        //On génère un nombre entre 0 et 100 (inclus)
        $result = mt_rand(0, 101);

        //Si le nombre généré est inférieur ou égal l'adversaire esquive l'attaque, on met donc $totalDamagesCharacter à 0
        if ($result <= $percentage)
        {
            $totalDamagesCharacter = 0;
            echo "$opponentName a esquivé l'attaque de $characterName<br />";
        }
        //Sinon l'adversaire subit l'attaque
        else
        {
            echo "$characterName a fait $totalDamagesCharacter point(s) de dégat à $opponentName<br />";
        }
    }
    //Si l'adversaire a moins d'agilité que le joueur il subit l'attaque
    else
    {
        echo "$characterName a fait $totalDamagesCharacter point(s) de dégat à $opponentName<br />";
    }

    //On vérifie si le joueur esquive l'attaque de l'adversaire
    if ($characterAgilityTotal >= $opponentAgility)
    {
        $totalDifference = $characterAgilityTotal - $opponentAgility;
        $percentage = $totalDifference/$characterAgilityTotal * 100;

        //Si la différence est de plus de 50% on bloque pour ne pas rendre le joueur intouchable
        if ($percentage > 50)
        {
            $percentage = 50;
        }

        //On génère un nombre entre 0 et 100 (inclus)
        $result = mt_rand(0, 101);

        //Si le nombre généré est inférieur ou égal le joueur esquive l'attaque, on met donc $totalDamagesOpponent à 0
        if ($result <= $percentage)
        {
            $totalDamagesOpponent = 0;
            echo "$characterName a esquivé l'attaque de $opponentName<br />";
        }
        //Sinon le joueur subit l'attaque
        else
        {
            echo "$opponentName a fait $totalDamagesOpponent point(s) de dégat à $characterName<br />";
        }
    }
    //Si le joueur a moins d'agilité que l'adversaire il subit l'attaque
    else
    {
        echo "$opponentName a fait $totalDamagesOpponent point(s) de dégat à $characterName<br />";
    }
    
    //On met à jour la vie du joueur et de l'adversaire
    $battleOpponentHpRemaining = $battleOpponentHpRemaining - $totalDamagesCharacter;
    $characterHpMin = $characterHpMin - $totalDamagesOpponent;

    //On met le personnage à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = :characterHpMin
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterHpMin' => $characterHpMin,
    'characterId' => $characterId]);
    $updateCharacter->closeCursor();

    //On met l'adversaire à jour dans la base de donnée
    $updateBattle = $bdd->prepare("UPDATE car_battles
    SET battleOpponentHpRemaining = :battleOpponentHpRemaining
    WHERE battleId = :battleId");
    $updateBattle->execute([
    'battleOpponentHpRemaining' => $battleOpponentHpRemaining,
    'battleId' => $battleId]);
    $updateBattle->closeCursor();
    

    //Si le joueur ou l'adversaire a moins ou a zéro HP on redirige le joueur vers la page des récompenses
    if ($characterHpMin <= 0 || $battleOpponentHpRemaining <= 0)
    {
        ?>
                    
        <hr>
        
        <form method="POST" action="rewards.php">
            <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
        </form>
        <?php
    }

    //Si l'adversaire et le joueur ont plus de zéro HP on continue le combat
    if ($battleOpponentHpRemaining > 0 && $characterHpMin > 0 )
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