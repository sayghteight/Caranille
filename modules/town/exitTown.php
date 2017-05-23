<?php 
require_once("../../html/header.php"); 
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur est déjà dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }

//On met le personnage à jour
$updatecharacter = $bdd->prepare("UPDATE car_characters SET
characterTownId = 0
WHERE characterId= :characterId");

$updatecharacter->execute(array(
'characterId' => $characterId));

header("Location: ../../modules/map/index.php");

require_once("../../html/footer.php"); ?>