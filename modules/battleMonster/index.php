<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) 
{ 
    exit(header("Location: ../../index.php")); 
}

echo "<p>Combat de $characterName contre $battleMonsterName ($battleMonsterHpTotal/$battleMonsterHpTotal)</p>";

require_once("../../html/footer.php"); ?>