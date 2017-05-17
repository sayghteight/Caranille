<?php require_once("../../html/header.php"); ?>
   
<form method="POST" action="login.php">
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Pseudo</label>
        <input class="form-control" type="text" name="accountPseudo" required>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Mot de passe</label>
        <input class="form-control" type="password" name="accountPassword" required>
    </div>
    <center><input type="submit" name="Register" class="btn btn-default form-control" value="Se connecter"></center>
</form>
                
<?php require_once("../../html/footer.php"); ?>