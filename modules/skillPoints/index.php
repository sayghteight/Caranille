<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }
?>

<h1>Points de compétences</h1>
Votre personnage possède <?php echo $characterSkillPoints; ?> PC (Point de compétences)<br /><br />
<form method="POST" action="addHP.php">
	<input type="submit" class="btn btn-default form-control" value="+1 HP">
</form>
<form method="POST" action="addMP.php">
	<input type="submit" class="btn btn-default form-control" value="+1 MP">
</form>
<form method="POST" action="addStrength.php">
	<input type="submit" class="btn btn-default form-control" value="+1 en force">
</form>
<form method="POST" action="addMagic.php">
	<input type="submit" class="btn btn-default form-control" value="+1 en magie">
</form>
<form method="POST" action="addAgility.php">
	<input type="submit" class="btn btn-default form-control" value="+1 en agilité">
</form>
<form method="POST" action="addDefense.php">
	<input type="submit" class="btn btn-default form-control" value="+1 en défense">
</form>
<form method="POST" action="addDefenseMagic.php">
	<input type="submit" class="btn btn-default form-control" value="+1 en défense magique">
</form>
<form method="POST" action="addWisdom.php">
	<input type="submit" class="btn btn-default form-control" value="+1 en sagesse">
</form>

<?php require_once("../../html/footer.php"); ?>