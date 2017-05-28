<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un personnage on redirige le joueur vers le module arena
if ($foundBattleArena == 0) { exit(header("Location: ../../modules/arena/index.php")); }

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

    //On calcule les dégats du personnage adverse
    $positiveDamagesCharacter = mt_rand($opponentCharacterMinStrength, $opponentCharacterMaxStrength);
    $negativeDamagesCharacter = mt_rand($characterMinDefense, $characterMaxDefense);
    $totalDamagesOpponentCharacter = $positiveDamagesCharacter - $negativeDamagesCharacter;

    //Si le joueur à fait des dégats négatif ont bloque à zéro pour ne pas soigner l'adversaire (Car moins et moins fait plus)
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
    $opponentCharacterHpMin = $opponentCharacterHpMin - $totalDamagesCharacter;
    $characterHpMin = $characterHpMin - $totalDamagesOpponentCharacter;

    //On met le personnage à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = :characterHpMin
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterHpMin' => $characterHpMin,
    'characterId' => $characterId]);

    //On met le monstre à jour dans la base de donnée
    $updateCharacterBattle = $bdd->prepare("UPDATE car_battles_arenas
    SET battleArenaOpponentCharacterHpRemaining = :opponentCharacterHpMin
    WHERE battleCharacterId = :battleCharacterId");
    $updateCharacterBattle->execute([
    'opponentCharacterHpMin' => $opponentCharacterHpMin,
    'battleCharacterId' => $battleCharacterId]);

    //Si le monstre a moins ou a zéro HP on redirige le joueur vers la page des récompenses
    if ($opponentCharacterHpMin <= 0)
    {
        ?>
        <form method="POST" action="rewards.php">
            <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
        </form>
        <?php
    }

    //Si le joueur a moins ou a zéro HP on redirige le joueur vers la page des récompenses
    if ($characterHpMin <= 0)
    {
        ?>
        <form method="POST" action="rewards.php">
            <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
        </form>
        <?php
    }

    //Si le monstre et le joueur ont plus de zéro HP on continue le combat
    if ($battleCharacterHpRemaining > 0 && $characterHpMin > 0 )
    {
        ?>
        <form method="POST" action="index.php">
            <input type="submit" name="magic" class="btn btn-default form-control" value="Continuer"><br>
        </form>
        <?php
    }
}
else
{
    echo "Erreur: Aucune attaque de lancée";
}

require_once("../../html/footer.php"); ?>