<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['addStrength']))
{
    //Si le personnage a suffisamment de PC
    if ($characterSkillPoints > 0)
    {
        ?>
        
        <p>ATTENTION</p> 
        Vous êtes sur le point d'attribuer 1 point en force à votre personnage pour 1 PC.<br />
        Confirmez-vous ?

        <hr>
            
        <form method="POST" action="addStrengthEnd.php">
            <input type="submit" class="btn btn-default form-control" name="finalAddStrength" value="Je confirme">
        </form>
        
        <hr>
            
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" value="Retour">
        </form>
        
        <?php
    }
    //Si le personnage n'a pas suffisamment de PC
    else
    {
        ?>
        
        Vous n'avez pas assez de points de compétences
                
        <hr>
        
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" value="Retour">
        </form>
        
        <?php
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>
