<?php require_once("../../html/header.php"); ?>
   
<form method="POST" action="startConnection.php">
    Pseudo : <input class="form-control" type="text" name="accountPseudo" required>
    Mot de passe : <input class="form-control" type="password" name="accountPassword" required>
    <input type="hidden" name="token" class="btn btn-default form-control" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" name="Register" class="btn btn-default form-control" value="Se connecter"></center>
</form>
                
<?php require_once("../../html/footer.php"); ?>