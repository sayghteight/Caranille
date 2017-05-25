<?php require_once("../../html/header.php");

//On vérifie si un combat à bien été lancé dans le module donjon, si c'est le cas une variable battleId existe
if (isset($battleId))
{
    /*
    VARIABLES GLOBALES
    */
    $minStrength = $characterStrengthTotal / 1.1;
    $maxStrength = $characterStrengthTotal * 1.1;    
    $minMagic = $characterMagicTotal / 1.1;
    $maxMagic = $characterMagicTotal * 1.1;

    $opponentMinDefense = $opponentcharacterDefenseTotal / 1.1;
    $opponentMaxDefense = $opponentcharacterDefenseTotal * 1.1;
    $opponentMinDefenseMagic = $opponentcharacterDefenseMagicTotal / 1.1;
    $opponentMaxDefenseMagic = $opponentcharacterDefenseMagicTotal * 1.1;

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
                    echo "Combat de $characterName contre $opponentcharacterName<br />";
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
                    echo "Combat de $characterName contre $opponentcharacterName<br />";
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
            <form method="POST" action="index.php">
                <input type="submit" name="escape" class="btn btn-default form-control" value="Abandonner le combat"><br />
            </form>
        <?php

        //Si une action a été choisie
        if (isset($_POST['attack']) || isset($_POST['magic']) || isset($_POST['escape']))
        {
            //Si il s'agit d'une attaque physique
            if (isset($_POST['attack']))
            {
                $positiveDamagePlayer = mt_rand($minStrength, $maxStrength);
                $negativeDamagePlayer = mt_rand($opponentMinDefense, $opponentMaxDefense);
                
                $totalDamagePlayer = $positiveDamagePlayer - $negativeDamagePlayer;

                if ($totalDamagePlayer <= 0)
                {
                    $totalDamagePlayer = 0;
                }
            }
            //Si il s'agit d'une attaque magique
            else if (isset($_POST['magic']))
            {
                $positiveDamagePlayer = mt_rand($minMagic, $maxMagic);
                $negativeDamagePlayer = mt_rand($opponentMinDefenseMagic, $opponentMaxDefenseMagic);

                $totalDamagePlayer = $positiveDamagePlayer - $negativeDamagePlayer;

                if ($totalDamagePlayer <= 0)
                {
                    $totalDamagePlayer = 0;
                }
            }
            //Si le joueur choisit de fuire
            else if (isset($_POST['escape']))
            {
                $DeleteBattle = $bdd->prepare("DELETE FROM mop_battles 
                WHERE battleId = :battleId");
                $DeleteBattle->execute(array('battleId' => $battleId));

                //On force le rafraichissement de la page
                echo "<meta http-equiv=\"refresh\" content=\"0\">";
                exit();
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
            //On crée une variable selectedAction pour ne plus afficher le formulaire avec les choix (Attaquer, Magie etc...)
            echo "<meta http-equiv=\"refresh\" content=\"0\">";
        }
    }
    
    /*
    ETAPE 1 - Les joueurs ont choisit leur actions
    */
    else if ($playerOneStep == 1 && $playerTwoStep == 1 || $playerOneStep == 1 && $playerTwoStep == 2 || $playerOneStep == 2 && $playerTwoStep == 1)
    {
        switch ($battlePlayer)
        {
            case 1:
                echo "$characterName a infligé $damagesPlayerOne point(s) de dégats à $opponentcharacterName<br />";
                echo "$opponentcharacterName a infligé $damagesPlayerTwo point(s) de dégats à $characterName<br /><br />";

                //On met les données du combat à jour pour le tour suivant
                $updateBattle = $bdd->prepare("UPDATE mop_battles
                SET battleTrainerTwoStep = 2
                WHERE battleId = :battleId");
                $updateBattle->execute([
                'battleId' => $battleId]);

                //On met les stats du character à jour
                $updatecharacter = $bdd->prepare("UPDATE mop_characters SET 
                characterHpMin = characterHpMin - :damagesPlayerTwo
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
                $updateBattle = $bdd->prepare("UPDATE mop_battles
                SET battleTrainerOneStep = 2
                WHERE battleId = :battleId");
                $updateBattle->execute([
                'battleId' => $battleId]);

                //On met les stats du character à jour
                $updatecharacter = $bdd->prepare("UPDATE mop_characters SET 
                characterHpMin = characterHpMin - :damagesPlayerOne
                WHERE characterId= :characterId");
                $updatecharacter->execute([
                'damagesPlayerOne' => $damagesPlayerOne,
                'characterId' => $characterId]);

                $hp = $characterHpMin - $damagesPlayerOne; 
                $OpponentHp = $opponentcharacterHpMin - $damagesPlayerTwo;

                if ($hp <= 0)
                {
                    echo "$characterName est KO, la victoire revient à $opponentcharacterName";
                }

                if ($OpponentHp <=0 )
                {
                    echo "$opponentcharacterName est KO, la victoire revient à $characterName";
                }
                break;
        }
    }

    /*
    ETAPE 3 - On vérifie si un des joueurs à gagné sinon on refait un tour
    */
    else if ($playerOneStep == 2 && $playerTwoStep == 2 || $playerOneStep == 2 && $playerTwoStep == 0 || $playerOneStep == 0 && $playerTwoStep == 2)
    {
        //Si un des deux characters à sa vie à zéro (voir même les deux characters en même temps) on arrète le combat
        if ($characterHpMin <= 0 || $opponentcharacterHpMin <= 0 || $characterHpMin <= 0 && $opponentcharacterHpMin <= 0)
        {
            //On supprime le combat en cours
            $DeleteBattle = $bdd->prepare("DELETE FROM mop_battles 
            WHERE battleId = :battleId");
            $DeleteBattle->execute(array('battleId' => $battleId));
        }
        else
        {
            //On met les données du combat à jour pour le tour suivant
            $updateBattle = $bdd->prepare("UPDATE mop_battles
            SET battleTrainerOneStep = 0,
            battleTrainerOneDamage = 0,
            battleTrainerTwoStep = 0,
            battleTrainerTwoDamage = 0
            WHERE battleId = :battleId");
            $updateBattle->execute([
            'battleId' => $battleId]);
        }
        //On force le rafraichissement de la page
        echo "<meta http-equiv=\"refresh\" content=\"0\">";
    }
}
//Si il n'y a plus de combat en cours
else
{
    header("Location: $url/modules/character/stats.php");
}

echo "<meta http-equiv=\"refresh\" content=\"4\">";

require_once("../../html/footer.php"); ?>