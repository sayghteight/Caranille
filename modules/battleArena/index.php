<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un joueur on redirige le joueur vers la ville
if ($foundBattleArena == 0) { exit(header("Location: ../../modules/town/index.php")); }

/*
VARIABLES GLOBALES
*/
$minStrength = $characterStrengthTotal / 1.1;
$maxStrength = $characterStrengthTotal * 1.1;    
$minMagic = $characterMagicTotal / 1.1;
$maxMagic = $characterMagicTotal * 1.1;

$opponentMinDefense = $opponentCharacterDefenseTotal / 1.1;
$opponentMaxDefense = $opponentCharacterDefenseTotal * 1.1;
$opponentMinDefenseMagic = $opponentCharacterDefenseMagicTotal / 1.1;
$opponentMaxDefenseMagic = $opponentCharacterDefenseMagicTotal * 1.1;

/*
ETAPE 0 - Les joueurs choisissent une action
*/
if ($playerOneStep == 0 && $playerTwoStep == 0 || $playerOneStep == 0 && $playerTwoStep == 1 || $playerOneStep == 1 && $playerTwoStep == 0)
{
    //On va vérifier si on peut afficher ou non le fomulaire des actions
    switch ($battlePlayer)
    {
        case 1:
            //Si le joueur numéro un n'a pas fait d'attaque ont affiche le formulaire
            if ($playerOneStep == 0)
            {
                echo "Combat de $characterName contre $opponentCharacterName<br />";
                echo "HP de $characterName: $characterHpMin/$characterHpTotal";
                ?>
                    <form method="POST" action="index.php">
                        <input type="submit" name="attack" class="btn btn-default form-control" value="Attaque physique"><br>
                    </form>
                        
                    <form method="POST" action="index.php">
                        <input type="submit" name="magic" class="btn btn-default form-control" value="Attaque magique"><br>
                    </form>
                <?php
            }
            //Si le joueur numéro un a fait une attaque on l'invite à patienter
            else
            {
                echo "Veuillez patientez pendant que l'adversaire choisit une action...";
            }
        break;

        case 2:
            //Si le joueur numéro deux n'a pas fait d'attaque ont affiche le formulaire
            if ($playerTwoStep == 0)
            {
                echo "Combat de $characterName contre $opponentCharacterName<br />";
                echo "HP de $characterName: $characterHpMin/$characterHpTotal";
                ?>
                    <form method="POST" action="index.php">
                        <input type="submit" name="attack" class="btn btn-default form-control" value="Attaque physique"><br>
                    </form>
                        
                    <form method="POST" action="index.php">
                        <input type="submit" name="magic" class="btn btn-default form-control" value="Attaque magique"><br>
                    </form>
                <?php
            }
            //Si le joueur deux a fait une attaque on l'invite à patienter
            else
            {
                echo "Veuillez patientez pendant que l'adversaire choisit une action...";
            }
        break;
    }
    ?>
        <form method="POST" action="escape.php">
            <input type="submit" name="escape" class="btn btn-default form-control" value="Abandonner le combat"><br />
        </form>
    <?php
}

if ($playerOneStep == 0 && $playerTwoStep == 0 || $playerOneStep == 0 && $playerTwoStep == 1 || $playerOneStep == 1 && $playerTwoStep == 0)
{
    if (isset($_POST['attack']) || isset($_POST['magic']) || isset($_POST['escape']))
    {
        if (isset($_POST['attack']))
        {
            $positiveDamagesPlayer = mt_rand($minStrength, $maxStrength);
            $negativeDamagesPlayer = mt_rand($opponentMinDefense, $opponentMaxDefense);
            
            $totalDamagesPlayer = $positiveDamagesPlayer - $negativeDamagesPlayer;

            if ($totalDamagesPlayer <= 0)
            {
                $totalDamagesPlayer = 0;
            }
        }

        if (isset($_POST['magic']))
        {
            $positiveDamagesPlayer = mt_rand($minMagic, $maxMagic);
            $negativeDamagesPlayer = mt_rand($opponentMinDefenseMagic, $opponentMaxDefenseMagic);

            $totalDamagesPlayer = $positiveDamagesPlayer - $negativeDamagesPlayer;

            if ($totalDamagesPlayer <= 0)
            {
                $totalDamagesPlayer = 0;
            }
        }
        switch ($battlePlayer)
        {
            //Si le joueur numéro un a attaqué on met à jour ses dégats dans la base de donnée
            case 1:
                $updateBattle = $bdd->prepare("UPDATE car_battles_arenas
                SET battleArenaCharacterOneStep = '1',
                battleArenaCharacterOneDamages = :totalDamagesPlayer
                WHERE battleArenaId = :battleArenaId");
                $updateBattle->execute([
                'totalDamagesPlayer' => $totalDamagesPlayer,
                'battleArenaId' => $battleArenaId]);
                break;

            //Si le joueur numéro deux a attaqué on met à jour ses dégats dans la base de donnée
            case 2:
                $updateBattle = $bdd->prepare("UPDATE car_battles_arenas
                SET battleArenaCharacterTwoStep = '1',
                battleArenaCharacterTwoDamages = :totalDamagesPlayer
                WHERE battleArenaId = :battleArenaId");
                $updateBattle->execute([
                'totalDamagesPlayer' => $totalDamagesPlayer,
                'battleArenaId' => $battleArenaId]);
                break;
        }
        //On crée une variable selectedAction pour ne plus afficher le formulaire avec les choix (Attaquer, Magie etc...)
        header("Location: index.php");
    }
}

if ($playerOneStep == 1 && $playerTwoStep == 1 || $playerOneStep == 1 && $playerTwoStep == 2 || $playerOneStep == 2 && $playerTwoStep == 1)
{
    switch ($battlePlayer)
    {
        case 1:
            echo "$characterName a infligé $damagesPlayerOne point(s) de dégats à $opponentCharacterName<br />";
            echo "$opponentCharacterName a infligé $damagesPlayerTwo point(s) de dégats à $characterName<br /><br />";

            //On met les données du combat à jour pour le tour suivant
            $updateBattle = $bdd->prepare("UPDATE car_battles_arenas
            SET battleArenaCharacterTwoStep = 2
            WHERE battleArenaId = :battleArenaId");
            $updateBattle->execute([
            'battleArenaId' => $battleArenaId]);

            //On met les stats du character à jour
            $updatecharacter = $bdd->prepare("UPDATE car_characters 
            SET characterHpMin = characterHpMin - :damagesPlayerTwo
            WHERE characterId= :characterId");
            $updatecharacter->execute([
            'damagesPlayerTwo' => $damagesPlayerTwo,
            'characterId' => $characterId]);

            echo "Vous avez perdu $damagesPlayerTwo HP";

            $hp = $characterHpMin - $damagesPlayerTwo; 
            $OpponentHp = $opponentCharacterHpMin - $damagesPlayerOne;

            if ($hp <= 0)
            {
                echo "$characterName est KO, la victoire revient à $opponentCharacterName";
            }

            if ($OpponentHp <=0 )
            {
                echo "$opponentCharacterName est KO, la victoire revient à $characterName";
            }         
            break;

        case 2:
            echo "$characterName a infligé $damagesPlayerTwo point(s) de dégats à $opponentCharacterName<br />";
            echo "$opponentCharacterName a infligé $damagesPlayerOne point(s) de dégats à $characterName<br /><br />";

            //On met les données du combat à jour pour le tour suivant
            $updateBattle = $bdd->prepare("UPDATE car_battles_arenas
            SET battleArenaCharacterOneStep = 2
            WHERE battleArenaId = :battleArenaId");
            $updateBattle->execute([
            'battleArenaId' => $battleArenaId]);

            //On met les stats du character à jour
            $updatecharacter = $bdd->prepare("UPDATE car_characters 
            SET characterHpMin = characterHpMin - :damagesPlayerOne
            WHERE characterId= :characterId");
            $updatecharacter->execute([
            'damagesPlayerOne' => $damagesPlayerOne,
            'characterId' => $characterId]);

            echo "Vous avez perdu $damagesPlayerOne HP";

            $hp = $characterHpMin - $damagesPlayerOne; 
            $OpponentHp = $opponentCharacterHpMin - $damagesPlayerTwo;

            if ($hp <= 0)
            {
                echo "$characterName est KO, la victoire revient à $opponentCharacterName";
            }

            if ($OpponentHp <=0 )
            {
                echo "$opponentCharacterName est KO, la victoire revient à $characterName";
            }
            break;
    }
}

if ($playerOneStep == 2 && $playerTwoStep == 2 || $playerOneStep == 2 && $playerTwoStep == 0 || $playerOneStep == 0 && $playerTwoStep == 2)
{
    //Si un des deux characters à sa vie à zéro (voir même les deux characters en même temps) on arrète le combat
    if ($characterHpMin <= 0 || $opponentCharacterHpMin <= 0 || $characterHpMin <= 0 && $opponentCharacterHpMin <= 0)
    {
        //On supprime le combat en cours
        $DeleteBattle = $bdd->prepare("DELETE FROM car_battles_arenas 
        WHERE battleArenaId = :battleArenaId");
        $DeleteBattle->execute(array('battleArenaId' => $battleArenaId));
    }
    else
    {
        //On met les données du combat à jour pour le tour suivant
        $updateBattle = $bdd->prepare("UPDATE car_battles_arenas
        SET battleArenaCharacterOneStep = 0,
        battleArenaCharacterOneDamages = 0,
        battleArenaCharacterTwoStep = 0,
        battleArenaCharacterTwoDamages = 0
        WHERE battleArenaId = :battleArenaId");
        $updateBattle->execute([
        'battleArenaId' => $battleArenaId]);
    }
    header("Location: index.php");
}

echo "<meta http-equiv=\"refresh\" content=\"4\">";

require_once("../../html/footer.php"); ?>