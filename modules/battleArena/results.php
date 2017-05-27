<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un joueur on redirige le joueur vers la ville
if ($foundBattleArena == 0) { exit(header("Location: ../../modules/town/index.php")); }

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
    //On force le rafraichissement de la page
    echo "<meta http-equiv=\"refresh\" content=\"0\">";
}

require_once("../../html/footer.php"); ?>