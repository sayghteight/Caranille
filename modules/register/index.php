<?php require_once("../../html/header.php"); ?>

<form method="POST" action="register.php">
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Pseudo</label>
        <input class="form-control" type="text" name="accountPseudo" required>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Mot de passe</label>
        <input class="form-control" type="password" name="accountPassword" required>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Confirmez</label>
        <input class="form-control" type="password" name="accountPasswordConfirm" required>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Email</label>
        <input class="form-control" type="email" name="accountEmail" required>
    </div>
    <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" type="radio" name="characterSex" id="gridRadios1" value="1" checked>
            Male
        </label>
    </div>
    <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" type="radio" name="characterSex" id="gridRadios2" value="0">
            Femelle
        </label>
    </div>
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Nom du personnage</label>
        <input class="form-control" type="text" name="characterName" required>
    </div>
    <center><iframe src="../../licence.txt" width="100%" height="100%"></iframe></center>
    <div class="form-group row">
        <center>En vous inscrivant vous acceptez le présent règlement !</center>
    </div>
    <center><input type="submit" name="Register" class="btn btn-default form-control" value="Je créer mon compte"></center>
</form>

<?php require_once("../../html/footer.php"); ?>