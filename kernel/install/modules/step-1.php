<?php require_once("../html/header.php"); ?>

<p>Avant de procéder à l'installation de Caranille vous devez lire et accepter la licence d'utilisation du logiciel</p>

<form method="POST" action="step-2.php">
    <iframe src="../../../LICENCE.txt" width="100%" height="100%"></iframe>
    En cliquant sur "Installer le logiciel" vous acceptez la licence d'utilisation !
    <input type="submit" name="install" class="btn btn-default form-control" value="Installer le logiciel">
</form>

<?php require_once("../html/footer.php"); ?>