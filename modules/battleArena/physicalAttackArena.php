<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un personnage on redirige le joueur vers le module arena
if ($battleArenaRow == 0) { exit(header("Location: ../../modules/battleArena/index.php")); }

//Si le joueur a cliqué sur le bouton magic
if (isset($_POST['attack']))
{
    /*
    VARIABLES GLOBALES
    */
    $characterMinStrength = $characterStrengthTotal / 1.1;
    $characterMaxStrength = $characterStrengthTotal * 1.1;    
    $characterMinMagic = $characterMagicTotal / 1.1;
    $characterMaxMagic = $characterMagicTotal * 1.1;

    $opponentCharacterMinStrength = $opponentCharacterStrengthTotal / 1.1;
    $opponentCharacterMaxStrength = $opponentCharacterStrengthTotal * 1.1;    
    $opponentCharacterMinMagic = $opponentCharacterMagicTotal / 1.1;
    $opponentCharacterMaxMagic = $opponentCharacterMagicTotal * 1.1;

    $opponentCharacterMinDefense = $opponentCharacterDefenseTotal / 1.1;
    $opponentCharacterMaxDefense = $opponentCharacterDefenseTotal * 1.1;
    $opponentCharacterMinDefenseMagic = $opponentCharacterDefenseMagicTotal / 1.1;
    $opponentCharacterMaxDefenseMagic = $opponentCharacterDefenseMagicTotal * 1.1;

    $characterMinDefense = $characterDefenseTotal / 1.1;
    $characterMaxDefense = $characterDefenseTotal * 1.1;
    $characterMinDefenseMagic = $characterDefenseMagicTotal / 1.1;
    $characterMaxDefenseMagic = $characterDefenseMagicTotal * 1.1;

    //On calcule les dégats du joueur
    $positiveDamagesCharacter = mt_rand($characterMinStrength, $characterMaxStrength);
    $negativeDamagesCharacter = mt_rand($opponentCharacterMinDefense, $opponentCharacterMaxDefense);
    $totalDamagesCharacter = $positiveDamagesCharacter - $negativeDamagesCharacter;

    //Si le personnage adversaire à plus de puissance physique ou autant que de magique il fera une attaque physique
    if ($opponentCharacterStrengthTotal >= $opponentCharacterMagicTotal)
    {
        //On calcule les dégats du personnage adverse
        $positiveDamagesOpponentCharacter = mt_rand($opponentCharacterMinStrength, $opponentCharacterMaxStrength);
        $negativeDamagesOpponentCharacter = mt_rand($characterMinDefense, $characterMaxDefense);
        $totalDamagesOpponentCharacter = $positiveDamagesOpponentCharacter - $negativeDamagesOpponentCharacter;
    }
    //Sinon il fera une attaque magique
    else
    {
        //On calcule les dégats du personnage adverse
        $positiveDamagesOpponentCharacter = mt_rand($opponentCharacterMinMagic, $opponentCharacterMaxMagic);
        $negativeDamagesOpponentCharacter = mt_rand($characterMinDefenseMagic, $characterMaxDefenseMagic);
        $totalDamagesOpponentCharacter = $positiveDamagesOpponentCharacter - $negativeDamagesOpponentCharacter;
    }

    //Si le joueur a fait des dégats négatif ont bloque à zéro pour ne pas soigner le monstre (Car moins et moins fait plus)
    if ($totalDamagesCharacter < 0)
    {
        $totalDamagesCharacter = 0;
    }

    //Si le monstre a fait des dégats négatif ont bloque à zéro pour ne pas soigner le personnage (Car moins et moins fait plus)
    if ($totalDamagesMonster < 1)
    {
        $totalDamagesMonster = 0;
    }

    //On vérifie si l'adversaire esquive l'attaque du joueur
    if ($opponentCharacterAgilityTotal >= $characterAgilityTotal)
    {
        $totalDifference = $opponentCharacterAgilityTotal - $characterAgilityTotal;
        $percentage = $totalDifference/$opponentCharacterAgilityTotal * 100;

        //Si la différence est de plus de 50% on bloque pour ne pas rendre le monstre intouchable
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
            echo "$opponentCharacterName a esquivé l'attaque de $characterName<br />";
        }
        //Sinon l'adversaire subit l'attaque
        else
        {
            echo "$characterName a fait $totalDamagesCharacter point(s) de dégat à $opponentCharacterName<br />";
        }
    }
    //Si l'adversaire a moins d'agilité que le joueur il subit l'attaque
    else
    {
        echo "$characterName aà fait $totalDamagesCharacter point(s) de dégat à $opponentCharacterName<br />";
    }

    //On vérifie si le joueur esquive l'attaque de l'adversaire
    if ($characterAgilityTotal >= $opponentCharacterAgilityTotal)
    {
        $totalDifference = $characterAgilityTotal - $opponentCharacterAgilityTotal;
        $percentage = $totalDifference/$characterAgilityTotal * 100;

        //Si la différence est de plus de 50% on bloque pour ne pas rendre le joueur intouchable
        if ($percentage > 50)
        {
            $percentage = 50;
        }

        //On génère un nombre entre 0 et 100 (inclus)
        $result = mt_rand(0, 101);

        //Si le nombre généré est inférieur ou égal le joueur esquive l'attaque, on met donc $totalDamagesOpponentCharacter à 0
        if ($result <= $percentage)
        {
            $totalDamagesOpponentCharacter = 0;
            echo "$characterName a esquivé l'attaque de $opponentCharacterName<br />";
        }
        //Sinon le joueur subit l'attaque
        else
        {
            echo "$opponentCharacterName a fait $totalDamagesOpponentCharacter point(s) de dégat à $characterName<br />";
        }
    }
    //Si le joueur a moins d'agilité que l'adversaire il subit l'attaque
    else
    {
        echo "$opponentCharacterName a fait $totalDamagesOpponentCharacter point(s) de dégat à $characterName<br />";
    }
    
    //On met à jour la vie du joueur et du monstre
    $battleArenaOpponentCharacterHpRemaining = $battleArenaOpponentCharacterHpRemaining - $totalDamagesCharacter;
    $characterHpMin = $characterHpMin - $totalDamagesOpponentCharacter;

    //On met le personnage à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = :characterHpMin
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterHpMin' => $characterHpMin,
    'characterId' => $characterId]);
    $updateCharacter->closeCursor();

    //On met le monstre à jour dans la base de donnée
    $updateCharacterBattle = $bdd->prepare("UPDATE car_battles_arenas
    SET battleArenaOpponentCharacterHpRemaining = :battleArenaOpponentCharacterHpRemaining
    WHERE battleArenaOpponentCharacterId = :battleArenaOpponentCharacterId");
    $updateCharacterBattle->execute([
    'battleArenaOpponentCharacterHpRemaining' => $battleArenaOpponentCharacterHpRemaining,
    'battleArenaOpponentCharacterId' => $battleArenaOpponentCharacterId]);
    $updateCharacterBattle->closeCursor();

    //Si le joueur ou le personnage adverse a moins ou a zéro HP on redirige le joueur vers la page des récompenses
    if ($characterHpMin <= 0 || $battleArenaOpponentCharacterHpRemaining <= 0)
    {
        ?>
                    
        <hr>
        
        <form method="POST" action="rewardsArena.php">
            <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
        </form>
        <?php
    }

    //Si le monstre et le joueur ont plus de zéro HP on continue le combat
    if ($battleArenaOpponentCharacterHpRemaining > 0 && $characterHpMin > 0 )
    {
        ?>
                
        <hr>

        <form method="POST" action="index.php">
            <input type="submit" name="magic" class="btn btn-default form-control" value="Continuer"><br>
        </form>
        <?php
    }
}
//Si le joueur n'a pas cliqué sur le bouton attack
else
{
    echo "Erreur: Aucune attaque de lancée";
}

require_once("../../html/footer.php"); ?>