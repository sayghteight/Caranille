<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['changePassword']))
{
    ?>
    
    <form method="POST" action="changePasswordEnd.php">
        Ancien mot de passe : <input type="password" name="oldPassword" class="form-control" required>
        Nouveau mot de passe : <input type="password" name="newPassword" class="form-control" required>
        Confirmez : <input type="password" name="confirmNewPassword" class="form-control" required>
        <input type="submit" name="changePasswordEnd" class="btn btn-default form-control" value="Modifier le mot de passe">
    </form>
        
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Vous ne pouvez pas changer votre mot de passe";
}

require_once("../../html/footer.php"); ?>