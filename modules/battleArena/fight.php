<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un joueur on redirige le joueur vers la ville
if ($foundBattleArena == 0) { exit(header("Location: ../../modules/town/index.php")); }

if ($playerOneStep == 1 && $playerTwoStep == 1 || $playerOneStep == 1 && $playerTwoStep == 2 || $playerOneStep == 2 && $playerTwoStep == 1)
{
    switch ($battlePlayer)
    {
        case 1:
            echo "$characterName a infligé $damagesPlayerOne point(s) de dégats à $opponentcharacterName<br />";
            echo "$opponentcharacterName a infligé $damagesPlayerTwo point(s) de dégats à $characterName<br /><br />";

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

            $hp = $characterHpMin - $damagesPlayerTwo; 
            $OpponentHp = $opponentcharacterHpMin - $damagesPlayerOne;

            if ($hp <= 0)
            {
                echo "$characterName est KO, la victoire revient à $opponentcharacterName";
            }

            if ($OpponentHp <=0 )
            {
                echo "$opponentcharacterName est KO, la victoire revient à $characterName";
            }         
            break;

        case 2:
            echo "$characterName a infligé $damagesPlayerTwo point(s) de dégats à $opponentcharacterName<br />";
            echo "$opponentcharacterName a infligé $damagesPlayerOne point(s) de dégats à $characterName<br /><br />";

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

            $hp = $characterHpMin - $damagesPlayerOne; 
            $OpponentHp = $opponentcharacterHpMin - $damagesPlayerTwo;

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

require_once("../../html/footer.php"); ?>