<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un personnage on redirige le joueur vers le module arena
if ($battleArenaRow == 0) { exit(header("Location: ../../modules/battleArena/index.php")); }

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

    $opponentCharacterMinStrength = $opponentCharacterStrengthTotal / 2;
    $opponentCharacterMaxStrength = $opponentCharacterStrengthTotal * 2;    
    $opponentCharacterMinMagic = $opponentCharacterMagicTotal / 2;
    $opponentCharacterMaxMagic = $opponentCharacterMagicTotal * 4;

    $opponentCharacterMinDefense = $opponentCharacterDefenseTotal / 2;
    $opponentCharacterMaxDefense = $opponentCharacterDefenseTotal * 2;
    $opponentCharacterMinDefenseMagic = $opponentCharacterDefenseMagicTotal / 2;
    $opponentCharacterMaxDefenseMagic = $opponentCharacterDefenseMagicTotal * 2;

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
        $negativeDamagesCharacter = mt_rand($opponentCharacterMinDefenseMagic, $opponentCharacterMaxDefenseMagic);
        $totalDamagesCharacter = $positiveDamagesCharacter - $negativeDamagesCharacter;
    
        //Si l'adversaire à plus de puissance physique ou autant que de magique il fera une attaque physique
        if ($opponentCharacterStrengthTotal >= $opponentCharacterMagicTotal)
        {
            //On calcule les dégats de l'adversaire
            $positiveDamagesOpponentCharacter = mt_rand($opponentCharacterMinStrength, $opponentCharacterMaxStrength);
            $negativeDamagesOpponentCharacter = mt_rand($characterMinDefense, $characterMaxDefense);
            $totalDamagesOpponentCharacter = $positiveDamagesOpponentCharacter - $negativeDamagesOpponentCharacter;
        }
        //Sinon il fera une attaque magique
        else
        {
            //On calcule les dégats de l'adversaire
            $positiveDamagesOpponentCharacter = mt_rand($opponentCharacterMinMagic, $opponentCharacterMaxMagic);
            $negativeDamagesOpponentCharacter = mt_rand($characterMinDefenseMagic, $characterMaxDefenseMagic);
            $totalDamagesOpponentCharacter = $positiveDamagesOpponentCharacter - $negativeDamagesOpponentCharacter;
        }
    
        //Si le joueur a fait des dégats négatif ont bloque à zéro pour ne pas soigner l'adversaire (Car moins et moins fait plus)
        if ($totalDamagesCharacter < 0)
        {
            $totalDamagesCharacter = 0;
        }
    
        //Si l'adversaire a fait des dégats négatif ont bloque à zéro pour ne pas soigner le personnage (Car moins et moins fait plus)
        if ($totalDamagesOpponentCharacter < 0)
        {
            $totalDamagesOpponentCharacter = 0;
        }
    
        //On vérifie si l'adversaire esquive l'attaque du joueur
        if ($opponentCharacterAgilityTotal >= $characterAgilityTotal)
        {
            $totalDifference = $opponentCharacterAgilityTotal - $characterAgilityTotal;
            $percentage = $totalDifference/$opponentCharacterAgilityTotal * 100;
    
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
            echo "$characterName a fait $totalDamagesCharacter point(s) de dégat à $opponentCharacterName<br />";
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
    
        //On met à jour la vie du joueur et de l'adversaire
        $battleArenaOpponentCharacterHpRemaining = $battleArenaOpponentCharacterHpRemaining - $totalDamagesCharacter;
        $characterHpMin = $characterHpMin - $totalDamagesOpponentCharacter;
    
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
    
        //On met l'adversaire à jour dans la base de donnée
        $updateCharacterBattle = $bdd->prepare("UPDATE car_battles_arenas
        SET battleArenaOpponentCharacterHpRemaining = :battleArenaOpponentCharacterHpRemaining
        WHERE battleArenaOpponentCharacterId = :battleArenaOpponentCharacterId");
        $updateCharacterBattle->execute([
        'battleArenaOpponentCharacterHpRemaining' => $battleArenaOpponentCharacterHpRemaining,
        'battleArenaOpponentCharacterId' => $battleArenaOpponentCharacterId]);
        $updateCharacterBattle->closeCursor();
        
        //Si le joueur ou l'adversaire adverse a moins ou a zéro HP on redirige le joueur vers la page des récompenses
        if ($characterHpMin <= 0 || $battleArenaOpponentCharacterHpRemaining <= 0)
        {
            ?>
                        
            <hr>
            
            <form method="POST" action="rewardsArena.php">
                <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
            </form>
            <?php
        }
    
        //Si l'adversaire et le joueur ont plus de zéro HP on continue le combat
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