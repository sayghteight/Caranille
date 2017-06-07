<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }
?>

<img src="" alt="">
<?php echo $townName; ?><br />
<?php echo $townDescription; ?><br /><br />
<a href="../../modules/dungeon/index.php">Donjon</a><br>
<a href="../../modules/arena/index.php">L'arène</a><br>
<a href="../../modules/inn/index.php">L'auberge</a><hr>
<form method="POST" action="exitTown.php">
    <input type="submit" class="btn btn-default form-control" value="Quitter la ville">
</form>

<?php require_once("../../html/footer.php"); ?>