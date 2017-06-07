<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un personnage on redirige le joueur vers le module arena
if ($battleArenaRow == 0) { exit(header("Location: ../../modules/battleArena/index.php")); }

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
    $positiveDamagesCharacter = mt_rand($characterMinMagic, $characterMaxMagic);
    $negativeDamagesCharacter = mt_rand($opponentCharacterMinDefenseMagic, $opponentCharacterMaxDefenseMagic);
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

    //Si le joueur à fait des dégats négatif ont bloque à zéro pour ne pas soigner le monstre (Car moins et moins fait plus)
    if ($totalDamagesCharacter < 0)
    {
        $totalDamagesCharacter = 0;
    }

    //Si le monstre à fait des dégats négatif ont bloque à zéro pour ne pas soigner le personnage (Car moins et moins fait plus)
    if ($totalDamagesOpponentCharacter < 1)
    {
        $totalDamagesOpponentCharacter = 0;
    }

    //On affiche les résultats du tour
    echo "$characterName a fait $totalDamagesCharacter point(s) de dégat à $opponentCharacterName<br />";
    echo "$opponentCharacterName a fait $totalDamagesOpponentCharacter point(s) de dégat à $characterName<br /><br />";

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
        
        <form method="POST" action="rewards.php">
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
//Si le joueur n'a pas cliqué sur le bouton magic
else
{
    echo "Erreur: Aucune attaque magique de lancée";
}

require_once("../../html/footer.php"); ?>