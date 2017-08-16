<?php require_once("../html/header.php"); ?>

<p>Veuillez saisir les informations de connexion à votre base de donnée Mysql</p>

<form method="POST" action="step-3.php">
    Nom de la base de donnée : <input type="text" name="databaseName" class="form-control" required>
    Adresse de la base de donnée : <input type="text" name="databaseHost" class="form-control" required>
    Nom de l'utilisateur : <input type="text" name="databaseUser"  class="form-control" required>
    Mot de passe : <input type="password" name="databasePassword" class="form-control">
    Port (3306 par défaut) : <input type="number" name="databasePort" value="3306" class="form-control" required>
    <input type="hidden" name="token" class="btn btn-default form-control" value="<?php echo $_SESSION['token'] ?>">
    <input type="submit" class="btn btn-default form-control" name="install" value="Continuer">
</form>

<?php require_once("../html/footer.php"); ?>