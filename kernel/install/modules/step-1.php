<?php require_once("../html/header.php"); ?>

<p>Afin d'installer Caranille veuillez remplir les informations suivantes</p>

<form method="POST" action="step-2.php">
    <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Nom de la base de donnée</label>
        <input class="form-control" type="text" name="databaseName" required>
    </div>
        <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Adresse de la base de donnée</label>
        <input class="form-control" type="text" name="databaseHost" required>
    </div>
        <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Nom de l'utilisateur</label>
        <input class="form-control" type="text" name="databaseUser" required>
    </div>
        <div class="form-group row">
        <label for="example-text-input" class="col-2 col-form-label">Mot de passe</label>
        <input class="form-control" type="password" name="databasePassword">
    </div>
    <input type="submit" class="btn btn-default form-control" name="install" value="Continuer">
</form>

<?php require_once("../html/footer.php"); ?>