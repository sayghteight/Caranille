<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un personnage on redirige le joueur vers le module arena
if ($foundBattleArena == 0) { exit(header("Location: ../../modules/arena/index.php")); }

if (isset($_POST['escape']))
{
    $DeleteBattle = $bdd->prepare("DELETE FROM car_battles_monsters 
    WHERE battleMonsterId = :battleMonsterId");
    $DeleteBattle->execute(array('battleMonsterId' => $battleMonsterId));
    ?>
    
    Vous avez fuit le combat !
    <form method="POST" action="../../index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}
else
{
    echo "Erreur: Vous ne pouvez pas fuire";
}

require_once("../../html/footer.php"); ?>