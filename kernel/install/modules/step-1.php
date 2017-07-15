<?php require_once("../html/header.php"); ?>

<p>Afin d'installer Caranille veuillez remplir les informations suivantes</p>

<form method="POST" action="step-2.php">
    Nom de la base de donnée : <input type="text" name="databaseName" class="form-control" required>
    Adresse de la base de donnée : <input type="text" name="databaseHost" class="form-control" required>
    Nom de l'utilisateur : <input type="text" name="databaseUser"  class="form-control" required>
    Mot de passe: <input type="password" name="databasePassword" class="form-control">
    <input type="submit" class="btn btn-default form-control" name="install" value="Continuer">
</form>

<?php require_once("../html/footer.php"); ?>