<?php 
require_once("../../html/header.php"); 
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
?>

Bienvenue à l'auberge, ici vous allez pouvoir vous reposer pour 10 pièce d'or

<hr>

<form method="POST" action="sleep.php">
    <input type="submit" class="btn btn-default form-control" value="Se reposer">
</form>

<?php require_once("../../html/footer.php"); ?>