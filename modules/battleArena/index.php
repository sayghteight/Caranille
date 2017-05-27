<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un joueur on redirige le joueur vers la ville
if ($foundBattleArena == 0) { exit(header("Location: ../../modules/town/index.php")); }

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
                    <form method="POST" action="attack.php">
                        <input type="submit" name="attack" class="btn btn-default form-control" value="Attaque physique"><br>
                    </form>
                        
                    <form method="POST" action="magic.php">
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
                    <form method="POST" action="attack.php">
                        <input type="submit" name="attack" class="btn btn-default form-control" value="Attaque physique"><br>
                    </form>
                        
                    <form method="POST" action="magic.php">
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

require_once("../../html/footer.php"); ?>