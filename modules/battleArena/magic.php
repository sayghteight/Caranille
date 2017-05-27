<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un joueur on redirige le joueur vers la ville
if ($foundBattleArena == 0) { exit(header("Location: ../../modules/town/index.php")); }

if ($playerOneStep == 0 && $playerTwoStep == 0 || $playerOneStep == 0 && $playerTwoStep == 1 || $playerOneStep == 1 && $playerTwoStep == 0)
{ 
    if (isset($_POST['magic']))
    {
        $positiveDamagesPlayer = mt_rand($minMagic, $maxMagic);
        $negativeDamagesPlayer = mt_rand($opponentMinDefenseMagic, $opponentMaxDefenseMagic);

        $totalDamagesPlayer = $positiveDamagesPlayer - $negativeDamagesPlayer;

        if ($totalDamagesPlayer <= 0)
        {
            $totalDamagesPlayer = 0;
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
    }
}

require_once("../../html/footer.php"); ?>