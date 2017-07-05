<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }
?>

<img src="" alt="">
<?php echo $townName; ?><br />
<?php echo $townDescription; ?>
<a href="../../modules/dungeon/index.php">Donjon</a><br>
<a href="../../modules/arena/index.php">Arène</a><br>
<a href="../../modules/inn/index.php">Auberge</a><br>
<a href="../../modules/shops/index.php">Magasin(s)</a>

<hr>

<form method="POST" action="leaveTown.php">
    <input type="submit" name="leave" class="btn btn-default form-control" value="Quitter la ville">
</form>

<?php require_once("../../html/footer.php"); ?>