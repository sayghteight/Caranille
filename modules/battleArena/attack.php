<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un joueur on redirige le joueur vers la ville
if ($foundBattleArena == 0) { exit(header("Location: ../../modules/town/index.php")); }

if ($playerOneStep == 0 && $playerTwoStep == 0 || $playerOneStep == 0 && $playerTwoStep == 1 || $playerOneStep == 1 && $playerTwoStep == 0)
{ 
    if (isset($_POST['attack']))
    {
        $positiveDamagePlayer = mt_rand($minStrength, $maxStrength);
        $negativeDamagePlayer = mt_rand($opponentMinDefense, $opponentMaxDefense);
        
        $totalDamagePlayer = $positiveDamagePlayer - $negativeDamagePlayer;

        if ($totalDamagePlayer <= 0)
        {
            $totalDamagePlayer = 0;
        }

        switch ($battlePlayer)
        {
            //Si le joueur numéro un a attaqué on met à jour ses dégats dans la base de donnée
            case 1:
                $updateBattle = $bdd->prepare("UPDATE mop_battles
                SET battleTrainerOneStep = '1',
                battleTrainerOneDamage = :totalDamagePlayer
                WHERE battleId = :battleId");
                $updateBattle->execute([
                'totalDamagePlayer' => $totalDamagePlayer,
                'battleId' => $battleId]);
                break;

            //Si le joueur numéro deux a attaqué on met à jour ses dégats dans la base de donnée
            case 2:
                $updateBattle = $bdd->prepare("UPDATE mop_battles
                SET battleTrainerTwoStep = '1',
                battleTrainerTwoDamage = :totalDamagePlayer
                WHERE battleId = :battleId");
                $updateBattle->execute([
                'totalDamagePlayer' => $totalDamagePlayer,
                'battleId' => $battleId]);
                break;
        }
    }
}