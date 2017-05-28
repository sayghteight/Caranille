<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un personnage on redirige le joueur vers le module arena
if ($foundBattleArena == 0) { exit(header("Location: ../../modules/arena/index.php")); }

echo "Combat de $characterName contre $monsterName<br />";
echo "HP de $characterName: $characterHpMin/$characterHpTotal";
?>

<form method="POST" action="attack.php">
    <input type="submit" name="attack" class="btn btn-default form-control" value="Attaque physique"><br>
</form>
    
<form method="POST" action="magic.php">
    <input type="submit" name="magic" class="btn btn-default form-control" value="Attaque magique"><br>
</form>

<form method="POST" action="escape.php">
    <input type="submit" name="escape" class="btn btn-default form-control" value="Abandonner le combat"><br />
</form>

<?php require_once("../../html/footer.php"); ?>