<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }
?>

<?php echo $accountPseudo ?><br />

<hr>

Dernière connexion : <?php echo strftime('%d-%m-%Y - %H:%M:%S',strtotime($accountLastConnection)) ?><br />
Dernière action : <?php echo strftime('%d-%m-%Y - %H:%M:%S',strtotime($accountLastAction)) ?><br />
Accès : <?php echo $accountAccess ?><br />

<hr>

<form method="POST" action="changePassword.php">
    <input type="submit" name="changePassword" class="btn btn-default form-control" value="Changer mot de passe"><br>
</form>

<?php require_once("../../html/footer.php"); ?>