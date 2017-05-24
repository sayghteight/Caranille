<?php 
require_once("../../html/header.php"); 
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur est déjà dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
?>

<img src="" alt="">
<?php echo $townName; ?><br />
<?php echo $townDescription; ?><br /><br />
<a href="../../modules/dungeon/index.php">Donjon</a><br>
<a href="">Mission(s)</a><br>
<a href="">Boutique d'équipement(s)</a><br>
<a href="">Boutique de magie(s)</a><br>
<a href="">Boutique d'objet(s)</a><br>
<a href="">Le temple</a><br>
<a href="">L'auberge</a><hr>
<form method="POST" action="exitTown.php">
    <input type="submit" class="btn btn-default form-control" value="Quitter la ville">
</form>

<?php require_once("../../html/footer.php"); ?>