<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow == 0) { exit(header("Location: ../../modules/main/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['escape']))
{
    //On détruit le combat en cours
    $deleteBattle = $bdd->prepare("DELETE FROM car_battles 
    WHERE battleId = :battleId");
    $deleteBattle->execute(array('battleId' => $battleId));
    $deleteBattle->closeCursor();
    ?>
    
    Vous avez fuit le combat !
        
    <hr>

    <form method="POST" action="../../modules/town/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Vous ne pouvez pas fuire";
}

require_once("../../html/footer.php"); ?>