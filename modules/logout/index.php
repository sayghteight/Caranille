<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }

session_destroy();

header("Location: $url/modules/character/index.php");

require_once("../../html/footer.php"); ?>