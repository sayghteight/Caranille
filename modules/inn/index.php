<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }
?>

<p><img src="<?php echo $townPicture ?>" height="100" width="100"></p>

Bienvenue à l'auberge, ici vous allez pouvoir vous reposer pour <?php echo $townPriceInn; ?> pièce(s) d'or

<hr>

<form method="POST" action="sleep.php">
    <input type="submit" name="sleep" class="btn btn-default form-control" value="Se reposer">
</form>

<?php require_once("../../html/footer.php"); ?>